<x-layouts.app>
<div class="mx-auto max-w-2xl">
    <h1 class="text-2xl font-bold">New document request</h1>
    <p class="mt-1 text-sm text-slate-500">Note: <em>Proof of residence is no longer required.</em></p>

    <form method="POST" action="{{ route('resident.documents.store') }}" class="card mt-6"><div class="card-body">
        @csrf
        <x-select name="document_type" label="Document Type" required
            :options="collect($types)->mapWithKeys(fn($v,$k)=>[$k => $v['label'].' — ₱'.number_format($v['fee'],2)])->all()" />
        <x-input  name="purpose"  label="Purpose" required placeholder="e.g. Employment requirement" />
        <x-textarea name="details" label="Additional details" rows="4" />

        <div class="mt-2 flex justify-end gap-2">
            <a href="{{ route('resident.documents.index') }}" class="btn-secondary">Cancel</a>
            <button class="btn-primary">Submit request</button>
        </div>
    </div></form>
</div>
</x-layouts.app>
