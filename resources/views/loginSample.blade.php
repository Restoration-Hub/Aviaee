<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Sample</title>

    <!-- CSRF token for Laravel -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>

    <h2>Login Sample</h2>

    <input type="email" id="email" placeholder="Email"><br><br>
    <input type="password" id="password" placeholder="Password"><br><br>

    <button id="loginBtn">Login</button>

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
