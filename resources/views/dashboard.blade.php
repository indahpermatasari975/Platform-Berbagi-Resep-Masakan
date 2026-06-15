@extends('layouts.app')

@section('content')

<div class="row g-4">

```
<!-- HERO -->
<div class="col-12">

    <div class="card border-0 shadow-sm hero-card">

        <div class="card-body p-5">

            <div class="row align-items-center">

                <div class="col-lg-8">

                    <span class="badge bg-warning text-dark mb-3">
                        🍳 Platform Berbagi Resep
                    </span>

                    <h1 class="fw-bold">
                        Temukan Inspirasi Resep Terbaik
                    </h1>

                    <p class="text-muted">
                        Jelajahi resep nusantara dan internasional,
                        simpan favorit, buat meal planner,
                        dan hitung porsi otomatis.
                    </p>

                    <form method="GET"
                        action="{{ route('dashboard') }}">

                        <div class="input-group">

                            <input
                                type="text"
                                name="q"
                                value="{{ $q }}"
                                class="form-control"
                                placeholder="Cari resep...">

                            <button
                                class="btn btn-warning">

                                Cari

                            </button>

                        </div>

                    </form>

                </div>

                <div class="col-lg-4 text-center">

                    <img
                        src="https://images.unsplash.com/photo-1546069901-ba9599a7e63c"
                        class="img-fluid rounded-4">

                </div>

            </div>

        </div>

    </div>

</div>

<!-- KONTEN KIRI -->
<div class="col-lg-8">

    <!-- STATISTIK -->
    <div class="row g-3 mb-4">

        <div class="col-md-4">

            <div class="card border-0 shadow-sm">

                <div class="card-body text-center">

                    <h3>{{ $latestRecipes->count() }}</h3>

                    <small>
                        Resep Terbaru
                    </small>

                </div>

            </div>

        </div>

        <div class="col-md-4">

            <div class="card border-0 shadow-sm">

                <div class="card-body text-center">

                    <h3>{{ $popularRecipes->count() }}</h3>

                    <small>
                        Resep Populer
                    </small>

                </div>

            </div>

        </div>

        <div class="col-md-4">

            <div class="card border-0 shadow-sm">

                <div class="card-body text-center">

                    <h3>{{ $categories->count() }}</h3>

                    <small>
                        Kategori
                    </small>

                </div>

            </div>

        </div>

    </div>

    <!-- RESEP POPULER -->

    <div class="d-flex justify-content-between mb-3">

        <h4>🔥 Resep Populer</h4>

        <a href="{{ route('recipes.index') }}">
            Lihat Semua
        </a>

    </div>

    <div class="row g-4 mb-5">

        @foreach($popularRecipes as $recipe)

            @include(
                'recipes.partials.recipe-card',
                ['recipe'=>$recipe]
            )

        @endforeach

    </div>

    <!-- RESEP TERBARU -->

    <div class="d-flex justify-content-between mb-3">

        <h4>🆕 Resep Terbaru</h4>

        <a href="{{ route('recipes.index') }}">
            Lihat Semua
        </a>

    </div>

    <div class="row g-4">

        @foreach($latestRecipes as $recipe)

            @include(
                'recipes.partials.recipe-card',
                ['recipe'=>$recipe]
            )

        @endforeach

    </div>

</div>

<!-- SIDEBAR KANAN -->
<div class="col-lg-4">

    <!-- KATEGORI -->

    <div class="card border-0 shadow-sm mb-4">

        <div class="card-body">

            <h5>Kategori Populer</h5>

            @foreach($categories as $cat)

                <span class="badge bg-warning text-dark me-1 mb-1">

                    {{ $cat }}

                </span>

            @endforeach

        </div>

    </div>

    <!-- MEAL PLANNER -->

    <div class="card border-0 shadow-sm mb-4">

        <div class="card-body">

            <h5>
                📅 Meal Planner
            </h5>

            <p class="small text-muted">
                Atur menu harianmu.
            </p>

            <a
                href="{{ route('meal-planner.index') }}"
                class="btn btn-warning w-100">

                Buka Meal Planner

            </a>

        </div>

    </div>

    <!-- FAVORIT -->

    <div class="card border-0 shadow-sm mb-4">

        <div class="card-body">

            <h5>
                ❤️ Favorit Saya
            </h5>

            <a
                href="{{ route('favorites.index') }}"
                class="btn btn-outline-danger w-100">

                Lihat Favorit

            </a>

        </div>

    </div>

    <!-- VIDEO RESEP -->

    <div class="card border-0 shadow-sm">

        <div class="card-body">

            <h5>
                🎥 Video Resep
            </h5>

            @if(isset($latestVideoRecipe))

            <iframe
                width="100%"
                height="220"
                src="{{ $latestVideoRecipe->video_embed_url }}"
                allowfullscreen>
            </iframe>

            <h6 class="mt-3">

                {{ $latestVideoRecipe->title }}

            </h6>

            @else

            <div class="text-muted">

                Belum ada video resep

            </div>

            @endif

        </div>

    </div>

</div>
```

</div>

@endsection
