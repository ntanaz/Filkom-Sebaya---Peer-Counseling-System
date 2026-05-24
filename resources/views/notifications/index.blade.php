@extends('layouts.app')

@section('title', 'Notifikasi - FILKOM Sebaya')

@push('styles')
    @if(Auth::user()->role === 'admin')
        @vite(['resources/css/admin.css'])
    @elseif(Auth::user()->role === 'counselor')
        @vite(['resources/css/counselor.css'])
    @else
        @vite(['resources/css/konseli.css'])
    @endif
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
                @if($user->role === 'admin')
                    <a href="{{ route('admin.dashboard') }}" class="navbar-link">Dashboard</a>
                    <a href="{{ route('admin.users') }}" class="navbar-link">Manajemen Pengguna</a>
                @elseif($user->role === 'counselor')
                    <a href="{{ route('counselor.dashboard') }}" class="navbar-link">Dashboard</a>
                    <a href="{{ route('counselor.requests') }}" class="navbar-link">Permintaan Masuk</a>
                    <a href="{{ route('counselor.sessions') }}" class="navbar-link">Manajemen Sesi</a>
                @else
                    <a href="{{ route('konseli.dashboard') }}" class="navbar-link">Dashboard</a>
                    <a href="{{ route('konseli.create') }}" class="navbar-link">Ajukan Konseling</a>
                    <a href="{{ route('konseli.history') }}" class="navbar-link">Riwayat Konseling</a>
                @endif
            </div>
        </div>
        <div class="navbar-right-actions">
            <a href="{{ route('notifications.index') }}" class="notification-bell-link active" title="Notifikasi">
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
    <section class="jumbotron-banner jumbotron-banner-blue">
        <h1>Notifikasi Anda</h1>
        <p>Pemberitahuan status bimbingan, jadwal pertemuan, dan pengingat konseling</p>
    </section>

    <!-- Content Container -->
    <div class="notification-page-container">
        
        <!-- Back Link -->
        <div class="back-link-wrapper">
            @if($user->role === 'admin')
                <a href="{{ route('admin.dashboard') }}" class="back-link-btn">&larr; Kembali ke Dashboard</a>
            @elseif($user->role === 'counselor')
                <a href="{{ route('counselor.dashboard') }}" class="back-link-btn">&larr; Kembali ke Dashboard</a>
            @else
                <a href="{{ route('konseli.dashboard') }}" class="back-link-btn">&larr; Kembali ke Dashboard</a>
            @endif
        </div>

        <div class="notification-card-wrapper">
            <!-- Header Component -->
            <div class="notification-header">
                <span class="notification-title">Daftar Notifikasi</span>
                <span class="header-info-count">{{ $notifications->count() }} notifikasi</span>
            </div>

            <!-- Filters Component -->
            <div class="notification-filters">
                <a href="{{ route('notifications.index', ['filter' => 'all']) }}" class="filter-tab {{ $filter === 'all' ? 'active' : '' }}">Semua</a>
                <a href="{{ route('notifications.index', ['filter' => 'unread']) }}" class="filter-tab {{ $filter === 'unread' ? 'active' : '' }}">Belum Dibaca</a>
                <a href="{{ route('notifications.index', ['filter' => 'read']) }}" class="filter-tab {{ $filter === 'read' ? 'active' : '' }}">Telah Dibaca</a>
            </div>

            <!-- List Component -->
            <div class="notification-list">
                @forelse($notifications as $notification)
                    <!-- Notification Card Component -->
                    <div class="notification-card {{ !$notification->is_read ? 'unread' : '' }}">
                        <div class="notification-icon-badge {{ $notification->type }}">
                            @if($notification->type === 'status')
                                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" class="icon-20"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            @elseif($notification->type === 'schedule')
                                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" class="icon-20"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            @else
                                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" class="icon-20"><path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                            @endif
                        </div>
                        
                        <div class="notification-content">
                            <div class="notification-meta">
                                <span class="notification-status {{ $notification->type }}">
                                    @if($notification->type === 'status')
                                        Pembaruan Status
                                    @elseif($notification->type === 'schedule')
                                        Jadwal Konseling
                                    @else
                                        Pengingat Sesi
                                    @endif
                                </span>
                                <span class="notification-date">{{ $notification->created_at->diffForHumans() }}</span>
                            </div>
                            
                            <div class="notification-message">
                                {{ $notification->message }}
                            </div>

                            @if(!$notification->is_read)
                                <a href="{{ route('notifications.read', $notification->notification_id) }}" class="mark-read-btn">
                                    <svg fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" class="icon-14"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"></path></svg>
                                    Tandai telah dibaca
                                </a>
                            @endif
                        </div>
                    </div>
                @empty
                    <!-- Empty State Component -->
                    <div class="empty-state-container">
                        <svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" class="icon-56"><path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0"></path></svg>
                        <div class="empty-state-title">Belum ada notifikasi</div>
                        <div class="empty-state-subtitle">Kami akan memberitahu Anda saat ada perkembangan baru.</div>
                    </div>
                @endforelse
            </div>
        </div>

    </div>

    <!-- Footer -->
    <footer class="footer-container">
        &copy; 2025 FILKOM Sebaya
    </footer>
@endsection
