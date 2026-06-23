@extends('layouts.app')

@section('title', 'Tambah Resep')

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="fw-bold">Tambah Resep Baru</h1>

        <button type="submit" form="recipeForm" class="btn btn-warning text-white">
            <i class="bi bi-save"></i>
            Simpan Resep
        </button>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Periksa kembali input berikut:</strong>
            <ul class="mb-0 mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form id="recipeForm" action="{{ route('recipes.store') }}" method="POST" enctype="multipart/form-data">

        @csrf

        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body">

                <div class="row">

                    <div class="col-md-4">

                        <div class="border rounded p-3 text-center">

                            <img id="preview" src="https://placehold.co/500x300?text=Preview+Gambar"
                                class="img-fluid rounded mb-3">

                            <input type="file" name="image_file" class="form-control" accept="image/*"
                                onchange="previewImage(event)">
                        </div>

                        <div class="mt-3">
                            <label class="form-label">
                                Link Gambar (Opsional)
                            </label>

                            <input type="text" name="image" class="form-control" value="{{ old('image') }}"
                                placeholder="https://...">
                        </div>

                    </div>

                    <div class="col-md-8">

                        <div class="mb-3">
                            <label class="form-label">
                                Judul Resep
                            </label>

                            <input type="text" name="title" class="form-control" value="{{ old('title') }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">
                                Deskripsi Resep
                            </label>

                            <textarea name="description" rows="4" class="form-control" placeholder="Ceritakan resep ini...">{{ old('description') }}</textarea>
                        </div>

                        <div class="row">

                            <div class="col-md-4">
                                <label class="form-label">
                                    Porsi
                                </label>

                                <input type="number" name="servings" class="form-control" value="{{ old('servings', 4) }}"
                                    required>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">
                                    Waktu Persiapan
                                </label>

                                <input type="number" name="prep_time" class="form-control" value="{{ old('prep_time') }}"
                                    required>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">
                                    Waktu Memasak
                                </label>

                                <input type="number" name="cook_time" class="form-control" value="{{ old('cook_time') }}"
                                    required>
                            </div>

                        </div>

                        <div class="row mt-3">

                            <div class="col-md-6">
                                <label class="form-label">
                                    Kategori
                                </label>

                                <input type="text" name="category" class="form-control" value="{{ old('category') }}"
                                    required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">
                                    Penulis
                                </label>

                                <input type="text" name="author_name" class="form-control"
                                    value="{{ old('author_name') }}" required>
                            </div>

                        </div>

                        <div class="row mt-3">

                            <div class="col-md-6">
                                <label class="form-label">
                                    Tingkat Kesulitan
                                </label>

                                <select name="difficulty" class="form-select">

                                    <option value="Mudah">Mudah</option>
                                    <option value="Sedang">Sedang</option>
                                    <option value="Sulit">Sulit</option>

                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">
                                    Video Youtube
                                </label>

                                <input type="url" name="video_url" class="form-control" value="{{ old('video_url') }}">
                            </div>

                        </div>

                    </div>

                </div>

            </div>
        </div>

        <div class="row">

            <div class="col-lg-6">

                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body">

                        <h3 class="fw-bold mb-3">
                            Bahan Utama
                        </h3>

                        <textarea name="main_ingredients_text" rows="10" class="form-control"
                            placeholder="500 gr ayam&#10;200 ml santan&#10;2 batang serai">{{ old('main_ingredients_text') }}</textarea>

                    </div>
                </div>

            </div>

            <div class="col-lg-6">

                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body">

                        <h3 class="fw-bold mb-3">
                            Bumbu Halus
                        </h3>

                        <textarea name="ground_spices_text" rows="10" class="form-control"
                            placeholder="5 bawang merah&#10;3 bawang putih&#10;2 kemiri">{{ old('ground_spices_text') }}</textarea>

                    </div>
                </div>

            </div>

        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-body">

                <h3 class="fw-bold mb-3">
                    Langkah Memasak
                </h3>

                <textarea name="steps_text" rows="8" class="form-control" placeholder="Langkah 1&#10;Langkah 2&#10;Langkah 3"
                    required>{{ old('steps_text') }}</textarea>

                <small class="text-muted">
                    Tulis satu langkah per baris.
                </small>

            </div>
        </div>

    </form>

    <script>
        function previewImage(event) {
            let reader = new FileReader();

            reader.onload = function() {
                document.getElementById('preview').src =
                    reader.result;
            };

            reader.readAsDataURL(
                event.target.files[0]
            );
        }
    </script>

@endsection
