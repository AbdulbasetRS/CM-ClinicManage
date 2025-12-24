<nav class="navbar navbar-expand-lg navbar-light bg-light border-bottom shadow-sm mb-5">
    <div class="container">
        <div class="d-flex w-100 flex-column flex-lg-row">
            <!-- Top Row for Mobile: Brand and Menu -->
            <div class="d-flex justify-content-between align-items-center w-100">
                <!-- Brand -->
                <a class="navbar-brand d-flex align-items-center gap-2 {{ app()->getLocale() === 'ar' ? 'order-1' : 'order-5' }}"
                    href="{{ route('admin.dashboard') }}" aria-label="Go to dashboard">
                    {{-- Optional dual logos for theme switching --}}
                    {{-- Provide your actual logo files below, or keep the text fallback. --}}
                    {{-- Light logo (visible by default) --}}
                    <img src="{{ asset('assets/images/logo-light.png') }}" alt="Logo" height="28" class="logo-light"
                        onerror="this.classList.add('d-none')">
                    {{-- Dark logo --}}
                    <img src="{{ asset('assets/images/logo-dark.png') }}" alt="Logo" height="28"
                        class="logo-dark d-none" onerror="this.classList.add('d-none')">
                    {{-- Text fallback --}}
                    <span class="fw-semibold app-name">{{ config('app.name') }}</span>
                </a>

                <!-- Menu Toggle & Notifications -->
                <div class="d-flex align-items-center gap-2">
                    <!-- Menu Toggle Button -->
                    <button class="btn btn-outline-secondary" type="button" data-bs-toggle="offcanvas"
                        data-bs-target="#offcanvasWithBothOptions" aria-controls="offcanvasWithBothOptions"
                        aria-label="{{ __('admin.menu') }}">
                        <i class="fa-solid fa-bars"></i>
                    </button>
                    <x-admin.notification-button />
                </div>
            </div>
        </div>
    </div>
</nav>

@auth
    <div class="offcanvas {{ app()->getLocale() === 'ar' ? 'offcanvas-end' : 'offcanvas-start' }}" data-bs-scroll="true"
        tabindex="-1" id="offcanvasWithBothOptions" aria-labelledby="offcanvasWithBothOptionsLabel">

        <div class="offcanvas-header p-3">
            <div class="d-flex w-100 align-items-center justify-content-between">
                <div class="offcanvas-title-wrap flex-fill {{ app()->getLocale() === 'ar' ? 'text-start' : '' }}">
                    <h5 class="offcanvas-title mb-0" id="offcanvasWithBothOptionsLabel">
                        {{ __('admin.menu') }}
                    </h5>
                </div>
                <div class="offcanvas-close-wrap flex-shrink-0">
                    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                </div>
            </div>
        </div>

        <div class="offcanvas-body d-flex flex-column h-100 p-0">

            <!-- Sidebar Menu -->
            <ul class="nav flex-column p-3">
                @isset($menu)
                    @foreach ($menu as $index => $item)
                        @php
                            $collapseId = 'menuItem_' . $index;
                        @endphp

                        <li class="nav-item">

                            {{-- لو مفيش children → لينك عادي --}}
                            @if (empty($item['children']))
                                <a class="nav-link d-flex align-items-center {{ $item['active'] ? 'active-menu' : '' }}"
                                    href="{{ isset($item['route']) ? route($item['route']) : '#' }}">
                                    <i class="{{ $item['icon'] }} me-2"></i> {{ __($item['title']) }}
                                </a>
                            @else
                                {{-- لو في children → collapsible --}}
                                <a class="nav-link d-flex justify-content-between align-items-center" data-bs-toggle="collapse"
                                    href="#{{ $collapseId }}" role="button"
                                    aria-expanded="{{ $item['active'] ? 'true' : 'false' }}"
                                    aria-controls="{{ $collapseId }}">

                                    <span><i class="{{ $item['icon'] }} me-2"></i>{{ __($item['title']) }}</span>
                                    <i class="fa-solid fa-chevron-down collapse-arrow"></i>
                                </a>

                                <div class="collapse {{ $item['active'] ? 'show' : '' }}" id="{{ $collapseId }}">
                                    <ul class="nav flex-column submenu-indent">
                                        @foreach ($item['children'] as $child)
                                            <li class="nav-item">
                                                <a class="nav-link {{ $child['active'] ? 'active-submenu' : '' }}"
                                                    href="{{ route($child['route']) }}">
                                                    <i class="{{ $child['icon'] }} me-2"></i> {{ __($child['title']) }}
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                        </li>
                    @endforeach
                @endisset
            </ul>

            <!-- Logout Section -->
            <div class="mt-auto border-top p-3">
                <a class="btn btn-danger w-100" href="#"
                    onclick="event.preventDefault(); document.getElementById('admin-logout-form').submit();">
                    <i class="fa-solid fa-arrow-right-from-bracket me-2"></i> {{ __('admin.logout') }}
                </a>
                <form id="admin-logout-form" action="{{ route('admin.logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
            </div>
        </div>
    </div>
@endauth
