<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Missions</title>

    <!-- CSRF token for Laravel -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Josefin+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>

<body>
    <div id="topBanner" class="top-banner">
        <span id="bannerText"></span>
        <button id="bannerClose" class="banner-close" aria-label="Close banner">&times;</button>
    </div>

    <div class="header">
        <div>AVIAEE</div>
    </div>

    <div class="missions-header-container">
        <h1 class="missions-header">Missions</h1>
    </div>

    <div class="missions-main">

        {{-- Livewire missions table (includes search input & status filter, and the Create button) --}}
        <livewire:missions-table />
        
    </div>

    @livewireScripts

    <script>
        const topBanner = document.getElementById('topBanner');
        const bannerText = document.getElementById('bannerText');
        const bannerClose = document.getElementById('bannerClose');

        let bannerTimeout;

        function showBanner(message, type = 'success', duration = 3000) {
            bannerText.textContent = message;
            topBanner.className = `top-banner show ${type}`;

            clearTimeout(bannerTimeout);
            bannerTimeout = setTimeout(() => {
                topBanner.classList.remove('show');
            }, duration);
        }

        function hideBanner() {
            topBanner.classList.remove('show');
            clearTimeout(bannerTimeout);
        }

        bannerClose.addEventListener('click', hideBanner);

        document.addEventListener('DOMContentLoaded', () => {
            const message = sessionStorage.getItem('missionBannerMessage');
            const type = sessionStorage.getItem('missionBannerType') || 'success';

            if (message) {
                showBanner(message, type, 3000);

                sessionStorage.removeItem('missionBannerMessage');
                sessionStorage.removeItem('missionBannerType');
            }
        });

        window.addEventListener('show-top-banner', (event) => {
            const message = event.detail?.message || 'Success';
            const type = event.detail?.type || 'success';
            const duration = event.detail?.duration || 3000;

            showBanner(message, type, duration);
        });
    </script>
</body>
</html>