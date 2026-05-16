<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'PanipOne' }} — {{ config('panipone.barangay.name') }}</title>
    <meta name="description" content="Official online services portal of Barangay Panipuan.">
    @vite(['resources/css/app.css','resources/js/app.js'])
</head>
<body class="h-full bg-slate-50 font-sans text-slate-800 antialiased">

<header class="bg-white shadow-sm">
    <div class="mx-auto flex max-w-7xl items-center justify-between gap-4 px-4 py-4 sm:px-6 lg:px-8">
        <a href="{{ route('home') }}" class="flex items-center gap-3">
            <span class="grid h-10 w-10 place-items-center rounded-lg bg-brand-600 font-bold text-white">P1</span>
            <div class="flex flex-col leading-tight">
                <span class="text-base font-bold text-slate-900">PanipOne</span>
                <span class="text-xs text-slate-500">{{ config('panipone.barangay.name') }}</span>
            </div>
        </a>

        <nav class="hidden items-center gap-5 text-sm font-medium text-slate-600 lg:flex">
            <a href="{{ route('home') }}"           class="hover:text-brand-700">Home</a>
            <a href="{{ route('directory.index') }}" class="hover:text-brand-700">Directory</a>
            <a href="{{ route('about') }}"          class="hover:text-brand-700">About</a>
            <a href="{{ route('contact') }}"        class="hover:text-brand-700">Contact</a>
        </nav>

        <div class="flex items-center gap-2">
            @auth
                <a href="{{ route('dashboard') }}" class="btn-primary">Dashboard</a>
            @else
                <a href="{{ route('login') }}"    class="btn-secondary">Login</a>
                <a href="{{ route('register') }}" class="btn-primary">Register</a>
            @endauth
        </div>
    </div>
</header>

@if (session('toast'))
    <script>document.addEventListener('DOMContentLoaded', () =>
        document.dispatchEvent(new CustomEvent('toast:show', {detail: @json(session('toast'))})));</script>
@endif

<main>
    {{ $slot ?? '' }}
    @yield('content')
</main>

<footer class="mt-12 bg-slate-900 text-slate-200">
    <div class="mx-auto grid max-w-7xl gap-8 px-4 py-10 sm:px-6 lg:grid-cols-3 lg:px-8">
        <div>
            <div class="mb-3 flex items-center gap-2">
                <span class="grid h-8 w-8 place-items-center rounded bg-brand-600 font-bold">P1</span>
                <span class="text-base font-bold">PanipOne</span>
            </div>
            <p class="text-sm text-slate-400">
                {{ config('panipone.barangay.name') }} —
                {{ config('panipone.barangay.municipality') }},
                {{ config('panipone.barangay.province') }}.
            </p>
        </div>
        <div>
            <h4 class="mb-2 font-semibold">Quick Links</h4>
            <ul class="space-y-1 text-sm text-slate-400">
                <li><a href="{{ route('directory.index') }}" class="hover:text-white">Services Directory</a></li>
                <li><a href="{{ route('directory.index', ['type'=>'businesses']) }}" class="hover:text-white">Local Businesses</a></li>
                <li><a href="{{ route('register') }}" class="hover:text-white">Resident Registration</a></li>
            </ul>
        </div>
        <div>
            <h4 class="mb-2 font-semibold">Emergency Hotlines</h4>
            <ul class="space-y-1 text-sm text-slate-400">
                <li>Barangay: <strong class="text-white">{{ config('panipone.barangay.hotline') }}</strong></li>
                <li>Email: {{ config('panipone.barangay.email') }}</li>
            </ul>
        </div>
    </div>
    <div class="border-t border-slate-800 py-3 text-center text-xs text-slate-500">
        &copy; {{ date('Y') }} {{ config('panipone.barangay.name') }} — All rights reserved.
    </div>
</footer>

</body>
</html>
