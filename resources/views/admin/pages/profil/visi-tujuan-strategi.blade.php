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
                        <button id="btnClearContent" type="button" class="btn btn-sm btn-light text-primary">
                            <i class="fa-solid fa-eraser me-1"></i>
                            Bersihkan Area Teks
                        </button>
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
            <div class="col-lg-6 col-xl-7">
                <div class="card">
                    <div class="card-body overflow-hidden">
                        <form id="formUpdate" action="{{ route('visiKeilmuanTujuanStrategi.update') }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="mb-3">
                                <label class="small mb-1" for="visiKeilmuanField">
                                    Visi Keilmuan
                                    <span class="text-danger">*</span>
                                </label>
                                <textarea id="visiKeilmuanField" name="visi_keilmuan"
                                    class="summernote form-control @error('visi_keilmuan') is-invalid @enderror">{{ old('visi_keilmuan', $visiKeilmuan ?? '') }}</textarea>
                                @error('visi_keilmuan')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label class="small mb-1" for="tujuanField">
                                    Tujuan
                                    <span class="text-danger">*</span>
                                </label>
                                <textarea id="tujuanField" name="tujuan" class="summernote form-control @error('tujuan') is-invalid @enderror">{{ old('tujuan', $tujuan ?? '') }}</textarea>
                                @error('tujuan')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label class="small mb-1" for="strategiField">
                                    Strategi
                                    <span class="text-danger">*</span>
                                </label>
                                <textarea id="strategiField" name="strategi" class="summernote form-control @error('strategi') is-invalid @enderror">{{ old('strategi', $strategi ?? '') }}</textarea>
                                @error('strategi')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-xl-5">
                <div class="card">
                    <div class="card-header">Pratinjau</div>
                    <div class="card-body">
                        <div class="mb-3" style="padding: 10px; border: 1px solid #ddd; border-radius: 5px;">
                            <label class="fw-bolder text-sm mb-1">Visi Keilmuan</label>
                            <div id="previewVisiKeilmuan" class="mb-0"></div>
                        </div>
                        <div class="mb-3" style="padding: 10px; border: 1px solid #ddd; border-radius: 5px;">
                            <label class="fw-bolder text-sm mb-1">Tujuan</label>
                            <div id="previewTujuan" class="mb-0"></div>
                        </div>
                        <div class="mb-3" style="padding: 10px; border: 1px solid #ddd; border-radius: 5px;">
                            <label class="fw-bolder text-sm mb-1">Strategi</label>
                            <div id="previewStrategi" class="mb-0"></div>
                        </div>
                    </div>
                </div>
            </div>
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

            // inisialisasi summernote
            $('.summernote').summernote({
                lang: 'id-ID',
                placeholder: '...',
                height: 200,
                focus: true,
                toolbar: [
                    ['style', ['bold', 'italic', 'underline', 'clear']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['insert', ['link']],
                    ['help', ['help']],
                ],
                fontNames: ['Jost', 'Arial', 'Courier New', 'Georgia', 'Times New Roman', 'Verdana'],
                callbacks: {
                    onInit: function() {
                        // Mengatur font default ke font family Jost
                        $('.note-editable').css('font-family', 'Jost, sans-serif');
                    },
                    onPaste: function(e) {
                        // Menangani paste event
                        const bufferText = ((e.originalEvent || e).clipboardData || window
                                .clipboardData)
                            .getData('Text');

                        // Bersihkan konten yang disisipkan dan atur font family
                        e.preventDefault();
                        document.execCommand('insertText', false, bufferText.replace(/<[^>]*>/g, ''));
                        $('.note-editable').css('font-family', 'Jost, sans-serif');

                        // Hapus konten image yang disisipkan
                        if (bufferText.includes('<img')) {
                            e.preventDefault();

                            // sweetalert notifikasi
                            Swal.fire({
                                icon: 'error',
                                title: 'Pengunggahan gambar tidak diizinkan',
                                text: 'Pengunggahan gambar tidak diizinkan pada area teks.',
                            });
                        }
                    },
                    onImageUpload: function(files, editor, welEditable) {
                        // sweetalert notifikasi
                        Swal.fire({
                            icon: 'error',
                            title: 'Pengunggahan gambar tidak diizinkan',
                            text: 'Pengunggahan gambar tidak diizinkan pada area teks.',
                        });
                    }
                }
            });

            // fungsi update preview
            function updatePreview(fieldId, previewId) {
                const content = $(`#${fieldId}`).summernote('code');
                $(`#${previewId}`).html(content);
            }

            // event listener untuk memperbarui pratinjau saat terjadi perubahan
            $('#visiKeilmuanField').on('summernote.change', function() {
                updatePreview('visiKeilmuanField', 'previewVisiKeilmuan');
            });

            // event listener untuk memperbarui pratinjau saat tombol keyboard dilepas
            $('#visiKeilmuanField').on('summernote.keyup', function() {
                updatePreview('visiKeilmuanField', 'previewVisiKeilmuan');
            });

            // event listener untuk memperbarui pratinjau saat terjadi perubahan
            $('#tujuanField').on('summernote.change', function() {
                updatePreview('tujuanField', 'previewTujuan');
            });

            // event listener untuk memperbarui pratinjau saat tombol keyboard dilepas
            $('#tujuanField').on('summernote.keyup', function() {
                updatePreview('tujuanField', 'previewTujuan');
            });

            // event listener untuk memperbarui pratinjau saat terjadi perubahan
            $('#strategiField').on('summernote.change', function() {
                updatePreview('strategiField', 'previewStrategi');
            });

            // event listener untuk memperbarui pratinjau saat tombol keyboard dilepas
            $('#strategiField').on('summernote.keyup', function() {
                updatePreview('strategiField', 'previewStrategi');
            });

            // panggil fungsi update preview
            updatePreview('visiKeilmuanField', 'previewVisiKeilmuan');
            updatePreview('tujuanField', 'previewTujuan');
            updatePreview('strategiField', 'previewStrategi');

            // menangani event tombol perbarui
            $('#btnUpdate').on('click', function() {
                $('#formUpdate').submit();
            });

            // menangani event tombol hapus semua isi summernote
            $('#btnClearContent').on('click', function() {
                try {
                    // Hapus semua isi Summernote
                    $('#visiKeilmuanField').summernote('code', '');
                    $('#tujuanField').summernote('code', '');
                    $('#strategiField').summernote('code', '');

                    // Tambahkan font default setelah menghapus isi
                    $('.note-editable').css('font-family', 'Jost, sans-serif');
                } catch (error) {
                    // Jika terjadi kesalahan, tangani dengan menampilkan pesan atau aksi lainnya
                    alert('Terjadi kesalahan saat menghapus isi Summernote.');
                }
            });
        });
    </script>
@endpush
