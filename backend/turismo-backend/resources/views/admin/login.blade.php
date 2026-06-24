<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Iniciar sesión en el panel de administración de Turismo App">
    <title>Iniciar Sesión | Turismo Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        :root {
            --bg-base: #0a0f1e;
            --bg-card: #111827;
            --border: rgba(56,189,248,0.12);
            --primary: #38bdf8;
            --primary-dark: #0ea5e9;
            --primary-glow: rgba(56,189,248,0.15);
            --danger: #f87171;
            --text-primary: #f1f5f9;
            --text-muted: #94a3b8;
            --text-faint: #475569;
        }
        html, body {
            height: 100%; font-family: 'Inter', sans-serif;
            background: var(--bg-base); color: var(--text-primary);
        }
        body {
            display: flex; align-items: center; justify-content: center;
            min-height: 100vh;
            background: radial-gradient(ellipse at 20% 50%, rgba(14,165,233,0.08) 0%, transparent 60%),
                        radial-gradient(ellipse at 80% 20%, rgba(129,140,248,0.06) 0%, transparent 60%),
                        var(--bg-base);
        }
        .login-container {
            width: 100%; max-width: 420px;
            padding: 20px;
        }
        .login-brand {
            text-align: center;
            margin-bottom: 32px;
        }
        .brand-icon {
            width: 60px; height: 60px;
            background: linear-gradient(135deg, var(--primary-dark), #818cf8);
            border-radius: 18px;
            display: inline-flex; align-items: center; justify-content: center;
            font-size: 28px;
            box-shadow: 0 0 40px rgba(56,189,248,0.25);
            margin-bottom: 16px;
        }
        .login-brand h1 { font-size: 24px; font-weight: 700; }
        .login-brand p { font-size: 14px; color: var(--text-muted); margin-top: 6px; }

        .login-card {
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: 16px;
            padding: 32px;
            box-shadow: 0 24px 80px rgba(0,0,0,0.4);
        }
        .form-group { margin-bottom: 18px; }
        label {
            display: block;
            font-size: 12px; font-weight: 600;
            color: var(--text-muted);
            margin-bottom: 6px;
        }
        input {
            width: 100%;
            background: rgba(10,15,30,0.8);
            border: 1px solid var(--border);
            border-radius: 8px;
            color: var(--text-primary);
            padding: 10px 14px;
            font-size: 14px;
            font-family: inherit;
            transition: border-color 0.2s, box-shadow 0.2s;
        }
        input:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px var(--primary-glow);
        }
        input::placeholder { color: var(--text-faint); }

        .error-alert {
            background: rgba(248,113,113,0.1);
            border: 1px solid rgba(248,113,113,0.3);
            color: var(--danger);
            border-radius: 8px;
            padding: 10px 14px;
            font-size: 13px;
            margin-bottom: 18px;
        }

        .btn-login {
            width: 100%;
            padding: 12px;
            background: linear-gradient(135deg, var(--primary-dark), var(--primary));
            color: #0a0f1e;
            border: none;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.2s;
            box-shadow: 0 0 30px rgba(56,189,248,0.25);
            margin-top: 8px;
        }
        .btn-login:hover {
            box-shadow: 0 0 40px rgba(56,189,248,0.4);
            transform: translateY(-1px);
        }
        .login-footer {
            text-align: center;
            margin-top: 24px;
            font-size: 12px;
            color: var(--text-faint);
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-brand">
            <div class="brand-icon">🌊</div>
            <h1>TurismoApp Admin</h1>
            <p>Ingresá con tu cuenta de administrador</p>
        </div>

        <div class="login-card">
            @if ($errors->any())
                <div class="error-alert">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('admin.login.post') }}">
                @csrf
                <div class="form-group">
                    <label for="email">Correo electrónico</label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        value="{{ old('email') }}"
                        placeholder="admin@ejemplo.com"
                        autocomplete="email"
                        required
                    >
                </div>
                <div class="form-group">
                    <label for="password">Contraseña</label>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        placeholder="••••••••"
                        autocomplete="current-password"
                        required
                    >
                </div>
                <button type="submit" class="btn-login">Ingresar al Panel</button>
            </form>
        </div>

        <div class="login-footer">
            TurismoApp © {{ date('Y') }} · Panel de Administración
        </div>
    </div>
</body>
</html>
