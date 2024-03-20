@extends('admin.layouts.app')

@push('css')
    <link href="{{ asset('assets/admin/libs/sweetalert2/css/sweetalert2.min.css') }}" rel="stylesheet" />
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
                        <a class="btn btn-sm btn-light text-primary" href="{{ request()->fullUrl() }}" role="button">
                            <i class="fa-solid fa-arrows-rotate me-1"></i>
                            Segarkan
                        </a>
                        <button id="btnUpdate" type="button" class="btn btn-primary btn-sm">
                            <i class="fa-solid fa-floppy-disk me-1"></i>
                            Perbarui
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Konten Halaman Utama -->
    <div class="container-xl px-4 mt-4">
        <form id="formUpdate" action="{{ route('kontakLokasi.update') }}" method="POST" class="row g-4">
            @csrf
            @method('PUT')
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">Informasi Kontak</div>
                    <div class="card-body overflow-hidden">
                        <div class="mb-3">
                            <label class="small mb-1" for="nomorTeleponField">
                                Nomor Telepon
                            </label>
                            <input type="text" id="nomorTeleponField" name="nomor_telepon"
                                class="form-control @error('nomor_telepon') is-invalid @enderror"
                                value="{{ old('nomor_telepon', $nomorTelepon ?? '') }}" placeholder="+62">
                            @error('nomor_telepon')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="small mb-1" for="emailField">
                                Email
                            </label>
                            <input type="email" id="emailField" name="email"
                                class="form-control @error('email') is-invalid @enderror"
                                value="{{ old('email', $email ?? '') }}" placeholder="email@example.com">
                            @error('email')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="small mb-1" for="facebookField">
                                Facebook
                            </label>
                            <input type="text" id="facebookField" name="link_facebook"
                                class="form-control @error('link_facebook') is-invalid @enderror"
                                value="{{ old('link_facebook', $linkFacebook ?? '') }}" placeholder="...">
                            <span class="text-xs text-muted">Tautan Profil Facebook</span>
                            @error('link_facebook')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="small mb-1" for="instagramField">
                                Instagram
                            </label>
                            <input type="text" id="instagramField" name="link_instagram"
                                class="form-control @error('link_instagram') is-invalid @enderror"
                                value="{{ old('link_instagram', $linkInstagram ?? '') }}" placeholder="...">
                            <span class="text-xs text-muted">Tautan Profil Instagram</span>
                            @error('link_instagram')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">Informasi Lokasi</div>
                    <div class="card-body overflow-hidden">
                        <div class="mb-3">
                            <label class="small mb-1" for="alamatField">
                                Alamat
                            </label>
                            <input type="text" id="alamatField" name="alamat"
                                class="form-control @error('alamat') is-invalid @enderror"
                                value="{{ old('alamat', $alamat ?? '') }}" placeholder="...">
                            @error('alamat')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="small mb-1" for="gmapsField">
                                Google Maps
                            </label>
                            <input type="text" id="gmapsField" name="link_embed_gmaps"
                                class="form-control @error('link_embed_gmaps') is-invalid @enderror"
                                value="{{ old('link_embed_gmaps', $linkEmbedGmaps ?? '') }}" placeholder="...">
                            <span class="text-xs text-muted text-break">
                                Sematkan Tautan Google Maps. Contoh:
                                https://www.google.com/maps/embed?pb= ...
                            </span>
                            @error('link_embed_gmaps')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        @if (!empty($linkEmbedGmaps))
                            <div class="mb-3">
                                <iframe src="{{ $linkEmbedGmaps ?? '' }}" frameborder="0" class="w-100"
                                    height="300"></iframe>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection

@push('js')
    <script src="{{ asset('assets/admin/libs/jquery/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('assets/admin/libs/jquery/jquery.mask.min.js') }}"></script>
    <script src="{{ asset('assets/admin/libs/sweetalert2/js/sweetalert2.all.min.js') }}"></script>

    <script>
        $(document).ready(function() {
            // toast konfigurasi
            const Toast = Swal.mixin({
                toast: true,
                position: 'bottom-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.addEventListener(
                        'mouseenter',
                        Swal.stopTimer)
                    toast.addEventListener(
                        'mouseleave',
                        Swal.resumeTimer)
                    toast.addEventListener(
                        'click',
                        Swal.close
                    )
                }
            });

            // toast notifikasi
            @if (Session::has('success'))
                Toast.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: '{{ Session::get('success') }}'
                })
            @elseif (Session::has('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Maaf terjadi kesalahan',
                    text: '{{ Session::get('error') }}'
                })
            @endif

            // format nomor telepon
            $('#nomorTeleponField').mask('+62 000-0000-00000');

            // menangani event tombol perbarui
            $('#btnUpdate').on('click', function() {
                $('#formUpdate').submit();
            });
        });
    </script>
@endpush
