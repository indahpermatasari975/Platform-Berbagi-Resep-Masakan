<!DOCTYPE html>

<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

```
<title>ResepKita</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<link rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<link rel="stylesheet" href="{{ asset('css/app.css') }}">
```

</head>

<body class="bg-light">

```
<!-- TOPBAR -->
<nav class="navbar navbar-expand-lg bg-white border-bottom shadow-sm sticky-top">
    <div class="container-fluid px-4">

        <a class="navbar-brand fw-bold text-warning fs-3"
            href="{{ route('dashboard') }}">
            <i class="bi bi-egg-fried"></i>
            ResepKita
        </a>

        <ul class="navbar-nav me-4">
            <li class="nav-item">
                <a class="nav-link fw-semibold active" href="#">
                    Beranda
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="{{ route('recipes.index') }}">
                    Resep
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="#">
                    Kategori
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link"
                    href="{{ route('meal-planner.index') }}">
                    Meal Planner
                </a>
            </li>
        </ul>

        <form class="d-flex flex-grow-1 mx-4">
            <input
                class="form-control rounded-pill"
                type="search"
                placeholder="Cari resep, bahan, kategori...">
        </form>

        <a href="{{ route('recipes.create') }}"
            class="btn btn-warning text-white fw-semibold me-3">
            <i class="bi bi-plus-lg"></i>
            Tambah Resep
        </a>
        
    </div>
</nav>

<div class="container-fluid">

    <div class="row">

        <!-- SIDEBAR -->
        <div class="col-lg-2 bg-white border-end min-vh-100">

            <div class="py-4">

                <a class="sidebar-link active d-block"
                    href="{{ route('dashboard') }}">
                    <i class="bi bi-house-door"></i>
                    Beranda
                </a>

                <a class="sidebar-link d-block"
                    href="{{ route('recipes.index') }}">
                    <i class="bi bi-journal-richtext"></i>
                    Resep Saya
                </a>

                <a class="sidebar-link d-block"
                    href="{{ route('favorites.index') }}">
                    <i class="bi bi-heart"></i>
                    Favorit
                </a>

                <a class="sidebar-link d-block"
                    href="{{ route('meal-planner.index') }}">
                    <i class="bi bi-calendar3"></i>
                    Meal Planner
                </a>

                <a class="sidebar-link d-block"
                    href="{{ route('substitutions.index') }}">
                    <i class="bi bi-arrow-repeat"></i>
                    Substitusi Bahan
                </a>

                <a class="sidebar-link d-block">
                    <i class="bi bi-play-circle"></i>
                    Video Resep
                </a>

            </div>

        </div>

        <!-- MAIN CONTENT -->
        <div class="col-lg-10">

            <div class="p-4">

                @yield('content')

            </div>

        </div>

    </div>

</div>
```

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
