<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport" />
    <title>{{ $title ?? 'eSarana' }} — Precision Architect</title>
    <meta name="description" content="Sistem Manajemen Sarana & Prasarana">
    <meta name="theme-color" content="#1a56db">

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
                <button class="w-10 h-10 flex items-center justify-center rounded-xl hover:bg-surface-container-high transition-all duration-200 active:scale-95">
                    <span class="material-symbols-outlined text-on-surface-variant text-[22px]">search</span>
                </button>
                <button class="w-10 h-10 flex items-center justify-center rounded-xl hover:bg-surface-container-high transition-all duration-200 relative active:scale-95">
                    <span class="material-symbols-outlined text-on-surface-variant text-[22px]">notifications</span>
                    <span class="absolute top-2 right-2 w-2.5 h-2.5 bg-tertiary rounded-full ring-2 ring-white"></span>
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

    {{-- Confirmation Modal --}}
    <x-confirm-modal />

    @livewireScripts
</body>
</html>
