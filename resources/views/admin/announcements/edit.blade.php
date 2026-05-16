<x-layouts.app>
<div class="mx-auto max-w-3xl">
    <h1 class="text-2xl font-bold">Edit announcement</h1>
    <form method="POST" action="{{ route('admin.announcements.update', $announcement) }}" class="card mt-6"><div class="card-body">
        @csrf @method('PUT')
        <x-input  name="title"    label="Title"    required :value="$announcement->title"/>
        <x-select name="priority" label="Priority" required
            :options="['low'=>'Low','normal'=>'Normal','high'=>'High','urgent'=>'Urgent']"
            :value="$announcement->priority"/>
        <x-textarea name="body" rows="8" label="Body" required :value="$announcement->body"/>
        <label class="mb-4 flex items-center gap-2 text-sm">
            <input type="checkbox" name="is_published" value="1" @checked($announcement->is_published) class="rounded">
            Published
        </label>
        <div class="flex justify-end gap-2">
            <a href="{{ route('admin.announcements.index') }}" class="btn-secondary">Cancel</a>
            <button class="btn-primary">Save</button>
        </div>
    </div></form>
</div>
</x-layouts.app>
