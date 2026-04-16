@extends('layouts.master-blank')

@section('css')
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Fraunces:wght@500;700&family=Manrope:wght@400;500;600;700&display=swap');

        :root {
            --ink: #14121a;
            --muted: #6f7285;
            --surface: #ffffff;
            --border: #e6e3ef;
        }

        body {
            background: radial-gradient(circle at 10% 10%, #fff4d8 0%, transparent 40%),
                radial-gradient(circle at 80% 0%, #dce8ff 0%, transparent 45%),
                linear-gradient(180deg, #f3f4fb 0%, #f9f7ff 100%);
            color: var(--ink);
            font-family: "Manrope", system-ui, -apple-system, sans-serif;
        }

        .user-app-navbar { background: #1A6F6F; border-bottom: 1px solid rgba(255, 255, 255, 0.2); }
        .user-app-navbar-inner { max-width: 1120px; margin: 0 auto; padding: 12px 16px; display: flex; align-items: center; justify-content: space-between; gap: 14px; flex-wrap: wrap; }
        .user-app-brand { display: inline-flex; align-items: center; gap: 10px; text-decoration: none; color: #fff; font-weight: 700; font-size: 18px; }
        .user-app-brand img { width: 26px; height: 26px; object-fit: contain; }
        .user-app-menu { display: flex; align-items: center; gap: 12px; flex-wrap: wrap; }
        .user-app-menu a { color: rgba(255, 255, 255, 0.9); text-decoration: none; font-size: 13px; font-weight: 600; padding: 5px 10px; border-radius: 999px; }
        .user-app-menu a.active, .user-app-menu a:hover { background: rgba(255, 255, 255, 0.2); color: #fff; }
        .user-app-logout { border: 1px solid rgba(255, 255, 255, 0.6); background: transparent; color: #fff; border-radius: 999px; font-size: 12px; font-weight: 700; padding: 6px 12px; }

        .draft-page { min-height: 100vh; padding: 24px 16px 48px; }
        .draft-shell { max-width: 1120px; margin: 0 auto; }
        .draft-card { background: var(--surface); border-radius: 22px; padding: 22px; border: 1px solid var(--border); box-shadow: 0 16px 32px rgba(15, 23, 42, 0.06); }
        .draft-title { font-weight: 700; font-size: 20px; margin-bottom: 14px; }

        @media (max-width: 768px) {
            .draft-page { padding: 16px 12px 28px; }
            .draft-card { border-radius: 14px; padding: 14px; }
            .draft-title { font-size: 18px; margin-bottom: 10px; }
        }
    </style>
@endsection

@section('content')
    @include('layouts.user-navbar', ['active' => 'history_draft'])

    <div class="draft-page">
        <div class="draft-shell">
            <div class="draft-card mb-3">
                <div class="draft-title">Upload Draft Dokumen Presensi</div>
                @if (!$employee)
                    <div class="alert alert-warning mb-0">Data peserta magang belum tersedia. Lengkapi profil terlebih dahulu.</div>
                @else
                    <form method="POST" action="{{ route('user.attendance.draft.store') }}" enctype="multipart/form-data" class="form-row">
                        @csrf
                        <div class="form-group col-md-4">
                            <label>Bulan</label>
                            <input type="month" class="form-control" name="report_month" value="{{ old('report_month', now()->format('Y-m')) }}" required>
                        </div>
                        <div class="form-group col-md-8">
                            <label>File Draft Presensi</label>
                            <input type="file" class="form-control" name="draft_document" accept=".pdf,.doc,.docx,.xls,.xlsx,.jpg,.jpeg,.png" required>
                            <small class="text-muted">Format: pdf/doc/docx/xls/xlsx/jpg/png, maksimal 5MB.</small>
                        </div>
                        <div class="form-group col-12 mb-0">
                            <button class="btn btn-primary btn-sm" type="submit">Unggah Draft</button>
                        </div>
                    </form>
                @endif
            </div>

            <div class="draft-card">
                <div class="draft-title">Riwayat Draft Presensi</div>
                @if ($documents->isEmpty())
                    <div class="text-muted">Belum ada draft presensi yang diunggah.</div>
                @else
                    <div class="table-responsive d-none d-md-block">
                        <table class="table table-striped mb-0">
                            <thead>
                                <tr>
                                    <th>Bulan</th>
                                    <th>Tanggal Unggah</th>
                                    <th>Dokumen</th>
                                    <th>Ukuran</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($documents as $item)
                                    <tr>
                                        <td>{{ $item->report_month }}</td>
                                        <td>{{ optional($item->created_at)->format('d-m-Y H:i') }}</td>
                                        <td>
                                            <a target="_blank" rel="noopener" href="{{ route('user.attendance.draft.view', $item->id) }}">Lihat</a>
                                            |
                                            <a href="{{ route('user.attendance.draft.download', $item->id) }}">{{ $item->file_name }}</a>
                                        </td>
                                        <td>
                                            @php
                                                $size = (int) ($item->file_size ?? 0);
                                                $displaySize = '-';
                                                if ($size > 0) {
                                                    $displaySize = number_format($size / 1024, 1) . ' KB';
                                                }
                                            @endphp
                                            {{ $displaySize }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="d-md-none">
                        @foreach ($documents as $item)
                            <div class="border rounded p-2 mb-2 bg-white">
                                <div class="font-weight-bold">{{ $item->report_month }}</div>
                                <div class="small text-muted">{{ optional($item->created_at)->format('d-m-Y H:i') }}</div>
                                <div class="small mt-1">
                                    <a target="_blank" rel="noopener" href="{{ route('user.attendance.draft.view', $item->id) }}">Lihat</a>
                                    |
                                    <a href="{{ route('user.attendance.draft.download', $item->id) }}">{{ $item->file_name }}</a>
                                </div>
                                <div class="small mt-1 text-muted">
                                    @php
                                        $size = (int) ($item->file_size ?? 0);
                                        $displaySize = '-';
                                        if ($size > 0) {
                                            $displaySize = number_format($size / 1024, 1) . ' KB';
                                        }
                                    @endphp
                                    Ukuran: {{ $displaySize }}
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
