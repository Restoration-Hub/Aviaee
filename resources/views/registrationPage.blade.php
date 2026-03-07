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

  <div class="header">
    <div>AVIAEE</div>
  </div>

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

        const checkbox = document.getElementById("locationCheck");
        const registerBtn = document.getElementById('registerBtn');
        const emailInput = document.getElementById("email");
        const emailError = document.getElementById("emailError");
        const passwordInput = document.getElementById("password");
        const confirmInput = document.getElementById("confirm_password");
        const formError = document.getElementById("formError");

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
            registerBtn.disabled = !this.checked;
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
                console.warn('Please fill in all fields.');
                formError.innerHTML =
                    '<span class="error-icon">!</span> Please fill in all fields';
                return;
            } else {
                formError.innerHTML = "";
            }

            if (password !== confirmPassword) {
                console.warn('Passwords do not match.');
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
                        address: address
                    })
                });

                if (response.status === 201) {
                    console.log('Registration successful');
                    window.location.href = '/';
                } else if (response.status === 422) {
                    const data = await response.json();
                    console.error(data.message || 'Validation error');
                } else if (response.status === 500) {
                    const data = await response.json();
                    console.error(data.message || 'Internal server error');
                } else {
                    console.error('Unexpected error:', response.status);
                }
            } catch (error) {
                console.error('Network error:', error.message);
            }
        });
    </script>

</body>
</html>
