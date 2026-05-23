@extends('layouts.app')

@section('title', 'Ajukan Konseling - FILKOM Sebaya')

@push('styles')
    @vite(['resources/css/konseli.css'])
@endpush

@section('content')
    <!-- Horizontal Topbar Navbar -->
    <nav class="navbar-container">
        <div style="display: flex; align-items: center; gap: 40px;">
            <div class="navbar-logo" style="display: flex; align-items: center; gap: 10px;">
                <img src="{{ asset('images/logo-icon.png') }}" alt="Logo" style="height: 32px; border-radius: 6px;">
                <span style="font-weight: 700; color: #0f2942; font-size: 18px;">FILKOM Sebaya</span>
            </div>
            <div class="navbar-menu" style="display: flex; gap: 28px;">
                <a href="{{ route('konseli.dashboard') }}" class="navbar-link">Dashboard</a>
                <a href="{{ route('konseli.create') }}" class="navbar-link active">Ajukan Konseling</a>
                <a href="{{ route('konseli.history') }}" class="navbar-link">Riwayat</a>
            </div>
        </div>
        <div>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="secondary-button" style="border-radius: 6px; padding: 8px 24px; font-size: 13px; color: #0f2942; border: 1.5px solid #e2e8f0; font-weight: 600; background-color: #ffffff;">Keluar</button>
            </form>
        </div>
    </nav>

    <!-- Top Banner Jumbotron (Full Width) -->
    <section class="jumbotron-banner">
        <h1 style="font-size: 48px; font-weight: 800; color: #ffffff; margin-bottom: 12px;">Ajukan konseling</h1>
        <p style="font-size: 16px; color: rgba(255, 255, 255, 0.85); max-width: 700px; margin: 0 auto; line-height: 1.6;">Isi formulir berikut untuk mengajukan permintaan konseling. Data Anda akan diproses secara terstruktur oleh peer counselor dan bersifat rahasia.</p>
    </section>

    <!-- Content Container -->
    <div class="portal-container" style="max-width: 800px;">
        
        <!-- Form Title Group -->
        <div style="margin-bottom: 32px; text-align: left;">
            <h2 style="font-size: 24px; font-weight: 800; color: #0f2942; margin-bottom: 6px;">Detail pengajuan</h2>
            <p style="font-size: 14px; color: #64748b;">Jelaskan permasalahan Anda secara jelas agar dapat diproses dengan tepat</p>
        </div>

        <!-- Form Submission Card -->
        <div class="form-card" style="background-color: #ffffff; border: 1px solid var(--border-color); border-radius: var(--radius-md); padding: 40px; box-shadow: var(--shadow-card); margin-bottom: 56px;">
            <form action="{{ route('konseli.store') }}" method="POST">
                @csrf

                <!-- Grid Group -->
                <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 24px; margin-bottom: 24px;">
                    <!-- Topic -->
                    <div class="form-group" style="margin-bottom: 0;">
                        <label for="topic" style="font-weight: 600; color: #0f2942;">Judul pengajuan</label>
                        <input type="text" name="topic" id="topic" class="input-field" placeholder="Contoh: Kesulitan Membagi Waktu Kuliah" value="{{ old('topic') }}" required style="border-radius: 4px; border: 1.5px solid #cbd5e1;">
                    </div>

                    <!-- Category -->
                    <div class="form-group" style="margin-bottom: 0;">
                        <label for="category" style="font-weight: 600; color: #0f2942;">Pilih kategori</label>
                        <select name="category" id="category" class="input-field" required style="cursor: pointer; border-radius: 4px; border: 1.5px solid #cbd5e1;">
                            <option value="">-- Pilih Kategori --</option>
                            <option value="Akademik">Akademik</option>
                            <option value="Sosial">Sosial</option>
                            <option value="Pribadi">Pribadi</option>
                            <option value="Keluarga">Keluarga</option>
                            <option value="Lainnya">Lainnya</option>
                        </select>
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 24px; margin-bottom: 24px;">
                    <!-- Counselor Option -->
                    <div class="form-group" style="margin-bottom: 0;">
                        <label for="konselor_id" style="font-weight: 600; color: #0f2942;">Pilih salah satu</label>
                        <select name="konselor_id" id="konselor_id" class="input-field" style="cursor: pointer; border-radius: 4px; border: 1.5px solid #cbd5e1;">
                            <option value="">-- Biarkan Sistem Memilihkan (Otomatis) --</option>
                            @foreach($counselors as $counselor)
                                <option value="{{ $counselor->user_id }}">{{ $counselor->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Phone Number -->
                    <div class="form-group" style="margin-bottom: 0;">
                        <label for="phone" style="font-weight: 600; color: #0f2942;">Nomor telepon</label>
                        <input type="text" name="phone" id="phone" class="input-field" placeholder="Contoh: 08123456789" value="{{ Auth::user()->phone }}" style="border-radius: 4px; border: 1.5px solid #cbd5e1;">
                    </div>
                </div>

                <!-- Select main topic / category group -->
                <div class="form-group" style="margin-bottom: 32px;">
                    <label for="main_topic" style="font-weight: 600; color: #0f2942;">Pilih topik utama</label>
                    <select name="main_topic" id="main_topic" class="input-field" required style="cursor: pointer; border-radius: 4px; border: 1.5px solid #cbd5e1;">
                        <option value="">-- Pilih Topik Utama Bimbingan --</option>
                        <option value="Akademik - Manajemen Waktu & Tugas">Akademik - Manajemen Waktu & Tugas</option>
                        <option value="Sosial - Masalah Pertemanan & Adaptasi">Sosial - Masalah Pertemanan & Adaptasi</option>
                        <option value="Pribadi - Stres & Kecemasan Berlebih">Pribadi - Stres & Kecemasan Berlebih</option>
                        <option value="Keluarga - Masalah Komunikasi Rumah">Keluarga - Masalah Komunikasi Rumah</option>
                        <option value="Lainnya">Lainnya</option>
                    </select>
                </div>

                <!-- Checkbox grid group: Apa yang paling mengganggu Anda -->
                <div style="margin-bottom: 32px;">
                    <label style="font-weight: 600; color: #0f2942; display: block; margin-bottom: 16px;">Apa yang paling mengganggu Anda</label>
                    <div class="checkbox-grid">
                        <label class="checkbox-item">
                            <input type="checkbox" name="annoyances[]" value="Masalah akademik" style="width: 16px; height: 16px;">
                            <span>Masalah akademik</span>
                        </label>
                        <label class="checkbox-item">
                            <input type="checkbox" name="annoyances[]" value="Kesulitan pribadi" style="width: 16px; height: 16px;">
                            <span>Kesulitan pribadi</span>
                        </label>
                        <label class="checkbox-item">
                            <input type="checkbox" name="annoyances[]" value="Hubungan sosial" style="width: 16px; height: 16px;">
                            <span>Hubungan sosial</span>
                        </label>
                        <label class="checkbox-item">
                            <input type="checkbox" name="annoyances[]" value="Perencanaan karir" style="width: 16px; height: 16px;">
                            <span>Perencanaan karir</span>
                        </label>
                        <label class="checkbox-item">
                            <input type="checkbox" name="annoyances[]" value="Kesehatan mental" style="width: 16px; height: 16px;">
                            <span>Kesehatan mental</span>
                        </label>
                        <label class="checkbox-item">
                            <input type="checkbox" name="annoyances[]" value="Lainnya" style="width: 16px; height: 16px;">
                            <span>Lainnya</span>
                        </label>
                    </div>
                </div>

                <!-- Explanation Area -->
                <div class="form-group" style="margin-bottom: 24px;">
                    <label for="description" style="font-weight: 600; color: #0f2942;">Penjelasan</label>
                    <textarea name="description" id="description" class="input-field" rows="6" placeholder="Ceritakan situasi Anda secara detail..." required style="resize: vertical; border-radius: 4px; border: 1.5px solid #cbd5e1; font-family: inherit;"></textarea>
                </div>

                <!-- Problem Detail Hidden (For DB consistency, let's copy description value to problem_description on submission if needed, or keep standard) -->
                <input type="hidden" name="problem_description" id="problem_description">
                <script>
                    document.getElementById('description').addEventListener('input', function() {
                        document.getElementById('problem_description').value = this.value;
                    });
                </script>

                <!-- Checkbox Confirmation -->
                <label style="display: flex; align-items: center; gap: 8px; font-size: 13px; color: #64748b; margin-bottom: 32px; cursor: pointer;">
                    <input type="checkbox" required style="width: 16px; height: 16px;">
                    <span>Saya memastikan data benar</span>
                </label>

                <!-- Action Button -->
                <div>
                    <button type="submit" class="primary-button" style="background-color: #0f2942; background-image: none; border-radius: 4px; padding: 12px 40px; box-shadow: none; font-size: 14px; font-weight: 700;">Kirim</button>
                </div>

            </form>
        </div>

        <!-- Horizontal Timeline: Apa yang terjadi setelah pengajuan dikirim -->
        <div style="margin-bottom: 56px;">
            <h3 style="font-size: 24px; font-weight: 800; color: #0f2942; text-align: center; margin-bottom: 40px;">Apa yang terjadi setelah pengajuan dikirim</h3>
            
            <div class="horizontal-timeline-container">
                <div class="timeline-line"></div>
                <div class="horizontal-timeline">
                    <!-- Step 1 -->
                    <div class="timeline-item">
                        <div class="timeline-bullet-circle active">
                            <!-- Hourglass SVG -->
                            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" style="width: 24px; height: 24px;"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path></svg>
                        </div>
                        <h4 style="font-size: 16px; font-weight: 700; color: #0f2942; margin: 16px 0 8px 0;">Menunggu</h4>
                        <p style="font-size: 12px; color: #64748b; line-height: 1.5;">Sistem menerima pengajuan Anda dan memasukkan ke antrean untuk ditinjau.</p>
                    </div>

                    <!-- Step 2 -->
                    <div class="timeline-item">
                        <div class="timeline-bullet-circle">
                            <!-- Refresh SVG -->
                            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" style="width: 24px; height: 24px;"><path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 1121.21 7.89M9 11l3-3 3 3m0 2l-3 3-3-3"></path></svg>
                        </div>
                        <h4 style="font-size: 16px; font-weight: 700; color: #64748b; margin: 16px 0 8px 0;">Diproses</h4>
                        <p style="font-size: 12px; color: #94a3b8; line-height: 1.5;">Peer counselor membaca dan mengevaluasi permintaan untuk penanganan selanjutnya.</p>
                    </div>

                    <!-- Step 3 -->
                    <div class="timeline-item">
                        <div class="timeline-bullet-circle">
                            <!-- Check SVG -->
                            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" style="width: 24px; height: 24px;"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path></svg>
                        </div>
                        <h4 style="font-size: 16px; font-weight: 700; color: #64748b; margin: 16px 0 8px 0;">Selesai</h4>
                        <p style="font-size: 12px; color: #94a3b8; line-height: 1.5;">Proses konseling ditutup dan ringkasan hasil dikirim ke email Anda.</p>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- Footer -->
    <footer style="background-color: #ffffff; padding: 40px 120px; border-top: 1.5px solid #f1f5f9; text-align: center; font-size: 13px; color: #94a3b8; font-weight: 500; margin-top: 80px;">
        &copy; 2025 FILKOM Sebaya
    </footer>
@endsection
