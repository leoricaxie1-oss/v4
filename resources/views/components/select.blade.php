@props([
    'label'    => null,
    'name',
    'options'  => [],
    'value'    => null,
    'required' => false,
    'placeholder' => '-- Select --',
])

<div class="mb-4">
    @if ($label)
        <label for="{{ $name }}" class="form-label {{ $required ? 'required' : '' }}">{{ $label }}</label>
    @endif
    <select id="{{ $name }}" name="{{ $name }}" {{ $required ? 'required' : '' }} {{ $attributes->merge(['class' => 'form-input']) }}>
        <option value="">{{ $placeholder }}</option>
        @foreach ($options as $val => $text)
            <option value="{{ $val }}" @selected(old($name, $value) == $val)>{{ $text }}</option>
        @endforeach
    </select>
    @error($name) <p class="form-error">{{ $message }}</p> @enderror
</div>
