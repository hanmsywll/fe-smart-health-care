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
                        <label class="block text-sm text-gray-600 mb-1">Waktu Selesai (otomatis)</label>
                        <input id="editSelesai" type="time" disabled class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm bg-gray-100 text-gray-700 cursor-not-allowed" />
                        <p class="mt-1 text-xs text-gray-500">Diisi otomatis +1 jam dari waktu mulai</p>
                    </div>
                </div>
                <div>
                    <label class="block text-sm text-gray-600 mb-1">Keluhan (opsional)</label>
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