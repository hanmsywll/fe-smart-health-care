<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Status — Smart Health Care</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="min-h-screen bg-gray-50 text-gray-900">
        <main class="max-w-3xl mx-auto px-6 py-12">
            <h1 class="text-2xl font-bold">Status Layanan</h1>

            @if ($error ?? false)
                <div class="mt-4 rounded-md bg-red-50 text-red-700 border border-red-200 p-4">
                    {{ $error }}
                </div>
            @endif

            @if (!$data)
                <p class="mt-4 text-gray-700">Tidak ada data status.</p>
            @else
                <div class="mt-6 rounded-lg border border-gray-200 bg-white p-4">
                    <div class="grid grid-cols-1 gap-2 text-sm">
                        @php($status = is_array($data) ? ($data['status'] ?? null) : null)
                        @php($message = is_array($data) ? ($data['message'] ?? null) : null)
                        @if($status)
                            <div><span class="font-medium">Status:</span> {{ $status }}</div>
                        @endif
                        @if($message)
                            <div><span class="font-medium">Message:</span> {{ $message }}</div>
                        @endif
                    </div>
                    <div class="mt-4">
                        <div class="text-sm text-gray-600 mb-1">Raw Response</div>
                        <pre class="text-xs bg-gray-100 p-3 rounded overflow-auto">{{ json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                    </div>
                </div>
            @endif

            <div class="mt-8">
                <a href="/" class="inline-block px-4 py-2 border border-black rounded-md">Kembali</a>
            </div>
        </main>
    </body>
</html>