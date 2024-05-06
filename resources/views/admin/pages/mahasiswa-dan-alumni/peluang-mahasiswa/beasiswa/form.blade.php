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

        .btn-hapus-gambar {
            display: flex;
            align-items: center;
            background: none !important;
            border: none;
            color: #69707A;
            padding: 0;
        }

        .custom-btn-upload {
            display: flex;
            justify-content: space-between;
            width: 100%;
            align-items: center;
            gap: 0.5rem;
            padding: 0.875rem 1.125rem;
            font-size: 0.875rem;
            font-weight: 400;
            color: #a7aeb8;
            border: 1px solid #c5ccd6;
            border-radius: 0.35rem;
            cursor: pointer;
        }
    </style>
@endpush

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
                        <a class="btn btn-sm btn-light text-primary" href="{{ route('beasiswa.index') }}">
                            <i class="fa-solid fa-arrow-left me-1"></i>
                            Kembali
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <div class="container-xl px-4 mt-4">
        <form
            action="@if (isset($beasiswa)) {{ route('beasiswa.update', $beasiswa->id) }} @else {{ route('beasiswa.store') }} @endif"
            method="POST" class="row" enctype="multipart/form-data">
            @csrf
            @if (isset($beasiswa))
                @method('PUT')
            @endif

            @if (isset($beasiswa))
                <div class="col-xl-4">
                    <div class="card mb-4">
                        <div class="card-header">Gambar</div>
                        <div class="card-body p-0 overflow-hidden text-center">
                            <img src="{{ asset('storage/mahasiswa-dan-alumni/peluang-mahasiswa/beasiswa/' . $beasiswa->gambar) }}"
                                alt="profil-lulusan-image" class="img-fluid">
                        </div>
                    </div>
                </div>
            @endif

            <div class="{{ isset($beasiswa) ? 'col-xl-8' : 'col-xl-6' }}">
                <div class="card mb-4">
                    <div class="card-header">Form {{ $title ?? '' }}</div>
                    @if (isset($beasiswa) && $beasiswa->createdBy)
                        <div class="card-header bg-white">
                            <div
                                class="d-flex flex-column flex-md-row-reverse align-items-start align-items-md-center justify-content-between">
                                <div class="text-xs text-muted">
                                    <i class="fa-solid fa-user fa-xs me-1"></i>
                                    <span>
                                        {{ $beasiswa->createdBy->name }}
                                    </span>
                                </div>
                                <div class="text-xs text-muted">
                                    <i class="fa-solid fa-calendar fa-xs me-1"></i>
                                    <span>
                                        {{ \Carbon\Carbon::parse($beasiswa->created_at)->isoFormat('dddd, D MMMM Y H:mm') }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    @endif
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="small mb-1" for="judulField">
                                Judul
                                <span class="text-danger">*</span>
                            </label>
                            <input class="form-control @error('judul') is-invalid @enderror" name="judul" id="judulField"
                                type="text" placeholder="Masukkan judul"
                                value="{{ old('judul', $beasiswa->judul ?? '') }}" />
                            @error('judul')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="small mb-1" for="deskripsiField">
                                Deskripsi
                                <span class="text-danger">*</span>
                            </label>
                            <textarea id="summernote" name="deskripsi" class="form-control @error('deskripsi') is-invalid @enderror">{{ old('deskripsi', $beasiswa->deskripsi ?? '') }}</textarea>
                            @error('deskripsi')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="small mb-1">
                                Gambar
                                @if (!isset($beasiswa))
                                    <span class="text-danger">*</span>
                                @endif
                            </label>
                            <label id="gambarLabel" class="custom-btn-upload" for="gambarField">
                                <div class="d-flex align-items-center gap-2">
                                    <i class="fa-solid fa-upload"></i>
                                    <span id="gambatText">Unggah Gambar</span>
                                </div>
                                <button type="button" role="button" id="btnHapusGambar" class="btn-hapus-gambar d-none">
                                    <i class="fa-solid fa-xmark fa-lg"></i>
                                </button>
                            </label>
                            <input class="d-none" name="gambar" id="gambarField" type="file"
                                accept="image/jpg, image/jpeg, image/png" />
                            <span class="text-xs text-muted">Format JPG, JPEG, PNG max. 2MB</span>
                            @error('gambar')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <button class="btn btn-light" type="reset">
                            <i class="fa-solid fa-rotate-left me-1"></i>
                            Atur Ulang
                        </button>
                        <button class="btn btn-primary" type="submit">
                            <i class="fa-solid fa-floppy-disk me-1"></i>
                            @if (isset($beasiswa))
                                Perbarui
                            @else
                                Simpan
                            @endif
                        </button>
                    </div>
                    @if (isset($beasiswa) && $beasiswa->updatedBy)
                        <div class="card-footer p-2 bg-white">
                            <div
                                class="d-flex gap-3 py-2 align-items-center text-start bg-white text-muted text-xs overflow-hidden">
                                <div class="px-3 border-end border-2">
                                    <i class="fa-solid fa-circle-info"></i>
                                </div>
                                <div>
                                    Terakhir diperbarui oleh
                                    <span class="fw-bolder">
                                        {{ $beasiswa->updatedBy->name }}
                                    </span>
                                    pada
                                    <span class="fw-bolder">
                                        {{ \Carbon\Carbon::parse($beasiswa->updated_at)->isoFormat('dddd, D MMMM Y') }}
                                    </span>
                                    pukul
                                    <span class="fw-bolder">
                                        {{ \Carbon\Carbon::parse($beasiswa->updated_at)->isoFormat('H:mm') }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </form>
    </div>
@endsection

@push('js')
    <script src="{{ asset('assets/admin/libs/jquery/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('assets/admin/libs/summernote/summernote-lite.min.js') }}"></script>
    <script src="{{ asset('assets/admin/libs/summernote/lang/summernote-id-ID.min.js') }}"></script>
    <script src="{{ asset('assets/admin/libs/sweetalert2/js/sweetalert2.all.min.js') }}"></script>

    <script>
        $(document).ready(function() {
            // inisialisasi summernote
            $('#summernote').summernote({
                lang: 'id-ID',
                placeholder: '...',
                height: 400,
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

            // event upload gambar
            $('#gambarField').change(function() {
                const label = $('#gambarLabel');
                const file = this.files[0];
                const fileName = file.name;
                const validImageTypes = ["image/jpeg", "image/png", "image/jpg"];

                // validasi tipe file (harus berupa gambar)
                if (!validImageTypes.includes(file.type)) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Maaf terjadi kesalahan',
                        text: 'File harus berupa gambar (JPEG, PNG, JPG)'
                    });

                    $(this).val(''); // reset nilai file input
                    label.find('span').text('Unggah Gambar'); // kembalikan teks label
                    $('#btnHapusGambar').addClass('d-none'); // sembunyikan tombol hapus

                    return false;
                }

                // validasi ukuran file (maksimum 2MB)
                if (file.size > 2 * 1024 * 1024) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Maaf terjadi kesalahan',
                        text: 'Ukuran gambar maksimal 2 MB'
                    });

                    $(this).val(''); // reset nilai file input
                    label.find('span').text('Unggah Gambar'); // kembalikan teks label
                    $('#btnHapusGambar').addClass('d-none'); // sembunyikan tombol hapus

                    return false;
                }

                label.find('span').text(fileName); // mengubah teks span di dalam label
                $('#btnHapusGambar').removeClass('d-none'); // tampilkan tombol hapus
            });

            // event hapus gambar
            $('#btnHapusGambar').click(function() {
                const label = $('#gambarLabel');
                label.find('span').text('Unggah Gambar'); // mengatur kembali teks span di dalam label
                $('#btnHapusGambar').addClass('d-none'); // sembunyikan tombol hapus
                $('#gambarField').val(''); // kosongkan nilai file input
            });
        });
    </script>
@endpush
