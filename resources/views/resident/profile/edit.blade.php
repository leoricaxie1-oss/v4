<x-layouts.app>
{{-- Note: legacy gradient hero block intentionally REMOVED per v3 spec --}}
<div class="mx-auto max-w-3xl space-y-6">
    <div>
        <h1 class="text-2xl font-bold">My Profile</h1>
        <p class="text-sm text-slate-500">Keep your information accurate and up-to-date.</p>
    </div>

    <form method="POST" action="{{ route('resident.profile.update') }}" enctype="multipart/form-data" class="card"><div class="card-body">
        @csrf @method('PUT')

        <div class="mb-6 flex items-center gap-4">
            <span class="grid h-16 w-16 place-items-center rounded-full bg-brand-600 text-xl font-bold text-white">{{ $user->initials }}</span>
            <div class="flex-1">
                <label class="form-label">Profile picture</label>
                <input type="file" name="avatar" accept="image/*" class="form-input">
            </div>
        </div>

        <h2 class="mb-2 text-sm font-semibold text-slate-700">Personal information</h2>
        <div class="grid gap-2 sm:grid-cols-2">
            <x-input name="first_name"  label="First name" required :value="$user->first_name" />
            <x-input name="middle_name" label="Middle name"          :value="$user->middle_name" />
            <x-input name="last_name"   label="Last name"  required :value="$user->last_name" />
            <x-input name="suffix"      label="Suffix"                :value="$user->suffix" />
            <x-input name="phone"       label="Mobile" required      :value="$user->phone" />
            <x-input name="birthdate"   type="date" label="Birthdate" :value="$user->resident?->birthdate?->format('Y-m-d')" />
            <x-select name="sex"          label="Sex"
                :options="['male'=>'Male','female'=>'Female','other'=>'Other']"
                :value="$user->resident?->sex" />
            <x-select name="civil_status" label="Civil status"
                :options="['single'=>'Single','married'=>'Married','widowed'=>'Widowed','separated'=>'Separated','divorced'=>'Divorced']"
                :value="$user->resident?->civil_status" />
            <x-input name="occupation"  label="Occupation" :value="$user->resident?->occupation" />
            <x-input name="religion"    label="Religion"   :value="$user->resident?->religion" />
        </div>

        <h2 class="mt-4 mb-2 text-sm font-semibold text-slate-700">Address</h2>
        <x-select name="purok_id" label="Purok" required
            :options="\App\Models\Purok::orderBy('name')->pluck('name', 'id')->all()"
            :value="$user->resident?->purok_id" />

        <h2 class="mt-4 mb-2 text-sm font-semibold text-slate-700">Emergency contact</h2>
        <div class="grid gap-2 sm:grid-cols-3">
            <x-input name="emergency_contact_name"     label="Name"     :value="$user->resident?->emergency_contact_name" />
            <x-input name="emergency_contact_phone"    label="Phone"    :value="$user->resident?->emergency_contact_phone" />
            <x-input name="emergency_contact_relation" label="Relation" :value="$user->resident?->emergency_contact_relation" />
        </div>

        <div class="mt-2 flex justify-end gap-2">
            <button class="btn-primary">Save changes</button>
        </div>
    </div></form>

    <form method="POST" action="{{ route('resident.password.update') }}" class="card"><div class="card-body">
        @csrf @method('PUT')
        <h2 class="text-lg font-semibold">Change password</h2>
        <x-input name="current_password" type="password" label="Current password" required />
        <x-input name="password" type="password" label="New password" required />
        <x-input name="password_confirmation" type="password" label="Confirm new password" required />
        <div class="flex justify-end">
            <button class="btn-primary">Update password</button>
        </div>
    </div></form>
</div>
</x-layouts.app>
