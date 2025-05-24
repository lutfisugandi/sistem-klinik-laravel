<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

    <!-- Custom Medical Theme CSS -->
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #2563eb 0%, #0d9488 100%);
            min-height: 100vh;
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .login-container {
            width: 100%;
            max-width: 400px;
            padding: 20px;
        }
        
        .medical-logo {
            text-align: center;
            margin-bottom: 30px;
        }
        
        .medical-logo i {
            font-size: 4rem;
            color: white;
            margin-bottom: 15px;
        }
        
        .medical-logo h1 {
            color: white;
            font-size: 2.5rem;
            margin: 0;
            font-weight: bold;
        }
        
        .medical-logo p {
            color: rgba(255,255,255,0.8);
            margin: 5px 0 0 0;
            font-size: 1.1rem;
        }
        
        .login-card {
            background: white;
            border-radius: 15px;
            padding: 40px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.1);
            backdrop-filter: blur(10px);
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #374151;
        }
        
        .input-group {
            position: relative;
        }
        
        .input-icon {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #9CA3AF;
        }
        
        .form-control {
            width: 100%;
            padding: 12px 12px 12px 40px;
            border: 2px solid #E5E7EB;
            border-radius: 8px;
            font-size: 16px;
            transition: border-color 0.3s;
            box-sizing: border-box;
        }
        
        .form-control:focus {
            outline: none;
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }
        
        .form-check {
            display: flex;
            align-items: center;
            margin: 20px 0;
        }
        
        .form-check-input {
            margin-right: 8px;
        }
        
        .btn-login {
            width: 100%;
            background: linear-gradient(135deg, #2563eb, #0d9488);
            color: white;
            padding: 14px;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.2s;
            margin-top: 10px;
        }
        
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(37, 99, 235, 0.3);
        }
        
        .alert {
            background: #FEE2E2;
            border: 1px solid #FECACA;
            color: #DC2626;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        
        .demo-accounts {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #E5E7EB;
        }
        
        .demo-accounts h6 {
            color: #374151;
            font-weight: 600;
            margin-bottom: 12px;
        }
        
        .demo-accounts .account-list {
            font-size: 12px;
            color: #6B7280;
            line-height: 1.5;
        }
        
        .account-list div {
            margin-bottom: 4px;
        }
        
        .forgot-password {
            color: #6B7280;
            text-decoration: none;
            font-size: 14px;
        }
        
        .forgot-password:hover {
            color: #2563eb;
        }
        
        .login-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <!-- Logo -->
        <div class="medical-logo">
            <i class="fas fa-stethoscope"></i>
            <h1>Klinik App</h1>
            <p>Sistem Manajemen Klinik</p>
        </div>

        <div class="login-card">
            <!-- Session Status -->
            @if (session('status'))
                <div class="alert">
                    {{ session('status') }}
                </div>
            @endif

            <!-- Errors -->
            @if ($errors->any())
                <div class="alert">
                    @foreach ($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <!-- Email Address -->
                <div class="form-group">
                    <label for="email" class="form-label">Email</label>
                    <div class="input-group">
                        <i class="fas fa-envelope input-icon"></i>
                        <input id="email" 
                               class="form-control" 
                               type="email" 
                               name="email" 
                               value="{{ old('email') }}" 
                               required 
                               autofocus 
                               autocomplete="username" 
                               placeholder="Masukkan email Anda" />
                    </div>
                </div>

                <!-- Password -->
                <div class="form-group">
                    <label for="password" class="form-label">Password</label>
                    <div class="input-group">
                        <i class="fas fa-lock input-icon"></i>
                        <input id="password" 
                               class="form-control"
                               type="password"
                               name="password"
                               required 
                               autocomplete="current-password"
                               placeholder="Masukkan password Anda" />
                    </div>
                </div>

                <!-- Remember Me -->
                <div class="form-check">
                    <input id="remember_me" type="checkbox" name="remember">
                    <label for="remember_me">Ingat saya</label>
                </div>

                <!-- Login Button & Forgot Password -->
                <button type="submit" class="btn-login">
                    <i class="fas fa-sign-in-alt" style="margin-right: 8px;"></i>
                    Masuk
                </button>

                <div class="login-footer">
                    @if (Route::has('password.request'))
                        <a class="forgot-password" href="{{ route('password.request') }}">
                            Lupa password?
                        </a>
                    @endif
                </div>
            </form>

            <!-- Demo Accounts -->
            <div class="demo-accounts">
                <h6>Demo Accounts:</h6>
                <div class="account-list">
                    <div><strong>Pendaftaran:</strong> pendaftaran@klinik.com</div>
                    <div><strong>Dokter:</strong> dokter@klinik.com</div>
                    <div><strong>Perawat:</strong> perawat@klinik.com</div>
                    <div><strong>Apoteker:</strong> apoteker@klinik.com</div>
                    <div><strong>Password:</strong> password</div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>