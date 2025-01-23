<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Register</title>
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
  <div class="container d-flex justify-content-center align-items-center vh-100">
    <div class="card shadow p-4" style="width: 100%; max-width: 400px;">
      <h3 class="text-center mb-4">Register</h3>
      <form id="registerForm">
        <div class="mb-3">
          <label for="name" class="form-label">Name</label>
          <input type="text" id="name" name="name" class="form-control" placeholder="Enter your name" required>
        </div>
        <div class="mb-3">
          <label for="email" class="form-label">Email</label>
          <input type="email" id="email" name="email" class="form-control" placeholder="Enter your email" required>
        </div>
        <div class="mb-3">
          <label for="password" class="form-label">Password</label>
          <input type="password" id="password" name="password" class="form-control" placeholder="Enter your password" required>
        </div>
        <div class="mb-3">
          <label for="password_confirmation" class="form-label">Confirm Password</label>
          <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" placeholder="Confirm your password" required>
        </div>
        <button type="submit" class="btn btn-primary w-100">Register</button>
        <div id="error" class="text-danger mt-3" style="font-size: 0.9rem;"></div>
      </form>
      <div class="mt-4 text-center">
        <p>Already have an account? <a href="/login" class="text-decoration-none">Login</a></p>
      </div>
    </div>
  </div>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    document.getElementById('registerForm').addEventListener('submit', async function (e) {
      e.preventDefault();

      const name = document.getElementById('name').value;
      const email = document.getElementById('email').value;
      const password = document.getElementById('password').value;
      const password_confirmation = document.getElementById('password_confirmation').value;

      const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

      try {
        // Step 1: Fetch CSRF token
        await fetch('/sanctum/csrf-cookie', {
          method: 'GET',
          credentials: 'include'
        });

        // Step 2: Send registration request
        const response = await fetch('/api/users/register', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
          },
          credentials: 'include',
          body: JSON.stringify({ name, email, password, password_confirmation }),
        });

        const data = await response.json();

        if (response.ok) {
          alert('Registration successful! You can now log in.');
          window.location.href = '/login';
        } else {
          document.getElementById('error').innerText = data.message || 'Registration failed.';
        }
      } catch (error) {
        console.error('Error:', error);
        document.getElementById('error').innerText = 'An error occurred. Please try again.';
      }
    });
  </script>
</body>
</html>
