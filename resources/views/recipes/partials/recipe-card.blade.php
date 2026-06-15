@php
    $avg = (float) ($recipe->rating ?? 0);
    $count = (int) ($recipe->total_ratings ?? 0);
    $isFavorited = $recipe->isInFavorites();
@endphp

<div class="col-6 col-sm-4 col-lg-3">
    <div class="recipe-card h-100">
        <a href="{{ route('recipes.show', $recipe->id) }}" class="text-decoration-none text-reset">
            <div class="position-relative">
                @if ($recipe->image_url)
                    <img src="{{ $recipe->image_url }}" class="recipe-card__img" alt="{{ $recipe->title }}">
                @else
                    <div class="recipe-card__placeholder">🍽️</div>
                @endif
                <div class="recipe-card__overlay">
                    <span class="badge bg-warning text-dark">{{ $recipe->difficulty }}</span>
                </div>

                {{-- Favorite toggle overlay --}}
                <div class="position-absolute" style="top:8px; right:8px; z-index:5">
                    @if ($isFavorited)
                        <form action="{{ route('recipes.favorite.destroy', $recipe->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger" title="Unfavorite">❤️</button>
                        </form>
                    @else
                        <form action="{{ route('recipes.favorite', $recipe->id) }}" method="POST">
                            @csrf
                            <button class="btn btn-sm btn-outline-danger" title="Favorit">🤍</button>
                        </form>
                    @endif
            <div class="p-3">
                <h5 class="recipe-card__title">{{ $recipe->title }}</h5>
                <div class="text-muted small mb-2">{{ $recipe->category }}</div>

                <div class="d-flex align-items-center justify-content-between">
                    <div class="small">
                        <span class="fw-semibold">{{ number_format($avg, 1) }}</span>
                        <span class="text-muted">/ 5</span>
                        <span class="text-muted">({{ $count }})</span>
                    </div>
                    <span class="btn btn-sm btn-warning">Detail</span>
                </div>
            </div>
        </a>
    </div>
</div>

