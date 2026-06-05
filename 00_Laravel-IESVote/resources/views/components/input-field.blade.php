@props(['label', 'name', 'placeholder' => '', 'type' => 'text'])

<div class="campo">
    <label>{{ $label }}</label>
    <input id="{{ $name }}" name="{{ $name }}" type="{{ $type }}" placeholder="{{ $placeholder }}"
        value="{{ old($name) }}" {{ $attributes }}>

    <div class="error" id="err{{ ucfirst($name) }}"></div>

    @error($name)
        <div class="error-server">{{ $message }}</div>
    @enderror

    <div class="barra-container">
        <div class="barra" id="barra{{ ucfirst($name) }}"></div>
    </div>
    <div class="contador" id="contador{{ ucfirst($name) }}"></div>
</div>
