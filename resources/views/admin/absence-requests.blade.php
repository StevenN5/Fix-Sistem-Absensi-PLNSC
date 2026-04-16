@extends('layouts.master')

@section('css')
    <style>
        .admin-card {
            border-radius: 16px;
            border: 1px solid #e6e3ef;
            box-shadow: 0 16px 32px rgba(15, 23, 42, 0.06);
        }

        .admin-card .card-body {
            padding: 20px;
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
    </style>
@endsection

@section('breadcrumb')
    <div class="col-sm-6">
        <h4 class="page-title text-left">Ketidakhadiran</h4>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="javascript:void(0);">{{ __('global.home') }}</a></li>
            <li class="breadcrumb-item"><a href="javascript:void(0);">Ketidakhadiran</a></li>
        </ol>
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card admin-card">
                <div class="card-body">
                    <form method="GET" class="form-inline mb-3">
                        <div class="d-flex flex-wrap mr-3 mb-2" style="gap:8px;">
                            <a href="{{ route('absence-requests.index', ['type' => 'izin_sakit', 'month' => $selectedMonth]) }}"
                                class="btn btn-sm {{ ($selectedType ?? 'izin_sakit') === 'izin_sakit' ? 'btn-primary' : 'btn-light' }}">
                                Izin &amp; Sakit
                            </a>
                            <a href="{{ route('absence-requests.index', ['type' => 'lupa_absensi', 'month' => $selectedMonth]) }}"
                                class="btn btn-sm {{ ($selectedType ?? 'izin_sakit') === 'lupa_absensi' ? 'btn-primary' : 'btn-light' }}">
                                Lupa Absensi
                            </a>
                        </div>
                        <label class="mr-2 mb-2">Bulan</label>
                        <input type="hidden" name="type" value="{{ $selectedType ?? 'izin_sakit' }}">
                        <input type="month" class="form-control mr-2 mb-2" name="month" value="{{ $selectedMonth }}">
                        <button type="submit" class="btn btn-primary btn-sm mb-2">Tampilkan</button>
                    </form>

                    @if ($requests->isEmpty())
                        <div class="text-muted">Tidak ada pengajuan pada bulan ini.</div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered mb-0">
                                <thead>
                                    <tr>
                                        <th>Tanggal</th>
                                        <th>Peserta</th>
                                        <th>Jenis</th>
                                        <th>Jam Koreksi</th>
                                        <th>Alasan</th>
                                        <th>Dokumen</th>
                                        <th>Status</th>
                                        <th>Aksi Admin</th>
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
                                            <td>
                                                <strong>{{ optional($item->employee)->name ?? '-' }}</strong><br>
                                                <small class="text-muted">{{ optional($item->employee)->email ?? '-' }}</small>
                                            </td>
                                            <td>{{ $item->absence_type === 'lupa_absensi' ? 'Lupa Absensi' : ucfirst($item->absence_type ?: 'izin') }}</td>
                                            <td>
                                                {{ $item->correction_time_in ? \Carbon\Carbon::parse($item->correction_time_in)->format('H:i') : '-' }}
                                                -
                                                {{ $item->correction_time_out ? \Carbon\Carbon::parse($item->correction_time_out)->format('H:i') : '-' }}
                                            </td>
                                            <td>{{ $item->reason }}</td>
                                            <td>
                                                @if ($item->document_path)
                                                    <a href="{{ route('absence-requests.view', $item->id) }}" target="_blank" rel="noopener">Lihat</a>
                                                    |
                                                    <a href="{{ route('absence-requests.download', $item->id) }}">Unduh</a>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="status-pill {{ $pillClass }}">{{ strtoupper($status) }}</span>
                                                @if ($item->reviewer)
                                                    <div class="text-muted mt-1" style="font-size:11px;">
                                                        Oleh {{ $item->reviewer->name }}
                                                    </div>
                                                @endif
                                            </td>
                                            <td style="min-width: 260px;">
                                                <div class="d-flex flex-wrap" style="gap:8px;">
                                                    <form method="POST" action="{{ route('absence-requests.update', $item->id) }}" class="mb-0">
                                                        @csrf
                                                        @method('PATCH')
                                                        <input type="hidden" name="status" value="approved">
                                                        @if ($item->absence_type === 'lupa_absensi')
                                                            <input type="time" name="correction_time_in" value="{{ $item->correction_time_in ? \Carbon\Carbon::parse($item->correction_time_in)->format('H:i') : '' }}" class="form-control form-control-sm mb-1">
                                                            <input type="time" name="correction_time_out" value="{{ $item->correction_time_out ? \Carbon\Carbon::parse($item->correction_time_out)->format('H:i') : '' }}" class="form-control form-control-sm mb-1">
                                                        @endif
                                                        <button type="submit" class="btn btn-sm btn-success">Setujui</button>
                                                    </form>
                                                    <form method="POST" action="{{ route('absence-requests.update', $item->id) }}" class="mb-0">
                                                        @csrf
                                                        @method('PATCH')
                                                        <input type="hidden" name="status" value="rejected">
                                                        <button type="submit" class="btn btn-sm btn-danger">Tolak</button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
