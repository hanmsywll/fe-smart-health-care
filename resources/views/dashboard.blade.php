@extends('layouts.app')
@section('title', 'Dashboard - Smart Health Care')
@push('styles')
<style>
    .gradient-text {
        background: linear-gradient(135deg, #10b981 0%, #06b6d4 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
    .sidebar-transition { transition: all 0.3s ease-in-out; }
</style>
@endpush
@section('content')
    @include('partials.sidebar', ['active' => 'dashboard'])

    <!-- Main Content -->
    <div id="mainContent" class="sidebar-transition ml-64">
        <!-- Top Bar -->
        <header class="bg-white border-b border-gray-200 sticky top-0 z-30">
            <div class="flex items-center justify-between px-6 py-4">
                <div class="flex items-center gap-4">
                    <button onclick="toggleSidebar()" class="p-2 rounded-lg hover:bg-gray-100 transition">
                        <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>
                    <div>
                        <h1 class="text-xl font-bold text-gray-900">Dashboard</h1>
                        <p class="text-sm text-gray-500">Selamat datang kembali!</p>
                    </div>
                </div>

                <div class="flex items-center gap-4">
                    <!-- User Profile (Topbar) -->
                    @php
                        $sessionUser = session('user');
                        $user = $sessionUser ?: auth()->user();
                        $name = data_get($user, 'name')
                            ?? data_get($user, 'nama')
                            ?? data_get($user, 'full_name')
                            ?? data_get($user, 'email')
                            ?? 'Tamu';
                        $role = data_get($user, 'role')
                            ?? data_get($user, 'roles.0')
                            ?? data_get($user, 'roles.0.name')
                            ?? 'Pasien';
                        $initialsSource = $name;
                        if (str_contains($name, '@')) {
                            $initialsSource = explode('@', $name)[0];
                        }
                        $initials = collect(explode(' ', $initialsSource))
                            ->map(function($n){return mb_substr($n,0,1);})->join('');
                    @endphp
                    <div class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-gray-100 transition">
                        <div class="w-10 h-10 bg-gradient-to-br from-emerald-400 to-cyan-400 rounded-full flex items-center justify-center text-white font-semibold">
                            {{ $initials }}
                        </div>
                        <div class="min-w-0">
                            <div class="font-semibold text-gray-900 truncate">{{ $name }}</div>
                            <div class="text-sm text-gray-500 truncate">{{ $role }}</div>
                        </div>
                    </div>

                    <!-- Logout -->
                    <button onclick="logout()"
                        class="flex items-center gap-2 px-4 py-2 text-red-600 hover:bg-red-50 rounded-lg transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
                            </path>
                        </svg>
                        <span class="hidden md:inline">Logout</span>
                    </button>
                </div>
            </div>
        </header>

        <!-- Dashboard Content -->
        <main class="p-6">
            <!-- Toast Notification -->
            <div id="toast" class="fixed top-4 right-4 z-50 hidden">
                <div
                    class="flex items-start gap-3 bg-white border border-emerald-200 shadow-lg rounded-xl p-4 max-w-md">
                    <div class="w-8 h-8 rounded-lg bg-emerald-100 flex items-center justify-center">
                        <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="flex-1">
                        <div id="toastTitle" class="font-semibold text-gray-900">Yeay! Janji temu berhasil dibooking 🎉
                        </div>
                        <div id="toastDesc" class="text-sm text-gray-600">Dengan dokter X pada tanggal X dan jam X.
                        </div>
                    </div>
                    <button onclick="hideToast()" class="p-2 hover:bg-gray-100 rounded-lg">
                        <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 bg-emerald-100 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                </path>
                            </svg>
                        </div>
                        <span
                            class="text-xs text-emerald-600 bg-emerald-50 px-2 py-1 rounded-full font-semibold">+12%</span>
                    </div>
                    <div class="text-2xl font-bold text-gray-900 mb-1">12</div>
                    <div class="text-sm text-gray-500">Total Janji Temu</div>
                </div>

                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 bg-cyan-100 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <span class="text-xs text-cyan-600 bg-cyan-50 px-2 py-1 rounded-full font-semibold">Aktif</span>
                    </div>
                    <div class="text-2xl font-bold text-gray-900 mb-1">3</div>
                    <div class="text-sm text-gray-500">Janji Temu Aktif</div>
                </div>

                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                </path>
                            </svg>
                        </div>
                        <span
                            class="text-xs text-purple-600 bg-purple-50 px-2 py-1 rounded-full font-semibold">Baru</span>
                    </div>
                    <div class="text-2xl font-bold text-gray-900 mb-1">8</div>
                    <div class="text-sm text-gray-500">Rekam Medis</div>
                </div>

                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                                </path>
                            </svg>
                        </div>
                        <span
                            class="text-xs text-orange-600 bg-orange-50 px-2 py-1 rounded-full font-semibold">Aktif</span>
                    </div>
                    <div class="text-2xl font-bold text-gray-900 mb-1">5</div>
                    <div class="text-sm text-gray-500">Resep Obat</div>
                </div>
            </div>

            <div class="grid lg:grid-cols-3 gap-6 mb-6">
                <!-- Upcoming Appointments -->
                <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-gray-100">
                    <div class="p-6 border-b border-gray-100">
                        <div class="flex items-center justify-between gap-3">
                            <h2 class="text-lg font-bold text-gray-900">Janji Temu Mendatang</h2>
                            <div class="flex items-center gap-2">
                                <input id="searchTanggal" type="date" class="border border-gray-300 rounded-lg px-2 py-1 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-200" />
                                <input id="searchDokter" type="text" placeholder="Nama Dokter" class="border border-gray-300 rounded-lg px-2 py-1 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-200" />
                                <button onclick="cariJanji()" class="px-3 py-1 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-medium">Cari</button>
                            </div>
                        </div>
                    </div>
                    <div class="p-6">
                        <div id="upcomingList" class="space-y-4"></div>
                    </div>
                </div>

                <!-- System / Auth Status -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-lg font-bold text-gray-900">Status Sistem</h2>
                        <a href="/status" class="text-sm text-emerald-600 hover:text-emerald-700 font-medium">Detail</a>
                    </div>
                    <ul class="space-y-3 text-sm">
                        <li class="flex items-center justify-between">
                            <span class="text-gray-600">API Gateway</span>
                            <span class="px-2 py-1 rounded bg-amber-50 text-amber-700">menunggu</span>
                        </li>
                        <li class="flex items-center justify-between">
                            <span class="text-gray-600">Autentikasi</span>
                            @if(session('api_gateway_token'))
                                <span class="px-2 py-1 rounded bg-emerald-50 text-emerald-700">terautentikasi</span>
                            @else
                                <span class="px-2 py-1 rounded bg-red-50 text-red-700">belum masuk</span>
                            @endif
                        </li>
                        <li class="flex items-center justify-between">
                            <span class="text-gray-600">Notifikasi</span>
                            <span class="px-2 py-1 rounded bg-amber-50 text-amber-700">menunggu</span>
                        </li>
                    </ul>
                    <div class="mt-4">
                        <a href="/ketersediaan"
                            class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-emerald-600 text-white hover:bg-emerald-700">Buat
                            Janji</a>
                        <a href="/login"
                            class="inline-flex items-center gap-2 px-4 py-2 rounded-lg border border-gray-200 text-gray-700 hover:bg-gray-50 ml-2">Masuk</a>
                    </div>
                </div>
            </div>

            <!-- Footer spacing -->
            <div class="h-12"></div>
        </main>
    </div>
    @include('partials.dashboard.janji-detail-modal')

    @include('partials.dashboard.edit-janji-modal')

@endsection

@section('scripts')
    @include('partials.dashboard.scripts')
@endsection