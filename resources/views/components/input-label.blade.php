@props(['value'])

<label {{ $attributes->merge(['class' => 'block text-sm font-medium text-[#b5b5b5]']) }}>
    {{ $value ?? $slot }}
</label>
