<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($title) ? $title : 'Sistem Presensi Kelas'; ?></title>
    
    <!-- Bootstrap 5.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    
    <!-- DataTables CSS -->
    <link href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/responsive/2.4.1/css/responsive.bootstrap5.min.css" rel="stylesheet">
	
	
	<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
   
    
    <!-- Custom CSS -->
    <link href="<?= base_url('assets/css/custom.css'); ?>" rel="stylesheet">
    
    <style>
        :root {
            --primary-color: #0d6efd;
            --sidebar-width: 250px;
        }
        
        body {
            background-color: #f5f6fa;
        }
        
        .navbar {
            background-color: var(--primary-color);
            box-shadow: 0 2px 4px rgba(0,0,0,.1);
        }
        
        .sidebar {
            position: fixed;
            top: 56px;
            left: 0;
            height: calc(100vh - 56px);
            width: var(--sidebar-width);
            background-color: #343a40;
            overflow-y: auto;
            transition: all 0.3s;
            z-index: 1000;
        }
        
        .sidebar .nav-link {
            color: rgba(255,255,255,.8);
            padding: 12px 20px;
            border-left: 3px solid transparent;
        }
        
        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            color: #fff;
            background-color: rgba(255,255,255,.1);
            border-left-color: var(--primary-color);
        }
        
        .sidebar .nav-link i {
            margin-right: 10px;
            width: 20px;
            text-align: center;
        }
        
        .sidebar-header {
            padding: 20px;
            background-color: rgba(0,0,0,.2);
            text-align: center;
        }
        
        .profile-box {
            padding: 20px;
            background-color: rgba(0,0,0,.2);
            text-align: center;
        }
        
        .profile-box img {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #fff;
        }
        
        .profile-box h6 {
            color: #fff;
            margin: 10px 0 5px;
            font-size: 14px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        
        .content-wrapper {
            margin-left: var(--sidebar-width);
            padding: 20px;
            margin-top: 56px;
            transition: all 0.3s;
        }
        
        .card {
            border: none;
            box-shadow: 0 0 10px rgba(0,0,0,.05);
            margin-bottom: 20px;
        }
        
        .card-header {
            background-color: var(--primary-color);
            color: #fff;
            border: none;
            font-weight: 600;
        }
        
        @media (max-width: 768px) {
            .sidebar {
                margin-left: calc(-1 * var(--sidebar-width));
            }
            
            .sidebar.active {
                margin-left: 0;
            }
            
            .content-wrapper {
                margin-left: 0;
            }
        }

        .main-footer {
            margin-left: var(--sidebar-width);
            padding: 16px 20px;
            background-color: #fff;
            border-top: 1px solid #e9ecef;
            position: relative;
            z-index: 1;
        }

        @media (max-width: 768px) {
            .main-footer {
                margin-left: 0;
            }
        }

        /* Konsistensi card-header untuk semua halaman admin */
        .card-header {
            font-weight: 600;
        }

        /* Spacing page heading */
        .page-heading {
            margin-bottom: 1.5rem;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top">
        <div class="container-fluid">
            <button class="btn btn-link btn-sm text-white me-2 d-md-none" id="sidebarToggle">
                <i class="fas fa-bars"></i>
            </button>
            
            <a class="navbar-brand fw-bold" href="<?= site_url('dashboard'); ?>">
                <i class="fas fa-school me-2"></i>Sistem Presensi Kelas
            </a>
            
            <span class="navbar-text text-white d-none d-md-block">
                <?= isset($tahun_ajaran) ? 'T.A. ' . $tahun_ajaran->tahun_ajaran . ' Semester ' . $tahun_ajaran->semester : ''; ?>
            </span>
            
            <ul class="navbar-nav ms-auto">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle text-white" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                        <i class="fas fa-user-circle me-1"></i>
                        <?= isset($user_data['nama_lengkap']) ? html_escape($user_data['nama_lengkap']) : 'User'; ?>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="<?= site_url('profil'); ?>"><i class="fas fa-user me-2"></i>Profil</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item text-danger" href="<?= site_url('logout'); ?>"><i class="fas fa-sign-out-alt me-2"></i>Logout</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>
