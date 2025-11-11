<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cek Jadwal - Smart Health Care</title>
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
    </style>
</head>

<body class="bg-gray-50">
    



    <!-- Main Content -->
    <!-- Sidebar & Topbar (dashboard layout) -->
    @include('partials.sidebar', ['active' => 'ketersediaan'])

    <div id="mainContent" class="sidebar-transition ml-64">
        <header class="bg-white border-b border-gray-200 sticky top-0 z-30">
            <div class="flex items-center justify-between px-6 py-4">
                <div class="flex items-center gap-4">
                    <button onclick="toggleSidebar()" class="p-2 rounded-lg hover:bg-gray-100 transition">
                        <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>
                    <div>
                        <h1 class="text-xl font-bold text-gray-900">Janji Temu</h1>
                        <p class="text-sm text-gray-500">Cek jadwal dokter dan lakukan booking</p>
                    </div>
                </div>
                <div class="flex items-center gap-4">
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

                    <button onclick="logout()" class="flex items-center gap-2 px-4 py-2 text-red-600 hover:bg-red-50 rounded-lg transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                        </svg>
                        <span class="hidden md:inline">Logout</span>
                    </button>
                </div>
            </div>
        </header>
    <main class="max-w-7xl mx-auto px-6 py-12">
        

        <!-- Loading State -->
        <div id="loadingSchedule" class="text-center py-12">
            <div
                class="inline-block animate-spin rounded-full h-12 w-12 border-4 border-emerald-500 border-t-transparent">
            </div>
            <p class="mt-4 text-gray-600">Memuat data jadwal dokter...</p>
        </div>

        <!-- Doctor Cards Container -->
        <div id="scheduleContainer" class="grid md:grid-cols-2 lg:grid-cols-3 gap-6 hidden"></div>

        <!-- Doctor Detail Modal -->
        <div id="doctorDetailModal" class="hidden">
            <div class="bg-gradient-to-br from-emerald-50 to-cyan-50 rounded-2xl p-6 mb-6">
                <div class="flex items-center gap-4 mb-4">
                    <div
                        class="w-20 h-20 bg-gradient-to-br from-emerald-400 to-cyan-400 rounded-full flex items-center justify-center">
                        <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                    <div>
                        <h2 id="modalDoctorName" class="text-2xl font-bold text-gray-900"></h2>
                        <p id="modalDoctorSpec" class="text-emerald-600 font-medium text-lg"></p>
                        <p id="modalDoctorFee" class="text-gray-600"></p>
                    </div>
                </div>
                <span id="modalDoctorShift" class="inline-block px-4 py-2 rounded-full text-sm font-semibold"></span>
            </div>

            <div class="bg-white rounded-2xl shadow-lg p-6">
                <h3 class="text-xl font-bold text-gray-900 mb-4">Pilih Tanggal</h3>
                <div id="dateContainer" class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-6"></div>

                <div id="timeSlotContainer" class="hidden">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Pilih Waktu</h3>
                    <div id="timeSlotGrid" class="grid grid-cols-3 md:grid-cols-6 gap-3 mb-6"></div>
                </div>

                <div id="bookingFormContainer" class="hidden mt-6 pt-6 border-t border-gray-200">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Informasi Booking</h3>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Keluhan / Catatan</label>
                            <textarea id="keluhan" rows="3"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
                                placeholder="Jelaskan keluhan Anda (opsional)"></textarea>
                        </div>
                        <div class="bg-emerald-50 rounded-lg p-4">
                            <h4 class="font-semibold text-gray-900 mb-2">Ringkasan Booking</h4>
                            <div class="space-y-1 text-sm">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Dokter:</span>
                                    <span id="summaryDoctor" class="font-medium"></span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Tanggal:</span>
                                    <span id="summaryDate" class="font-medium">-</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Waktu:</span>
                                    <span id="summaryTime" class="font-medium">-</span>
                                </div>
                                <div class="flex justify-between pt-2 border-t border-emerald-200">
                                    <span class="text-gray-600">Biaya:</span>
                                    <span id="summaryFee" class="font-bold text-emerald-600"></span>
                                </div>
                            </div>
                        </div>
                        <button onclick="confirmBooking()"
                            class="w-full bg-gradient-to-r from-emerald-500 to-cyan-500 text-white py-4 rounded-xl font-semibold hover:shadow-xl transition transform hover:scale-105">
                            Konfirmasi Booking
                        </button>
                    </div>
                </div>
            </div>

            <div class="mt-6">
                <button onclick="backToList()"
                    class="flex items-center gap-2 text-gray-600 hover:text-emerald-600 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Kembali ke Daftar Dokter
                </button>
            </div>
        </div>

        <!-- Success Modal -->
        <div id="successModal" class="hidden">
            <div class="text-center py-12">
                <div class="w-24 h-24 bg-emerald-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="w-12 h-12 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h2 class="text-3xl font-bold text-gray-900 mb-4">Booking Berhasil!</h2>
                <div class="bg-white rounded-2xl shadow-lg p-6 max-w-md mx-auto mb-6">
                    <div class="space-y-3 text-left">
                        <div class="flex justify-between border-b pb-2">
                            <span class="text-gray-600">Dokter:</span>
                            <span id="successDoctor" class="font-semibold"></span>
                        </div>
                        <div class="flex justify-between border-b pb-2">
                            <span class="text-gray-600">Spesialisasi:</span>
                            <span id="successSpec" class="font-semibold"></span>
                        </div>
                        <div class="flex justify-between border-b pb-2">
                            <span class="text-gray-600">Tanggal:</span>
                            <span id="successDate" class="font-semibold"></span>
                        </div>
                        <div class="flex justify-between border-b pb-2">
                            <span class="text-gray-600">Waktu:</span>
                            <span id="successTime" class="font-semibold"></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Biaya:</span>
                            <span id="successFee" class="font-bold text-emerald-600"></span>
                        </div>
                    </div>
                </div>
                <p class="text-gray-600 mb-6">Silakan datang 15 menit sebelum jadwal konsultasi. Simpan bukti booking
                    ini.</p>
                <div class="flex gap-3 justify-center">
                    <a href="/dashboard"
                        class="bg-white text-gray-700 px-8 py-3 rounded-full font-semibold border-2 border-gray-200 hover:border-emerald-500 transition inline-block">
                        Lihat Dashboard
                    </a>
                </div>
            </div>
        </div>
    </main>
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
            } catch (_) {}
            window.location.href = '/';
        }

        // State management
        let doctorsData = [];
        let selectedDoctor = null;
        let selectedDate = null;
        let selectedTime = null;

        // Load doctor schedule on page load
        document.addEventListener('DOMContentLoaded', function () {
            loadDoctorSchedule();
        });

        // Load doctor schedule from API
        async function loadDoctorSchedule() {
            const loadingEl = document.getElementById('loadingSchedule');
            const scheduleContainer = document.getElementById('scheduleContainer');

            loadingEl.classList.remove('hidden');
            scheduleContainer.classList.add('hidden');

            try {
                const token = localStorage.getItem('access_token');
                const response = await fetch("https://smart-healthcare-system-production-6e7c.up.railway.app/api/janji/ketersediaan", {
                    headers: {
                        'Accept': 'application/json',
                        ...(token ? { 'Authorization': `Bearer ${token}` } : {})
                    }
                });

                if (!response.ok) {
                    throw new Error('Failed to fetch data');
                }

                const data = await response.json();
                doctorsData = data;

                loadingEl.classList.add('hidden');
                scheduleContainer.classList.remove('hidden');
                renderDoctorCards(data);
            } catch (error) {
                console.error('Error:', error);
                loadingEl.innerHTML = `
                    <div class="text-center py-12">
                        <p class="text-red-600 mb-4">Gagal memuat data jadwal dokter</p>
                        <button onclick="loadDoctorSchedule()" class="bg-emerald-600 text-white px-6 py-2 rounded-full hover:bg-emerald-700 transition">
                            Coba Lagi
                        </button>
                    </div>
                `;
            }
        }

        // Render doctor cards
        function renderDoctorCards(doctors) {
            const container = document.getElementById('scheduleContainer');
            container.innerHTML = doctors.map(doctor => `
                <div class="bg-white rounded-2xl shadow-lg hover:shadow-xl transition p-6 border border-gray-100">
                    <div class="flex items-start gap-4 mb-4">
                        <div class="w-16 h-16 bg-gradient-to-br from-emerald-400 to-cyan-400 rounded-full flex items-center justify-center flex-shrink-0">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-xl font-bold text-gray-900">${doctor.nama_dokter}</h3>
                            <p class="text-emerald-600 font-medium">${doctor.spesialisasi}</p>
                            <p class="text-gray-600 text-sm mt-1">Biaya: Rp ${parseInt(doctor.biaya_konsultasi).toLocaleString('id-ID')}</p>
                            <span class="inline-block mt-2 px-3 py-1 rounded-full text-xs font-semibold ${doctor.shift === 'pagi' ? 'bg-yellow-100 text-yellow-700' : 'bg-indigo-100 text-indigo-700'}">
                                Shift ${doctor.shift === 'pagi' ? '🌅 Pagi (07:00-18:00)' : '🌙 Malam (19:00-06:00)'}
                            </span>
                        </div>
                    </div>
                    <button onclick="viewDoctorDetail(${doctor.id_dokter})" class="w-full bg-gradient-to-r from-emerald-500 to-cyan-500 text-white py-3 rounded-xl font-semibold hover:shadow-lg transition transform hover:scale-105">
                        Lihat Jadwal & Booking
                    </button>
                </div>
            `).join('');
        }

        // View doctor detail
        async function viewDoctorDetail(doctorId) {
            // Refresh data dokter terlebih dulu agar slot up-to-date
            await refreshDoctorData(doctorId);
            selectedDoctor = doctorsData.find(d => d.id_dokter === doctorId);
            if (!selectedDoctor) return;

            // Hide schedule list, show doctor detail
            document.getElementById('scheduleContainer').classList.add('hidden');
            document.getElementById('doctorDetailModal').classList.remove('hidden');
            document.getElementById('successModal').classList.add('hidden');

            // Reset selections
            selectedDate = null;
            selectedTime = null;

            // Populate doctor info
            document.getElementById('modalDoctorName').textContent = selectedDoctor.nama_dokter;
            document.getElementById('modalDoctorSpec').textContent = selectedDoctor.spesialisasi;
            document.getElementById('modalDoctorFee').textContent = `Biaya: Rp ${parseInt(selectedDoctor.biaya_konsultasi).toLocaleString('id-ID')}`;

            const shiftBadge = document.getElementById('modalDoctorShift');
            if (selectedDoctor.shift === 'pagi') {
                shiftBadge.className = 'inline-block px-4 py-2 rounded-full text-sm font-semibold bg-yellow-100 text-yellow-700';
                shiftBadge.textContent = 'Shift 🌅 Pagi (07:00-18:00)';
            } else {
                shiftBadge.className = 'inline-block px-4 py-2 rounded-full text-sm font-semibold bg-indigo-100 text-indigo-700';
                shiftBadge.textContent = 'Shift 🌙 Malam (19:00-06:00)';
            }

            document.getElementById('summaryDoctor').textContent = selectedDoctor.nama_dokter;
            document.getElementById('summaryFee').textContent = `Rp ${parseInt(selectedDoctor.biaya_konsultasi).toLocaleString('id-ID')}`;

            // Render date options
            renderDates();
            // Start realtime updater untuk jumlah slot di grid tanggal
            startSlotRealtimeUpdater();

            // Hide time slots and booking form initially
            document.getElementById('timeSlotContainer').classList.add('hidden');
            document.getElementById('bookingFormContainer').classList.add('hidden');
        }

        // Refresh data dokter dari API Gateway agar slot selalu terbaru
        async function refreshDoctorData(doctorId) {
            try {
                const token = localStorage.getItem('access_token');
                const response = await fetch("https://smart-healthcare-system-production-6e7c.up.railway.app/api/janji/ketersediaan", {
                    headers: {
                        'Accept': 'application/json',
                        ...(token ? { 'Authorization': `Bearer ${token}` } : {})
                    }
                });
                const data = await response.json().catch(() => []);
                if (Array.isArray(data)) {
                    doctorsData = data;
                }
            } catch (_) {}
        }

        // Util: format tanggal YYYY-MM-DD
        function formatYYYYMMDD(dateObj) {
            return dateObj.toISOString().split('T')[0];
        }

        // Hitung slot yang benar-benar masih tersedia (untuk hari ini, exclude jam yang sudah lewat)
        function countFutureSlotsForDate(jadwal) {
            const slots = Array.isArray(jadwal?.slot_tersedia) ? jadwal.slot_tersedia : [];
            const todayStr = formatYYYYMMDD(new Date());
            if (jadwal?.tanggal !== todayStr) return slots.length;

            const now = new Date();
            const nowMinutes = now.getHours() * 60 + now.getMinutes();
            return slots.filter((s) => {
                const parts = String(s).split(':');
                const h = parseInt(parts[0] || '0', 10);
                const m = parseInt(parts[1] || '0', 10);
                const minutes = h * 60 + m;
                return minutes > nowMinutes;
            }).length;
        }

        // Updater berkala untuk menjaga angka slot di grid tanggal tetap realtime
        let slotRealtimeTimer = null;
        function startSlotRealtimeUpdater() {
            stopSlotRealtimeUpdater();
            slotRealtimeTimer = setInterval(() => {
                const todayStr = formatYYYYMMDD(new Date());
                const list = Array.isArray(selectedDoctor?.jadwal_ketersediaan) ? selectedDoctor.jadwal_ketersediaan : [];
                list.forEach((jadwal) => {
                    if (jadwal.tanggal === todayStr) {
                        const count = countFutureSlotsForDate(jadwal);
                        const btn = document.querySelector(`.date-btn[data-date="${jadwal.tanggal}"]`);
                        const countEl = btn?.querySelector('.slot-count');
                        if (countEl) {
                            countEl.textContent = count > 0 ? `${count} slot` : 'Penuh';
                            countEl.classList.toggle('text-emerald-600', count > 0);
                            countEl.classList.toggle('text-red-600', count === 0);
                        }
                    }
                });
            }, 30000); // update tiap 30 detik
        }

        function stopSlotRealtimeUpdater() {
            if (slotRealtimeTimer) {
                clearInterval(slotRealtimeTimer);
                slotRealtimeTimer = null;
            }
        }

        // Render date options
        function renderDates() {
            const container = document.getElementById('dateContainer');
            const today = new Date().toISOString().split('T')[0];

            container.innerHTML = selectedDoctor.jadwal_ketersediaan.map(jadwal => {
                const date = new Date(jadwal.tanggal);
                const isToday = jadwal.tanggal === today;
                const availableSlots = countFutureSlotsForDate(jadwal);

                return `
                    <button onclick="selectDate('${jadwal.tanggal}')" data-date="${jadwal.tanggal}"
                        class="date-btn p-4 rounded-xl border-2 transition hover:border-emerald-500 hover:shadow-md ${selectedDate === jadwal.tanggal ? 'border-emerald-500 bg-emerald-50' : 'border-gray-200'}">
                        <div class="text-center">
                            <div class="text-sm text-gray-600">${jadwal.hari}</div>
                            <div class="text-2xl font-bold text-gray-900">${date.getDate()}</div>
                            <div class="text-xs text-gray-500">${date.toLocaleDateString('id-ID', { month: 'short' })}</div>
                            ${isToday ? '<div class="text-xs text-emerald-600 font-semibold mt-1">Hari Ini</div>' : ''}
                            <div class="text-xs mt-2 ${availableSlots > 0 ? 'text-emerald-600' : 'text-red-600'} slot-count">
                                ${availableSlots > 0 ? `${availableSlots} slot` : 'Penuh'}
                            </div>
                        </div>
                    </button>
                `;
            }).join('');
        }

        // Select date
        function selectDate(date) {
            selectedDate = date;
            selectedTime = null;

            // Update date buttons
            document.querySelectorAll('.date-btn').forEach(btn => {
                btn.classList.remove('border-emerald-500', 'bg-emerald-50');
                btn.classList.add('border-gray-200');
            });
            event.target.closest('button').classList.add('border-emerald-500', 'bg-emerald-50');
            event.target.closest('button').classList.remove('border-gray-200');

            // Show time slots
            const jadwal = selectedDoctor.jadwal_ketersediaan.find(j => j.tanggal === date);
            if (!jadwal) return;

            const timeContainer = document.getElementById('timeSlotContainer');
            const timeGrid = document.getElementById('timeSlotGrid');

            timeContainer.classList.remove('hidden');
            document.getElementById('bookingFormContainer').classList.add('hidden');

            if (jadwal.slot_tersedia.length > 0) {
                const isTodaySelected = selectedDate === new Date().toISOString().split('T')[0];
                const now = new Date();
                const nowMinutes = now.getHours() * 60 + now.getMinutes();

                timeGrid.innerHTML = jadwal.slot_tersedia.map(time => {
                    const parts = String(time).split(':');
                    const h = parseInt(parts[0] || '0', 10);
                    const m = parseInt(parts[1] || '0', 10);
                    const timeMinutes = h * 60 + m;
                    const isPast = isTodaySelected && timeMinutes <= nowMinutes;

                    const disabledAttr = isPast ? 'disabled' : '';
                    const disabledClasses = isPast ? 'opacity-50 cursor-not-allowed' : '';
                    const onClick = isPast ? '' : `onclick="selectTime('${time}')"`;

                    return `
                    <button ${onClick} ${disabledAttr}
                        class="time-btn p-3 rounded-lg border-2 transition hover:border-emerald-500 hover:shadow-md text-center border-gray-200 ${disabledClasses}">
                        <div class="text-lg font-bold">${time}</div>
                    </button>`;
                }).join('');
            } else {
                timeGrid.innerHTML = '<p class="col-span-full text-center text-gray-500 py-4">Tidak ada slot tersedia untuk tanggal ini</p>';
            }
        }

        // Select time
        function selectTime(time) {
            selectedTime = time;

            // Update time buttons
            document.querySelectorAll('.time-btn').forEach(btn => {
                btn.classList.remove('border-emerald-500', 'bg-emerald-50', 'font-semibold');
                btn.classList.add('border-gray-200');
            });
            event.target.closest('button').classList.add('border-emerald-500', 'bg-emerald-50', 'font-semibold');
            event.target.closest('button').classList.remove('border-gray-200');

            // Show booking form
            document.getElementById('bookingFormContainer').classList.remove('hidden');

            // Update summary
            const jadwal = selectedDoctor.jadwal_ketersediaan.find(j => j.tanggal === selectedDate);
            const dateObj = new Date(selectedDate);
            document.getElementById('summaryDate').textContent = dateObj.toLocaleDateString('id-ID', {
                weekday: 'long',
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            });
            document.getElementById('summaryTime').textContent = time;
        }

        // Confirm booking
        async function confirmBooking() {
            if (!selectedDate || !selectedTime) {
                alert('Silakan pilih tanggal dan waktu terlebih dahulu');
                return;
            }

            const keluhan = document.getElementById('keluhan').value;
            const bookingData = {
                id_dokter: selectedDoctor.id_dokter,
                tanggal: selectedDate,
                waktu_mulai: selectedTime,
                keluhan: keluhan
            };

            try {
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                const token = localStorage.getItem('access_token');
                const headers = {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                };

                if (token) {
                    headers['Authorization'] = `Bearer ${token}`;
                }

                const res = await fetch("https://smart-healthcare-system-production-6e7c.up.railway.app/api/janji", {
                    method: 'POST',
                    headers: headers,
                    body: JSON.stringify(bookingData)
                });

                const body = await res.json().catch(() => ({ success: false, message: 'Terjadi kesalahan parsing respons' }));

                if (res.ok) {
                    const apiData = body?.data || {};
                    const apiMessage = body?.message || 'Booking Berhasil!';

                    // Update header modal jika ada
                    const headerEl = document.querySelector('#successModal h2');
                    if (headerEl) headerEl.textContent = apiMessage;

                    // Populate success modal dengan data dari API (fallback ke pilihan user)
                    document.getElementById('successDoctor').textContent = selectedDoctor.nama_dokter;
                    document.getElementById('successSpec').textContent = selectedDoctor.spesialisasi;

                    const dateObj = new Date(selectedDate);
                    const displayDate = apiData?.tanggal_janji
                        ? new Date(apiData.tanggal_janji).toLocaleDateString('id-ID', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' })
                        : dateObj.toLocaleDateString('id-ID', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });
                    document.getElementById('successDate').textContent = displayDate;

                    const displayTime = apiData?.waktu_mulai
                        ? `${apiData.waktu_mulai}${apiData?.waktu_selesai ? ' - ' + apiData.waktu_selesai : ''}`
                        : selectedTime;
                    document.getElementById('successTime').textContent = displayTime;

                    document.getElementById('successFee').textContent = `Rp ${parseInt(selectedDoctor.biaya_konsultasi).toLocaleString('id-ID')}`;

                    // Tampilkan modal sukses
                    document.getElementById('doctorDetailModal').classList.add('hidden');
                    document.getElementById('successModal').classList.remove('hidden');
                } else {
                    // Jika belum login, arahkan ke halaman login dan kembali ke ketersediaan
                    if (res.status === 401) {
                        // Simpan booking yang tertunda ke localStorage agar dieksekusi setelah login
                        const pending = {
                            bookingData,
                            doctor: {
                                nama: selectedDoctor?.nama_dokter || selectedDoctor?.nama || '-',
                                spesialisasi: selectedDoctor?.spesialisasi || '-',
                                biaya_konsultasi: selectedDoctor?.biaya_konsultasi || null,
                            }
                        };
                        try { localStorage.setItem('pendingBooking', JSON.stringify(pending)); } catch (_) { }

                        // Arahkan ke login lalu ke dashboard untuk menampilkan notifikasi sukses
                        window.location.href = `/login?redirect=${encodeURIComponent('/dashboard')}`;
                        return;
                    }
                    // Tangani error umum berdasarkan status
                    let msg = body?.message || 'Maaf, terjadi kesalahan saat membooking janji temu';
                    if (res.status === 409) msg = 'Slot waktu ini sudah penuh. Pilih waktu lain.';
                    if (res.status === 400) msg = 'Dokter tidak tersedia pada jam ini. Pilih waktu lain.';
                    if (res.status === 422) msg = 'Mohon periksa kembali data yang Anda masukkan.';
                    alert(msg);
                }
            } catch (err) {
                console.error(err);
                alert('Maaf, terjadi kesalahan jaringan. Coba lagi nanti.');
            }
        }

        // Back to doctor list
        function backToList() {
            document.getElementById('scheduleContainer').classList.remove('hidden');
            document.getElementById('doctorDetailModal').classList.add('hidden');
            document.getElementById('successModal').classList.add('hidden');

            // Reset form
            document.getElementById('keluhan').value = '';
            selectedDoctor = null;
            selectedDate = null;
            selectedTime = null;
        }
    </script>
</body>

</html>