@props(['label'=>null, 'name', 'rows'=>4, 'value'=>null, 'required'=>false, 'help'=>null])

<div class="mb-4">
    @if ($label) <label for="{{ $name }}" class="form-label {{ $required ? 'required' : '' }}">{{ $label }}</label> @endif
    <textarea
        id="{{ $name }}" name="{{ $name }}" rows="{{ $rows }}"
        {{ $required ? 'required' : '' }}
        {{ $attributes->merge(['class' => 'form-input']) }}
    >{{ old($name, $value) }}</textarea>
    @error($name) <p class="form-error">{{ $message }}</p> @enderror
    @if ($help) <p class="form-help">{{ $help }}</p> @endif
</div>
