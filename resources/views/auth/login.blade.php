@extends('layouts.app')

@section('title', 'Masuk - FILKOM Sebaya')

@push('styles')
    @vite(['resources/css/login.css'])
@endpush

@section('content')
<!-- Header Navbar -->
<nav class="navbar-container">
    <div class="navbar-logo" style="display: flex; align-items: center; gap: 10px;">
        <img src="{{ asset('images/logo-icon.png') }}" alt="FILKOM Sebaya Logo" style="height: 38px; border-radius: 6px;">
        <span style="font-weight: 700; color: #0f2942;">FILKOM <span style="color: #059669;">Sebaya</span></span>
    </div>
    <div>
        <a href="{{ route('login') }}" class="primary-button" style="background-color: #0f2942; background-image: none; box-shadow: none; border-radius: 6px; padding: 8px 24px; font-size: 13px;">Login</a>
    </div>
</nav>

<div class="login-layout">
    <div class="login-container">
        
        <div class="login-title-group" style="text-align: center; margin-bottom: 40px;">
            <h1 class="login-main-title" style="font-size: 40px; font-weight: 800; color: #0f2942; margin-bottom: 12px;">Masuk ke FILKOM Sebaya</h1>
            <p class="login-card-subtitle" style="font-size: 15px; color: #64748b; max-width: 600px; margin: 0 auto;">Gunakan NIM dan password FILKOM Anda untuk mengakses platform konseling sebaya kami.</p>
        </div>

        <div class="login-card">
            <!-- Left Side Logo -->
            <div class="login-card-left">
                <img src="{{ asset('images/logo-full.png') }}" alt="FILKOM Sebaya Logo" style="width: 100%; max-width: 260px; height: auto; object-fit: contain;">
            </div>

            <!-- Right Side Form -->
            <div class="login-card-right">
                <form action="{{ route('login.post') }}" method="POST" style="width: 100%;">
                    @csrf
                    
                    <div class="form-group">
                        <label for="email" style="font-weight: 600; color: #0f2942;">NIM</label>
                        <input type="email" name="email" id="email" value="{{ old('email') }}" class="input-field" placeholder="Gunakan email UB / NIM UB" required autofocus style="border-radius: 4px; border: 1.5px solid #cbd5e1; padding: 12px;">
                        @error('email')
                            <span class="login-error-message" style="color: var(--danger); font-size: 12px; margin-top: 4px; display: block;">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group" style="margin-bottom: 24px;">
                        <label for="password" style="font-weight: 600; color: #0f2942;">Password</label>
                        <input type="password" name="password" id="password" class="input-field" placeholder="Password" required style="border-radius: 4px; border: 1.5px solid #cbd5e1; padding: 12px;">
                        @error('password')
                            <span class="login-error-message" style="color: var(--danger); font-size: 12px; margin-top: 4px; display: block;">{{ $message }}</span>
                        @enderror
                    </div>

                    <button type="submit" class="primary-button" style="width: auto; background-color: #0f2942; background-image: none; border-radius: 4px; padding: 12px 36px; box-shadow: none; font-size: 14px; font-weight: 700;">Login</button>
                </form>
            </div>
        </div>

    </div>
</div>

<!-- Footer -->
<footer style="background-color: #ffffff; padding: 40px 120px; border-top: 1.5px solid #f1f5f9; text-align: center; font-size: 13px; color: #94a3b8; font-weight: 500;">
    &copy; 2025 FILKOM Sebaya
</footer>
@endsection
