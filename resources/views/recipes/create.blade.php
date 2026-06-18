@extends('layouts.app')

@section('title', 'Tambah Resep - ResepKita')

@section('content')
<div class="row g-4">
    <div class="col-lg-8">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <div>
                <h2 class="fw-bold mb-1">Tambah Resep</h2>
                <p class="text-muted mb-0">Lengkapi informasi resep, bahan, gambar, dan video jika ada.</p>
            </div>
            <a href="{{ route('recipes.index') }}" class="btn btn-outline-secondary">Kembali</a>
        </div>

        <form action="{{ route('recipes.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="form-section p-4 mb-4">
                <h4 class="fw-bold mb-3">Informasi Resep</h4>

                <div class="mb-3">
                    <label class="form-label">Judul Resep</label>
                    <input type="text" name="title" class="form-control" value="{{ old('title') }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Deskripsi</label>
                    <textarea name="description" rows="4" class="form-control" placeholder="Ceritakan singkat tentang resep ini." required>{{ old('description') }}</textarea>
                </div>

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Kategori</label>
                        <input type="text" name="category" class="form-control" value="{{ old('category') }}" placeholder="Makanan Indonesia" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Penulis</label>
                        <input type="text" name="author_name" class="form-control" value="{{ old('author_name') }}" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Porsi</label>
                        <input type="number" name="servings" min="1" class="form-control" value="{{ old('servings', 1) }}" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Persiapan (menit)</label>
                        <input type="number" name="prep_time" min="1" class="form-control" value="{{ old('prep_time', 10) }}" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Memasak (menit)</label>
                        <input type="number" name="cook_time" min="1" class="form-control" value="{{ old('cook_time', 20) }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Tingkat Kesulitan</label>
                        <select name="difficulty" class="form-select" required>
                            @foreach (['Mudah', 'Sedang', 'Sulit'] as $level)
                                <option value="{{ $level }}" @selected(old('difficulty') === $level)>{{ $level }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Video YouTube (opsional)</label>
                        <input type="url" name="video_url" class="form-control" value="{{ old('video_url') }}" placeholder="https://www.youtube.com/watch?v=...">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Link Gambar (opsional)</label>
                        <input type="text" name="image" class="form-control" value="{{ old('image') }}" placeholder="Tempel alamat gambar di sini">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Upload Gambar (opsional)</label>
                        <input type="file" name="image_file" class="form-control" accept="image/*">
                    </div>
                </div>
            </div>

            <div class="form-section p-4 mb-4">
                <h4 class="fw-bold mb-3">Bahan Resep</h4>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Bahan Utama</label>
                    <div class="form-text mb-2">Tulis satu bahan per baris. Contoh: 500 ml santan atau 2 batang serai, memarkan.</div>
                    <textarea name="main_ingredients_text" rows="6" class="form-control" placeholder="1 ekor ayam, potong 8 bagian&#10;500 ml santan&#10;2 batang serai, memarkan&#10;3 lembar daun salam">{{ old('main_ingredients_text') }}</textarea>
                </div>

                <div class="mb-0">
                    <label class="form-label fw-semibold">Bumbu Halus</label>
                    <div class="form-text mb-2">Isi bumbu yang perlu diulek atau diblender.</div>
                    <textarea name="ground_spices_text" rows="6" class="form-control" placeholder="8 siung bawang merah&#10;4 siung bawang putih&#10;5 buah cabai merah&#10;3 butir kemiri">{{ old('ground_spices_text') }}</textarea>
                </div>
            </div>

            <div class="form-section p-4 mb-4">
                <h4 class="fw-bold mb-3">Langkah Memasak</h4>
                <div class="form-text mb-2">Tulis satu langkah per baris agar tersimpan berurutan di database.</div>
                <textarea name="steps_text" rows="7" class="form-control" placeholder="Tumis bumbu halus sampai harum&#10;Masukkan ayam, aduk sampai berubah warna&#10;Tuang santan lalu masak sampai matang" required>{{ old('steps_text') }}</textarea>
            </div>

            <div class="d-flex gap-2">
                <button class="btn btn-success fw-semibold">
                    <i class="bi bi-save"></i>
                    Simpan Resep
                </button>
                <a href="{{ route('recipes.index') }}" class="btn btn-outline-secondary">Batal</a>
            </div>
        </form>
    </div>

    <div class="col-lg-4">
        <div class="card border-0 shadow-sm sticky-top" style="top: 6rem;">
            <div class="card-body">
                <h5 class="fw-bold mb-3">Panduan Singkat</h5>
                <ul class="text-muted small mb-0">
                    <li>Tulis bahan satu per baris agar mudah dibaca di halaman detail.</li>
                    <li>Tulis langkah memasak satu per baris agar urutannya tersimpan rapi.</li>
                    <li>Gunakan link gambar atau upload file, salah satu saja sudah cukup.</li>
                    <li>Video YouTube otomatis diubah menjadi tampilan embed di halaman detail.</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
