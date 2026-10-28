
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>

    <!-- Bootstrap 5 CDN -->
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >
</head>
<body class="bg-light">

    <div class="container min-vh-100 d-flex align-items-center justify-content-center py-5">
        <div class="card border-0 shadow-sm rounded-4 w-100" style="max-width: 450px;">
            <div class="card-body p-4 p-md-5">

                <h2 class="fw-bold text-center mb-2">Buat Akun</h2>
                <p class="text-secondary text-center mb-4">
                    Daftar untuk mulai mengelola proyekmu.
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

                <form action="{{ route('register.store') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label for="name" class="form-label">Nama lengkap</label>
                        <input
                            type="text"
                            id="name"
                            name="name"
                            class="form-control"
                            value="{{ old('name') }}"
                            placeholder="Masukkan nama"
                            autocomplete="name"
                            required
                        >
                    </div>

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
                        >
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input
                            type="password"
                            id="password"
                            name="password"
                            class="form-control"
                            placeholder="Minimal 8 karakter"
                            autocomplete="new-password"
                            required
                        >
                    </div>

                    <div class="mb-4">
                        <label for="password_confirmation" class="form-label">
                            Konfirmasi password
                        </label>
                        <input
                            type="password"
                            id="password_confirmation"
                            name="password_confirmation"
                            class="form-control"
                            placeholder="Ulangi password"
                            autocomplete="new-password"
                            required
                        >
                    </div>

                    <button type="submit" class="btn btn-primary w-100 py-2">
                        Buat Akun
                    </button>
                </form>

                <p class="text-center text-secondary mt-4 mb-0">
                    Sudah punya akun?
                    <a href="{{ route('login') }}" class="text-decoration-none fw-semibold">
                        Login
                    </a>
                </p>

            </div>
        </div>
    </div>

</body>
</html>
