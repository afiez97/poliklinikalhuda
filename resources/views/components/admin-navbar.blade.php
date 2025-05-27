<div class="ec-left-sidebar ec-bg-sidebar">
    <div id="sidebar" class="sidebar ec-sidebar-footer">

        <!-- Brand -->
        <div class="ec-brand">
            <a href="{{ route('admin.dashboard') }}" title="Ekka">
                <img class="ec-brand-icon" src="{{ asset('assets-admin/img/logo/logo-klinik-light.png') }}" alt="" style="width: 100px; height: auto;" />
            </a>
        </div>

        <!-- Sidebar Scrollable Navigation -->
        <div class="ec-navigation" data-simplebar>
            <ul class="nav sidebar-inner" id="sidebar-menu">
                <!-- Dashboard -->
                <li class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <a class="sidenav-item-link" href="{{ route('admin.dashboard') }}">
                        <i class="bi bi-speedometer2"></i>
                        <span class="nav-text">Dashboard</span>
                    </a>
                    <hr>
                </li>

                <!-- Temujanji -->
                <li class="has-sub {{ request()->is('admin/temujanji*') ? 'active' : '' }}">
                    <a class="sidenav-item-link" href="javascript:void(0)">
                        <i class="bi bi-calendar-check"></i>
                        <span class="nav-text">Temujanji</span> <b class="caret"></b>
                    </a>
                    <div class="collapse {{ request()->is('admin/temujanji*') ? 'show' : '' }}">
                        <ul class="sub-menu" id="temujanji" data-parent="#sidebar-menu">
                            <li><a class="sidenav-item-link" >Jadual Temujanji</a></li>
                            <li><a class="sidenav-item-link" >Buat Temujanji Baru</a></li>
                            <li><a class="sidenav-item-link" >Sejarah Temujanji</a></li>
                        </ul>
                    </div>
                </li>

                <!-- Pesakit -->
                <li class="has-sub {{ request()->is('admin/pesakit*') ? 'active' : '' }}">
                    <a class="sidenav-item-link" href="javascript:void(0)">
                        <i class="bi bi-people"></i>
                        <span class="nav-text">Pesakit</span> <b class="caret"></b>
                    </a>
                    <div class="collapse {{ request()->is('admin/pesakit*') ? 'show' : '' }}">
                        <ul class="sub-menu" id="pesakit" data-parent="#sidebar-menu">
                            <li><a class="sidenav-item-link" >Senarai Pesakit</a></li>
                            <li><a class="sidenav-item-link" >Daftar Pesakit Baharu</a></li>
                            <li><a class="sidenav-item-link" >Carian Pesakit</a></li>
                        </ul>
                    </div>
                </li>

                <!-- Rekod Perubatan -->
                <li class="{{ request()->routeIs('admin.medical-records') ? 'active' : '' }}">
                    <a class="sidenav-item-link" >
                        <i class="bi bi-journal-medical"></i>
                        <span class="nav-text">Rekod Perubatan</span>
                    </a>
                </li>

                <!-- Preskripsi / Ubat -->
                <li class="has-sub {{ request()->is('admin/prescription*') ? 'active' : '' }}">
                    <a class="sidenav-item-link" href="javascript:void(0)">
                        <i class="bi bi-capsule"></i>
                        <span class="nav-text">Preskripsi / Ubat</span> <b class="caret"></b>
                    </a>
                    <div class="collapse {{ request()->is('admin/prescription*') ? 'show' : '' }}">
                        <ul class="sub-menu" id="preskripsi" data-parent="#sidebar-menu">
                            <li><a class="sidenav-item-link" >Preskripsi Baru</a></li>
                            <li><a class="sidenav-item-link" >Senarai Preskripsi</a></li>
                            <li><a class="sidenav-item-link" >Inventori Ubat</a></li>
                        </ul>
                    </div>
                </li>

                <!-- Surat Rujukan -->
                <li class="{{ request()->routeIs('admin.referral-letter') ? 'active' : '' }}">
                    <a class="sidenav-item-link" >
                        <i class="bi bi-envelope-paper"></i>
                        <span class="nav-text">Surat Rujukan</span>
                    </a>
                </li>

                <!-- Laporan -->
                <li class="has-sub {{ request()->is('admin/reports*') ? 'active' : '' }}">
                    <a class="sidenav-item-link" href="javascript:void(0)">
                        <i class="bi bi-bar-chart-line"></i>
                        <span class="nav-text">Laporan</span> <b class="caret"></b>
                    </a>
                    <div class="collapse {{ request()->is('admin/reports*') ? 'show' : '' }}">
                        <ul class="sub-menu" id="laporan" data-parent="#sidebar-menu">
                            <li><a class="sidenav-item-link" >Laporan Harian</a></li>
                            <li><a class="sidenav-item-link" >Laporan Bulanan</a></li>
                            <li><a class="sidenav-item-link" >Eksport PDF/Excel</a></li>
                        </ul>
                    </div>
                </li>

                <!-- Bil & Pembayaran -->
                <li class="has-sub {{ request()->is('admin/billing*') ? 'active' : '' }}">
                    <a class="sidenav-item-link" href="javascript:void(0)">
                        <i class="bi bi-credit-card"></i>
                        <span class="nav-text">Bil & Pembayaran</span> <b class="caret"></b>
                    </a>
                    <div class="collapse {{ request()->is('admin/billing*') ? 'show' : '' }}">
                        <ul class="sub-menu" id="bil" data-parent="#sidebar-menu">
                            <li><a class="sidenav-item-link" >Bil Pesakit</a></li>
                            <li><a class="sidenav-item-link" >Transaksi</a></li>
                            <li><a class="sidenav-item-link" >Terimaan Harian</a></li>
                        </ul>
                    </div>
                </li>

                <!-- Staf / Pengguna Sistem -->
                <li class="has-sub {{ request()->is('admin/staff*') ? 'active' : '' }}">
                    <a class="sidenav-item-link" href="javascript:void(0)">
                        <i class="bi bi-person-badge"></i>
                        <span class="nav-text">Staf / Pengguna Sistem</span> <b class="caret"></b>
                    </a>
                    <div class="collapse {{ request()->is('admin/staff*') ? 'show' : '' }}">
                        <ul class="sub-menu" id="staf" data-parent="#sidebar-menu">
                            <li><a class="sidenav-item-link">Senarai Staf</a></li>
                            <li><a class="sidenav-item-link" >Peranan & Kebenaran</a></li>
                        </ul>
                    </div>
                </li>

                <!-- Tetapan Sistem -->
                <li class="{{ request()->routeIs('admin.system-settings') ? 'active' : '' }}">
                    <a class="sidenav-item-link" >
                        <i class="bi bi-gear"></i>
                        <span class="nav-text">Tetapan Sistem</span>
                    </a>
                </li>

                <!-- Profil Saya -->
                <li class="{{ request()->routeIs('admin.profile') ? 'active' : '' }}">
                    <a class="sidenav-item-link" >
                        <i class="bi bi-person-circle"></i>
                        <span class="nav-text">Profil Saya</span>
                    </a>
                </li>

                <!-- Log Keluar -->
                <li>
                    <a class="sidenav-item-link" >
                        <i class="bi bi-box-arrow-right"></i>
                        <span class="nav-text">Log Keluar</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>
