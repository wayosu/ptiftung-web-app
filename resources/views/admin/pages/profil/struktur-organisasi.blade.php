@extends('admin.layouts.app')

@push('css')
    <link href="{{ asset('assets/admin/libs/sweetalert2/css/sweetalert2.min.css') }}" rel="stylesheet" />

    <style>
        #previewImage {
            width: 100%;
            height: 500px;
            object-fit: contain;
        }

        @media screen and (max-width: 576px) {
            #previewImage {
                height: auto;
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
        <div class="row g-4">
            <div class="col-xl-12">
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
                        <form id="formUpdate" action="{{ route('strukturOrganisasi.update') }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="text-center">
                                <img class="mb-3" id="previewImage"
                                    src="{{ isset($strukturOrganisasi)
                                        ? ($strukturOrganisasi
                                            ? asset('storage/profilProgramStudi/' . $strukturOrganisasi)
                                            : asset('assets/admin/img/no-image-placeholder.png'))
                                        : asset('assets/admin/img/no-image-placeholder.png') }}"
                                    alt="" />
                                <div class="small font-italic text-muted mb-4">JPG atau PNG tidak lebih besar dari 2 MB
                                </div>
                                <input type="file" name="gambar" id="gambarInput" class="d-none"
                                    accept="image/jpeg, image/png" />
                                <label for="gambarInput" id="unggahFoto" class="btn btn-primary" type="button">
                                    <i class="fas fa-upload me-1"></i>
                                    Unggah Foto
                                </label>
                                @error('gambar')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script src="{{ asset('assets/admin/libs/jquery/jquery-3.7.1.min.js') }}"></script>
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

            // menangani perubahan input file
            $('#gambarInput').on('change', function() {
                previewImage(this);
            });

            // memicu input file ketika tombol unggahGambar di klik
            $('#unggahGambar').on('click', function(e) {
                e.preventDefault();

                // mengatur ulang nilai input file
                $('#gambarInput').val('');

                // memicu peristiwa klik
                $('#gambarInput').click();
            });

            // menangani event tombol perbarui
            $('#btnUpdate').on('click', function() {
                $('#formUpdate').submit();
            });

            // fungsi melihat pratinjau gambar
            function previewImage(input) {
                if (input.files && input.files[0]) {
                    // validasi ukuran file (maksimum 2MB)
                    if (input.files[0].size > 2 * 1024 * 1024) {
                        // toast notifikasi
                        Toast.fire({
                            icon: 'error',
                            title: 'Ukuran file harus kurang atau tidak lebih dari 2 MB.'
                        });

                        // mengatur ulang gambar pratinjau
                        $('#previewImage').attr('src', '{{ asset('assets/admin/img/no-image-placeholder.png') }}');
                        return;
                    }

                    // memuat gambar pratinjau dengan FileReader
                    var reader = new FileReader();

                    // memuat gambar setelah FileReader selesai
                    reader.onload = function(e) {
                        $('#previewImage').attr('src', e.target.result);
                    };

                    // memuat gambar dari input file
                    reader.readAsDataURL(input.files[0]);
                }
            }
        });
    </script>
@endpush
