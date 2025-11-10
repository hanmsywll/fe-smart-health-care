<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Lokasi — Smart Health Care</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="min-h-screen bg-gray-50 text-gray-900">
        <header class="bg-white border-b border-gray-200">
            <div class="max-w-6xl mx-auto px-6 py-4 flex items-center justify-between">
                <a href="/" class="font-semibold text-lg">Smart Health Care</a>
                <nav class="flex items-center gap-6 text-sm">
                    <a href="/" class="hover:underline">Home</a>
                    <a href="/about" class="hover:underline">About Us</a>
                    <a href="/ketersediaan" class="hover:underline">Cek Jadwal</a>
                    <a href="/lokasi" class="hover:underline">Lokasi</a>
                </nav>
            </div>
        </header>

        <main class="max-w-4xl mx-auto px-6 py-12">
            <h1 class="text-3xl font-bold">Lokasi Fasilitas</h1>
            <p class="mt-4 text-gray-700">Temukan lokasi fasilitas kesehatan yang bekerja sama dengan Smart Health Care.</p>

            <div class="mt-6 bg-white border border-gray-200 rounded-lg p-4">
                <div class="text-sm text-gray-600 mb-2">Contoh Embed Peta (placeholder)</div>
                <div class="aspect-video">
                    <iframe
                        class="w-full h-full rounded"
                        src="https://www.google.com/maps?q=Rumah%20Sakit&output=embed"
                        loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade"></iframe>
                </div>
            </div>

            <div class="mt-8">
                <a href="/" class="inline-block px-4 py-2 border border-black rounded-md">Kembali</a>
            </div>
        </main>
    </body>
    </html>