{{-- Premium Confirmation Modal - uses Alpine.js --}}
<div
    x-data="{
        show: false,
        title: '',
        message: '',
        icon: 'warning',
        iconColor: 'tertiary',
        confirmText: 'Konfirmasi',
        confirmColor: 'error',
        action: null,
        open(config) {
            this.title = config.title || 'Konfirmasi';
            this.message = config.message || 'Apakah Anda yakin?';
            this.icon = config.icon || 'warning';
            this.iconColor = config.iconColor || 'tertiary';
            this.confirmText = config.confirmText || 'Konfirmasi';
            this.confirmColor = config.confirmColor || 'error';
            this.action = config.action || null;
            this.show = true;
        },
        confirm() {
            if (this.action) {
                this.action();
            }
            this.show = false;
        },
        close() {
            this.show = false;
        }
    }"
    x-on:confirm-modal.window="open($event.detail)"
    x-show="show"
    x-cloak
    class="fixed inset-0 z-[100] flex items-end justify-center sm:items-center"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
>
    {{-- Backdrop --}}
    <div class="fixed inset-0 bg-black/40 backdrop-blur-sm" x-on:click="close"></div>

    {{-- Modal Panel --}}
    <div
        class="relative w-full max-w-sm mx-5 mb-8 sm:mb-0"
        x-show="show"
        x-transition:enter="transition ease-out duration-300 delay-75"
        x-transition:enter-start="opacity-0 translate-y-8 scale-95"
        x-transition:enter-end="opacity-100 translate-y-0 scale-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 translate-y-0 scale-100"
        x-transition:leave-end="opacity-0 translate-y-4 scale-95"
    >
        <div class="bg-white rounded-3xl shadow-2xl overflow-hidden">
            {{-- Content --}}
            <div class="p-6 text-center">
                {{-- Icon --}}
                <div class="mx-auto mb-4 w-16 h-16 rounded-2xl flex items-center justify-center"
                     :class="{
                        'bg-danger-light': iconColor === 'error',
                        'bg-tertiary-10': iconColor === 'tertiary',
                        'bg-primary-10': iconColor === 'primary',
                        'bg-success-light': iconColor === 'success'
                     }">
                    <span class="material-symbols-outlined text-3xl"
                          :class="{
                            'text-error': iconColor === 'error',
                            'text-tertiary': iconColor === 'tertiary',
                            'text-primary': iconColor === 'primary',
                            'text-success': iconColor === 'success'
                          }"
                          style="font-variation-settings: 'FILL' 1;"
                          x-text="icon"></span>
                </div>

                {{-- Title --}}
                <h3 class="text-lg font-extrabold text-on-surface font-headline" x-text="title"></h3>

                {{-- Message --}}
                <p class="text-sm text-on-surface-variant mt-2 leading-relaxed" x-text="message"></p>
            </div>

            {{-- Actions --}}
            <div class="px-6 pb-6 flex gap-3">
                <button
                    x-on:click="close"
                    class="flex-1 py-3 rounded-xl text-sm font-bold text-on-surface-variant bg-surface-container-high hover:bg-surface-container-highest active:scale-95 transition-all"
                >
                    Batal
                </button>
                <button
                    x-on:click="confirm"
                    class="flex-1 py-3 rounded-xl text-sm font-bold text-white active:scale-95 transition-all shadow-sm"
                    :class="{
                        'bg-error hover:bg-red-700': confirmColor === 'error',
                        'bg-primary hover:bg-blue-700': confirmColor === 'primary',
                        'bg-success hover:bg-green-700': confirmColor === 'success',
                        'bg-tertiary hover:bg-orange-700': confirmColor === 'tertiary'
                    }"
                    x-text="confirmText"
                >
                </button>
            </div>
        </div>
    </div>
</div>
