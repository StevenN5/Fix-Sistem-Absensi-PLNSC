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

        .absence-page {
            min-height: 100vh;
            padding: 24px 16px 48px;
        }

        .absence-shell {
            max-width: 1120px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: 1fr;
            gap: 16px;
        }

        .absence-card {
            background: var(--surface);
            border-radius: 22px;
            padding: 22px;
            border: 1px solid var(--border);
            box-shadow: 0 16px 32px rgba(15, 23, 42, 0.06);
        }

        .absence-title {
            font-weight: 700;
            font-size: 20px;
            margin-bottom: 14px;
        }

        .form-row-wrap {
            display: grid;
            grid-template-columns: 1fr;
            gap: 12px;
        }

        .status-pill {
            padding: 4px 10px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 700;
        }

        .status-pending { background: #fff3cd; color: #8a6d3b; }
        .status-approved { background: #d4edda; color: #155724; }
        .status-rejected { background: #f8d7da; color: #721c24; }

        @media (max-width: 768px) {
            .form-row-wrap { grid-template-columns: 1fr; }
            .absence-card {
                padding: 14px;
                border-radius: 14px;
            }
            .absence-title {
                font-size: 18px;
                margin-bottom: 10px;
            }
        }
    </style>
@endsection

@section('content')
    @php
        $selectedType = $selectedType ?? 'izin_sakit';
        $absenceActiveTab = $selectedType === 'lupa_absensi' ? 'absence_lupa' : 'absence_izin';
    @endphp
    @include('layouts.user-navbar', ['active' => $absenceActiveTab])

    <div class="absence-page">
        <div class="absence-shell">
            <div class="absence-card">
                <div class="absence-title">Pengajuan Ketidakhadiran / Izin</div>
                <div class="mb-3 d-flex flex-wrap" style="gap:8px;">
                    <a href="{{ route('user.absence.index', ['type' => 'izin_sakit']) }}"
                        class="btn btn-sm {{ $selectedType === 'izin_sakit' ? 'btn-primary' : 'btn-light' }}">
                        Izin &amp; Sakit
                    </a>
                    <a href="{{ route('user.absence.index', ['type' => 'lupa_absensi']) }}"
                        class="btn btn-sm {{ $selectedType === 'lupa_absensi' ? 'btn-primary' : 'btn-light' }}">
                        Lupa Absensi
                    </a>
                </div>
                @if (!$employee)
                    <div class="alert alert-warning mb-0">Data peserta magang belum tersedia. Lengkapi profil terlebih dahulu.</div>
                @else
                    <form method="POST" action="{{ route('user.absence.store') }}" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="menu_type" value="{{ $selectedType }}">
                        <div class="form-row-wrap">
                            <div class="form-group">
                                <label>Tanggal Izin</label>
                                <input type="date" name="absence_date" class="form-control" value="{{ old('absence_date', now()->toDateString()) }}" required>
                            </div>
                            <div class="form-group">
                                <label>Jenis Tidak Masuk</label>
                                @if ($selectedType === 'lupa_absensi')
                                    <input type="hidden" name="absence_type" value="lupa_absensi">
                                    <input type="text" class="form-control" value="Lupa Absensi" disabled>
                                @else
                                    <select name="absence_type" class="form-control" required>
                                        <option value="izin" {{ old('absence_type') === 'izin' ? 'selected' : '' }}>Izin</option>
                                        <option value="sakit" {{ old('absence_type') === 'sakit' ? 'selected' : '' }}>Sakit</option>
                                    </select>
                                @endif
                            </div>
                            @if ($selectedType === 'lupa_absensi')
                                <div class="form-group">
                                    <label>Jam Masuk Koreksi (opsional)</label>
                                    <input type="time" name="correction_time_in" class="form-control" value="{{ old('correction_time_in') }}">
                                </div>
                                <div class="form-group">
                                    <label>Jam Pulang Koreksi (opsional)</label>
                                    <input type="time" name="correction_time_out" class="form-control" value="{{ old('correction_time_out') }}">
                                    <small class="text-muted">Isi minimal salah satu jam (masuk/pulang).</small>
                                </div>
                            @endif
                            <div class="form-group">
                                <label>Dokumen Pendukung (Opsional)</label>
                                <input type="file" name="document" class="form-control" accept=".pdf,.jpg,.jpeg,.png,.doc,.docx">
                                <small class="text-muted">Format: pdf/jpg/png/doc/docx, maksimal 5MB.</small>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Alasan</label>
                            <textarea name="reason" rows="4" class="form-control" placeholder="Tuliskan alasan izin/ketidakhadiran..." required>{{ old('reason') }}</textarea>
                        </div>
                        <button type="submit" class="btn btn-primary btn-sm">Kirim Pengajuan</button>
                    </form>
                @endif
            </div>

            <div class="absence-card">
                <div class="absence-title">Riwayat Pengajuan</div>
                @if ($requests->isEmpty())
                    <div class="text-muted">Belum ada pengajuan.</div>
                @else
                    <div class="table-responsive d-none d-md-block">
                        <table class="table table-striped mb-0">
                            <thead>
                                <tr>
                                    <th>Tanggal Izin</th>
                                    <th>Jenis</th>
                                    <th>Jam Koreksi</th>
                                    <th>Alasan</th>
                                    <th>Dokumen</th>
                                    <th>Status</th>
                                    <th>Catatan Admin</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($requests as $item)
                                    @php
                                        $status = strtolower((string) $item->status);
                                        $pillClass = 'status-pending';
                                        if ($status === 'approved') $pillClass = 'status-approved';
                                        if ($status === 'rejected') $pillClass = 'status-rejected';
                                    @endphp
                                    <tr>
                                        <td>{{ optional($item->absence_date)->format('d-m-Y') }}</td>
                                        <td>{{ $item->absence_type === 'lupa_absensi' ? 'Lupa Absensi' : ucfirst($item->absence_type ?: 'izin') }}</td>
                                        <td>
                                            {{ $item->correction_time_in ? \Carbon\Carbon::parse($item->correction_time_in)->format('H:i') : '-' }}
                                            -
                                            {{ $item->correction_time_out ? \Carbon\Carbon::parse($item->correction_time_out)->format('H:i') : '-' }}
                                        </td>
                                        <td>{{ $item->reason }}</td>
                                        <td>
                                            @if ($item->document_path)
                                                <a href="{{ route('user.absence.view', $item->id) }}" target="_blank" rel="noopener">Lihat</a>
                                                |
                                                <a href="{{ route('user.absence.download', $item->id) }}">Unduh</a>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="status-pill {{ $pillClass }}">{{ strtoupper($status) }}</span>
                                        </td>
                                        <td>{{ $item->admin_note ?: '-' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="d-md-none">
                        @foreach ($requests as $item)
                            @php
                                $status = strtolower((string) $item->status);
                                $pillClass = 'status-pending';
                                if ($status === 'approved') $pillClass = 'status-approved';
                                if ($status === 'rejected') $pillClass = 'status-rejected';
                            @endphp
                            <div class="border rounded p-2 mb-2 bg-white">
                                <div class="font-weight-bold">{{ optional($item->absence_date)->format('d-m-Y') }}</div>
                                <div class="small text-muted mb-1">{{ $item->absence_type === 'lupa_absensi' ? 'Lupa Absensi' : ucfirst($item->absence_type ?: 'izin') }}</div>
                                <div class="small mb-1">
                                    Jam koreksi:
                                    {{ $item->correction_time_in ? \Carbon\Carbon::parse($item->correction_time_in)->format('H:i') : '-' }}
                                    -
                                    {{ $item->correction_time_out ? \Carbon\Carbon::parse($item->correction_time_out)->format('H:i') : '-' }}
                                </div>
                                <div class="small mb-1">{{ $item->reason }}</div>
                                <div class="small mb-1">
                                    @if ($item->document_path)
                                        <a href="{{ route('user.absence.view', $item->id) }}" target="_blank" rel="noopener">Lihat</a>
                                        |
                                        <a href="{{ route('user.absence.download', $item->id) }}">Unduh</a>
                                    @else
                                        <span class="text-muted">Dokumen: -</span>
                                    @endif
                                </div>
                                <div class="small mb-1">
                                    <span class="status-pill {{ $pillClass }}">{{ strtoupper($status) }}</span>
                                </div>
                                <div class="small text-muted">Catatan: {{ $item->admin_note ?: '-' }}</div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
