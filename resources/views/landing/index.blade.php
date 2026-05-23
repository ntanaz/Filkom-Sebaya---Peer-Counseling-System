@extends('layouts.app')

@section('title', 'FILKOM Sebaya - Peer Counseling System')

@push('styles')
    @vite(['resources/css/landing.css'])
@endpush

@section('content')
    <!-- Navbar -->
    <nav class="navbar-container">
        <div class="navbar-logo" style="display: flex; align-items: center; gap: 10px;">
            <img src="{{ asset('images/logo-icon.png') }}" alt="FILKOM Sebaya Logo" style="height: 38px; border-radius: 6px;">
            <span style="font-weight: 700; color: #0f2942;">FILKOM <span style="color: #059669;">Sebaya</span></span>
        </div>
        <div>
            @auth
                @if(Auth::user()->role === 'admin')
                    <a href="{{ route('admin.dashboard') }}" class="primary-button" style="background-color: #0f2942; background-image: none; box-shadow: none; border-radius: 6px; padding: 8px 24px; font-size: 13px;">Dashboard Admin</a>
                @elseif(Auth::user()->role === 'counselor')
                    <a href="{{ route('counselor.dashboard') }}" class="primary-button" style="background-color: #0f2942; background-image: none; box-shadow: none; border-radius: 6px; padding: 8px 24px; font-size: 13px;">Dashboard Konselor</a>
                @else
                    <a href="{{ route('konseli.dashboard') }}" class="primary-button" style="background-color: #0f2942; background-image: none; box-shadow: none; border-radius: 6px; padding: 8px 24px; font-size: 13px;">Dashboard Konseli</a>
                @endif
            @else
                <a href="{{ route('login') }}" class="primary-button" style="background-color: #0f2942; background-image: none; box-shadow: none; border-radius: 6px; padding: 8px 24px; font-size: 13px;">Login</a>
            @endauth
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="hero-image-left">
            <img src="{{ asset('images/logo-full.png') }}" alt="FILKOM Sebaya Logo" style="max-height: 280px; width: auto; object-fit: contain;">
        </div>
        <div class="hero-content-right">
            <h1 class="hero-title" style="font-size: 44px; color: #0f2942; font-weight: 800; line-height: 1.2; margin-bottom: 16px;">Konseling sebaya yang terstruktur</h1>
            <p class="hero-desc" style="font-size: 16px; color: #64748b; line-height: 1.6; margin-bottom: 32px;">Ajukan permintaan, pantau status, terima pembaruan melalui email.</p>
            <div class="hero-buttons" style="display: flex; gap: 12px;">
                @auth
                    @if(Auth::user()->role === 'konseli')
                        <a href="{{ route('konseli.dashboard') }}" class="primary-button" style="background-color: #0f2942; background-image: none; border: none; border-radius: 6px; padding: 12px 32px; font-size: 14px; box-shadow: none;">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="primary-button" style="background-color: #0f2942; background-image: none; border: none; border-radius: 6px; padding: 12px 32px; font-size: 14px; box-shadow: none;">Login</a>
                    @endif
                @else
                    <a href="{{ route('login') }}" class="primary-button" style="background-color: #0f2942; background-image: none; border: none; border-radius: 6px; padding: 12px 32px; font-size: 14px; box-shadow: none;">Login</a>
                @endauth
                <a href="#cara-kerja" class="secondary-button" style="border: 1.5px solid #e2e8f0; border-radius: 6px; padding: 12px 32px; font-size: 14px; color: #0f2942; font-weight: 600;">Tentang</a>
            </div>
        </div>
    </section>

    <!-- Three Pillars Section -->
    <section class="three-pillars-section">
        <h2 style="font-size: 32px; font-weight: 800; margin-bottom: 12px;">Tiga pilar sistem kami</h2>
        <p style="color: #94a3b8; font-size: 15px; max-width: 600px; margin: 0 auto 54px auto;">Setiap fitur dirancang untuk membuat konseling lebih mudah dan terarah</p>

        <div class="pillars-grid">
            <!-- Pillar 1 -->
            <div class="pillar-card" style="background-image: url('{{ asset('images/pillar-1.png') }}');">
                <div class="pillar-content">
                    <span class="tag">Buat</span>
                    <h3>Ajukan konseling dengan mudah</h3>
                    <p>Buat pengajuan dengan cepat dan tentukan yang baru dalam beberapa langkah sederhana.</p>
                    <a href="{{ route('login') }}">Ajukan &rarr;</a>
                </div>
            </div>

            <!-- Pillar 2 -->
            <div class="pillar-card" style="background-image: url('{{ asset('images/pillar-2.png') }}');">
                <div class="pillar-content">
                    <span class="tag">Pantau</span>
                    <h3>Pantau status pengajuan</h3>
                    <p>Lihat perkembangan status pengajuan melalui status yang jelas: Menunggu, Diproses, dan Selesai.</p>
                    <a href="{{ route('login') }}">Pantau &rarr;</a>
                </div>
            </div>

            <!-- Pillar 3 -->
            <div class="pillar-card" style="background-image: url('{{ asset('images/pillar-3.png') }}');">
                <div class="pillar-content">
                    <span class="tag">Akses</span>
                    <h3>Terintegrasi dengan sistem kampus</h3>
                    <p>Gunakan akun FILKOM untuk satu registrasi terpadu dalam mengakses layanan.</p>
                    <a href="{{ route('login') }}">Masuk &rarr;</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Timeline Steps Section -->
    <section class="timeline-section" id="cara-kerja">
        <div>
            <h2 style="font-size: 36px; font-weight: 800; color: #0f2942; line-height: 1.2;">Alur penggunaan sistem</h2>
        </div>
        <div class="timeline-steps">
            <!-- Step 1 -->
            <div class="step-item">
                <div class="step-bullet"></div>
                <div class="step-info">
                    <h4>Login</h4>
                    <p>Peer counselor menerima pengajuan dan memulai proses konseling secara berkualitas.</p>
                </div>
            </div>

            <!-- Step 2 -->
            <div class="step-item">
                <div class="step-bullet"></div>
                <div class="step-info">
                    <h4>Buat pengajuan</h4>
                    <p>Lihat status pengajuan dan terima notifikasi untuk setiap ada perubahan sistem.</p>
                </div>
            </div>

            <!-- Step 3 -->
            <div class="step-item">
                <div class="step-bullet"></div>
                <div class="step-info">
                    <h4>Counselor meninjau permintaan</h4>
                    <p>Masuk, lapor, pantau, selesai.</p>
                </div>
            </div>

            <!-- Step 4 -->
            <div class="step-item">
                <div class="step-bullet"></div>
                <div class="step-info">
                    <h4>Pantau melalui dashboard</h4>
                    <p>Empat langkah sederhana menuju konseling yang efektif dan terstruktur.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta-section" style="background-color: #ffffff; padding: 100px 120px; display: grid; grid-template-columns: 1.2fr 1fr; gap: 80px; align-items: center; border-top: 1.5px solid #f1f5f9;">
        <div style="text-align: left; display: flex; flex-direction: column; gap: 16px;">
            <h2 style="font-size: 36px; font-weight: 800; color: #0f2942; line-height: 1.2;">Akses sistem konseling sekarang</h2>
            <p style="font-size: 16px; color: #64748b; line-height: 1.6; margin-bottom: 16px;">Masuk dengan NIM Anda dan mulai perjalanan konseling yang terstruktur bersama peer counselor FILKOM.</p>
            <div>
                <a href="{{ route('login') }}" class="primary-button" style="background-color: #0f2942; background-image: none; border: none; border-radius: 6px; padding: 12px 36px; font-size: 14px; box-shadow: none;">Login</a>
            </div>
        </div>
        <div style="display: flex; justify-content: center;">
            <img src="{{ asset('images/supportive_hands.png') }}" alt="Supportive Hands" style="width: 100%; max-width: 440px; border-radius: var(--radius-md); box-shadow: var(--shadow-card); object-fit: cover; height: 280px;">
        </div>
    </section>

    <!-- Footer -->
    <footer style="background-color: #ffffff; padding: 40px 120px; border-top: 1.5px solid #f1f5f9; text-align: center; font-size: 13px; color: #94a3b8; font-weight: 500;">
        &copy; 2025 FILKOM Sebaya
    </footer>
@endsection
