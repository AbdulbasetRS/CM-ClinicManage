<!DOCTYPE html>

<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}" class="h-100">

<head>
    <!-- Meta Data -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ env('APP_NAME') }} @if(trim($__env->yieldContent('title'))) | @yield('title') @endif</title>
    @yield('meta')
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('assets/images/cpanel-logo.png') }}">
    <!-- Fonts -->
    <link rel="stylesheet" href="{{ asset('assets/fonts/fonts.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/fontawesome/fontawesome-free-6.4.2-web/css/all.css') }}">
    <!-- Site Stylesheet -->
    <link rel="stylesheet" href="{{ asset('assets/libraries/bootstrap/bootstrap-v5.3.8/dist/css/bootstrap.min.css') }}">
    {{--
    <link rel="stylesheet" href="{{ asset('assets/libraries/sweetAlert2/v11.17.2/sweetalert2.min.css') }}"> --}}
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('assets/libraries/dataTables/datatables.bundle.css') }}">
    <script src="{{ asset('assets/libraries/dataTables/datatables.bundle.js') }}"></script>
    <link href="{{ asset('assets/css/app.css') }}" rel="stylesheet">
    @yield('main.style')
</head>

<body class="font-default" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
    <div class="d-flex flex-column min-vh-100">
        @auth
            {{-- @include('components.admin.sidebar') --}}
            {{-- @include('components.admin.navbar') --}}
            <x-admin.sidebar />
            {{-- <x-admin.navbar /> --}}
        @endauth

        <main class="flex-grow-1">
            @yield('content')
        </main>
        {{-- @include('components.admin.footer') --}}
        <x-admin.footer />
    </div>
    <!-- Site Scripts -->
    <script src="{{ asset('assets/libraries/bootstrap/bootstrap-v5.3.8/dist/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/libraries/chart.js/chart-v4.5.0.js') }}"></script>
    {{--
    <script src="{{ asset('assets/libraries/sweetAlert2/v11.17.2/sweetalert2.min.js') }}"></script> --}}

    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    @yield('main.script')
    <script src="{{ asset('assets/js/app.js') }}"></script>
    @auth
        @if (env('NOTIFICATIONS_ENABLED'))
            <script src="https://js.pusher.com/8.0/pusher.min.js"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/laravel-echo/1.12.1/echo.iife.js"></script>
            <script>
                Pusher.logToConsole = {{ app()->environment('local') ? 'true' : 'false' }};
                const pusher = new Pusher("{{ env('PUSHER_APP_KEY') }}", {
                    cluster: "{{ env('PUSHER_APP_CLUSTER') }}",
                    encrypted: true,
                    authEndpoint: '/broadcasting/auth',
                    auth: {
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    }
                });
                const channel = pusher.subscribe('private-admin.' + {{ Auth::id() }});
                channel.bind('Illuminate\\Notifications\\Events\\BroadcastNotificationCreated', function (notification) {
                    console.log(notification);
                    pushNotificationToList(
                        id = notification.id ?? null,
                        title = notification.title,
                        content = notification.body,
                        time = notification.timestamp,
                        notification_url = notification.model_url
                            ? (notification.notification_url ?? '#')
                            : '#',
                        icon = null,
                        image = null,
                        sound = null,
                        unreadCountFromServer = notification.meta?.unread_count
                    )
                });
            </script>
        @endif
    @endauth
</body>

</html>