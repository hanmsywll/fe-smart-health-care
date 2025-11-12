<!-- Edit Janji Modal -->
<div id="editJanjiModal" class="fixed inset-0 bg-black bg-opacity-40 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <!-- Kontainer Modal dengan Scroll -->
        <div class="bg-white rounded-xl shadow-lg w-full max-w-xl max-h-[90vh] overflow-y-auto">
            
            <!-- Header Modal (Sticky) -->
            <div class="flex items-center justify-between p-4 border-b border-gray-100 sticky top-0 bg-white z-10">
                <h3 class="font-semibold text-gray-900">Edit Janji Temu</h3>
                <button onclick="closeEditJanjiModal()" class="p-2 rounded-lg hover:bg-gray-100">
                    <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            
            <!-- Body Modal -->
            <div class="p-4 space-y-4">
                <input type="hidden" id="editJanjiId" />
                <input type="hidden" id="editCurrentStatus" />
                
                <!-- === BAGIAN EDIT JADWAL (Untuk Pasien & Dokter) === -->
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
                        <p class="mt-1 text-xs text-gray-500">Otomatis +1 jam dari waktu mulai</p>
                    </div>
                </div>
                
                <div>
                    <label class="block text-sm text-gray-600 mb-1">Keluhan</label>
                    <textarea id="editCatatan" rows="3" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-200"></textarea>
                    <p id="keluhanReadOnlyText" class="hidden mt-1 text-xs text-blue-600">Keluhan hanya dapat diubah oleh pasien.</p>
                </div>
                <!-- === AKHIR BAGIAN EDIT JADWAL === -->

                
                <!-- === BAGIAN KHUSUS DOKTER (Rekam Medis & Assign) === -->
                <div id="dokterSection" class="hidden space-y-4 pt-4 border-t border-gray-200">
                    
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-3">
                        <p class="text-sm text-blue-800">
                            <strong>Panel Dokter:</strong> Anda dapat mendelegasikan janji, atau mengisi rekam medis untuk menyelesaikan janji.
                        </p>
                    </div>

                    <!-- Assign ke Dokter lain -->
                    <div id="assignDoctorContainer">
                        <label class="block text-sm text-gray-600 mb-1">1. Delegasikan ke Dokter Lain</label>
                        <select id="assignDoctorSelect" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-200">
                            <option value="">— Tidak mengubah/mendelegasikan dokter —</option>
                        </select>
                        <p class="mt-1 text-xs text-gray-500">Pilih dokter lain jika Anda tidak bisa hadir.</p>
                    </div>

                    <!-- Form Rekam Medis -->
                    <div class="border border-gray-200 rounded-lg p-4 space-y-3">
                        <h4 class="font-semibold text-gray-900 mb-2">2. Input Rekam Medis & Selesaikan Janji</h4>
                        
                        <!-- Status indicator jika rekam medis sudah ada -->
                        <div id="rekamMedisExist" class="hidden bg-green-50 border border-green-200 rounded-lg p-3">
                            <p class="text-sm text-green-800">
                                ✅ <strong>Rekam medis sudah tersedia</strong> untuk janji temu ini. Anda dapat mengeditnya di bawah.
                            </p>
                        </div>

                        <div>
                            <label class="block text-sm text-gray-600 mb-1">Diagnosis <span class="text-red-500">*</span></label>
                            <textarea id="inputDiagnosis" rows="2" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-200" placeholder="Contoh: Hipertensi stadium 1"></textarea>
                        </div>
                        
                        <div>
                            <label class="block text-sm text-gray-600 mb-1">Tindakan <span class="text-red-500">*</span></label>
                            <textarea id="inputTindakan" rows="2" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-200" placeholder="Contoh: Pemberian obat dan edukasi diet"></textarea>
                        </div>
                        
                        <div>
                            <label class="block text-sm text-gray-600 mb-1">Catatan Medis (opsional)</label>
                            <textarea id="inputCatatanMedis" rows="2" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-200" placeholder="Contoh: Kontrol 2 minggu lagi"></textarea>
                        </div>
                    </div>
                    
                    <!-- Checkbox untuk konfirmasi selesai -->
                    <div class="flex items-start gap-2">
                        <input type="checkbox" id="checkboxSelesai" class="mt-1 w-4 h-4 text-emerald-600 border-gray-300 rounded focus:ring-emerald-500" />
                        <label for="checkboxSelesai" class="text-sm text-gray-700">
                            Saya sudah mengisi rekam medis (Diagnosis & Tindakan) dan ingin menandai janji temu ini sebagai <strong>Selesai</strong>.
                        </label>
                    </div>

                </div>
                <!-- === AKHIR BAGIAN KHUSUS DOKTER === -->

            </div>
            
            <!-- Footer Modal (Sticky) -->
            <div class="flex items-center justify-end gap-2 p-4 border-t border-gray-100 sticky bottom-0 bg-white z-10">
                <button onclick="closeEditJanjiModal()" class="px-4 py-2 rounded-lg border border-gray-200 text-gray-700 hover:bg-gray-50">Batal</button>
                <button onclick="submitEditJanji()" class="px-4 py-2 rounded-lg bg-emerald-600 text-white hover:bg-emerald-700">Simpan</button>
            </div>

        </div>
    </div>
</div>