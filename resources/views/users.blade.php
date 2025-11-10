<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Users — Smart Health Care</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="min-h-screen bg-gray-50 text-gray-900">
        <main class="max-w-3xl mx-auto px-6 py-12">
            <h1 class="text-2xl font-bold">Users</h1>
            @if ($error ?? false)
                <div class="mt-4 rounded-md bg-red-50 text-red-700 border border-red-200 p-4">
                    {{ $error }}
                </div>
            @endif

            @if (empty($users))
                <p class="mt-4 text-gray-700">Belum ada data pengguna.</p>
            @else
                <div class="mt-6 overflow-hidden rounded-lg border border-gray-200 bg-white">
                    <table class="w-full text-left">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="px-4 py-2 text-sm">ID</th>
                                <th class="px-4 py-2 text-sm">Nama</th>
                                <th class="px-4 py-2 text-sm">Email</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)
                                <tr class="border-t border-gray-200">
                                    <td class="px-4 py-2 text-sm">{{ $user['id'] ?? '-' }}</td>
                                    <td class="px-4 py-2 text-sm">{{ $user['name'] ?? '-' }}</td>
                                    <td class="px-4 py-2 text-sm">{{ $user['email'] ?? '-' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif

            <div class="mt-8">
                <a href="/" class="inline-block px-4 py-2 border border-black rounded-md">Kembali</a>
            </div>
        </main>
    </body>
</html>