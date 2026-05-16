<x-layouts.auth>
<div class="card">
    <div class="card-body">
        <h1 class="text-xl font-bold text-slate-900">Sign in to your account</h1>
        <p class="mt-1 text-sm text-slate-500">Use your registered email and password.</p>

        <form method="POST" action="{{ route('login') }}" class="mt-6">
            @csrf
            <x-input  name="email"    type="email" label="Email address" required autofocus />

            <div x-data="{show:false}" class="mb-4">
                <label for="password" class="form-label required">Password</label>
                <div class="relative">
                    <input :type="show ? 'text' : 'password'" id="password" name="password" required class="form-input pr-10">
                    <button type="button" @click="show=!show" class="absolute right-2 top-2 text-xs text-slate-500 hover:text-slate-700"
                            x-text="show ? 'Hide' : 'Show'"></button>
                </div>
                @error('password') <p class="form-error">{{ $message }}</p> @enderror
                @error('email')    <p class="form-error">{{ $message }}</p> @enderror
            </div>

            <label class="mb-4 flex items-center gap-2 text-sm">
                <input type="checkbox" name="remember" value="1" class="rounded">
                Remember me
            </label>

            <button class="btn-primary w-full">Sign in</button>
        </form>

        <p class="mt-6 text-center text-sm text-slate-500">
            Don't have an account?
            <a href="{{ route('register') }}" class="font-semibold text-brand-600 hover:underline">Register</a>
        </p>
    </div>
</div>
</x-layouts.auth>
