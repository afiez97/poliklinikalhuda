<!doctype html>
<html class="no-js" lang="zxx">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>@yield('title', __('medical.clinic') . ' - Poliklinik Al-Huda')</title>
    <meta name="description" content="@yield('description', __('medical.clinic_description', ['name' => 'Poliklinik Al-Huda']))">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Place favicon.ico in the root directory -->
    <link rel="shortcut icon" type="image/x-icon" href="../assets-portal/img/medical/logo/favicon.png">

    <!-- CSS here -->
    <link rel="stylesheet" href="../assets-portal/css/bootstrap.css">
    <link rel="stylesheet" href="../assets-portal/css/swiper-bundle.css">
    <link rel="stylesheet" href="../assets-portal/css/magnific-popup.css">
    <link rel="stylesheet" href="../assets-portal/css/font-awesome-pro.css">
    <link rel="stylesheet" href="../assets-portal/css/spacing.css">
    <link rel="stylesheet" href="../assets-portal/css/main.css">
    @vite(['resources/scss/app.scss', 'resources/js/app.js'])

</head>

<body>

    <!-- Preloader Start -->
    <div class="preloader med-loading">
        <div class="med-loader"></div>
    </div>
    <!-- Preloader End -->

    <!-- back to top start -->
    <div class="back-to-top-wrapper">
        <button id="back_to_top" type="button" class="back-to-top-btn back-to-top-btn-med">
            <svg width="12" height="7" viewBox="0 0 12 7" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M11 6L6 1L1 6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                    stroke-linejoin="round" />
            </svg>
        </button>
    </div>
    <!-- back to top end -->

    <!-- tp-offcanvus-area-end -->
    <div class="tp-offcanvas-area">
        <div class="tp-offcanvas-wrapper">
            <div class="tp-offcanvas-top d-flex align-items-center justify-content-between">
                <div class="tp-offcanvas-logo">
                    <a href="index.html">
                        <img data-width="110" src="../assets-portal/img/medical/logo/logo.png" alt="">
                    </a>
                </div>
                <div class="tp-offcanvas-close">
                    <button class="tp-offcanvas-close-btn">
                        <svg width="37" height="38" viewBox="0 0 37 38" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path d="M9.19141 9.80762L27.5762 28.1924" stroke="currentColor" stroke-width="1.5"
                                stroke-linecap="round" stroke-linejoin="round" />
                            <path d="M9.19141 28.1924L27.5762 9.80761" stroke="currentColor" stroke-width="1.5"
                                stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </button>
                </div>
            </div>
            <div class="tp-offcanvas-main">
                <div class="tp-offcanvas-content">
                    <h3 class="tp-offcanvas-title">{{ __('common.welcome') }}!</h3>
                    <p>{{ __('medical.clinic_description', ['name' => 'Poliklinik Al-Huda']) }}</p>

                    <!-- Language Switcher for Mobile -->
                    <div class="tp-offcanvas-language mt-3">
                        <x-language-switcher />
                    </div>
                </div>
                <div class="tp-offcanvas-menu d-xl-none">
                    <nav></nav>
                </div>
                <div class="tp-offcanvas-contact">
                    <h3 class="tp-offcanvas-title fs-20">{{ __('common.info') }}</h3>
                    <ul>
                        <li><a href="tel:1245654">+ 4 20 7700 1007</a></li>
                        <li><a href="mailto:hello@diego.com">hello@brizmo.com</a></li>
                        <li><a href="#">Avenue de Roma 158b, Lisboa</a></li>
                    </ul>
                </div>
                <div class="tp-offcanvas-social">
                    <h3 class="tp-offcanvas-title sm">Follow Us</h3>
                    <ul>
                        <li>
                            <a href="#">
                                <svg width="16" height="16" viewBox="0 0 16 16" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M11.25 1.5H4.75C2.95507 1.5 1.5 2.95507 1.5 4.75V11.25C1.5 13.0449 2.95507 14.5 4.75 14.5H11.25C13.0449 14.5 14.5 13.0449 14.5 11.25V4.75C14.5 2.95507 13.0449 1.5 11.25 1.5Z"
                                        stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                    <path
                                        d="M10.6016 7.5907C10.6818 8.13166 10.5894 8.68414 10.3375 9.16955C10.0856 9.65497 9.68711 10.0486 9.19862 10.2945C8.71014 10.5404 8.15656 10.6259 7.61663 10.5391C7.0767 10.4522 6.57791 10.1972 6.19121 9.81055C5.80451 9.42385 5.54959 8.92506 5.46271 8.38513C5.37583 7.8452 5.46141 7.29163 5.70728 6.80314C5.95315 6.31465 6.34679 5.91613 6.83221 5.66425C7.31763 5.41238 7.87011 5.31998 8.41107 5.4002C8.96287 5.48202 9.47372 5.73915 9.86817 6.1336C10.2626 6.52804 10.5197 7.0389 10.6016 7.5907Z"
                                        stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                    <path d="M11.5742 4.42578H11.5842" stroke="currentColor" stroke-width="1.5"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </a>
                        </li>
                        <li>
                            <a href="#">
                                <svg width="18" height="18" viewBox="0 0 18 18" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M2.50589 12.7494C4.57662 16.336 9.16278 17.5648 12.7494 15.4941C14.2113 14.65 15.2816 13.388 15.8962 11.9461C16.7895 9.85066 16.7208 7.37526 15.4941 5.25063C14.2674 3.12599 12.1581 1.82872 9.89669 1.55462C8.34063 1.366 6.71259 1.66183 5.25063 2.50589C1.66403 4.57662 0.435172 9.16278 2.50589 12.7494Z"
                                        stroke="currentColor" stroke-width="1.5" />
                                    <path
                                        d="M12.7127 15.4292C12.7127 15.4292 12.0086 10.4867 10.5011 7.87559C8.99362 5.26451 5.28935 2.57155 5.28935 2.57155M5.68449 15.6124C6.79553 12.2606 12.34 8.54524 16.3975 9.43537M12.311 2.4082C11.1953 5.72344 5.75732 9.38453 1.71875 8.58915"
                                        stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                                </svg>
                            </a>
                        </li>
                        <li>
                            <a href="#">
                                <svg width="18" height="11" viewBox="0 0 18 11" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M1 5.5715H6.33342C7.62867 5.5715 8.61917 6.56199 8.61917 7.85725C8.61917 9.15251 7.62867 10.143 6.33342 10.143H1.76192C1.30477 10.143 1 9.83823 1 9.38108V1.76192C1 1.30477 1.30477 1 1.76192 1H5.5715C6.86676 1 7.85725 1.99049 7.85725 3.28575C7.85725 4.58101 6.86676 5.5715 5.5715 5.5715H1Z"
                                        stroke="currentColor" stroke-width="1.5" stroke-miterlimit="10" />
                                    <path
                                        d="M10.9062 7.09454H17.0016C17.0016 5.41832 15.6301 4.04688 13.9539 4.04688C12.2777 4.04688 10.9062 5.41832 10.9062 7.09454ZM10.9062 7.09454C10.9062 8.77076 12.2777 10.1422 13.9539 10.1422H15.2492"
                                        stroke="currentColor" stroke-width="1.5" stroke-miterlimit="10"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                    <path d="M16.1125 1.44434H11.668" stroke="currentColor" stroke-width="1.2"
                                        stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </a>
                        </li>
                        <li>
                            <a href="#">
                                <svg width="18" height="14" viewBox="0 0 18 14" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M12.75 13H5.25C3 13 1.5 11.5 1.5 9.25V4.75C1.5 2.5 3 1 5.25 1H12.75C15 1 16.5 2.5 16.5 4.75V9.25C16.5 11.5 15 13 12.75 13Z"
                                        stroke="currentColor" stroke-width="1.5" stroke-miterlimit="10"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                    <path
                                        d="M8.70676 5.14837L10.8006 6.40465C11.5543 6.90716 11.5543 7.66093 10.8006 8.16344L8.70676 9.41972C7.86923 9.92224 7.19922 9.50348 7.19922 8.5822V6.06964C7.19922 4.98086 7.86923 4.64585 8.70676 5.14837Z"
                                        fill="currentColor" />
                                </svg>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="body-overlay"></div>
    <!-- tp-offcanvus-area-end -->


    <header class="tp-header-height">

        <!-- header-area-start -->
        <div id="tp-header-sticky" class="tp-header-area tp-header-md-space tp-header-transparent med-sticky-bg">
            <div class="container container-1710">
                <div class="row align-items-center p-relative">
                    <div class="col-xl-2 col-5">
                        <div class="tp-header-logo lh-1">
                            <a href="index.html"><img data-width="110" src="../assets-portal/img/medical/logo/logo.png"
                                    alt="logo"></a>
                        </div>
                    </div>
                    <x-portal.navbar />


                    <div class="col-xl-2 col-7">
                        <div class="tp-header-med-btn d-flex align-items-center justify-content-end">
                            <!-- Language Switcher -->
                            <div class="me-3">
                                <x-language-switcher />
                            </div>

                            <!-- Authentication Menu -->
                            <ul class="tp-header-auth-menu list-unstyled m-0">
                                @auth
                                    <li class="has-dropdown position-relative">
                                        <button type="button" class="d-flex align-items-center btn btn-link p-0" data-bs-toggle="dropdown" aria-expanded="false" style="text-decoration: none; color: inherit;">
                                            @if(auth()->user()->avatar)
                                                <img src="{{ auth()->user()->avatar }}" alt="Profile" class="rounded-circle me-2" style="width: 24px; height: 24px;">
                                            @else
                                                <i class="fas fa-user-circle me-2"></i>
                                            @endif
                                            {{ auth()->user()->name }}
                                            <i class="fas fa-chevron-down ms-2"></i>
                                        </button>
                                        <ul class="tp-submenu submenu dropdown-menu">
                                            <li><a class="dropdown-item" href="#"><i class="fas fa-user me-2"></i>{{ __('common.profile') }}</a></li>
                                            <li><a class="dropdown-item" href="#"><i class="fas fa-calendar me-2"></i>{{ __('medical.appointment') }}</a></li>
                                            <li>
                                                <form method="POST" action="{{ route('logout') }}">
                                                    @csrf
                                                    <button type="submit" class="dropdown-item">
                                                        <i class="fas fa-sign-out-alt me-2"></i>{{ __('common.logout') }}
                                                    </button>
                                                </form>
                                            </li>
                                        </ul>
                                    </li>
                                @else
                                    <li class="has-dropdown position-relative">
                                        <button type="button" class="btn btn-link p-0" data-bs-toggle="dropdown" aria-expanded="false" style="text-decoration: none; color: inherit;">
                                            <i class="fas fa-sign-in-alt me-2"></i>{{ __('common.login') }}
                                            <i class="fas fa-chevron-down ms-2"></i>
                                        </button>
                                        <ul class="tp-submenu submenu dropdown-menu">
                                            <li>
                                                <a class="dropdown-item d-flex align-items-center" href="{{ route('auth.google') }}">
                                                    <svg width="18" height="18" viewBox="0 0 24 24" class="me-2">
                                                        <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                                                        <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                                                        <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                                                        <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                                                    </svg>
                                                    {{ __('auth.google_login') }}
                                                </a>
                                            </li>
                                            <li><a class="dropdown-item" href="#"><i class="fas fa-envelope me-2"></i>{{ __('auth.login.title') }}</a></li>
                                            <li><a class="dropdown-item" href="#"><i class="fas fa-user-plus me-2"></i>{{ __('auth.register.title') }}</a></li>
                                        </ul>
                                    </li>
                                @endauth
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- header-area-end -->

    </header>

    <!-- Flash Messages -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show m-0" role="alert" style="border-radius: 0;">
            <div class="container">
                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show m-0" role="alert" style="border-radius: 0;">
            <div class="container">
                <i class="fas fa-exclamation-triangle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
    @endif

    @if(session('info'))
        <div class="alert alert-info alert-dismissible fade show m-0" role="alert" style="border-radius: 0;">
            <div class="container">
                <i class="fas fa-info-circle me-2"></i>{{ session('info') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
    @endif

    <main>
        @yield('content')

    </main>

    <footer>
        <!-- footer area start -->
        <div class="tp-footer-area pt-80 tp-bg-common-black-4 pb-40">
            <div class="container container-1230">
                <div class="row gx-24">
                    <div class="col-lg-4 col-md-6">
                        <div class="tp-footer-med-widget mr-75 mb-50">
                            <a class="mb-35 d-inline-block" href="index.html"><img data-width="108"
                                    src="../assets-portal/img/medical/logo/logo-white.png" alt=""></a>
                            <p class="tp-text-rgba-3 tp-ff-roboto fw-500 mb-20 lh-30">{{ __('medical.clinic_description', ['name' => 'Poliklinik Al-Huda']) }}</p>
                            <div class="tp-footer-med-social d-flex">
                                <a href="#"><i class="fa-brands fa-facebook-f"></i></a>
                                <a href="#">
                                    <svg width="16" height="15" viewBox="0 0 16 15" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M5.25954 0.646484H0.588257L6.12678 7.92395L0.942558 14.0288H3.33782L7.25882 9.41152L10.7405 13.9864H15.4118L9.71235 6.49747L9.72244 6.51039L14.6297 0.731499H12.2345L8.59022 5.02298L5.25954 0.646484ZM3.16672 1.921H4.62096L12.8333 12.7118H11.3791L3.16672 1.921Z"
                                            fill="currentColor" />
                                    </svg>
                                </a>
                                <a href="#"><i class="fa-brands fa-linkedin-in"></i></a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6">
                        <div class="tp-footer-med-widget mr-85 mb-50">
                            <h3 class="tp-ff-poppins fw-600 fs-26 ls-m-2 tp-text-common-white mb-40">Check our location
                            </h3>
                            <a class="tp-text-rgba-3 tp-ff-roboto fw-500 mb-15 lh-30 d-block hover-text-white"
                                href="https://www.google.com/maps" target="_blank">5058, South settle, New York, South
                                Bend
                                IN 36001 sunway piramid</a>
                            <a href="tel:1234564890"
                                class="tp-ff-poppins fw-500 tp-text-common-white hover-text-white">
                                <svg width="18" height="18" viewBox="0 0 18 18" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M16.4775 13.7473C16.4775 14.0173 16.4175 14.2948 16.29 14.5648C16.1625 14.8348 15.9975 15.0898 15.78 15.3298C15.4125 15.7348 15.0075 16.0273 14.55 16.2148C14.1 16.4023 13.6125 16.4998 13.0875 16.4998C12.3225 16.4998 11.505 16.3198 10.6425 15.9523C9.77997 15.5848 8.91747 15.0898 8.06247 14.4673C7.19997 13.8373 6.38247 13.1398 5.60247 12.3673C4.82997 11.5873 4.13247 10.7698 3.50997 9.91476C2.89497 9.05976 2.39997 8.20476 2.03997 7.35726C1.67997 6.50226 1.49997 5.68476 1.49997 4.90476C1.49997 4.39476 1.58997 3.90726 1.76997 3.45726C1.94997 2.99976 2.23497 2.57976 2.63247 2.20476C3.11247 1.73226 3.63747 1.49976 4.19247 1.49976C4.40247 1.49976 4.61247 1.54476 4.79997 1.63476C4.99497 1.72476 5.16747 1.85976 5.30247 2.05476L7.04247 4.50726C7.17747 4.69476 7.27497 4.86726 7.34247 5.03226C7.40997 5.18976 7.44747 5.34726 7.44747 5.48976C7.44747 5.66976 7.39497 5.84976 7.28997 6.02226C7.19247 6.19476 7.04997 6.37476 6.86997 6.55476L6.29997 7.14726C6.21747 7.22976 6.17997 7.32726 6.17997 7.44726C6.17997 7.50726 6.18747 7.55976 6.20247 7.61976C6.22497 7.67976 6.24747 7.72476 6.26247 7.76976C6.39747 8.01726 6.62997 8.33976 6.95997 8.72976C7.29747 9.11976 7.65747 9.51726 8.04747 9.91476C8.45247 10.3123 8.84247 10.6798 9.23997 11.0173C9.62997 11.3473 9.95247 11.5723 10.2075 11.7073C10.245 11.7223 10.29 11.7448 10.3425 11.7673C10.4025 11.7898 10.4625 11.7973 10.53 11.7973C10.6575 11.7973 10.755 11.7523 10.8375 11.6698L11.4075 11.1073C11.595 10.9198 11.775 10.7773 11.9475 10.6873C12.12 10.5823 12.2925 10.5298 12.48 10.5298C12.6225 10.5298 12.7725 10.5598 12.9375 10.6273C13.1025 10.6948 13.275 10.7923 13.4625 10.9198L15.945 12.6823C16.14 12.8173 16.275 12.9748 16.3575 13.1623C16.4325 13.3498 16.4775 13.5373 16.4775 13.7473Z"
                                        stroke="white" stroke-width="1.5" stroke-miterlimit="10" />
                                    <path d="M12.15 5.85H15.75M12.15 5.85V2.25V5.85Z" stroke="white"
                                        stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                123 456 4890 <span class="free ml-5">Free</span></a>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-8">
                        <div class="tp-footer-med-widget mb-50">
                            <h3 class="tp-ff-poppins fw-600 fs-26 ls-m-2 tp-text-common-white mb-40">{{ __('messages.opening_hours') }}</h3>
                            <ul class="tp-footer-med-time">
                                <li>{{ __('messages.schedule.weekdays') }}</li>
                                <li>{{ __('messages.schedule.saturday') }}</li>
                                <li>{{ __('messages.schedule.sunday') }}</li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="tp-footer-med-bottom tp-round-6 d-flex mt-50 flex-wrap justify-content-between">
                            <div class="tp-footer-med-copyright mr-30">
                                <p class="tp-ff-roboto fw-500 tp-text-rgba-3 mb-10">© 2025 <a
                                        class="tp-text-common-white" href="#">Klinik Alhuda.</a> All Rights
                                    Reserved.</p>
                            </div>
                            <div class="tp-footer-med-bottom-menu">
                                <ul>
                                    <li><a href="index.html">{{ __('common.home') }}</a></li>
                                    <li><a href="about.html">{{ __('common.about') }}</a></li>
                                    <li><a href="treatments.html">{{ __('medical.treatment') }}</a></li>
                                    <li><a href="team.html">{{ __('medical.doctor') }}</a></li>
                                    <li><a href="timetable.html">{{ __('medical.schedule') }}</a></li>
                                    <li><a href="contact.html">{{ __('common.contact') }}</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- footer area end -->
    </footer>



    <!-- JS here -->
    <script src="../assets-portal/js/vendor/jquery.js"></script>
    <script src="../assets-portal/js/jquery.appear.js"></script>
    <script src="../assets-portal/js/bootstrap-bundle.js"></script>
    <script src="../assets-portal/js/swiper-bundle.js"></script>
    <script src="../assets-portal/js/magnific-popup.js"></script>
    <script src="../assets-portal/js/nice-select.js"></script>
    <script src="../assets-portal/js/isotope-pkgd.js"></script>
    <script src="../assets-portal/js/imagesloaded-pkgd.js"></script>
    <script src="../assets-portal/js/ajax-form.js"></script>
    <script src="../assets-portal/js/anime.min.js"></script>
    <script src="../assets-portal/js/parallax.js"></script>
    <script src="../assets-portal/js/parallax-scrool.js"></script>
    <script src="../assets-portal/js/Jarallax.js"></script>
    <script src="../assets-portal/js/atropos.js"></script>
    <script src="../assets-portal/js/pie-chart.js"></script>
    <script src="../assets-portal/js/slider-init.js"></script>
    <script src="../assets-portal/js/main.js"></script>

</body>

</html>
