<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>

    <!-- CSRF token for Laravel -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Josefin+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<body>

    <div id="topBanner" class="top-banner">
        <span id="bannerText"></span>
        <button type="button" id="bannerClose" class="banner-close" aria-label="Close banner">
            <img src="{{ Vite::asset('resources/assets/close-icon.svg') }}" alt="Close Icon">
        </button>
    </div>
    
    <div class="header">
        <div>AVIAEE</div>
    </div>

    <a class="hyperlink-button action-button back-button" href="{{ route('home') }}">Back</a>

    <div class="main">
        <div class="registration-box">
            <div class="register-title">
                <h2>Register</h2>
                <div id="formError" class="form-error-message"></div>
            </div>

            <div class="adjacent-input-group">
                <div class="input-group">
                    <label>First Name</label>
                    <input id="first_name" class="input-box-registration" type="text">
                </div>
                <div class="input-group">
                    <label>Last Name</label>
                    <input id="last_name" class="input-box-registration" type="text">
                </div>
            </div>
            
       
            <div class="input-group">
                <div class="label-row">
                    <label for="email">Email</label>
                    <div id="emailError" class="error-message"></div>
                </div>
                <input id="email" class="input-box-registration" type="email">
            </div>

            <div class="adjacent-input-group">
                <div class="input-group">
                    <label>Password</label>
                    <input id="password" class="input-box-registration" type="password">
                    <div id="passwordError" class="password-error-message"></div>
                </div>
                <div class="input-group">
                    <label for="confirm_password">Confirm Password</label>               
                    <input id="confirm_password" class="input-box-registration" type="password">
                </div>
            </div>

            <p class="form-help-text">Password must be at least 8 characters long.</p>

            <div class="input-group">
                <label>Address</label>
                <input id="address" class="input-box-registration" type="text">
            </div>

            <div class="misc-input-group">
                <div class="adjacent-input-group">
                    <div class="input-group">
                        <label>User Type</label>
                        <select id="user_type" class="select-box">
                            <option value="buyer">Buyer</option>
                            <option value="seller">Seller</option>
                            <option value="pilot">Pilot</option>
                        </select>
                    </div>
                    <div class="input-group">
                        <label>Phone Number</label>
                        <input id="phone_number" class="input-box-registration" type="text">
                    </div>
                </div>
            </div>

            <div class="checkbox-box">
                <input type="checkbox" id="locationCheck" class="checkbox">
                <label for="locationCheck">Allow Aviaee to access your location?</label>
            </div>

            <button id="registerBtn" class="action-button" disabled>Register</button>
        </div>
    </div>

    <div id="message" style="margin-top: 10px; font-weight: bold;"></div>

    <script>

        let latitude = null;
        let longitude = null;
        let bannerTimeout;
        const topBanner = document.getElementById('topBanner');
        const bannerText = document.getElementById('bannerText');
        const bannerClose = document.getElementById('bannerClose');
        const checkbox = document.getElementById("locationCheck");
        const registerBtn = document.getElementById('registerBtn');
        registerBtn.disabled = true;
        const emailInput = document.getElementById("email");
        const emailError = document.getElementById("emailError");
        const passwordInput = document.getElementById("password");
        const passwordError = document.getElementById("passwordError");
        const confirmInput = document.getElementById("confirm_password");
        const formError = document.getElementById("formError");

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

        confirmInput.addEventListener("blur", function () {
            if (confirmInput.value === "") {
                passwordError.innerHTML = "";
                return;
            }

            if (passwordInput.value !== confirmInput.value) {
                passwordError.innerHTML =
                    '<span class="error-icon">!</span> Passwords do not match';
            } else {
                passwordError.innerHTML = "";
            }
        });

        emailInput.addEventListener("blur", function () {
            const value = emailInput.value.trim();

            if (value === "") {
                emailError.innerHTML = "";
                return;
            }

            if (!emailInput.checkValidity()) {
                emailError.innerHTML =
                '<span class="error-icon">!</span> Invalid email';
            } else {
                emailError.innerHTML = "";
            }
            });

            emailInput.addEventListener("input", function () {
            emailError.innerHTML = "";
        });

        checkbox.addEventListener("change", function () {
            if (this.checked) {
                requestLocation();
            } else {
                latitude = null;
                longitude = null;
            }
        });

        registerBtn.addEventListener('click', async () => {
            const firstName = document.getElementById('first_name')?.value;
            const lastName = document.getElementById('last_name')?.value;
            const phoneNumber = document.getElementById('phone_number')?.value;
            const userType = document.getElementById('user_type')?.value;
            const email = document.getElementById('email')?.value;
            const password = document.getElementById('password')?.value;
            const confirmPassword = document.getElementById('confirm_password')?.value;
            const address = document.getElementById('address')?.value;

            if (
                !firstName || !lastName || !phoneNumber ||
                !userType || !email || !password || !confirmPassword || !address
            ) {
                formError.innerHTML =
                    '<span class="error-icon">!</span> Please fill in all fields';
                return;
            }

            if (password !== confirmPassword) {
                passwordError.innerHTML =
                    '<span class="error-icon">!</span> Passwords do not match';
                return;
            }

            try {
                const response = await fetch('/register', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document
                            .querySelector('meta[name="csrf-token"]')
                            .getAttribute('content')
                    },
                    body: JSON.stringify({
                        first_name: firstName,
                        last_name: lastName,
                        phone_number: phoneNumber,
                        user_type: userType,
                        email,
                        password,
                        address: address,
                        latitude: latitude,
                        longitude: longitude
                    })
                });

                if (response.status === 201) {
                    window.location.href = '/';
                } else {
                    const data = await response.json();
                    console.error(data.message || 'Registration error');
                }

            } catch (error) {
                console.error('Network error:', error.message);
            }
        });

        // GOELOCATION FUNCTIONS
        function requestLocation() {
            if (!navigator.geolocation) {
                showBanner('Geolocation is not supported by this browser.', 'error');
                return;
            }

            navigator.geolocation.getCurrentPosition(
                (position) => {
                    latitude = position.coords.latitude;
                    longitude = position.coords.longitude;
                    registerBtn.disabled = false;
                    showBanner('Location accessed successfully!', 'success');
                },
                showError
            );
        }

        function showError(error) {
            switch(error.code) {
                case error.PERMISSION_DENIED:
                    document.getElementById("location-status").innerHTML = "User denied the request for Geolocation."
                    break;
                case error.POSITION_UNAVAILABLE:
                    document.getElementById("location-status").innerHTML = "Location information is unavailable."
                    break;
                case error.TIMEOUT:
                    document.getElementById("location-status").innerHTML = "The request to get user location timed out."
                    break;
                case error.UNKNOWN_ERROR:
                    document.getElementById("location-status").innerHTML = "An unknown error occurred."
                    break;
            }
        }
    </script>

</body>
</html>
