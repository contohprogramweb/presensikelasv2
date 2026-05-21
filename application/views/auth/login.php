<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($title) ? $title : 'Login - Sistem Presensi Kelas'; ?></title>

    <!-- Bootstrap 5.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="<?= base_url('assets/css/custom.css'); ?>" rel="stylesheet">

    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .login-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
            overflow: hidden;
            max-width: 400px;
            width: 100%;
        }

        .login-header {
            background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%);
            color: white;
            padding: 30px 20px;
            text-align: center;
        }

        .login-header i {
            font-size: 50px;
            margin-bottom: 15px;
        }

        .login-header h3 {
            margin: 0;
            font-weight: bold;
        }

        .login-header p {
            margin: 10px 0 0;
            opacity: 0.9;
            font-size: 14px;
        }

        .login-body {
            padding: 30px;
        }

        .form-control {
            border-radius: 8px;
            border: 1px solid #dee2e6;
            padding: 12px 15px;
        }

        .form-control:focus {
            border-color: #0d6efd;
            box-shadow: none;
            outline: none;
        }

        .input-group-text {
            background-color: #f8f9fa;
            border-radius: 8px 0 0 8px;
            border: 1px solid #dee2e6;
            border-right: none;
        }

        .input-group .form-control {
            border-radius: 0 8px 8px 0;
        }

        .btn-login {
            background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%);
            border: none;
            border-radius: 8px;
            padding: 12px;
            font-weight: 600;
            width: 100%;
            transition: transform 0.2s;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(13,110,253,.3);
        }

        .alert {
            border-radius: 8px;
        }

        .footer-text {
            text-align: center;
            color: rgba(255,255,255,0.8);
            margin-top: 20px;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-4">
                <div class="login-card">
                    <div class="login-header">
                        <i class="fas fa-school"></i>
                        <h3>Sistem Presensi Kelas</h3>
                        <p>SMPTK Galang Kasih Ubung</p>
                    </div>
                    <div class="login-body">
                        <?php if ($this->session->flashdata('error')): ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="fas fa-exclamation-circle me-2"></i>
                                <?= $this->session->flashdata('error'); ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>

                        <?php if (validation_errors()): ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                <?= validation_errors(); ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>

                        <form action="<?= site_url('auth/login'); ?>" method="post" id="loginForm">
                            <input type="hidden" name="<?= $csrf_name; ?>" value="<?= $csrf_hash; ?>">

                            <div class="mb-3">
                                <label for="username" class="form-label">Username</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                                    <input type="text" class="form-control" id="username" name="username" 
                                           placeholder="Masukkan username" required autofocus 
                                           value="<?= set_value('username'); ?>">
                                </div>
                            </div>

                            <div class="mb-4">
                                <label for="password" class="form-label">Password</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                    <input type="password" class="form-control" id="password" name="password" 
                                           placeholder="Masukkan password" required>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary btn-login">
                                <i class="fas fa-sign-in-alt me-2"></i>Login
                            </button>
                        </form>
                    </div>
                </div>
                <p class="footer-text">&copy; <?= date('Y'); ?> SMPTK Galang Kasih Ubung</p>
            </div>
        </div>
    </div>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        $(document).ready(function() {
            // Auto-hide alerts after 3 seconds
            setTimeout(function() {
                $('.alert').fadeOut('slow');
            }, 3000);

            // Form validation
            $('#loginForm').on('submit', function(e) {
                var username = $('#username').val().trim();
                var password = $('#password').val();

                if (!username) {
                    e.preventDefault();
                    alert('Username harus diisi!');
                    $('#username').focus();
                    return false;
                }

                if (!password) {
                    e.preventDefault();
                    alert('Password harus diisi!');
                    $('#password').focus();
                    return false;
                }
            });
        });
    </script>
</body>
</html>
