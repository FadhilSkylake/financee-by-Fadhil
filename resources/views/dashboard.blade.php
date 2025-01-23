<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
  <div class="container mt-5">
    <h1>Welcome to the Dashboard</h1>
    <p>This is the admin dashboard. Only authenticated users can access this page.</p>
    <button id="logoutButton" class="btn btn-danger">Logout</button>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    const token = localStorage.getItem('access_token');
    if (!token) {
        window.location.href = '/login';
    }
    document.getElementById('logoutButton').addEventListener('click', function() {
    fetch('/logout', {
        method: 'POST',
        headers: {
            'Authorization': `Bearer ${localStorage.getItem('access_token')}`,
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        },
        }).then(response => {
            if (response.ok) {
                localStorage.removeItem('access_token');
                window.location.href = '/login';
            } else {
                alert('Failed to logout.');
            }
        });
    });
    
  </script>
</body>
</html>
