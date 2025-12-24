<footer class="mt-auto border-top bg-light">
    <div class="container py-3 d-flex align-items-center justify-content-between">
        <!-- Left: Language Dropdown + Theme Toggle -->
        <div class="d-flex align-items-center gap-3">
            @php($current = app()->getLocale())

            <div class="dropdown">
                <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" id="langDropdown"
                        data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fa-solid fa-globe me-1"></i>
                    @php($current = app()->getLocale())
                    @php($currentLabel = \LaravelLocalization::getSupportedLocales()[$current]['native'] ?? strtoupper($current))
                    {{ $currentLabel }}
                </button>
                <ul class="dropdown-menu" aria-labelledby="langDropdown">
                    @foreach(\LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
                        @php($url = \LaravelLocalization::getLocalizedURL($localeCode, null, [], true))
                        <li>
                            <a rel="alternate" hreflang="{{ $localeCode }}"
                               class="dropdown-item d-flex align-items-center justify-content-between {{ $localeCode === $current ? 'active' : '' }}"
                               href="{{ $url }}">
                                <span>{{ $properties['native'] ?? strtoupper($localeCode) }}</span>
                                @if($localeCode === $current)
                                    <i class="fa-solid fa-check small ms-2"></i>
                                @endif
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>

            <button id="themeToggle" class="btn btn-sm btn-outline-secondary" type="button" aria-label="Toggle theme">
                <i class="fa-solid fa-moon me-1 theme-icon-dark d-none"></i>
                <i class="fa-solid fa-sun me-1 theme-icon-light d-none"></i>
                <span class="theme-label">Dark</span>
            </button>

            {{-- No need for POST form; mcamara/laravel-localization handles locale via localized URLs --}}
        </div>

        <!-- Right: Copyright -->
        <div class="text-muted small">
            &copy; {{ date('Y') }} {{ config('app.name') }}. جميع الحقوق محفوظة.
        </div>
    </div>

    <script>
        (function() {
            const storageKey = 'theme';
            const body = document.body;
            const btn = document.getElementById('themeToggle');
            const iconDark = btn?.querySelector('.theme-icon-dark');
            const iconLight = btn?.querySelector('.theme-icon-light');
            const label = btn?.querySelector('.theme-label');

            function applyTheme(theme) {
                if (theme === 'dark') {
                    body.classList.add('theme-dark');
                    iconDark && iconDark.classList.remove('d-none');
                    iconLight && iconLight.classList.add('d-none');
                    label && (label.textContent = 'Light');
                } else {
                    body.classList.remove('theme-dark');
                    iconDark && iconDark.classList.add('d-none');
                    iconLight && iconLight.classList.remove('d-none');
                    label && (label.textContent = 'Dark');
                }
                // Bootstrap 5.3+ theme attribute
                document.documentElement.setAttribute('data-bs-theme', theme === 'dark' ? 'dark' : 'light');
            }

            const saved = localStorage.getItem(storageKey) || 'light';
            applyTheme(saved);

            btn && btn.addEventListener('click', function() {
                const current = body.classList.contains('theme-dark') ? 'dark' : 'light';
                const next = current === 'dark' ? 'light' : 'dark';
                localStorage.setItem(storageKey, next);
                applyTheme(next);
            });

            // Localization links are direct via LaravelLocalization, no JS handling required
        })();
    </script>
</footer>