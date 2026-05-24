@extends('layouts.app')

@section('title', 'Manajemen Sesi - FILKOM Sebaya')

@push('styles')
    @vite(['resources/css/counselor.css'])
@endpush

@section('content')
    <!-- Horizontal Topbar Navbar -->
    <nav class="navbar-container">
        <div class="navbar-brand-group">
            <div class="navbar-logo-wrap">
                <img src="{{ asset('images/logo-icon.png') }}" alt="Logo" class="navbar-logo-img">
                <span class="navbar-logo-text">FILKOM Sebaya</span>
            </div>
            <div class="navbar-menu">
                <a href="{{ route('counselor.dashboard') }}" class="navbar-link">Dashboard</a>
                <a href="{{ route('counselor.requests') }}" class="navbar-link">Permintaan Masuk</a>
                <a href="{{ route('counselor.sessions') }}" class="navbar-link active">Manajemen Sesi</a>
            </div>
        </div>
        <div class="navbar-right-actions">
            <a href="{{ route('notifications.index') }}" class="notification-bell-link" title="Notifikasi">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" class="icon-20">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0"></path>
                </svg>
                @php
                    $unreadNotificationsCount = \App\Models\Notification::where('user_id', Auth::id())->where('is_read', false)->count();
                @endphp
                @if($unreadNotificationsCount > 0)
                    <span class="notification-bell-badge">
                        {{ $unreadNotificationsCount }}
                    </span>
                @endif
            </a>
            <form action="{{ route('logout') }}" method="POST" class="logout-form">
                @csrf
                <button type="submit" class="navbar-logout-btn">Keluar</button>
            </form>
        </div>
    </nav>



    <!-- Top Banner Jumbotron (Full Width) -->
    <section class="jumbotron-banner">
        <h1 style="font-size: 48px; font-weight: 800; color: #ffffff; margin-bottom: 12px;">Manajemen Sesi Aktif</h1>
        <p style="font-size: 16px; color: rgba(255, 255, 255, 0.85); max-width: 700px; margin: 0 auto; line-height: 1.6;">Atur jadwal pertemuan atau selesaikan bimbingan dengan membuat laporan bimbingan</p>
    </section>

    <!-- Content Container -->
    <div class="portal-container">
        
        <div class="session-management">
            <h3 style="font-size: 18px; font-weight: 800; color: #0f2942; margin-bottom: 24px; border-bottom: 1.5px solid #f1f5f9; padding-bottom: 12px;">Daftar Sesi Konseling Aktif</h3>

            <div class="sessions-grid">
                @forelse($sessions as $session)
                    <div class="session-card">
                        
                        <div class="session-header">
                            <div class="session-client-info">
                                <div class="session-client-avatar">
                                    {{ strtoupper(substr($session->konseli->name, 0, 2)) }}
                                </div>
                                <div>
                                    <div class="session-client-name">{{ $session->konseli->name }}</div>
                                    <div class="session-client-nim">NIM: {{ $session->konseli->nim }}</div>
                                </div>
                            </div>
                            <span class="status-badge {{ $session->status }}">{{ $session->status }}</span>
                        </div>

                        <div>
                            <span style="font-size: 11px; text-transform: uppercase; font-weight: 700; color: var(--text-light); display: block; margin-bottom: 4px;">Topik Bimbingan</span>
                            <div class="session-topic">{{ $session->topic }}</div>
                            <span style="font-size: 13px; color: var(--text-muted); display: block; margin-top: 6px;">Kategori: <strong>{{ $session->category }}</strong></span>
                        </div>

                        <!-- Schedule Info -->
                        @if($session->schedule)
                            <div class="session-meta-info">
                                <div style="display: flex; align-items: center; gap: 8px; font-weight: 700; color: #0f2942;">
                                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" style="width: 16px; height: 16px; display: inline-block; vertical-align: middle;"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg> Jadwal Sesi: {{ $session->schedule->date }} pukul {{ $session->schedule->time }}
                                </div>
                            </div>
                        @else
                            <div class="session-meta-info" style="color: var(--warning); font-weight: 600; display: flex; align-items: center; gap: 6px;">
                                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" style="width: 16px; height: 16px; display: inline-block; vertical-align: middle;"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg> Pilih waktu konseling yang sesuai.
                            </div>
                        @endif

                        <!-- Contextual Form Actions based on Status -->
                        @if($session->status === 'diproses')
                            <!-- Form to Schedule Session -->
                            <form action="{{ route('counselor.schedule', $session->request_id) }}" method="POST" class="schedule-inline-form">
                                @csrf
                                <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 10px; margin-bottom: 12px;">
                                    <div class="form-group" style="margin-bottom: 0;">
                                        <label for="date-{{ $session->request_id }}" style="font-size: 11px; font-weight: 700; color: #0f2942;">Tanggal Sesi</label>
                                        <input type="date" name="date" id="date-{{ $session->request_id }}" class="input-field" style="padding: 8px; font-size: 12px; border-radius: 4px; border: 1.5px solid #cbd5e1;" required>
                                    </div>
                                    <div class="form-group" style="margin-bottom: 0;">
                                        <label for="time-{{ $session->request_id }}" style="font-size: 11px; font-weight: 700; color: #0f2942;">Waktu Sesi</label>
                                        <input type="time" name="time" id="time-{{ $session->request_id }}" class="input-field" style="padding: 8px; font-size: 12px; border-radius: 4px; border: 1.5px solid #cbd5e1;" required>
                                    </div>
                                </div>
                                <button type="submit" class="primary-button" style="width: 100%; padding: 10px; font-size: 12px; background-color: #0f2942; background-image: none; box-shadow: none; border-radius: 4px; font-weight: 700;">Tentukan Jadwal</button>
                            </form>
                        @elseif($session->status === 'dijadwalkan')
                            <!-- Form to Complete Session and Write Report -->
                            <div style="border-top: 1px solid var(--border-color); padding-top: 16px; margin-top: 8px;">
                                <h4 style="font-size: 13px; font-weight: 800; color: var(--primary-green-dark); margin-bottom: 12px; display: flex; align-items: center; gap: 6px;">
                                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" style="width: 16px; height: 16px;"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                    Selesaikan & Tulis Laporan
                                </h4>
                                
                                <form action="{{ route('counselor.complete', $session->request_id) }}" method="POST" style="display: flex; flex-direction: column; gap: 12px;">
                                    @csrf

                                    <div class="form-group" style="margin-bottom: 0; gap: 4px;">
                                        <label for="case-{{ $session->request_id }}" style="font-size: 11px; font-weight: 700; color: #0f2942;">Tingkat Kerawanan Kasus</label>
                                        <select name="case_level" id="case-{{ $session->request_id }}" class="input-field" style="padding: 8px; font-size: 12px; border-radius: 4px; border: 1.5px solid #cbd5e1; cursor: pointer;" required>
                                            <option value="">-- Pilih Tingkat --</option>
                                            <option value="Ringan">Ringan (Masalah akademik, motivasi)</option>
                                            <option value="Sedang">Sedang (Kecemasan tinggi, stres adaptasi)</option>
                                            <option value="Berat">Berat (Memerlukan rujukan psikolog profesional)</option>
                                        </select>
                                    </div>

                                    <div class="form-group" style="margin-bottom: 0; gap: 4px;">
                                        <label for="summary-{{ $session->request_id }}" style="font-size: 11px; font-weight: 700; color: #0f2942;">Ringkasan Konseling</label>
                                        <textarea name="summary" id="summary-{{ $session->request_id }}" class="input-field" rows="2" style="padding: 8px; font-size: 12px; resize: vertical; border-radius: 4px; border: 1.5px solid #cbd5e1; font-family: inherit;" placeholder="Tulis jalannya konseling..." required></textarea>
                                    </div>

                                    <div class="form-group" style="margin-bottom: 0; gap: 4px;">
                                        <label for="rec-{{ $session->request_id }}" style="font-size: 11px; font-weight: 700; color: #0f2942;">Rekomendasi Tindak Lanjut</label>
                                        <textarea name="recommendation" id="rec-{{ $session->request_id }}" class="input-field" rows="2" style="padding: 8px; font-size: 12px; resize: vertical; border-radius: 4px; border: 1.5px solid #cbd5e1; font-family: inherit;" placeholder="Tulis saran tindak lanjut..." required></textarea>
                                    </div>

                                    <button type="submit" class="primary-button" style="width: 100%; padding: 10px; font-size: 13px; background-color: var(--primary-green); background-image: none; box-shadow: none; border-radius: 4px; font-weight: 700;">Simpan & Selesaikan Sesi</button>
                                </form>
                            </div>
                        @endif

                        <div class="session-action-group" style="justify-content: flex-end; margin-top: auto; border-top: 1px solid #f1f5f9; padding-top: 12px;">
                            <a href="{{ route('counselor.detail', $session->request_id) }}" class="secondary-button" style="padding: 6px 12px; font-size: 12px; width: 100%; text-align: center; border-radius: 4px; border: 1.5px solid #cbd5e1; color: #0f2942; font-weight: 700;">Lihat Timeline Detail &rarr;</a>
                        </div>

                    </div>
                @empty
                    <div class="empty-state" style="grid-column: 1 / -1; width: 100%; padding: 56px 0;">
                        <div class="empty-state-icon" style="color: var(--text-light); display: flex; justify-content: center; margin-bottom: 12px;">
                            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" style="width: 48px; height: 48px;"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        </div>
                        <p style="font-weight: 500; color: #64748b;">Belum ada data tersedia.</p>
                        <p style="font-size: 13px; color: #94a3b8;">Anda tidak memiliki sesi konseling aktif yang sedang dikelola saat ini.</p>
                    </div>
                @endforelse
            </div>
        </div>

    </div>

    <!-- Footer -->
    <footer style="background-color: #ffffff; padding: 40px 120px; border-top: 1.5px solid #f1f5f9; text-align: center; font-size: 13px; color: #94a3b8; font-weight: 500; margin-top: 80px;">
        &copy; 2025 FILKOM Sebaya
    </footer>
@endsection
