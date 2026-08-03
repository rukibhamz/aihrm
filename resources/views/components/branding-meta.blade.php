@props([
    'title' => null,
    'suffix' => null,
])

@php
    try {
        $resolvedName = $companyName
            ?? \App\Models\Setting::get('company_name')
            ?? config('app.name', 'AIHRM');
        $logoPath = $companyLogo ?? \App\Models\Setting::get('company_logo');
    } catch (\Throwable $e) {
        $resolvedName = config('app.name', 'AIHRM');
        $logoPath = null;
    }

    $appName = filled($resolvedName) ? $resolvedName : 'AIHRM';
    $pageTitle = $title
        ? ($suffix === false ? $title : trim($title.' | '.$appName))
        : $appName;

    $faviconHref = filled($logoPath)
        ? asset('storage/'.$logoPath)
        : asset('favicon.svg');

    $extension = filled($logoPath) ? strtolower(pathinfo($logoPath, PATHINFO_EXTENSION)) : 'svg';
    $faviconType = match ($extension) {
        'svg' => 'image/svg+xml',
        'png' => 'image/png',
        'jpg', 'jpeg' => 'image/jpeg',
        'webp' => 'image/webp',
        'gif' => 'image/gif',
        'ico' => 'image/x-icon',
        default => 'image/png',
    };
@endphp

<title>{{ $pageTitle }}</title>
<link rel="icon" href="{{ $faviconHref }}" type="{{ $faviconType }}">
<link rel="shortcut icon" href="{{ $faviconHref }}">
<link rel="apple-touch-icon" href="{{ $faviconHref }}">
<meta name="application-name" content="{{ $appName }}">
<meta name="apple-mobile-web-app-title" content="{{ $appName }}">
