@extends('layouts.app')

@section('title', $recipe->title . ' - ResepKita')

@section('content')
@php
    $avg = (float) ($recipe->rating ?? 0);
    $count = (int) ($recipe->total_ratings ?? 0);
    $isFavorited = $recipe->isInFavorites();
    $canManageRecipe = auth()->check()
        && (auth()->user()->role === 'admin' || (int) $recipe->user_id === (int) auth()->id());
@endphp

<div class="row g-4">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm overflow-hidden">
            @if ($recipe->image_url)
                <img src="{{ $recipe->image_url }}" class="w-100 detail-image" alt="{{ $recipe->title }}">
            @else
                <div class="detail-image recipe-placeholder">
                    <i class="bi bi-image"></i>
                </div>
            @endif

            <div class="card-body p-4">
                <div class="d-flex flex-column flex-md-row justify-content-between gap-3">
                    <div>
                        <div class="d-flex flex-wrap gap-2 mb-2">
                            <span class="badge bg-warning text-dark">{{ $recipe->category }}</span>
                            <span class="badge bg-light text-dark border">{{ $recipe->difficulty }}</span>
                        </div>
                        <h2 class="fw-bold mb-2">{{ $recipe->title }}</h2>
                        <div class="text-muted">Oleh {{ $recipe->author_name }}</div>
                    </div>

                    <div class="text-md-end">
                        <div class="h4 mb-1">
                            <i class="bi bi-star-fill text-warning"></i>
                            {{ number_format($avg, 1) }}
                            <span class="text-muted fs-6">/ 5</span>
                        </div>
                        <div class="text-muted small">Dari {{ $count }} rating</div>
                        <div class="rating-stars" aria-label="Rating {{ $avg }} dari 5">
                            @for ($i = 1; $i <= 5; $i++)
                                <span class="star {{ $avg >= $i ? 'filled' : '' }}">&#9733;</span>
                            @endfor
                        </div>
                    </div>
                </div>

                <hr>

                <div class="row g-3 mb-4">
                    <div class="col-sm-4">
                        <div class="p-3 bg-light rounded-3">
                            <div class="text-muted small">Porsi</div>
                            <div class="fw-semibold fs-5">{{ $recipe->servings }} orang</div>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="p-3 bg-light rounded-3">
                            <div class="text-muted small">Persiapan</div>
                            <div class="fw-semibold fs-5">{{ $recipe->prep_time }} menit</div>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="p-3 bg-light rounded-3">
                            <div class="text-muted small">Memasak</div>
                            <div class="fw-semibold fs-5">{{ $recipe->cook_time }} menit</div>
                        </div>
                    </div>
                </div>

                @if ($recipe->video_embed_url)
                    <section class="mb-4">
                        <h4 class="fw-bold mb-3">Video Resep</h4>
                        <div class="ratio ratio-16x9 rounded overflow-hidden">
                            <iframe src="{{ $recipe->video_embed_url }}" title="Video resep {{ $recipe->title }}" allowfullscreen></iframe>
                        </div>
                    </section>
                @endif

                <section class="mb-4">
                    <h4 class="fw-bold mb-3">Deskripsi</h4>
                    <div class="p-3 bg-light rounded-3 lh-lg">
                        {!! nl2br(e($recipe->description)) !!}
                    </div>
                </section>

                <section class="mb-4">
                    <h4 class="fw-bold mb-3">Bahan-Bahan</h4>

                    @if ($recipe->main_ingredients)
                        <div class="mb-3">
                            <h5 class="fw-bold mb-2">Bahan Utama</h5>
                            <div class="p-3 bg-light rounded-3 lh-lg">
                                {!! nl2br(e($recipe->main_ingredients)) !!}
                            </div>
                        </div>
                    @endif

                    @if ($recipe->ground_spices)
                        <div class="mb-3">
                            <h5 class="fw-bold mb-2">Bumbu Halus</h5>
                            <div class="p-3 bg-light rounded-3 lh-lg">
                                {!! nl2br(e($recipe->ground_spices)) !!}
                            </div>
                        </div>
                    @endif

                    @if (!$recipe->main_ingredients && !$recipe->ground_spices)
                        <div class="empty-state text-center">Belum ada bahan.</div>
                    @endif
                </section>

                <section>
                    <h4 class="fw-bold mb-3">Langkah Memasak</h4>
                    @if ($recipe->steps->count())
                        <div class="list-group list-group-flush">
                            @foreach ($recipe->steps as $step)
                                <div class="list-group-item px-0 d-flex align-items-start gap-3">
                                    <span class="step-number flex-shrink-0">{{ $step->step_number }}</span>
                                    <div class="lh-lg">{{ $step->clean_instruction }}</div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="empty-state text-center">Belum ada langkah memasak.</div>
                    @endif
                </section>
            </div>
        </div>
    </div>

    <aside class="col-lg-4">
        <div class="card border-0 shadow-sm sticky-top" style="top: 6rem;">
            <div class="card-body">
                <h5 class="fw-bold mb-3">Aksi Resep</h5>

                <div class="d-grid gap-2 mb-3">
                    @guest
                        <a href="{{ route('login') }}" class="btn btn-warning fw-semibold">
                            <i class="bi bi-box-arrow-in-right"></i>
                            Login untuk menggunakan fitur
                        </a>
                    @else
                        @if ($isFavorited)
                            <form action="{{ route('recipes.favorite.destroy', $recipe) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger w-100">
                                    <i class="bi bi-heart-fill"></i>
                                    Hapus Favorit
                                </button>
                            </form>
                        @else
                            <form action="{{ route('recipes.favorite', $recipe) }}" method="POST">
                                @csrf
                                <button class="btn btn-outline-danger w-100">
                                    <i class="bi bi-heart"></i>
                                    Simpan Favorit
                                </button>
                            </form>
                        @endif

                        @if ($canManageRecipe)
                            <a href="{{ route('recipes.edit', $recipe) }}" class="btn btn-warning fw-semibold">
                                <i class="bi bi-pencil-square"></i>
                                Edit Resep
                            </a>

                            <form action="{{ route('recipes.destroy', $recipe) }}" method="POST" onsubmit="return confirm('Hapus resep ini?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-outline-danger w-100">
                                    <i class="bi bi-trash"></i>
                                    Hapus Resep
                                </button>
                            </form>
                        @endif
                    @endguest
                </div>

                @auth
                    <hr>

                    <h5 class="fw-bold mb-3">Beri Rating</h5>
                    <form action="{{ route('recipes.rate', $recipe) }}" method="POST" class="mb-4">
                        @csrf
                        <div class="d-flex flex-wrap gap-2 mb-3">
                            @for ($i = 1; $i <= 5; $i++)
                                <input type="radio" class="btn-check" name="rating_value" id="rating{{ $i }}" value="{{ $i }}" @checked($i === 5 && $count === 0)>
                                <label class="btn btn-outline-warning btn-sm" for="rating{{ $i }}">{{ $i }} <i class="bi bi-star-fill"></i></label>
                            @endfor
                        </div>
                        <button class="btn btn-warning w-100 fw-semibold">Kirim Rating</button>
                    </form>

                    <h5 class="fw-bold mb-3">Tambah ke Meal Planner</h5>
                    <form action="{{ route('meal-planner.store') }}" method="POST" class="d-grid gap-2">
                        @csrf
                        <input type="hidden" name="recipe_id" value="{{ $recipe->id }}">
                        <div>
                            <label class="form-label small">Tanggal</label>
                            <input type="date" name="meal_date" class="form-control" required>
                        </div>
                        <div>
                            <label class="form-label small">Jenis</label>
                            <select name="meal_type" class="form-select" required>
                                <option value="Sarapan">Sarapan</option>
                                <option value="Makan Siang">Makan Siang</option>
                                <option value="Makan Malam">Makan Malam</option>
                            </select>
                        </div>
                        <button class="btn btn-warning fw-semibold">Tambahkan</button>
                    </form>
                @endauth

                <hr>
                <a href="{{ route('recipes.index') }}" class="btn btn-outline-secondary w-100">Kembali ke Daftar</a>
            </div>
        </div>
    </aside>
</div>
@endsection
