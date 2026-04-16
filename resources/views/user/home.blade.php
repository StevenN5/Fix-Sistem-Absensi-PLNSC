@extends('layouts.master-blank')

@section('content')
@section('css')
    <style>
        :root {
            --ink: #14121a;
            --muted: #6f7285;
            --surface: #ffffff;
            --border: #e6e3ef;
            --primary: #1f6feb;
            --accent: #12968a;
        }

        body {
            background: radial-gradient(circle at 10% 10%, #fff4d8 0%, transparent 40%),
                radial-gradient(circle at 80% 0%, #dce8ff 0%, transparent 45%),
                linear-gradient(180deg, #f3f4fb 0%, #f9f7ff 100%);
            color: var(--ink);
            font-family: "Manrope", system-ui, -apple-system, sans-serif;
        }

        .home-wrap {
            min-height: 100vh;
            padding: 24px 16px 48px;
        }

        .home-shell {
            max-width: 1120px;
            margin: 0 auto;
        }

        .hero-card {
            background: linear-gradient(130deg, #0f3e77 0%, #145a8a 45%, #1f7f9e 100%);
            border-radius: 22px;
            padding: 24px;
            color: #fff;
            box-shadow: 0 16px 36px rgba(15, 23, 42, 0.18);
            margin-bottom: 18px;
        }

        .hero-title {
            font-size: 28px;
            font-weight: 800;
            margin: 0 0 6px;
        }

        .hero-subtitle {
            opacity: 0.9;
            margin: 0;
        }

        .quick-menu-section {
            margin-top: 18px;
        }

        .quick-menu-head {
            margin-bottom: 12px;
        }

        .quick-menu-head h2 {
            margin: 0;
            font-size: 22px;
            font-weight: 800;
            color: #1e293b;
        }

        .quick-menu-head p {
            margin: 4px 0 0;
            font-size: 13px;
            color: #64748b;
        }

        .quick-menu-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 14px;
        }

        .quick-menu-card {
            display: block;
            border-radius: 18px;
            padding: 18px 18px 16px;
            color: #fff !important;
            text-decoration: none !important;
            box-shadow: 0 12px 28px rgba(15, 23, 42, 0.16);
            transition: transform .2s ease, box-shadow .2s ease, filter .2s ease;
            min-height: 168px;
            position: relative;
            overflow: hidden;
        }

        .quick-menu-card::before {
            content: "";
            position: absolute;
            width: 130px;
            height: 130px;
            right: -45px;
            top: -45px;
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.18);
            pointer-events: none;
        }

        .quick-menu-card::after {
            content: "";
            position: absolute;
            inset: 0;
            border: 1px solid rgba(255, 255, 255, 0.26);
            border-radius: 18px;
            pointer-events: none;
        }

        .quick-menu-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 18px 34px rgba(15, 23, 42, 0.22);
            filter: saturate(1.04);
        }

        .quick-menu-icon {
            width: 40px;
            height: 40px;
            border-radius: 12px;
            border: 1px solid rgba(255, 255, 255, 0.32);
            background: rgba(255, 255, 255, 0.15);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 12px;
        }

        .quick-menu-icon i {
            font-size: 24px;
            line-height: 1;
        }

        .quick-menu-title {
            margin: 0;
            font-size: clamp(18px, 2.2vw, 28px);
            font-weight: 800;
            line-height: 1.2;
        }

        .quick-menu-desc {
            margin: 8px 0 0;
            color: rgba(255, 255, 255, 0.9);
            font-size: 13px;
            line-height: 1.45;
            min-height: 38px;
        }

        .quick-menu-link {
            margin-top: 10px;
            display: inline-block;
            color: rgba(255, 255, 255, 0.95);
            font-size: 12px;
            font-weight: 700;
            letter-spacing: .02em;
        }

        .quick-menu-card.c1 { background: linear-gradient(135deg, #2f8be6 0%, #16a6bd 100%); }
        .quick-menu-card.c2 { background: linear-gradient(135deg, #8e4ee8 0%, #e14195 100%); }
        .quick-menu-card.c3 { background: linear-gradient(135deg, #ff7a18 0%, #f04646 100%); }
        .quick-menu-card.c4 { background: linear-gradient(135deg, #4f6de6 0%, #8c4de8 100%); }
        .quick-menu-card.c5 { background: linear-gradient(135deg, #0ea5a2 0%, #2563eb 100%); }
        .quick-menu-card.c6 { background: linear-gradient(135deg, #0d9488 0%, #14b8a6 100%); }

        .doc-card {
            border: 1px solid var(--border);
            border-radius: 18px;
            background: var(--surface);
            box-shadow: 0 10px 24px rgba(15, 23, 42, 0.06);
            overflow: hidden;
        }

        .doc-head {
            padding: 16px 18px;
            border-bottom: 1px solid #edf0f5;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            flex-wrap: wrap;
        }

        .doc-title {
            margin: 0;
            font-size: 22px;
            font-weight: 800;
        }

        .doc-subtitle {
            color: var(--muted);
            font-size: 13px;
            margin: 4px 0 0;
        }

        .doc-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(210px, 1fr));
            gap: 12px;
            padding: 16px;
        }

        .doc-item {
            position: relative;
            overflow: hidden;
            border: 1px solid #e6ecf4;
            border-radius: 16px;
            padding: 14px;
            background: linear-gradient(180deg, #ffffff 0%, #fbfdff 100%);
            box-shadow: 0 8px 18px rgba(15, 23, 42, 0.06);
            transition: transform .2s ease, box-shadow .2s ease, border-color .2s ease;
            --mx: 50%;
            --my: 50%;
            --spot-opacity: 0;
        }

        .doc-item:hover {
            transform: translateY(-3px);
            border-color: #d4deea;
            box-shadow: 0 14px 26px rgba(15, 23, 42, 0.10);
        }

        .doc-item::before {
            content: "";
            position: absolute;
            inset: -1px;
            pointer-events: none;
            opacity: var(--spot-opacity);
            transition: opacity .25s ease;
            background:
                radial-gradient(260px circle at var(--mx) var(--my),
                    rgba(148, 163, 184, 0.20) 0%,
                    rgba(148, 163, 184, 0.08) 28%,
                    rgba(148, 163, 184, 0) 56%);
        }

        .doc-item-content {
            position: relative;
            z-index: 1;
        }

        .doc-item-icon {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border: 1px solid #dbe5f0;
            background: #f8fbff;
            color: #3b82f6;
            margin-bottom: 8px;
        }

        .doc-item-icon i {
            font-size: 18px;
        }

        .doc-item-title {
            font-size: 13px;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 6px;
            min-height: 32px;
        }

        .doc-item-category {
            display: inline-block;
            margin-bottom: 6px;
            padding: 3px 8px;
            border-radius: 999px;
            background: #e8f3ff;
            color: #275285;
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .03em;
        }

        .doc-item-desc {
            color: #475569;
            font-size: 12px;
            line-height: 1.4;
            min-height: 34px;
            margin-bottom: 8px;
        }

        .doc-item-file {
            color: #64748b;
            font-size: 11px;
            word-break: break-all;
            margin-bottom: 8px;
        }

        .doc-actions {
            display: flex;
            gap: 6px;
            flex-wrap: wrap;
        }

        .btn-live {
            background: #e6f4ff;
            color: #0b5cab;
            border: 1px solid #bfdbfe;
            border-radius: 999px;
            font-weight: 700;
            font-size: 11px;
            padding: 5px 10px;
            text-decoration: none !important;
        }

        .btn-live:hover {
            background: #dbeeff;
            color: #0b5cab;
        }

        .btn-download {
            background: #e8f8f4;
            color: #0f766e;
            border: 1px solid #bce7dd;
            border-radius: 999px;
            font-weight: 700;
            font-size: 11px;
            padding: 5px 10px;
            text-decoration: none !important;
        }

        .btn-download:hover {
            background: #d9f2eb;
            color: #0f766e;
        }

        .empty-box {
            padding: 32px 16px;
            text-align: center;
            color: var(--muted);
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
            width: min(1120px, 100%);
            height: min(88vh, 820px);
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
        }

        @media (max-width: 768px) {
            .home-wrap {
                padding: 16px 12px 24px;
            }

            .hero-card {
                border-radius: 16px;
                padding: 16px;
            }

            .hero-title {
                font-size: 24px;
            }

            .doc-title {
                font-size: 18px;
            }

            .doc-grid {
                grid-template-columns: 1fr;
                padding: 12px;
            }

        }
    </style>
@endsection

    @php
        $selectedCategory = $selectedCategory ?? 'all';
        $categoryLabel = function ($key) {
            $map = [
                'all' => 'Semua',
                'compro' => 'Compro',
                'laporan_keuangan' => 'Laporan Keuangan',
                'pedoman_sop' => 'Pedoman / SOP',
                'materi_orientasi' => 'Materi Orientasi',
                'lainnya' => 'Lainnya',
            ];
            return $map[$key] ?? 'Lainnya';
        };
    @endphp

    @include('layouts.user-navbar', ['active' => 'home'])

    <div class="home-wrap">
        <div class="home-shell">
            <div class="hero-card">
                <h1 class="hero-title">Home</h1>
                <p class="hero-subtitle">Perpustakaan dokumen magang dari admin untuk panduan peserta.</p>
            </div>

            <div class="doc-card">
                <div class="doc-head">
                    <div>
                        <h2 class="doc-title">Dokumen Magang &amp; FAQ</h2>
                        <div class="doc-subtitle">Pilih kategori lalu buka dokumen dengan tombol Lihat.</div>
                    </div>
                </div>
                @if ($documents->isEmpty())
                    <div class="empty-box">Belum ada dokumen dossier pada kategori ini.</div>
                @else
                    <div class="doc-grid">
                        @foreach ($documents as $item)
                            <div class="doc-item dossier-spotlight">
                                <div class="doc-item-content">
                                    <span class="doc-item-icon"><i class="mdi mdi-file-document-outline"></i></span>
                                    <span class="doc-item-category">{{ $categoryLabel($item->library_category ?: 'lainnya') }}</span>
                                    <div class="doc-item-title">{{ $item->title }}</div>
                                    <div class="doc-item-desc">{{ \Illuminate\Support\Str::limit($item->description ?: 'Tidak ada deskripsi.', 100) }}</div>
                                    <div class="doc-item-file">{{ $item->file_name }}</div>
                                    <div class="doc-actions">
                                        <button type="button"
                                            class="btn-live btn-template-preview"
                                            data-title="{{ $item->title }}"
                                            data-preview-url="{{ route('user.template.view', $item->id) }}">
                                            Lihat
                                        </button>
                                        <a class="btn-download" href="{{ route('user.template.download', $item->id) }}">Unduh</a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <section class="quick-menu-section">
                <div class="quick-menu-head">
                    <h2>Menu</h2>
                    <p>Akses cepat fitur inti untuk aktivitas magang harian.</p>
                </div>
                <div class="quick-menu-grid">
                    <a href="{{ route('user.attendance.index') }}" class="quick-menu-card c2">
                        <span class="quick-menu-icon"><i class="mdi mdi-clock-outline"></i></span>
                        <h3 class="quick-menu-title">Kehadiran</h3>
                        <p class="quick-menu-desc">Presensi masuk dan pulang sesuai jadwal kerja.</p>
                        <span class="quick-menu-link">Buka Menu -></span>
                    </a>

                    <a href="{{ route('user.attendance.history') }}" class="quick-menu-card c3">
                        <span class="quick-menu-icon"><i class="mdi mdi-calendar-month-outline"></i></span>
                        <h3 class="quick-menu-title">Riwayat Kehadiran</h3>
                        <p class="quick-menu-desc">Pantau kalender presensi dan draft histori kehadiran.</p>
                        <span class="quick-menu-link">Buka Menu -></span>
                    </a>

                    <a href="{{ route('user.absence.index', ['type' => 'izin_sakit']) }}" class="quick-menu-card c4">
                        <span class="quick-menu-icon"><i class="mdi mdi-clipboard-text-outline"></i></span>
                        <h3 class="quick-menu-title">Ketidakhadiran</h3>
                        <p class="quick-menu-desc">Ajukan izin, sakit, atau laporan lupa absensi.</p>
                        <span class="quick-menu-link">Buka Menu -></span>
                    </a>

                    <a href="{{ route('user.monthly-report') }}" class="quick-menu-card c5">
                        <span class="quick-menu-icon"><i class="mdi mdi-file-document-outline"></i></span>
                        <h3 class="quick-menu-title">Laporan Magang</h3>
                        <p class="quick-menu-desc">Kelola laporan bulanan serta laporan akhir magang.</p>
                        <span class="quick-menu-link">Buka Menu -></span>
                    </a>

                    <a href="{{ route('user.profile') }}" class="quick-menu-card c6">
                        <span class="quick-menu-icon"><i class="mdi mdi-account-outline"></i></span>
                        <h3 class="quick-menu-title">Profil</h3>
                        <p class="quick-menu-desc">Perbarui data pribadi dan informasi profil peserta.</p>
                        <span class="quick-menu-link">Buka Menu -></span>
                    </a>
                </div>
            </section>
        </div>
    </div>

    <div class="template-preview-modal" id="template-preview-modal" aria-hidden="true">
        <div class="template-preview-card">
            <div class="template-preview-head">
                <div class="template-preview-title" id="template-preview-title">Preview Dokumen</div>
                <button type="button" class="btn btn-sm btn-danger" id="template-preview-close">Tutup</button>
            </div>
            <iframe id="template-preview-frame" class="template-preview-frame" src=""></iframe>
        </div>
    </div>
@endsection

@section('script')
    <script>
        (function () {
            var modal = document.getElementById('template-preview-modal');
            var frame = document.getElementById('template-preview-frame');
            var title = document.getElementById('template-preview-title');
            var closeBtn = document.getElementById('template-preview-close');

            if (!modal || !frame || !title || !closeBtn) {
                return;
            }

            function closePreview() {
                modal.classList.remove('show');
                modal.setAttribute('aria-hidden', 'true');
                frame.setAttribute('src', '');
            }

            document.querySelectorAll('.btn-template-preview').forEach(function (btn) {
                btn.addEventListener('click', function () {
                    var url = btn.getAttribute('data-preview-url') || '';
                    title.textContent = btn.getAttribute('data-title') || 'Preview Dokumen';
                    frame.setAttribute('src', url + '#toolbar=0&navpanes=0&scrollbar=0');
                    modal.classList.add('show');
                    modal.setAttribute('aria-hidden', 'false');
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

            document.querySelectorAll('.dossier-spotlight').forEach(function (card) {
                card.addEventListener('mouseenter', function () {
                    card.style.setProperty('--spot-opacity', '1');
                });

                card.addEventListener('mouseleave', function () {
                    card.style.setProperty('--spot-opacity', '0');
                });

                card.addEventListener('mousemove', function (event) {
                    var rect = card.getBoundingClientRect();
                    var x = event.clientX - rect.left;
                    var y = event.clientY - rect.top;
                    card.style.setProperty('--mx', x + 'px');
                    card.style.setProperty('--my', y + 'px');
                });
            });
        })();
    </script>
@endsection
