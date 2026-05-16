<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'PanipOne' }}</title>
    @vite(['resources/css/app.css','resources/js/app.js'])
</head>
<body class="min-h-full bg-gradient-to-br from-slate-100 via-white to-brand-50 font-sans antialiased">

@if (session('toast'))
    <script>document.addEventListener('DOMContentLoaded', () =>
        document.dispatchEvent(new CustomEvent('toast:show', {detail: @json(session('toast'))})));</script>
@endif

<div class="flex min-h-screen items-center justify-center px-4 py-12">
    <div class="w-full max-w-xl">
        <div class="mb-6 flex items-center justify-center gap-3">
            <a href="{{ route('home') }}" class="grid h-12 w-12 place-items-center rounded-lg bg-brand-600 text-lg font-bold text-white">P1</a>
            <div class="text-center">
                <p class="text-lg font-semibold text-slate-900">PanipOne</p>
                <p class="text-xs text-slate-500">{{ config('panipone.barangay.name') }}</p>
            </div>
        </div>
        {{ $slot ?? '' }}
        @yield('content')
    </div>
</div>
</body>
</html>
