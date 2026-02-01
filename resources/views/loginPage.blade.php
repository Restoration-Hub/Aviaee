<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>

    <meta name="csrf-token" content="{{ csrf_token() }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Josefin+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>

  <div class="header">
    <div class="header-title">AVIAEE</div>
  </div>

    <div class="main">
        <div class="login-box">
            <h2>Sign In</h2>

            <div class="input-group">
                <label>Email</label>
                <br>
                <input class="input-box" id="email" type="email">
            </div>

            <div class="input-group">
                <label>Password</label>
                <br>
                <input class="input-box" id="password" type="password">
            </div>

            <div class="forgot">
                <a href="#" class="link">Forgot password?</a>
            </div>

            <button id="loginBtn" class="action-button">Sign In</button>

            <p style="margin-top: 16px;">
                <a href="{{ route('registration') }}" class="link">
                    Don't have an account? Register
                </a>
            </p>
        </div>
    </div>

    <script>
        const loginBtn = document.getElementById('loginBtn');

        loginBtn.addEventListener('click', async () => {
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;

            if (!email || !password) {
                console.warn('Please enter email and password.');
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
                    console.log('Login successful');
                } else if (response.status === 401) {
                    const data = await response.json();
                    console.error(data.message || 'Unauthorized');
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
                console.error('Network error: ' + error.message);
            }
        });
    </script>

</body>
</html>
