<x-layouts.app>
<div class="mx-auto max-w-3xl">
    <h1 class="text-2xl font-bold">New announcement</h1>
    <form method="POST" action="{{ route('admin.announcements.store') }}" enctype="multipart/form-data" class="card mt-6"><div class="card-body">
        @csrf
        <x-input    name="title" label="Title" required/>
        <x-select   name="priority" label="Priority" required
            :options="['low'=>'Low','normal'=>'Normal','high'=>'High','urgent'=>'Urgent']"
            value="normal"/>
        <x-textarea name="body" rows="8" label="Body" required/>
        <div class="mb-4">
            <label class="form-label">Cover image (optional)</label>
            <input type="file" name="cover" accept="image/*" class="form-input">
        </div>
        <label class="mb-4 flex items-center gap-2 text-sm">
            <input type="checkbox" name="is_published" value="1" class="rounded">
            Publish immediately
        </label>
        <div class="flex justify-end gap-2">
            <a href="{{ route('admin.announcements.index') }}" class="btn-secondary">Cancel</a>
            <button class="btn-primary">Save announcement</button>
        </div>
    </div></form>
</div>
</x-layouts.app>
