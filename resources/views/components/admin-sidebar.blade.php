<div class="ec-left-sidebar ec-bg-sidebar">
    <div id="sidebar" class="sidebar ec-sidebar-footer">

        <!-- Brand -->
        <div class="ec-brand">
            <a href="{{ route('admin.dashboard') }}" title="Ekka">
                <img class="ec-brand-icon" src="{{ asset('logo-light-removebg.png') }}" alt="" style="width: 100px; height: auto;" />
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
                <li class="has-sub {{ request()->is('admin/appointments*') ? 'active' : '' }}">
                    <a class="sidenav-item-link" href="javascript:void(0)">
                        <i class="bi bi-calendar-check"></i>
                        <span class="nav-text">Temujanji</span> <b class="caret"></b>
                    </a>
                    <div class="collapse {{ request()->is('admin/appointments*') ? 'show' : '' }}">
                        <ul class="sub-menu" id="temujanji" data-parent="#sidebar-menu">
                            <li><a class="sidenav-item-link" href="{{ route('admin.appointments.calendar') }}">Jadual Temujanji</a></li>
                            <li><a class="sidenav-item-link" href="{{ route('admin.appointments.create') }}">Buat Temujanji Baru</a></li>
                            <li><a class="sidenav-item-link" href="{{ route('admin.appointments') }}">Sejarah Temujanji</a></li>
                        </ul>
                    </div>
                </li>

                <!-- Pesakit -->
                <li class="has-sub {{ request()->is('admin/patients*') ? 'active' : '' }}">
                    <a class="sidenav-item-link" href="javascript:void(0)">
                        <i class="bi bi-people"></i>
                        <span class="nav-text">Pesakit</span> <b class="caret"></b>
                    </a>
                    <div class="collapse {{ request()->is('admin/patients*') ? 'show' : '' }}">
                        <ul class="sub-menu" id="pesakit" data-parent="#sidebar-menu">
                            <li><a class="sidenav-item-link" href="{{ route('admin.patients.index') }}">Senarai Pesakit</a></li>
                            <li><a class="sidenav-item-link" href="{{ route('admin.patients.create') }}">Daftar Pesakit Baharu</a></li>
                        </ul>
                    </div>
                </li>

                <!-- Giliran / Queue Management -->
                <li class="has-sub {{ request()->is('admin/queue*') ? 'active' : '' }}">
                    <a class="sidenav-item-link" href="javascript:void(0)">
                        <i class="bi bi-people-fill"></i>
                        <span class="nav-text">Giliran</span> <b class="caret"></b>
                    </a>
                    <div class="collapse {{ request()->is('admin/queue*') ? 'show' : '' }}">
                        <ul class="sub-menu" id="giliran" data-parent="#sidebar-menu">
                            <li><a class="sidenav-item-link" href="{{ route('admin.queue.index') }}">Dashboard Giliran</a></li>
                            <li><a class="sidenav-item-link" href="{{ route('admin.queue.display') }}" target="_blank">Paparan Awam</a></li>
                            <li><a class="sidenav-item-link" href="{{ route('admin.queue.kiosk') }}" target="_blank">Kiosk</a></li>
                            <li><a class="sidenav-item-link" href="{{ route('admin.queue.types') }}">Jenis Giliran</a></li>
                            <li><a class="sidenav-item-link" href="{{ route('admin.queue.counters') }}">Kaunter</a></li>
                            <li><a class="sidenav-item-link" href="{{ route('admin.queue.reports') }}">Laporan Giliran</a></li>
                        </ul>
                    </div>
                </li>

                <!-- Rekod Perubatan / EMR -->
                <li class="has-sub {{ request()->is('admin/emr*') || request()->is('admin/encounters*') ? 'active' : '' }}">
                    <a class="sidenav-item-link" href="javascript:void(0)">
                        <i class="bi bi-journal-medical"></i>
                        <span class="nav-text">Rekod Perubatan (EMR)</span> <b class="caret"></b>
                    </a>
                    <div class="collapse {{ request()->is('admin/emr*') || request()->is('admin/encounters*') ? 'show' : '' }}">
                        <ul class="sub-menu" id="emr" data-parent="#sidebar-menu">
                            <li><a class="sidenav-item-link" href="{{ route('admin.emr.encounters.index') }}">Senarai Encounter</a></li>
                            <li><a class="sidenav-item-link" href="{{ route('admin.emr.encounters.today') }}">Encounter Hari Ini</a></li>
                            <li><a class="sidenav-item-link" href="{{ route('admin.emr.encounters.pending') }}">Encounter Belum Selesai</a></li>
                            <li><a class="sidenav-item-link" href="{{ route('admin.emr.encounters.create') }}">Encounter Baru</a></li>
                        </ul>
                    </div>
                </li>

                <!-- Farmasi & Inventori Ubat -->
                <li class="has-sub {{ request()->is('admin/pharmacy*') ? 'active' : '' }}">
                    <a class="sidenav-item-link" href="javascript:void(0)">
                        <i class="bi bi-capsule"></i>
                        <span class="nav-text">Farmasi</span> <b class="caret"></b>
                    </a>
                    <div class="collapse {{ request()->is('admin/pharmacy*') ? 'show' : '' }}">
                        <ul class="sub-menu" id="farmasi" data-parent="#sidebar-menu">
                            <li><a class="sidenav-item-link" href="{{ route('admin.pharmacy.medicines.index') }}">Senarai Ubat</a></li>
                            <li><a class="sidenav-item-link" href="{{ route('admin.pharmacy.medicines.create') }}">Tambah Ubat Baru</a></li>
                            <li><a class="sidenav-item-link" href="{{ route('admin.pharmacy.dispensing.index') }}">Dispensing</a></li>
                            <li><a class="sidenav-item-link" href="{{ route('admin.pharmacy.dispensing.pending') }}">Menunggu Dispens</a></li>
                            <li><a class="sidenav-item-link" href="{{ route('admin.pharmacy.suppliers.index') }}">Pembekal</a></li>
                        </ul>
                    </div>
                </li>

                <!-- Surat Rujukan (Akan Datang) -->
                <li>
                    <a class="sidenav-item-link text-muted" href="javascript:void(0)" title="Akan datang">
                        <i class="bi bi-envelope-paper"></i>
                        <span class="nav-text"><i class="bi bi-clock me-1"></i>Surat Rujukan</span>
                    </a>
                </li>

                <!-- Laporan -->
                <li class="has-sub {{ request()->is('admin/billing/reports*') ? 'active' : '' }}">
                    <a class="sidenav-item-link" href="javascript:void(0)">
                        <i class="bi bi-bar-chart-line"></i>
                        <span class="nav-text">Laporan</span> <b class="caret"></b>
                    </a>
                    <div class="collapse {{ request()->is('admin/billing/reports*') ? 'show' : '' }}">
                        <ul class="sub-menu" id="laporan" data-parent="#sidebar-menu">
                            <li><a class="sidenav-item-link" href="{{ route('admin.billing.reports.daily') }}">Laporan Harian</a></li>
                            <li><a class="sidenav-item-link" href="{{ route('admin.billing.reports') }}">Laporan Kewangan</a></li>
                            <li><a class="sidenav-item-link" href="{{ route('admin.billing.reports.outstanding') }}">Laporan Tertunggak</a></li>
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
                            <li><a class="sidenav-item-link" href="{{ route('admin.billing.index') }}">Dashboard Billing</a></li>
                            <li><a class="sidenav-item-link" href="{{ route('admin.billing.invoices.index') }}">Senarai Invois</a></li>
                            <li><a class="sidenav-item-link" href="{{ route('admin.billing.payments.index') }}">Senarai Pembayaran</a></li>
                            <li><a class="sidenav-item-link" href="{{ route('admin.billing.refunds.index') }}">Pulangan</a></li>
                            <li><a class="sidenav-item-link" href="{{ route('admin.billing.approvals.index') }}">Kelulusan Diskaun</a></li>
                            <li><a class="sidenav-item-link" href="{{ route('admin.billing.cashier.index') }}">Tutup Kaunter</a></li>
                            <li><a class="sidenav-item-link" href="{{ route('admin.billing.reports') }}">Laporan Kewangan</a></li>
                            <li><a class="sidenav-item-link" href="{{ route('admin.billing.settings') }}">Tetapan Billing</a></li>
                        </ul>
                    </div>
                </li>

                <!-- Staf / Pengguna Sistem -->
                <li class="has-sub {{ request()->is('admin/staff*') || request()->is('admin/users*') ? 'active' : '' }}">
                    <a class="sidenav-item-link" href="javascript:void(0)">
                        <i class="bi bi-person-badge"></i>
                        <span class="nav-text">Staf / Pengguna Sistem</span> <b class="caret"></b>
                    </a>
                    <div class="collapse {{ request()->is('admin/staff*') || request()->is('admin/users*') ? 'show' : '' }}">
                        <ul class="sub-menu" id="staf" data-parent="#sidebar-menu">
                            <li><a class="sidenav-item-link" href="{{ route('admin.staff.index') }}">Senarai Staf</a></li>
                            <li><a class="sidenav-item-link" href="{{ route('admin.users.index') }}">Pengguna Sistem</a></li>
                        </ul>
                    </div>
                </li>

                <!-- Tetapan Sistem -->
                <li class="has-sub {{ request()->is('admin/settings*') ? 'active' : '' }}">
                    <a class="sidenav-item-link" href="javascript:void(0)">
                        <i class="bi bi-gear"></i>
                        <span class="nav-text">Tetapan Sistem</span> <b class="caret"></b>
                    </a>
                    <div class="collapse {{ request()->is('admin/settings*') ? 'show' : '' }}">
                        <ul class="sub-menu" id="tetapan" data-parent="#sidebar-menu">
                            <li><a class="sidenav-item-link" href="{{ route('admin.settings.general') }}">Umum</a></li>
                            <li><a class="sidenav-item-link" href="{{ route('admin.settings.clinic') }}">Maklumat Klinik</a></li>
                            <li><a class="sidenav-item-link" href="{{ route('admin.settings.security') }}">Keselamatan</a></li>
                            <li><a class="sidenav-item-link" href="{{ route('admin.settings.notifications') }}">Notifikasi</a></li>
                        </ul>
                    </div>
                </li>

                <!-- Profil Saya -->
                @auth
                <li class="{{ request()->is('admin/users/' . auth()->id()) ? 'active' : '' }}">
                    <a class="sidenav-item-link" href="{{ route('admin.users.show', auth()->id()) }}">
                        <i class="bi bi-person-circle"></i>
                        <span class="nav-text">Profil Saya</span>
                    </a>
                </li>
                @endauth

                <!-- Log Keluar -->
                <li>
                    <form method="POST" action="{{ route('logout') }}" id="logout-form">
                        @csrf
                        <a class="sidenav-item-link" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="bi bi-box-arrow-right"></i>
                            <span class="nav-text">Log Keluar</span>
                        </a>
                    </form>
                </li>
            </ul>
        </div>
    </div>
</div>
