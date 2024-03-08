<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="userInfo" content="{{ $userId ?? "" }}">
    <meta name="userName" content="{{ $name ?? "" }}">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v2.1.9/css/unicons.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>

    <script>
        let APP_URL = {!! \Psy\Util\Json::encode(url('/')) !!};
    </script>

    <script>
        // Define global config object
        window.Laravel = {!! json_encode([
            'apiToken' => config('app.dalle_api_key'),
        ]) !!};
    </script>

    <style>
        .grecaptcha-badge {
            visibility: hidden;
        }


        .g-blue{ color:#4285F4; }
        .o-red{ color:#DB4437; }
        .o-yellow{ color:#F4B400; }
        .l-green{ color:#0F9D58; }
    </style>

    <script src="https://www.google.com/recaptcha/api.js"></script>

    @viteReactRefresh
    @vite(['resources/sass/app.scss', 'resources/js/app.js', 'resources/sass/loginPage.scss', 'resources/sass/support/support.scss'])
</head>
<body>


    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm" style="display: none;">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name', 'Laravel') }}
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav me-auto">

                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto">
                        <!-- Authentication Links -->
                        @guest
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }}
                                </a>

{{--                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">--}}
{{--                                    <a class="dropdown-item" href="{{ route('logout') }}"--}}
{{--                                       onclick="event.preventDefault();--}}
{{--                                                     document.getElementById('logout-form').submit();">--}}
{{--                                        {{ __('Logout') }}--}}
{{--                                    </a>--}}

{{--                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">--}}
{{--                                        @csrf--}}
{{--                                    </form>--}}
{{--                                </div>--}}
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="text-center">
            @yield('content')
        </main>

    </div>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
</body>
</html>
