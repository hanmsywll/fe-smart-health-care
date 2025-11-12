<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Rekam Medis - Smart Health</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: #f5f5f5;
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar */
        .sidebar {
            width: 290px;
            background-color: #fff;
            padding: 20px;
            box-shadow: 2px 0 5px rgba(0,0,0,0.05);
            position: fixed;
            height: 100vh;
            left: 0;
            top: 0;
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 40px;
            padding: 10px 0;
        }

        .logo-icon {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, #06d6a0 0%, #1cc5a0 100%);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 24px;
        }

        .logo-text {
            font-size: 22px;
            font-weight: 700;
            color: #06d6a0;
        }

        .menu {
            list-style: none;
        }

        .menu-item {
            margin-bottom: 8px;
        }

        .menu-link {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 14px 20px;
            text-decoration: none;
            color: #6b7280;
            border-radius: 12px;
            font-size: 15px;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .menu-link:hover {
            background-color: #f0fdfa;
            color: #06d6a0;
        }

        .menu-link.active {
            background-color: #06d6a0;
            color: white;
        }

        .menu-icon {
            width: 20px;
            height: 20px;
        }

        /* Main Content */
        .main-content {
            margin-left: 290px;
            flex: 1;
            padding: 0;
        }

        /* Header */
        .header {
            background-color: #fff;
            padding: 20px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .header-left {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .menu-toggle {
            font-size: 24px;
            color: #6b7280;
            cursor: pointer;
            background: none;
            border: none;
        }

        .header-title h1 {
            font-size: 28px;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 4px;
        }

        .header-subtitle {
            font-size: 14px;
            color: #9ca3af;
        }

        .header-right {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .user-avatar {
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, #06d6a0 0%, #1cc5a0 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 20px;
            font-weight: 600;
        }

        .user-info {
            text-align: right;
        }

        .user-email {
            font-size: 14px;
            font-weight: 600;
            color: #1f2937;
        }

        .user-role {
            font-size: 12px;
            color: #9ca3af;
        }

        .logout-btn {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 8px 16px;
            background: none;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            color: #ef4444;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .logout-btn:hover {
            background-color: #fef2f2;
            border-color: #ef4444;
        }

        /* Content Area */
        .content-area {
            padding: 30px 40px;
        }

        .page-header {
            margin-bottom: 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .page-title-section h2 {
            font-size: 24px;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 8px;
        }

        .page-description {
            font-size: 14px;
            color: #6b7280;
        }

        /* Add Record Button */
        .add-record-btn {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 12px 24px;
            background: linear-gradient(135deg, #06d6a0 0%, #1cc5a0 100%);
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 2px 8px rgba(6, 214, 160, 0.3);
        }

        .add-record-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(6, 214, 160, 0.4);
        }

        /* Medical Records Grid */
        .records-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 24px;
        }

        .record-card {
            background: white;
            border-radius: 16px;
            padding: 24px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
        }

        .record-card:hover {
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            transform: translateY(-2px);
        }

        .record-header {
            display: flex;
            align-items: center;
            gap: 16px;
            margin-bottom: 20px;
        }

        .patient-avatar {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, #06d6a0 0%, #1cc5a0 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 24px;
        }

        .patient-info h3 {
            font-size: 18px;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 4px;
        }

        .patient-id {
            font-size: 14px;
            color: #06d6a0;
            font-weight: 500;
        }

        .record-details {
            display: flex;
            flex-direction: column;
            gap: 12px;
            margin-bottom: 20px;
        }

        .detail-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 0;
            border-bottom: 1px solid #f3f4f6;
        }

        .detail-label {
            font-size: 13px;
            color: #6b7280;
            font-weight: 500;
        }

        .detail-value {
            font-size: 14px;
            color: #1f2937;
            font-weight: 600;
        }

        .view-btn {
            width: 100%;
            padding: 12px;
            background: linear-gradient(135deg, #06d6a0 0%, #1cc5a0 100%);
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .view-btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(6, 214, 160, 0.4);
        }

        /* Loading */
        .loading {
            text-align: center;
            padding: 60px 20px;
            color: #6b7280;
        }

        .loading-spinner {
            width: 50px;
            height: 50px;
            border: 4px solid #f3f4f6;
            border-top-color: #06d6a0;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin: 0 auto 16px;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #9ca3af;
        }

        .empty-state-icon {
            font-size: 64px;
            margin-bottom: 16px;
        }

        .empty-state-text {
            font-size: 16px;
            font-weight: 500;
        }

        /* Modal/Popup */
        .modal-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 2000;
            animation: fadeIn 0.3s ease;
        }

        .modal-overlay.active {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        .modal {
            background: white;
            border-radius: 16px;
            width: 90%;
            max-width: 600px;
            max-height: 90vh;
            overflow-y: auto;
            animation: slideUp 0.3s ease;
        }

        @keyframes slideUp {
            from { 
                opacity: 0;
                transform: translateY(20px);
            }
            to { 
                opacity: 1;
                transform: translateY(0);
            }
        }

        .modal-header {
            padding: 24px 30px;
            border-bottom: 1px solid #e5e7eb;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .modal-title {
            font-size: 20px;
            font-weight: 700;
            color: #1f2937;
        }

        .modal-close {
            background: none;
            border: none;
            font-size: 28px;
            color: #9ca3af;
            cursor: pointer;
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .modal-close:hover {
            background-color: #f3f4f6;
            color: #1f2937;
        }

        .modal-body {
            padding: 30px;
        }

        .modal-info-group {
            margin-bottom: 24px;
        }

        .modal-label {
            font-size: 13px;
            font-weight: 600;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 8px;
            display: block;
        }

        .modal-value {
            font-size: 15px;
            color: #1f2937;
            line-height: 1.6;
            padding: 12px;
            background-color: #f9fafb;
            border-radius: 8px;
            border: 1px solid #e5e7eb;
        }

        .modal-value.textarea {
            min-height: 80px;
            white-space: pre-wrap;
        }

        .patient-header {
            display: flex;
            align-items: center;
            gap: 16px;
            padding: 20px;
            background: linear-gradient(135deg, #f0fdfa 0%, #e6f9f5 100%);
            border-radius: 12px;
            margin-bottom: 24px;
        }

        .patient-header-avatar {
            width: 64px;
            height: 64px;
            background: linear-gradient(135deg, #06d6a0 0%, #1cc5a0 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 28px;
        }

        .patient-header-info h3 {
            font-size: 18px;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 4px;
        }

        .patient-header-id {
            font-size: 14px;
            color: #06d6a0;
            font-weight: 600;
        }

        .modal-footer {
            padding: 20px 30px;
            border-top: 1px solid #e5e7eb;
            display: flex;
            justify-content: flex-end;
            gap: 12px;
        }

        .btn-secondary {
            padding: 10px 20px;
            background-color: #f3f4f6;
            color: #6b7280;
            border: none;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-secondary:hover {
            background-color: #e5e7eb;
            color: #1f2937;
        }

        .btn-primary {
            padding: 10px 20px;
            background: linear-gradient(135deg, #06d6a0 0%, #1cc5a0 100%);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(6, 214, 160, 0.4);
        }

        /* Form Styles */
        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            font-size: 14px;
            font-weight: 600;
            color: #374151;
            margin-bottom: 8px;
            display: block;
        }

        .form-label .required {
            color: #ef4444;
        }

        .form-input, .form-select, .form-textarea {
            width: 100%;
            padding: 12px;
            font-size: 14px;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            transition: all 0.3s ease;
            font-family: 'Inter', sans-serif;
        }

        .form-input:focus, .form-select:focus, .form-textarea:focus {
            outline: none;
            border-color: #06d6a0;
            box-shadow: 0 0 0 3px rgba(6, 214, 160, 0.1);
        }

        .form-textarea {
            min-height: 100px;
            resize: vertical;
        }

        /* Alert Messages */
        .alert {
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 14px;
            font-weight: 500;
        }

        .alert-success {
            background-color: #d1fae5;
            color: #065f46;
            border: 1px solid #6ee7b7;
        }

        .alert-error {
            background-color: #fee2e2;
            color: #991b1b;
            border: 1px solid #fca5a5;
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="logo">
            <div class="logo-icon">❤️</div>
            <span class="logo-text">Smart Health</span>
        </div>
        
        <ul class="menu">
            <li class="menu-item">
                <a href="/dashboard" class="menu-link">
                    <svg class="menu-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                    Dashboard
                </a>
            </li>
            <li class="menu-item">
                <a href="/ketersediaan" class="menu-link">
                    <svg class="menu-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    Janji Temu
                </a>
            </li>
            <li class="menu-item">
                <a href="/rekam-medis" class="menu-link active">
                    <svg class="menu-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Rekam Medis
                </a>
            </li>
            <li class="menu-item">
                <a href="/resep-obat" class="menu-link">
                    <svg class="menu-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path>
                    </svg>
                    Resep Obat
                </a>
            </li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Header -->
        <div class="header">
            <div class="header-left">
                <button class="menu-toggle">☰</button>
                <div class="header-title">
                    <h1>Rekam Medis</h1>
                    <p class="header-subtitle">Kelola dan lihat rekam medis pasien</p>
                </div>
            </div>
            <div class="header-right">
                <div class="user-avatar">T</div>
                <div class="user-info">
                    <div class="user-email">tes1234@gmail.com</div>
                    <div class="user-role">dokter</div>
                </div>
                <button class="logout-btn">
                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                    </svg>
                    Logout
                </button>
            </div>
        </div>

        <!-- Content Area -->
        <div class="content-area">
            <div class="page-header">
                <div class="page-title-section">
                    <h2>Daftar Rekam Medis Pasien</h2>
                    <p class="page-description">Klik pada kartu untuk melihat detail rekam medis</p>
                </div>
                <button class="add-record-btn" onclick="openAddModal()">
                    <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Tambah Rekam Medis
                </button>
            </div>

            <!-- Loading State -->
            <div id="loadingState" class="loading">
                <div class="loading-spinner"></div>
                <p>Memuat data rekam medis...</p>
            </div>

            <!-- Records Grid -->
            <div id="recordsGrid" class="records-grid" style="display: none;">
                <!-- Data will be loaded here via JavaScript -->
            </div>

            <!-- Empty State -->
            <div id="emptyState" class="empty-state" style="display: none;">
                <div class="empty-state-icon">📋</div>
                <p class="empty-state-text">Belum ada rekam medis</p>
            </div>
        </div>
    </div>

    <!-- Detail Modal -->
    <div id="detailModal" class="modal-overlay">
        <div class="modal">
            <div class="modal-header">
                <h2 class="modal-title">Detail Rekam Medis</h2>
                <button class="modal-close" onclick="closeDetailModal()">&times;</button>
            </div>
            <div class="modal-body">
                <div class="patient-header">
                    <div class="patient-header-avatar">👤</div>
                    <div class="patient-header-info">
                        <h3 id="detailPatientName">-</h3>
                        <p class="patient-header-id" id="detailRecordId">-</p>
                    </div>
                </div>

                <div class="modal-info-group">
                    <label class="modal-label">Tanggal Kunjungan</label>
                    <div class="modal-value" id="detailTanggal">-</div>
                </div>

                <div class="modal-info-group">
                    <label class="modal-label">Dokter Pemeriksa</label>
                    <div class="modal-value" id="detailDokter">-</div>
                </div>

                <div class="modal-info-group">
                    <label class="modal-label">Diagnosis</label>
                    <div class="modal-value textarea" id="detailDiagnosis">-</div>
                </div>

                <div class="modal-info-group">
                    <label class="modal-label">Tindakan</label>
                    <div class="modal-value textarea" id="detailTindakan">-</div>
                </div>

                <div class="modal-info-group">
                    <label class="modal-label">Catatan</label>
                    <div class="modal-value textarea" id="detailCatatan">-</div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn-secondary" onclick="closeDetailModal()">Tutup</button>
            </div>
        </div>
    </div>

    <!-- Add Modal -->
    <div id="addModal" class="modal-overlay">
        <div class="modal">
            <div class="modal-header">
                <h2 class="modal-title">Tambah Rekam Medis Baru</h2>
                <button class="modal-close" onclick="closeAddModal()">&times;</button>
            </div>
            <form id="addRecordForm" onsubmit="handleSubmit(event)">
                <div class="modal-body">
                    <div id="formAlert"></div>

                    <div class="form-group">
                        <label class="form-label">Pasien <span class="required">*</span></label>
                        <select class="form-select" name="id_pasien" id="id_pasien" required>
                            <option value="">Pilih Pasien</option>
                            <!-- Options will be loaded via API -->
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Dokter <span class="required">*</span></label>
                        <select class="form-select" name="id_dokter" id="id_dokter" required>
                            <option value="">Pilih Dokter</option>
                            <!-- Options will be loaded via API -->
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Janji Temu (Opsional)</label>
                        <select class="form-select" name="id_janji_temu" id="id_janji_temu">
                            <option value="">Tidak Ada Janji Temu</option>
                            <!-- Options will be loaded via API -->
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Tanggal Kunjungan <span class="required">*</span></label>
                        <input type="date" class="form-input" name="tanggal_kunjungan" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Diagnosis</label>
                        <textarea class="form-textarea" name="diagnosis" placeholder="Masukkan diagnosis pasien..."></textarea>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Tindakan</label>
                        <textarea class="form-textarea" name="tindakan" placeholder="Masukkan tindakan yang dilakukan..."></textarea>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Catatan</label>
                        <textarea class="form-textarea" name="catatan" placeholder="Masukkan catatan tambahan..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-secondary" onclick="closeAddModal()">Batal</button>
                    <button type="submit" class="btn-primary" id="submitBtn">Simpan Rekam Medis</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Backend base URL (set BACKEND_API_URL in your .env or Railway environment variables)
        // If empty, requests will be relative (same origin)
        const BACKEND_BASE = "{{ rtrim(env('BACKEND_API_URL', ''), '/') }}";

        // Helper to build full API URL. Pass a path starting with '/'
        function api(path) {
            if (!path.startsWith('/')) path = '/' + path;
            return (BACKEND_BASE && BACKEND_BASE.length) ? BACKEND_BASE + path : path;
        }

        // API path for rekam-medis endpoints
        const API_BASE_PATH = '/rekam-medis';

        // Get CSRF Token
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        // Get Auth Token from localStorage or session — prefer access_token used by other pages
        const authToken = localStorage.getItem('access_token') || localStorage.getItem('auth_token') || localStorage.getItem('token') || '';

        // Load Data on Page Load
        document.addEventListener('DOMContentLoaded', function() {
            loadRekamMedis();
            // Dropdown options will be loaded when modal opens
        });

        // Load Rekam Medis from API
        async function loadRekamMedis() {
            try {
                const headers = {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                };

                // Add auth token if available
                if (authToken) {
                    headers['Authorization'] = `Bearer ${authToken}`;
                }

                const response = await fetch(api(API_BASE_PATH), { headers });
                
                if (!response.ok) {
                    throw new Error('Failed to fetch data');
                }

                const result = await response.json();
                
                // Handle response - could be array or object with data property
                const data = Array.isArray(result) ? result : (result.data || []);
                
                // Hide loading state
                document.getElementById('loadingState').style.display = 'none';
                
                if (data.length === 0) {
                    document.getElementById('emptyState').style.display = 'block';
                } else {
                    displayRekamMedis(data);
                }
            } catch (error) {
                console.error('Error loading rekam medis:', error);
                document.getElementById('loadingState').style.display = 'none';
                document.getElementById('emptyState').style.display = 'block';
            }
        }

        // Display Rekam Medis Cards
        function displayRekamMedis(records) {
            const grid = document.getElementById('recordsGrid');
            grid.style.display = 'grid';
            grid.innerHTML = '';

            records.forEach(record => {
                const card = createRecordCard(record);
                grid.appendChild(card);
            });
        }

        // Create Record Card
        function createRecordCard(record) {
            const card = document.createElement('div');
            card.className = 'record-card';
            
            const patientName = record.pasien?.nama || 'Nama Pasien';
            const doctorName = record.dokter?.nama || 'Nama Dokter';
            const diagnosis = record.diagnosis || '-';
            const tanggal = formatDate(record.tanggal_kunjungan);
            
            card.innerHTML = `
                <div class="record-header">
                    <div class="patient-avatar">👤</div>
                    <div class="patient-info">
                        <h3>${patientName}</h3>
                        <p class="patient-id">ID: RM-${String(record.id_rekam_medis).padStart(3, '0')}</p>
                    </div>
                </div>
                <div class="record-details">
                    <div class="detail-item">
                        <span class="detail-label">Tanggal Kunjungan</span>
                        <span class="detail-value">${tanggal}</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Diagnosa</span>
                        <span class="detail-value">${diagnosis.substring(0, 30)}${diagnosis.length > 30 ? '...' : ''}</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Dokter</span>
                        <span class="detail-value">${doctorName}</span>
                    </div>
                </div>
                <button class="view-btn" onclick="viewDetail(${record.id_rekam_medis})">Lihat Detail Rekam Medis</button>
            `;
            
            return card;
        }

        // Format Date
        function formatDate(dateString) {
            if (!dateString) return '-';
            const date = new Date(dateString);
            const options = { day: 'numeric', month: 'short', year: 'numeric' };
            return date.toLocaleDateString('id-ID', options);
        }

        // View Detail
        async function viewDetail(id) {
            try {
                const headers = {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                };

                if (authToken) {
                    headers['Authorization'] = `Bearer ${authToken}`;
                }

                const response = await fetch(api(`${API_BASE_PATH}/${id}`), { headers });
                
                if (!response.ok) {
                    throw new Error('Failed to fetch detail');
                }

                const result = await response.json();
                const record = result.data || result;
                
                // Populate modal
                document.getElementById('detailPatientName').textContent = record.pasien?.nama || record.nama_pasien || 'Nama Pasien';
                document.getElementById('detailRecordId').textContent = `ID: RM-${String(record.id_rekam_medis || record.id).padStart(3, '0')}`;
                document.getElementById('detailTanggal').textContent = formatDate(record.tanggal_kunjungan);
                document.getElementById('detailDokter').textContent = record.dokter?.nama || record.nama_dokter || '-';
                document.getElementById('detailDiagnosis').textContent = record.diagnosis || '-';
                document.getElementById('detailTindakan').textContent = record.tindakan || '-';
                document.getElementById('detailCatatan').textContent = record.catatan || '-';
                
                // Open modal
                document.getElementById('detailModal').classList.add('active');
                document.body.style.overflow = 'hidden';
            } catch (error) {
                console.error('Error fetching detail:', error);
                alert('Gagal memuat detail rekam medis');
            }
        }

        // Close Detail Modal
        function closeDetailModal() {
            document.getElementById('detailModal').classList.remove('active');
            document.body.style.overflow = 'auto';
        }

        // Open Add Modal
        function openAddModal() {
            loadDropdownOptions();
            document.getElementById('addModal').classList.add('active');
            document.body.style.overflow = 'hidden';
        }

        // Close Add Modal
        function closeAddModal() {
            document.getElementById('addModal').classList.remove('active');
            document.getElementById('addRecordForm').reset();
            document.getElementById('formAlert').innerHTML = '';
            document.body.style.overflow = 'auto';
        }

        // Load Dropdown Options
        async function loadDropdownOptions() {
            try {
                const headers = {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                };

                if (authToken) {
                    headers['Authorization'] = `Bearer ${authToken}`;
                }

                // Load Janji Temu (untuk mendapatkan data pasien dan dokter)
                const janjiTemuResponse = await fetch(api('/api/janji'), { 
                    headers 
                });
                
                if (janjiTemuResponse.ok) {
                    const janjiResult = await janjiTemuResponse.json();
                    const janjiTemuData = janjiResult.data || janjiResult;
                    
                    // Extract unique pasien dan dokter dari janji temu
                    const pasienMap = new Map();
                    const dokterMap = new Map();
                    const janjiTemuSelect = document.getElementById('id_janji_temu');
                    
                    // Clear existing options except first
                    janjiTemuSelect.innerHTML = '<option value="">Tidak Ada Janji Temu</option>';
                    
                    if (Array.isArray(janjiTemuData)) {
                        janjiTemuData.forEach(janji => {
                            // Add to janji temu dropdown
                            const option = document.createElement('option');
                            option.value = janji.id_janji_temu || janji.id;
                            const tanggal = formatDate(janji.tanggal_janji || janji.tanggal);
                            option.textContent = `Janji Temu #${janji.id_janji_temu || janji.id} - ${tanggal}`;
                            janjiTemuSelect.appendChild(option);
                            
                            // Collect unique pasien
                            if (janji.pasien || janji.id_pasien) {
                                const pasienId = janji.id_pasien;
                                const pasienNama = janji.pasien?.nama || janji.nama_pasien || `Pasien #${pasienId}`;
                                if (!pasienMap.has(pasienId)) {
                                    pasienMap.set(pasienId, pasienNama);
                                }
                            }
                            
                            // Collect unique dokter
                            if (janji.dokter || janji.id_dokter) {
                                const dokterId = janji.id_dokter;
                                const dokterNama = janji.dokter?.nama || janji.nama_dokter || `Dokter #${dokterId}`;
                                if (!dokterMap.has(dokterId)) {
                                    dokterMap.set(dokterId, dokterNama);
                                }
                            }
                        });
                    }
                    
                    // Populate Pasien dropdown
                    const pasienSelect = document.getElementById('id_pasien');
                    pasienSelect.innerHTML = '<option value="">Pilih Pasien</option>';
                    pasienMap.forEach((nama, id) => {
                        const option = document.createElement('option');
                        option.value = id;
                        option.textContent = nama;
                        pasienSelect.appendChild(option);
                    });
                    
                    // Populate Dokter dropdown
                    const dokterSelect = document.getElementById('id_dokter');
                    dokterSelect.innerHTML = '<option value="">Pilih Dokter</option>';
                    dokterMap.forEach((nama, id) => {
                        const option = document.createElement('option');
                        option.value = id;
                        option.textContent = nama;
                        dokterSelect.appendChild(option);
                    });
                }
                
                // If no data from janji temu, add manual options
                if (document.getElementById('id_pasien').options.length === 1) {
                    // Add sample data if API doesn't return anything
                    const pasienSelect = document.getElementById('id_pasien');
                    pasienSelect.innerHTML = `
                        <option value="">Pilih Pasien</option>
                        <option value="1">Budi Santoso</option>
                        <option value="2">Siti Aminah</option>
                        <option value="3">Ahmad Wijaya</option>
                    `;
                }
                
                if (document.getElementById('id_dokter').options.length === 1) {
                    const dokterSelect = document.getElementById('id_dokter');
                    dokterSelect.innerHTML = `
                        <option value="">Pilih Dokter</option>
                        <option value="1">Dr. Raihan Strange</option>
                        <option value="2">Dr. Raihan Wong</option>
                        <option value="3">Dr. Raihan Loki</option>
                    `;
                }
                
            } catch (error) {
                console.error('Error loading dropdown options:', error);
                // Load default options on error
                const pasienSelect = document.getElementById('id_pasien');
                pasienSelect.innerHTML = `
                    <option value="">Pilih Pasien</option>
                    <option value="1">Budi Santoso</option>
                    <option value="2">Siti Aminah</option>
                    <option value="3">Ahmad Wijaya</option>
                `;
                
                const dokterSelect = document.getElementById('id_dokter');
                dokterSelect.innerHTML = `
                    <option value="">Pilih Dokter</option>
                    <option value="1">Dr. Raihan Strange</option>
                    <option value="2">Dr. Raihan Wong</option>
                    <option value="3">Dr. Raihan Loki</option>
                `;
            }
        }

        // Handle Form Submit
        async function handleSubmit(event) {
            event.preventDefault();
            
            const submitBtn = document.getElementById('submitBtn');
            const formAlert = document.getElementById('formAlert');
            
            // Disable button
            submitBtn.disabled = true;
            submitBtn.textContent = 'Menyimpan...';
            
            // Clear previous alerts
            formAlert.innerHTML = '';
            
            // Get form data
            const formData = new FormData(event.target);
            const data = {};
            
            formData.forEach((value, key) => {
                if (value) {
                    data[key] = value;
                }
            });
            
            try {
                const headers = {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                };

                if (authToken) {
                    headers['Authorization'] = `Bearer ${authToken}`;
                }

                const response = await fetch(api(API_BASE_PATH), {
                    method: 'POST',
                    headers: headers,
                    body: JSON.stringify(data)
                });
                
                const result = await response.json();
                
                if (response.ok) {
                    // Success
                    formAlert.innerHTML = '<div class="alert alert-success">Rekam medis berhasil ditambahkan!</div>';
                    
                    // Reset form
                    event.target.reset();
                    
                    // Reload data
                    await loadRekamMedis();
                    
                    // Close modal after 1.5 seconds
                    setTimeout(() => {
                        closeAddModal();
                    }, 1500);
                } else {
                    // Error
                    let errorMessage = 'Gagal menyimpan rekam medis. ';
                    if (result.errors) {
                        errorMessage += Object.values(result.errors).flat().join(', ');
                    } else if (result.message) {
                        errorMessage += result.message;
                    }
                    formAlert.innerHTML = `<div class="alert alert-error">${errorMessage}</div>`;
                }
            } catch (error) {
                console.error('Error submitting form:', error);
                formAlert.innerHTML = '<div class="alert alert-error">Terjadi kesalahan saat menyimpan data.</div>';
            } finally {
                // Re-enable button
                submitBtn.disabled = false;
                submitBtn.textContent = 'Simpan Rekam Medis';
            }
        }

        // Close modal when clicking outside
        document.getElementById('detailModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeDetailModal();
            }
        });

        document.getElementById('addModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeAddModal();
            }
        });

        // Close modal with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeDetailModal();
                closeAddModal();
            }
        });
    </script>
</body>
</html>