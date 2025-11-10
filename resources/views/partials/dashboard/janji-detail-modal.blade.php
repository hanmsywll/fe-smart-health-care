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