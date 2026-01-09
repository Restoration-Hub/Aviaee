<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>

    <!-- CSRF token for Laravel -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite(['resources/css/loginPage.css'])</head>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Josefin+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<body>

  <div class="header">
    <div class="header-title">AVIAEE</div>
  </div>

    <div class="main">
        <div class="login-box">
            <h2>Sign In</h2>

            <div class="input-group">
                <label>Email</label>
                <input class="input-box" type="email">
            </div>

            <div class="input-group">
                <label>Password</label>
                <input class="input-box" type="password">
            </div>

            <div class="forgot">
                <a href="#" class="link">Forgot password?</a>
            </div>

            <button class="action-button">Sign In</button>

            <p style="margin-top: 16px;">
                <a href="#" class="link">Don't have an account? Register</a>
            </p>
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
