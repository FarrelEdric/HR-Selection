<?php

namespace Database\Seeders;

use App\Models\Job;
use Illuminate\Database\Seeder;

class JobSeeder extends Seeder
{
    public function run(): void
    {
        Job::create([
            'title' => 'Senior Backend Developer (Laravel)',
            'location' => 'Jakarta / Remote',
            'description' => "Kami mencari Senior Backend Developer yang ahli dalam Laravel untuk membangun sistem skala besar.\n\nResponsibilitas:\n- Merancang dan mengimplementasikan API yang efisien.\n- Mengoptimalkan database dan query.\n- Mentoring developer junior.",
            'qualification' => "- Pengalaman minimal 4 tahun dengan Laravel.\n- Menguasai MySQL, Redis, dan Docker.\n- Memahami arsitektur Microservices.",
            'benefit' => "- Gaji Kompetitif\n- Asuransi Kesehatan Premium\n- WFH Friendly\n- MacBook Pro untuk bekerja",
        ]);

        Job::create([
            'title' => 'UI/UX Designer',
            'location' => 'Bandung / Hybrid',
            'description' => "Bergabunglah dengan tim kreatif kami untuk menciptakan pengalaman pengguna yang memukau.\n\nResponsibilitas:\n- Membuat wireframe dan prototype high-fidelity.\n- Melakukan user research.\n- Kolaborasi dengan tim engineering.",
            'qualification' => "- Mahir menggunakan Figma dan Adobe Creative Suite.\n- Memiliki portfolio desain yang kuat.\n- Memahami prinsip User-Centered Design.",
            'benefit' => "- Lingkungan kerja kreatif\n- Tunjangan pengembangan diri\n- Bonus tahunan",
        ]);
        
        Job::create([
            'title' => 'Fullstack Developer',
            'location' => 'Jakarta',
            'description' => "Dibutuhkan Fullstack Developer yang gesit untuk membangun platform HR inovatif kami.\n\nResponsibilitas:\n- Membangun frontend menggunakan Tailwind CSS & React/Vue.\n- Membangun backend dengan Laravel.",
            'qualification' => "- Menguasai PHP dan JavaScript.\n- Familiar dengan Tailwind CSS.\n- Bisa bekerja secara mandiri maupun tim.",
            'benefit' => "- Kantor strategis\n- Makan siang gratis\n- Jenjang karir yang jelas",
        ]);
    }
}
