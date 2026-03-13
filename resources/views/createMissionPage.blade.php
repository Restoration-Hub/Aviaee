<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Mission</title>

    <!-- CSRF token for Laravel -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite(['resources/css/createMissionPage.css'])

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
        <div class="header-title">AVIAEE</div>
    </div>

    <div class="main">
        <div class="create-mission-box">
            <h2>Create Mission</h2>

            <div class="input-group">
                <label>Mission Name</label>
                <input required class="input-box" id="missionName" placeholder="Enter a mission name">
            </div>

            <div class="input-group">
                <label>Starting Location</label>
                <input required class="input-box" id="startingLocation" placeholder="Enter a pickup address">
            </div>

            <div class="input-group">
                <label>Destination</label>
                <input required class="input-box" id="destination" placeholder="Enter a dropoff address">
            </div>

            <div class="action-buttons" style="margin-top: 20px;">
                <a href="{{ route('missions.list') }}" class="cancel-button">Cancel</a>
                <button id="create-button" class="create-button">Create</button>
            </div>
        </div>
    </div>

    <script>
        const createBtn = document.getElementById('create-button');
        const topBanner = document.getElementById('topBanner');
        const bannerText = document.getElementById('bannerText');
        const bannerClose = document.getElementById('bannerClose');

        let bannerTimeout;

        function showBanner(message, type = 'success', duration = 6000) {
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

        createBtn.addEventListener('click', async () => {
            const missionName = document.getElementById('missionName').value.trim();
            const startingLocation = document.getElementById('startingLocation').value.trim();
            const destination = document.getElementById('destination').value.trim();

            if (!missionName && !startingLocation && !destination) {
                showBanner('Please enter all required fields.', 'error');
                return;
            } else if (!missionName) {
                showBanner('Please enter a mission name.', 'error');
                return;
            } else if (!startingLocation) {
                showBanner('Please enter a starting location.', 'error');
                return;
            } else if (!destination) {
                showBanner('Please enter a destination.', 'error');
                return;
            }

            try {
                const response = await fetch('/missions', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document
                            .querySelector('meta[name="csrf-token"]')
                            .getAttribute('content')
                    },
                    body: JSON.stringify({ missionName, startingLocation, destination })
                });

                const data = await response.json();

                if (response.ok) {
                    sessionStorage.setItem('missionBannerMessage', 'Mission created successfully!');
                    sessionStorage.setItem('missionBannerType', 'success');

                    setTimeout(() => {
                        window.location.href = "{{ route('missions.list') }}";
                    }, 1000);
                } else {
                    showBanner(data.message || 'Error creating mission.', 'error');
                }
            } catch (error) {
                showBanner('Network error: ' + error.message, 'error');
            }
        });
    </script>

</body>
</html>