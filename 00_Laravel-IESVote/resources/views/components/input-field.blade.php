{{-- 1. ENTRADA DE DATOS  --}}
@props(['label', 'name', 'placeholder' => '', 'type' => 'text'])

<div class="campo">
    {{-- 2. EL TÍTULO  --}}
    <label>{{ $label }}</label>

    {{-- 3. LA CASILLA DE ENTRADA  --}}
    <input id="{{ $name }}" name="{{ $name }}" type="{{ $type }}" placeholder="{{ $placeholder }}"
        value="{{ old($name) }}" {{ $attributes }}>

    {{-- 5. ERRORES DE LARAVEL DESDE EL SERVIDOR  --}}
    @error($name)
        <div class="error-server">{{ $message }}</div>
    @enderror
</div>
