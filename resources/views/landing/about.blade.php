<x-layouts.public>
<section class="mx-auto max-w-3xl px-4 py-12 sm:px-6 lg:px-8 prose prose-slate">
    <h1>About {{ config('panipone.barangay.name') }}</h1>
    <p>
        Barangay Panipuan is one of the vibrant communities of
        {{ config('panipone.barangay.municipality') }}, {{ config('panipone.barangay.province') }}.
        PanipOne is its official digital service portal, designed to make local government services
        accessible to every resident.
    </p>
    <h2>Mission</h2>
    <p>To provide modern, transparent, and citizen-friendly governance through technology.</p>
    <h2>Address</h2>
    <p>{{ config('panipone.barangay.address') }}</p>
</section>
</x-layouts.public>
