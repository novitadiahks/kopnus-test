
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >
</head>
<body class="bg-light">

    <div class="container min-vh-100 d-flex align-items-center justify-content-center py-5">
        <div class="card border-0 shadow-sm rounded-4 w-100" style="max-width: 450px;">
            <div class="card-body p-4 p-md-5">

                <h2 class="fw-bold text-center mb-2">Selamat Datang!</h2>
                <p class="text-secondary text-center mb-4">
                    Login untuk melanjutkan ke dashboard.
                </p>

                @if ($errors->any())
                    <div class="alert alert-danger" role="alert">
                        <ul class="mb-0 ps-3">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                @endif

                <form action="{{ route('login.store') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input
                            type="email"
                            id="email"
                            name="email"
                            class="form-control"
                            value="{{ old('email') }}"
                            placeholder="nama@email.com"
                            autocomplete="email"
                            required
                            autofocus
                        >
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input
                            type="password"
                            id="password"
                            name="password"
                            class="form-control"
                            placeholder="Masukkan password"
                            autocomplete="current-password"
                            required
                        >
                    </div>

                    <div class="form-check mb-4">
                        <input
                            class="form-check-input"
                            type="checkbox"
                            id="remember"
                            name="remember"
                            value="1"
                        >
                        <label class="form-check-label" for="remember">
                            Ingat saya
                        </label>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 py-2">
                        Login
                    </button>
                </form>

                <p class="text-center text-secondary mt-4 mb-0">
                    Belum punya akun?
                    <a href="{{ route('register') }}" class="text-decoration-none fw-semibold">
                        Register
                    </a>
                </p>

            </div>
        </div>
    </div>

</body>
</html>
