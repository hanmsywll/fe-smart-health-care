<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Smart Health Care</title>

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="min-h-screen bg-gray-50 text-gray-900">
        <main class="max-w-2xl mx-auto px-6 py-16">
            <h1 class="text-3xl font-bold">Laravel + Tailwind (polosan)</h1>
            <p class="mt-4 text-gray-700">Tailwind terpasang via Vite dan siap dipakai.</p>
            <div class="mt-6 flex gap-3">
                <a href="https://tailwindcss.com/docs" class="inline-flex items-center rounded-md bg-black text-white px-4 py-2">Dokumentasi Tailwind</a>
                <a href="https://laravel.com/docs" class="inline-flex items-center rounded-md border border-black px-4 py-2">Dokumentasi Laravel</a>
            </div>
        </main>
    </body>
</html>