@extends('layouts.app')

@section('title', 'Edit Resep - ResepKita')

@section('content')
<div class="row g-4">
    <div class="col-lg-8">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <div>
                <h2 class="fw-bold mb-1">Edit Resep</h2>
                <p class="text-muted mb-0">Perbarui informasi resep dan daftar bahan.</p>
            </div>
            <a href="{{ route('recipes.show', $recipe) }}" class="btn btn-outline-secondary">Kembali</a>
        </div>

        <form action="{{ route('recipes.update', $recipe) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="form-section p-4 mb-4">
                <h4 class="fw-bold mb-3">Informasi Resep</h4>

                <div class="mb-3">
                    <label class="form-label">Judul Resep</label>
                    <input type="text" name="title" class="form-control" value="{{ old('title', $recipe->title) }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Deskripsi</label>
                    <textarea name="description" rows="4" class="form-control" required>{{ old('description', $recipe->description) }}</textarea>
                </div>

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Kategori</label>
                        <input type="text" name="category" class="form-control" value="{{ old('category', $recipe->category) }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Penulis</label>
                        <input type="text" name="author_name" class="form-control" value="{{ old('author_name', $recipe->author_name) }}" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Porsi</label>
                        <input type="number" name="servings" min="1" class="form-control" value="{{ old('servings', $recipe->servings) }}" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Persiapan (menit)</label>
                        <input type="number" name="prep_time" min="1" class="form-control" value="{{ old('prep_time', $recipe->prep_time) }}" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Memasak (menit)</label>
                        <input type="number" name="cook_time" min="1" class="form-control" value="{{ old('cook_time', $recipe->cook_time) }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Tingkat Kesulitan</label>
                        <select name="difficulty" class="form-select" required>
                            @foreach (['Mudah', 'Sedang', 'Sulit'] as $level)
                                <option value="{{ $level }}" @selected(old('difficulty', $recipe->difficulty) === $level)>{{ $level }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Video YouTube (opsional)</label>
                        <input type="url" name="video_url" class="form-control" value="{{ old('video_url', $recipe->video_url) }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Link Gambar (opsional)</label>
                        <input type="text" name="image" class="form-control" value="{{ old('image', $recipe->image) }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Upload Gambar Baru (opsional)</label>
                        <input type="file" name="image_file" class="form-control" accept="image/*">
                    </div>
                </div>

                @if ($recipe->image_url)
                    <div class="mt-3">
                        <div class="text-muted small mb-2">Gambar saat ini</div>
                        <img src="{{ $recipe->image_url }}" alt="{{ $recipe->title }}" class="rounded-3" style="max-height: 180px; object-fit: cover;">
                    </div>
                @endif
            </div>

            <div class="form-section p-4 mb-4">
                <h4 class="fw-bold mb-3">Bahan Resep</h4>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Bahan Utama</label>
                    <div class="form-text mb-2">Tulis satu bahan per baris. Contoh: 500 ml santan atau 2 batang serai, memarkan.</div>
                    <textarea name="main_ingredients_text" rows="6" class="form-control" placeholder="1 ekor ayam, potong 8 bagian&#10;500 ml santan&#10;2 batang serai, memarkan&#10;3 lembar daun salam">{{ old('main_ingredients_text', $mainIngredientsText) }}</textarea>
                </div>

                <div class="mb-0">
                    <label class="form-label fw-semibold">Bumbu Halus</label>
                    <div class="form-text mb-2">Isi bumbu yang perlu diulek atau diblender.</div>
                    <textarea name="ground_spices_text" rows="6" class="form-control" placeholder="8 siung bawang merah&#10;4 siung bawang putih&#10;5 buah cabai merah&#10;3 butir kemiri">{{ old('ground_spices_text', $groundSpicesText) }}</textarea>
                </div>
            </div>

            <div class="form-section p-4 mb-4">
                <h4 class="fw-bold mb-3">Langkah Memasak</h4>
                <div class="form-text mb-2">Tulis satu langkah per baris agar tersimpan berurutan di database.</div>
                <textarea name="steps_text" rows="7" class="form-control" placeholder="Tumis bumbu halus sampai harum&#10;Masukkan ayam, aduk sampai berubah warna&#10;Tuang santan lalu masak sampai matang" required>{{ old('steps_text', $stepsText) }}</textarea>
            </div>

            <div class="d-flex gap-2">
                <button class="btn btn-success fw-semibold">
                    <i class="bi bi-save"></i>
                    Simpan Perubahan
                </button>
                <a href="{{ route('recipes.show', $recipe) }}" class="btn btn-outline-secondary">Batal</a>
            </div>
        </form>
    </div>

    <div class="col-lg-4">
        <div class="card border-0 shadow-sm sticky-top" style="top: 6rem;">
            <div class="card-body">
                <h5 class="fw-bold mb-3">Catatan Edit</h5>
                <p class="text-muted small mb-0">
                    Tulis bahan dan langkah memasak satu per baris agar data tersimpan rapi.
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
