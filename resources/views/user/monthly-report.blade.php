@extends('layouts.master-blank')

@section('content')
@section('css')
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Fraunces:wght@500;700&family=Manrope:wght@400;500;600;700&display=swap');

        :root {
            --ink: #14121a;
            --muted: #6f7285;
            --primary: #1f6feb;
            --primary-soft: rgba(31, 111, 235, 0.12);
            --accent: #f4b942;
            --accent-soft: rgba(244, 185, 66, 0.18);
            --danger: #e5484d;
            --surface: #ffffff;
            --surface-alt: #f7f5fb;
            --border: #e6e3ef;
        }

        body {
            background: radial-gradient(circle at 10% 10%, #fff4d8 0%, transparent 40%),
                radial-gradient(circle at 80% 0%, #dce8ff 0%, transparent 45%),
                linear-gradient(180deg, #f3f4fb 0%, #f9f7ff 100%);
            color: var(--ink);
            font-family: "Manrope", system-ui, -apple-system, sans-serif;
        }

        .user-app-navbar {
            background: #1A6F6F;
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
        }

        .user-app-navbar-inner {
            max-width: 1120px;
            margin: 0 auto;
            padding: 12px 16px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 14px;
            flex-wrap: wrap;
        }

        .user-app-brand {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
            color: #fff;
            font-weight: 700;
            font-size: 18px;
        }

        .user-app-brand img {
            width: 26px;
            height: 26px;
            object-fit: contain;
        }

        .user-app-menu {
            display: flex;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
        }

        .user-app-menu a {
            color: rgba(255, 255, 255, 0.9);
            text-decoration: none;
            font-size: 13px;
            font-weight: 600;
            padding: 5px 10px;
            border-radius: 999px;
        }

        .user-app-menu a.active,
        .user-app-menu a:hover {
            background: rgba(255, 255, 255, 0.2);
            color: #fff;
        }

        .user-app-logout {
            border: 1px solid rgba(255, 255, 255, 0.6);
            background: transparent;
            color: #fff;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 700;
            padding: 6px 12px;
        }

        .attendance-page {
            min-height: 100vh;
            padding: 24px 16px 48px;
            position: relative;
            overflow: hidden;
        }

        .attendance-page::before,
        .attendance-page::after {
            content: "";
            position: absolute;
            width: 340px;
            height: 340px;
            border-radius: 48% 52% 58% 42%;
            background: rgba(31, 111, 235, 0.08);
            filter: blur(0px);
            z-index: 0;
        }

        .attendance-page::before {
            top: -120px;
            right: -80px;
            transform: rotate(12deg);
        }

        .attendance-page::after {
            bottom: -140px;
            left: -90px;
            background: rgba(244, 185, 66, 0.16);
            transform: rotate(-8deg);
        }

        .attendance-shell {
            max-width: 1120px;
            margin: 0 auto;
            position: relative;
            z-index: 1;
        }

        .attendance-topbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            margin-bottom: 24px;
        }

        .attendance-greeting {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .attendance-greeting span {
            color: var(--muted);
            font-size: 13px;
            letter-spacing: 0.04em;
            text-transform: uppercase;
        }

        .attendance-greeting h1 {
            font-family: "Fraunces", "Manrope", serif;
            font-size: 28px;
            margin: 0;
        }

        .attendance-logout {
            border: 1px solid var(--border);
            background: var(--surface);
            color: var(--ink);
            border-radius: 999px;
            padding: 8px 18px;
            font-weight: 600;
            transition: all 0.2s ease;
        }

        .attendance-logout:hover {
            border-color: var(--primary);
            color: var(--primary);
            box-shadow: 0 8px 20px rgba(31, 111, 235, 0.12);
        }

        .hero-card {
            background: var(--surface);
            border-radius: 24px;
            padding: 28px;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 24px;
            box-shadow: 0 20px 50px rgba(15, 23, 42, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.6);
        }

        .hero-title {
            font-family: "Fraunces", "Manrope", serif;
            font-size: 24px;
            margin-bottom: 6px;
        }

        .hero-subtitle {
            color: var(--muted);
            margin-bottom: 18px;
        }

        .action-card {
            background: var(--surface);
            border-radius: 20px;
            padding: 20px;
            border: 1px solid var(--border);
            box-shadow: 0 14px 30px rgba(15, 23, 42, 0.06);
            margin-top: 22px;
        }

        .action-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 12px;
        }

        .action-title {
            font-weight: 700;
            font-size: 16px;
        }

        .action-status {
            font-size: 12px;
            font-weight: 600;
            padding: 4px 10px;
            border-radius: 999px;
        }

        .action-status.done {
            background: rgba(16, 185, 129, 0.16);
            color: #0f766e;
        }

        .action-status.pending {
            background: rgba(239, 68, 68, 0.12);
            color: var(--danger);
        }

        .action-form label {
            font-size: 12px;
            font-weight: 600;
            color: var(--muted);
            text-transform: uppercase;
            letter-spacing: 0.04em;
        }

        .action-form .form-control {
            border-radius: 12px;
            border: 1px solid var(--border);
            background: #fff;
        }

        .action-button {
            width: 100%;
            border-radius: 12px;
            font-weight: 700;
            padding: 10px 14px;
        }

        .logs-card {
            margin-top: 28px;
            background: var(--surface);
            border-radius: 22px;
            padding: 22px;
            border: 1px solid var(--border);
            box-shadow: 0 16px 32px rgba(15, 23, 42, 0.06);
        }

        .logs-title {
            font-weight: 700;
            font-size: 18px;
            margin-bottom: 14px;
        }

        .logs-table thead th {
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: var(--muted);
            border-top: none;
        }

        .logs-table tbody td {
            vertical-align: middle;
            font-size: 14px;
        }

        .template-list {
            margin-top: 10px;
            display: grid;
            gap: 8px;
        }

        .template-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            padding: 8px 10px;
            border: 1px solid var(--border);
            border-radius: 10px;
            background: #fff;
        }

        .template-item-name {
            font-size: 12px;
            font-weight: 600;
            color: #334155;
        }

        .template-item-actions {
            display: inline-flex;
            gap: 6px;
            flex-shrink: 0;
        }

        .template-item-actions .btn {
            padding: 4px 10px;
            font-size: 12px;
            border-radius: 8px;
        }

        .template-preview-modal {
            position: fixed;
            inset: 0;
            z-index: 9999;
            display: none;
            align-items: center;
            justify-content: center;
            background: rgba(12, 22, 40, 0.35);
            backdrop-filter: blur(6px);
            padding: 18px;
        }

        .template-preview-modal.show {
            display: flex;
        }

        .template-preview-card {
            width: min(980px, 100%);
            height: min(84vh, 760px);
            background: #fff;
            border-radius: 14px;
            overflow: hidden;
            box-shadow: 0 24px 60px rgba(2, 6, 23, 0.35);
            display: flex;
            flex-direction: column;
        }

        .template-preview-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            border-bottom: 1px solid #e5e7eb;
            padding: 10px 14px;
        }

        .template-preview-title {
            font-size: 13px;
            font-weight: 700;
            color: #1f2937;
        }

        .template-preview-frame {
            width: 100%;
            height: 100%;
            border: 0;
            display: none;
        }

        .template-preview-body {
            width: 100%;
            height: 100%;
            overflow: auto;
            background: #f3f4f6;
            padding: 16px;
        }

        .template-pdf-pages {
            display: grid;
            gap: 12px;
            justify-content: center;
        }

        .template-pdf-pages canvas {
            background: #fff;
            box-shadow: 0 4px 18px rgba(15, 23, 42, 0.14);
            max-width: 100%;
            height: auto;
        }

        .template-preview-loading {
            font-size: 13px;
            color: #475569;
        }

        @media (max-width: 768px) {
            .attendance-topbar {
                flex-direction: column;
                align-items: flex-start;
            }
            .attendance-page {
                padding: 16px 12px 28px;
            }
            .hero-card,
            .action-card,
            .logs-card {
                border-radius: 14px;
                padding: 14px;
            }
            .hero-title {
                font-size: 20px;
            }
            .attendance-greeting h1 {
                font-size: 24px;
            }
            .template-item {
                flex-direction: column;
                align-items: flex-start;
            }
            .template-item-actions {
                width: 100%;
            }
            .template-item-actions .btn {
                flex: 1;
                text-align: center;
            }
            .template-preview-modal {
                padding: 8px;
            }
            .template-preview-card {
                height: 88vh;
            }
        }
    </style>
@endsection
@php
    $displayUser = auth()->user();
    if (!function_exists('format_filesize')) {
        function format_filesize($bytes)
        {
            $bytes = (int) $bytes;
            if ($bytes <= 0) {
                return '-';
            }
            $units = ['B', 'KB', 'MB', 'GB'];
            $i = 0;
            while ($bytes >= 1024 && $i < count($units) - 1) {
                $bytes /= 1024;
                $i++;
            }
            return number_format($bytes, 2) . ' ' . $units[$i];
        }
    }
@endphp
    @include('layouts.user-navbar', ['active' => 'monthly'])
    <div class="attendance-page">
        <div class="attendance-shell">
            <div class="attendance-topbar">
                <div class="attendance-greeting">
                    <span>Selamat datang kembali</span>
                    <h1>{{ $displayUser->name }}</h1>
                </div>
            </div>

            <div class="hero-card">
                <div>
                    <div class="hero-title">Laporan Bulanan</div>
                    <div class="hero-subtitle">Unggah dokumen laporan bulanan per bulan.</div>
                    @if ($errors->any())
                        <div class="alert alert-danger mt-3">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>
                <div>
                    <div class="text-muted">Maksimal 5MB, format PDF.</div>
                    @if ($latestTemplate)
                        <a class="btn btn-outline-info action-button mt-2" href="{{ route('user.template.download', $latestTemplate->id) }}">
                            Unduh Format Laporan Magang (SC)
                        </a>
                    @else
                        <button type="button" class="btn btn-outline-secondary action-button mt-2" disabled>
                            Format dari admin belum tersedia
                        </button>
                    @endif
                    @if (($templateDocuments ?? collect())->isNotEmpty())
                        <div class="template-list">
                            @foreach ($templateDocuments as $doc)
                                <div class="template-item">
                                    <div class="template-item-name">{{ $doc->title }}</div>
                                    <div class="template-item-actions">
                                        <button type="button" class="btn btn-light btn-template-preview"
                                            data-title="{{ $doc->title }}"
                                            data-preview-url="{{ route('user.template.view', $doc->id) }}"
                                            data-mime="{{ $doc->mime_type ?? '' }}">
                                            Lihat
                                        </button>
                                        <a class="btn btn-outline-primary" href="{{ route('user.template.download', $doc->id) }}">Download</a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            <div class="action-card">
                <div class="action-header">
                    <div class="action-title">Unggah Laporan Bulanan</div>
                    <span class="action-status {{ $reports->isEmpty() ? 'pending' : 'done' }}">
                        {{ $reports->isEmpty() ? 'Belum' : 'Tercatat' }}
                    </span>
                </div>
                <form method="POST" action="{{ route('monthly-report.store') }}" class="action-form" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label for="report_month">Bulan Laporan (YYYY-MM)</label>
                        <input type="month" class="form-control" id="report_month" name="report_month" required>
                    </div>
                    <div class="form-group">
                        <label for="monthly_report">Unggah Laporan Bulanan (PDF)</label>
                        <input type="file" class="form-control" id="monthly_report" name="monthly_report" accept=".pdf" required>
                    </div>
                    <button class="btn btn-success action-button" type="submit">
                        Unggah Dokumen
                    </button>
                </form>
            </div>

            <div class="logs-card">
                <div class="logs-title">Riwayat Laporan Bulanan</div>
                @if ($reports->isEmpty())
                    <div class="text-muted">Belum ada histori.</div>
                @else
                    <div class="table-responsive d-none d-md-block">
                        <table class="table table-striped mb-0 logs-table">
                            <thead>
                                <tr>
                                    <th>Bulan</th>
                                    <th>Tanggal</th>
                                    <th>Dokumen</th>
                                    <th>Ukuran</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($reports as $report)
                                    <tr>
                                        <td>{{ $report->report_month }}</td>
                                        <td>{{ optional($report->created_at)->format('d-m-Y H:i') }}</td>
                                        <td>
                                            <a target="_blank" rel="noopener" href="{{ route('monthly-report.view', $report->id) }}">
                                                Lihat
                                            </a>
                                            |
                                            <a href="{{ route('monthly-report.download', $report->id) }}">
                                                {{ $report->file_name }}
                                            </a>
                                        </td>
                                        <td>{{ format_filesize($report->file_size) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="d-md-none">
                        @foreach ($reports as $report)
                            <div class="border rounded p-2 mb-2 bg-white">
                                <div class="font-weight-bold">{{ $report->report_month }}</div>
                                <div class="small text-muted">{{ optional($report->created_at)->format('d-m-Y H:i') }}</div>
                                <div class="small mt-1">Ukuran: {{ format_filesize($report->file_size) }}</div>
                                <div class="small mt-1">
                                    <a target="_blank" rel="noopener" href="{{ route('monthly-report.view', $report->id) }}">Lihat</a>
                                    |
                                    <a href="{{ route('monthly-report.download', $report->id) }}">{{ $report->file_name }}</a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="template-preview-modal" id="template-preview-modal" aria-hidden="true">
        <div class="template-preview-card">
            <div class="template-preview-head">
                <div class="template-preview-title" id="template-preview-title">Preview Dokumen</div>
                <button type="button" class="btn btn-sm btn-danger" id="template-preview-close">Tutup</button>
            </div>
            <div class="template-preview-body" id="template-preview-body">
                <div class="template-preview-loading" id="template-preview-loading">Memuat dokumen...</div>
                <div class="template-pdf-pages" id="template-pdf-pages"></div>
                <iframe id="template-preview-frame" class="template-preview-frame" src=""></iframe>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="https://cdn.jsdelivr.net/npm/pdfjs-dist@4.5.136/build/pdf.min.mjs" type="module"></script>
    <script>
        (async function () {
            var modal = document.getElementById('template-preview-modal');
            var frame = document.getElementById('template-preview-frame');
            var title = document.getElementById('template-preview-title');
            var closeBtn = document.getElementById('template-preview-close');
            var loading = document.getElementById('template-preview-loading');
            var pages = document.getElementById('template-pdf-pages');

            if (!modal || !frame || !title || !closeBtn || !loading || !pages) {
                return;
            }

            var pdfjsLib = null;
            try {
                pdfjsLib = await import('https://cdn.jsdelivr.net/npm/pdfjs-dist@4.5.136/build/pdf.min.mjs');
                pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdn.jsdelivr.net/npm/pdfjs-dist@4.5.136/build/pdf.worker.min.mjs';
            } catch (e) {
                pdfjsLib = null;
            }

            function closePreview() {
                modal.classList.remove('show');
                modal.setAttribute('aria-hidden', 'true');
                frame.setAttribute('src', '');
                frame.style.display = 'none';
                pages.innerHTML = '';
                loading.textContent = 'Memuat dokumen...';
                loading.style.display = 'block';
            }

            document.querySelectorAll('.btn-template-preview').forEach(function (btn) {
                btn.addEventListener('click', async function () {
                    var url = btn.getAttribute('data-preview-url') || '';
                    var mime = (btn.getAttribute('data-mime') || '').toLowerCase();
                    var isPdf = mime.indexOf('pdf') !== -1 || url.toLowerCase().indexOf('.pdf') !== -1;

                    title.textContent = btn.getAttribute('data-title') || 'Preview Dokumen';
                    modal.classList.add('show');
                    modal.setAttribute('aria-hidden', 'false');

                    frame.style.display = 'none';
                    frame.setAttribute('src', '');
                    pages.innerHTML = '';
                    loading.textContent = 'Memuat dokumen...';
                    loading.style.display = 'block';

                    if (isPdf && pdfjsLib) {
                        try {
                            var response = await fetch(url, { credentials: 'same-origin' });
                            var buffer = await response.arrayBuffer();
                            var pdf = await pdfjsLib.getDocument({ data: buffer }).promise;

                            for (var i = 1; i <= pdf.numPages; i++) {
                                var page = await pdf.getPage(i);
                                var viewport = page.getViewport({ scale: 1.3 });
                                var canvas = document.createElement('canvas');
                                var context = canvas.getContext('2d');
                                canvas.width = viewport.width;
                                canvas.height = viewport.height;
                                await page.render({ canvasContext: context, viewport: viewport }).promise;
                                pages.appendChild(canvas);
                            }
                            loading.style.display = 'none';
                        } catch (error) {
                            loading.style.display = 'none';
                            frame.style.display = 'block';
                            frame.setAttribute('src', url + '#toolbar=0&navpanes=0&scrollbar=0');
                        }
                    } else {
                        loading.style.display = 'none';
                        frame.style.display = 'block';
                        frame.setAttribute('src', url + '#toolbar=0&navpanes=0&scrollbar=0');
                    }
                });
            });

            closeBtn.addEventListener('click', closePreview);
            modal.addEventListener('click', function (event) {
                if (event.target === modal) {
                    closePreview();
                }
            });
            document.addEventListener('keydown', function (event) {
                if (event.key === 'Escape') {
                    closePreview();
                }
            });
        })();
    </script>
@endsection
