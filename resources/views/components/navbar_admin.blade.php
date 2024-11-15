<div class="st-height-70"></div>
<header id="page-topbar">
    <div class="navbar-header">
        <div class="d-flex">
            <!-- LOGO -->
            <div class="navbar-brand-box">
                <a href="{{ url('/') }}" class="logo logo-dark">
                    <span class="logo-sm">
                        <img src="{{ asset('assets/images/logo-sm.png') }}" alt="logo-sm" height="22">
                    </span>
                    <span class="logo-lg">
                        <img src="{{ asset('assets/images/logo-dark.png') }}" alt="logo-dark" height="20">
                    </span>
                </a>

                <a href="{{ url('/') }}" class="logo logo-light">
                    <span class="logo-sm">
                        <img src="{{ asset('assets/images/logo-sm.png') }}" alt="logo-sm-light" height="22">
                    </span>
                    <span class="logo-lg">
                        <img src="{{ asset('assets/images/logo-light.png') }}" alt="logo-light" height="20">
                    </span>
                </a>
            </div>

            <button type="button" class="btn btn-sm px-3 font-size-24 header-item waves-effect" id="vertical-menu-btn">
                <i class="ri-menu-2-line align-middle"></i>
            </button>

            
        </div>

        <div class="d-flex">

            <div class="dropdown d-inline-block user-dropdown">
                <button type="button" class="btn header-item waves-effect" id="page-header-user-dropdown"
                    data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <img class="rounded-circle header-profile-user" src="https://api.dicebear.com/5.x/identicon/svg?seed={{ Auth::user()->name }}"
                        alt="Header Avatar">
                    <span class="d-none d-xl-inline-block ms-1">Hai, {{ Auth::user()->name }}</span>
                    <i class="mdi mdi-chevron-down d-none d-xl-inline-block"></i>
                </button>
                <div class="dropdown-menu dropdown-menu-end">

                    @guest
                        <a class="dropdown-item" href="{{ url('/') }}"><i class="ri-user-line align-middle me-1"></i> Dashboard</a>
                        <a class="dropdown-item" href="#"><i class="ri-user-line align-middle me-1"></i> Dashboard</a>
                        <li><a class="dropdown-item" href="{{ route('login') }}">Masuk</a></li>
                    @else
                        <a class="dropdown-item" href="{{ route('home') }}"><i class="ri-home-line align-middle me-1"></i> Dashboard</a>
                                
                        <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" style="color:red;"> Keluar </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                        </form>
                    @endguest
                </div>
            </div>

        </div>
    </div>
</header>
<!-- ========== Left Sidebar Start ========== -->
<div class="vertical-menu">

    <div data-simplebar class="h-100">

        <!-- User details -->
        <div class="user-profile text-center mt-3">
            <div class="">
                <img src="https://api.dicebear.com/5.x/identicon/svg?seed={{ Auth::user()->name }}" alt="" class="avatar-md rounded-circle">
            </div>
            <div class="mt-3">
                <h4 class="font-size-16 mb-1">{{ Auth::user()->name }}</h4>
            </div>
        </div>

        <!--- Sidemenu -->
        <div id="sidebar-menu">
            <!-- Left Menu Start -->
            <ul class="metismenu list-unstyled" id="side-menu">
                <li class="menu-title">Menu</li>

                <li>
                    <a href="{{ route('home') }}" class="waves-effect">
                        <i class="ri-dashboard-line"></i>
                        <span>Dashboard</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('data-user.index') }}" class=" waves-effect">
                        <i class="ri-user-2-line"></i>
                        <span>Data User</span>
                    </a>
                </li>
    
                <li>
                    <a href="#" class=" waves-effect">
                        <i class="ri-key-2-line"></i>
                        <span>Data Role</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('data-kecamatan.index') }}" class=" waves-effect">
                        <i class="ri-user-2-line"></i>
                        <span>Data Kecamatan</span>
                    </a>
                </li>

                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="ri-mail-send-line"></i>
                        <span>CCTV</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="{{ route('data-cctv.peta') }}">Peta</a></li>
                        <li><a href="{{ route('data-cctv.index') }}">Data CCTV</a></li>
                    </ul>
                </li>
                
            </ul>
        </div>
        <!-- Sidebar -->
    </div>
</div>
<!-- Left Sidebar End -->