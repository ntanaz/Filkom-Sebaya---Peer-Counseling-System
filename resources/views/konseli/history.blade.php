@extends('layouts.app')

@section('title', 'Riwayat Konseling - FILKOM Sebaya')

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
                <a href="{{ route('konseli.dashboard') }}" class="navbar-link">Dashboard</a>
                <a href="{{ route('konseli.create') }}" class="navbar-link">Ajukan Konseling</a>
                <a href="{{ route('konseli.history') }}" class="navbar-link active">Riwayat</a>
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
        <h1 style="font-size: 48px; font-weight: 800; color: #ffffff; margin-bottom: 12px;">Riwayat konseling</h1>
        <p style="font-size: 16px; color: rgba(255, 255, 255, 0.85); max-width: 700px; margin: 0 auto; line-height: 1.6;">Lihat seluruh pengajuan konseling Anda dan pantau perkembangan status setiap laporan secara terstruktur.</p>
    </section>

    <!-- Content Container -->
    <div class="portal-container" style="max-width: 900px;">
        
        <!-- Filter Tabs -->
        <div class="filter-tabs-container" style="margin-bottom: 40px; display: flex; gap: 8px;">
            <a href="{{ route('konseli.history') }}" class="tab-button {{ !request('status') ? 'active' : '' }}">Semua</a>
            <a href="{{ route('konseli.history', ['status' => 'menunggu']) }}" class="tab-button {{ request('status') === 'menunggu' ? 'active' : '' }}">Menunggu</a>
            <a href="{{ route('konseli.history', ['status' => 'diproses']) }}" class="tab-button {{ request('status') === 'diproses' ? 'active' : '' }}">Diproses</a>
            <a href="{{ route('konseli.history', ['status' => 'selesai']) }}" class="tab-button {{ request('status') === 'selesai' ? 'active' : '' }}">Selesai</a>
        </div>

        <!-- History Items List -->
        <div style="display: flex; flex-direction: column; gap: 20px;">
            @forelse($historyRequests as $request)
                <div class="history-row-card">
                    <!-- Column 1: Category -->
                    <div style="width: 20%; font-weight: 600; color: #64748b;">
                        {{ $request->category }}
                    </div>

                    <!-- Column 2: Topic & Date -->
                    <div style="width: 45%; display: flex; flex-direction: column; gap: 4px;">
                        <h4 style="font-size: 18px; font-weight: 700; color: #0f2942;">{{ $request->topic }}</h4>
                        <span style="font-size: 13px; color: #94a3b8;">{{ $request->created_at->format('d M Y') }}</span>
                    </div>

                    <!-- Column 3: Status -->
                    <div style="width: 20%;">
                        <span class="status-badge {{ $request->status }}">{{ $request->status }}</span>
                    </div>

                    <!-- Column 4: Action button -->
                    <div style="width: 15%; text-align: right;">
                        <a href="{{ route('konseli.detail', $request->request_id) }}" class="secondary-button" style="border-radius: 4px; padding: 8px 16px; font-size: 12px; border: 1.5px solid #cbd5e1; color: #0f2942; font-weight: 700; background-color: #ffffff;">Lihat detail</a>
                    </div>
                </div>
            @empty
                <div class="empty-state" style="background-color: #ffffff; border: 1px solid var(--border-color); border-radius: var(--radius-md); padding: 56px;">
                    <div class="empty-state-icon" style="color: var(--text-light); display: flex; justify-content: center; margin-bottom: 12px;">
                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" style="width: 48px; height: 48px;"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <p style="font-weight: 500;">Belum ada data tersedia.</p>
                    <p style="font-size: 13px;">Tidak ada riwayat pengajuan konseling dengan status tersebut saat ini.</p>
                </div>
            @endforelse
        </div>

    </div>

    <!-- Footer -->
    <footer style="background-color: #ffffff; padding: 40px 120px; border-top: 1.5px solid #f1f5f9; text-align: center; font-size: 13px; color: #94a3b8; font-weight: 500; margin-top: 80px;">
        &copy; 2025 FILKOM Sebaya
    </footer>
@endsection
