<x-layouts.app>
<div class="mx-auto max-w-3xl">
    <h1 class="text-2xl font-bold">File a complaint</h1>
    <p class="mt-1 text-sm text-slate-500">Allowed evidence: JPG, PNG, PDF (max {{ config('panipone.uploads.max_size_kb')/1024 }} MB each).</p>

    <form method="POST" action="{{ route('resident.complaints.store') }}" enctype="multipart/form-data" class="card mt-6"
          x-data="{cat: '{{ old('category') }}'}">
    <div class="card-body">
        @csrf

        <x-input name="title" label="Complaint Title" required />

        <x-select name="category" label="Complaint Type" required
            :options="collect($categories)->mapWithKeys(fn($v)=>[$v=>$v])->all()"
            x-model="cat" />

        <div x-show="cat === 'Others'" x-cloak class="mb-4">
            <x-input name="custom_category" label="Specify category" />
        </div>

        <x-input name="respondent_name"   label="Respondent Name" required />
        <div class="grid gap-2 sm:grid-cols-2">
            <x-input name="incident_date"   type="date" label="Incident Date" required />
            <x-input name="incident_location" label="Incident Location" required />
        </div>
        <x-textarea name="description" rows="5" label="Detailed Description" required />

        <h2 class="mt-2 text-sm font-semibold text-slate-700">Witness (optional)</h2>
        <div class="grid gap-2 sm:grid-cols-2">
            <x-input name="witness_name"    label="Witness Name" />
            <x-input name="witness_contact" label="Witness Contact" />
        </div>

        <div class="mb-4">
            <label class="form-label">Supporting Evidence</label>
            <input type="file" name="evidence[]" multiple accept=".jpg,.jpeg,.png,.pdf" class="form-input">
            <p class="form-help">You may attach multiple files.</p>
            @error('evidence.*') <p class="form-error">{{ $message }}</p> @enderror
        </div>

        <div class="mt-2 flex justify-end gap-2">
            <a href="{{ route('resident.complaints.index') }}" class="btn-secondary">Cancel</a>
            <button class="btn-primary">Submit complaint</button>
        </div>
    </div></form>
</div>
</x-layouts.app>
