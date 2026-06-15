<?php

namespace App\Jobs;

use App\Services\N8nService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class AnalyzeCvJob implements ShouldQueue
{
    use Queueable;

    protected $driveLink;
    protected $folderName;
    protected $profileWanted;

    /**
     * Create a new job instance.
     */
    public function __construct($driveLink, $folderName, $profileWanted)
    {
        $this->driveLink = $driveLink;
        $this->folderName = $folderName;
        $this->profileWanted = $profileWanted;
    }

    /**
     * Execute the job.
     */
    public function handle(N8nService $n8nService): void
    {
        Log::info('Executing background job for CV analysis');
        
        $n8nService->analyzeCV(
            $this->driveLink,
            $this->folderName,
            $this->profileWanted
        );
        
        Log::info('Background job finished triggering n8n');
    }
}
