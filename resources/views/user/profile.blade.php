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

        .action-card {
            background: var(--surface);
            border-radius: 20px;
            padding: 20px;
            border: 1px solid var(--border);
            box-shadow: 0 14px 30px rgba(15, 23, 42, 0.06);
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

        .profile-section-title {
            margin-top: 10px;
            margin-bottom: 12px;
            font-size: 14px;
            font-weight: 800;
            color: #1f2c3d;
            text-transform: uppercase;
            letter-spacing: 0.04em;
        }

        .profile-section-divider {
            margin-top: 6px;
            margin-bottom: 16px;
            border-top: 1px solid #eef1f4;
        }

        @media (max-width: 768px) {
            .attendance-page {
                padding: 16px 12px 28px;
            }

            .attendance-topbar {
                flex-direction: column;
                align-items: flex-start;
            }

            .attendance-greeting h1 {
                font-size: 24px;
            }

            .action-card {
                border-radius: 14px;
                padding: 14px;
            }
        }
    </style>
@endsection
@php
    $displayUser = auth()->user();
@endphp
    @include('layouts.user-navbar', ['active' => 'profile'])
    <div class="attendance-page">
        <div class="attendance-shell">
            <div class="attendance-topbar">
                <div class="attendance-greeting">
                    <span>Selamat datang kembali</span>
                    <h1>{{ $displayUser->name }}</h1>
                </div>
            </div>

            <div class="action-card">
                <div class="action-header">
                    <div class="action-title">Kelola Profil</div>
                </div>
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <form method="POST" action="{{ route('user.profile.update') }}" class="action-form" enctype="multipart/form-data">
                    @csrf
                    <div class="form-row">
                        <div class="col-12">
                            <div class="profile-section-title">Data Pribadi</div>
                            <div class="profile-section-divider"></div>
                        </div>
                        <div class="form-group col-md-12">
                            <label for="profile_photo">Foto Profil</label>
                            @php
                                $photoPath = old('profile_photo_path', $displayUser->profile_photo_path ?? optional($employee)->profile_photo_path);
                            @endphp
                            @if ($photoPath)
                                <div class="mb-2">
                                    <img src="{{ asset('storage/' . $photoPath) }}" alt="Foto Profil"
                                        style="width: 90px; height: 90px; object-fit: cover; border-radius: 50%; border: 1px solid #e6e3ef;">
                                </div>
                            @endif
                            <input id="profile_photo" type="file" class="form-control" name="profile_photo" accept=".jpg,.jpeg,.png,.webp">
                            <small class="text-muted">Maksimal 2MB (JPG, JPEG, PNG, WEBP).</small>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="name">{{ __('global.name') }}</label>
                            <input id="name" type="text" class="form-control" name="name"
                                value="{{ old('name', $displayUser->name) }}" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="phone_number">{{ __('global.phone_number') }}</label>
                            <input id="phone_number" type="text" class="form-control" name="phone_number"
                                value="{{ old('phone_number', $displayUser->phone_number) }}" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="address">{{ __('global.address') }}</label>
                            <input id="address" type="text" class="form-control" name="address"
                                value="{{ old('address', $displayUser->address) }}" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="birth_date">{{ __('global.birth_date') }}</label>
                            <input id="birth_date" type="date" class="form-control" name="birth_date"
                                value="{{ old('birth_date', $displayUser->birth_date) }}" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="institution">{{ __('global.institution') }}</label>
                            <input id="institution" type="text" class="form-control" name="institution"
                                value="{{ old('institution', $displayUser->institution) }}" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="internship_start_date">Periode Magang Mulai</label>
                            <input id="internship_start_date" type="date" class="form-control" name="internship_start_date"
                                value="{{ old('internship_start_date', $displayUser->internship_start_date ?? optional($employee)->internship_start_date) }}">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="internship_end_date">Periode Magang Selesai</label>
                            <input id="internship_end_date" type="date" class="form-control" name="internship_end_date"
                                value="{{ old('internship_end_date', $displayUser->internship_end_date ?? optional($employee)->internship_end_date) }}">
                        </div>

                        <div class="col-12">
                            <div class="profile-section-title">Data Profil</div>
                            <div class="profile-section-divider"></div>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="division_id">Divisi</label>
                            @php
                                $selectedDivisionId = old('division_id', $displayUser->division_id ?? optional($employee)->division_id);
                                $mentorName = old('mentor_name');
                                if (!$mentorName) {
                                    $mentorName = optional($displayUser->mentor)->name ?? optional(optional($employee)->mentor)->name ?? '-';
                                }
                            @endphp
                            <select id="division_id" class="form-control" name="division_id" required>
                                <option value="">Pilih Divisi</option>
                                @foreach (($divisions ?? collect()) as $division)
                                    <option value="{{ $division->id }}" {{ (string) $selectedDivisionId === (string) $division->id ? 'selected' : '' }}>
                                        {{ $division->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="mentor_name">Mentor</label>
                            <input id="mentor_name" type="text" class="form-control" name="mentor_name" value="{{ $mentorName }}" readonly>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="major">{{ __('global.major') }}</label>
                            <input id="major" type="text" class="form-control" name="major"
                                value="{{ old('major', optional($employee)->major) }}" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="email">{{ __('global.email') }}</label>
                            <input id="email" type="email" class="form-control" name="email"
                                value="{{ old('email', $displayUser->email) }}" required>
                        </div>

                        <div class="col-12">
                            <div class="profile-section-title">Kontak Darurat</div>
                            <div class="profile-section-divider"></div>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="emergency_contact_name">Nama Kontak Darurat</label>
                            <input id="emergency_contact_name" type="text" class="form-control" name="emergency_contact_name"
                                value="{{ old('emergency_contact_name', $displayUser->emergency_contact_name ?? optional($employee)->emergency_contact_name) }}">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="emergency_contact_phone">Nomor Kontak Darurat</label>
                            <input id="emergency_contact_phone" type="text" class="form-control" name="emergency_contact_phone"
                                value="{{ old('emergency_contact_phone', $displayUser->emergency_contact_phone ?? optional($employee)->emergency_contact_phone) }}">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="emergency_contact_relation">Hubungan Kontak Darurat</label>
                            <input id="emergency_contact_relation" type="text" class="form-control" name="emergency_contact_relation"
                                value="{{ old('emergency_contact_relation', $displayUser->emergency_contact_relation ?? optional($employee)->emergency_contact_relation) }}">
                        </div>

                        <div class="col-12">
                            <div class="profile-section-title">Data Bank</div>
                            <div class="profile-section-divider"></div>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="bank_name">Nama Bank</label>
                            <input id="bank_name" type="text" class="form-control" name="bank_name"
                                value="{{ old('bank_name', $displayUser->bank_name ?? optional($employee)->bank_name) }}">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="bank_account_number">Nomor Rekening</label>
                            <input id="bank_account_number" type="text" class="form-control" name="bank_account_number"
                                value="{{ old('bank_account_number', $displayUser->bank_account_number ?? optional($employee)->bank_account_number) }}">
                        </div>

                        <div class="col-12">
                            <div class="profile-section-title">Histori Magang</div>
                            <div class="profile-section-divider"></div>
                            @if (!empty($history) && $history->isNotEmpty())
                                <div class="table-responsive mb-3">
                                    <table class="table table-sm table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Tipe</th>
                                                <th>Periode</th>
                                                <th>Tanggal</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($history as $item)
                                                <tr>
                                                    <td>{{ $item['type'] }}</td>
                                                    <td>{{ $item['period'] }}</td>
                                                    <td>{{ $item['date'] }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="text-muted mb-3">Belum ada histori magang.</div>
                            @endif
                        </div>
                    </div>
                    <button class="btn btn-primary action-button" type="submit">Simpan Profil</button>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        (function () {
            var divisionSelect = document.getElementById('division_id');
            var mentorInput = document.getElementById('mentor_name');
            if (!divisionSelect || !mentorInput) return;

            var divisionMentorMap = @json(
                collect($divisions ?? [])->mapWithKeys(function ($division) {
                    return [(string) $division->id => optional($division->mentors->first())->name ?: '-'];
                })
            );

            function syncMentor() {
                var selectedDivisionId = divisionSelect.value || '';
                mentorInput.value = divisionMentorMap[selectedDivisionId] || '-';
            }

            divisionSelect.addEventListener('change', syncMentor);
            syncMentor();
        })();
    </script>
@endsection
