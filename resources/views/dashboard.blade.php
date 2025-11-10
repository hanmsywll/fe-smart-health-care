<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Smart Health Care</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
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
        .gradient-text {
            background: linear-gradient(135deg, #10b981 0%, #06b6d4 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .sidebar-transition {
            transition: all 0.3s ease-in-out;
        }
    </style>
</head>

<body class="bg-gray-50">
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

=

            <!-- Footer spacing -->
            <div class="h-12"></div>
        </main>
    </div>

    <!-- Janji Detail Modal -->
    <div id="janjiModal" class="fixed inset-0 bg-black bg-opacity-40 hidden z-50">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-xl shadow-lg w-full max-w-xl">
                <div class="flex items-center justify-between p-4 border-b border-gray-100">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 bg-gradient-to-br from-emerald-400 to-cyan-400 rounded-full flex items-center justify-center">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                        <h3 id="janjiModalTitle" class="font-semibold text-gray-900">Detail Janji Temu</h3>
                    </div>
                    <div id="janjiModalStatus" class="px-2 py-1 rounded text-xs font-semibold bg-gray-100 text-gray-700">-</div>
                </div>
                <div class="p-4" id="janjiModalDetail">
                    <!-- populated dynamically -->
                </div>
                <div class="p-4 border-t border-gray-100 flex justify-end">
                    <button onclick="closeJanjiModal()" class="px-3 py-2 rounded-lg bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Janji Modal -->
    <div id="editJanjiModal" class="fixed inset-0 bg-black bg-opacity-40 hidden z-50">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-xl shadow-lg w-full max-w-xl">
                <div class="flex items-center justify-between p-4 border-b border-gray-100">
                    <h3 class="font-semibold text-gray-900">Edit Janji Temu</h3>
                    <button onclick="closeEditJanjiModal()" class="p-2 rounded-lg hover:bg-gray-100">
                        <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
                <div class="p-4 space-y-4">
                    <input type="hidden" id="editJanjiId" />
                    <div>
                        <label class="block text-sm text-gray-600 mb-1">Tanggal</label>
                        <input id="editTanggal" type="date" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-200" />
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-sm text-gray-600 mb-1">Waktu Mulai</label>
                            <input id="editMulai" type="time" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-200" />
                        </div>
                        <div>
                            <label class="block text-sm text-gray-600 mb-1">Waktu Selesai (opsional)</label>
                            <input id="editSelesai" type="time" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-200" />
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm text-gray-600 mb-1">Catatan (opsional)</label>
                        <textarea id="editCatatan" rows="3" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-200"></textarea>
                    </div>
                </div>
                <div class="flex items-center justify-end gap-2 p-4 border-t border-gray-100">
                    <button onclick="closeEditJanjiModal()" class="px-4 py-2 rounded-lg border border-gray-200 text-gray-700 hover:bg-gray-50">Batal</button>
                    <button onclick="submitEditJanji()" class="px-4 py-2 rounded-lg bg-emerald-600 text-white hover:bg-emerald-700">Simpan</button>
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

        function capitalize(str) {
            if (!str || typeof str !== 'string') return '';
            return str.charAt(0).toUpperCase() + str.slice(1);
        }

        function normalizeDateString(str) {
            if (!str) return '';
            // Prefer ISO date portion to avoid timezone shifts
            const s = String(str);
            if (s.length >= 10) return s.slice(0, 10);
            return s;
        }

        function statusBadgeClasses(status) {
            const s = (status || '').toLowerCase();
            switch (s) {
                case 'terjadwal':
                    return { bg: 'bg-emerald-100', text: 'text-emerald-700' };
                case 'selesai':
                    return { bg: 'bg-cyan-100', text: 'text-cyan-700' };
                case 'dibatalkan':
                    return { bg: 'bg-red-100', text: 'text-red-700' };
                default:
                    return { bg: 'bg-gray-100', text: 'text-gray-700' };
            }
        }

        function renderAppointments(items) {
            const container = document.getElementById('upcomingList');
            if (!container) return;
            container.innerHTML = '';
            if (!Array.isArray(items) || items.length === 0) {
                container.innerHTML = '<p class="text-gray-500 text-sm">Belum ada janji temu mendatang.</p>';
                return;
            }

            items.forEach((it) => {
                const doctorName = it?.dokter?.pengguna?.nama_lengkap || it?.dokter?.pengguna?.name || 'Dokter';
                const specialization = it?.dokter?.spesialisasi || 'Dokter';
                let dateStr = '';
                try {
                    dateStr = it?.tanggal_janji ? new Date(it.tanggal_janji).toLocaleDateString('id-ID', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' }) : '';
                } catch (_) { dateStr = it?.tanggal_janji || ''; }
                const timeStr = `${it?.waktu_mulai || ''}${it?.waktu_selesai ? ' - ' + it.waktu_selesai : ''}`.trim();
                const statusText = capitalize(it?.status || 'terjadwal');
                const { bg, text } = statusBadgeClasses(it?.status);
                const id = it?.id ?? it?.id_janji_temu ?? it?.id_janji ?? it?.kode ?? null;

                const html = `
                <div class="flex items-center gap-4 p-4 rounded-lg border border-gray-200 hover:border-emerald-300 transition cursor-pointer group"
                     ${id ? `onclick=\"openJanjiDetail('${id}')\"` : ''}>
                    <div class="w-12 h-12 bg-gradient-to-br from-emerald-400 to-cyan-400 rounded-full flex items-center justify-center flex-shrink-0">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <h3 class="font-semibold text-gray-900">${doctorName}</h3>
                        <p class="text-sm text-gray-500">${specialization}</p>
                        <div class="flex items-center gap-4 mt-2 text-sm">
                            <span class="flex items-center gap-1 text-gray-600">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                ${dateStr}
                            </span>
                            <span class="flex items-center gap-1 text-gray-600">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                ${timeStr ? timeStr + ' WIB' : ''}
                            </span>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="px-3 py-1 ${bg} ${text} rounded-full text-xs font-semibold">${statusText}</span>
                        ${id ? `
                        <button title="Edit" onclick=\"event.stopPropagation();openEditJanji('${id}','${normalizeDateString(it?.tanggal_janji)}','${it?.waktu_mulai || ''}','${it?.waktu_selesai || ''}')\" class="p-2 rounded-lg text-gray-500 hover:text-emerald-700 hover:bg-emerald-50">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 4h2a2 2 0 012 2v2m-4-4a2 2 0 00-2 2v2m6 0h2a2 2 0 012 2v2m-4-4a2 2 0 00-2 2v2m6 0h2a2 2 0 012 2v2m-4-4a2 2 0 00-2 2v2"/></svg>
                        </button>
                        <button title="Hapus" onclick=\"event.stopPropagation();deleteJanji('${id}')\" class="p-2 rounded-lg text-gray-500 hover:text-red-700 hover:bg-red-50">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 7h6m2 0h-2m-6 0H7m2-2h6a2 2 0 012 2v0H7v0a2 2 0 012-2z"/></svg>
                        </button>
                        ` : ''}
                    </div>
                </div>`;

                const temp = document.createElement('div');
                temp.innerHTML = html.trim();
                container.appendChild(temp.firstElementChild);
            });
        }

        async function cariJanji() {
            const token = localStorage.getItem('access_token');
            if (!token) {
                window.location.href = '/login?redirect=' + encodeURIComponent('/dashboard');
                return;
            }
            const tgl = document.getElementById('searchTanggal')?.value || '';
            const dokter = document.getElementById('searchDokter')?.value || '';

            if (!tgl && !dokter) {
                showToast('Isi pencarian', 'Masukkan tanggal atau nama dokter.');
                return;
            }

            const qs = new URLSearchParams();
            if (tgl) qs.set('tanggal', tgl);
            if (dokter) qs.set('nama_dokter', dokter);

            try {
                let res = await fetch('/janji/search?' + qs.toString(), {
                    headers: {
                        'Accept': 'application/json',
                        'Authorization': `Bearer ${token}`
                    }
                });
                if (res.status === 401) {
                    window.location.href = '/login?redirect=' + encodeURIComponent('/dashboard');
                    return;
                }
                if (!res.ok && res.status !== 404) {
                    throw new Error('Search failed');
                }
                if (res.status === 404) {
                    // Fallback: try base endpoint with same query
                    res = await fetch('/janji?' + qs.toString(), {
                        headers: {
                            'Accept': 'application/json',
                            'Authorization': `Bearer ${token}`
                        }
                    });
                    if (res.status === 401) {
                        window.location.href = '/login?redirect=' + encodeURIComponent('/dashboard');
                        return;
                    }
                }
                const body = await res.json().catch(() => ({}));
                const items = body?.data || [];

                // Client-side strict filtering to avoid server-side loose matches
                const qDate = tgl ? normalizeDateString(tgl) : '';
                const qName = dokter ? dokter.toLowerCase() : '';

                const filtered = items.filter(it => {
                    const itDate = normalizeDateString(it?.tanggal_janji);
                    const matchDate = qDate ? (itDate === qDate) : true;

                    const names = [
                        it?.dokter?.pengguna?.nama_lengkap,
                        it?.dokter?.pengguna?.name,
                        it?.dokter?.nama_dokter,
                        it?.dokter?.nama
                    ].filter(Boolean).map(s => String(s).toLowerCase());
                    const matchName = qName ? names.some(n => n.includes(qName)) : true;

                    return matchDate && matchName;
                });

                renderAppointments(filtered);
                const msg = body?.message || (items.length ? `Berhasil menemukan ${items.length} janji temu` : 'Tidak ada janji temu yang sesuai');
                showToast('Hasil Pencarian', msg);
            } catch (_) {
                showToast('Jaringan bermasalah', 'Gagal melakukan pencarian janji.');
            }
        }

        function populateJanjiModal(data) {
            const modal = document.getElementById('janjiModal');
            const titleEl = document.getElementById('janjiModalTitle');
            const detailEl = document.getElementById('janjiModalDetail');
            const statusEl = document.getElementById('janjiModalStatus');

            const doctorName = data?.dokter?.pengguna?.nama_lengkap || data?.dokter?.pengguna?.name || data?.dokter?.nama || 'Dokter';
            const specialization = data?.dokter?.spesialisasi || 'Dokter';
            const patientName = data?.pasien?.pengguna?.nama_lengkap || data?.pasien?.pengguna?.name || data?.pasien?.nama || '';
            let dateStr = '';
            try {
                dateStr = data?.tanggal_janji ? new Date(data.tanggal_janji).toLocaleDateString('id-ID', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' }) : '';
            } catch (_) { dateStr = data?.tanggal_janji || ''; }
            const timeStr = `${data?.waktu_mulai || ''}${data?.waktu_selesai ? ' - ' + data.waktu_selesai : ''}`.trim();

            titleEl.textContent = `Janji dengan ${doctorName}`;
            statusEl.textContent = capitalize(data?.status || '');
            statusEl.className = `px-2 py-1 rounded text-xs font-semibold ${statusBadgeClasses(data?.status).bg} ${statusBadgeClasses(data?.status).text}`;
            detailEl.innerHTML = `
                <div class="space-y-2 text-sm text-gray-700">
                    <div><span class="text-gray-500">Dokter:</span> <span class="font-medium">${doctorName}</span> <span class="text-gray-500">•</span> <span class="text-gray-500">${specialization}</span></div>
                    ${patientName ? `<div><span class='text-gray-500'>Pasien:</span> <span class='font-medium'>${patientName}</span></div>` : ''}
                    <div><span class="text-gray-500">Tanggal:</span> <span class="font-medium">${dateStr}</span></div>
                    <div><span class="text-gray-500">Waktu:</span> <span class="font-medium">${timeStr ? timeStr + ' WIB' : '-'}</span></div>
                    ${data?.catatan ? `<div><span class='text-gray-500'>Catatan:</span> <span class='font-medium'>${data.catatan}</span></div>` : ''}
                </div>
            `;

            modal.classList.remove('hidden');
        }

        function closeJanjiModal() {
            document.getElementById('janjiModal').classList.add('hidden');
        }

        function openEditJanji(id, tanggal, mulai, selesai) {
            const modal = document.getElementById('editJanjiModal');
            document.getElementById('editJanjiId').value = id || '';
            document.getElementById('editTanggal').value = normalizeDateString(tanggal || '');
            document.getElementById('editMulai').value = (mulai || '').slice(0, 5);
            document.getElementById('editSelesai').value = (selesai || '').slice(0, 5);
            modal.classList.remove('hidden');
        }

        function closeEditJanjiModal() {
            document.getElementById('editJanjiModal').classList.add('hidden');
        }

        async function submitEditJanji() {
            const token = localStorage.getItem('access_token');
            if (!token) {
                window.location.href = '/login?redirect=' + encodeURIComponent('/dashboard');
                return;
            }
            const csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            const id = document.getElementById('editJanjiId').value;
            const tanggal = document.getElementById('editTanggal').value;
            const mulai = document.getElementById('editMulai').value;
            const selesai = document.getElementById('editSelesai').value;
            const catatan = document.getElementById('editCatatan').value;

            if (!id) {
                showToast('Gagal', 'ID janji tidak valid.');
                return;
            }
            if (!tanggal || !mulai) {
                showToast('Data belum lengkap', 'Tanggal dan waktu mulai wajib diisi.');
                return;
            }

            const payload = {
                tanggal_janji: tanggal,
                waktu_mulai: mulai,
                ...(selesai ? { waktu_selesai: selesai } : {}),
                ...(catatan ? { catatan } : {}),
            };

            try {
                const res = await fetch(`/janji/${encodeURIComponent(id)}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'Authorization': `Bearer ${token}`,
                        'X-CSRF-TOKEN': csrf,
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify(payload)
                });

                if (res.status === 401) {
                    window.location.href = '/login?redirect=' + encodeURIComponent('/dashboard');
                    return;
                }

                const body = await res.json().catch(() => ({}));

                if (res.ok) {
                    showToast('Berhasil', body?.message || 'Jadwal janji temu berhasil diperbarui');
                    closeEditJanjiModal();
                    await refetchAppointments();
                } else if (res.status === 403) {
                    showToast('Akses ditolak', body?.message || 'Anda tidak memiliki izin untuk memperbarui janji temu ini');
                } else if (res.status === 404) {
                    showToast('Tidak ditemukan', body?.message || 'Janji temu yang Anda cari tidak ditemukan');
                } else if (res.status === 409) {
                    showToast('Bentrok jadwal', body?.message || 'Jadwal ini sudah terisi. Pilih waktu lain');
                } else if (res.status === 422) {
                    showToast('Data tidak valid', body?.message || 'Periksa kembali input anda');
                } else if (res.status === 400) {
                    showToast('Di luar jam kerja', body?.message || 'Jadwal berada di luar jam kerja dokter');
                } else {
                    showToast('Gagal', body?.message || 'Terjadi kesalahan saat memperbarui janji temu');
                }
            } catch (e) {
                showToast('Jaringan bermasalah', 'Gagal memperbarui janji. Coba lagi.');
            }
        }

        async function deleteJanji(id) {
            const token = localStorage.getItem('access_token');
            if (!token) {
                window.location.href = '/login?redirect=' + encodeURIComponent('/dashboard');
                return;
            }
            const csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            if (!id) return;
            const ok = window.confirm('Yakin ingin membatalkan janji ini?');
            if (!ok) return;
            try {
                const res = await fetch(`/janji/${encodeURIComponent(id)}`, {
                    method: 'DELETE',
                    headers: {
                        'Accept': 'application/json',
                        'Authorization': `Bearer ${token}`,
                        'X-CSRF-TOKEN': csrf,
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });
                if (res.status === 401) {
                    window.location.href = '/login?redirect=' + encodeURIComponent('/dashboard');
                    return;
                }
                const body = await res.json().catch(() => ({}));
                if (res.ok) {
                    showToast('Berhasil', body?.message || 'Janji temu berhasil dibatalkan');
                    await refetchAppointments();
                } else if (res.status === 403) {
                    showToast('Akses ditolak', body?.message || 'Maaf, Anda tidak memiliki izin untuk membatalkan janji ini');
                } else if (res.status === 404) {
                    showToast('Tidak ditemukan', body?.message || 'Janji temu tidak ditemukan atau sudah dibatalkan');
                } else {
                    showToast('Gagal', body?.message || 'Maaf, terjadi kesalahan saat membatalkan janji temu');
                }
            } catch (e) {
                showToast('Jaringan bermasalah', 'Gagal membatalkan janji. Coba lagi.');
            }
        }

        async function refetchAppointments() {
            const token = localStorage.getItem('access_token');
            if (!token) {
                window.location.href = '/login?redirect=' + encodeURIComponent('/dashboard');
                return;
            }
            const tgl = document.getElementById('searchTanggal')?.value || '';
            const dokter = document.getElementById('searchDokter')?.value || '';
            if (tgl || dokter) {
                await cariJanji();
                return;
            }
            try {
                const res = await fetch('/janji', {
                    headers: {
                        'Accept': 'application/json',
                        'Authorization': `Bearer ${token}`
                    }
                });
                if (res.status === 401) {
                    window.location.href = '/login?redirect=' + encodeURIComponent('/dashboard');
                    return;
                }
                const body = await res.json().catch(() => ({}));
                const items = body?.data || [];
                const upcoming = items.filter(it => (it?.status || '').toLowerCase() === 'terjadwal');
                renderAppointments(upcoming.length ? upcoming : items);
            } catch (_) {}
        }

        async function openJanjiDetail(id) {
            const token = localStorage.getItem('access_token');
            if (!token) {
                window.location.href = '/login?redirect=' + encodeURIComponent('/dashboard');
                return;
            }
            try {
                const res = await fetch(`/janji/${id}`, {
                    headers: {
                        'Accept': 'application/json',
                        'Authorization': `Bearer ${token}`
                    }
                });
                if (res.status === 401) {
                    window.location.href = '/login?redirect=' + encodeURIComponent('/dashboard');
                    return;
                }
                if (res.status === 403) {
                    showToast('Akses ditolak', 'Anda tidak memiliki akses ke janji ini.');
                    return;
                }
                if (res.status === 404) {
                    showToast('Tidak ditemukan', 'Janji temu tidak ditemukan atau sudah dihapus.');
                    return;
                }
                const body = await res.json().catch(() => ({}));
                const data = body?.data || null;
                if (!data) {
                    showToast('Gagal', body?.message || 'Tidak bisa mengambil detail janji.');
                    return;
                }
                populateJanjiModal(data);
            } catch (_) {
                showToast('Jaringan bermasalah', 'Gagal mengambil detail janji.');
            }
        }

        async function logout() {
            const csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            localStorage.removeItem('access_token');
            localStorage.removeItem('token_type');
            localStorage.removeItem('user');
            try {
                await fetch('/logout', {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrf
                    }
                });
            } catch (_) { }
            window.location.href = '/';
        }

        function showToast(title, desc) {
            const toast = document.getElementById('toast');
            document.getElementById('toastTitle').textContent = title;
            document.getElementById('toastDesc').textContent = desc;
            toast.classList.remove('hidden');
            setTimeout(() => hideToast(), 6000);
        }

        function hideToast() {
            document.getElementById('toast').classList.add('hidden');
        }

        async function initDashboard() {
            const token = localStorage.getItem('access_token');
            if (!token) {
                window.location.href = '/login?redirect=' + encodeURIComponent('/dashboard');
                return;
            }
            let pending = null;
            try { pending = JSON.parse(localStorage.getItem('pendingBooking')); } catch (_) { }
            if (!token) {
                window.location.href = '/login?redirect=' + encodeURIComponent('/dashboard');
                return;
            }
            if (pending && pending.bookingData) {
                try {
                    const csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                    const headers = {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrf,
                        'Authorization': `Bearer ${token}`
                    };
                    const res = await fetch('/janji/booking-cepat', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': csrf,
                            'Authorization': `Bearer ${token}`
                        },
                        body: JSON.stringify(pending.bookingData)
                    });
                    const body = await res.json().catch(() => ({}));

                    if (res.ok && (body?.success ?? true)) {
                        const d = body?.data || {};
                        const doctorName = pending?.doctor?.nama || 'dokter';
                        const dateStr = d?.tanggal_janji ? new Date(d.tanggal_janji).toLocaleDateString('id-ID', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' }) : pending.bookingData.tanggal;
                        const timeStr = d?.waktu_mulai ? `${d.waktu_mulai}${d?.waktu_selesai ? ' - ' + d.waktu_selesai : ''}` : pending.bookingData.waktu_mulai;
                        showToast('Yeay! Janji temu Anda berhasil dibooking 🎉', `Dengan ${doctorName} pada ${dateStr} pukul ${timeStr}.`);
                    } else if (res.status === 409) {
                        showToast('Slot penuh', 'Silakan pilih waktu lain.');
                    } else if (res.status === 400) {
                        showToast('Tidak tersedia', 'Dokter tidak tersedia pada jam ini.');
                    } else if (res.status === 422) {
                        showToast('Validasi gagal', body?.message || 'Periksa kembali data.');
                    } else if (res.status === 401) {
                        window.location.href = '/login?redirect=' + encodeURIComponent('/dashboard');
                        return;
                    } else {
                        showToast('Gagal', body?.message || 'Terjadi kesalahan saat membooking.');
                    }
                } catch (e) {
                    showToast('Jaringan bermasalah', 'Coba lagi nanti.');
                } finally {
                    try { localStorage.removeItem('pendingBooking'); } catch (_) { }
                }
            }

            try {
                const res = await fetch('/janji', {
                    headers: {
                        'Accept': 'application/json',
                        'Authorization': `Bearer ${token}`
                    }
                });

                if (res.status === 401) {
                    window.location.href = '/login?redirect=' + encodeURIComponent('/dashboard');
                    return;
                }
                const body = await res.json();
                const items = body?.data || [];
                const upcoming = items.filter(it => (it?.status || '').toLowerCase() === 'terjadwal');
                renderAppointments(upcoming.length ? upcoming : items);
            } catch (_) {
            }
        }

        document.addEventListener('DOMContentLoaded', initDashboard);
    </script>
</body>

</html>