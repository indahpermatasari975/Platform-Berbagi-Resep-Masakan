<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'ResepKita')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body class="bg-light">
  <nav class="navbar navbar-expand-lg bg-white border-bottom shadow-sm sticky-top">
    <div class="container-fluid px-4">

        <a class="navbar-brand fw-bold text-warning fs-3" href="{{ route('dashboard') }}">
            <i class="bi bi-egg-fried"></i>
            ResepKita
        </a>

        <div class="d-flex align-items-center gap-2">

            @guest
                <a href="{{ route('login') }}" class="btn btn-outline-secondary fw-semibold">
                    <i class="bi bi-box-arrow-in-right"></i>
                    Login
                </a>
            @else
                <span class="fw-semibold text-muted">{{ auth()->user()->name }}</span>

                <form action="{{ route('logout') }}" method="POST" class="m-0">
                    @csrf
                    <button type="submit" class="btn btn-outline-secondary fw-semibold">
                        <i class="bi bi-box-arrow-right"></i>
                        Logout
                    </button>
                </form>

                <a href="{{ route('recipes.create') }}" class="btn btn-warning text-white fw-semibold">
                    <i class="bi bi-plus-lg"></i>
                    Tambah Resep
                </a>
            @endguest

        </div>

    </div>
</nav>

    <div class="container-fluid">
        <div class="row">
            <aside class="col-xl-2 col-lg-3 bg-white border-end min-vh-100 sidebar">
                <div class="py-4">
                    <a class="sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                        <i class="bi bi-house-door"></i>
                        Beranda
                    </a>
                    <a class="sidebar-link {{ request()->routeIs('recipes.*') ? 'active' : '' }}" href="{{ route('recipes.index') }}">
                        <i class="bi bi-journal-richtext"></i>
                        Resep Saya
                    </a>
                    <a class="sidebar-link {{ request()->routeIs('favorites.*') ? 'active' : '' }}" href="{{ route('favorites.index') }}">
                        <i class="bi bi-heart"></i>
                        Favorit
                    </a>
                    <a class="sidebar-link {{ request()->routeIs('meal-planner.*') ? 'active' : '' }}" href="{{ route('meal-planner.index') }}">
                        <i class="bi bi-calendar3"></i>
                        Meal Planner
                    </a>
                    <a class="sidebar-link {{ request()->routeIs('substitutions.*') ? 'active' : '' }}" href="{{ route('substitutions.index') }}">
                        <i class="bi bi-arrow-repeat"></i>
                        Substitusi Bahan
                    </a>
                </div>
            </aside>

            <main class="col-xl-10 col-lg-9">
                <div class="p-4">
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <div class="fw-semibold mb-1">Periksa kembali input berikut:</div>
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @yield('content')
                </div>
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
