<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? config('app.name') }} — {{ config('panipone.barangay.name') }}</title>
    @vite(['resources/css/app.css','resources/js/app.js'])
</head>
<body class="h-full bg-slate-50 font-sans text-slate-800 antialiased">

<div class="min-h-full flex">
    @auth
        @include('layouts.partials.sidebar')
    @endauth

    <div class="flex-1 flex flex-col min-w-0">
        @auth
            @include('layouts.partials.topbar')
        @endauth

        <main class="flex-1 p-4 sm:p-6 lg:p-8">
            @if (session('toast'))
                <script>document.addEventListener('DOMContentLoaded', () =>
                    document.dispatchEvent(new CustomEvent('toast:show', {detail: @json(session('toast'))})));</script>
            @endif

            {{ $slot ?? '' }}
            @yield('content')
        </main>

        <footer class="border-t bg-white py-4 text-center text-xs text-slate-500">
            &copy; {{ date('Y') }} {{ config('panipone.barangay.name') }} — PanipOne v3
        </footer>
    </div>
</div>

</body>
</html>
