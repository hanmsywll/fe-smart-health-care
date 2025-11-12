<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resep Obat - Smart Health Care</title>
    <meta name="csrf-token" content="csrf_token_placeholder">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#10b981',
                        secondary: '#06b6d4',
                    }
                }
            }
        }
    </script>
    <style>
        @keyframes float {

            0%,
            100% {
                transform: translateY(0px);
            }

            50% {
                transform: translateY(-20px);
            }
        }

        .float-animation {
            animation: float 3s ease-in-out infinite;
        }

        .gradient-text {
            background: linear-gradient(135deg, #10b981 0%, #06b6d4 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .slide-in {
            animation: slideIn 0.5s ease-out forwards;
        }
    </style>
</head>

<body class="bg-gray-50">
    <!-- Sidebar -->
    @include('partials.sidebar', ['active' => 'resep'])


    <!-- Main Content -->
    <div id="mainContent" class="ml-64 transition-all duration-300">
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
                        <h1 class="text-xl font-bold text-gray-900">Resep Obat</h1>
                        <p class="text-sm text-gray-500">Kelola dan lihat resep obat Anda</p>
                    </div>
                </div>
                <div class="flex items-center gap-4">

                    <div class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-gray-100 transition">
                        @php
                            $sessionUser = session('user');
                            $user = $sessionUser ?: auth()->user();
                            $name =
                                data_get($user, 'name') ??
                                (data_get($user, 'nama') ??
                                    (data_get($user, 'full_name') ?? (data_get($user, 'email') ?? 'Tamu')));
                            $role =
                                data_get($user, 'role') ??
                                (data_get($user, 'roles.0') ?? (data_get($user, 'roles.0.name') ?? 'Pasien'));
                            $initialsSource = $name;
                            if (str_contains($name, '@')) {
                                $initialsSource = explode('@', $name)[0];
                            }
                            $initials = collect(explode(' ', $initialsSource))
                                ->map(function ($n) {
                                    return mb_substr($n, 0, 1);
                                })
                                ->join('');
                        @endphp
                        <div
                            class="w-10 h-10 bg-gradient-to-br from-emerald-400 to-cyan-400 rounded-full flex items-center justify-center text-white font-semibold">
                            {{ $initials }}
                        </div>
                        <div class="min-w-0">
                            <div class="font-semibold text-gray-900 truncate">{{ $name }}</div>
                            <div class="text-sm text-gray-500 truncate">{{ $role }}</div>
                        </div>
                    </div>

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

        <main class="max-w-7xl mx-auto px-6 py-8">
            <!-- Filter & Stats -->
            <div class="grid md:grid-cols-3 gap-6 mb-8">
                <div class="bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-2xl shadow-lg p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-emerald-100 text-sm mb-1">Total Resep</p>
                            <h3 id="totalResep" class="text-3xl font-bold">0</h3>
                        </div>
                        <div class="w-14 h-14 bg-white/20 rounded-full flex items-center justify-center">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                </path>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-amber-500 to-amber-600 rounded-2xl shadow-lg p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-amber-100 text-sm mb-1">Menunggu</p>
                            <h3 id="menungguResep" class="text-3xl font-bold">0</h3>
                        </div>
                        <div class="w-14 h-14 bg-white/20 rounded-full flex items-center justify-center">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-cyan-500 to-cyan-600 rounded-2xl shadow-lg p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-cyan-100 text-sm mb-1">Diserahkan</p>
                            <h3 id="diserahkanResep" class="text-3xl font-bold">0</h3>
                        </div>
                        <div class="w-14 h-14 bg-white/20 rounded-full flex items-center justify-center">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filter Tabs -->
            <div class="bg-white rounded-2xl shadow-lg p-4 mb-6">
                <div class="flex flex-wrap items-center gap-4">
                    <div class="lg:flex block items-center gap-4 flex-1">
                        <div class="lg:flex block  gap-2 mb-2 lg:mb-0">
                            <button onclick="filterByStatus('semua')"
                                class="filter-btn-status px-6 py-2 rounded-lg font-semibold transition bg-emerald-500 text-white"
                                data-filter="semua">
                                Semua
                            </button>
                            <button onclick="filterByStatus('menunggu')"
                                class="filter-btn-status px-6 py-2 rounded-lg font-semibold transition bg-gray-100 text-gray-600 hover:bg-gray-200"
                                data-filter="menunggu">
                                Menunggu
                            </button>
                            <button onclick="filterByStatus('diserahkan')"
                                class="filter-btn-status px-6 py-2 rounded-lg font-semibold transition bg-gray-100 text-gray-600 hover:bg-gray-200"
                                data-filter="diserahkan">
                                Diserahkan
                            </button>
                            <button onclick="filterByStatus('dibatalkan')"
                                class="filter-btn-status px-6 py-2 rounded-lg font-semibold transition bg-gray-100 text-gray-600 hover:bg-gray-200"
                                data-filter="dibatalkan">
                                Dibatalkan
                            </button>
                        </div>
                        <div class="relative">
                            <input type="date" id="dateFilter" onchange="filterByDate()"
                                class="bg-gray-100 border-gray-300 rounded-lg py-2 px-4 focus:ring-emerald-500 focus:border-emerald-500">
                        </div>
                    </div>
                    @if (strtolower($role) === 'dokter')
                        <button onclick="openCreateModal()"
                            class="bg-gradient-to-r from-emerald-500 to-cyan-500 text-white px-4 py-2 rounded-lg font-semibold hover:shadow-lg transition transform hover:scale-105 flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            <span>Tambah Resep</span>
                        </button>
                    @endif
                </div>
            </div>

            <!-- Loading State -->
            <div id="loadingResep" class="text-center py-12">
                <div
                    class="inline-block animate-spin rounded-full h-12 w-12 border-4 border-emerald-500 border-t-transparent">
                </div>
                <p class="mt-4 text-gray-600">Memuat data resep obat...</p>
            </div>

            <!-- Resep Cards Container -->
            <div id="resepContainer" class="space-y-6 hidden"></div>

            <!-- Empty State -->
            <div id="emptyState" class="hidden text-center py-12">
                <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                        </path>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Tidak Ada Resep</h3>
                <p class="text-gray-500">Belum ada resep obat yang tersedia</p>
            </div>
        </main>
    </div>

    <!-- Create Modal -->
    <div id="createModal" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl max-w-3xl w-full max-h-[90vh] overflow-y-auto">
            <form id="createResepForm" onsubmit="handleCreateResep(event)">
                <div class="sticky top-0 bg-gradient-to-r from-emerald-500 to-cyan-500 text-white p-6 rounded-t-2xl">
                    <div class="flex items-center justify-between">
                        <h2 class="text-2xl font-bold">Tambah Resep Baru</h2>
                        <button type="button" onclick="closeCreateModal()"
                            class="p-2 hover:bg-white/20 rounded-lg transition">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                </div>

                <div class="p-6 space-y-6">
                    <!-- Informasi Resep -->
                    <div class="bg-gray-50 rounded-xl p-4">
                        <h3 class="font-bold text-gray-900 mb-3">Informasi Utama</h3>
                        <div class="grid md:grid-cols-2 gap-4">
                            <div>
                                <label for="id_rekam_medis" class="block text-sm font-medium text-gray-700 mb-1">Rekam
                                    Medis</label>
                                <select id="id_rekam_medis" name="id_rekam_medis" required
                                    class="w-full bg-white border border-gray-300 rounded-lg py-2 px-3 focus:ring-emerald-500 focus:border-emerald-500">
                                    <option value="">Memuat...</option>
                                </select>
                            </div>
                            <div>
                                <label for="tanggal_resep"
                                    class="block text-sm font-medium text-gray-700 mb-1">Tanggal Resep</label>
                                <input type="date" id="tanggal_resep" name="tanggal_resep" required
                                    class="w-full border border-gray-300 rounded-lg py-2 px-3 focus:ring-emerald-500 focus:border-emerald-500">
                            </div>
                        </div>
                    </div>

                    <!-- Daftar Obat -->
                    <div class="bg-white border-2 border-emerald-200 rounded-xl p-4">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="font-bold text-gray-900 flex items-center gap-2">
                                <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                                    </path>
                                </svg>
                                Detail Obat
                            </h3>
                            <button type="button" onclick="addObatField()"
                                class="bg-emerald-100 text-emerald-700 px-3 py-1 rounded-lg font-semibold text-sm hover:bg-emerald-200 transition">
                                + Tambah Obat
                            </button>
                        </div>
                        <div id="obatFieldsContainer" class="space-y-4">
                            <!-- Obat fields will be inserted here -->
                        </div>
                    </div>
                </div>

                <div class="sticky bottom-0 bg-gray-50 p-6 rounded-b-2xl border-t">
                    <div class="flex justify-end gap-4">
                        <button type="button" onclick="closeCreateModal()"
                            class="bg-gray-200 text-gray-700 px-6 py-3 rounded-lg font-semibold hover:bg-gray-300 transition">
                            Batal
                        </button>
                        <button type="submit" id="createResepSubmitBtn"
                            class="bg-gradient-to-r from-emerald-500 to-cyan-500 text-white px-6 py-3 rounded-lg font-semibold hover:shadow-lg transition transform hover:scale-105">
                            Simpan Resep
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Detail Modal -->
    <div id="detailModal" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl max-w-3xl w-full max-h-[90vh] overflow-y-auto">
            <div class="sticky top-0 bg-gradient-to-r from-emerald-500 to-cyan-500 text-white p-6 rounded-t-2xl">
                <div class="flex items-center justify-between">
                    <h2 class="text-2xl font-bold">Detail Resep Obat</h2>
                    <button onclick="closeModal()" class="p-2 hover:bg-white/20 rounded-lg transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>

            <div class="p-6">
                <!-- Informasi Resep -->
                <div class="bg-gray-50 rounded-xl p-4 mb-6">
                    <div class="grid md:grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-500 mb-1">ID Resep</p>
                            <p id="modalIdResep" class="font-semibold text-gray-900">-</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 mb-1">Tanggal Resep</p>
                            <p id="modalTanggalResep" class="font-semibold text-gray-900">-</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 mb-1">Status</p>
                            <span id="modalStatus"
                                class="inline-block px-3 py-1 rounded-full text-sm font-semibold">-</span>
                        </div>
                    </div>
                </div>

                <!-- Informasi Pasien & Dokter -->
                <div class="grid md:grid-cols-2 gap-4 mb-6">
                    <div class="bg-blue-50 rounded-xl p-4">
                        <h3 class="font-bold text-gray-900 mb-3 flex items-center gap-2">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            Pasien
                        </h3>
                        <p id="modalNamaPasien" class="font-semibold text-gray-900 mb-1">-</p>
                        <p id="modalEmailPasien" class="text-sm text-gray-600 mb-1">-</p>
                        <p id="modalTelpPasien" class="text-sm text-gray-600">-</p>
                    </div>

                    <div class="bg-emerald-50 rounded-xl p-4">
                        <h3 class="font-bold text-gray-900 mb-3 flex items-center gap-2">
                            <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z">
                                </path>
                            </svg>
                            Dokter
                        </h3>
                        <p id="modalNamaDokter" class="font-semibold text-gray-900 mb-1">-</p>
                        <p id="modalSpesialisasi" class="text-sm text-emerald-600 mb-1">-</p>
                        <p id="modalTelpDokter" class="text-sm text-gray-600">-</p>
                    </div>
                </div>

                <!-- Diagnosis & Tindakan -->
                <div class="bg-amber-50 rounded-xl p-4 mb-6">
                    <h3 class="font-bold text-gray-900 mb-3">Rekam Medis</h3>
                    <div class="space-y-2">
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Diagnosis:</p>
                            <p id="modalDiagnosis" class="text-gray-900">-</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Tindakan:</p>
                            <p id="modalTindakan" class="text-gray-900">-</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Catatan:</p>
                            <p id="modalCatatan" class="text-gray-900">-</p>
                        </div>
                    </div>
                </div>

                <!-- Daftar Obat -->
                <div class="bg-white border-2 border-emerald-200 rounded-xl p-4">
                    <h3 class="font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                            </path>
                        </svg>
                        Daftar Obat
                    </h3>
                    <div id="modalObatList" class="space-y-3">
                        <!-- Obat items will be inserted here -->
                    </div>
                </div>
            </div>

            <!-- Action Buttons for 'menunggu' status -->
            <div id="modalActionButtons" class="hidden p-6 border-t">
                <h3 class="font-bold text-gray-900 mb-3">Ubah Status</h3>
                <div class="flex gap-4">
                    <button onclick="updateResepStatus(currentDetailId, 'diserahkan')"
                        class="flex-1 bg-emerald-500 text-white py-3 rounded-lg font-semibold hover:bg-emerald-600 transition">
                        Tandai sebagai Diserahkan
                    </button>
                    <button onclick="updateResepStatus(currentDetailId, 'dibatalkan')"
                        class="flex-1 bg-red-500 text-white py-3 rounded-lg font-semibold hover:bg-red-600 transition">
                        Batalkan Resep
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function toggleSidebar() {

            const sidebar = document.getElementById('sidebar');

            const main = document.getElementById('mainContent');

            const isCollapsed = sidebar.style.marginLeft === '-16rem';

            sidebar.style.marginLeft = isCollapsed ? '0' : '-16rem';

            main.style.marginLeft = isCollapsed ? '16rem' : '0';

        }



        async function logout() {

            localStorage.removeItem('access_token');

            localStorage.removeItem('token_type');

            localStorage.removeItem('user');

            window.location.href = '/';

        }



        let resepData = [];

        let currentStatusFilter = 'semua';

        let currentDateFilter = '';

        let obatOptions = []; // Cache for obat dropdown

        let obatFieldIndex = 0;

        let currentDetailId = null; // To hold the id for the modal actions



        // Load initial data on page load

        document.addEventListener('DOMContentLoaded', function() {

            loadResepData();

            loadDropdownData();

        });



                        // Load resep data from API



                        async function loadResepData(status = '', tanggal = '') {



                            console.log('Starting to load resep data...');



                            const loadingEl = document.getElementById('loadingResep');



                            const containerEl = document.getElementById('resepContainer');



                            const emptyEl = document.getElementById('emptyState');



                



                            loadingEl.classList.remove('hidden');



                            containerEl.classList.add('hidden');



                            emptyEl.classList.add('hidden');



                



                            try {



                                const token = localStorage.getItem('access_token');



                                console.log('Using token:', token); // Log 1: Token



                



                                let url = 'http://127.0.0.1:8000/api/resep?';



                                const params = new URLSearchParams();



                                if (status && status !== 'semua') {



                                    params.append('status', status);



                                }



                                if (tanggal) {



                                    params.append('tanggal', tanggal);



                                }



                                url += params.toString();



                                console.log('Fetching URL:', url); // Log 2: URL



                



                                const response = await fetch(url, {



                                    headers: {



                                        'Accept': 'application/json',



                                        ...(token ? { 'Authorization': `Bearer ${token}` } : {})



                                    }



                                });



                                



                                console.log('API Response:', response); // Log 3: Raw Response



                



                                if (!response.ok) {



                                    throw new Error(`Failed to fetch resep data. Status: ${response.status}`);



                                }



                



                                const data = await response.json();



                                console.log('Parsed Data:', data); // Log 4: Parsed Data



                



                                // Handle both direct array and paginated response



                                if (data && typeof data === 'object' && Array.isArray(data.data)) {



                                    resepData = data.data;



                                } else if (Array.isArray(data)) {



                                    resepData = data;



                                } else {



                                    resepData = [];



                                }



                                



                                console.log('Final resepData array:', resepData); // Log 5: Final Array



                



                                loadingEl.classList.add('hidden');



                



                                if (resepData.length === 0) {



                                    console.log('No recipes found, showing empty state.');



                                    emptyEl.classList.remove('hidden');



                                } else {



                                    console.log(`${resepData.length} recipes found, rendering cards.`);



                                    containerEl.classList.remove('hidden');



                                    if (!status && !tanggal) {



                                        updateStats();



                                    }



                                    renderResepCards();



                                }



                            } catch (error) {



                                console.error('Error in loadResepData:', error); // Log 6: Error



                                loadingEl.innerHTML = `<div class="text-center py-12"><p class="text-red-600 mb-4">Gagal memuat data resep obat: ${error.message}</p><button onclick="loadResepData()" class="bg-emerald-600 text-white px-6 py-2 rounded-full hover:bg-emerald-700 transition">Coba Lagi</button></div>`;



                            }



                        }



        // Load data for dropdowns in create modal

        async function loadDropdownData() {

            const token = localStorage.getItem('access_token');

            const headers = {
                'Accept': 'application/json',
                ...(token ? {
                    'Authorization': `Bearer ${token}`
                } : {})
            };



            try {

                // Fetch Rekam Medis

                const rekamMedisResponse = await fetch('http://127.0.0.1:8000/api/rekam-medis', {
                    headers
                });

                if (!rekamMedisResponse.ok) throw new Error('Failed to fetch rekam medis');

                const rekamMedisData = await rekamMedisResponse.json();

                const rekamMedisSelect = document.getElementById('id_rekam_medis');

                rekamMedisSelect.innerHTML = '<option value="">Pilih Rekam Medis</option>';

                rekamMedisData.forEach(rm => {

                    const displayText =
                        `ID: ${rm.id_rekam_medis} - ${rm.pasien.pengguna.nama_lengkap} (Dr. ${rm.dokter.pengguna.nama_lengkap})`;

                    rekamMedisSelect.innerHTML +=
                        `<option value="${rm.id_rekam_medis}">${displayText}</option>`;

                });



                // Fetch Obat

                const obatResponse = await fetch('http://127.0.0.1:8000/api/obat', {
                    headers
                });

                if (!obatResponse.ok) throw new Error('Failed to fetch obat');

                obatOptions = await obatResponse.json();



            } catch (error) {

                console.error('Error loading dropdown data:', error);

                document.getElementById('id_rekam_medis').innerHTML = '<option value="">Gagal memuat data</option>';

            }

        }



        // Update statistics

        function updateStats() {

            document.getElementById('totalResep').textContent = resepData.length;

            document.getElementById('menungguResep').textContent = resepData.filter(r => r.status === 'menunggu').length;

            document.getElementById('diserahkanResep').textContent = resepData.filter(r => r.status === 'diserahkan')
                .length;

        }



        // Render resep cards

        function renderResepCards() {

            const container = document.getElementById('resepContainer');

            if (resepData.length === 0) {

                document.getElementById('emptyState').classList.remove('hidden');

                container.classList.add('hidden');

                return;

            }

            document.getElementById('emptyState').classList.add('hidden');

            container.classList.remove('hidden');

            container.innerHTML = resepData.map((resep, index) => {

                const tanggal = new Date(resep.tanggal_resep).toLocaleDateString('id-ID', {
                    weekday: 'long',
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric'
                });

                const statusColors = {
                    'menunggu': 'bg-amber-100 text-amber-700',
                    'diserahkan': 'bg-emerald-100 text-emerald-700',
                    'dibatalkan': 'bg-red-100 text-red-700'
                };

                const statusIcons = {
                    'menunggu': '⏳',
                    'diserahkan': '✅',
                    'dibatalkan': '❌'
                };

                const dokterNama = resep.rekam_medis?.dokter?.pengguna?.nama_lengkap || '-';

                const spesialisasi = resep.rekam_medis?.dokter?.spesialisasi || '-';

                const jumlahObat = resep.obat?.length || 0;

                return `<div class="bg-white rounded-2xl shadow-lg hover:shadow-xl transition p-6 border border-gray-100 slide-in" style="animation-delay: ${index * 0.1}s">

                                <div class="flex items-start justify-between mb-4">

                                    <div class="flex-1">

                                        <div class="flex items-center gap-3 mb-2">

                                            <h3 class="text-xl font-bold text-gray-900">Resep #${resep.id_resep}</h3>

                                            <span class="${statusColors[resep.status] || 'bg-gray-100 text-gray-700'} px-3 py-1 rounded-full text-xs font-semibold">${statusIcons[resep.status] || ''} ${resep.status.charAt(0).toUpperCase() + resep.status.slice(1)}</span>

                                        </div>

                                        <p class="text-gray-600 text-sm mb-1">📅 ${tanggal}</p>

                                        <p class="text-emerald-600 font-medium">👨‍⚕️ ${dokterNama}</p>

                                        <p class="text-gray-500 text-sm">${spesialisasi}</p>

                                    </div>

                                    <div class="text-right">

                                        <div class="w-16 h-16 bg-gradient-to-br from-emerald-400 to-cyan-400 rounded-full flex items-center justify-center text-white mb-2"><svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg></div>

                                        <p class="text-sm text-gray-600">${jumlahObat} Obat</p>

                                    </div>

                                </div>

                                <div class="bg-gray-50 rounded-lg p-3 mb-4">

                                    <p class="text-sm text-gray-600 mb-1">Diagnosis:</p>

                                    <p class="text-gray-900 font-medium">${resep.rekam_medis?.diagnosis || '-'}</p>

                                </div>

                                <div class="flex gap-2 mt-4">

                                    <button onclick="viewDetail(${resep.id_resep})" class="flex-1 bg-gradient-to-r from-emerald-500 to-cyan-500 text-white py-3 rounded-xl font-semibold hover:shadow-lg transition transform hover:scale-105">

                                        Lihat Detail Lengkap

                                    </button>

                                    <button onclick="deleteResep(${resep.id_resep})" class="bg-red-500 text-white p-3 rounded-xl font-semibold hover:bg-red-600 transition transform hover:scale-105">

                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>

                                    </button>

                                </div>

                            </div>`;

            }).join('');

        }



        // Filter controls

        function filterByStatus(status) {

            currentStatusFilter = status;

            document.querySelectorAll('.filter-btn-status').forEach(btn => {

                btn.className = btn.dataset.filter === status ?
                    'filter-btn-status px-6 py-2 rounded-lg font-semibold transition bg-emerald-500 text-white' :
                    'filter-btn-status px-6 py-2 rounded-lg font-semibold transition bg-gray-100 text-gray-600 hover:bg-gray-200';

            });

            loadResepData(currentStatusFilter, currentDateFilter);

        }

        function filterByDate() {

            currentDateFilter = document.getElementById('dateFilter').value;

            loadResepData(currentStatusFilter, currentDateFilter);

        }



        // Create Modal functions

        function openCreateModal() {

            document.getElementById('createModal').classList.remove('hidden');

            document.body.style.overflow = 'hidden';

            if (document.getElementById('obatFieldsContainer').innerHTML === '') {

                addObatField(); // Add one field by default if empty

            }

        }

        function closeCreateModal() {

            const modal = document.getElementById('createModal');

            modal.classList.add('hidden');

            document.getElementById('createResepForm').reset();

            document.getElementById('obatFieldsContainer').innerHTML = '';

            obatFieldIndex = 0;

            document.body.style.overflow = 'auto';

        }

        function addObatField() {

            const container = document.getElementById('obatFieldsContainer');

            const newField = document.createElement('div');

            newField.className = 'p-4 bg-gray-50 rounded-lg border space-y-2 relative';

            newField.id = `obat_field_${obatFieldIndex}`;

            let optionsHtml = '<option value="">Pilih Obat</option>';

            obatOptions.forEach(obat => {
                optionsHtml += `<option value="${obat.id_obat}">${obat.nama_obat} (${obat.kategori})</option>`;
            });

            newField.innerHTML =
                `<button type="button" onclick="removeObatField(${obatFieldIndex})" class="absolute top-2 right-2 text-red-500 hover:text-red-700"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg></button>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                            <div><label class="block text-sm font-medium text-gray-700 mb-1">Obat</label><select name="id_obat" required class="w-full bg-white border border-gray-300 rounded-lg py-2 px-3">${optionsHtml}</select></div>

                            <div><label class="block text-sm font-medium text-gray-700 mb-1">Jumlah</label><input type="number" name="jumlah" required placeholder="e.g., 10" class="w-full border border-gray-300 rounded-lg py-2 px-3"></div>

                        </div>

                        <div><label class="block text-sm font-medium text-gray-700 mb-1">Dosis</label><input type="text" name="dosis" required placeholder="e.g., 3x1 sehari" class="w-full border border-gray-300 rounded-lg py-2 px-3"></div>

                        <div><label class="block text-sm font-medium text-gray-700 mb-1">Instruksi</label><input type="text" name="instruksi" required placeholder="e.g., Setelah makan" class="w-full border border-gray-300 rounded-lg py-2 px-3"></div>`;

            container.appendChild(newField);

            obatFieldIndex++;

        }

        function removeObatField(index) {

            document.getElementById(`obat_field_${index}`)?.remove();

        }

        async function handleCreateResep(event) {

            event.preventDefault();

            const submitBtn = document.getElementById('createResepSubmitBtn');

            submitBtn.disabled = true;

            submitBtn.textContent = 'Menyimpan...';



            const details = Array.from(document.querySelectorAll('#obatFieldsContainer > div')).map(field => ({

                id_obat: field.querySelector('[name="id_obat"]').value,

                jumlah: field.querySelector('[name="jumlah"]').value,

                dosis: field.querySelector('[name="dosis"]').value,

                instruksi: field.querySelector('[name="instruksi"]').value,

            }));



            const requestBody = {

                id_rekam_medis: document.getElementById('id_rekam_medis').value,

                tanggal_resep: document.getElementById('tanggal_resep').value,

                status: 'menunggu',

                details: details

            };



            try {

                const token = localStorage.getItem('access_token');

                const response = await fetch('http://127.0.0.1:8000/api/resep', {

                    method: 'POST',

                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        ...(token ? {
                            'Authorization': `Bearer ${token}`
                        } : {})
                    },

                    body: JSON.stringify(requestBody)

                });

                if (!response.ok) {
                    const errorData = await response.json();
                    throw new Error(errorData.message || 'Gagal menyimpan resep');
                }

                closeCreateModal();

                loadResepData();

                alert('Resep berhasil ditambahkan!');

            } catch (error) {

                console.error('Error creating resep:', error);

                alert(`Error: ${error.message}`);

            } finally {

                submitBtn.disabled = false;

                submitBtn.textContent = 'Simpan Resep';

            }

        }



        async function deleteResep(idResep) {

            if (!confirm('Apakah Anda yakin ingin menghapus resep ini?')) {

                return;

            }



            try {

                const token = localStorage.getItem('access_token');

                const response = await fetch(`http://127.0.0.1:8000/api/resep/${idResep}`, {

                    method: 'DELETE',

                    headers: {

                        'Accept': 'application/json',

                        ...(token ? {
                            'Authorization': `Bearer ${token}`
                        } : {})

                    }

                });



                if (!response.ok) {

                    const errorData = await response.json();

                    throw new Error(errorData.message || 'Gagal menghapus resep');

                }



                alert('Resep berhasil dihapus!');

                loadResepData(currentStatusFilter, currentDateFilter); // Refresh the list



            } catch (error) {

                console.error('Error deleting resep:', error);

                alert(`Error: ${error.message}`);

            }

        }



        async function updateResepStatus(idResep, newStatus) {

            const capitalizedStatus = newStatus.charAt(0).toUpperCase() + newStatus.slice(1);

            if (!confirm(`Apakah Anda yakin ingin mengubah status resep menjadi "${capitalizedStatus}"?`)) {

                return;

            }



            try {

                const token = localStorage.getItem('access_token');

                const response = await fetch(`http://127.0.0.1:8000/api/resep/${idResep}`, {

                    method: 'PUT',

                    headers: {

                        'Content-Type': 'application/json',

                        'Accept': 'application/json',

                        ...(token ? {
                            'Authorization': `Bearer ${token}`
                        } : {})

                    },

                    body: JSON.stringify({
                        status: newStatus
                    })

                });



                if (!response.ok) {

                    const errorData = await response.json();

                    throw new Error(errorData.message || 'Gagal mengubah status resep');

                }



                alert('Status resep berhasil diubah!');

                closeModal(); // Close the detail modal

                loadResepData(currentStatusFilter, currentDateFilter); // Refresh the list



            } catch (error) {

                console.error('Error updating resep status:', error);

                alert(`Error: ${error.message}`);

            }

        }



        // Detail Modal functions

        function viewDetail(idResep) {

            currentDetailId = idResep; // Set the current ID

            const resep = resepData.find(r => r.id_resep === idResep);

            if (!resep) return;



            const modal = document.getElementById('detailModal');

            modal.classList.remove('hidden');



            document.getElementById('modalIdResep').textContent = `#${resep.id_resep}`;

            document.getElementById('modalTanggalResep').textContent = new Date(resep.tanggal_resep).toLocaleDateString(
                'id-ID', {
                    weekday: 'long',
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric',
                    hour: '2-digit',
                    minute: '2-digit'
                });

            const statusColors = {
                'menunggu': 'bg-amber-100 text-amber-700',
                'diserahkan': 'bg-emerald-100 text-emerald-700',
                'dibatalkan': 'bg-red-100 text-red-700'
            };

            const statusEl = document.getElementById('modalStatus');

            statusEl.textContent = resep.status.charAt(0).toUpperCase() + resep.status.slice(1);

            statusEl.className =
                `inline-block px-3 py-1 rounded-full text-sm font-semibold ${statusColors[resep.status] || 'bg-gray-100 text-gray-700'}`;

            const pasien = resep.rekam_medis?.pasien?.pengguna;

            document.getElementById('modalNamaPasien').textContent = pasien?.nama_lengkap || '-';

            document.getElementById('modalEmailPasien').textContent = pasien?.email || '-';

            document.getElementById('modalTelpPasien').textContent = pasien?.no_telepon || '-';

            const dokter = resep.rekam_medis?.dokter;

            document.getElementById('modalNamaDokter').textContent = dokter?.pengguna?.nama_lengkap || '-';

            document.getElementById('modalSpesialisasi').textContent = dokter?.spesialisasi || '-';

            document.getElementById('modalTelpDokter').textContent = dokter?.pengguna?.no_telepon || '-';

            document.getElementById('modalDiagnosis').textContent = resep.rekam_medis?.diagnosis || '-';

            document.getElementById('modalTindakan').textContent = resep.rekam_medis?.tindakan || '-';

            document.getElementById('modalCatatan').textContent = resep.rekam_medis?.catatan || '-';

            const obatListEl = document.getElementById('modalObatList');

            if (resep.obat && resep.obat.length > 0) {

                obatListEl.innerHTML = resep.obat.map((obat, index) => {

                    const harga = parseFloat(obat.harga || 0);

                    const jumlah = parseInt(obat.pivot?.jumlah || 0);

                    const subtotal = harga * jumlah;

                    return `<div class="bg-gradient-to-r from-emerald-50 to-cyan-50 rounded-lg p-4 border-l-4 border-emerald-500">

                                    <div class="flex items-start justify-between mb-2">

                                        <div class="flex-1"><h4 class="font-bold text-gray-900 text-lg mb-1">${index + 1}. ${obat.nama_obat}</h4><p class="text-sm text-emerald-600 mb-2">📦 ${obat.kategori}</p></div>

                                        <div class="text-right"><p class="text-sm text-gray-600">Jumlah</p><p class="text-xl font-bold text-emerald-600">${jumlah}x</p></div>

                                    </div>

                                    <div class="grid md:grid-cols-2 gap-3 mb-3">

                                        <div class="bg-white rounded-lg p-3"><p class="text-xs text-gray-600 mb-1">💊 Dosis</p><p class="font-semibold text-gray-900">${obat.pivot?.dosis || '-'}</p></div>

                                        <div class="bg-white rounded-lg p-3"><p class="text-xs text-gray-600 mb-1">📋 Instruksi</p><p class="font-semibold text-gray-900">${obat.pivot?.instruksi || '-'}</p></div>

                                    </div>

                                    <div class="flex justify-between items-center pt-3 border-t border-emerald-200">

                                        <div><p class="text-sm text-gray-600">Harga Satuan</p><p class="font-semibold text-gray-900">Rp ${harga.toLocaleString('id-ID')}</p></div>

                                        <div class="text-right"><p class="text-sm text-gray-600">Subtotal</p><p class="text-lg font-bold text-emerald-600">Rp ${subtotal.toLocaleString('id-ID')}</p></div>

                                    </div>

                                </div>`;

                }).join('');

                const total = resep.obat.reduce((sum, obat) => sum + (parseFloat(obat.harga || 0) * parseInt(obat.pivot
                    ?.jumlah || 0)), 0);

                obatListEl.innerHTML += `<div class="bg-gradient-to-r from-emerald-500 to-cyan-500 text-white rounded-lg p-4 mt-4">

                                <div class="flex justify-between items-center">

                                    <div><p class="text-emerald-100 text-sm mb-1">Total Biaya Obat</p><p class="text-3xl font-bold">Rp ${total.toLocaleString('id-ID')}</p></div>

                                    <div class="w-14 h-14 bg-white/20 rounded-full flex items-center justify-center"><svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg></div>

                                </div>

                            </div>`;

            } else {

                obatListEl.innerHTML = '<p class="text-center text-gray-500 py-4">Tidak ada obat dalam resep ini</p>';

            }



            const actionButtons = document.getElementById('modalActionButtons');

            if (resep.status === 'menunggu') {

                actionButtons.classList.remove('hidden');

            } else {

                actionButtons.classList.add('hidden');

            }



            document.body.style.overflow = 'hidden';

        }

        function closeModal() {

            document.getElementById('detailModal').classList.add('hidden');

            document.body.style.overflow = 'auto';

        }

        document.getElementById('detailModal').addEventListener('click', function(e) {
            if (e.target === this) closeModal();
        });

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') closeModal();
        });
    </script>
</body>

</html>
