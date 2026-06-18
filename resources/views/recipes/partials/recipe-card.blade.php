@php
    $avg = (float) ($recipe->rating ?? 0);
    $count = (int) ($recipe->total_ratings ?? 0);
    $isFavorited = method_exists($recipe, 'isInFavorites') ? $recipe->isInFavorites() : false;
@endphp

<div class="col-xl-3 col-lg-4 col-md-6">
    <div class="card border-0 shadow-sm h-100 recipe-card">
        <div class="position-relative">
            <a href="{{ route('recipes.show', $recipe) }}">
                @if ($recipe->image_url)
                    <img src="{{ $recipe->image_url }}" class="card-img-top recipe-image" alt="{{ $recipe->title }}">
                @else
                    <div class="recipe-image recipe-placeholder">
                        <i class="bi bi-image"></i>
                    </div>
                @endif
            </a>

            <div class="position-absolute top-0 end-0 m-2">
                @if ($isFavorited)
                    <form action="{{ route('recipes.favorite.destroy', $recipe) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm rounded-circle" title="Hapus dari favorit">
                            <i class="bi bi-heart-fill"></i>
                        </button>
                    </form>
                @else
                    <form action="{{ route('recipes.favorite', $recipe) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-light btn-sm rounded-circle" title="Tambah favorit">
                            <i class="bi bi-heart"></i>
                        </button>
                    </form>
                @endif
            </div>
        </div>

        <div class="card-body">
            <div class="d-flex justify-content-between align-items-start gap-2 mb-2">
                <h5 class="fw-bold mb-0 recipe-title">{{ $recipe->title }}</h5>
                <span class="badge bg-warning text-dark">{{ $recipe->difficulty }}</span>
            </div>
            <p class="text-muted small mb-2">{{ $recipe->category }}</p>

            <div class="d-flex align-items-center gap-2 mb-3">
                <span class="text-warning"><i class="bi bi-star-fill"></i></span>
                <span class="fw-semibold">{{ number_format($avg, 1) }}</span>
                <span class="text-muted small">({{ $count }})</span>
            </div>

            <div class="d-flex justify-content-between text-muted small">
                <span><i class="bi bi-clock"></i> {{ $recipe->cook_time }} menit</span>
                <span><i class="bi bi-people"></i> {{ $recipe->servings }} porsi</span>
            </div>
        </div>

        <div class="card-footer bg-white border-0 pt-0">
            <a href="{{ route('recipes.show', $recipe) }}" class="btn btn-warning w-100 fw-semibold">
                Lihat Resep
            </a>
        </div>
    </div>
</div>
