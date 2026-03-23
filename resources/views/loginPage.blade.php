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
    <div>AVIAEE</div>
  </div>

    <div class="main">
        <div class="login-box">
            <div class="signin-title">
                <h2>Sign In</h2>
                <div id="formError" class="form-error-message"></div>
            </div>

            <div class="input-group">
                <label>Email</label>
                <br>
                <input class="input-box-signin" id="email" type="email">
            </div>

            <div class="input-group">
                <label>Password</label>
                <br>
                <input class="input-box-signin" id="password" type="password">
            </div>

            <div id="verify-message" style="display:none; color: green; margin-top: 10px;">
                Please check your email to verify your account before logging in.
            </div>

            <div id="verified-message" style="display:none; color:green; margin-bottom:10px;">
                Email verified successfully. You can now log in.
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
        const params = new URLSearchParams(window.location.search);
        const verifyEmailMessage = document.getElementById('verify-message');

        if (params.get('verify_email') === '1') {
            verifyEmailMessage.style.display = 'block';

            window.history.replaceState({}, document.title, "/");
        }

        if (params.get('verified') === '1') {
            const message = document.getElementById('verified-message');
            message.style.display = 'block';
        }

        const loginBtn = document.getElementById('loginBtn');

        loginBtn.addEventListener('click', async () => {
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;

            if (!email || !password) {
                console.warn('Please enter email and password.');
                formError.innerHTML =
                    '<span class="error-icon">!</span> Please enter email and password';
                return;
            } else {
                formError.innerHTML = "";
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
                    window.location.href = "{{ route('missions.list') }}";
                } else if (response.status === 401) {
                    const data = await response.json();
                    console.error(data.message || 'Unauthorized');
                    formError.innerHTML = '<span class="error-icon">!</span> Incorrect email or password';
                } else if (response.status === 403) {
                    const data = await response.json();
                    console.error(data.message || 'Forbidden');
                    formError.innerHTML = '<span class="error-icon">!</span> Please verify your email before logging in';
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
