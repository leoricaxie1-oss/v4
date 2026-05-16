<x-layouts.app>
<div class="mx-auto max-w-2xl">
    <h1 class="text-2xl font-bold">Edit listing</h1>
    <form method="POST" action="{{ route('resident.skills.update', $skill) }}" class="card mt-6"><div class="card-body">
        @csrf @method('PUT')
        <x-input    name="title"       label="Title"     :value="$skill->title" required />
        <x-textarea name="description" rows="4" label="Description" :value="$skill->description" required />
        <div class="grid gap-2 sm:grid-cols-2">
            <x-input name="rate"      label="Rate (₱)" type="number" :value="$skill->rate" />
            <x-input name="rate_unit" label="Rate unit"               :value="$skill->rate_unit" />
        </div>
        <label class="mb-4 flex items-center gap-2 text-sm">
            <input type="checkbox" name="is_active" value="1" @checked($skill->is_active) class="rounded">
            Listing is active
        </label>
        <div class="flex justify-end gap-2">
            <a href="{{ route('resident.skills.index') }}" class="btn-secondary">Cancel</a>
            <button class="btn-primary">Save changes</button>
        </div>
    </div></form>
</div>
</x-layouts.app>
