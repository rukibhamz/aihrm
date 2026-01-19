<div
    x-data="{ 
        show: false, 
        message: '', 
        type: 'success',
        init() {
            window.addEventListener('notify', (e) => {
                this.message = e.detail.message;
                this.type = e.detail.type || 'success';
                this.show = true;
                setTimeout(() => { this.show = false; }, 5000);
            });
        }
    }"
    x-show="show"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0 transform translate-y-2"
    x-transition:enter-end="opacity-100 transform translate-y-0"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100 transform translate-y-0"
    x-transition:leave-end="opacity-0 transform translate-y-2"
    class="fixed bottom-4 right-4 z-50 pointer-events-none"
    style="display: none;"
>
    <div 
        :class="{
            'bg-black text-white': type === 'success',
            'bg-red-600 text-white': type === 'error',
            'bg-neutral-800 text-white': type === 'info'
        }"
        class="px-6 py-3 rounded-lg shadow-2xl flex items-center gap-3 pointer-events-auto"
    >
        <template x-if="type === 'success'">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
        </template>
        <template x-if="type === 'error'">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </template>
        <span x-text="message" class="font-medium text-sm"></span>
    </div>
</div>
