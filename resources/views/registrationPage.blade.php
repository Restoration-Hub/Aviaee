<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>

    <!-- CSRF token for Laravel -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite(['resources/css/registrationPage.css'])</head>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Josefin+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<body>

  <div class="header">
    <div class="header-title">AVIAEE</div>
  </div>

    <div class="main">
        <div class="registration-box">
            <h2>Register</h2>

        <div class="adjacent-input-group">
            <div class="input-group">
                <label>First Name</label>
                <input class="input-box" type="text">
            </div>
            <div class="input-group">
                <label>Last Name</label>
                <input class="input-box" type="text">
            </div>
        </div>

            <div class="input-group">
                <label>Email</label>
                <input class="input-box" type="email">
            </div>

            <div class="adjacent-input-group">
                <div class="input-group">
                    <label>Password</label>
                    <input class="input-box" type="password">
                </div>
                <div class="input-group">
                    <label>Confirm Password</label>
                    <input class="input-box" type="password">
                </div>
            </div>

            <div class="misc-input-group">
                <div class="input-group">
                    <label>Phone Number</label>
                    <input class="input-box" type="text">
                </div>
                <div class="input-group">
                    <label>User Type</label>
                    <select class="select-box">
                        <option value="buyer">Buyer</option>
                        <option value="seller">Seller</option>
                        <option value="pilot">Pilot</option>
                    </select>
            </div>
            <div> 
                <input type="checkbox" class="checkbox-box"> Allow Aviaee to access your location?
            </div>


            <button class="action-button">Register</button>
        </div>
    </div>



    <div id="message" style="margin-top: 10px; font-weight: bold;"></div>

    <script>
        const loginBtn = document.getElementById('loginBtn');
        const messageDiv = document.getElementById('message');

        loginBtn.addEventListener('click', async () => {
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;
            messageDiv.textContent = '';

            if (!email || !password) {
                messageDiv.textContent = 'Please enter email and password.';
                return;
            }

            try {
                const response = await fetch('/verify-login-credentials', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ email, password })
                });

                if (response.status === 200) {
                    messageDiv.textContent = 'User found!';
                } else if (response.status === 401) {
                    const data = await response.json();
                    messageDiv.textContent = data.message || 'Unauthorized';
                } else {
                    messageDiv.textContent = 'An error occurred: ' + response.status;
                }
            } catch (error) {
                messageDiv.textContent = 'Network error: ' + error.message;
            }
        });
    </script>

</body>
</html>
