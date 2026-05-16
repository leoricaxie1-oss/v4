<x-layouts.auth>
<div class="card">
    <div class="card-body">
        <h1 class="text-xl font-bold text-slate-900">Create a Resident account</h1>
        <p class="mt-1 text-sm text-slate-500">
            Your account will be reviewed in 3 steps: Secretary → Kagawad → Captain.
        </p>

        <form method="POST" action="{{ route('register') }}" class="mt-6" x-data="registerForm()">
            @csrf

            <div class="grid gap-2 sm:grid-cols-2">
                <x-input name="first_name"  label="First Name"   required />
                <x-input name="middle_name" label="Middle Name" />
                <x-input name="last_name"   label="Last Name"    required />
                <x-input name="suffix"      label="Suffix (e.g. Jr.)" />
            </div>

            <x-input name="email" type="email" label="Email address" required />
            <x-input name="phone" label="Mobile number" required
                     placeholder="09XXXXXXXXX or +639XXXXXXXXX" />

            <h2 class="mt-4 text-sm font-semibold text-slate-700">Address</h2>

            <div class="grid gap-2 sm:grid-cols-2">
                <div class="mb-4">
                    <label class="form-label required" for="province_id">Province</label>
                    <select id="province_id" name="province_id" required class="form-input" x-model="province" @change="loadCities()">
                        <option value="">-- Select Province --</option>
                        @foreach ($provinces as $p)
                            <option value="{{ $p->id }}" @selected(old('province_id') == $p->id)>{{ $p->name }}</option>
                        @endforeach
                    </select>
                    @error('province_id') <p class="form-error">{{ $message }}</p> @enderror
                </div>

                <div class="mb-4">
                    <label class="form-label required" for="city_id">City / Municipality</label>
                    <select id="city_id" name="city_id" required class="form-input" x-model="city" @change="loadBarangays()">
                        <option value="">-- Select City --</option>
                        <template x-for="c in cities" :key="c.id">
                            <option :value="c.id" x-text="c.name"></option>
                        </template>
                    </select>
                    @error('city_id') <p class="form-error">{{ $message }}</p> @enderror
                </div>

                <div class="mb-4">
                    <label class="form-label required" for="barangay_id">Barangay</label>
                    <select id="barangay_id" name="barangay_id" required class="form-input" x-model="barangay" @change="loadPuroks()">
                        <option value="">-- Select Barangay --</option>
                        <template x-for="b in barangays" :key="b.id">
                            <option :value="b.id" x-text="b.name"></option>
                        </template>
                    </select>
                    @error('barangay_id') <p class="form-error">{{ $message }}</p> @enderror
                </div>

                <div class="mb-4">
                    <label class="form-label required" for="purok_id">Purok</label>
                    <select id="purok_id" name="purok_id" required class="form-input" x-model="purok">
                        <option value="">-- Select Purok --</option>
                        <template x-for="p in puroks" :key="p.id">
                            <option :value="p.id" x-text="p.name"></option>
                        </template>
                    </select>
                    @error('purok_id') <p class="form-error">{{ $message }}</p> @enderror
                </div>
            </div>

            {{-- Password with strength meter --}}
            <div x-data="{show:false, val:'', meter: {score:0, label:'—', color:'bg-slate-200'}}" class="mb-4">
                <label class="form-label required" for="password">Password</label>
                <div class="relative">
                    <input :type="show ? 'text' : 'password'" id="password" name="password" required class="form-input pr-12"
                           x-model="val" @input="meter = window.passwordStrength(val)">
                    <button type="button" @click="show=!show" class="absolute right-2 top-2 text-xs text-slate-500"
                            x-text="show ? 'Hide' : 'Show'"></button>
                </div>
                <div class="mt-2 flex h-1.5 w-full overflow-hidden rounded-full bg-slate-200">
                    <div class="h-full transition-all" :class="meter.color" :style="`width:${(meter.score/5)*100}%`"></div>
                </div>
                <p class="form-help">Strength: <span x-text="meter.label" class="font-semibold"></span> — must include uppercase, lowercase, number, and special character (min. 8 chars).</p>
                @error('password') <p class="form-error">{{ $message }}</p> @enderror
            </div>

            <x-input name="password_confirmation" type="password" label="Confirm password" required />

            <label class="mb-4 flex items-start gap-2 text-sm">
                <input type="checkbox" name="terms" value="1" required class="mt-1 rounded">
                <span>I confirm the information above is true and complete.</span>
            </label>

            <button class="btn-primary w-full">Submit registration</button>
        </form>

        <p class="mt-6 text-center text-sm text-slate-500">
            Already have an account?
            <a href="{{ route('login') }}" class="font-semibold text-brand-600 hover:underline">Sign in</a>
        </p>
    </div>
</div>

<script>
function registerForm() {
    return {
        province:  @json(old('province_id', '')),
        city:      @json(old('city_id', '')),
        barangay:  @json(old('barangay_id', '')),
        purok:     @json(old('purok_id', '')),
        cities: [], barangays: [], puroks: [],

        init() {
            if (this.province) this.loadCities(true);
        },
        async loadCities(keep = false) {
            this.cities = []; this.barangays = []; this.puroks = [];
            if (!keep) { this.city=''; this.barangay=''; this.purok=''; }
            if (!this.province) return;
            const r = await fetch(`/lookup/cities/${this.province}`);
            this.cities = await r.json();
            if (keep && this.city) await this.loadBarangays(true);
        },
        async loadBarangays(keep = false) {
            this.barangays = []; this.puroks = [];
            if (!keep) { this.barangay=''; this.purok=''; }
            if (!this.city) return;
            const r = await fetch(`/lookup/barangays/${this.city}`);
            this.barangays = await r.json();
            if (keep && this.barangay) await this.loadPuroks(true);
        },
        async loadPuroks(keep = false) {
            this.puroks = [];
            if (!keep) this.purok = '';
            if (!this.barangay) return;
            const r = await fetch(`/lookup/puroks/${this.barangay}`);
            this.puroks = await r.json();
        }
    };
}
</script>
</x-layouts.auth>
