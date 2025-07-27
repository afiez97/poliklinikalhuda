<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="ltr">

<head>
	<meta charset="utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<meta name="description" content="{{ __('auth.admin.login_title') }} - {{ __('auth.admin.system_title') }}">

	<title>{{ __('auth.admin.login_title') }} - {{ __('auth.admin.system_title') }}</title>

	<!-- GOOGLE FONTS -->
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link
		href="https://fonts.googleapis.com/css2?family=Montserrat:wght@200;300;400;500;600;700;800&family=Poppins:wght@300;400;500;600;700;800;900&family=Roboto:wght@400;500;700;900&display=swap"
		rel="stylesheet">

	<link href="https://cdn.materialdesignicons.com/4.4.95/css/materialdesignicons.min.css" rel="stylesheet" />
    <!-- PLUGINS CSS STYLE -->
    <link href="{{ asset('assets-admin/plugins/daterangepicker/daterangepicker.css') }}" rel="stylesheet">
    <link href="{{ asset('assets-admin/plugins/simplebar/simplebar.css') }}" rel="stylesheet" />

    <!-- Ekka CSS -->
    <link id="ekka-css" href="{{ asset('assets-admin/css/ekka.css') }}" rel="stylesheet" />

    <!-- FAVICON -->
    <link href="{{ asset('assets-admin/img/favicon.png') }}" rel="shortcut icon" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">

    <style>
        .login-wrapper {
            min-height: 100vh;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .login-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            max-width: 1000px;
            width: 100%;
        }

        .login-bg {
            background: url('{{ asset('bg-login-admin.avif') }}') center/cover;
            background-color: #667eea;
            position: relative;
        }

        .login-bg::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(102, 126, 234, 0.8);
        }

        .login-content {
            position: relative;
            z-index: 2;
            color: white;
            padding: 60px 40px;
            text-align: center;
        }

        .login-form {
            padding: 60px 40px;
        }

        .logo {
            max-width: 200px;
            margin-bottom: 20px;
        }

        .form-control {
            border: 2px solid #e9ecef;
            border-radius: 10px;
            padding: 15px 20px;
            font-size: 16px;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }

        .btn-login {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 10px;
            padding: 15px 30px;
            font-size: 16px;
            font-weight: 600;
            color: white;
            width: 100%;
            transition: all 0.3s ease;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3);
            color: white;
        }

        .btn-google {
            border: 2px solid #dc3545;
            color: #dc3545;
            padding: 12px 30px;
            font-size: 16px;
            font-weight: 600;
            border-radius: 10px;
            transition: all 0.3s ease;
        }

        .btn-google:hover {
            background-color: #dc3545;
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 8px 15px rgba(220, 53, 69, 0.3);
        }

        .form-label {
            font-weight: 600;
            color: #495057;
            margin-bottom: 8px;
        }

        .alert {
            border-radius: 10px;
            border: none;
        }

        .text-muted {
            font-size: 14px;
        }

        /* Language switcher for login page */
        .login-form .language-switcher .btn {
            border-color: #dee2e6;
            color: #495057;
            background: transparent;
        }

        .login-form .language-switcher .btn:hover {
            background-color: #667eea;
            color: white;
            border-color: #667eea;
        }

        @media (max-width: 768px) {
            .login-bg {
                display: none;
            }
            .login-form {
                padding: 40px 30px;
            }
        }
    </style>
</head>

<body>
    <div class="login-wrapper">
        <div class="login-card">
            <div class="row g-0">
                <div class="col-lg-6 login-bg">
                    <div class="login-content">
                        <img src="{{ asset('logo-light-removebg.png') }}" alt="Logo" class="logo">
                        <h2 class="mb-3">{{ __('auth.admin.welcome') }}</h2>
                        <p class="mb-0">{{ __('auth.admin.system_title') }}</p>
                        <p class="mt-3">{{ __('auth.admin.welcome_message') }}</p>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="login-form">
                        <!-- Language Switcher -->
                        <div class="d-flex justify-content-end mb-3">
                            <x-language-switcher />
                        </div>

                        <div class="text-center mb-4">
                            <h3 class="mb-2">{{ __('auth.admin.login_title') }}</h3>
                            <p class="text-muted">{{ __('auth.admin.login_subtitle') }}</p>
                        </div>

                        @if ($errors->any())
                            <div class="alert alert-danger mb-4">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        @if (session('error'))
                            <div class="alert alert-danger mb-4">
                                {{ session('error') }}
                            </div>
                        @endif

                        @if (session('success'))
                            <div class="alert alert-success mb-4">
                                {{ session('success') }}
                            </div>
                        @endif

                        <form method="POST" action="{{ route('admin.doLogin') }}">
                            @csrf

                            <div class="mb-3">
                                <label for="username" class="form-label">{{ __('auth.admin.username') }}</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="mdi mdi-account"></i>
                                    </span>
                                    <input type="text"
                                           class="form-control @error('username') is-invalid @enderror"
                                           id="username"
                                           name="username"
                                           value="{{ old('username') }}"
                                           placeholder="{{ __('auth.admin.username_placeholder') }}"
                                           required
                                           autofocus>
                                </div>
                                @error('username')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="password" class="form-label">{{ __('auth.admin.password') }}</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="mdi mdi-lock"></i>
                                    </span>
                                    <input type="password"
                                           class="form-control @error('password') is-invalid @enderror"
                                           id="password"
                                           name="password"
                                           placeholder="{{ __('auth.admin.password_placeholder') }}"
                                           required>
                                    <button type="button" class="input-group-text" id="togglePassword">
                                        <i class="mdi mdi-eye" id="toggleIcon"></i>
                                    </button>
                                </div>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="remember" name="remember">
                                    <label class="form-check-label" for="remember">
                                        {{ __('auth.admin.remember_me') }}
                                    </label>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-login">
                                <i class="mdi mdi-login me-2"></i>{{ __('auth.admin.login_button') }}
                            </button>
                        </form>

                        <div class="text-center mt-3">
                            <p class="text-muted mb-3">{{ __('auth.admin.or') }}</p>
                            <a href="{{ route('auth.google') }}" class="btn btn-google w-100 d-flex align-items-center justify-content-center">
                                <svg width="18" height="18" viewBox="0 0 24 24" class="me-2">
                                    <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                                    <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                                    <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                                    <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                                </svg>
                                {{ __('auth.admin.google_login') }}
                            </a>
                        </div>

                        <div class="text-center mt-4">
                            <a href="{{ route('portal.home') }}" class="text-muted">
                                <i class="mdi mdi-arrow-left me-1"></i>{{ __('auth.admin.back_home') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Common Javascript -->
    <script src="{{ asset('assets-admin/plugins/jquery/jquery-3.5.1.min.js') }}"></script>
    <script src="{{ asset('assets-admin/js/bootstrap.bundle.min.js') }}"></script>

    <script>
        // Toggle password visibility
        document.getElementById('togglePassword').addEventListener('click', function() {
            const passwordInput = document.getElementById('password');
            const toggleIcon = document.getElementById('toggleIcon');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.classList.remove('mdi-eye');
                toggleIcon.classList.add('mdi-eye-off');
            } else {
                passwordInput.type = 'password';
                toggleIcon.classList.remove('mdi-eye-off');
                toggleIcon.classList.add('mdi-eye');
            }
        });

        // Auto-focus on first input
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('username').focus();
        });
    </script>
</body>

</html>
