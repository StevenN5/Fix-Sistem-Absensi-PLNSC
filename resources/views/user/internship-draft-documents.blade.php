@extends('layouts.master-blank')

@section('content')
@section('css')
    <style>
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

        .page-wrap {
            min-height: 100vh;
            padding: 24px 16px 48px;
        }

        .page-shell {
            max-width: 1120px;
            margin: 0 auto;
        }

        .page-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 20px;
            box-shadow: 0 16px 32px rgba(15, 23, 42, 0.06);
            padding: 20px;
        }

        .page-title {
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 4px;
        }

        .page-subtitle {
            color: var(--muted);
            margin-bottom: 14px;
        }

        .table thead th {
            font-size: 12px;
            text-transform: uppercase;
            color: #667085;
            letter-spacing: .04em;
        }

        @media (max-width: 768px) {
            .page-wrap {
                padding: 16px 12px 28px;
            }
            .page-card {
                border-radius: 14px;
                padding: 14px;
            }
            .page-title {
                font-size: 20px;
            }
            .form-inline {
                display: block;
            }
            .form-inline .form-control,
            .form-inline .btn {
                width: 100%;
                margin-right: 0 !important;
            }
        }
    </style>
@endsection

@php
    $documentTypeLabel = function ($type) {
        $map = [
            'monthly' => 'Template Laporan Bulanan',
            'final' => 'Template Laporan Akhir',
            'dossier' => 'Dossier Home User',
        ];
        return $map[$type] ?? ucfirst((string) $type);
    };

    if (!function_exists('format_filesize_draft_template')) {
        function format_filesize_draft_template($bytes)
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

    @include('layouts.user-navbar', ['active' => 'internship_templates'])

    <div class="page-wrap">
        <div class="page-shell">
            <div class="page-card">
                <div class="page-title">Draft Dokumen Magang</div>
                <div class="page-subtitle">Template dokumen untuk Laporan Bulanan dan Laporan Akhir.</div>

                <form method="GET" action="{{ route('user.internship-draft-documents') }}" class="form-inline mb-3">
                    <select class="form-control mr-2 mb-2" name="type">
                        <option value="">Semua Jenis</option>
                        <option value="monthly" {{ $selectedType === 'monthly' ? 'selected' : '' }}>Template Laporan Bulanan</option>
                        <option value="final" {{ $selectedType === 'final' ? 'selected' : '' }}>Template Laporan Akhir</option>
                        <option value="dossier" {{ $selectedType === 'dossier' ? 'selected' : '' }}>Dossier Home User</option>
                    </select>
                    <button class="btn btn-primary btn-sm mb-2" type="submit">Tampilkan</button>
                </form>

                @if ($documents->isEmpty())
                    <div class="text-muted">Belum ada template dokumen dari admin.</div>
                @else
                    <div class="table-responsive d-none d-md-block">
                        <table class="table table-striped table-bordered mb-0">
                            <thead>
                                <tr>
                                    <th>Tanggal Upload</th>
                                    <th>Jenis</th>
                                    <th>Judul</th>
                                    <th>Nama File</th>
                                    <th>Ukuran</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($documents as $item)
                                    <tr>
                                        <td>{{ optional($item->created_at)->format('d-m-Y H:i') }}</td>
                                        <td>{{ $documentTypeLabel($item->document_type) }}</td>
                                        <td>{{ $item->title }}</td>
                                        <td>{{ $item->file_name }}</td>
                                        <td>{{ format_filesize_draft_template($item->file_size) }}</td>
                                        <td>
                                            <a target="_blank" rel="noopener" href="{{ route('user.internship-draft-documents.view', $item->id) }}">Lihat</a>
                                            |
                                            <a href="{{ route('user.internship-draft-documents.download', $item->id) }}">Unduh</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="d-md-none">
                        @foreach ($documents as $item)
                            <div class="border rounded p-2 mb-2 bg-white">
                                <div class="font-weight-bold">{{ $item->title }}</div>
                                <div class="small text-muted">{{ optional($item->created_at)->format('d-m-Y H:i') }}</div>
                                <div class="small mt-1">{{ $documentTypeLabel($item->document_type) }}</div>
                                <div class="small mt-1 text-break">{{ $item->file_name }}</div>
                                <div class="small mt-1 text-muted">Ukuran: {{ format_filesize_draft_template($item->file_size) }}</div>
                                <div class="small mt-1">
                                    <a target="_blank" rel="noopener" href="{{ route('user.internship-draft-documents.view', $item->id) }}">Lihat</a>
                                    |
                                    <a href="{{ route('user.internship-draft-documents.download', $item->id) }}">Unduh</a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
