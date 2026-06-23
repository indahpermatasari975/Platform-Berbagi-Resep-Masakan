@extends('layouts.app')

@section('title', 'Login')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-5">

            <div class="card shadow-sm border-0">
                <div class="card-body p-4">

                    <h3 class="text-center mb-4">
                        Login ResepKita
                    </h3>

                    <form action="{{ route('login.process') }}" method="POST">

                        @csrf

                        <div class="mb-3">
                            <label>Email</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label>Password</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>
                        <div class="text-center mt-3">
                            Belum punya akun?

                            <a href="{{ route('register') }}">
                                Daftar Sekarang
                            </a>
                        </div>

                        <button class="btn btn-warning w-100 text-white">
                            Login
                        </button>

                    </form>

                </div>
            </div>

        </div>
    </div>
@endsection
