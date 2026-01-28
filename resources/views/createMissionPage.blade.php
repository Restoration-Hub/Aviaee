<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Mission</title>

    <!-- CSRF token for Laravel -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite(['resources/css/createMissionPage.css'])</head>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Josefin+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<body>

  <div class="header">
    <div class="header-title">AVIAEE</div>
  </div>

    <div class="main">
        <div class="create-mission-box">
            <h2>Create Mission</h2>

            <div class="input-group">
                <label >Mission Name</label>
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
            <button id="cancel-button" class="cancel-button">Cancel</button>
            <button id="create-button" class="create-button">Create</button>
        </div>
        </div>
            <div id="message" style="margin-top: 10px; font-weight: bold;"></div>

        </div>
    </div>



    <div id="message" style="margin-top: 10px; font-weight: bold;"></div>

    <script>
        const createBtn = document.getElementById('create-button');
        const messageDiv = document.getElementById('message');

        createBtn.addEventListener('click', async () => {
            const missionName = document.getElementById('missionName').value;
            const startingLocation = document.getElementById('startingLocation').value;
            const destination = document.getElementById('destination').value;

            messageDiv.textContent = '';

            if (!missionName && !startingLocation && !destination) {
                messageDiv.textContent = 'Please enter all required fields.';
                return;
            }
            else if (!missionName) {
                messageDiv.textContent = 'Please enter a mission name.';
                return;
            } else if (!startingLocation) {
                messageDiv.textContent = 'Please enter a starting location.';
                return;
            } else if (!destination) {
                messageDiv.textContent = 'Please enter a destination.';
                return;
            }

            try {
                const response = await fetch('/missions', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ missionName, startingLocation, destination })
                });

                const data = await response.json();

                if (response.ok) {
                    messageDiv.textContent = 'Mission created successfully!';
                    } else {
                    messageDiv.textContent = data.message || 'Error creating mission.';
                }
            } catch (error) {
                messageDiv.textContent = 'Network error: ' + error.message;
            }
        });
    </script>

</body>
</html>
