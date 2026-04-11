{{-- Notification panel — rendered inside the layout's Alpine notif scope --}}
<div>
    {{-- Panel --}}
    <div x-show="notifOpen"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 -translate-y-2"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 -translate-y-2"
         x-cloak
         class="fixed top-[3.75rem] left-0 right-0 z-50 max-w-lg mx-auto px-3">

        <div class="bg-surface rounded-2xl shadow-2xl border border-surface-container-high overflow-hidden">
            {{-- Header --}}
            <div class="flex items-center justify-between px-4 py-3.5 border-b border-surface-container-high">
                <div class="flex items-center gap-2">
                    <span class="material-symbols-outlined text-on-surface text-[20px]">notifications</span>
                    <h3 class="text-sm font-extrabold text-on-surface font-headline">Notifikasi</h3>
                    @if($items->isNotEmpty())
                    <span class="inline-flex items-center justify-center w-5 h-5 rounded-full bg-error text-white text-[10px] font-bold">
                        {{ $items->count() }}
                    </span>
                    @endif
                </div>
                <button @click="notifOpen = false"
                        class="icon-container-sm bg-surface-container-high active:scale-90 transition-transform">
                    <span class="material-symbols-outlined text-on-surface-variant text-lg">close</span>
                </button>
            </div>

            {{-- Items --}}
            <div class="divide-y divide-surface-container-high max-h-[70vh] overflow-y-auto">
                @forelse($items as $item)
                <a href="{{ $item['link'] }}" wire:navigate
                   @click="notifOpen = false"
                   class="flex items-start gap-3 px-4 py-3.5 active:bg-surface-container-low transition-colors block">
                    <div class="icon-container-sm {{ $item['bg'] }} shrink-0 mt-0.5">
                        <span class="material-symbols-outlined text-{{ $item['color'] }} text-[16px]"
                              style="font-variation-settings: 'FILL' 1;">{{ $item['icon'] }}</span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold text-on-surface truncate">{{ $item['title'] }}</p>
                        <p class="text-[11px] text-on-surface-variant mt-0.5">{{ $item['body'] }}</p>
                    </div>
                    <span class="text-[10px] text-on-surface-variant shrink-0 mt-0.5 whitespace-nowrap">{{ $item['time'] }}</span>
                </a>
                @empty
                <div class="flex flex-col items-center justify-center py-10 text-on-surface-variant gap-2">
                    <span class="material-symbols-outlined text-3xl opacity-30">notifications_off</span>
                    <p class="text-sm font-medium">Semua beres!</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Backdrop --}}
    <div x-show="notifOpen"
         x-cloak
         x-transition:enter="transition ease-out duration-150"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-100"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @click="notifOpen = false"
         class="fixed inset-0 z-40 bg-black/20 backdrop-blur-[2px]">
    </div>
</div>
