@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'rounded-lg border border-[var(--border)] bg-[#161616] px-3.5 py-2.5 text-[#f0f0f0] shadow-none outline-none placeholder:text-[#666] focus:border-[var(--accent)] focus:ring-0']) }}>
