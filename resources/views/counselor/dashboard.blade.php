@extends('layouts.app')

@section('title', 'Dashboard Konselor - FILKOM Sebaya')

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
                <a href="{{ route('counselor.dashboard') }}" class="navbar-link active">Dashboard</a>
                <a href="{{ route('counselor.requests') }}" class="navbar-link">Permintaan Masuk</a>
                <a href="{{ route('counselor.sessions') }}" class="navbar-link">Manajemen Sesi</a>
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
        <h1 style="font-size: 48px; font-weight: 800; color: #ffffff; margin-bottom: 12px;">Dashboard Konselor</h1>
        <p style="font-size: 16px; color: rgba(255, 255, 255, 0.85); max-width: 700px; margin: 0 auto; line-height: 1.6;">Kelola data konseling sebaya dan laporan bimbingan mahasiswa</p>
    </section>

    <!-- Content Container -->
    <div class="portal-container">
        
        <!-- Metrics Grid -->
        <div class="metrics-grid">
            <div class="metric-card">
                <div class="metric-icon">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" style="width: 24px; height: 24px;"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                </div>
                <div class="metric-details">
                    <span class="metric-number">{{ $totalSessions }}</span>
                    <span class="metric-label">Total Sesi</span>
                </div>
            </div>
            <div class="metric-card">
                <div class="metric-icon yellow">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" style="width: 24px; height: 24px;"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <div class="metric-details">
                    <span class="metric-number">{{ $activeSessions }}</span>
                    <span class="metric-label">Sesi Aktif</span>
                </div>
            </div>
            <div class="metric-card">
                <div class="metric-icon green">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" style="width: 24px; height: 24px;"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <div class="metric-details">
                    <span class="metric-number">{{ $completedSessions }}</span>
                    <span class="metric-label">Sesi Selesai</span>
                </div>
            </div>
            <div class="metric-card">
                <div class="metric-icon purple">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" style="width: 24px; height: 24px;"><path stroke-linecap="round" stroke-linejoin="round" d="M8 4H6a2 2 0 00-2 2v12a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-2m-4-1v8m0 0l3-3m-3 3L9 8m-5 5h2.586a1 1 0 01.707.293l2.414 2.414a1 1 0 00.707.293h3.172a1 1 0 00.707-.293l2.414-2.414a1 1 0 01.707-.293H20"></path></svg>
                </div>
                <div class="metric-details">
                    <span class="metric-number">{{ $pendingRequests }}</span>
                    <span class="metric-label">Permintaan</span>
                </div>
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1.8fr 1.2fr; gap: 32px; align-items: start;">
            
            <!-- Active Sessions List -->
            <div class="card-container">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; border-bottom: 1.5px solid #f1f5f9; padding-bottom: 12px;">
                    <h3 style="font-size: 18px; font-weight: 800; color: #0f2942;">Sesi Konseling Aktif Saat Ini</h3>
                    <a href="{{ route('counselor.sessions') }}" style="font-size: 13px; color: #0f2942; font-weight: 700; text-decoration: underline;">Kelola Semua &rarr;</a>
                </div>

                <div style="display: flex; flex-direction: column; gap: 16px;">
                    @forelse($recentRequests as $request)
                        <div style="display: flex; justify-content: space-between; align-items: center; padding: 16px; border: 1.5px solid #e2e8f0; border-radius: 6px; background-color: #ffffff;">
                            <div>
                                <span style="font-weight: 700; font-size: 15px; color: #0f2942; display: block; margin-bottom: 4px;">{{ $request->topic }}</span>
                                <div style="display: flex; align-items: center; gap: 12px; font-size: 12px; color: var(--text-muted);">
                                    <span>Konseli: {{ $request->konseli->name }} ({{ $request->konseli->nim }})</span>
                                    <span>•</span>
                                    <span class="status-badge {{ $request->status }}" style="font-size: 10px; padding: 2px 8px;">{{ $request->status }}</span>
                                </div>
                            </div>
                            <div style="display: flex; gap: 8px;">
                                <a href="{{ route('counselor.detail', $request->request_id) }}" class="secondary-button" style="padding: 6px 12px; font-size: 12px; border-radius: 4px; border: 1.5px solid #cbd5e1; color: #0f2942; font-weight: 700;">Detail</a>
                            </div>
                        </div>
                    @empty
                        <div class="empty-state" style="padding: 32px 0;">
                            <div class="empty-state-icon" style="color: var(--text-light); display: flex; justify-content: center; margin-bottom: 12px;">
                                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" style="width: 48px; height: 48px;"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            </div>
                            <p style="font-weight: 500; color: #64748b;">Belum ada data sesi aktif.</p>
                            <p style="font-size: 13px; color: #94a3b8;">Anda tidak memiliki sesi konseling aktif yang sedang ditangani saat ini.</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Counselor Rules / Guidelines Card -->
            <div style="display: flex; flex-direction: column; gap: 24px;">
                <div class="card-container" style="background-color: #e0f2fe; border-color: rgba(2, 132, 199, 0.1);">
                    <h4 style="color: #0369a1; font-weight: 700; margin-bottom: 8px;">Kode Etik Peer Counselor</h4>
                    <p style="font-size: 13px; color: #0369a1; opacity: 0.9; line-height: 1.5;">Sebagai peer counselor, Anda wajib menjaga kerahasiaan seluruh data konseli. Jangan pernah mendiskusikan identitas atau detail masalah konseli di luar lingkungan formal pengawasan psikolog.</p>
                </div>

                <div class="card-container">
                    <h3 style="font-size: 16px; font-weight: 700; margin-bottom: 16px; display: flex; align-items: center; gap: 8px; color: #0f2942; border-bottom: 1.5px solid #f1f5f9; padding-bottom: 12px;">
                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" style="width: 18px; height: 18px; color: #0f2942;"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg> Langkah Cepat
                    </h3>
                    <div style="display: flex; flex-direction: column; gap: 10px;">
                        <a href="{{ route('counselor.requests') }}" class="primary-button" style="width: 100%; font-size: 13px; padding: 10px; background-color: #0f2942; background-image: none; box-shadow: none; border-radius: 4px; font-weight: 700;">Lihat Permintaan Masuk</a>
                        <a href="{{ route('counselor.sessions') }}" class="secondary-button" style="width: 100%; font-size: 13px; padding: 10px; border-radius: 4px; border: 1.5px solid #cbd5e1; color: #0f2942; font-weight: 700;">Kelola Jadwal & Sesi</a>
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
