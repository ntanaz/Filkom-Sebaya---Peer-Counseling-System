@extends('layouts.app')

@section('title', 'Dashboard Konseli - FILKOM Sebaya')

@push('styles')
    @vite(['resources/css/konseli.css'])
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
                <a href="{{ route('konseli.dashboard') }}" class="navbar-link active">Dashboard</a>
                <a href="{{ route('konseli.create') }}" class="navbar-link">Ajukan Konseling</a>
                <a href="{{ route('konseli.history') }}" class="navbar-link">Riwayat</a>
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
        <h1 style="font-size: 48px; font-weight: 800; color: #ffffff; margin-bottom: 12px;">Halo, mahasiswa</h1>
        <p style="font-size: 16px; color: rgba(255, 255, 255, 0.85); max-width: 700px; margin: 0 auto; line-height: 1.6;">Pantau pengajuan konseling anda dan lihat perkembangan setiap laporan yang telah diajukan</p>
    </section>

    <!-- Content Container -->
    <div class="portal-container">
        
        <!-- Continue Action Banner Card -->
        <div class="continue-banner-card">
            <div>
                <h3 style="font-size: 20px; font-weight: 700; color: #0f2942; margin-bottom: 6px;">Lanjutkan pengajuan anda</h3>
                <p style="font-size: 14px; color: #64748b;">Buat laporan konseling baru atau telusuri semua pengajuan yang sudah pernah anda buat</p>
            </div>
            <div style="display: flex; gap: 12px; flex-shrink: 0;">
                <a href="{{ route('konseli.create') }}" class="primary-button" style="background-color: #0f2942; background-image: none; box-shadow: none; border-radius: 4px; padding: 12px 28px; font-size: 13px;">Ajukan</a>
                <a href="{{ route('konseli.history') }}" class="secondary-button" style="border-radius: 4px; padding: 12px 28px; font-size: 13px; border: 1.5px solid #cbd5e1; color: #0f2942; font-weight: 600;">Riwayat</a>
            </div>
        </div>

        <!-- Table Title -->
        <div style="text-align: center; margin: 56px 0 32px 0;">
            <h2 style="font-size: 28px; font-weight: 800; color: #0f2942; margin-bottom: 8px;">Daftar Pengajuan anda</h2>
            <p style="font-size: 14px; color: #64748b;">Pantau semua pengajuan konseling anda di sini</p>
        </div>

        <!-- Table Card -->
        <div class="table-card">
            <table class="dashboard-table">
                <thead>
                    <tr>
                        <th style="width: 35%;">Judul pengajuan</th>
                        <th style="width: 25%;">Tanggal</th>
                        <th style="width: 25%;">Status</th>
                        <th style="width: 15%;">Detail</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($activeRequests as $request)
                        <tr>
                            <td style="font-weight: 700; color: #0f2942;">{{ $request->topic }}</td>
                            <td style="color: #64748b;">{{ $request->created_at->format('d F Y') }}</td>
                            <td>
                                <span class="status-badge {{ $request->status }}">{{ $request->status }}</span>
                            </td>
                            <td>
                                <a href="{{ route('konseli.detail', $request->request_id) }}" class="detail-link" style="color: #0f2942; font-weight: 700; text-decoration: underline;">Lihat Detail</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" style="text-align: center; color: #94a3b8; padding: 48px;">
                                Belum ada data pengajuan aktif saat ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- See All Link -->
        <div style="text-align: center; margin-top: 32px;">
            <a href="{{ route('konseli.history') }}" class="secondary-button" style="border-radius: 4px; padding: 12px 32px; font-size: 13px; border: 1.5px solid #e2e8f0; color: #0f2942; font-weight: 700;">Lihat Selengkapnya</a>
        </div>

    </div>

    <!-- Footer -->
    <footer style="background-color: #ffffff; padding: 40px 120px; border-top: 1.5px solid #f1f5f9; text-align: center; font-size: 13px; color: #94a3b8; font-weight: 500; margin-top: 80px;">
        &copy; 2025 FILKOM Sebaya
    </footer>
@endsection
