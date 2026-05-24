@extends('layouts.app')

@section('title', 'Dashboard Admin - FILKOM Sebaya')

@push('styles')
    @vite(['resources/css/admin.css'])
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
                <a href="{{ route('admin.dashboard') }}" class="navbar-link active">Dashboard</a>
                <a href="{{ route('admin.users') }}" class="navbar-link">Manajemen User</a>
            </div>
        </div>
        <div class="navbar-right-actions">
            <a href="{{ route('notifications.index') }}" class="notification-bell-link" title="Notifikasi Sistem">
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
        <h1 style="font-size: 48px; font-weight: 800; color: #ffffff; margin-bottom: 12px;">Dashboard admin</h1>
        <p style="font-size: 16px; color: rgba(255, 255, 255, 0.85); max-width: 700px; margin: 0 auto; line-height: 1.6;">Pantau aktivitas sistem dan kelola pengguna FILKOM Sebaya dari sini</p>
    </section>

    <!-- Content Container -->
    <div class="portal-container">
        
        <!-- Manage User Banner Card -->
        <div class="continue-banner-card">
            <div>
                <h3 style="font-size: 20px; font-weight: 700; color: #0f2942; margin-bottom: 6px;">Kelola akun pengguna sistem</h3>
                <p style="font-size: 14px; color: #64748b;">Atur data mahasiswa dan peer counselor dalam FILKOM Sebaya</p>
            </div>
            <div style="flex-shrink: 0;">
                <a href="{{ route('admin.users') }}" class="primary-button" style="background-color: #0f2942; background-image: none; box-shadow: none; border-radius: 4px; padding: 12px 28px; font-size: 13px; font-weight: 700;">Kelola Pengguna</a>
            </div>
        </div>

        <!-- Split Stats Panel -->
        <div class="split-dashboard-grid">
            
            <!-- Left Info column -->
            <div style="text-align: left; display: flex; flex-direction: column; gap: 16px; padding-top: 20px;">
                <h2 style="font-size: 36px; font-weight: 800; color: #0f2942; line-height: 1.2;">Jumlah pengguna terdaftar dalam sistem</h2>
                <p style="font-size: 15px; color: #64748b; line-height: 1.6; margin-bottom: 16px;">Lihat distribusi mahasiswa dan peer counselor yang aktif menggunakan FILKOM Sebaya. Data ini mencerminkan partisipasi pengguna dalam sistem konseling sebaya.</p>
                <div>
                    <button onclick="window.location.reload();" class="secondary-button" style="border-radius: 4px; padding: 12px 32px; font-size: 13px; border: 1.5px solid #cbd5e1; color: #0f2942; font-weight: 700;">Perbarui</button>
                </div>
            </div>

            <!-- Right stats card column (2x2 Grid) -->
            <div class="admin-stats-cards-grid">
                <!-- Card 1: Total Pengguna -->
                <div class="admin-stat-card">
                    <span class="admin-stat-label-top">Total pengguna sistem</span>
                    <span class="admin-stat-number">{{ $countKonseli + $countCounselor + $countAdmin }}</span>
                    <span class="admin-stat-desc-bottom">Mahasiswa dan peer counselor yang terdaftar</span>
                </div>

                <!-- Card 2: Mahasiswa Aktif -->
                <div class="admin-stat-card">
                    <span class="admin-stat-label-top">Mahasiswa aktif</span>
                    <span class="admin-stat-number">{{ $countKonseli }}</span>
                    <span class="admin-stat-desc-bottom">Pengguna yang mengajukan atau menerima konseling</span>
                </div>

                <!-- Card 3: Peer Counselor Aktif -->
                <div class="admin-stat-card">
                    <span class="admin-stat-label-top">Peer counselor aktif</span>
                    <span class="admin-stat-number">{{ $countCounselor }}</span>
                    <span class="admin-stat-desc-bottom">Konselor yang menangani pengajuan konseling</span>
                </div>

                <!-- Card 4: Admin Sistem -->
                <div class="admin-stat-card">
                    <span class="admin-stat-label-top">Admin sistem</span>
                    <span class="admin-stat-number">{{ $countAdmin }}</span>
                    <span class="admin-stat-desc-bottom">Pengelola dan pengawas platform FILKOM Sebaya</span>
                </div>
            </div>

        </div>

    </div>

    <!-- Footer -->
    <footer style="background-color: #ffffff; padding: 40px 120px; border-top: 1.5px solid #f1f5f9; text-align: center; font-size: 13px; color: #94a3b8; font-weight: 500; margin-top: 80px;">
        &copy; 2025 FILKOM Sebaya
    </footer>
@endsection
