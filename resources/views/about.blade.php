<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>About Us — Smart Health Care</title>
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
            <h1 class="text-3xl font-bold">Tentang Smart Health Care</h1>
            <p class="mt-4 text-gray-700">
                Smart Health Care adalah inisiatif untuk menghadirkan layanan kesehatan yang
                terintegrasi, memanfaatkan API Gateway untuk menyatukan berbagai layanan seperti
                penjadwalan janji temu, status layanan, dan informasi fasilitas kesehatan.
            </p>
            <p class="mt-3 text-gray-700">
                Proyek ini bagian dari mata kuliah Enterprise Application Integration / Web Service Development,
                dengan fokus frontend yang mengonsumsi API dan mendokumentasikan alur melalui Swagger/Postman.
            </p>

            <div class="mt-8">
                <a href="/ketersediaan" class="inline-block px-4 py-2 bg-black text-white rounded-md">Cek Jadwal</a>
                <a href="/status" class="inline-block ml-3 px-4 py-2 border border-black rounded-md">Status Layanan</a>
            </div>
        </main>
    </body>
    </html>