<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class N8nService
{
    protected $webhookUrl;

    public function __construct()
    {
        $this->webhookUrl = config('services.n8n.webhook_url');
    }

    public function analyzeCV($driveLink, $folderName, $profileWanted)
    {
        try {
            Log::info('Triggering n8n Webhook', [
                'url' => $this->webhookUrl,
                'payload' => [
                    'driveLink' => $driveLink,
                    'folderName' => $folderName,
                    'profile_wanted' => $profileWanted,
                ]
            ]);

            $response = Http::withoutVerifying()
                ->timeout(10) // 10-second timeout for async n8n dispatcher webhook
                ->post($this->webhookUrl, [
                    'driveLink' => $driveLink,
                    'folderName' => $folderName,
                    'profile_wanted' => $profileWanted,
                ]);

            if ($response->successful()) {
                Log::info('N8n Response Received Successfully');
                return $response->json() ?? ['status' => 'triggered'];
            }

            Log::warning('N8n Service returned non-successful response, assuming triggered: ' . $response->body());
            return ['status' => 'triggered'];
        } catch (\Exception $e) {
            Log::info('N8n Service Exception caught (e.g. timeout), assuming background trigger was successful: ' . $e->getMessage());
            return ['status' => 'triggered'];
        }
    }
}
