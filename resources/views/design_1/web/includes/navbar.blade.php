@if(empty($hideNavbar))
    <nav class="navbar-container bg-white shadow-sm py-15">
        <div class="container d-flex align-items-center justify-content-between">
            <a class="navbar-brand d-flex align-items-center" href="/">
                @if(!empty($generalSettings['logo']))
                    <img src="{{ $generalSettings['logo'] }}" class="img-cover" alt="site logo">
                @else
                    <span class="font-24 font-weight-bold text-primary">LMS<span class="text-dark">PRO</span></span>
                @endif
            </a>

            <div class="d-none d-lg-flex align-items-center">
                <ul class="d-flex align-items-center gap-24 list-style-none mb-0">
                    <li>
                        <a class="nav-link text-dark font-14 font-weight-500" href="/">Home</a>
                    </li>
                    <li>
                        <a class="nav-link text-dark font-14 font-weight-500" href="/classes">Courses</a>
                    </li>
                    <li>
                        <a class="nav-link text-dark font-14 font-weight-500" href="/instructors">Instructors</a>
                    </li>
                    <li>
                        <a class="nav-link text-dark font-14 font-weight-500" href="/contact">Contact</a>
                    </li>
                </ul>
            </div>

            <div class="d-flex align-items-center gap-12">
                @if(auth()->check())
                    <a href="/panel" class="btn btn-primary btn-sm px-20">Dashboard</a>
                    <a href="/logout" class="text-gray-500 font-14 ml-10">Logout</a>
                @else
                    <a href="/login" class="text-dark font-14 font-weight-bold mr-20">Login</a>
                    <a href="/register" class="btn btn-primary btn-sm px-25 d-none d-lg-block">Register</a>
                @endif
            </div>
        </div>
    </nav>

    <style>
        .navbar-container {
            position: sticky;
            top: 0;
            z-index: 999;
        }
        .gap-24 { gap: 24px; }
        .gap-12 { gap: 12px; }
        .py-15 { padding-top: 15px; padding-bottom: 15px; }
        .list-style-none { list-style: none; }
        .nav-link:hover { color: var(--primary) !important; }
    </style>
@endif
