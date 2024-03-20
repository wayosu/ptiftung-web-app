@extends('admin.layouts.app')

@push('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/css/lightbox.min.css"
        integrity="sha512-ZKX+BvQihRJPA8CROKBhDNvoc2aDMOdAlcm7TUQY+35XYtrd3yh95QOOhsPDQY9QnKE0Wqag9y38OIgEvb88cA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <style>
        .gallery-image-container {
            position: relative;
            display: block;
            width: 100%;
            height: 250px;
            overflow: hidden;
        }

        .gallery-image {
            display: block;
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: all 0.3s ease;
        }

        .gallery-image-container:hover .gallery-image {
            transform: scale(1.1);
            opacity: 0.5;
        }

        @media screen and (max-width: 1440px) {
            .gallery-image-container {
                height: 200px;
            }
        }

        @media screen and (max-width: 1200px) {
            .gallery-image-container {
                height: 200px;
            }
        }

        @media screen and (max-width: 576px) {
            .gallery-image-container {
                height: 150px;
            }
        }
    </style>
@endpush

@section('content')
    <!-- Konten Header -->
    <header class="page-header page-header-compact page-header-light border-bottom bg-white mb-4">
        <div class="container-xl px-4">
            <div class="page-header-content">
                <div class="row align-items-center justify-content-between pt-3">
                    <div class="col-auto mb-3">
                        <h1 class="page-header-title">
                            <div class="page-header-icon"><i class="{{ $icon ?? '' }}"></i></div>
                            {{ $title ?? '' }}
                        </h1>
                        <p class="mb-0 small mt-1">
                            {{ $subtitle ?? '' }}
                        </p>
                    </div>
                    <div class="col-12 col-xl-auto mb-3">
                        <a class="btn btn-sm btn-light text-primary" href="{{ route('prasarana.index') }}">
                            <i class="fa-solid fa-arrow-left me-1"></i>
                            Kembali
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Konten Halaman Utama -->
    <div class="container-xl px-4 mt-4">
        <div class="card">
            <div class="card-body p-2 overflow-hidden">
                <div class="row g-2 row-cols-2 row-cols-md-3 row-cols-lg-4 row-cols-xl-5">
                    @foreach ($prasaranaImages as $image)
                        <div class="col">
                            <a href="{{ asset('storage/fasilitas/prasarana/' . $image) }}" data-lightbox="image"
                                data-title="{{ $image ?? '' }}" class="gallery-image-container">
                                <img src="{{ asset('storage/fasilitas/prasarana/' . $image) }}"
                                    alt="prasarana-image-{{ $image ?? '' }}" class="gallery-image rounded" />
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script src="{{ asset('assets/admin/libs/jquery/jquery-3.7.1.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/js/lightbox.min.js"
        integrity="sha512-k2GFCTbp9rQU412BStrcD/rlwv1PYec9SNrkbQlo6RZCf75l6KcC3UwDY8H5n5hl4v77IDtIPwOk9Dqjs/mMBQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
@endpush
