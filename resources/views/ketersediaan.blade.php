<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cek Jadwal - Smart Health Care</title>
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

<body class="bg-gradient-to-br from-emerald-50 via-white to-cyan-50">
    @include('partials.header')



    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-6 py-12">
        <!-- Header -->
        <div class="mb-8">
            <a href="/"
                class="flex items-center gap-2 text-gray-600 hover:text-emerald-600 transition mb-4 inline-flex">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Kembali ke Home
            </a>
            <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-2">Jadwal Ketersediaan Dokter</h1>
            <p class="text-gray-600">Pilih dokter dan jadwal yang sesuai untuk konsultasi Anda</p>
        </div>

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
                    <button onclick="backToList()"
                        class="bg-gradient-to-r from-emerald-500 to-cyan-500 text-white px-8 py-3 rounded-full font-semibold hover:shadow-lg transition">
                        Booking Lagi
                    </button>
                    <a href="/"
                        class="bg-white text-gray-700 px-8 py-3 rounded-full font-semibold border-2 border-gray-200 hover:border-emerald-500 transition inline-block">
                        Kembali ke Home
                    </a>
                </div>
            </div>
        </div>
    </main>

    @include('partials.footer')

    <script>
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
                const response = await fetch('https://smart-healthcare-system-production-6e7c.up.railway.app/api/janji/ketersediaan-all');

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
        function viewDoctorDetail(doctorId) {
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

            // Hide time slots and booking form initially
            document.getElementById('timeSlotContainer').classList.add('hidden');
            document.getElementById('bookingFormContainer').classList.add('hidden');
        }

        // Render date options
        function renderDates() {
            const container = document.getElementById('dateContainer');
            const today = new Date().toISOString().split('T')[0];

            container.innerHTML = selectedDoctor.jadwal_ketersediaan.map(jadwal => {
                const date = new Date(jadwal.tanggal);
                const isToday = jadwal.tanggal === today;
                const availableSlots = jadwal.slot_tersedia.length;

                return `
                    <button onclick="selectDate('${jadwal.tanggal}')" 
                        class="date-btn p-4 rounded-xl border-2 transition hover:border-emerald-500 hover:shadow-md ${selectedDate === jadwal.tanggal ? 'border-emerald-500 bg-emerald-50' : 'border-gray-200'}">
                        <div class="text-center">
                            <div class="text-sm text-gray-600">${jadwal.hari}</div>
                            <div class="text-2xl font-bold text-gray-900">${date.getDate()}</div>
                            <div class="text-xs text-gray-500">${date.toLocaleDateString('id-ID', { month: 'short' })}</div>
                            ${isToday ? '<div class="text-xs text-emerald-600 font-semibold mt-1">Hari Ini</div>' : ''}
                            <div class="text-xs mt-2 ${availableSlots > 0 ? 'text-emerald-600' : 'text-red-600'}">
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
                timeGrid.innerHTML = jadwal.slot_tersedia.map(time => `
                    <button onclick="selectTime('${time}')" 
                        class="time-btn p-3 rounded-lg border-2 transition hover:border-emerald-500 hover:shadow-md text-center border-gray-200">
                        <div class="text-lg font-bold">${time}</div>
                    </button>
                `).join('');
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
        function confirmBooking() {
            if (!selectedDate || !selectedTime) {
                alert('Silakan pilih tanggal dan waktu terlebih dahulu');
                return;
            }

            const keluhan = document.getElementById('keluhan').value;

            // Populate success modal
            document.getElementById('successDoctor').textContent = selectedDoctor.nama_dokter;
            document.getElementById('successSpec').textContent = selectedDoctor.spesialisasi;

            const dateObj = new Date(selectedDate);
            document.getElementById('successDate').textContent = dateObj.toLocaleDateString('id-ID', {
                weekday: 'long',
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            });
            document.getElementById('successTime').textContent = selectedTime;
            document.getElementById('successFee').textContent = `Rp ${parseInt(selectedDoctor.biaya_konsultasi).toLocaleString('id-ID')}`;

            // Show success modal
            document.getElementById('doctorDetailModal').classList.add('hidden');
            document.getElementById('successModal').classList.remove('hidden');

            // Here you would normally send data to backend
            // const bookingData = {
            //     id_dokter: selectedDoctor.id_dokter,
            //     tanggal: selectedDate,
            //     waktu_mulai: selectedTime,
            //     keluhan: keluhan
            // };
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