@php
    $activeTab = $active ?? '';
    $isHomeActive = $activeTab === 'home';
    $isInternshipReportActive = in_array($activeTab, ['monthly', 'final'], true);
    $isAttendanceHistoryActive = in_array($activeTab, ['history', 'history_draft'], true);
    $isAbsenceActive = in_array($activeTab, ['absence', 'absence_izin', 'absence_lupa'], true);
@endphp

@once
    <style>
        .user-app-navbar {
            background: #1a6f6f;
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
            position: sticky;
            top: 0;
            z-index: 200;
        }

        .user-app-navbar-inner {
            max-width: 1120px;
            margin: 0 auto;
            padding: 12px 16px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            flex-wrap: wrap;
        }

        .user-app-brand {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            color: #fff;
            text-decoration: none;
            font-weight: 700;
            min-height: 36px;
        }

        .user-app-brand img {
            width: 26px;
            height: 26px;
            object-fit: contain;
        }

        .user-app-nav-group {
            display: flex;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
        }

        .user-app-menu {
            display: flex;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
        }

        .user-app-menu > a,
        .user-app-dropdown-toggle {
            color: rgba(255, 255, 255, 0.92);
            text-decoration: none;
            font-size: 13px;
            font-weight: 600;
            padding: 6px 10px;
            border-radius: 999px;
            line-height: 1.2;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .user-app-menu > a.active,
        .user-app-menu > a:hover,
        .user-app-dropdown-toggle.active,
        .user-app-dropdown-toggle:hover {
            background: rgba(255, 255, 255, 0.2);
            color: #fff;
        }

        .user-app-logout {
            border: 1px solid rgba(255, 255, 255, 0.65);
            background: transparent;
            color: #fff;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 700;
            padding: 7px 12px;
            min-height: 34px;
        }

        .user-app-toggle {
            display: none;
            align-items: center;
            justify-content: center;
            border: 1px solid rgba(255, 255, 255, 0.5);
            background: rgba(255, 255, 255, 0.08);
            color: #fff;
            border-radius: 8px;
            width: 36px;
            height: 36px;
            padding: 0;
        }

        .user-app-overlay {
            position: fixed;
            inset: 0;
            background: rgba(2, 6, 23, 0.45);
            backdrop-filter: blur(2px);
            z-index: 180;
            opacity: 0;
            visibility: hidden;
            transition: opacity .2s ease, visibility .2s ease;
        }

        .user-app-dropdown {
            position: relative;
            display: inline-flex;
            align-items: center;
        }

        .user-app-dropdown-caret {
            font-size: 10px;
            line-height: 1;
        }

        .user-app-submenu {
            position: absolute;
            top: calc(100% + 8px);
            left: 0;
            min-width: 180px;
            padding: 8px;
            border-radius: 10px;
            background: #ffffff;
            border: 1px solid #d8e2eb;
            box-shadow: 0 10px 28px rgba(15, 23, 42, 0.18);
            display: none;
            z-index: 50;
        }

        .user-app-submenu a {
            display: block;
            border-radius: 8px;
            padding: 8px 10px;
            color: #1f2c3d !important;
            font-size: 12px;
        }

        .user-app-submenu a.active,
        .user-app-submenu a:hover {
            background: #e9f2ff;
            color: #0f4f9b !important;
        }

        .user-app-dropdown:hover .user-app-submenu,
        .user-app-dropdown:focus-within .user-app-submenu {
            display: block;
        }

        @media (max-width: 991.98px) {
            .user-app-toggle {
                display: inline-flex;
            }

            .user-app-nav-group {
                position: fixed;
                top: 0;
                left: 0;
                height: 100vh;
                width: min(84vw, 320px);
                background: #1a6f6f;
                border-right: 1px solid rgba(255, 255, 255, 0.2);
                z-index: 220;
                transform: translateX(-102%);
                transition: transform .25s ease;
                flex-direction: column;
                align-items: stretch;
                gap: 10px;
                padding: 70px 12px 16px;
                overflow-y: auto;
            }

            .user-app-navbar.user-app-open .user-app-nav-group {
                transform: translateX(0);
            }

            .user-app-navbar.user-app-open + .user-app-overlay {
                opacity: 1;
                visibility: visible;
            }

            .user-app-menu {
                width: 100%;
                flex-direction: column;
                align-items: stretch;
                gap: 8px;
            }

            .user-app-menu > a,
            .user-app-dropdown-toggle {
                width: 100%;
                border-radius: 10px;
                padding: 10px 12px;
                text-align: left;
            }

            .user-app-dropdown {
                width: 100%;
                display: block;
            }

            .user-app-submenu {
                position: static;
                margin-top: 6px;
                width: 100%;
                box-shadow: none;
                border-radius: 10px;
                border: 1px solid rgba(255, 255, 255, 0.18);
                background: rgba(7, 35, 46, 0.42);
                display: none;
            }

            .user-app-dropdown.user-app-dropdown-open .user-app-submenu {
                display: block;
            }

            .user-app-dropdown:not(.user-app-dropdown-open):hover .user-app-submenu,
            .user-app-dropdown:not(.user-app-dropdown-open):focus-within .user-app-submenu {
                display: none;
            }

            .user-app-logout {
                width: 100%;
                margin-top: 2px;
            }

            .user-app-submenu a {
                color: rgba(255, 255, 255, 0.92) !important;
            }

            .user-app-submenu a.active,
            .user-app-submenu a:hover {
                background: rgba(255, 255, 255, 0.16);
                color: #fff !important;
            }
        }
    </style>
@endonce

<nav class="user-app-navbar" id="user-app-navbar">
    <div class="user-app-navbar-inner">
        <a href="{{ route('home') }}" class="user-app-brand">
            <img src="{{ asset('assets/images/logo-pln1.png') }}" alt="PLN Suku Cadang">
            <span>PLN Suku Cadang</span>
        </a>

        <button class="user-app-toggle" id="user-app-toggle" type="button" aria-label="Toggle menu">
            <i class="mdi mdi-menu" style="font-size:20px;"></i>
        </button>

        <div class="user-app-nav-group" id="user-app-nav-group">
            <div class="user-app-menu">
                <a class="{{ $isHomeActive ? 'active' : '' }}" href="{{ route('home') }}">Home</a>
                <a class="{{ ($active ?? '') === 'attendance' ? 'active' : '' }}" href="{{ route('user.attendance.index') }}">Kehadiran</a>
                <div class="user-app-dropdown">
                    <a class="user-app-dropdown-toggle {{ $isAttendanceHistoryActive ? 'active' : '' }}" href="javascript:void(0);">
                        Riwayat Kehadiran
                        <span class="user-app-dropdown-caret">&#9662;</span>
                    </a>
                    <div class="user-app-submenu">
                        <a class="{{ $activeTab === 'history' ? 'active' : '' }}" href="{{ route('user.attendance.history') }}">Kalender Kehadiran</a>
                        <a class="{{ $activeTab === 'history_draft' ? 'active' : '' }}" href="{{ route('user.attendance.draft') }}">Upload Draft Presensi</a>
                    </div>
                </div>
                <div class="user-app-dropdown">
                    <a class="user-app-dropdown-toggle {{ $isAbsenceActive ? 'active' : '' }}" href="javascript:void(0);">
                        Ketidakhadiran
                        <span class="user-app-dropdown-caret">&#9662;</span>
                    </a>
                    <div class="user-app-submenu">
                        <a class="{{ $activeTab === 'absence_izin' ? 'active' : '' }}" href="{{ route('user.absence.index', ['type' => 'izin_sakit']) }}">Izin &amp; Sakit</a>
                        <a class="{{ $activeTab === 'absence_lupa' ? 'active' : '' }}" href="{{ route('user.absence.index', ['type' => 'lupa_absensi']) }}">Lupa Absensi</a>
                    </div>
                </div>
                <div class="user-app-dropdown">
                    <a class="user-app-dropdown-toggle {{ $isInternshipReportActive ? 'active' : '' }}" href="javascript:void(0);">
                        Laporan Magang
                        <span class="user-app-dropdown-caret">&#9662;</span>
                    </a>
                    <div class="user-app-submenu">
                        <a class="{{ $activeTab === 'monthly' ? 'active' : '' }}" href="{{ route('user.monthly-report') }}">Laporan Bulanan</a>
                        <a class="{{ $activeTab === 'final' ? 'active' : '' }}" href="{{ route('user.final-report') }}">Laporan Akhir</a>
                    </div>
                </div>
                <a class="{{ ($active ?? '') === 'profile' ? 'active' : '' }}" href="{{ route('user.profile') }}">Profil</a>
            </div>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="user-app-logout" type="submit">Keluar</button>
            </form>
        </div>
    </div>
</nav>
<div class="user-app-overlay" id="user-app-overlay"></div>

@once
    <script>
        (function () {
            var navbar = document.getElementById('user-app-navbar');
            var toggle = document.getElementById('user-app-toggle');
            var overlay = document.getElementById('user-app-overlay');
            if (!navbar || !toggle) return;

            function closeMobileMenu() {
                navbar.classList.remove('user-app-open');
                document.body.style.overflow = '';
            }

            toggle.addEventListener('click', function () {
                navbar.classList.toggle('user-app-open');
                document.body.style.overflow = navbar.classList.contains('user-app-open') && window.innerWidth <= 991 ? 'hidden' : '';
            });

            if (overlay) {
                overlay.addEventListener('click', closeMobileMenu);
            }

            var dropdownToggles = navbar.querySelectorAll('.user-app-dropdown-toggle');
            var dropdowns = navbar.querySelectorAll('.user-app-dropdown');
            dropdownToggles.forEach(function (el) {
                el.addEventListener('click', function (evt) {
                    if (window.innerWidth > 991) return;
                    evt.preventDefault();
                    var parent = el.closest('.user-app-dropdown');
                    if (!parent) return;
                    dropdowns.forEach(function (item) {
                        if (item !== parent) {
                            item.classList.remove('user-app-dropdown-open');
                        }
                    });
                    parent.classList.toggle('user-app-dropdown-open');
                });
            });

            window.addEventListener('resize', function () {
                if (window.innerWidth > 991) {
                    closeMobileMenu();
                }
            });
        })();
    </script>
@endonce

