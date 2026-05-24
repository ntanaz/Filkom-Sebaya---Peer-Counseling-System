<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\CounselingRequest;
use App\Models\Schedule;
use App\Models\Report;
use App\Models\Notification;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Seed Admins
        $admin = User::create([
            'name' => 'Fadhil Admin',
            'email' => 'admin@sebaya.id',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'phone' => '081122334455',
        ]);

        // 2. Seed Counselors (Konselor Sebaya)
        $counselor1 = User::create([
            'name' => 'Budi Santoso',
            'email' => 'budi.counselor@sebaya.id',
            'password' => Hash::make('password'),
            'role' => 'counselor',
            'phone' => '081234567890',
        ]);

        $counselor2 = User::create([
            'name' => 'Siti Aminah',
            'email' => 'siti.counselor@sebaya.id',
            'password' => Hash::make('password'),
            'role' => 'counselor',
            'phone' => '081345678901',
        ]);

        // 3. Seed Students (Konseli)
        $student1 = User::create([
            'name' => 'Rian Hidayat',
            'email' => 'mahasiswa1@student.ub.ac.id',
            'password' => Hash::make('password'),
            'role' => 'konseli',
            'nim' => '245150200111001',
            'phone' => '087890123456',
        ]);

        $student2 = User::create([
            'name' => 'Aisyah Putri',
            'email' => 'mahasiswa2@student.ub.ac.id',
            'password' => Hash::make('password'),
            'role' => 'konseli',
            'nim' => '245150200111002',
            'phone' => '089678901234',
        ]);

        // 4. Seed Counseling Requests with various states
        
        // --- Request 1: Menunggu (Waiting) ---
        $request1 = CounselingRequest::create([
            'konseli_id' => $student1->user_id,
            'konselor_id' => null, // completely unassigned
            'topic' => 'Kesulitan Penyesuaian Akademik Semester Awal',
            'description' => 'Saya merasa sangat tertekan dengan banyaknya tugas kuliah di FILKOM. Adaptasi dari sekolah ke universitas terasa begitu berat.',
            'status' => 'pending',
            'category' => 'Akademik',
            'problem_description' => 'Saya sering menunda pekerjaan sampai menit terakhir karena merasa cemas. Nilai kuis pertama saya di bawah rata-rata.',
        ]);

        Notification::create([
            'user_id' => $student1->user_id,
            'request_id' => $request1->request_id,
            'message' => 'Pengajuan berhasil dikirim.',
            'type' => 'status',
            'is_read' => false,
        ]);

        // --- Request 2: Diproses (Processing) ---
        $request2 = CounselingRequest::create([
            'konseli_id' => $student2->user_id,
            'konselor_id' => $counselor1->user_id,
            'topic' => 'Kecemasan Berbicara di Depan Umum (Presentasi)',
            'description' => 'Tiap kali ada tugas presentasi kelompok, saya selalu merasa mual dan gemetar hebat. Saya butuh saran bagaimana mengatasi rasa gugup ini.',
            'status' => 'accepted',
            'category' => 'Pribadi',
            'accepted_at' => now()->subHours(2),
        ]);

        Notification::create([
            'user_id' => $student2->user_id,
            'request_id' => $request2->request_id,
            'message' => 'Pengajuan konseling Anda telah diterima oleh konselor Budi Santoso.',
            'type' => 'status',
            'is_read' => false,
        ]);

        // --- Request 3: Dijadwalkan (Scheduled) ---
        $request3 = CounselingRequest::create([
            'konseli_id' => $student1->user_id,
            'konselor_id' => $counselor2->user_id,
            'topic' => 'Konflik Internal dengan Rekan Kerja Organisasi',
            'description' => 'Saya sedang memiliki selisih paham dengan salah satu pengurus harian di himpunan mahasiswa. Suasana kerja kelompok menjadi sangat tidak nyaman.',
            'status' => 'accepted',
            'category' => 'Sosial',
            'accepted_at' => now()->subDays(1),
        ]);

        Schedule::create([
            'request_id' => $request3->request_id,
            'date' => date('Y-m-d', strtotime('+2 days')),
            'time' => '13:00',
            'status' => 'confirmed',
        ]);

        Notification::create([
            'user_id' => $student1->user_id,
            'request_id' => $request3->request_id,
            'message' => 'Jadwal konseling telah diatur pada ' . date('Y-m-d', strtotime('+2 days')) . ' pukul 13:00.',
            'type' => 'schedule',
            'is_read' => false,
        ]);

        // --- Request 4: Selesai (Completed with reports) ---
        $request4 = CounselingRequest::create([
            'konseli_id' => $student2->user_id,
            'konselor_id' => $counselor1->user_id,
            'topic' => 'Masalah Manajemen Waktu (Time Management)',
            'description' => 'Saya merasa waktu 24 jam tidak pernah cukup. Saya sering telat tidur karena mengejar deadline tugas dan akhirnya telat masuk kuliah pagi.',
            'status' => 'completed',
            'category' => 'Akademik',
            'case_level' => 'Ringan',
            'accepted_at' => now()->subDays(3),
            'completed_at' => now()->subHours(5),
        ]);

        $schedule4 = Schedule::create([
            'request_id' => $request4->request_id,
            'date' => date('Y-m-d', strtotime('-1 days')),
            'time' => '09:00',
            'status' => 'completed',
        ]);

        Report::create([
            'request_id' => $request4->request_id,
            'counselor_id' => $counselor1->user_id,
            'summary' => 'Konseli mengalami ketidakseimbangan alokasi waktu karena terlalu banyak mengambil kegiatan non-akademik di luar kampus tanpa prioritas yang teratur.',
            'counseling_result' => 'Konseli bersedia mengurangi aktivitas eksternal non-akademik dan fokus pada pembagian prioritas tugas kuliah.',
            'recommendation' => 'Konseli disarankan untuk menggunakan matriks Eisenhower (mendesak vs penting) dan mencatat to-do list harian menggunakan aplikasi kalender digital.',
        ]);

        Notification::create([
            'user_id' => $student2->user_id,
            'request_id' => $request4->request_id,
            'message' => 'Sesi konseling telah selesai. Rekomendasi laporan telah dikirim.',
            'type' => 'status',
            'is_read' => true,
        ]);
    }
}
