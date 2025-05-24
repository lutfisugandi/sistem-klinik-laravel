<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>@yield('title', 'Dashboard') - {{ config('app.name', 'Klinik App') }}</title>

    <!-- AdminLTE CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Google Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    
    <!-- Custom Medical Theme CSS -->
    <style>
        :root {
            --medical-blue: #2563eb;
            --medical-teal: #0d9488;
            --medical-green: #16a34a;
        }
        
        .main-header.navbar {
            background: linear-gradient(135deg, var(--medical-blue), var(--medical-teal)) !important;
            border: none;
        }
        
        .main-sidebar {
            background: #ffffff;
            box-shadow: 2px 0 5px rgba(0,0,0,0.1);
        }
        
        .brand-link {
            background: linear-gradient(135deg, var(--medical-blue), var(--medical-teal));
            color: white !important;
            text-decoration: none;
        }
        
        .nav-sidebar .nav-link.active {
            background-color: var(--medical-blue) !important;
            color: white !important;
        }
        
        .nav-sidebar .nav-link:hover {
            background-color: rgba(37, 99, 235, 0.1) !important;
            color: var(--medical-blue) !important;
        }
        
        .card-primary {
            border-top: 3px solid var(--medical-blue);
        }
        
        .btn-primary {
            background-color: var(--medical-blue);
            border-color: var(--medical-blue);
        }
        
        .btn-primary:hover {
            background-color: #1d4ed8;
            border-color: #1d4ed8;
        }
        
        /* Custom Toast Styling */
        .toast-top-right {
            top: 70px;
            right: 12px;
        }
        
        .toast-success {
            background-color: var(--medical-green) !important;
        }
        
        .toast-error {
            background-color: #dc2626 !important;
        }
        
        .toast-warning {
            background-color: #d97706 !important;
        }
        
        .toast-info {
            background-color: var(--medical-blue) !important;
        }
    </style>
</head>

<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">
        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand navbar-dark">
            <!-- Left navbar links -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button">
                        <i class="fas fa-bars"></i>
                    </a>
                </li>
            </ul>

            <!-- Right navbar links -->
            <ul class="navbar-nav ml-auto">
                <!-- User Menu -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown">
                        <i class="fas fa-user-circle mr-1"></i>
                        {{ Auth::user()->name }}
                    </a>
                    <div class="dropdown-menu dropdown-menu-right">
                        <div class="dropdown-header">
                            <strong>{{ Auth::user()->role_display }}</strong>
                        </div>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="fas fa-sign-out-alt mr-2"></i> Logout
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </div>
                </li>
            </ul>
        </nav>

        <!-- Main Sidebar Container -->
        <aside class="main-sidebar sidebar-light-primary elevation-4">
            <!-- Brand Logo -->
            <a href="{{ route('dashboard') }}" class="brand-link text-center">
                <i class="fas fa-stethoscope fa-2x mb-2"></i>
                <br>
                <span class="brand-text font-weight-bold">Klinik App</span>
            </a>

            <!-- Sidebar -->
            <div class="sidebar">
                <!-- Sidebar Menu -->
                <nav class="mt-3">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu">
                        <!-- Dashboard -->
                        <li class="nav-item">
                            <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-tachometer-alt text-primary"></i>
                                <p>Dashboard</p>
                            </a>
                        </li>

                        @if(Auth::user()->isPendaftaran())
                        <!-- Pendaftaran Menu -->
                        <li class="nav-header">PENDAFTARAN</li>
                        <li class="nav-item">
                            <a href="{{ route('patients.index') }}" class="nav-link {{ request()->routeIs('patients*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-users text-info"></i>
                                <p>Data Pasien</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('patients.create') }}" class="nav-link">
                                <i class="nav-icon fas fa-user-plus text-success"></i>
                                <p>Daftar Pasien Baru</p>
                            </a>
                        </li>
                        @endif

                        @if(Auth::user()->isPerawat())
                        <!-- Perawat Menu -->
                        <li class="nav-header">PERAWAT</li>
                        <li class="nav-item">
                            <a href="{{ route('vitals.index') }}" class="nav-link {{ request()->routeIs('vitals*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-heartbeat text-danger"></i>
                                <p>Input Vital Signs</p>
                            </a>
                        </li>
                        @endif

                        @if(Auth::user()->isDokter())
                        <!-- Dokter Menu -->
                        <li class="nav-header">DOKTER</li>
                        <li class="nav-item">
                            <a href="{{ route('diagnosis.index') }}" class="nav-link {{ request()->routeIs('diagnosis*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-stethoscope text-warning"></i>
                                <p>Diagnosis Pasien</p>
                            </a>
                        </li>
                        @endif

                        @if(Auth::user()->isApoteker())
                        <!-- Apoteker Menu -->
                        <li class="nav-header">APOTEKER</li>
                        <li class="nav-item">
                            <a href="{{ route('medicines.index') }}" class="nav-link {{ request()->routeIs('medicines*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-pills text-success"></i>
                                <p>Data Obat</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('prescriptions.index') }}" class="nav-link {{ request()->routeIs('prescriptions*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-prescription text-primary"></i>
                                <p>Resep Obat</p>
                            </a>
                        </li>
                        @endif

                        <!-- Shared Menu -->
                        <li class="nav-header">LAPORAN</li>
                        <li class="nav-item">
                            <a href="{{ route('medical-records.index') }}" class="nav-link {{ request()->routeIs('medical-records*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-file-medical text-info"></i>
                                <p>Rekam Medis</p>
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
        </aside>

        <!-- Content Wrapper -->
        <div class="content-wrapper">
            <!-- Content Header -->
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0">@yield('title', 'Dashboard')</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                @yield('breadcrumb')
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <section class="content">
                <div class="container-fluid">
                    @yield('content')
                </div>
            </section>
        </div>

        <!-- Footer -->
        <footer class="main-footer">
            <strong>&copy; {{ date('Y') }} Klinik App.</strong>
            Sistem Manajemen Klinik
            <div class="float-right d-none d-sm-inline-block">
                <b>User:</b> {{ Auth::user()->role_display }}
            </div>
        </footer>
    </div>

    <!-- AdminLTE JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
    <!-- Toastr for notifications -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    
    <!-- Toast Notifications -->
    <script>
        // Configure toastr
        toastr.options = {
            "closeButton": true,
            "debug": false,
            "newestOnTop": true,
            "progressBar": true,
            "positionClass": "toast-top-right",
            "preventDuplicates": false,
            "onclick": null,
            "showDuration": "300",
            "hideDuration": "1000",
            "timeOut": "5000",
            "extendedTimeOut": "1000",
            "showEasing": "swing",
            "hideEasing": "linear",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut"
        };

        // Show notifications based on session flash data
        @if(session('success'))
            toastr.success('{{ session('success') }}', 'Berhasil!');
        @endif

        @if(session('error'))
            toastr.error('{{ session('error') }}', 'Error!');
        @endif

        @if(session('warning'))
            toastr.warning('{{ session('warning') }}', 'Peringatan!');
        @endif

        @if(session('info'))
            toastr.info('{{ session('info') }}', 'Informasi');
        @endif

        // Show validation errors
        @if($errors->any())
            @foreach($errors->all() as $error)
                toastr.error('{{ $error }}', 'Validation Error');
            @endforeach
        @endif
    </script>
    
    @stack('scripts')
</body>
</html>