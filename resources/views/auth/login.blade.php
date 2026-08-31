<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Preventivas</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: #f5f6fa;
        }

        .login-card {
            width: 420px;
            border: none;
            border-radius: 15px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, .08);
        }

        .logo {
            font-size: 34px;
            font-weight: bold;
            color: #0d6efd;
        }

        .subtitle {
            color: #777;
        }
    </style>
</head>

<body>

    <div class="container vh-100 d-flex justify-content-center align-items-center">

        <div class="card login-card">

            <div class="card-body p-5">

                @if ($errors->any())
                    <div class="alert alert-danger text-center mb-4">
                        {{ $errors->first() }}
                    </div>
                @endif


                <div class="text-center mb-4">

                    <div class="logo">
                        Preventivas
                    </div>

                    <div class="subtitle">
                        Sistema de Gestão de Preventivas
                    </div>

                </div>

                <form method="POST" action="{{ route('login.authenticate') }}">

                    @csrf

                    <div class="mb-3">

                        <label class="form-label">
                            E-mail
                        </label>

                        <input type="email" class="form-control" name="email" placeholder="Digite seu e-mail"
                            value="{{ old('email') }}" required autofocus>

                    </div>

                    <div class="mb-4">

                        <label class="form-label">
                            Senha
                        </label>

                        <input type="password" class="form-control" placeholder="Digite sua senha" name="password"
                            required>

                    </div>
                    <div class="form-check mb-4">
                        <input type="checkbox" class="form-check-input" name="remember" id="remember"
                            {{ old('remember') ? 'checked' : '' }}>
                        <label class="form-check-label" for="remember">
                            Lembrar-me
                        </label>
                    </div>

                    <button class="btn btn-primary w-100">

                        Entrar

                    </button>

                </form>

            </div>

        </div>

    </div>

</body>

</html>
