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

    // ===== Endpoint URL Helpers (seragam) =====
    function getApiBase() {
        try {
            if (window.API_BASE) return String(window.API_BASE).replace(/\/$/, '');
        } catch (_) { }
        return 'https://smart-healthcare-system-production-6e7c.up.railway.app/api';
    }
    function apiUrl(path) {
        return getApiBase() + '/' + String(path || '').replace(/^\/+/, '');
    }
    function localUrl(path) {
        return '/' + String(path || '').replace(/^\/+/, '');
    }

    // Normalisasi nilai sort: menerima 'terbaru'/'desc' => 'desc', 'terlama'/'asc' => 'asc'
    function normalizeSortValue(raw) {
        const v = String(raw || '').toLowerCase();
        if (v === 'desc' || v === 'terbaru') return 'desc';
        if (v === 'asc' || v === 'terlama') return 'asc';
        return '';
    }

    // Sorting berdasarkan tanggal_janji (primer) lalu waktu_mulai (sekunder)
    function applySortByDateTime(items, dir) {
        const d = normalizeSortValue(dir);
        if (!Array.isArray(items) || !d) return items;
        const sign = (d === 'desc') ? -1 : 1;
        const parseDate = (s) => {
            try { return new Date(normalizeDateString(s || '')); } catch (_) { return new Date('1970-01-01'); }
        };
        const parseTime = (s) => {
            const t = String(s || '').slice(0, 5);
            const [h, m] = t.split(':').map(n => parseInt(n || '0', 10));
            return (Number.isFinite(h) ? h : 0) * 60 + (Number.isFinite(m) ? m : 0);
        };
        return [...items].sort((a, b) => {
            const da = parseDate(a?.tanggal_janji ?? a?.tanggal);
            const db = parseDate(b?.tanggal_janji ?? b?.tanggal);
            const cmpDate = (da.getTime() - db.getTime());
            if (cmpDate !== 0) return sign * (cmpDate > 0 ? 1 : -1);
            const ta = parseTime(a?.waktu_mulai);
            const tb = parseTime(b?.waktu_mulai);
            const cmpTime = (ta - tb);
            return sign * (cmpTime > 0 ? 1 : (cmpTime < 0 ? -1 : 0));
        });
    }

    // Parse tanggal + waktu sebagai waktu WIB (+07:00) untuk penentuan mendatang
    function parseWIBDateTime(tanggal, waktu) {
        const t = normalizeDateString(tanggal || '');
        const w = String(waktu || '').slice(0, 5);
        if (!t || !w) return null;
        const iso = `${t}T${w}:00+07:00`;
        try { return new Date(iso); } catch (_) { return null; }
    }

    function isUpcomingAppointment(it) {
        const status = (it?.status || '').toLowerCase();
        if (status !== 'terjadwal') return false;
        const dt = parseWIBDateTime(it?.tanggal_janji ?? it?.tanggal, it?.waktu_mulai);
        if (!dt) return false;
        const now = new Date();
        return dt.getTime() >= now.getTime();
    }

    function orderAppointmentsForUpcoming(items) {
        if (!Array.isArray(items)) return [];
        const scheduledAll = items.filter(it => (it?.status || '').toLowerCase() === 'terjadwal');
        const upcomingScheduled = scheduledAll.filter(isUpcomingAppointment);
        const pastScheduled = scheduledAll.filter(it => !isUpcomingAppointment(it));
        const selesai = items.filter(it => (it?.status || '').toLowerCase() === 'selesai');
        const dibatalkan = items.filter(it => (it?.status || '').toLowerCase() === 'dibatalkan');
        return [...upcomingScheduled, ...pastScheduled, ...selesai, ...dibatalkan];
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
            // Tentukan nama yang ditampilkan berdasarkan role:
            // - Pasien: tampilkan nama dokter
            // - Dokter: tampilkan nama pasien
            const role = (function () {
                try {
                    if (window.APP_ROLE) return String(window.APP_ROLE);
                    const u = JSON.parse(localStorage.getItem('user') || '{}');
                    return String(u?.role || u?.roles?.[0] || '');
                } catch (_) { return ''; }
            })();
            const isDoctorRole = /dokter/i.test(role || '');
            const namePrimary = isDoctorRole
                ? (it?.pasien?.pengguna?.nama_lengkap || it?.pasien?.pengguna?.name || it?.pasien?.nama || 'Pasien')
                : (it?.dokter?.pengguna?.nama_lengkap || it?.dokter?.pengguna?.name || it?.dokter?.nama || 'Dokter');
            // Teks kecil (secondary):
            // - Dokter: tampilkan keluhan/catatan pasien
            // - Pasien: tetap tampilkan spesialisasi dokter
            const keluhanTextRaw = it?.keluhan ?? it?.catatan ?? '';
            const truncate = (s, n) => (typeof s === 'string' && s.length > n) ? (s.slice(0, n) + '…') : s;
            const secondaryText = isDoctorRole
                ? (keluhanTextRaw ? truncate(keluhanTextRaw, 80) : '')
                : (it?.dokter?.spesialisasi || 'Dokter');
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
                    <h3 class="font-semibold text-gray-900">${namePrimary}</h3>
                    <p class="text-sm text-gray-500">${secondaryText}</p>
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
                    <button title="Edit" onclick=\"event.stopPropagation();openEditJanji('${id}','${normalizeDateString(it?.tanggal_janji)}','${it?.waktu_mulai || ''}','${it?.waktu_selesai || ''}','${encodeURIComponent(it?.keluhan ?? it?.catatan ?? '')}')\" class="p-2 rounded-lg text-gray-500 hover:text-emerald-700 hover:bg-emerald-50">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 4a2 2 0 012-2h0a2 2 0 012 2v0m-2.879 5.121l6.758 6.758M4 16.5V20h3.5l9.879-9.879a2.5 2.5 0 000-3.536l-1.964-1.964a2.5 2.5 0 00-3.536 0L4 16.5z" />
                        </svg>
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

    // ===== Pagination State & Helpers =====
    let _appointmentsAll = [];
    let _appointmentsPage = 1;
    const _appointmentsPageSize = 5;

    function updatePaginationControls() {
        const container = document.getElementById('upcomingPagination');
        const prevBtn = document.getElementById('upcomingPrevBtn');
        const nextBtn = document.getElementById('upcomingNextBtn');
        const info = document.getElementById('upcomingPageInfo');
        const total = _appointmentsAll.length;
        const totalPages = Math.max(1, Math.ceil(total / _appointmentsPageSize));
        if (!container) return;
        if (total <= _appointmentsPageSize) {
            container.classList.add('hidden');
            return;
        }
        container.classList.remove('hidden');
        if (prevBtn) prevBtn.disabled = (_appointmentsPage <= 1);
        if (nextBtn) nextBtn.disabled = (_appointmentsPage >= totalPages);
        if (info) info.textContent = `Halaman ${_appointmentsPage}/${totalPages}`;
    }

    function renderAppointmentsPage(page) {
        const total = _appointmentsAll.length;
        const totalPages = Math.max(1, Math.ceil(total / _appointmentsPageSize));
        _appointmentsPage = Math.min(Math.max(1, page || _appointmentsPage), totalPages);
        const start = (_appointmentsPage - 1) * _appointmentsPageSize;
        const end = start + _appointmentsPageSize;
        const slice = _appointmentsAll.slice(start, end);
        renderAppointments(slice);
        updatePaginationControls();
    }

    function setAppointments(items) {
        _appointmentsAll = Array.isArray(items) ? items : [];
        _appointmentsPage = 1;
        renderAppointmentsPage(_appointmentsPage);
    }

    function upcomingPrev() { renderAppointmentsPage(_appointmentsPage - 1); }
    function upcomingNext() { renderAppointmentsPage(_appointmentsPage + 1); }

    async function cariJanji() {
        const token = localStorage.getItem('access_token');
        if (!token) {
            window.location.href = '/login?redirect=' + encodeURIComponent('/dashboard');
            return;
        }
        const tgl = document.getElementById('searchTanggal')?.value || '';
        let dokter = document.getElementById('searchDokter')?.value || '';
        let pasien = document.getElementById('searchPasien')?.value || '';
        const sortRaw = document.getElementById('searchSort')?.value || '';
        const sortVal = normalizeSortValue(sortRaw);

        // Role-based akses filter: Dokter hanya cari pasien, Pasien hanya cari dokter
        const role = (function () {
            try {
                if (window.APP_ROLE) return String(window.APP_ROLE);
                const u = JSON.parse(localStorage.getItem('user') || '{}');
                return String(u?.role || u?.roles?.[0] || '');
            } catch (_) { return ''; }
        })();
        const isDoctorRole = /dokter/i.test(role || '');
        const isPatientRole = /pasien/i.test(role || '');
        if (isDoctorRole) { dokter = ''; }
        if (isPatientRole) { pasien = ''; }

        if (!tgl && !dokter && !pasien && !sortVal) {
            showToast('Isi pencarian', 'Masukkan tanggal, nama dokter, atau nama pasien, atau pilih urutan.');
            return;
        }

        // Jika hanya sort yang dipilih tanpa filter lain, gunakan endpoint daftar janji dengan sort
        if (!tgl && !dokter && !pasien && sortVal) {
            try {
                const base = apiUrl('janji');
                const url = `${base}?sort=${encodeURIComponent(sortVal)}`;
                const res = await fetch(url, {
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
                let items = body?.data || body || [];
                if (!Array.isArray(items)) items = [];
                setAppointments(items);
                const label = (sortVal === 'desc') ? 'Terbaru' : 'Terlama';
                showToast('Diurutkan', `Daftar janji diurutkan: ${label}.`);
            } catch (_) {
                showToast('Jaringan bermasalah', 'Gagal mengambil daftar janji terurut.');
            }
            return;
        }

        const qs = new URLSearchParams();
        if (tgl) qs.set('tanggal', tgl);
        if (dokter) qs.set('nama_dokter', dokter);
        if (pasien) qs.set('nama_pasien', pasien);

        try {
            let res = await fetch(apiUrl('janji/cari') + '?' + qs.toString(), {
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
                const body403 = await res.json().catch(() => ({}));
                showToast('Akses dibatasi', body403?.message || 'Anda tidak memiliki akses untuk pencarian ini.');
                return;
            }
            if (!res.ok && res.status !== 404) {
                throw new Error('Search failed');
            }
            const body = await res.json().catch(() => ({}));
            const items = Array.isArray(body) ? body : (body?.data || body?.items || body?.results || []);

            const qDate = tgl ? normalizeDateString(tgl) : '';
            const qName = dokter ? dokter.toLowerCase() : '';
            const qPatient = pasien ? pasien.toLowerCase() : '';

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

                const patientNames = [
                    it?.pasien?.pengguna?.nama_lengkap,
                    it?.pasien?.pengguna?.name,
                    it?.pasien?.nama
                ].filter(Boolean).map(s => String(s).toLowerCase());
                const matchPatient = qPatient ? patientNames.some(n => n.includes(qPatient)) : true;

                return matchDate && matchName && matchPatient;
            });

            const toRender = sortVal ? applySortByDateTime(filtered, sortVal) : (orderAppointmentsForUpcoming(filtered).length ? orderAppointmentsForUpcoming(filtered) : filtered);
            setAppointments(toRender);
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
                ${data?.keluhan ? `<div><span class='text-gray-500'>Keluhan:</span> <span class='font-medium'>${data.keluhan}</span></div>` : ''}
                ${data?.catatan ? `<div><span class='text-gray-500'>Catatan:</span> <span class='font-medium'>${data.catatan}</span></div>` : ''}
            </div>
        `;

        modal.classList.remove('hidden');
    }

    function closeJanjiModal() {
        document.getElementById('janjiModal').classList.add('hidden');
    }

    function openEditJanji(id, tanggal, mulai, selesai, keluhan) {
        const modal = document.getElementById('editJanjiModal');
        document.getElementById('editJanjiId').value = id || '';

        // Ambil token dan role
        const token = localStorage.getItem('access_token');
        const role = (function () {
            try {
                if (window.APP_ROLE) return String(window.APP_ROLE);
                const u = JSON.parse(localStorage.getItem('user') || '{}');
                return String(u?.role || u?.roles?.[0] || '');
            } catch (_) { return ''; }
        })();
        const isDoctorRole = /dokter/i.test(role || '');
        const isPatientRole = /pasien/i.test(role || '');

        // Reset form dokter
        document.getElementById('inputDiagnosis').value = '';
        document.getElementById('inputTindakan').value = '';
        document.getElementById('inputCatatanMedis').value = '';
        document.getElementById('checkboxSelesai').checked = false;
        document.getElementById('assignDoctorSelect').innerHTML = '<option value="">— Tidak mengubah/mendelegasikan dokter —</option>';
        document.getElementById('rekamMedisExist').classList.add('hidden');

        // === 1. Isi Form Edit Jadwal (untuk semua role) ===
        document.getElementById('editTanggal').value = normalizeDateString(tanggal || '');
        const startVal = (mulai || '').slice(0, 5);
        const endValRaw = (selesai || '').slice(0, 5);
        document.getElementById('editMulai').value = startVal;

        const endInput = document.getElementById('editSelesai');
        if (endValRaw) {
            endInput.value = endValRaw;
        } else if (startVal && startVal.includes(':')) {
            endInput.value = computeEndFromStart(startVal);
        } else {
            endInput.value = '';
        }

        const startInput = document.getElementById('editMulai');
        startInput.oninput = function () {
            const val = (startInput.value || '').slice(0, 5);
            if (val && val.includes(':')) {
                endInput.value = computeEndFromStart(val);
            } else {
                endInput.value = '';
            }
        };

        const keluhanInput = document.getElementById('editCatatan');
        const keluhanHelpText = document.getElementById('keluhanReadOnlyText');
        try {
            keluhanInput.value = keluhan ? decodeURIComponent(keluhan) : '';
        } catch (_) {
            keluhanInput.value = keluhan || '';
        }

        // === 2. Ambil detail janji (untuk status & rekam medis) ===
        // --- DIPERBAIKI: Menggunakan apiUrl ---
        fetch(apiUrl('janji/' + id), {
            headers: {
                'Accept': 'application/json',
                'Authorization': `Bearer ${token}`
            }
        })
            .then(res => {
                if (!res.ok) throw new Error('Gagal memuat detail');
                return res.json();
            })
            .then(body => {
                const data = body?.data || body;
                const status = (data?.status || '').toLowerCase();
                document.getElementById('editCurrentStatus').value = status;

                const dokterSection = document.getElementById('dokterSection');

                // === 3. Atur Visibilitas berdasarkan Role ===
                if (isDoctorRole && status === 'terjadwal') {
                    dokterSection.classList.remove('hidden');
                    keluhanInput.readOnly = true;
                    keluhanHelpText.classList.remove('hidden');

                    // Ambil daftar dokter untuk delegasi
                    (async function loadDoctors() {
                        const assignSelect = document.getElementById('assignDoctorSelect');
                        try {
                            if (!window._assignDoctorsList) {
                                // (Ini sudah benar menggunakan apiUrl)
                                const res = await fetch(apiUrl('janji/ketersediaan'), {
                                    headers: { 'Accept': 'application/json' }
                                });
                                const dataList = await res.json().catch(() => []);
                                window._assignDoctorsList = Array.isArray(dataList) ? dataList : [];
                            }
                            const list = window._assignDoctorsList || [];
                            list.forEach(d => {
                                const idOpt = d?.id_dokter ?? d?.id;
                                const namaOpt = d?.nama_dokter ?? d?.nama ?? 'Dokter';
                                const spes = d?.spesialisasi ? ` (${d.spesialisasi})` : '';
                                const shiftLabel = d?.shift ? ` — Shift ${d.shift}` : '';
                                const opt = document.createElement('option');
                                opt.value = String(idOpt || '');
                                opt.textContent = `${namaOpt}${spes}${shiftLabel}`;
                                assignSelect.appendChild(opt);
                            });
                        } catch (_) {
                            assignSelect.innerHTML = '<option value="" disabled>Gagal memuat daftar dokter</option>';
                        }
                    })();

                    // Cek apakah rekam medis sudah ada
                    // --- DIPERBAIKI: Menggunakan apiUrl ---
                    fetch(apiUrl('rekam-medis'), {
                        headers: {
                            'Accept': 'application/json',
                            'Authorization': `Bearer ${token}`
                        }
                    })
                        .then(res => res.json())
                        .then(allRekamMedis => {
                            const allData = allRekamMedis?.data || allRekamMedis; // Menyesuaikan jika ada bungkus 'data'
                            const existingRekamMedis = Array.isArray(allData)
                                ? allData.find(rm => rm.id_janji_temu == id)
                                : null;

                            const rekamMedisExistDiv = document.getElementById('rekamMedisExist');
                            if (existingRekamMedis) {
                                rekamMedisExistDiv.classList.remove('hidden');
                                document.getElementById('inputDiagnosis').value = existingRekamMedis.diagnosis || '';
                                document.getElementById('inputTindakan').value = existingRekamMedis.tindakan || '';
                                document.getElementById('inputCatatanMedis').value = existingRekamMedis.catatan || '';
                            } else {
                                rekamMedisExistDiv.classList.add('hidden');
                            }
                        })
                        .catch(() => { });

                } else if (isPatientRole) {
                    dokterSection.classList.add('hidden');
                    keluhanInput.readOnly = false;
                    keluhanHelpText.classList.add('hidden');
                } else {
                    dokterSection.classList.add('hidden');
                    keluhanInput.readOnly = true;
                    keluhanHelpText.classList.add('hidden');
                }
            })
            .catch(() => {
                showToast('Gagal', 'Tidak bisa memuat detail status janji.');
                document.getElementById('dokterSection').classList.add('hidden');
                keluhanInput.readOnly = !isPatientRole;
            });

        modal.classList.remove('hidden');
    }

    function closeEditJanjiModal() {
        document.getElementById('editJanjiModal').classList.add('hidden');
    }

    function computeEndFromStart(hhmm) {
        try {
            const [h, m] = hhmm.split(':').map(Number);
            if (!Number.isFinite(h) || !Number.isFinite(m)) return '';
            let endH = h + 1;
            if (endH >= 24) endH = endH - 24;
            const pad = (n) => (n < 10 ? '0' + n : '' + n);
            return pad(endH) + ':' + pad(m);
        } catch (_) {
            return '';
        }
    }

    async function submitEditJanji() {
        const token = localStorage.getItem('access_token');
        if (!token) {
            window.location.href = '/login?redirect=' + encodeURIComponent('/dashboard');
            return;
        }

        const csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const id = document.getElementById('editJanjiId').value;
        const currentStatus = document.getElementById('editCurrentStatus').value;

        const role = (function () {
            try {
                if (window.APP_ROLE) return String(window.APP_ROLE);
                const u = JSON.parse(localStorage.getItem('user') || '{}');
                return String(u?.role || u?.roles?.[0] || '');
            } catch (_) { return ''; }
        })();
        const isDoctorRole = /dokter/i.test(role || '');
        const isPatientRole = /pasien/i.test(role || '');

        if (!id) {
            showToast('Gagal', 'ID janji tidak valid.');
            return;
        }

        let payload = {};
        let endpoint = apiUrl('janji/' + encodeURIComponent(id));
        let method = 'PUT';

        // === LOGIKA UNTUK DOKTER ===
        if (isDoctorRole && currentStatus === 'terjadwal') {
            const assignDoctorId = document.getElementById('assignDoctorSelect')?.value;
            const checkSelesai = document.getElementById('checkboxSelesai').checked;
            const tanggal = document.getElementById('editTanggal').value;
            const mulai = document.getElementById('editMulai').value;

            // PRIORITAS 1: Mendelegasikan Janji
            if (assignDoctorId && assignDoctorId !== "") {
                payload = { id_dokter: parseInt(assignDoctorId, 10) };

                // PRIORITAS 2: Menyelesaikan Janji (Input Rekam Medis)
            } else if (checkSelesai) {
                const diagnosis = document.getElementById('inputDiagnosis').value.trim();
                const tindakan = document.getElementById('inputTindakan').value.trim();
                const catatanMedis = document.getElementById('inputCatatanMedis').value.trim();

                if (!diagnosis || !tindakan) {
                    showToast('Data belum lengkap', 'Diagnosis dan Tindakan wajib diisi sebelum menandai selesai');
                    return;
                }

                try {
                    // Step 1: Ambil detail janji (untuk id_pasien, id_dokter, tgl)
                    const janjiDetailRes = await fetch(apiUrl('janji/' + id), {
                        headers: { 'Accept': 'application/json', 'Authorization': `Bearer ${token}` }
                    });
                    const janjiDetail = await janjiDetailRes.json().then(b => b?.data || b).catch(() => ({}));

                    // Step 2: Cek rekam medis yang ada
                    const checkRes = await fetch(apiUrl('rekam-medis'), {
                        headers: { 'Accept': 'application/json', 'Authorization': `Bearer ${token}` }
                    });
                    const allRekamMedis = await checkRes.json().catch(() => []);
                    const allData = allRekamMedis?.data || allRekamMedis;
                    const existingRekamMedis = Array.isArray(allData)
                        ? allData.find(rm => rm.id_janji_temu == id)
                        : null;

                    // Step 3: Siapkan payload rekam medis
                    const rekamMedisPayload = {
                        id_pasien: janjiDetail.id_pasien,
                        id_dokter: janjiDetail.id_dokter,
                        id_janji_temu: parseInt(id, 10),
                        // ==================== DIPERBAIKI DI SINI ====================
                        tanggal_kunjungan: normalizeDateString(janjiDetail.tanggal_janji), // Gunakan helper untuk membersihkan tanggal
                        // ==========================================================
                        diagnosis: diagnosis,
                        tindakan: tindakan,
                        catatan: catatanMedis || null
                    };

                    let rekamMedisResponse;
                    const headersRekamMedis = {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'Authorization': `Bearer ${token}`,
                        'X-CSRF-TOKEN': csrf,
                        'X-Requested-With': 'XMLHttpRequest'
                    };

                    // Step 4: Buat atau Update rekam medis
                    if (existingRekamMedis) {
                        // Update (PUT)
                        rekamMedisResponse = await fetch(apiUrl(`rekam-medis/${existingRekamMedis.id_rekam_medis}`), {
                            method: 'PUT',
                            headers: headersRekamMedis,
                            body: JSON.stringify(rekamMedisPayload)
                        });
                    } else {
                        // Buat (POST)
                        rekamMedisResponse = await fetch(apiUrl('rekam-medis'), {
                            method: 'POST',
                            headers: headersRekamMedis,
                            body: JSON.stringify(rekamMedisPayload)
                        });
                    }

                    if (!rekamMedisResponse.ok) {
                        const errorBody = await rekamMedisResponse.json().catch(() => ({}));
                        console.error("Error saat POST/PUT rekam-medis:", errorBody); // Tambah log
                        showToast('Gagal menyimpan rekam medis', errorBody?.message || 'Terjadi kesalahan. Status janji tidak diubah.');
                        return;
                    }

                    // Step 5: Jika rekam medis OK, siapkan payload untuk update status janji
                    payload = { status: 'selesai' };

                } catch (e) {
                    console.error("Error di try-catch rekam medis:", e); // Tambah log
                    showToast('Gagal', 'Terjadi kesalahan saat memproses rekam medis: ' + e.message);
                    return;
                }

                // PRIORITAS 3: Edit Jadwal (Tanggal/Waktu)
            } else {
                if (!tanggal || !mulai) {
                    showToast('Data belum lengkap', 'Tanggal dan waktu mulai wajib diisi.');
                    return;
                }
                if (isTimeInPast(tanggal, mulai)) {
                    showToast('Waktu tidak valid', 'Tidak boleh mengatur janji di waktu yang sudah lewat.');
                    return;
                }
                payload = {
                    tanggal_janji: tanggal,
                    waktu_mulai: mulai,
                };
            }

            // === LOGIKA UNTUK PASIEN ===
        } else if (isPatientRole) {
            const tanggal = document.getElementById('editTanggal').value;
            const mulai = document.getElementById('editMulai').value;
            const catatan = document.getElementById('editCatatan').value;

            if (!tanggal || !mulai) {
                showToast('Data belum lengkap', 'Tanggal dan waktu mulai wajib diisi.');
                return;
            }
            if (isTimeInPast(tanggal, mulai)) {
                showToast('Waktu tidak valid', 'Tidak boleh mengatur janji di waktu yang sudah lewat.');
                return;
            }
            payload = {
                tanggal_janji: tanggal,
                waktu_mulai: mulai,
                ...(catatan ? { keluhan: catatan } : {}),
            };

            // === LOGIKA UNTUK KASUS LAIN (Misal janji sudah selesai) ===
        } else {
            showToast('Tidak ada aksi', 'Janji ini tidak dapat diubah lagi.');
            return;
        }

        // === KIRIM REQUEST UPDATE JANJI (FINAL) ===
        const headersObj = {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'Authorization': `Bearer ${token}`,
            'X-CSRF-TOKEN': csrf,
            'X-Requested-With': 'XMLHttpRequest'
        };

        try {
            let res = await fetch(endpoint, {
                method: method,
                headers: headersObj,
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
                console.error("Error saat PUT janji:", body); // Tambah log
                showToast('Gagal', body?.message || 'Terjadi kesalahan saat memperbarui janji temu');
            }
        } catch (e) {
            console.error("Error di try-catch janji:", e); // Tambah log
            showToast('Jaringan bermasalah', 'Gagal memperbarui janji. Coba lagi: ' + e.message);
        }
    }

    // Fungsi helper baru untuk validasi waktu
    function isTimeInPast(tanggal, hhmm) {
        try {
            const now = new Date();
            const selectedDate = new Date(tanggal + 'T00:00:00');
            const [hMulai, mMulai] = (hhmm || '').split(':').map(Number);

            if (Number.isFinite(hMulai) && Number.isFinite(mMulai)) {
                selectedDate.setHours(hMulai, mMulai, 0, 0);
            }
            // Beri toleransi 1 menit
            return selectedDate.getTime() < (now.getTime() - 60000);
        } catch (_) {
            return false; // Jika format salah, biarkan backend yg validasi
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
            const res = await fetch(localUrl('janji/' + encodeURIComponent(id)), {
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
        let dokter = document.getElementById('searchDokter')?.value || '';
        let pasien = document.getElementById('searchPasien')?.value || '';
        const sortRaw = document.getElementById('searchSort')?.value || '';
        const sortVal = normalizeSortValue(sortRaw);
        const role = (function () {
            try {
                if (window.APP_ROLE) return String(window.APP_ROLE);
                const u = JSON.parse(localStorage.getItem('user') || '{}');
                return String(u?.role || u?.roles?.[0] || '');
            } catch (_) { return ''; }
        })();
        const isDoctorRole = /dokter/i.test(role || '');
        const isPatientRole = /pasien/i.test(role || '');
        if (isDoctorRole) { dokter = ''; }
        if (isPatientRole) { pasien = ''; }
        if (tgl || dokter || pasien) {
            await cariJanji();
            return;
        }
        try {
            const base = apiUrl('janji');
            const url = sortVal ? `${base}?sort=${encodeURIComponent(sortVal)}` : base;
            const res = await fetch(url, {
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
            let items = body?.data || body || [];
            if (!Array.isArray(items)) items = [];
            const renderedList = sortVal ? items : (orderAppointmentsForUpcoming(items).length ? orderAppointmentsForUpcoming(items) : items);
            setAppointments(renderedList);
        } catch (_) { }
    }

    async function openJanjiDetail(id) {
        const token = localStorage.getItem('access_token');
        if (!token) {
            window.location.href = '/login?redirect=' + encodeURIComponent('/dashboard');
            return;
        }
        try {
            const res = await fetch(localUrl('janji/' + id), {
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

    async function loadStatistikJanji() {
        const totalEl = document.getElementById('statTotalJanji');
        const aktifEl = document.getElementById('statAktifJanji');
        if (totalEl) totalEl.textContent = '…';
        if (aktifEl) aktifEl.textContent = '…';
        const token = localStorage.getItem('access_token');
        if (!token) {
            window.location.href = '/login?redirect=' + encodeURIComponent('/dashboard');
            return;
        }
        try {
            const res = await fetch('/janji/statistik', {
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
            const total = (body && (body.total ?? body?.data?.total)) ?? null;
            const aktif = (body && (body.aktif ?? body?.data?.aktif)) ?? null;
            if (totalEl) totalEl.textContent = (total !== null && total !== undefined) ? String(total) : '-';
            if (aktifEl) aktifEl.textContent = (aktif !== null && aktif !== undefined) ? String(aktif) : '-';
        } catch (_) {
            if (totalEl) totalEl.textContent = '-';
            if (aktifEl) aktifEl.textContent = '-';
        }
    }

    async function initDashboard() {
        const token = localStorage.getItem('access_token');
        if (!token) {
            window.location.href = '/login?redirect=' + encodeURIComponent('/dashboard');
            return;
        }
        // Atur visibilitas input pencarian berdasarkan role
        try {
            const role = (function () {
                try {
                    if (window.APP_ROLE) return String(window.APP_ROLE);
                    const u = JSON.parse(localStorage.getItem('user') || '{}');
                    return String(u?.role || u?.roles?.[0] || '');
                } catch (_) { return ''; }
            })();
            const isDoctorRole = /dokter/i.test(role || '');
            const isPatientRole = /pasien/i.test(role || '');
            const dokterInput = document.getElementById('searchDokter');
            const pasienInput = document.getElementById('searchPasien');
            if (isDoctorRole) {
                if (dokterInput) { dokterInput.classList.add('hidden'); dokterInput.value = ''; }
                if (pasienInput) { pasienInput.classList.remove('hidden'); }
            } else if (isPatientRole) {
                if (pasienInput) { pasienInput.classList.add('hidden'); pasienInput.value = ''; }
                if (dokterInput) { dokterInput.classList.remove('hidden'); }
            } else {
                // Admin/other: tampilkan keduanya
                if (dokterInput) { dokterInput.classList.remove('hidden'); }
                if (pasienInput) { pasienInput.classList.remove('hidden'); }
            }
        } catch (_) { }
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
            const sortRaw = document.getElementById('searchSort')?.value || '';
            const sortVal = normalizeSortValue(sortRaw);
            const base = '/janji';
            const url = sortVal ? `${base}?sort=${encodeURIComponent(sortVal)}` : base;
            const res = await fetch(url, {
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
            let items = body?.data || body || [];
            if (!Array.isArray(items)) items = [];
            const renderedList = sortVal ? items : (orderAppointmentsForUpcoming(items).length ? orderAppointmentsForUpcoming(items) : items);
            setAppointments(renderedList);
        } catch (_) {
        }

        try { await loadStatistikJanji(); } catch (_) { }
    }

    document.addEventListener('DOMContentLoaded', initDashboard);
</script>