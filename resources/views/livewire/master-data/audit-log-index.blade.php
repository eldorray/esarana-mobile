<div class="animate-fade-in">

    {{-- Header --}}
    <section class="pt-3 mb-5">
        <div class="flex items-center gap-3 mb-1">
            <a href="{{ route('master-data') }}" wire:navigate
               class="icon-container-sm bg-surface-container-high active:scale-90 transition-transform">
                <span class="material-symbols-outlined text-on-surface-variant text-lg">arrow_back</span>
            </a>
            <div>
                <p class="text-on-surface-variant text-xs font-semibold uppercase tracking-widest">Master Data</p>
                <h1 class="text-xl font-extrabold tracking-tight text-on-surface font-headline">Audit Log</h1>
            </div>
        </div>
        <p class="text-on-surface-variant text-sm mt-1 ml-[2.75rem]">Rekam jejak semua perubahan data sistem.</p>
    </section>

    {{-- Filters --}}
    <section class="mb-4 space-y-2.5">
        <div class="relative">
            <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-on-surface-variant text-xl">search</span>
            <input wire:model.live.debounce.300ms="search"
                   type="search"
                   placeholder="Cari nama data atau pengguna..."
                   class="input-precision pl-11">
        </div>
        <div class="flex gap-2">
            <select wire:model.live="filterAction" class="input-precision flex-1 text-sm">
                <option value="">Semua Aksi</option>
                <option value="created">Dibuat</option>
                <option value="updated">Diubah</option>
                <option value="deleted">Dihapus</option>
            </select>
            <select wire:model.live="filterModel" class="input-precision flex-1 text-sm">
                <option value="">Semua Model</option>
                @foreach($modelTypes as $type)
                <option value="{{ $type['value'] }}">{{ $type['label'] }}</option>
                @endforeach
            </select>
        </div>
    </section>

    {{-- Stats strip --}}
    <section class="grid grid-cols-3 gap-2 mb-5">
        @php
            $counts = \App\Models\AuditLog::selectRaw('action, count(*) as total')
                ->groupBy('action')->pluck('total','action');
        @endphp
        @foreach([['created','add_circle','success'],['updated','edit','primary'],['deleted','delete','error']] as [$act,$icon,$color])
        <div class="bg-surface-container-low rounded-2xl px-3 py-2.5 text-center">
            <span class="material-symbols-outlined text-{{ $color }} text-[18px]"
                  style="font-variation-settings: 'FILL' 1;">{{ $icon }}</span>
            <p class="text-base font-extrabold text-on-surface font-headline leading-tight">{{ $counts[$act] ?? 0 }}</p>
            <p class="text-[10px] text-on-surface-variant font-semibold uppercase">{{ ucfirst($act) }}</p>
        </div>
        @endforeach
    </section>

    {{-- Log list --}}
    @if($logs->isEmpty())
    <div class="text-center py-16 text-on-surface-variant">
        <span class="material-symbols-outlined text-5xl opacity-30 block mb-3">manage_search</span>
        <p class="text-sm font-medium">Belum ada log aktivitas</p>
    </div>
    @else
    <div class="space-y-2 stagger-children">
        @foreach($logs as $log)
        @php
            $colorMap = ['created' => 'success', 'updated' => 'primary', 'deleted' => 'error'];
            $bgMap    = ['created' => 'bg-success-light', 'updated' => 'bg-primary-10', 'deleted' => 'bg-danger-light'];
            $color    = $colorMap[$log->action] ?? 'tertiary';
            $bg       = $bgMap[$log->action]    ?? 'bg-tertiary-10';
            $changed  = $log->changed_fields;
        @endphp
        <div x-data="{ expanded: false }" class="card-elevated overflow-hidden">
            <button @click="expanded = !expanded"
                    class="w-full flex items-start gap-3 px-4 py-3.5 text-left active:bg-surface-container-low transition-colors">
                <div class="icon-container-sm {{ $bg }} shrink-0 mt-0.5">
                    <span class="material-symbols-outlined text-{{ $color }} text-[16px]"
                          style="font-variation-settings: 'FILL' 1;">{{ $log->action_icon }}</span>
                </div>
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-1.5 flex-wrap">
                        <span class="text-[10px] font-bold uppercase tracking-wider px-1.5 py-0.5 rounded-md
                            {{ $bg }} text-{{ $color }}">{{ $log->action }}</span>
                        <span class="text-[11px] font-bold text-on-surface-variant">{{ $log->model_short_name }}</span>
                    </div>
                    <p class="text-sm font-semibold text-on-surface mt-0.5 truncate">{{ $log->model_label }}</p>
                    <div class="flex items-center gap-2 mt-1">
                        <span class="text-[10px] text-on-surface-variant">
                            {{ $log->user?->name ?? 'System' }}
                        </span>
                        <span class="text-[10px] text-on-surface-variant opacity-50">•</span>
                        <span class="text-[10px] text-on-surface-variant">{{ $log->created_at->diffForHumans() }}</span>
                        @if($log->action === 'updated' && count($changed))
                        <span class="text-[10px] text-on-surface-variant opacity-50">•</span>
                        <span class="text-[10px] text-primary font-semibold">{{ count($changed) }} field berubah</span>
                        @endif
                    </div>
                </div>
                <span class="material-symbols-outlined text-on-surface-variant text-lg shrink-0 mt-0.5 transition-transform duration-200"
                      :class="expanded ? 'rotate-180' : ''">expand_more</span>
            </button>

            {{-- Expanded detail --}}
            <div x-show="expanded"
                 x-transition:enter="transition ease-out duration-150"
                 x-transition:enter-start="opacity-0 -translate-y-1"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 x-cloak
                 class="border-t border-surface-container-high px-4 py-3 bg-surface-container-low space-y-2">

                <div class="flex items-center gap-2 text-[11px] text-on-surface-variant">
                    <span class="material-symbols-outlined text-[14px]">schedule</span>
                    {{ $log->created_at->format('d M Y, H:i:s') }}
                    @if($log->ip_address)
                    <span class="opacity-50">•</span>
                    <span class="font-mono">{{ $log->ip_address }}</span>
                    @endif
                </div>

                @if($log->action === 'updated' && count($changed))
                <p class="text-[10px] font-bold text-on-surface-variant uppercase tracking-wider mt-2">Perubahan</p>
                @foreach($changed as $field => $diff)
                <div class="rounded-xl bg-surface px-3 py-2 text-xs">
                    <span class="font-bold text-on-surface-variant">{{ $field }}</span>
                    <div class="flex items-center gap-2 mt-1 flex-wrap">
                        <span class="line-through text-error/70 break-all">{{ $diff['from'] ?? '—' }}</span>
                        <span class="material-symbols-outlined text-[12px] text-on-surface-variant">arrow_forward</span>
                        <span class="text-success font-semibold break-all">{{ $diff['to'] ?? '—' }}</span>
                    </div>
                </div>
                @endforeach

                @elseif($log->action === 'created' && $log->new_values)
                <p class="text-[10px] font-bold text-on-surface-variant uppercase tracking-wider mt-2">Data Dibuat</p>
                <div class="rounded-xl bg-surface px-3 py-2 space-y-1">
                    @foreach($log->new_values as $field => $value)
                    <div class="flex gap-2 text-xs">
                        <span class="text-on-surface-variant shrink-0 w-24 truncate">{{ $field }}</span>
                        <span class="text-on-surface font-medium break-all">{{ $value ?? '—' }}</span>
                    </div>
                    @endforeach
                </div>

                @elseif($log->action === 'deleted' && $log->old_values)
                <p class="text-[10px] font-bold text-on-surface-variant uppercase tracking-wider mt-2">Data Dihapus</p>
                <div class="rounded-xl bg-surface px-3 py-2 space-y-1">
                    @foreach($log->old_values as $field => $value)
                    <div class="flex gap-2 text-xs">
                        <span class="text-on-surface-variant shrink-0 w-24 truncate">{{ $field }}</span>
                        <span class="text-on-surface font-medium break-all line-through opacity-60">{{ $value ?? '—' }}</span>
                    </div>
                    @endforeach
                </div>
                @endif
            </div>
        </div>
        @endforeach
    </div>

    {{-- Pagination --}}
    @if($logs->hasPages())
    <div class="mt-5 flex justify-center gap-2">
        @if($logs->onFirstPage())
        <span class="icon-container-sm bg-surface-container text-on-surface-variant opacity-40">
            <span class="material-symbols-outlined text-lg">chevron_left</span>
        </span>
        @else
        <button wire:click="previousPage" class="icon-container-sm bg-surface-container-high active:scale-90 transition-transform">
            <span class="material-symbols-outlined text-on-surface-variant text-lg">chevron_left</span>
        </button>
        @endif
        <span class="px-4 py-2 text-sm font-semibold text-on-surface-variant">
            {{ $logs->currentPage() }} / {{ $logs->lastPage() }}
        </span>
        @if($logs->hasMorePages())
        <button wire:click="nextPage" class="icon-container-sm bg-surface-container-high active:scale-90 transition-transform">
            <span class="material-symbols-outlined text-on-surface-variant text-lg">chevron_right</span>
        </button>
        @else
        <span class="icon-container-sm bg-surface-container text-on-surface-variant opacity-40">
            <span class="material-symbols-outlined text-lg">chevron_right</span>
        </span>
        @endif
    </div>
    @endif
    @endif

</div>
