@extends('layouts.app')

@section('content')
    <div class="row g-4">
        <div class="col-lg-8">
            <h2 class="mb-3">Edit Resep</h2>

            <form action="{{ route('recipes.update', $recipe->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Judul Resep</label>
                            <input type="text" name="title" class="form-control" value="{{ old('title', $recipe->title) }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Deskripsi</label>
                            <textarea name="description" class="form-control" required>{{ old('description', $recipe->description) }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Kategori</label>
                            <input type="text" name="category" class="form-control" value="{{ old('category', $recipe->category) }}" required>
                        </div>

                        <div class="row g-3">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Porsi</label>
                                    <input type="number" name="servings" class="form-control" value="{{ old('servings', $recipe->servings) }}" min="1" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Waktu Persiapan (menit)</label>
                                    <input type="number" name="prep_time" class="form-control" value="{{ old('prep_time', $recipe->prep_time) }}" min="0" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Waktu Memasak (menit)</label>
                                    <input type="number" name="cook_time" class="form-control" value="{{ old('cook_time', $recipe->cook_time) }}" min="0" required>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Tingkat Kesulitan</label>
                            <select name="difficulty" class="form-control" required>
                                <option value="Mudah" {{ old('difficulty', $recipe->difficulty) == 'Mudah' ? 'selected' : '' }}>Mudah</option>
                                <option value="Sedang" {{ old('difficulty', $recipe->difficulty) == 'Sedang' ? 'selected' : '' }}>Sedang</option>
                                <option value="Sulit" {{ old('difficulty', $recipe->difficulty) == 'Sulit' ? 'selected' : '' }}>Sulit</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Penulis</label>
                            <input type="text" name="author_name" class="form-control" value="{{ old('author_name', $recipe->author_name) }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Gambar Saat Ini</label>
                            <div>
                                @if($recipe->image_url)
                                    <img src="{{ $recipe->image_url }}" alt="current image" style="max-height:200px; display:block; margin-bottom:10px;">
                                @else
                                    <span class="text-muted">Tidak ada gambar</span>
                                @endif
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Link Gambar (opsional)</label>
                            <input type="url" name="image" class="form-control" value="{{ old('image', $recipe->image) }}" placeholder="https://images.unsplash.com/...">
                            <div class="form-text">Isi jika ingin mengganti dengan link</div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Unggah Gambar (opsional)</label>
                            <input type="file" name="image_file" class="form-control">
                            <small class="text-muted">Jika diisi, file ini akan menggantikan gambar lama</small>
                        </div>
                    </div>
                </div>

                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between gap-3 mb-3">
                            <h4 class="mb-0">Bahan Resep</h4>
                            <button type="button" class="btn btn-sm btn-outline-primary" id="addIngredientBtn">+ Tambah Baris</button>
                        </div>

                        <div id="ingredientsContainer">
                            @php
                                $ingredients = $recipe->ingredients->sortBy('sort_order');
                                $i = 0;
                            @endphp

                            @foreach ($ingredients as $ingredient)
                                <div class="ingredient-row border rounded-3 p-3 mb-3">
                                    <div class="row g-3 align-items-end">
                                        <div class="col-md-2">
                                            <label class="form-label small">Urutan</label>
                                            <input type="number" name="recipe_ingredients[{{ $i }}][sort_order]" class="form-control" value="{{ old('recipe_ingredients.' . $i . '.sort_order', $ingredient->sort_order) }}">
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label small">Nama Bahan</label>
                                            <input type="text" name="recipe_ingredients[{{ $i }}][ingredient_name]" class="form-control" value="{{ old('recipe_ingredients.' . $i . '.ingredient_name', $ingredient->ingredient_name) }}" required>
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label small">Jumlah</label>
                                            <input type="number" step="0.01" name="recipe_ingredients[{{ $i }}][quantity]" class="form-control" value="{{ old('recipe_ingredients.' . $i . '.quantity', $ingredient->quantity) }}">
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label small">Satuan</label>
                                            <input type="text" name="recipe_ingredients[{{ $i }}][unit]" class="form-control" value="{{ old('recipe_ingredients.' . $i . '.unit', $ingredient->unit) }}" placeholder="gram / ml / sdm">
                                        </div>
                                        <div class="col-md-2">
                                            <button type="button" class="btn btn-sm btn-outline-danger removeIngredientBtn w-100">Hapus</button>
                                        </div>

                                        <div class="col-12">
                                            <label class="form-label small">Catatan (opsional)</label>
                                            <input type="text" name="recipe_ingredients[{{ $i }}][preparation_note]" class="form-control" value="{{ old('recipe_ingredients.' . $i . '.preparation_note', $ingredient->preparation_note) }}" placeholder="contoh: iris tipis / haluskan">
                                        </div>

                                        <div class="col-12 form-check">
                                            <input type="hidden" name="recipe_ingredients[{{ $i }}][is_optional]" value="0">
                                            <input type="checkbox" class="form-check-input" name="recipe_ingredients[{{ $i }}][is_optional]" value="1" id="optional{{ $i }}" {{ old('recipe_ingredients.' . $i . '.is_optional', $ingredient->is_optional) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="optional{{ $i }}">Opsional</label>
                                        </div>
                                    </div>
                                </div>

                                @php $i++; @endphp
                            @endforeach

                            @if ($ingredients->count() === 0)
                                <div class="ingredient-row border rounded-3 p-3 mb-3">
                                    <div class="row g-3 align-items-end">
                                        <div class="col-md-2">
                                            <label class="form-label small">Urutan</label>
                                            <input type="number" name="recipe_ingredients[0][sort_order]" class="form-control" value="0">
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label small">Nama Bahan</label>
                                            <input type="text" name="recipe_ingredients[0][ingredient_name]" class="form-control" required>
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label small">Jumlah</label>
                                            <input type="number" step="0.01" name="recipe_ingredients[0][quantity]" class="form-control">
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label small">Satuan</label>
                                            <input type="text" name="recipe_ingredients[0][unit]" class="form-control" placeholder="gram / ml / sdm">
                                        </div>
                                        <div class="col-md-2">
                                            <button type="button" class="btn btn-sm btn-outline-danger removeIngredientBtn w-100">Hapus</button>
                                        </div>

                                        <div class="col-12">
                                            <label class="form-label small">Catatan (opsional)</label>
                                            <input type="text" name="recipe_ingredients[0][preparation_note]" class="form-control" placeholder="contoh: iris tipis / haluskan">
                                        </div>

                                        <div class="col-12 form-check">
                                            <input type="hidden" name="recipe_ingredients[0][is_optional]" value="0">
                                            <input type="checkbox" class="form-check-input" name="recipe_ingredients[0][is_optional]" value="1" id="optional0">
                                            <label class="form-check-label" for="optional0">Opsional</label>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <button class="btn btn-success">Simpan Perubahan</button>
            </form>
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm sticky-top" style="top: 1rem;">
                <div class="card-body">
                    <h5 class="mb-3">Tips</h5>
                    <ul class="text-muted small mb-0">
                        <li>Isi minimal 1 bahan.</li>
                        <li>Urutan akan menentukan tampilan bahan.</li>
                        <li>Quantity + unit opsional, tapi disarankan.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <script>
        (function () {
            const container = document.getElementById('ingredientsContainer');
            const addBtn = document.getElementById('addIngredientBtn');

            function reindex() {
                const rows = container.querySelectorAll('.ingredient-row');
                rows.forEach((row, idx) => {
                    row.querySelectorAll('input, select, textarea').forEach(el => {
                        if (!el.name) return;
                        el.name = el.name.replace(/recipe_ingredients\[\d+\]/, `recipe_ingredients[${idx}]`);

                        if (el.id && el.id.startsWith('optional')) {
                            el.id = `optional${idx}`;
                        }
                    });

                    const hidden = row.querySelector('input[type="hidden"][name*="[is_optional]"]');
                    const checkbox = row.querySelector('input[type="checkbox"][name*="[is_optional]"]');
                    if (hidden && checkbox) {
                        hidden.name = `recipe_ingredients[${idx}][is_optional]`;
                        checkbox.name = `recipe_ingredients[${idx}][is_optional]`;
                        checkbox.id = `optional${idx}`;

                        const label = row.querySelector(`label[for="optional${idx}"]`);
                        if (label) label.setAttribute('for', `optional${idx}`);
                    }
                });
            }

            function bindRemoveButtons() {
                container.querySelectorAll('.removeIngredientBtn').forEach(btn => {
                    btn.onclick = () => {
                        const row = btn.closest('.ingredient-row');
                        if (!row) return;
                        row.remove();
                        reindex();
                    };
                });
            }

            addBtn.addEventListener('click', () => {
                const idx = container.querySelectorAll('.ingredient-row').length;

                const wrapper = document.createElement('div');
                wrapper.className = 'ingredient-row border rounded-3 p-3 mb-3';

                wrapper.innerHTML = `
                    <div class="row g-3 align-items-end">
                        <div class="col-md-2">
                            <label class="form-label small">Urutan</label>
                            <input type="number" name="recipe_ingredients[${idx}][sort_order]" class="form-control" value="${idx}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small">Nama Bahan</label>
                            <input type="text" name="recipe_ingredients[${idx}][ingredient_name]" class="form-control" required>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label small">Jumlah</label>
                            <input type="number" step="0.01" name="recipe_ingredients[${idx}][quantity]" class="form-control">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label small">Satuan</label>
                            <input type="text" name="recipe_ingredients[${idx}][unit]" class="form-control" placeholder="gram / ml / sdm">
                        </div>
                        <div class="col-md-2">
                            <button type="button" class="btn btn-sm btn-outline-danger removeIngredientBtn w-100">Hapus</button>
                        </div>

                        <div class="col-12">
                            <label class="form-label small">Catatan (opsional)</label>
                            <input type="text" name="recipe_ingredients[${idx}][preparation_note]" class="form-control" placeholder="contoh: iris tipis / haluskan">
                        </div>

                        <div class="col-12 form-check">
                            <input type="hidden" name="recipe_ingredients[${idx}][is_optional]" value="0">
                            <input type="checkbox" class="form-check-input" name="recipe_ingredients[${idx}][is_optional]" value="1" id="optional${idx}">
                            <label class="form-check-label" for="optional${idx}">Opsional</label>
                        </div>
                    </div>
                `;

                container.appendChild(wrapper);
                bindRemoveButtons();
            });

            bindRemoveButtons();
        })();
    </script>
@endsection

