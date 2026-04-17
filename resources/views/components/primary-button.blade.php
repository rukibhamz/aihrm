<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-6 py-3 bg-neutral-900 border border-white/10 rounded-xl font-bold text-xs text-white uppercase tracking-widest hover:bg-black focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2 transition-all shadow-lg active:scale-95 duration-150']) }}>
    {{ $slot }}
</button>
