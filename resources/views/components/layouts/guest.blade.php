<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport" />
    <title>{{ $title ?? 'eSarana' }}</title>
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
<body class="bg-surface text-on-surface antialiased min-h-screen flex flex-col">
    <main class="flex-1 flex items-center justify-center px-5 py-8">
        <div class="w-full max-w-md">
            {{ $slot }}
        </div>
    </main>

    @livewireScripts
</body>
</html>
