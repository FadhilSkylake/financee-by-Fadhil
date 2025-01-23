<!-- resources/views/auth/login.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">Login</div>
                    <div class="card-body">
                        <form id="loginForm">
                            @csrf
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>
                            <div id="errorMessage" class="alert alert-danger" style="display:none;"></div>
                            <button type="submit" class="btn btn-primary">Login</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
    $(document).ready(function() {
        $('#loginForm').on('submit', function(e) {
            e.preventDefault();
            
            // Reset error message
            $('#errorMessage').hide();

            $.ajax({
                url: '{{ route("login") }}', // Pastikan Anda mendefinisikan route ini di routes/web.php
                type: 'POST',
                data: $(this).serialize(),
                success: function(response) {
                    // Simpan token ke localStorage
                    localStorage.setItem('access_token', response.data.access_token);
                    localStorage.setItem('user', JSON.stringify(response.data.user));

                    // Redirect ke halaman dashboard atau home
                    window.location.href = '/dashboard'; // Sesuaikan dengan route dashboard Anda
                },
                error: function(xhr) {
                    // Tampilkan pesan error
                    var errorMsg = xhr.responseJSON ? 
                        xhr.responseJSON.message : 
                        'Terjadi kesalahan saat login';
                    
                    $('#errorMessage')
                        .text(errorMsg)
                        .show();
                }
            });
        });
    });
    </script>
</body>
</html>