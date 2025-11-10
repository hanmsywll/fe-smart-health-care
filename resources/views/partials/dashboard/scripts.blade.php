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