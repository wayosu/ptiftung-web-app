@extends('admin.layouts.app')

@push('css')
    <link rel="stylesheet" href="{{ asset('assets/admin/libs/sweetalert2/css/sweetalert2.min.css') }}" />
    <link href="{{ asset('assets/admin/libs/summernote/summernote-lite.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/admin/libs/dropzone/dropzone.min.css') }}" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/css/lightbox.min.css"
        integrity="sha512-ZKX+BvQihRJPA8CROKBhDNvoc2aDMOdAlcm7TUQY+35XYtrd3yh95QOOhsPDQY9QnKE0Wqag9y38OIgEvb88cA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <style>
        .btn-hapus-thumbnail {
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

        .dz-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .dropzone.dz-started .dz-message {
            display: block !important;
        }

        .dropzone {
            border: 2px dashed #ced4da !important;
        }

        .dropzone .dz-message {
            margin: 3em 0 !important;
        }

        .dropzone .dz-preview.dz-complete .dz-success-mark {
            opacity: 1;
        }

        .dropzone .dz-preview.dz-error .dz-success-mark {
            opacity: 0;
        }

        .dropzone .dz-preview .dz-error-message {
            top: 144px;
        }

        .scroll-container {
            width: 100%;
            max-height: 600px;
            overflow-y: auto;
            overflow-x: hidden;

            /* scroll style */
            scrollbar-width: thin;
            scrollbar-color: rgba(0, 0, 0, 0.3) transparent;
        }

        .scroll-image {
            width: 100%;
            height: 200px;
            object-fit: cover;
            transition: filter 0.3s ease;
        }

        .btn-overflow-container {
            position: relative;
            display: block;
            width: 100%;
            height: 100%;
        }

        .btn-overflow-container::after {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .btn-overflow-container:hover::after {
            opacity: 1;
        }

        .btn-overflow {
            position: absolute;
            top: 50%;
            right: 50%;
            transform: translate(50%, -50%);
            width: 100%;
            text-align: center;
            z-index: 1;
            visibility: hidden;
            opacity: 0;
            transition: visibility 0.3s ease, opacity 0.3s ease;
        }

        .btn-overflow-container:hover .btn-overflow {
            visibility: visible;
            opacity: 1;
        }

        .btn-overflow-container:hover .scroll-image {
            filter: blur(1px);
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
                            <div class="page-header-icon"><i data-feather="{{ $icon ?? '' }}"></i></div>
                            {{ $title ?? '' }}
                        </h1>
                        <p class="mb-0 small mt-1">
                            {{ $subtitle ?? '' }}
                        </p>
                    </div>
                    <div class="col-12 col-xl-auto mb-3">
                        <a class="btn btn-sm btn-light text-primary" href="{{ route('kegiatanPerkuliahan.index') }}">
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
        <form
            action="@if (isset($kegiatanPerkuliahan)) {{ route('kegiatanPerkuliahan.update', $kegiatanPerkuliahan->id) }} @else {{ route('kegiatanPerkuliahan.store') }} @endif"
            method="POST" class="row g-4" enctype="multipart/form-data">
            @csrf
            @if (isset($kegiatanPerkuliahan))
                @method('PUT')
            @endif

            <div class="col-xl-8">
                <div class="card">
                    <div class="card-header">Form {{ $title ?? '' }}</div>
                    @if (isset($kegiatanPerkuliahan) && $kegiatanPerkuliahan->createdBy)
                        <div class="card-header bg-white">
                            <div
                                class="d-flex flex-column flex-md-row-reverse align-items-start align-items-md-center justify-content-between">
                                <div class="text-xs text-muted">
                                    <i class="fa-solid fa-user fa-xs me-1"></i>
                                    <span>
                                        {{ $kegiatanPerkuliahan->createdBy->name }}
                                    </span>
                                </div>
                                <div class="text-xs text-muted">
                                    <i class="fa-solid fa-calendar fa-xs me-1"></i>
                                    <span>
                                        {{ \Carbon\Carbon::parse($kegiatanPerkuliahan->created_at)->isoFormat('dddd, D MMMM Y H:mm') }}
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
                                value="{{ old('judul', $kegiatanPerkuliahan->judul ?? '') }}" />
                            @error('judul')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="small mb-1" for="deskripsiField">
                                Deskripsi
                                <span class="text-danger">*</span>
                            </label>
                            <textarea id="deskripsiField" name="deskripsi" class="form-control @error('deskripsi') is-invalid @enderror">{{ old('deskripsi', $kegiatanPerkuliahan->deskripsi ?? '') }}</textarea>
                            @error('deskripsi')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="small mb-1">
                                Thumbnail
                                @if (!isset($kegiatanPerkuliahan))
                                    <span class="text-danger">*</span>
                                @endif
                            </label>
                            <label id="thumbnailLabel" class="custom-btn-upload" for="thumbnailField">
                                <div class="d-flex align-items-center gap-2">
                                    <i class="fa-solid fa-upload"></i>
                                    <span>Unggah Thumbnail</span>
                                </div>
                                <button type="button" role="button" id="btnHapusThumbnail"
                                    class="btn-hapus-thumbnail d-none">
                                    <i class="fa-solid fa-xmark fa-lg"></i>
                                </button>
                            </label>
                            <input class="d-none" name="thumbnail" id="thumbnailField" type="file"
                                accept="image/jpg, image/jpeg, image/png" />
                            @error('thumbnail')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="small mb-1">
                                Upload Dokumentasi (JPG, JPEG, PNG) max. 2MB
                            </label>
                            <div class="dropzone" id="myDropzone"></div>
                        </div>
                        <div class="mb-3">
                            <label class="small mb-1" for="tautanField">
                                Tautan Video Dokumentasi
                            </label>
                            <input class="form-control @error('link_video') is-invalid @enderror" name="link_video"
                                id="tautanField" type="text" placeholder="Masukkan tautan video dokumentasi"
                                value="{{ old('link_video', $kegiatanPerkuliahan->link_video ?? '') }}" />
                            @error('link_video')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <button class="btn btn-light" type="reset">
                            <i class="fa-solid fa-rotate-left me-1"></i>
                            Atur Ulang
                        </button>
                        <button class="btn btn-primary" type="submit">
                            <i class="fa-solid fa-floppy-disk me-1"></i>
                            @if (isset($kegiatanPerkuliahan))
                                Perbarui
                            @else
                                Simpan
                            @endif
                        </button>
                    </div>
                </div>
            </div>
            @if (isset($kegiatanPerkuliahan->kegiatanPerkuliahanImages) &&
                    count($kegiatanPerkuliahan->kegiatanPerkuliahanImages) > 0)
                <div class="col-xl-4">
                    <div class="card mb-2">
                        <div class="card-header">Dokumentasi Kegiatan</div>
                        <div class="card-body p-2 overflow-hidden">

                            <div class="scroll-container">
                                <div class="row g-2 row-cols-2">
                                    @foreach ($kegiatanPerkuliahan->kegiatanPerkuliahanImages as $image)
                                        <div class="col">
                                            <div class="btn-overflow-container">
                                                <img src="{{ asset('storage/akademik/kegiatan-perkuliahan/' . $image->gambar) }}"
                                                    alt="kegiatan-perkuliahan-image-{{ $image->gambar ?? '' }}"
                                                    class="scroll-image" />
                                                <div class="btn-overflow">
                                                    <a href="{{ asset('storage/akademik/kegiatan-perkuliahan/' . $image->gambar) }}"
                                                        data-lightbox="image" data-title="{{ $image->gambar ?? '' }}"
                                                        class="btn btn-sm btn-light rounded-circle px-3 py-3 btn-gambar">
                                                        <i class="fa-solid fa-expand"></i>
                                                    </a>
                                                    <a href="javascript:void(0)" data-gambar-id="{{ $image->id }}"
                                                        class="btn btn-sm btn-light rounded-circle px-3 py-3 btn-gambar btn-delete-image">
                                                        <i class="fa-solid fa-trash"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            @endif
        </form>

        @if (isset($kegiatanPerkuliahan->kegiatanPerkuliahanImages) &&
                count($kegiatanPerkuliahan->kegiatanPerkuliahanImages) > 0)
            <form id="formDeleteKegiatanPerkuliahanImage" method="POST" class="d-none">
                @csrf
                @method('DELETE')
            </form>
        @endif
    </div>
@endsection

@push('js')
    <script src="{{ asset('assets/admin/libs/jquery/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('assets/admin/libs/sweetalert2/js/sweetalert2.all.min.js') }}"></script>
    <script src="{{ asset('assets/admin/libs/summernote/summernote-lite.min.js') }}"></script>
    <script src="{{ asset('assets/admin/libs/summernote/lang/summernote-id-ID.min.js') }}"></script>
    <script src="{{ asset('assets/admin/libs/dropzone/dropzone.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/js/lightbox.min.js"
        integrity="sha512-k2GFCTbp9rQU412BStrcD/rlwv1PYec9SNrkbQlo6RZCf75l6KcC3UwDY8H5n5hl4v77IDtIPwOk9Dqjs/mMBQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script type="text/javascript">
        Dropzone.autoDiscover = false;

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
            $('#deskripsiField').summernote({
                lang: 'id-ID',
                placeholder: '...',
                height: 200,
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
            $('#thumbnailField').change(function() {
                const label = $('#thumbnailLabel');
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
                    $('#btnHapusThumbnail').addClass('d-none'); // sembunyikan tombol hapus

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
                    $('#btnHapusThumbnail').addClass('d-none'); // sembunyikan tombol hapus

                    return false;
                }

                label.find('span').text(fileName); // mengubah teks span di dalam label
                $('#btnHapusThumbnail').removeClass('d-none'); // tampilkan tombol hapus
            });

            // event hapus gambar
            $('#btnHapusThumbnail').click(function() {
                const label = $('#thumbnailLabel');
                label.find('span').text('Unggah Gambar'); // mengatur kembali teks span di dalam label
                $('#btnHapusThumbnail').addClass('d-none'); // sembunyikan tombol hapus
                $('#thumbnailField').val(''); // kosongkan nilai file input
            });

            // inisialisasi dropzone
            let uploadedDocumentMap = {};
            $('div#myDropzone').dropzone({
                url: "{{ route('kegiatanPerkuliahan.uploadTemporaryImage') }}",
                minFiles: 1,
                maxFiles: 5,
                maxFilesize: 2,
                uploadMultiple: true,
                addRemoveLinks: true,
                acceptedFiles: '.jpeg,.jpg,.png',
                dictInvalidFileType: 'Type file ini tidak dizinkan',
                dictDefaultMessage: "Klik atau seret gambar disini",
                dictRemoveFile: "Hapus gambar",
                dictFileTooBig: "File yang Anda unggah terlalu besar. Maksimal 2MB",
                dictMaxFilesExceeded: "File yang Anda unggah melebihi batas maksimal. Maksimal 5 gambar",
                headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                },
                renameFile: function(file) {
                    let extension = file.name.split('.').pop(); // Mengambil ekstensi file
                    let dt = new Date();
                    let time = dt.getTime();
                    let fileName = time + '.' + extension;
                    return fileName;
                },
                init: function() {
                    const thisDropzone = this;

                    $.ajax({
                        url: "{{ route('kegiatanPerkuliahan.getTemporaryImage') }}",
                        type: "GET",
                        dataType: "json",
                        success: function(data) {
                            $.each(data, function(key, value) {
                                let mockFile = {
                                    name: value.name,
                                    size: value.size
                                }

                                thisDropzone.emit("addedfile", mockFile);
                                thisDropzone.emit("thumbnail", mockFile, value
                                    .path);
                                thisDropzone.emit("complete", mockFile);

                                $('form').append(
                                    '<input type="hidden" name="images[]" value="' +
                                    value.name + '">');
                            });
                        }
                    });
                },
                successmultiple: function(file, response) {
                    $.each(response['name'], function(key, val) {
                        $('form').append('<input type="hidden" name="images[]" value="' + val +
                            '">');
                        if (response[key] && response[key].name) {
                            uploadedDocumentMap[response[key].name] = val;
                        }

                        file[key].previewElement.querySelector('[data-dz-name]').textContent =
                            val;
                    });
                    // console.log(response);
                },
                removedfile: function(file) {
                    // console.log(file.previewElement.querySelector(
                    //     '[data-dz-name]').textContent);
                    if (this.options.dictRemoveFile) {
                        return Dropzone.confirm("Apakah anda yakin untuk " + this.options
                            .dictRemoveFile,
                            function() {
                                let name = file.previewElement.querySelector(
                                    '[data-dz-name]').textContent;

                                $.ajax({
                                    url: "{{ route('kegiatanPerkuliahan.deleteTemporaryImage') }}",
                                    type: "DELETE",
                                    data: {
                                        filename: name
                                    },
                                    headers: {
                                        'X-CSRF-TOKEN': "{{ csrf_token() }}"
                                    },
                                    success: function(data) {
                                        Toast.fire({
                                            icon: 'success',
                                            title: 'Berhasil',
                                            text: 'Gambar berhasil dihapus!'
                                        });

                                        $('form').find(
                                            'input[name="images[]"][value="' +
                                            name + '"]').remove();
                                        // console.log(data);
                                    },
                                    error: function(error) {
                                        Toast.fire({
                                            icon: 'error',
                                            title: 'Gambar gagal dihapus!'
                                        });
                                        console.log(error);
                                    }
                                });

                                let fileRef;
                                return (fileRef = file.previewElement) != null ?
                                    fileRef.parentNode.removeChild(file.previewElement) : void 0;
                            });
                    }
                },
                error: function(file, response) {
                    if ($.type(response) === "string") {
                        Swal.fire({
                            icon: 'error',
                            title: response
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Maaf terjadi kesalahan',
                        });
                    }

                    let fileRef;
                    return (fileRef = file.previewElement) != null ?
                        fileRef.parentNode.removeChild(file.previewElement) : void 0;
                }
            });

            $('body').on('click', '.btn-delete-image', function(e) {
                e.preventDefault();

                // Menampilkan modal konfirmasi sebelum menghapus gambar
                Swal.fire({
                    title: 'Apakah anda yakin?',
                    text: "Anda tidak akan dapat mengembalikannya!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal',
                }).then((result) => {
                    if (result.isConfirmed) {
                        // mengekstrak ID gambar dari atribut data
                        const dataGambarId = $(this).data('gambar-id');

                        // Membuat URL untuk penghapusan gambar dengan ID yang sesuai
                        const deleteImageUrl =
                            "{{ route('kegiatanPerkuliahan.deleteImage', ':dataGambarId') }}"
                            .replace(':dataGambarId', dataGambarId);

                        // Mengatur action form untuk URL penghapusan gambar yang sesuai
                        $('#formDeleteKegiatanPerkuliahanImage').attr('action', deleteImageUrl)
                            .submit();
                    }
                });
            });
        });
    </script>
@endpush
