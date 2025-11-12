<aside id="sidebar" class="fixed top-0 left-0 h-screen bg-white border-r border-gray-200 sidebar-transition z-40 w-64">
    <div class="flex flex-col h-full">
        <!-- Logo -->
        <div class="flex items-center justify-between p-4 border-b border-gray-200">
            <div class="flex items-center gap-2">
                <div
                    class="w-10 h-10 bg-gradient-to-br from-emerald-500 to-cyan-500 rounded-lg flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z">
                        </path>
                    </svg>
                </div>
                <span id="logoText" class="text-lg font-bold gradient-text whitespace-nowrap">Smart Health</span>
            </div>
        </div>

        <!-- Navigation -->
        <nav class="flex-1 overflow-y-auto p-4">
            <ul class="space-y-2">
                <li>
                    <a href="/dashboard"
                        class="flex items-center gap-3 px-4 py-3 rounded-lg {{ isset($active) && $active === 'dashboard' ? 'bg-gradient-to-r from-emerald-500 to-cyan-500 text-white' : 'text-gray-700 hover:bg-gray-100 transition' }}">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
                            </path>
                        </svg>
                        <span class="sidebar-text">Dashboard</span>
                    </a>
                </li>
                <li>
                    <a href="/ketersediaan"
                        class="flex items-center gap-3 px-4 py-3 rounded-lg {{ isset($active) && $active === 'ketersediaan' ? 'bg-gradient-to-r from-emerald-500 to-cyan-500 text-white' : 'text-gray-700 hover:bg-gray-100 transition' }}">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                            </path>
                        </svg>
                        <span class="sidebar-text">Janji Temu</span>
                    </a>
                </li>
                <li>
                    <a href="/rekam-medis"
                        class="flex items-center gap-3 px-4 py-3 rounded-lg text-gray-700 hover:bg-gray-100 transition">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                            </path>
                        </svg>
                        <span class="sidebar-text">Rekam Medis</span>
                    </a>
                </li>
                <li>
                    <a href="/resep"
                        class="flex items-center gap-3 px-4 py-3 rounded-lg {{ isset($active) && $active === 'resep' ? 'bg-gradient-to-r from-emerald-500 to-cyan-500 text-white' : 'text-gray-700 hover:bg-gray-100 transition' }}">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                            </path>
                        </svg>
                        <span class="sidebar-text">Resep Obat</span>
                    </a>
                </li>
            </ul>
        </nav>

        <!-- User Profile moved to topbar -->
    </div>
</aside>
