@extends('layouts.master')

@section('css')
<style>
    .detail-card {
        border-radius: 16px;
        border: 1px solid #e6e3ef;
        box-shadow: 0 16px 32px rgba(15, 23, 42, 0.06);
        overflow: hidden;
    }
    .detail-card .card-body {
        padding: 22px;
    }
    .detail-title {
        font-weight: 700;
        margin-bottom: 14px;
        color: #2c3243;
    }
    .detail-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 10px 20px;
    }
    .detail-row {
        border-bottom: 1px dashed #e6e3ef;
        padding: 6px 0;
    }
    .detail-label {
        font-size: 12px;
        color: #6b7280;
        text-transform: uppercase;
        letter-spacing: .04em;
    }
    .detail-value {
        font-weight: 600;
        color: #1f2937;
        margin-top: 2px;
    }
    .stat-box {
        border: 1px solid #e6e3ef;
        border-radius: 12px;
        padding: 14px 12px;
        text-align: center;
        background: #fbfbfe;
    }
    .stat-number {
        font-size: 22px;
        font-weight: 700;
        line-height: 1.2;
        color: #2f3b74;
    }
    .stat-label {
        font-size: 12px;
        color: #6b7280;
        margin-top: 6px;
    }
    @media (max-width: 991px) {
        .detail-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
@endsection

@section('breadcrumb')
<div class="col-sm-6">
    <h4 class="page-title text-left">Detail Peserta Magang</h4>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="javascript:void(0);">{{ __('global.home') }}</a></li>
        <li class="breadcrumb-item"><a href="{{ route('employees.index') }}">{{ __('global.employees') }}</a></li>
        <li class="breadcrumb-item active">Detail</li>
    </ol>
</div>
@endsection

@section('button')
<a href="{{ route('employees.index') }}" class="btn btn-secondary btn-sm btn-flat">
    <i class="mdi mdi-arrow-left mr-2"></i>Kembali
</a>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card detail-card mb-4">
            <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                    @if($employee->profile_photo_path)
                        <img src="{{ asset('storage/' . $employee->profile_photo_path) }}" alt="Foto Profil"
                            style="width:72px;height:72px;object-fit:cover;border-radius:50%;border:1px solid #d6dbe6;">
                    @else
                        <div style="width:72px;height:72px;border-radius:50%;background:#eef2ff;border:1px solid #d6dbe6;display:flex;align-items:center;justify-content:center;font-weight:700;color:#4f46e5;">
                            {{ strtoupper(substr($employee->name ?? '-', 0, 1)) }}
                        </div>
                    @endif
                    <div class="ml-3">
                        <h5 class="mb-1" style="font-weight:700;">{{ $employee->name }}</h5>
                        <div class="text-muted">ID Peserta: {{ $employee->id }}</div>
                    </div>
                </div>

                <h6 class="detail-title">Data Profil</h6>
                <div class="detail-grid">
                    <div class="detail-row">
                        <div class="detail-label">Email</div>
                        <div class="detail-value">{{ $employee->email ?: '-' }}</div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">Nomor HP</div>
                        <div class="detail-value">{{ $employee->phone_number ?: '-' }}</div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">Tanggal Lahir</div>
                        <div class="detail-value">{{ $employee->birth_date ? \Carbon\Carbon::parse($employee->birth_date)->format('d-m-Y') : '-' }}</div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">Alamat</div>
                        <div class="detail-value">{{ $employee->address ?: '-' }}</div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">Asal Kampus</div>
                        <div class="detail-value">{{ $employee->institution ?: '-' }}</div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">Divisi</div>
                        <div class="detail-value">{{ optional($employee->division)->name ?: $employee->position ?: '-' }}</div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">Mentor</div>
                        <div class="detail-value">{{ optional($employee->mentor)->name ?: '-' }}</div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">Jurusan</div>
                        <div class="detail-value">{{ $employee->major ?: '-' }}</div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">Periode Magang</div>
                        <div class="detail-value">
                            {{ $employee->internship_start_date ? \Carbon\Carbon::parse($employee->internship_start_date)->format('d-m-Y') : '-' }}
                            s/d
                            {{ $employee->internship_end_date ? \Carbon\Carbon::parse($employee->internship_end_date)->format('d-m-Y') : '-' }}
                        </div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">Terdaftar Sejak</div>
                        <div class="detail-value">{{ optional($employee->created_at)->format('d-m-Y H:i') ?: '-' }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card detail-card mb-4">
            <div class="card-body">
                <h6 class="detail-title">Kontak Darurat</h6>
                <div class="detail-grid" style="grid-template-columns: 1fr;">
                    <div class="detail-row">
                        <div class="detail-label">Nama</div>
                        <div class="detail-value">{{ $employee->emergency_contact_name ?: '-' }}</div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">Nomor HP</div>
                        <div class="detail-value">{{ $employee->emergency_contact_phone ?: '-' }}</div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">Hubungan</div>
                        <div class="detail-value">{{ $employee->emergency_contact_relation ?: '-' }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card detail-card mb-4">
            <div class="card-body">
                <h6 class="detail-title">Data Bank</h6>
                <div class="detail-grid" style="grid-template-columns: 1fr;">
                    <div class="detail-row">
                        <div class="detail-label">Nama Bank</div>
                        <div class="detail-value">{{ $employee->bank_name ?: '-' }}</div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">Nomor Rekening</div>
                        <div class="detail-value">{{ $employee->bank_account_number ?: '-' }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="card detail-card">
            <div class="card-body">
                <h6 class="detail-title">Ringkasan Aktivitas</h6>
                <div class="row">
                    <div class="col-md-3 col-6 mb-3">
                        <div class="stat-box">
                            <div class="stat-number">{{ $stats['attendance_count'] }}</div>
                            <div class="stat-label">Total Presensi Masuk</div>
                        </div>
                    </div>
                    <div class="col-md-3 col-6 mb-3">
                        <div class="stat-box">
                            <div class="stat-number">{{ $stats['leave_count'] }}</div>
                            <div class="stat-label">Total Presensi Pulang</div>
                        </div>
                    </div>
                    <div class="col-md-3 col-6 mb-3">
                        <div class="stat-box">
                            <div class="stat-number">{{ $stats['latetime_count'] }}</div>
                            <div class="stat-label">Total Terlambat</div>
                        </div>
                    </div>
                    <div class="col-md-3 col-6 mb-3">
                        <div class="stat-box">
                            <div class="stat-number">{{ $stats['overtime_count'] }}</div>
                            <div class="stat-label">Total Lembur</div>
                        </div>
                    </div>
                    <div class="col-md-3 col-6 mb-3">
                        <div class="stat-box">
                            <div class="stat-number">{{ $stats['absence_request_count'] }}</div>
                            <div class="stat-label">Total Ketidakhadiran</div>
                        </div>
                    </div>
                    <div class="col-md-3 col-6 mb-3">
                        <div class="stat-box">
                            <div class="stat-number">{{ $stats['monthly_report_count'] }}</div>
                            <div class="stat-label">Laporan Bulanan</div>
                        </div>
                    </div>
                    <div class="col-md-3 col-6 mb-3">
                        <div class="stat-box">
                            <div class="stat-number">{{ $stats['final_report_count'] }}</div>
                            <div class="stat-label">Laporan Akhir</div>
                        </div>
                    </div>
                    <div class="col-md-3 col-6 mb-3">
                        <div class="stat-box">
                            <div class="stat-number">{{ $stats['draft_document_count'] }}</div>
                            <div class="stat-label">Draft Presensi</div>
                        </div>
                    </div>
                </div>
                @if($user)
                    <div class="text-muted mt-2">Akun login terhubung: <strong>{{ $user->name }}</strong> ({{ $user->email }})</div>
                @else
                    <div class="text-warning mt-2">Akun login user belum terhubung berdasarkan email.</div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
