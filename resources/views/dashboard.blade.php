@extends('layouts.app')

@section('title', 'Dashboard - ResepKita')

@section('content')
<div class="row g-4">
    <section class="col-12">
        <div class="card border-0 shadow-sm hero-card overflow-hidden">
            <div class="card-body p-4 p-lg-5">
                <div class="row align-items-center g-4">
                    <div class="col-lg-8">
                        <span class="badge bg-warning text-dark mb-3">
                            <i class="bi bi-stars"></i>
                            Platform Berbagi Resep
                        </span>
                        <h1 class="fw-bold display-6 mb-3">Temukan Inspirasi Resep Terbaik</h1>
                        <p class="text-muted fs-5 mb-4">
                            Jelajahi resep nusantara dan internasional, simpan favorit, buat meal planner,
                            dan sesuaikan porsi bahan secara otomatis.
                        </p>
                        <form method="GET" action="{{ route('dashboard') }}">
                            <div class="input-group input-group-lg">
                                <input type="text" name="q" value="{{ $q }}" class="form-control" placeholder="Cari resep...">
                                <button class="btn btn-warning fw-semibold">
                                    <i class="bi bi-search"></i>
                                    Cari
                                </button>
                            </div>
                        </form>
                    </div>
                    <div class="col-lg-4">
                        <img src="https://cdn.moneysmart.id/wp-content/uploads/2019/03/03103821/Masakan-nusantara-jadi-delegasi-Garuda-Indonesia-ke-wisatawan.jpg" class="img-fluid rounded-3 hero-image" alt="Aneka hidangan sehat">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="col-lg-8">
        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <div class="stat-box">
                    <div class="stat-icon bg-warning-subtle text-warning"><i class="bi bi-journal-richtext"></i></div>
                    <div>
                        <div class="h3 mb-0">{{ $totalRecipes }}</div>
                        <small class="text-muted">Total Resep</small>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-box">
                    <div class="stat-icon bg-success-subtle text-success"><i class="bi bi-tags"></i></div>
                    <div>
                        <div class="h3 mb-0">{{ $totalCategories }}</div>
                        <small class="text-muted">Kategori</small>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-box">
                    <div class="stat-icon bg-danger-subtle text-danger"><i class="bi bi-heart-fill"></i></div>
                    <div>
                        <div class="h3 mb-0">{{ $totalFavorites }}</div>
                        <small class="text-muted">Favorit</small>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="fw-bold mb-0"><i class="bi bi-fire text-warning"></i> Resep Populer</h4>
            <a href="{{ route('recipes.index') }}" class="text-decoration-none">Lihat Semua</a>
        </div>
        <div class="row g-4 mb-5">
            @forelse ($popularRecipes as $recipe)
                @include('recipes.partials.recipe-card', ['recipe' => $recipe])
            @empty
                <div class="col-12">
                    <div class="empty-state">Belum ada resep populer.</div>
                </div>
            @endforelse
        </div>

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="fw-bold mb-0"><i class="bi bi-clock-history text-warning"></i> Resep Terbaru</h4>
            <a href="{{ route('recipes.index') }}" class="text-decoration-none">Lihat Semua</a>
        </div>
        <div class="row g-4">
            @forelse ($latestRecipes as $recipe)
                @include('recipes.partials.recipe-card', ['recipe' => $recipe])
            @empty
                <div class="col-12">
                    <div class="empty-state">Belum ada resep terbaru.</div>
                </div>
            @endforelse
        </div>
    </section>

    <aside class="col-lg-4">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body">
                <h5 class="fw-bold mb-3">Kategori Populer</h5>
                @forelse ($categories as $cat)
                    <a href="{{ route('recipes.index', ['q' => $cat]) }}" class="badge rounded-pill text-bg-warning text-decoration-none me-1 mb-2">
                        {{ $cat }}
                    </a>
                @empty
                    <div class="text-muted small">Belum ada kategori.</div>
                @endforelse
            </div>
        </div>

        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body">
                <h5 class="fw-bold"><i class="bi bi-calendar3 text-warning"></i> Meal Planner</h5>
                <p class="text-muted small">Atur menu sarapan, makan siang, dan makan malam.</p>
                <a href="{{ route('meal-planner.index') }}" class="btn btn-warning w-100 fw-semibold">Buka Meal Planner</a>
            </div>
        </div>

        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body">
                <h5 class="fw-bold"><i class="bi bi-heart text-danger"></i> Favorit Saya</h5>
                <p class="text-muted small">Lihat kembali resep yang sudah kamu simpan.</p>
                <a href="{{ route('favorites.index') }}" class="btn btn-outline-danger w-100 fw-semibold">Lihat Favorit</a>
            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <h5 class="fw-bold"><i class="bi bi-play-circle text-warning"></i> Video Resep</h5>
                @if ($latestVideoRecipe && $latestVideoRecipe->video_embed_url)
                    <div class="ratio ratio-16x9 rounded overflow-hidden mb-3">
                        <iframe src="{{ $latestVideoRecipe->video_embed_url }}" title="Video resep {{ $latestVideoRecipe->title }}" allowfullscreen></iframe>
                    </div>
                    <h6 class="mb-0">{{ $latestVideoRecipe->title }}</h6>
                @else
                    <div class="text-muted small">Belum ada video resep.</div>
                @endif
            </div>
        </div>
    </aside>
</div>
@endsection
