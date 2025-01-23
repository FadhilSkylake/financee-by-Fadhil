<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login</title>
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
  <div class="container d-flex justify-content-center align-items-center vh-100">
    <div class="card shadow p-4" style="width: 100%; max-width: 400px;">
      <h3 class="text-center mb-4">Login</h3>
      <form id="loginForm">
        <div class="mb-3">
          <label for="email" class="form-label">Email</label>
          <input type="email" id="email" name="email" class="form-control" placeholder="Enter your email" required>
        </div>
        <div class="mb-3">
          <label for="password" class="form-label">Password</label>
          <input type="password" id="password" name="password" class="form-control" placeholder="Enter your password" required>
        </div>
        <button type="submit" class="btn btn-primary w-100">Login</button>
        <div id="error" class="text-danger mt-3" style="font-size: 0.9rem;"></div>
      </form>
      <div class="mt-4 text-center">
        <p>Don't have an account? <a href="/register" class="text-decoration-none">Register</a></p>
      </div>
    </div>
  </div>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    document.getElementById('loginForm').addEventListener('submit', async function (e) {
      e.preventDefault();

      const email = document.getElementById('email').value;
      const password = document.getElementById('password').value;
      const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

      try {
        await fetch('/sanctum/csrf-cookie', { method: 'GET', credentials: 'include' });

        const response = await fetch('/api/login', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json'
          },
          credentials: 'include',
          body: JSON.stringify({ email, password }),
        });

        const data = await response.json();

        if (response.ok) {
          alert('Login berhasil!');
          localStorage.setItem('access_token', data.data.access_token);
          window.location.href = '/dashboard';
        } else {
          document.getElementById('error').innerText = data.message || 'Login gagal.';
        }
      } catch (error) {
        console.error('Error:', error);
        document.getElementById('error').innerText = 'Terjadi kesalahan, coba lagi.';
      }
    });
  </script>
</body>
</html>
