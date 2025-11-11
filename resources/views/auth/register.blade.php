<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar - Smart Health Care</title>
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
    </style>
</head>

<body class="bg-gradient-to-br from-emerald-50 via-white to-cyan-50 min-h-screen flex items-center justify-center">
    <div class="max-w-6xl w-full mx-auto px-6 py-12">
        <div class="grid md:grid-cols-2 gap-8 items-center">
            <!-- Left Side - Branding (reuse login style) -->
            <div class="hidden md:block">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-12 h-12 bg-gradient-to-br from-emerald-500 to-cyan-500 rounded-xl flex items-center justify-center">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                        </svg>
                    </div>
                    <span class="text-2xl font-bold gradient-text">Smart Health Care</span>
                </div>
                <h1 class="text-4xl font-bold text-gray-900 mb-4">Buat Akun Baru ✨</h1>
                <p class="text-gray-600 text-lg mb-6">Daftar sebagai pasien atau dokter untuk mulai menggunakan layanan.</p>
                <div class="space-y-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-emerald-100 rounded-lg flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <span class="text-gray-700">Akses fitur lengkap sesuai peran</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-cyan-100 rounded-lg flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <span class="text-gray-700">Pengalaman login cepat dan aman</span>
                    </div>
                </div>
            </div>

            <!-- Right Side - Register Form -->
            <div class="bg-white rounded-2xl shadow-2xl p-8 md:p-10">
                <div class="md:hidden flex items-center gap-2 mb-6">
                    <div class="w-10 h-10 bg-gradient-to-br from-emerald-500 to-cyan-500 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                        </svg>
                    </div>
                    <span class="text-xl font-bold gradient-text">Smart Health Care</span>
                </div>

                <div class="mb-6">
                    <h2 class="text-2xl md:text-3xl font-bold text-gray-900 mb-2">Daftar Akun</h2>
                    <p class="text-gray-600">Pilih peran untuk menampilkan form yang sesuai</p>
                </div>

                <!-- Role Switcher -->
                <div class="grid grid-cols-2 gap-2 mb-6">
                    <button id="rolePasien" class="px-4 py-2 rounded-xl border border-emerald-300 text-emerald-700 bg-emerald-50 font-semibold">Pasien</button>
                    <button id="roleDokter" class="px-4 py-2 rounded-xl border border-gray-300 text-gray-700 hover:bg-gray-50">Dokter</button>
                </div>

                <!-- Error Messages -->
                <div id="errorContainer" class="hidden mb-4 p-4 rounded-lg bg-red-50 border border-red-200">
                    <div class="flex items-start gap-3">
                        <svg class="w-5 h-5 text-red-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <div>
                            <p class="text-sm font-semibold text-red-800">Terjadi Kesalahan</p>
                            <p id="errorMessage" class="text-sm text-red-700 mt-1"></p>
                        </div>
                    </div>
                </div>

                <!-- Success Message -->
                <div id="successContainer" class="hidden mb-4 p-4 rounded-lg bg-emerald-50 border border-emerald-200">
                    <div class="flex items-start gap-3">
                        <svg class="w-5 h-5 text-emerald-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <div>
                            <p class="text-sm font-semibold text-emerald-800">Registrasi Berhasil!</p>
                            <p class="text-sm text-emerald-700 mt-1">Mengalihkan ke halaman login...</p>
                        </div>
                    </div>
                </div>

                <!-- Register Form -->
                <form id="registerForm" class="space-y-5">
                    <input type="hidden" id="role" name="role" value="pasien" />
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Lengkap</label>
                        <input type="text" id="nama_lengkap" name="nama_lengkap" required class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition" placeholder="Nama lengkap">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Email</label>
                        <input type="email" id="email" name="email" required class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition" placeholder="contoh@email.com">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Password</label>
                        <input type="password" id="password" name="password" required class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition" placeholder="••••••••">
                    </div>
                    <div>
                        <label id="labelNoTelepon" class="block text-sm font-semibold text-gray-700 mb-2">No. Telepon (opsional)</label>
                        <input type="text" id="no_telepon" name="no_telepon" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition" placeholder="08xxxxxxxxxx">
                    </div>

                    <!-- Dokter fields -->
                    <div id="dokterFields" class="hidden space-y-5">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Spesialisasi</label>
                            <input type="text" id="spesialisasi" name="spesialisasi" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition" placeholder="Contoh: Penyakit Dalam">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">No. Lisensi</label>
                            <input type="text" id="no_lisensi" name="no_lisensi" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition" placeholder="Nomor lisensi praktik">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Shift</label>
                            <select id="shift" name="shift" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition">
                                <option value="pagi">Pagi</option>
                                <option value="malam">Malam</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Biaya Konsultasi</label>
                            <input type="number" id="biaya" name="biaya" min="0" step="1000" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition" placeholder="Contoh: 150000">
                            <p class="text-xs text-gray-500 mt-1">Gunakan rupiah tanpa titik/koma.</p>
                        </div>
                    </div>

                    <button type="submit" id="registerButton" class="w-full bg-gradient-to-r from-emerald-500 to-cyan-500 text-white py-3.5 rounded-xl font-semibold hover:shadow-xl transition transform hover:scale-[1.02] active:scale-[0.98]">
                        <span id="buttonText">Daftar</span>
                        <span id="buttonLoading" class="hidden">
                            <svg class="inline w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Memproses...
                        </span>
                    </button>
                </form>

                <div class="mt-6 text-center">
                    <p class="text-sm text-gray-600">
                        Sudah punya akun?
                        <a href="/login" class="font-semibold text-emerald-600 hover:text-emerald-700">Masuk</a>
                    </p>
                </div>

                <div class="mt-6 pt-6 border-t border-gray-200">
                    <a href="/" class="flex items-center justify-center gap-2 text-sm text-gray-600 hover:text-emerald-600 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Kembali ke Beranda
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script>
        const rolePasienBtn = document.getElementById('rolePasien');
        const roleDokterBtn = document.getElementById('roleDokter');
        const roleInput = document.getElementById('role');
        const dokterFields = document.getElementById('dokterFields');

        rolePasienBtn.addEventListener('click', function () {
            roleInput.value = 'pasien';
            dokterFields.classList.add('hidden');
            rolePasienBtn.className = 'px-4 py-2 rounded-xl border border-emerald-300 text-emerald-700 bg-emerald-50 font-semibold';
            roleDokterBtn.className = 'px-4 py-2 rounded-xl border border-gray-300 text-gray-700 hover:bg-gray-50';

            // Pasien: no_telepon tidak wajib, biaya tidak berlaku
            document.getElementById('no_telepon')?.removeAttribute('required');
            const labelNoTel = document.getElementById('labelNoTelepon');
            if (labelNoTel) labelNoTel.textContent = 'No. Telepon (opsional)';
            document.getElementById('biaya')?.removeAttribute('required');
        });

        roleDokterBtn.addEventListener('click', function () {
            roleInput.value = 'dokter';
            dokterFields.classList.remove('hidden');
            roleDokterBtn.className = 'px-4 py-2 rounded-xl border border-emerald-300 text-emerald-700 bg-emerald-50 font-semibold';
            rolePasienBtn.className = 'px-4 py-2 rounded-xl border border-gray-300 text-gray-700 hover:bg-gray-50';

            // Dokter: no_telepon dan biaya wajib
            document.getElementById('no_telepon')?.setAttribute('required', 'true');
            const labelNoTel = document.getElementById('labelNoTelepon');
            if (labelNoTel) labelNoTel.textContent = 'No. Telepon (wajib)';
            document.getElementById('biaya')?.setAttribute('required', 'true');
        });

        document.getElementById('registerForm').addEventListener('submit', async function (e) {
            e.preventDefault();

            const role = roleInput.value;
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;
            const nama_lengkap = document.getElementById('nama_lengkap').value;
            const no_telepon = document.getElementById('no_telepon').value;
            const spesialisasi = document.getElementById('spesialisasi')?.value || undefined;
            const no_lisensi = document.getElementById('no_lisensi')?.value || undefined;
            const shift = document.getElementById('shift')?.value || undefined;
            const biayaInput = document.getElementById('biaya')?.value || undefined;
            const biaya_konsultasi = biayaInput ? parseInt(biayaInput, 10) : undefined;

            const registerButton = document.getElementById('registerButton');
            const buttonText = document.getElementById('buttonText');
            const buttonLoading = document.getElementById('buttonLoading');
            const errorContainer = document.getElementById('errorContainer');
            const successContainer = document.getElementById('successContainer');

            // reset state
            errorContainer.classList.add('hidden');
            successContainer.classList.add('hidden');
            registerButton.disabled = true;
            buttonText.classList.add('hidden');
            buttonLoading.classList.remove('hidden');

            try {
                const csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                const endpoint = role === 'dokter' ? '/register/dokter' : '/register/pasien';
                const payload = {
                    email,
                    password,
                    nama_lengkap,
                    no_telepon,
                    ...(role === 'dokter' ? { spesialisasi, no_lisensi, shift } : {})
                };

                if (role === 'dokter') {
                    // Sertakan biaya_konsultasi untuk dokter; input bertipe number dan required saat peran dokter
                    if (typeof biaya_konsultasi !== 'undefined' && !Number.isNaN(biaya_konsultasi)) {
                        payload.biaya_konsultasi = biaya_konsultasi;
                    }
                }

                const res = await fetch(endpoint, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrf
                    },
                    body: JSON.stringify(payload)
                });

                const data = await res.json().catch(() => ({ success: false }));
                if (res.ok) {
                    successContainer.classList.remove('hidden');
                    setTimeout(() => {
                        window.location.href = '/login';
                    }, 1200);
                } else {
                    errorContainer.classList.remove('hidden');
                    document.getElementById('errorMessage').textContent = data?.message || 'Gagal mendaftar. Periksa kembali data Anda.';
                    registerButton.disabled = false;
                    buttonText.classList.remove('hidden');
                    buttonLoading.classList.add('hidden');
                }
            } catch (err) {
                errorContainer.classList.remove('hidden');
                document.getElementById('errorMessage').textContent = 'Terjadi kesalahan koneksi. Silakan coba lagi.';
                registerButton.disabled = false;
                buttonText.classList.remove('hidden');
                buttonLoading.classList.add('hidden');
            }
        });
    </script>
</body>

</html>