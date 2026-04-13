<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport" />
    <title>{{ $title ?? 'e-SARPRAS' }}</title>
    <meta name="description" content="Sistem Manajemen Sarana Prasarana Digital">
    <meta name="theme-color" content="#1a56db">
    <link rel="manifest" href="/manifest.json">
    <link rel="icon" type="image/x-icon" href="/favicon.ico">
    <link rel="icon" type="image/png" sizes="32x32" href="/icons/icon-32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/icons/icon-16.png">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="apple-mobile-web-app-title" content="e-SARPRAS">
    <link rel="apple-touch-icon" href="/icons/icon-180.png">

    {{-- Google Fonts & Material Symbols --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Manrope:wght@500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="bg-surface text-on-surface antialiased min-h-screen pb-28">

    @php
        $user = auth()->user();
        $currentRoute = request()->route()?->getName() ?? '';
        $roleName = $user?->roles?->first()?->name ?? 'staff';
        $canManageLokasi = $user?->can('manage_lokasi') ?? false;
        $isAdmin = $roleName === 'administrator';
    @endphp

    <!-- Top App Bar -->
    <header class="topbar-glass fixed top-0 w-full z-40">
        <div class="flex justify-between items-center w-full px-5 py-3.5 max-w-lg mx-auto">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl overflow-hidden bg-gradient-to-br from-primary to-primary-container flex items-center justify-center shadow-sm">
                    <span class="text-white font-bold text-xs font-headline tracking-wide">eS</span>
                </div>
                <div>
                    <span class="text-base font-extrabold tracking-tight text-on-surface font-headline">eSarana</span>
                    <span class="text-[10px] text-on-surface-variant block -mt-0.5 font-medium">{{ ucfirst($roleName) }}</span>
                </div>
            </div>
            <div class="flex items-center gap-1">
                <a href="{{ route('cari') }}" wire:navigate class="w-10 h-10 flex items-center justify-center rounded-xl hover:bg-surface-container-high transition-all duration-200 active:scale-95 {{ $currentRoute === 'cari' ? 'bg-primary-10' : '' }}">
                    <span class="material-symbols-outlined {{ $currentRoute === 'cari' ? 'text-primary' : 'text-on-surface-variant' }} text-[22px]">search</span>
                </a>
                <button x-data
                        @click="$store('notif').open = !$store('notif').open"
                        :class="$store('notif').open ? 'bg-primary-10' : ''"
                        class="w-10 h-10 flex items-center justify-center rounded-xl hover:bg-surface-container-high transition-all duration-200 relative active:scale-95">
                    <span class="material-symbols-outlined text-[22px]"
                          :class="$store('notif').open ? 'text-primary' : 'text-on-surface-variant'"
                          style="{{ "font-variation-settings: 'FILL' 0;" }}">notifications</span>
                    @php
                        $notifCount = \App\Models\Peminjaman::where('status','aktif')->whereDate('tanggal_kembali_rencana','<',today())->count()
                            + \App\Models\Laporan::where('status','baru')->count()
                            + \App\Models\BahanHabisPakai::whereColumn('stok','<=','stok_minimum')->count();
                    @endphp
                    @if($notifCount > 0)
                    <span class="absolute top-1.5 right-1.5 min-w-[16px] h-4 px-1 bg-error text-white text-[9px] font-bold rounded-full ring-2 ring-surface flex items-center justify-center">
                        {{ $notifCount > 9 ? '9+' : $notifCount }}
                    </span>
                    @endif
                </button>
                {{-- User Avatar --}}
                <a href="#user-section" class="w-10 h-10 rounded-xl bg-gradient-to-br from-primary to-primary-container flex items-center justify-center text-white text-xs font-bold ml-1 active:scale-95 transition-transform">
                    {{ strtoupper(substr($user->name ?? 'U', 0, 2)) }}
                </a>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="pt-[4.5rem] px-5 max-w-lg mx-auto">
        {{ $slot }}
    </main>

    <!-- Floating Action Button -->
    @if(isset($showFab) && $showFab)
    <a href="{{ $fabHref ?? '#' }}" class="fab" wire:navigate>
        <span class="material-symbols-outlined text-[26px]">add</span>
    </a>
    @endif

    <!-- Bottom Navigation Bar -->
    <nav class="fixed bottom-0 left-0 w-full z-50 bottom-nav rounded-t-2xl">
        <div class="flex justify-around items-center px-2 pt-2 pb-7 max-w-lg mx-auto">
            @php
                $navItems = [
                    ['route' => 'dashboard', 'icon' => 'dashboard', 'label' => 'Beranda', 'match' => $currentRoute === 'dashboard', 'show' => true],
                    ['route' => 'inventaris.index', 'icon' => 'inventory_2', 'label' => 'Inventaris', 'match' => str_starts_with($currentRoute, 'inventaris'), 'show' => true],
                    ['route' => 'peminjaman.index', 'icon' => 'swap_horiz', 'label' => 'Pinjaman', 'match' => str_starts_with($currentRoute, 'peminjaman'), 'show' => true],
                    ['route' => 'master-data', 'icon' => 'more_horiz', 'label' => 'Lainnya', 'match' => str_starts_with($currentRoute, 'master'), 'show' => $isAdmin],
                ];
            @endphp
            @foreach($navItems as $nav)
                @if($nav['show'])
                <a href="{{ route($nav['route']) }}" wire:navigate
                   class="flex flex-col items-center justify-center w-16 py-1.5 rounded-2xl transition-all duration-300 {{ $nav['match'] ? 'text-primary' : 'text-on-surface-variant' }}">
                    <div class="relative">
                        @if($nav['match'])
                        <div class="absolute -inset-x-3 -inset-y-1 bg-primary-10 rounded-xl"></div>
                        @endif
                        <span class="material-symbols-outlined text-[24px] relative" style="{{ $nav['match'] ? "font-variation-settings: 'FILL' 1;" : '' }}">{{ $nav['icon'] }}</span>
                    </div>
                    <span class="text-[10px] font-semibold mt-1 {{ $nav['match'] ? 'text-primary' : '' }}">{{ $nav['label'] }}</span>
                </a>
                @endif
            @endforeach
        </div>
    </nav>

    {{-- Notification Panel (inline — no Livewire wrapper to avoid Alpine scope isolation) --}}
    @php
        $notifUser    = auth()->user();
        $notifIsStaff = $notifUser?->hasRole('staff') ?? false;
        $notifItems   = collect();

        // 1. Peminjaman terlambat
        $overdueQ = \App\Models\Peminjaman::with(['inventaris','user'])
            ->where('status','aktif')
            ->whereDate('tanggal_kembali_rencana','<',today());
        if ($notifIsStaff) $overdueQ->where('user_id', $notifUser->id);
        foreach ($overdueQ->latest('tanggal_kembali_rencana')->take(5)->get() as $p) {
            $days = today()->diffInDays($p->tanggal_kembali_rencana);
            $notifItems->push([
                'type'  => 'overdue',
                'icon'  => 'schedule',
                'color' => 'error',
                'bg'    => 'bg-danger-light',
                'title' => $p->inventaris?->nama ?? 'Aset',
                'body'  => 'Terlambat '.$days.' hari • '.($p->user?->name ?? '—'),
                'link'  => route('peminjaman.index'),
                'time'  => $p->tanggal_kembali_rencana->diffForHumans(),
            ]);
        }

        // 2. Laporan baru
        $laporanQ = \App\Models\Laporan::where('status','baru');
        if ($notifIsStaff) $laporanQ->where('user_id', $notifUser->id);
        foreach ($laporanQ->latest()->take(5)->get() as $l) {
            $notifItems->push([
                'type'  => 'laporan',
                'icon'  => 'report',
                'color' => 'tertiary',
                'bg'    => 'bg-tertiary-10',
                'title' => $l->aset_lokasi,
                'body'  => ucfirst($l->tipe).' • '.$l->pelapor_name,
                'link'  => route('laporan.show', $l),
                'time'  => $l->created_at->diffForHumans(),
            ]);
        }

        // 3. Stok kritis
        foreach (\App\Models\BahanHabisPakai::whereColumn('stok','<=','stok_minimum')->take(5)->get() as $b) {
            $notifItems->push([
                'type'  => 'stok',
                'icon'  => 'warning',
                'color' => 'tertiary',
                'bg'    => 'bg-tertiary-10',
                'title' => $b->nama,
                'body'  => 'Stok '.$b->stok.' '.$b->satuan.' (min '.$b->stok_minimum.')',
                'link'  => route('inventaris.bahan.show', $b),
                'time'  => 'Kritis',
            ]);
        }

        $notifItems = $notifItems->sortBy(fn($i) => match($i['type']) {
            'overdue' => 0, 'laporan' => 1, default => 2,
        })->values();
    @endphp

    {{-- Panel --}}
    <div x-data
         x-show="$store('notif').open"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 -translate-y-2"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 -translate-y-2"
         x-cloak
         class="fixed top-[3.75rem] left-0 right-0 z-50 max-w-lg mx-auto px-3">
        <div class="bg-surface rounded-2xl shadow-2xl border border-surface-container-high overflow-hidden">
            <div class="flex items-center justify-between px-4 py-3.5 border-b border-surface-container-high">
                <div class="flex items-center gap-2">
                    <span class="material-symbols-outlined text-on-surface text-[20px]">notifications</span>
                    <h3 class="text-sm font-extrabold text-on-surface font-headline">Notifikasi</h3>
                    @if($notifItems->isNotEmpty())
                    <span class="inline-flex items-center justify-center w-5 h-5 rounded-full bg-error text-white text-[10px] font-bold">{{ $notifItems->count() }}</span>
                    @endif
                </div>
                <button @click="$store('notif').open = false"
                        class="icon-container-sm bg-surface-container-high active:scale-90 transition-transform">
                    <span class="material-symbols-outlined text-on-surface-variant text-lg">close</span>
                </button>
            </div>
            <div class="divide-y divide-surface-container-high max-h-[70vh] overflow-y-auto">
                @forelse($notifItems as $item)
                <a href="{{ $item['link'] }}" wire:navigate
                   @click="$store('notif').open = false"
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
    <div x-data
         x-show="$store('notif').open"
         x-cloak
         x-transition:enter="transition ease-out duration-150"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-100"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @click="$store('notif').open = false"
         class="fixed inset-0 z-40 bg-black/20 backdrop-blur-[2px]">
    </div>

    {{-- Confirmation Modal --}}
    <x-confirm-modal />

    @livewireScripts
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.store('notif', { open: false });
        });
        document.addEventListener('keydown', e => {
            if (e.key === 'Escape') Alpine.store('notif').open = false;
        });
    </script>
</body>
</html>
