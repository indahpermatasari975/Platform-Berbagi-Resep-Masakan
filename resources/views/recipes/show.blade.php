@extends('layouts.app')

@section('content')
    @php
        $avg = (float) ($recipe->rating ?? 0);
        $count = (int) ($recipe->total_ratings ?? 0);
        $isFavorited = $recipe->isInFavorites();
    @endphp

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm overflow-hidden">
                <div class="ratio" style="--bs-aspect-ratio: 56%">
                    @if ($recipe->image_url)
                        <img src="{{ $recipe->image_url }}" class="w-100 h-100 object-fit-cover" alt="{{ $recipe->title }}">
                    @else
                        <div class="recipe-card__placeholder hero-placeholder">🍲</div>
                    @endif
                </div>

                <div class="card-body p-4">
                    <div class="d-flex flex-column flex-md-row justify-content-between gap-2">
                        <div>
                            <h2 class="mb-1">{{ $recipe->title }}</h2>
                            <div class="text-muted">{{ $recipe->category }} • {{ $recipe->difficulty }}</div>
                        </div>

                        <div class="text-md-end">
                            <div class="d-flex align-items-center justify-content-md-end gap-2">
                                <div class="h5 mb-0">{{ number_format($avg, 1) }} <span class="text-muted">/ 5</span></div>
                                <div class="text-muted small">({{ $count }})</div>
                            </div>
                            <div class="rating-stars" aria-label="Rating {{ $avg }} dari 5">
                                @for ($i = 1; $i <= 5; $i++)
                                    <span class="star {{ $avg >= $i ? 'filled' : '' }}">★</span>
                                @endfor
                            </div>
                        </div>
                    </div>

                    <hr />

                    {{-- YouTube Embed --}}
                    @if ($recipe->video_embed_url)
                        <div class="mb-4">
                            <div class="d-flex align-items-center justify-content-between gap-2 mb-2">
                                <h4 class="mb-0">Video Resep</h4>
                                <span class="badge bg-dark">YouTube</span>
                            </div>
                            <div class="ratio" style="--bs-aspect-ratio: 56%">
                                <iframe
                                    src="{{ $recipe->video_embed_url }}"
                                    title="YouTube video resep"
                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                    allowfullscreen>
                                </iframe>
                            </div>
                        </div>
                    @endif

                    <div class="row g-3">
                        <div class="col-sm-4">
                            <div class="p-3 bg-light rounded-3">
                                <div class="text-muted small">Porsi</div>
                                <div class="fw-semibold fs-5">{{ $recipe->servings }}</div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="p-3 bg-light rounded-3">
                                <div class="text-muted small">Waktu Persiapan</div>
                                <div class="fw-semibold fs-5">{{ $recipe->prep_time }} menit</div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="p-3 bg-light rounded-3">
                                <div class="text-muted small">Waktu Memasak</div>
                                <div class="fw-semibold fs-5">{{ $recipe->cook_time }} menit</div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4">
                        <h4 class="mb-3">Langkah Memasak</h4>
                        <div class="card border-0 bg-light">
                            <div class="card-body">
                                {{-- sementara: gunakan deskripsi sebagai langkah --}}
                                {!! nl2br(e($recipe->description)) !!}
                            </div>
                        </div>
                    </div>

                    <div class="mt-4">
                        <h4 class="mb-3">Bahan-Bahan</h4>

                        <div class="mt-2 mb-3">
                            <h4 class="mb-2">Beri Rating</h4>
                            <div class="text-muted small mb-2">Skala 1–5 bintang</div>

                            <form action="{{ route('recipes.rate', $recipe->id) }}" method="POST" class="d-flex flex-column flex-md-row gap-2 align-items-md-center">
                                @csrf
                                <div class="d-flex gap-2 align-items-center">
                                    @for ($i = 1; $i <= 5; $i++)
                                        <label class="btn btn-sm {{ $i <= $avg ? 'btn-warning' : 'btn-outline-warning' }}" style="cursor:pointer;">
                                            <input type="radio" name="rating_value" value="{{ $i }}" autocomplete="off" class="d-none" {{ $i == 5 && $count == 0 ? 'checked' : '' }}>
                                            {{ $i }}★
                                        </label>
                                    @endfor
                                </div>
                                <button class="btn btn-warning mt-2 mt-md-0">Kirim Rating</button>
                            </form>
                        </div>

                        <div class="card border-0 bg-light">
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-borderless align-middle m-0">
                                        <thead>
                                            <tr class="text-muted">
                                                <th>Urutan</th>
                                                <th>Nama Bahan</th>
                                                <th class="text-end">Jumlah</th>
                                                <th class="text-end">Satuan</th>
                                                <th>Catatan</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($recipe->ingredients->sortBy('sort_order') as $ingredient)
                                                <tr>
                                                    <td class="text-muted small">{{ $ingredient->sort_order }}</td>
                                                    <td class="fw-semibold">{{ $ingredient->ingredient_name }}</td>
                                                    <td class="text-end">{{ $ingredient->quantity }}</td>
                                                    <td class="text-end">{{ $ingredient->unit }}</td>
                                                    <td class="text-muted small">{{ $ingredient->preparation_note }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4">
                        <h4 class="mb-3">Kalkulator Porsi</h4>
                        <div class="row g-3 align-items-end">
                            <div class="col-md-5">
                                <label class="form-label">Porsi yang diinginkan</label>
                                <input id="targetServings" type="number" min="1" class="form-control" value="{{ $recipe->servings }}">
                                <div class="form-text">Skalakan jumlah bahan berdasarkan perbandingan porsi.</div>
                            </div>
                            <div class="col-md-7">
                                <div class="p-3 bg-light rounded-3">
                                    <div class="text-muted small">Skala</div>
                                    <div class="fw-semibold fs-5" id="scaleLabel">1.00x</div>
                                    <div class="text-muted small">Jumlah bahan akan menyesuaikan tanpa reload.</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <script>
                        (function () {
                            const baseServings = Number({{ $recipe->servings }});
                            const input = document.getElementById('targetServings');
                            const scaleLabel = document.getElementById('scaleLabel');

                            const rows = Array.from(document.querySelectorAll('table.table tbody tr'));
                            const original = rows.map(r => {
                                const qty = r.children[2]?.textContent?.trim();
                                const num = Number(qty);
                                return isNaN(num) ? null : num;
                            });

                            function render() {
                                const target = Number(input.value);
                                const scale = baseServings > 0 ? (target / baseServings) : 1;
                                scaleLabel.textContent = scale.toFixed(2) + 'x';

                                rows.forEach((r, idx) => {
                                    const baseQty = original[idx];
                                    const qtyCell = r.children[2];
                                    if (baseQty === null) return;

                                    const newQty = baseQty * scale;
                                    qtyCell.textContent = newQty % 1 === 0 ? newQty.toFixed(0) : newQty.toFixed(2);
                                });
                            }

                            input.addEventListener('input', render);
                        })();
                    </script>

                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm sticky-top" style="top: 1rem;">
                <div class="card-body">
                    <h5 class="mb-3">Aksi</h5>

                    {{-- Favorite button --}}
                    <div class="d-grid gap-2 mb-3">
                        @if ($isFavorited)
                            <form action="{{ route('recipes.favorite.destroy', $recipe->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger">❤️ Unfavorite</button>
                            </form>
                        @else
                            <form action="{{ route('recipes.favorite', $recipe->id) }}" method="POST">
                                @csrf
                                <button class="btn btn-outline-danger">🤍 Favorit</button>
                            </form>
                        @endif
                    </div>

                    <div class="d-grid gap-2">
                        <a href="{{ route('recipes.edit', $recipe->id) }}" class="btn btn-warning">Edit Resep</a>

                        <form action="{{ route('recipes.destroy', $recipe->id) }}" method="POST" onsubmit="return confirm('Hapus resep ini?')">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-outline-danger">Hapus</button>
                        </form>
                    </div>

                    <hr />

                    <div class="text-muted small">Penulis</div>
                    <div class="fw-semibold mb-3">{{ $recipe->author_name }}</div>

                    {{-- Meal planner form --}}
                    @auth
                        <div class="mb-3">
                            <h6 class="mb-2">Tambah ke Meal Planner</h6>
                            <form action="{{ route('meal-planner.store') }}" method="POST" class="d-grid gap-2">
                                @csrf
                                <input type="hidden" name="recipe_id" value="{{ $recipe->id }}">

                                <div class="mb-0">
                                    <label class="form-label small">Tanggal</label>
                                    <input type="date" name="meal_date" class="form-control" required>
                                </div>

                                <div class="mb-0">
                                    <label class="form-label small">Jenis</label>
                                    <select name="meal_type" class="form-control" required>
                                        <option value="Sarapan">Sarapan</option>
                                        <option value="Makan Siang">Makan Siang</option>
                                        <option value="Makan Malam">Makan Malam</option>
                                    </select>
                                </div>

                                <button class="btn btn-warning fw-semibold">Tambah</button>
                            </form>
                        </div>
                    @else
                        <div class="text-muted small mb-3">Login untuk menambahkan meal planner.</div>
                    @endauth

                    <a href="{{ route('recipes.index') }}" class="btn btn-outline-secondary w-100">Kembali ke Daftar</a>
                </div>
            </div>
        </div>
    </div>
@endsection


