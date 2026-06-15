@extends('layouts.app')

@section('content')
    <div class="row g-4">
        <div class="col-lg-8">
            <h2 class="mb-3">Tambah Resep</h2>

            <form action="{{ route('recipes.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Judul Resep</label>
                            <input type="text" name="title" class="form-control" value="{{ old('title') }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Deskripsi</label>
                            <textarea name="description" class="form-control" required>{{ old('description') }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Kategori</label>
                            <input type="text" name="category" class="form-control" value="{{ old('category') }}" required>
                        </div>

                        <div class="row g-3">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Porsi</label>
                                    <input type="number" name="servings" min="1" class="form-control" value="{{ old('servings') }}" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Waktu Persiapan (menit)</label>
                                    <input type="number" name="prep_time" min="0" class="form-control" value="{{ old('prep_time') }}" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Waktu Memasak (menit)</label>
                                    <input type="number" name="cook_time" min="0" class="form-control" value="{{ old('cook_time') }}" required>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Tingkat Kesulitan</label>
                            <select name="difficulty" class="form-control" required>
                                <option value="Mudah" {{ old('difficulty') == 'Mudah' ? 'selected' : '' }}>Mudah</option>
                                <option value="Sedang" {{ old('difficulty') == 'Sedang' ? 'selected' : '' }}>Sedang</option>
                                <option value="Sulit" {{ old('difficulty') == 'Sulit' ? 'selected' : '' }}>Sulit</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Penulis</label>
                            <input type="text" name="author_name" class="form-control" value="{{ old('author_name') }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Link Gambar (opsional)</label>
                            <input type="url" name="image" class="form-control" value="{{ old('image') }}" placeholder="https://images.unsplash.com/...">
                            <div class="form-text">Atau unggah file gambar di bawah</div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Unggah Gambar (opsional)</label>
                            <input type="file" name="image_file" class="form-control">
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
                        </div>

                    </div>
                </div>

                <button class="btn btn-success">Simpan</button>

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
                        // update checkbox id if exists
                        if (el.id && el.id.startsWith('optional')) {
                            el.id = `optional${idx}`;
                            const label = row.querySelector(`label[for="optional${idx}"]`);
                        }
                    });

                    // update hidden+checkbox optional properly
                    const hidden = row.querySelector('input[type="hidden"][name*="[is_optional]"]');
                    const checkbox = row.querySelector('input[type="checkbox"][name*="[is_optional]"]');
                    const label = row.querySelector('.form-check-label');
                    if (hidden && checkbox) {
                        hidden.name = `recipe_ingredients[${idx}][is_optional]`;
                        checkbox.name = `recipe_ingredients[${idx}][is_optional]`;
                        checkbox.id = `optional${idx}`;
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

