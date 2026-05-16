<x-layouts.app>
<div class="mx-auto max-w-2xl">
    <h1 class="text-2xl font-bold">Book an appointment</h1>
    <form method="POST" action="{{ route('resident.appointments.store') }}" class="card mt-6"><div class="card-body">
        @csrf
        <x-input    name="purpose"      label="Purpose" required />
        <x-input    name="scheduled_at" type="datetime-local" label="Scheduled date &amp; time" required />
        <x-textarea name="notes"        rows="3" label="Notes (optional)" />
        <div class="flex justify-end gap-2">
            <a href="{{ route('resident.appointments.index') }}" class="btn-secondary">Cancel</a>
            <button class="btn-primary">Submit</button>
        </div>
    </div></form>
</div>
</x-layouts.app>
