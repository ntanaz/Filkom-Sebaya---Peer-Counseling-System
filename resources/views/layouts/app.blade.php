<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'FILKOM Sebaya - Peer Counseling System')</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    
    <!-- Global CSS -->
    @vite(['resources/css/global.css'])
    
    <!-- Child Page CSS Stack -->
    @stack('styles')
</head>
<body>
    
    <!-- Success / Error Toast Notifications -->
    @if(session('success'))
        <div class="toast-notification success" id="toast-success">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width: 20px; height: 20px; color: var(--success);">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span>{{ session('success') }}</span>
        </div>
        <script>
            setTimeout(() => {
                const toast = document.getElementById('toast-success');
                if (toast) {
                    toast.style.opacity = '0';
                    toast.style.transform = 'translateY(-20px)';
                    setTimeout(() => toast.remove(), 300);
                }
            }, 4000);
        </script>
    @endif

    @if(session('error'))
        <div class="toast-notification error" id="toast-error">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width: 20px; height: 20px; color: var(--danger);">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
            <span>{{ session('error') }}</span>
        </div>
        <script>
            setTimeout(() => {
                const toast = document.getElementById('toast-error');
                if (toast) {
                    toast.style.opacity = '0';
                    toast.style.transform = 'translateY(-20px)';
                    setTimeout(() => toast.remove(), 300);
                }
            }, 4000);
        </script>
    @endif

    <style>
        .toast-notification {
            position: fixed;
            top: 24px;
            right: 24px;
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 16px 24px;
            background-color: #ffffff;
            border-radius: var(--radius-md);
            box-shadow: var(--shadow-hover);
            border-left: 4px solid var(--primary-blue);
            z-index: 9999;
            transition: all 0.3s ease;
            font-weight: 500;
            font-size: 14px;
        }
        .toast-notification.success {
            border-left-color: var(--success);
        }
        .toast-notification.error {
            border-left-color: var(--danger);
        }
    </style>

    @yield('content')

</body>
</html>
