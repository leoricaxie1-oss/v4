@props([
    'label'    => null,
    'name',
    'type'     => 'text',
    'value'    => null,
    'required' => false,
    'help'     => null,
])

<div class="mb-4">
    @if ($label)
        <label for="{{ $name }}" class="form-label {{ $required ? 'required' : '' }}">{{ $label }}</label>
    @endif
    <input
        id="{{ $name }}"
        name="{{ $name }}"
        type="{{ $type }}"
        value="{{ old($name, $value) }}"
        {{ $required ? 'required' : '' }}
        {{ $attributes->merge(['class' => 'form-input']) }}
    >
    @error($name) <p class="form-error">{{ $message }}</p> @enderror
    @if ($help) <p class="form-help">{{ $help }}</p> @endif
</div>
