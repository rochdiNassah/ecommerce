<!DOCTYPE html>
<html lang="en" dir="ltr">
    <head>
        <meta charset="utf8">
        <meta name="viewport" content="initial-scale=1, width=device-width">
        <title>{{ config('app.name') }} | @yield('title', 'Unknown page!')</title>
        <link rel="stylesheet" href="{{ asset('css/tailwind.css') }}">
        <link rel="stylesheet" href="{{ asset('css/main.css') }}">
    </head>

    <body>
        @if (session('message'))
            @php
                $color = session('status') == 'error' ? 'red' : (session('status') == 'success' ? 'green' : 'yellow');
            @endphp

        <div class="transition transform fixed opacity-0 -bottom-32 right-2 mr-2 z-20" id="global-alert" role="alert">
            <div class="rounded-t-sm border border-{{ $color }}-600 bg-{{ $color }}-900 text-{{ $color }}-400 font-bold px-4 py-2">
                {{ ucfirst(session('status')) }}
            </div>

            <div class="rounded-b-sm px-4 py-3 text-gray-300 border border-{{ $color }}-600 border-t-0 font-bold bg-gray">
                <p>{{ session('message') }}</p>
            </div>

            <span class="absolute top-0 bottom-0 right-0 px-2 py-2" id="closeAlertButton" onclick="closeGlobalAlert()">
                <svg class="fill-current h-6 w-6 text-{{ $color }}-400" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><title>Close</title><path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z"/></svg>
            </span>
        </div>
        @endif

        @yield('content')

        <script>
            // Global alert
            var globalAlert = document.getElementById('global-alert')

            if (document.body.contains(globalAlert)) {
                removeClass(globalAlert, ['-bottom-32', 'opacity-0'])
                addClass(globalAlert, ['bottom-2', 'opacity-100'])

                function closeGlobalAlert()
                {
                    removeClass(globalAlert, ['bottom-2', 'opacity-1'])
                    addClass(globalAlert, ['-bottom-32', 'opacity-0'])
                }
            }

            // Helpers
            function removeClass(element, classes)
            {
                if ('object' === typeof classes) {
                    classes.forEach(item => {
                        element.classList.remove(item)
                    })

                    return
                }

                element.classList.remove(classes)
            }

            function addClass(element, classes)
            {
                if ('object' === typeof classes) {
                    classes.forEach(item => {
                        element.classList.add(item)
                    })

                    return
                }

                element.classList.add(classes)
            }
        </script>
    </body>
</html>