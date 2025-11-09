<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Invoice System - Login</title>
  <link href="https://fonts.googleapis.com/css?family=Karla" rel="stylesheet">
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: 'Karla', sans-serif;
    }

    body {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 20px;
    }

    .login-container {
      background: white;
      padding: 40px;
      border-radius: 12px;
      box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
      width: 100%;
      max-width: 400px;
    }

    .login-header {
      text-align: center;
      margin-bottom: 40px;
    }

    .login-header h1 {
      color: #1a1a1a;
      font-size: 28px;
      font-weight: 700;
      margin-bottom: 8px;
    }

    .login-header p {
      color: #6b7280;
      font-size: 16px;
    }

    .input-group {
      margin-bottom: 24px;
    }

    .input-group label {
      display: block;
      font-size: 14px;
      color: #374151;
      font-weight: 500;
      margin-bottom: 8px;
    }

    .input-group input {
      width: 100%;
      padding: 12px 16px;
      border: 2px solid #e5e7eb;
      border-radius: 8px;
      font-size: 16px;
      transition: all 0.3s ease;
    }

    .input-group input:focus {
      border-color: #6366f1;
      outline: none;
      box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
    }

    .remember-me {
      display: flex;
      align-items: center;
      margin-bottom: 24px;
    }

    .remember-me input[type="checkbox"] {
      margin-right: 8px;
    }

    .remember-me label {
      color: #4b5563;
      font-size: 14px;
    }

    .login-button {
      width: 100%;
      padding: 12px;
      background: #6366f1;
      color: white;
      border: none;
      border-radius: 8px;
      font-size: 16px;
      font-weight: 600;
      cursor: pointer;
      transition: background-color 0.3s ease;
    }

    .login-button:hover {
      background: #4f46e5;
    }

    .error-message {
      background: #fee2e2;
      color: #dc2626;
      padding: 12px;
      border-radius: 6px;
      margin-bottom: 20px;
      display: none;
    }

    .forgot-password {
      text-align: center;
      margin-top: 16px;
    }

    .forgot-password a {
      color: #6366f1;
      text-decoration: none;
      font-size: 14px;
    }

    .forgot-password a:hover {
      text-decoration: underline;
    }

    @media (max-width: 480px) {
      .login-container {
        padding: 20px;
      }
    }
  </style>
</head>

<body>
  <div class="login-container">
    <div class="login-header">
      <h1>Welcome Back</h1>
      <p>Please sign in to continue</p>
    </div>

    <div class="error-message" id="errorMessage">
      Invalid username or password
    </div>

    <form id="loginForm" onsubmit="return handleLogin(event)">
      <div class="input-group">
        <label for="username">Username</label>
        <input type="text" id="username" name="username" required>
      </div>

      <div class="input-group">
        <label for="password">Password</label>
        <input type="password" id="password" name="password" required>
      </div>

      <div class="remember-me">
        <input type="checkbox" id="remember" name="remember">
        <label for="remember">Remember me</label>
      </div>

      <button type="submit" class="login-button">Sign In</button>

      <div class="forgot-password">
        <a href="#" onclick="alert('Please contact your administrator')">Forgot your password?</a>
      </div>

      <div class="register-link" style="text-align: center; margin-top: 16px; border-top: 1px solid #e5e7eb; padding-top: 16px;">
        Don't have an account? <a href="signin.php" style="color: #6366f1; text-decoration: none; font-weight: 500;">Register Now</a>
      </div>
    </form>
  </div>

  <script>
    function handleLogin(event) {
      event.preventDefault();

      const username = document.getElementById('username').value;
      const password = document.getElementById('password').value;
      const remember = document.getElementById('remember').checked;

      // Send login request to server
      fetch('api/auth/login_user.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
          },
          body: JSON.stringify({
            username,
            password
          })
        })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            // Store username if remember me is checked
            if (remember) {
              localStorage.setItem('username', username);
            }

            // Store user data
            sessionStorage.setItem('user', JSON.stringify(data.user));
            sessionStorage.setItem('isLoggedIn', 'true');

            // Redirect to dashboard
            window.location.href = 'dashboard.php';
          } else {
            const errorMessage = document.getElementById('errorMessage');
            errorMessage.style.display = 'block';

            // Hide error message after 3 seconds
            setTimeout(() => {
              errorMessage.style.display = 'none';
            }, 3000); 
          }

          return false;
        })
        .catch(error => {
          console.error('Error:', error);
        });
    }

    // Check if user was remembered
    window.addEventListener('DOMContentLoaded', () => {
      const rememberedUser = localStorage.getItem('username');
      if (rememberedUser) {
        document.getElementById('username').value = rememberedUser;
        document.getElementById('remember').checked = true;
      }
    });
  </script>
</body>

</html>