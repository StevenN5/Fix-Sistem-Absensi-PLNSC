@extends('layouts.master-blank')

@section('css')
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap');

        :root {
            --bg-start: #11A898;
            --bg-end: #11A898;
            --panel-left-start: #0D9089;
            --panel-left-end: #0D9089;
            --panel-right: #edf1f4;
            --ink: #1f2c3d;
            --muted: #5f6e7d;
            --field-border: #c5cdd5;
            --link: #0D9089;
            --btn-start: #0D9089;
            --btn-end: #0D9089;
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
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 8px 30px rgba(13, 31, 50, 0.28);
        }

        .auth-grid {
            display: flex;
            min-height: 700px;
        }

        .auth-brand {
            width: 40%;
            padding: 52px 32px;
            color: #fff;
            background: linear-gradient(165deg, var(--panel-left-start), var(--panel-left-end));
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
            align-items: center;
            text-align: center;
        }

        .auth-logo {
            width: 160px;
            height: 170px;
            border-radius: 16px;
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.25);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 22px;
        }

        .auth-logo img {
            width: 118px;
            height: auto;
            object-fit: contain;
        }

        .auth-brand p {
            margin: 0;
            color: rgba(228, 244, 248, 0.88);
            max-width: 280px;
            line-height: 1.65;
            font-size: 16px;
            font-weight: 400;
        }

        .auth-form-panel {
            width: 60%;
            background: var(--panel-right);
            padding: 40px 34px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .auth-title {
            font-size: 28px;
            font-weight: 800;
            color: var(--ink);
            margin-bottom: 8px;
            text-align: left;
        }

        .auth-subtitle {
            color: var(--muted);
            margin-bottom: 20px;
            text-align: left;
            font-size: 14px;
        }

        .auth-field {
            margin-bottom: 16px;
        }

        .auth-field label {
            font-size: 12px;
            font-weight: 700;
            color: #394958;
            margin-bottom: 6px;
            display: block;
        }

        .auth-input {
            height: 42px;
            border-radius: 10px;
            border: 1px solid var(--field-border);
            box-shadow: none !important;
            font-size: 14px;
            padding-left: 14px;
            padding-right: 40px;
        }

        .auth-input:focus {
            border-color: #97a8b7;
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
            color: #6a7785;
            width: 22px;
            height: 22px;
            padding: 0;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .auth-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            margin-top: 4px;
            margin-bottom: 20px;
            font-size: 14px;
        }

        .auth-row .form-check {
            display: flex;
            align-items: center;
        }

        .auth-row .form-check-input {
            width: 16px;
            height: 16px;
            margin-top: 0;
            margin-right: 8px;
        }

        .auth-row .form-check-label {
            font-size: 14px;
            color: #566474;
        }

        .forgot-link {
            font-size: 14px;
            font-weight: 700;
            color: var(--link);
            text-decoration: none;
        }

        .auth-submit {
            width: 100%;
            border: 0;
            border-radius: 11px;
            padding: 11px 14px;
            color: #fff;
            font-weight: 700;
            font-size: 16px;
            background: linear-gradient(90deg, var(--btn-start), var(--btn-end));
            box-shadow: 0 8px 18px rgba(17, 154, 174, 0.28);
        }

        .auth-submit:hover {
            filter: brightness(0.95);
        }

        .auth-link {
            margin-top: 14px;
            text-align: center;
            color: #5e6d7c;
            font-size: 14px;
        }

        .auth-link a {
            color: var(--link);
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

            .auth-brand p {
                font-size: 16px;
                max-width: 360px;
            }

            .auth-title {
                font-size: 28px;
            }

            .auth-subtitle {
                font-size: 14px;
            }

            .auth-field label,
            .auth-row .form-check-label,
            .forgot-link,
            .auth-link {
                font-size: 14px;
            }

            .auth-input {
                height: 42px;
                font-size: 14px;
            }

            .auth-submit {
                font-size: 16px;
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
                    <p>Sistem untuk pengelolaan peserta magang secara terstruktur dan efisien.</p>
                </div>
            </div>

            <div class="auth-form-panel">
                <div class="auth-title">Selamat Datang Kembali</div>
                <div class="auth-subtitle">Masuk ke akun Anda</div>

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <div class="auth-field">
                        <label for="email">Alamat Email</label>
                        <input id="email" type="email" class="form-control auth-input @error('email') is-invalid @enderror"
                            name="email" value="{{ old('email') }}" required autocomplete="email" autofocus
                            placeholder="Masukkan email Anda">

                        @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="auth-field">
                        <label for="password">Kata Sandi</label>
                        <div class="password-wrap">
                            <input id="password" type="password" class="form-control auth-input @error('password') is-invalid @enderror"
                                name="password" required autocomplete="current-password" placeholder="Masukkan kata sandi">
                            <button type="button" class="password-toggle" id="toggle-password" aria-label="Tampilkan atau sembunyikan kata sandi">
                                <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M1 12s4-7 11-7s11 7 11 7s-4 7-11 7S1 12 1 12z"></path>
                                    <circle cx="12" cy="12" r="3"></circle>
                                </svg>
                            </button>
                        </div>

                        @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                   
                    <div class="auth-row">
                        <div class="form-check mb-0">
                            <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                            <label class="form-check-label" for="remember">Ingat Saya</label>
                        </div>
                        <a class="forgot-link" href="javascript:void(0)">Lupa Kata Sandi?</a>
                    </div>

                    <button class="auth-submit" type="submit">Masuk</button>
                </form>

                <div class="auth-link">
                    Belum punya akun?
                    <a href="{{ route('register') }}">Daftar sekarang</a>
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
            if (!toggle || !password) {
                return;
            }
            toggle.addEventListener('click', function () {
                password.type = password.type === 'text' ? 'password' : 'text';
            });
        })();
    </script>
@endsection
