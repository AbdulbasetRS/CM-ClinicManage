<nav class="navbar navbar-expand-lg navbar-light bg-light border-bottom shadow-sm">
    <div class="container">
        <!-- Brand: keep before collapse on mobile to avoid jumping -->
        <a class="navbar-brand d-flex align-items-center gap-2 {{ app()->getLocale() === 'ar' ? 'order-1' : 'order-3' }}"
            href="{{ route('admin.dashboard') }}" aria-label="Go to dashboard">
            {{-- Optional dual logos for theme switching --}}
            {{-- Provide your actual logo files below, or keep the text fallback. --}}
            {{-- Light logo (visible by default) --}}
            <img src="{{ asset('assets/images/logo-light.png') }}" alt="Logo" height="28" class="logo-light"
                onerror="this.classList.add('d-none')">
            {{-- Dark logo --}}
            <img src="{{ asset('assets/images/logo-dark.png') }}" alt="Logo" height="28" class="logo-dark d-none"
                onerror="this.classList.add('d-none')">
            {{-- Text fallback --}}
            <span class="fw-semibold app-name">{{ config('app.name') }}</span>
        </a>

        <!-- Toggler: keep before collapse on mobile; push to far edge with me-auto -->
        <button class="navbar-toggler {{ app()->getLocale() === 'ar' ? 'order-2 me-auto' : 'order-1 me-auto' }}"
            type="button" data-bs-toggle="collapse" data-bs-target="#adminNavbar" aria-controls="adminNavbar"
            aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Collapse: comes after brand & toggler on mobile to avoid pushing them -->
        <div class="collapse navbar-collapse order-5 order-lg-2" id="adminNavbar">
            <!-- Right: User Dropdown -->
            <ul class="navbar-nav mb-2 mb-lg-0 align-items-lg-center">
                <li class="nav-item"><a class="nav-link" href="{{ route('admin.login') }}">Login</a></li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="userMenu" role="button"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        <span>Services</span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userMenu">
                        <li><a class="dropdown-item" href="#">Service 1</a></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li><a class="dropdown-item" href="#">Service 2</a></li>
                        <li><a class="dropdown-item" href="#">Service 3</a></li>
                    </ul>
                </li>
                <li class="nav-item"><a class="nav-link" href="#">Link</a></li>
            </ul>
        </div>
    </div>
    <!-- Note: Requires Bootstrap 5 JS (already included in admin.structure) -->
</nav>