@extends('layouts.master-blank')

@section('css')
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap');

        :root {
            --auth-teal: #0D9089;
            --auth-cyan: #0D9089;
            --auth-ink: #1f2937;
            --auth-muted: #6b7280;
            --auth-border: #d1d5db;
        }

        body {
            min-height: 100vh;
            font-family: "Manrope", system-ui, -apple-system, sans-serif;
            background: #11A898;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 18px;
        }

        .auth-shell {
            width: 100%;
            max-width: 1120px;
            background: rgba(255, 255, 255, 0.96);
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 32px 70px rgba(3, 7, 18, 0.35);
        }

        .auth-grid {
            display: flex;
            min-height: 700px;
        }

        .auth-brand {
            width: 40%;
            padding: 52px 32px;
            color: #fff;
            background: linear-gradient(150deg, var(--auth-teal), var(--auth-cyan));
            position: relative;
        }

        .auth-brand::before,
        .auth-brand::after {
            content: "";
            position: absolute;
            border-radius: 999px;
            pointer-events: none;
        }

        .auth-brand::before {
            width: 220px;
            height: 220px;
            background: rgba(255, 255, 255, 0.14);
            top: -90px;
            right: -80px;
        }

        .auth-brand::after {
            width: 180px;
            height: 180px;
            background: rgba(255, 255, 255, 0.1);
            bottom: -80px;
            left: -60px;
        }

        .auth-brand-content {
            position: relative;
            z-index: 1;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: center;
            text-align: center;
            align-items: center;
        }

        .auth-logo {
            width: 180px;
            height: 190px;
            border-radius: 16px;
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.25);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 22px;
        }

        .auth-logo img {
            width: 132px;
            height: auto;
            object-fit: contain;
        }

        .auth-brand h2 {
            font-size: 30px;
            font-weight: 800;
            margin-bottom: 8px;
        }

        .auth-brand p {
            margin: 0;
            color: rgba(255, 255, 255, 0.86);
            max-width: 280px;
            line-height: 1.65;
        }

        .auth-form-panel {
            width: 60%;
            padding: 40px 34px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .auth-title {
            font-size: 28px;
            font-weight: 800;
            color: var(--auth-ink);
            margin-bottom: 8px;
        }

        .auth-subtitle {
            color: var(--auth-muted);
            margin-bottom: 20px;
        }

        .auth-grid-fields {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 12px 14px;
        }

        .auth-field label {
            font-size: 12px;
            font-weight: 700;
            color: #374151;
            margin-bottom: 6px;
            display: block;
        }

        .required-mark {
            color: #dc2626;
            margin-left: 3px;
        }

        .auth-input {
            height: 42px;
            border-radius: 10px;
            border: 1px solid var(--auth-border);
            box-shadow: none !important;
        }

        .auth-input:focus {
            border-color: var(--auth-cyan);
        }

        .auth-field.full {
            grid-column: 1 / -1;
        }

        .password-wrap {
            position: relative;
        }

        .password-toggle {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            border: 0;
            background: transparent;
            color: #4b5563;
            font-size: 11px;
            font-weight: 700;
            padding: 4px 6px;
        }

        .strength-indicator {
            font-size: 12px;
            margin-top: 6px;
            font-weight: 600;
        }

        .strength-weak {
            color: #dc2626;
        }

        .strength-medium {
            color: #d97706;
        }

        .strength-strong {
            color: #15803d;
        }

        .match-indicator {
            font-size: 12px;
            margin-top: 6px;
            font-weight: 600;
        }

        .match-ok {
            color: #15803d;
        }

        .match-bad {
            color: #dc2626;
        }

        .auth-submit {
            margin-top: 16px;
            width: 100%;
            border: 0;
            border-radius: 11px;
            padding: 11px 14px;
            color: #fff;
            font-weight: 700;
            background: #0D9089;
        }

        .auth-submit:hover {
            filter: brightness(0.95);
        }

        .auth-link {
            margin-top: 14px;
            text-align: center;
            color: var(--auth-muted);
            font-size: 14px;
        }

        .auth-link a {
            color: #0D9089;
            font-weight: 700;
        }

        @media (max-width: 1020px) {
            .auth-grid {
                flex-direction: column;
                min-height: auto;
            }

            .auth-brand,
            .auth-form-panel {
                width: 100%;
            }

            .auth-brand {
                padding: 30px 20px;
            }

            .auth-form-panel {
                padding: 30px 20px;
            }

            .auth-grid-fields {
                grid-template-columns: 1fr;
            }
        }
    </style>
@endsection
@section('content')
    <div class="auth-shell">
        <div class="auth-grid">
            <div class="auth-brand">
                <div class="auth-brand-content">
                    <div class="auth-logo">
                        <img src="{{ asset('assets/images/logo-plnsc.png') }}" alt="PLNSC">
                    </div>
                    <h2>{{ __('global.create_account') }}</h2>
                    <p>Daftar akun baru untuk mulai menggunakan sistem absensi.</p>
                </div>
            </div>

            <div class="auth-form-panel">
                <div class="auth-title">{{ __('global.create_account') }}</div>
                <div class="auth-subtitle">Lengkapi data berikut sesuai profil kamu.</div>

                <form method="POST" action="{{ route('register') }}">
                    @csrf
                    <div class="auth-grid-fields">
                        <div class="auth-field">
                            <label for="name">{{ __('global.name') }}<span class="required-mark">*</span></label>
                            <input id="name" type="text" class="form-control auth-input @error('name') is-invalid @enderror"
                                name="name" value="{{ old('name') }}" required autocomplete="name" placeholder="{{ __('global.placeholder_full_name') }}" autofocus>

                            @error('name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="auth-field">
                            <label for="phone_number">{{ __('global.phone_number') }}<span class="required-mark">*</span></label>
                            <input id="phone_number" type="text" class="form-control auth-input @error('phone_number') is-invalid @enderror"
                                name="phone_number" value="{{ old('phone_number') }}" required autocomplete="tel" placeholder="{{ __('global.placeholder_phone') }}">

                            @error('phone_number')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="auth-field">
                            <label for="address">{{ __('global.address') }}<span class="required-mark">*</span></label>
                            <input id="address" type="text" class="form-control auth-input @error('address') is-invalid @enderror"
                                name="address" value="{{ old('address') }}" required autocomplete="street-address" placeholder="{{ __('global.placeholder_address') }}">

                            @error('address')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="auth-field">
                            <label for="birth_date">{{ __('global.birth_date') }}<span class="required-mark">*</span></label>
                            <input id="birth_date" type="date" class="form-control auth-input @error('birth_date') is-invalid @enderror"
                                name="birth_date" value="{{ old('birth_date') }}" required autocomplete="bday">

                            @error('birth_date')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="auth-field">
                            <label for="institution">{{ __('global.institution') }}<span class="required-mark">*</span></label>
                            <input id="institution" type="text" class="form-control auth-input @error('institution') is-invalid @enderror"
                                name="institution" value="{{ old('institution') }}" required autocomplete="organization" placeholder="{{ __('global.placeholder_institution') }}">

                            @error('institution')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="auth-field">
                            <label for="position">{{ __('global.position') }}<span class="required-mark">*</span></label>
                            <input id="position" type="text" class="form-control auth-input @error('position') is-invalid @enderror"
                                name="position" value="{{ old('position') }}" required autocomplete="position" placeholder="{{ __('global.placeholder_position') }}">

                            @error('position')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="auth-field">
                            <label for="major">{{ __('global.major') }}<span class="required-mark">*</span></label>
                            <input id="major" type="text" class="form-control auth-input @error('major') is-invalid @enderror"
                                name="major" value="{{ old('major') }}" required autocomplete="major" placeholder="{{ __('global.placeholder_major') }}">

                            @error('major')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="auth-field">
                            <label for="email">{{ __('global.email') }}<span class="required-mark">*</span></label>
                            <input id="email" type="email" class="form-control auth-input @error('email') is-invalid @enderror"
                                name="email" value="{{ old('email') }}" required autocomplete="email" placeholder="{{ __('global.placeholder_email') }}">

                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="auth-field">
                            <label for="password">{{ __('global.login_password') }}<span class="required-mark">*</span></label>
                            <div class="password-wrap">
                                <input id="password" type="password" class="form-control auth-input @error('password') is-invalid @enderror"
                                    name="password" required autocomplete="new-password">
                                <button type="button" class="password-toggle" id="toggle-password">{{ __('global.show_password') }}</button>
                            </div>
                            <small class="text-muted d-block mt-1">Minimal 8 karakter, wajib huruf besar, huruf kecil, angka, dan simbol.</small>
                            <div id="password-strength" class="strength-indicator"></div>

                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="auth-field">
                            <label for="password-confirm">{{ __('global.confirm_password') }}<span class="required-mark">*</span></label>
                            <input id="password-confirm" type="password" class="form-control auth-input"
                                name="password_confirmation" required autocomplete="new-password">
                            <div id="password-match" class="match-indicator"></div>
                        </div>
                    </div>

                    <button class="auth-submit" type="submit">{{ __('global.register') }}</button>
                </form>

                <div class="auth-link">
                    {{ __('global.already_have_account') }}
                    <a href="{{ route('login') }}">{{ __('global.login') }}</a>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
<script>
    (function () {
        var toggle = document.getElementById('toggle-password');
        var password = document.getElementById('password');
        var confirm = document.getElementById('password-confirm');
        var strength = document.getElementById('password-strength');
        var match = document.getElementById('password-match');

        if (!toggle || !password || !confirm || !strength || !match) {
            return;
        }

        function evaluateStrength(value) {
            if (!value) {
                strength.textContent = '';
                strength.className = 'strength-indicator';
                return;
            }

            var score = 0;
            if (value.length >= 8) score++;
            if (/[a-z]/.test(value)) score++;
            if (/[A-Z]/.test(value)) score++;
            if (/\d/.test(value)) score++;
            if (/[^A-Za-z0-9]/.test(value)) score++;

            if (score <= 2) {
                strength.textContent = 'Kekuatan kata sandi: lemah';
                strength.className = 'strength-indicator strength-weak';
            } else if (score <= 4) {
                strength.textContent = 'Kekuatan kata sandi: sedang';
                strength.className = 'strength-indicator strength-medium';
            } else {
                strength.textContent = 'Kekuatan kata sandi: kuat';
                strength.className = 'strength-indicator strength-strong';
            }
        }

        function evaluateMatch() {
            if (!confirm.value) {
                match.textContent = '';
                match.className = 'match-indicator';
                return;
            }

            if (password.value === confirm.value) {
                match.textContent = 'Konfirmasi kata sandi cocok';
                match.className = 'match-indicator match-ok';
            } else {
                match.textContent = 'Konfirmasi kata sandi belum cocok';
                match.className = 'match-indicator match-bad';
            }
        }

        toggle.addEventListener('click', function () {
            var isText = password.type === 'text';
            var type = isText ? 'password' : 'text';
            password.type = type;
            confirm.type = type;
            toggle.textContent = isText ? '{{ __('global.show_password') }}' : 'Sembunyikan';
        });

        password.addEventListener('input', function () {
            evaluateStrength(password.value);
            evaluateMatch();
        });

        confirm.addEventListener('input', evaluateMatch);

        evaluateStrength(password.value);
        evaluateMatch();
    })();
</script>
@endsection
