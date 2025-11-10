<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Smart Health Care</title>
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
            <!-- Left Side - Branding -->
            <div class="hidden md:block">
                <div class="flex items-center gap-3 mb-6">
                    <div
                        class="w-12 h-12 bg-gradient-to-br from-emerald-500 to-cyan-500 rounded-xl flex items-center justify-center">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z">
                            </path>
                        </svg>
                    </div>
                    <span class="text-2xl font-bold gradient-text">Smart Health Care</span>
                </div>
                <h1 class="text-4xl font-bold text-gray-900 mb-4">
                    Selamat Datang<br>Kembali! 👋
                </h1>
                <p class="text-gray-600 text-lg mb-6">
                    Masuk ke akun Anda untuk mengakses layanan kesehatan digital terpercaya.
                </p>
                <div class="space-y-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-emerald-100 rounded-lg flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <span class="text-gray-700">Kelola janji temu dengan mudah</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-cyan-100 rounded-lg flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <span class="text-gray-700">Akses rekam medis kapan saja</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-emerald-100 rounded-lg flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <span class="text-gray-700">Konsultasi dengan dokter terbaik</span>
                    </div>
                </div>
                <div class="mt-8 p-4 bg-gradient-to-r from-emerald-50 to-cyan-50 rounded-xl border border-emerald-200">
                    <p class="text-sm text-gray-600">
                        <span class="font-semibold text-emerald-600">💡 Tips:</span>
                        Pastikan Anda datang 15 menit sebelum jadwal konsultasi untuk proses administrasi.
                    </p>
                </div>
            </div>

            <!-- Right Side - Login Form -->
            <div class="bg-white rounded-2xl shadow-2xl p-8 md:p-10">
                <!-- Mobile Logo -->
                <div class="md:hidden flex items-center gap-2 mb-6">
                    <div
                        class="w-10 h-10 bg-gradient-to-br from-emerald-500 to-cyan-500 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z">
                            </path>
                        </svg>
                    </div>
                    <span class="text-xl font-bold gradient-text">Smart Health Care</span>
                </div>

                <div class="mb-6">
                    <h2 class="text-2xl md:text-3xl font-bold text-gray-900 mb-2">Masuk ke Akun</h2>
                    <p class="text-gray-600">Silakan masukkan kredensial Anda</p>
                </div>

                <!-- Error Messages -->
                <div id="errorContainer" class="hidden mb-4 p-4 rounded-lg bg-red-50 border border-red-200">
                    <div class="flex items-start gap-3">
                        <svg class="w-5 h-5 text-red-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
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
                        <svg class="w-5 h-5 text-emerald-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <div>
                            <p class="text-sm font-semibold text-emerald-800">Login Berhasil!</p>
                            <p class="text-sm text-emerald-700 mt-1">Mengalihkan ke dashboard...</p>
                        </div>
                    </div>
                </div>

                <!-- Login Form -->
                <form id="loginForm" class="space-y-5">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Email</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207">
                                    </path>
                                </svg>
                            </div>
                            <input type="email" id="email" name="email" required
                                class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition"
                                placeholder="contoh@email.com">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Password</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                                    </path>
                                </svg>
                            </div>
                            <input type="password" id="password" name="password" required
                                class="w-full pl-10 pr-12 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition"
                                placeholder="••••••••">
                            <button type="button" onclick="togglePassword()"
                                class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                <svg id="eyeIcon" class="w-5 h-5 text-gray-400 hover:text-gray-600" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                    </path>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <div class="flex items-center justify-between">

                    </div>

                    <button type="submit" id="loginButton"
                        class="w-full bg-gradient-to-r from-emerald-500 to-cyan-500 text-white py-3.5 rounded-xl font-semibold hover:shadow-xl transition transform hover:scale-[1.02] active:scale-[0.98]">
                        <span id="buttonText">Masuk</span>
                        <span id="buttonLoading" class="hidden">
                            <svg class="inline w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                    stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                </path>
                            </svg>
                            Memproses...
                        </span>
                    </button>
                </form>

                <div class="mt-6 text-center">
                    <p class="text-sm text-gray-600">
                        Belum punya akun?
                        <a href="/register" class="font-semibold text-emerald-600 hover:text-emerald-700">Daftar
                            sekarang</a>
                    </p>
                </div>

                <div class="mt-6 pt-6 border-t border-gray-200">
                    <a href="/"
                        class="flex items-center justify-center gap-2 text-sm text-gray-600 hover:text-emerald-600 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Kembali ke Beranda
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Toggle password visibility
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const eyeIcon = document.getElementById('eyeIcon');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.innerHTML = `
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path>
                `;
            } else {
                passwordInput.type = 'password';
                eyeIcon.innerHTML = `
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                `;
            }
        }

        document.getElementById('loginForm').addEventListener('submit', async function (e) {
            e.preventDefault();

            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;
            const loginButton = document.getElementById('loginButton');
            const buttonText = document.getElementById('buttonText');
            const buttonLoading = document.getElementById('buttonLoading');
            const errorContainer = document.getElementById('errorContainer');
            const successContainer = document.getElementById('successContainer');

            // Hide previous messages
            errorContainer.classList.add('hidden');
            successContainer.classList.add('hidden');

            // Show loading state
            loginButton.disabled = true;
            buttonText.classList.add('hidden');
            buttonLoading.classList.remove('hidden');

            try {
                const params = new URLSearchParams(window.location.search);
                const redirect = params.get('redirect') || '/dashboard';
                const csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                const response = await fetch('/login', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrf
                    },
                    body: JSON.stringify({
                        email: email,
                        password: password,
                        redirect: redirect
                    })
                });

                const data = await response.json();

                if (response.ok && (data.success === true || data.access_token || data.token)) {
                    const token = data.token || data.access_token;
                    localStorage.setItem('access_token', token);
                    localStorage.setItem('token_type', data.token_type || 'Bearer');

                    if (data.user) {
                        localStorage.setItem('user', JSON.stringify(data.user));
                    }

                    successContainer.classList.remove('hidden');

                    // Sinkronkan token ke session agar middleware bisa mendeteksi login
                    try {
                        await fetch('/session/token', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': csrf
                            },
                            body: JSON.stringify({ token, user: data.user || null })
                        });
                    } catch (_) { /* abaikan jika gagal */ }

                    setTimeout(() => {
                        window.location.href = data.redirect || redirect;
                    }, 1200);
                } else {
                    errorContainer.classList.remove('hidden');
                    document.getElementById('errorMessage').textContent =
                        data.message || 'Email atau password salah. Silakan coba lagi.';

                    loginButton.disabled = false;
                    buttonText.classList.remove('hidden');
                    buttonLoading.classList.add('hidden');
                }
            } catch (error) {
                console.error('Login error:', error);
                errorContainer.classList.remove('hidden');
                document.getElementById('errorMessage').textContent =
                    'Terjadi kesalahan koneksi. Silakan coba lagi.';

                // Reset button state
                loginButton.disabled = false;
                buttonText.classList.remove('hidden');
                buttonLoading.classList.add('hidden');
            }
        });

        // Auto-fill for demo (remove in production)
        // document.getElementById('email').value = 'raihanbandungg@gmail.com';
        // document.getElementById('password').value = 'qwerty123';
    </script>
</body>

</html>