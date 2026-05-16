<x-layouts.app>
<div class="mx-auto max-w-3xl">
    <h1 class="text-2xl font-bold">Register a skill or service</h1>

    <form method="POST" action="{{ route('resident.skills.store') }}" enctype="multipart/form-data"
          class="card mt-6" x-data="{cat: '{{ old('category') }}'}">
    <div class="card-body">
        @csrf
        <x-select name="category" label="Category" required
            :options="collect($categories)->mapWithKeys(fn($v)=>[$v=>$v])->all()"
            x-model="cat" />

        <div x-show="cat === 'Others'" x-cloak class="mb-4">
            <x-input name="custom_category" label="Specify category" />
        </div>

        <x-input name="title" label="Title" required />
        <x-textarea name="description" rows="4" label="Description" required />

        <div class="grid gap-2 sm:grid-cols-2">
            <x-input name="rate"      label="Rate (₱)" type="number" />
            <x-input name="rate_unit" label="Rate unit (e.g. per hour)" />
            <x-input name="contact_email" type="email" label="Contact Email" required />
            <x-input name="contact_phone" label="Contact Phone (PH mobile)" required />
        </div>

        <div class="mb-4">
            <label class="form-label">Photo (optional)</label>
            <input type="file" name="photo" accept="image/*" class="form-input">
            @error('photo') <p class="form-error">{{ $message }}</p> @enderror
        </div>

        <div class="mt-2 flex justify-end gap-2">
            <a href="{{ route('resident.skills.index') }}" class="btn-secondary">Cancel</a>
            <button class="btn-primary">Submit for approval</button>
        </div>
    </div></form>
</div>
</x-layouts.app>
