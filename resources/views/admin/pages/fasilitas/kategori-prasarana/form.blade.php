@extends('admin.layouts.app')

@section('content')
    <!-- Header content-->
    <header class="page-header page-header-compact page-header-light border-bottom bg-white mb-4">
        <div class="container-xl px-4">
            <div class="page-header-content">
                <div class="row align-items-center justify-content-between pt-3">
                    <div class="col-auto mb-3">
                        <h1 class="page-header-title">
                            <div class="page-header-icon"><i data-feather="{{ $icon ?? '' }}"></i></div>
                            {{ $title ?? '' }}
                        </h1>
                        <p class="mb-0 small mt-1">
                            {{ $subtitle ?? '' }}
                        </p>
                    </div>
                    <div class="col-12 col-xl-auto mb-3">
                        <a class="btn btn-sm btn-light text-primary" href="{{ route('kategoriPrasarana.index') }}">
                            <i class="fa-solid fa-arrow-left me-1"></i>
                            Kembali
                        </a>
                        <a class="btn btn-sm btn-light text-primary" href="{{ route('prasarana.index') }}">
                            <i class="fa-solid fa-list me-1"></i>
                            Prasarana
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <div class="container-xl px-4 mt-4">
        <form
            action="@if (isset($prasaranaKategori)) {{ route('kategoriPrasarana.update', $prasaranaKategori->id) }} @else {{ route('kategoriPrasarana.store') }} @endif"
            method="POST" class="row">
            @csrf
            @if (isset($prasaranaKategori))
                @method('PUT')
            @endif

            <div class="col-xl-6">
                <div class="card mb-4">
                    <div class="card-header">Form {{ $title ?? '' }}</div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="small mb-1" for="kategoriPrasaranaField">
                                Kategori Prasarana
                                <span class="text-danger">*</span>
                            </label>
                            <input class="form-control @error('prasarana_kategori') is-invalid @enderror"
                                name="prasarana_kategori" id="kategoriPrasaranaField" type="text" placeholder="..."
                                value="{{ old('sarana_kategori', $prasaranaKategori->prasarana_kategori ?? '') }}" />
                            @error('prasarana_kategori')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <button class="btn btn-light" type="reset">
                            <i class="fa-solid fa-rotate-left me-1"></i>
                            Atur Ulang
                        </button>
                        <button class="btn btn-primary" type="submit">
                            <i class="fa-solid fa-floppy-disk me-1"></i>
                            @if (isset($prasaranaKategori))
                                Perbarui
                            @else
                                Simpan
                            @endif
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection