<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- 
            Inertia Title: 
            The 'inertia' attribute allows Vue to dynamically update the document title 
        -->
        <title inertia>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts: Figtree is the standard modern sans-serif for Laravel applications -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @routes <!-- Injects Ziggy's Laravel routes into the frontend -->
        @vite(['resources/js/app.js', "resources/js/Pages/{$page['component']}.vue"]) <!-- Hot Module Replacement (HMR) and production bundling via Vite -->
        @inertiaHead <!-- Injects meta tags, CSS, and SSR-specific head content -->
    </head>
    <body class="font-sans antialiased">
        <!-- 
            The Inertia Root Element: 
            This is where the Vue application is mounted and injected.
        -->
        @inertia
    </body>
</html>
