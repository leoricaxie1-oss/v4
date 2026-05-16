<x-layouts.app>
<div class="mb-4 flex items-center justify-between">
    <h1 class="text-2xl font-bold">Announcements</h1>
    <a href="{{ route('admin.announcements.create') }}" class="btn-primary">+ New Announcement</a>
</div>
<div class="card overflow-hidden">
    <table class="min-w-full divide-y divide-slate-200 text-sm">
        <thead class="bg-slate-50 text-left text-xs uppercase tracking-wider text-slate-500">
            <tr><th class="p-3">Title</th><th class="p-3">Priority</th><th class="p-3">Published</th><th class="p-3">Author</th><th class="p-3"></th></tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
            @forelse ($items as $a)
                <tr>
                    <td class="p-3 font-medium">{{ $a->title }}</td>
                    <td class="p-3"><span class="badge-info uppercase">{{ $a->priority }}</span></td>
                    <td class="p-3">{{ $a->is_published ? $a->published_at?->format('M d, Y') : 'Draft' }}</td>
                    <td class="p-3">{{ $a->author->full_name }}</td>
                    <td class="p-3 text-right space-x-1">
                        <a href="{{ route('admin.announcements.edit', $a) }}" class="text-brand-600 hover:underline">Edit</a>
                        <form method="POST" action="{{ route('admin.announcements.destroy', $a) }}" class="inline"
                              onsubmit="return confirm('Delete?')">
                            @csrf @method('DELETE')
                            <button class="text-rose-600 hover:underline">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="5" class="p-6 text-center text-slate-500">No announcements yet.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="mt-4">{{ $items->links() }}</div>
</x-layouts.app>
