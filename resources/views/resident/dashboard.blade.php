<x-layouts.app>
<div class="space-y-6">
    <div>
        <h1 class="text-2xl font-bold">Hello, {{ $user->first_name }} 👋</h1>
        <p class="text-sm text-slate-500">Here's an overview of your barangay activity.</p>
    </div>

    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <x-stat-card label="Document Requests" :value="$docs_count" tone="brand" icon="📄"/>
        <x-stat-card label="Ready to Pickup"   :value="$docs_ready" tone="success" icon="✔"/>
        <x-stat-card label="My Complaints"     :value="$complaints_count" tone="warn" icon="!"/>
        <x-stat-card label="Open Cases"        :value="$open_complaints" tone="danger" icon="⌛"/>
    </div>

    <div class="grid gap-6 lg:grid-cols-3">
        <div class="card lg:col-span-2"><div class="card-body">
            <h2 class="text-lg font-semibold">Quick actions</h2>
            <div class="mt-4 grid gap-3 sm:grid-cols-2">
                <a href="{{ route('resident.documents.create') }}" class="card hover:shadow-md transition">
                    <div class="card-body">
                        <p class="font-semibold">Request a Document</p>
                        <p class="text-sm text-slate-500">Clearance, residency, indigency, business clearance.</p>
                    </div>
                </a>
                <a href="{{ route('resident.complaints.create') }}" class="card hover:shadow-md transition">
                    <div class="card-body">
                        <p class="font-semibold">File a Complaint</p>
                        <p class="text-sm text-slate-500">Submit a formal complaint online.</p>
                    </div>
                </a>
                <a href="{{ route('resident.skills.create') }}" class="card hover:shadow-md transition">
                    <div class="card-body">
                        <p class="font-semibold">Register a Skill / Service</p>
                        <p class="text-sm text-slate-500">List your service for the community.</p>
                    </div>
                </a>
                <a href="{{ route('resident.appointments.create') }}" class="card hover:shadow-md transition">
                    <div class="card-body">
                        <p class="font-semibold">Book an Appointment</p>
                        <p class="text-sm text-slate-500">Walk-in to the barangay hall.</p>
                    </div>
                </a>
            </div>
        </div></div>

        <div class="card"><div class="card-body">
            <h2 class="text-lg font-semibold">Upcoming appointments</h2>
            <ul class="mt-4 space-y-3 text-sm">
                @forelse ($appointments as $a)
                    <li class="border-b pb-2 last:border-0">
                        <p class="font-medium">{{ $a->purpose }}</p>
                        <p class="text-xs text-slate-500">{{ $a->scheduled_at->format('M d, Y · h:i A') }}</p>
                        <x-status-badge :status="$a->status"/>
                    </li>
                @empty
                    <li class="text-sm text-slate-500">No upcoming appointments.</li>
                @endforelse
            </ul>
        </div></div>
    </div>

    <div class="card"><div class="card-body">
        <h2 class="text-lg font-semibold">Latest announcements</h2>
        <ul class="mt-4 grid gap-3 sm:grid-cols-3">
            @foreach ($announcements as $a)
                <li class="rounded-lg border p-3">
                    <span class="badge-info uppercase">{{ $a->priority }}</span>
                    <p class="mt-2 font-medium">{{ $a->title }}</p>
                    <p class="mt-1 line-clamp-2 text-sm text-slate-600">{{ \Illuminate\Support\Str::limit(strip_tags($a->body), 100) }}</p>
                </li>
            @endforeach
        </ul>
    </div></div>
</div>
</x-layouts.app>
