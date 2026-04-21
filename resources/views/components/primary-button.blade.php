<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center justify-center px-4 py-2.5 rounded-lg bg-[var(--accent)] font-semibold text-xs uppercase tracking-widest text-[#0b0b0b] transition duration-150 hover:bg-white focus:bg-white focus:outline-none']) }}>
    {{ $slot }}
</button>
