@extends('admin.layouts.app')

@push('css')
    <link href="{{ asset('assets/admin/libs/summernote/summernote-lite.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/admin/libs/sweetalert2/css/sweetalert2.min.css') }}" rel="stylesheet" />

    <style>
        .note-editor .note-editing-area,
        #preview {
            font-family: Jost, sans-serif !important;
            line-height: 1.8 !important;
        }

        .note-modal-content {
            overflow: hidden;
        }

        .note-modal-footer {
            height: auto !important;
            padding: 0 !important;
            position: relative;
        }

        .note-modal-footer input {
            margin-right: 15px;
            margin-bottom: 15px;
        }

        .note-modal-footer p {
            margin-top: 15px;
        }

        .sn-checkbox-open-in-new-window label input,
        .sn-checkbox-use-protocol label input {
            margin-right: 5px;
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
            <div class="col-lg-12">
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
                        <form id="formUpdate" action="{{ route('videoProfil.update') }}" method="POST">
                            @csrf
                            @method('PUT')
                            <label class="small mb-1" for="linkEmbedField">
                                Link Embed Video Profil
                                <span class="text-danger">*</span>
                            </label>
                            <input class="form-control @error('link_embed_video_profil') is-invalid @enderror"
                                name="link_embed_video_profil" id="linkEmbedField" type="text"
                                placeholder="Masukkan link embed video profil"
                                value="{{ old('link_embed_video_profil', $videoProfil ?? '') }}" />
                            <span class="text-xs text-muted">Embed Youtube atau Google Drive</span>
                            @error('link_embed_video_profil')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </form>
                    </div>
                </div>
            </div>
            @if ($videoProfil !== null)
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="ratio ratio-16x9">
                                <iframe src="{{ $videoProfil }}" allowfullscreen></iframe>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection

@push('js')
    <script src="{{ asset('assets/admin/libs/jquery/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('assets/admin/libs/summernote/summernote-lite.min.js') }}"></script>
    <script src="{{ asset('assets/admin/libs/summernote/lang/summernote-id-ID.min.js') }}"></script>
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

            // menangani event tombol perbarui
            $('#btnUpdate').on('click', function() {
                $('#formUpdate').submit();
            });
        });
    </script>
@endpush
