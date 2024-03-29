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
        <div class="row">
            <div class="col-xl-6">
                <div class="card">
                    @if (isset($updatedBy) && $updatedBy !== null)
                        <div class="card-header p-2 bg-white">
                            <div
                                class="d-flex gap-3 py-2 align-items-center text-start bg-white text-muted text-xs overflow-hidden">
                                <div class="px-3 border-end border-2">
                                    <i class="fa-solid fa-circle-info"></i>
                                </div>
                                <div>
                                    Terakhir diperbarui oleh
                                    <span class="fw-bolder">
                                        {{ $updatedBy }}
                                    </span>
                                    pada
                                    <span class="fw-bolder">
                                        {{ \Carbon\Carbon::parse($updatedAt)->isoFormat('dddd, D MMMM Y') }}
                                    </span>
                                    pukul
                                    <span class="fw-bolder">
                                        {{ \Carbon\Carbon::parse($updatedAt)->isoFormat('H:mm') }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    @endif
                    <div class="card-body overflow-hidden">
                        <form id="formUpdate" action="{{ route('kontakLokasi.update') }}" method="POST">
                            @csrf
                            @method('PUT')
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
                                    value="{{ old('email', $email ?? '') }}" autocomplete="off"
                                    placeholder="email@example.com">
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
                        </form>
                    </div>
                </div>
            </div>
        </div>
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
