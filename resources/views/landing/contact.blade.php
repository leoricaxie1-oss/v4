<x-layouts.public>
<section class="mx-auto max-w-3xl px-4 py-12 sm:px-6 lg:px-8">
    <h1 class="text-3xl font-bold">Contact Us</h1>
    <div class="mt-6 grid gap-6 sm:grid-cols-2">
        <div class="card"><div class="card-body">
            <p class="text-sm font-semibold uppercase text-slate-500">Hotline</p>
            <p class="text-xl font-bold">{{ config('panipone.barangay.hotline') }}</p>
        </div></div>
        <div class="card"><div class="card-body">
            <p class="text-sm font-semibold uppercase text-slate-500">Email</p>
            <p class="text-xl font-bold">{{ config('panipone.barangay.email') }}</p>
        </div></div>
        <div class="card sm:col-span-2"><div class="card-body">
            <p class="text-sm font-semibold uppercase text-slate-500">Address</p>
            <p class="text-base">{{ config('panipone.barangay.address') }}</p>
        </div></div>
    </div>
</section>
</x-layouts.public>
