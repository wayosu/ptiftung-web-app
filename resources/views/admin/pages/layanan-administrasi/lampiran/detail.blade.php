@extends('admin.layouts.app')

@push('css')
    @role('Superadmin|Kaprodi|Dosen')
        <link href="{{ asset('assets/admin/libs/sweetalert2/css/sweetalert2.min.css') }}" rel="stylesheet" />
        <link href="{{ asset('assets/admin/libs/summernote/summernote-lite.min.css') }}" rel="stylesheet">
    @endrole

    <style>
        .file-dokumen-btn {
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 200px;
            height: 200px;
            border: 1px dashed #ced4da;
            border-radius: 0.35rem;
            cursor: pointer;
            transition: all 0.3s ease;
            overflow: hidden;
        }

        .file-dokumen-btn:hover {
            background-color: #f8f9fa;
            border-color: #ced4da;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
        }

        .file-dokumen-text {
            color: #6c757d;
            font-size: 0.875rem;
            font-weight: 500;
        }

        .dokumentasi-btn {
            position: relative;
            display: block;
            width: 200px;
            height: 200px;
            overflow: hidden;
        }

        .dokumentasi-btn:hover .dokumentasi-img {
            transform: scale(1.1);
        }

        .dokumentasi-img {
            object-fit: cover;
            object-position: center;
            width: 200px;
            height: 200px;
            transition: all 0.3s ease;
        }
    </style>
@endpush

@section('content')
    <!-- Header content-->
    <header class="page-header page-header-compact page-header-light border-bottom bg-white mb-4">
        <div class="container-fluid px-4">
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
                        @role('Mahasiswa')
                            <a class="btn btn-sm btn-light text-primary" href="{{ route('lampiranKegiatan.index', $kegiatanMahasiswa->slug) }}" role="button">
                                <i class="fa-solid fa-arrow-left me-1"></i>
                                Kembali
                            </a>
                        @else
                            <a class="btn btn-sm btn-light text-primary" href="{{ route('lampiranKegiatan.review', $lampiranKegiatan->kegiatan->id) }}" role="button">
                                <i class="fa-solid fa-arrow-left me-1"></i>
                                Kembali
                            </a>
                        @endrole
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Main page content-->
    <div class="container-fluid px-4">
        <div class="row g-4">
            <div class="col-12 col-xl-4">
                <div class="card">
                    <div class="card-header">Informasi Lampiran</div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="text-xs text-uppercase fw-bolder mb-1">Kegiatan</label>
                            <p class="mb-0">{{ $lampiranKegiatan->kegiatan->kegiatanMahasiswa->nama_kegiatan }}</p>
                        </div>
                        <div class="mb-3">
                            <label class="text-xs text-uppercase fw-bolder mb-1">Dosen Pembimbing Lapangan</label>
                            <p class="mb-0">{{ $lampiranKegiatan->kegiatan->dosen->user->name }}</p>
                        </div>
                        <div class="mb-3">
                            <label class="text-xs text-uppercase fw-bolder mb-1">Mahasiswa</label>
                            <p class="mb-0">{{ $lampiranKegiatan->kegiatan->mahasiswa->user->name }}</p>
                        </div>
                        <div class="mb-3">
                            <label class="text-xs text-uppercase fw-bolder mb-1">Keterangan Lampiran</label>
                            <p class="mb-0">{{ $lampiranKegiatan->keterangan_lampiran }}</p>
                        </div>
                        <div class="mb-0">
                            <label class="d-block text-xs text-uppercase fw-bolder mb-1">Status</label>
                            @if ($lampiranKegiatan->status === 'belum direview')
                                <span class="badge bg-warning">Belum Direview</span>
                            @elseif ($lampiranKegiatan->status === 'disetujui')
                                <span class="badge bg-success">Disetujui</span>
                            @elseif ($lampiranKegiatan->status === 'ditolak')
                                <span class="badge bg-danger">Ditolak</span>
                            @endif
                        </div>
                    </div>
                </div>

                @if ($lampiranKegiatan->status === 'disetujui' || $lampiranKegiatan->status === 'ditolak')
                    <div class="card mt-4">
                        <div class="card-header">Catatan Dosen</div>
                        <div class="card-body">
                            <div class="small">
                                {!! $lampiranKegiatan->catatan_dosen !!}
                            </div>
                        </div>
                    </div>
                @endif

                @role('Superadmin|Kaprodi|Dosen')
                    <div class="card mt-4">
                        @if ($lampiranKegiatan->status === 'belum direview') 
                            <div class="card-header">Setujui / Tolak Lampiran</div>
                            <div class="card-body d-flex flex-column gap-3">
                                <a href="javascript:void(0)" class="btn btn-danger d-block" data-bs-toggle="modal"
                                data-bs-target="#modalCatatanTolak" title="Tolak">
                                    <i class="fa-solid fa-xmark me-1"></i>
                                    Tolak Lampiran
                                </a>
                                <a href="javascript:void(0)" class="btn btn-success d-block" data-bs-toggle="modal" data-bs-target="#modalCatatanSetujui" title="Setujui">
                                    <i class="fa-solid fa-check me-1"></i>
                                    Setujui Lampiran
                                </a>
                            </div>
                        @else
                            <div class="card-header">Aksi</div>
                            <div class="card-body d-flex flex-column gap-3">
                                <a href="javascript:void(0)" class="btn btn-danger d-block tombol-batal" data-id="{{ $lampiranKegiatan->id }}" title="Review">
                                    <i class="fa-solid fa-arrow-rotate-left me-1"></i>
                                    @if ($lampiranKegiatan->status === 'disetujui')
                                        Batalkan Setujui Lampiran
                                    @else
                                        Batalkan Tolak Lampiran
                                    @endif
                                </a>
                            </div>
                        @endif
                    </div>
                @endrole
            </div>
            <div class="col-12 col-xl-8">
                <div class="card">
                    <div class="card-header">File Lampiran</div>
                    <div class="card-body">
                        <div class="d-flex flex-wrap gap-4">
                            @foreach ($lampiranKegiatan->fileLampiranKegiatans as $fileLampiran)
                                @php
                                    $extension = pathinfo($fileLampiran->file_name, PATHINFO_EXTENSION);
                                @endphp

                                @if (in_array($extension, ['pdf', 'docx', 'doc', 'PDF', 'DOCX', 'DOC']))
                                    <a href="{{ asset('storage/' . $fileLampiran->file_path . '/' . $fileLampiran->file_name) }}" class="file-dokumen-btn" target="_blank">
                                        <div class="d-flex flex-column gap-2">
                                            @if ($extension === 'pdf')
                                                <i class="fa-solid fa-file-pdf fa-3x me-1 text-danger"></i>
                                            @elseif ($extension === 'docx')
                                                <i class="fa-solid fa-file-word  fa-3x me-1 text-primary"></i>
                                            @elseif ($extension === 'doc')
                                                <i class="fa-solid fa-file-word fa-3x me-1 text-primary"></i>
                                            @else
                                                <i class="fa-solid fa-file fa-3x me-1"></i>
                                            @endif
                                            <span class="file-dokumen-text">Dokumen ({{ strtoupper($extension) }})</span>
                                        </div>
                                    </a>
                                @elseif (in_array($extension, ['jpeg', 'jpg', 'png', 'JPEG', 'JPG', 'PNG']))
                                    <a href="{{ asset('storage/' . $fileLampiran->file_path . '/' . $fileLampiran->file_name) }}" class="dokumentasi-btn rounded-2" target="_blank">
                                        <img src="{{ asset('storage/' . $fileLampiran->file_path . '/' . $fileLampiran->file_name) }}" alt="gambar-dokumentasi" class="dokumentasi-img" style="width:200px;height:200px;">
                                    </a>
                                @else
                                    <a href="{{ asset('storage/' . $fileLampiran->file_path . '/' . $fileLampiran->file_name) }}" class="btn btn-secondary" target="_blank">
                                        Lihat File ({{ strtoupper($extension) }})
                                    </a>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @role('Superadmin|Kaprodi|Dosen')
        <div class="modal fade" id="modalCatatanTolak" tabindex="-1" role="dialog" aria-labelledby="examplemModalCatatanTolakTitle"
            aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                <form id="formCatatanTolak" method="POST" class="modal-content">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="examplemModalCatatanTolakTitle">Form Catatan</h5>
                        <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p class="mb-1 text-sm">
                            <span class="text-danger">*</span> Berikan catatan untuk mahasiswa.
                        </p>
                        <textarea id="summernote1" name="catatan_dosen" class="form-control"></textarea>
                        <span id="pesan-error" class="text-xs text-danger"></span>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Tutup</button>
                        <button id="submitRevisiLaporan-btn" class="btn btn-primary tombol-tolak" type="button" data-id="{{ $lampiranKegiatan->id }}">
                            <i class="fa-solid fa-paper-plane me-2"></i> Tolak dan Kirim Catatan
                        </button>
                    </div>
                </form>
            </div>
        </div>
        
        <div class="modal fade" id="modalCatatanSetujui" tabindex="-1" role="dialog" aria-labelledby="examplemModalCatatanSetujuiTitle"
            aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                <form id="formCatatanSetujui" method="POST" class="modal-content">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="examplemModalCatatanSetujuiTitle">Form Catatan</h5>
                        <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p class="mb-1 text-sm">
                            <span class="text-danger">*</span> Berikan catatan untuk mahasiswa.
                        </p>
                        <textarea id="summernote2" name="catatan_dosen" class="form-control"></textarea>
                        <span id="pesan-error" class="text-xs text-danger"></span>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Tutup</button>
                        <button id="submitRevisiLaporan-btn" class="btn btn-primary tombol-setujui" type="button" data-id="{{ $lampiranKegiatan->id }}">
                            <i class="fa-solid fa-paper-plane me-2"></i> Setujui dan Kirim Catatan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endrole
@endsection

@role('Superadmin|Kaprodi|Dosen')
    @push('js')
        <script src="{{ asset('assets/admin/libs/jquery/jquery-3.7.1.min.js') }}"></script>
        <script src="{{ asset('assets/admin/libs/summernote/summernote-lite.min.js') }}"></script>
        <script src="{{ asset('assets/admin/libs/summernote/lang/summernote-id-ID.min.js') }}"></script>
        <script src="{{ asset('assets/admin/libs/sweetalert2/js/sweetalert2.all.min.js') }}"></script>

        <script>
            $(document).ready(function() {
                // inisialisasi summernote
                $('#summernote1').summernote({
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

                $('#summernote2').summernote({
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

                $('.tombol-tolak').on('click', function() {
                    let kegiatanId = $(this).data('id');
                    let url = "{{ route('lampiranKegiatan.tolak', ':kegiatanId') }}";
                    url = url.replace(':kegiatanId', kegiatanId);

                    let redirectUrl = "{{ route('lampiranKegiatan.review', $lampiranKegiatan->kegiatan->id) }}";

                    const formData = $('#formCatatanTolak').serialize();

                    Swal.fire({
                        title: 'Apakah Anda yakin?',
                        text: "Anda tidak akan bisa mengembalikan ini!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: 'Ya, tolak!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $.ajax({
                                url: url,
                                type: 'POST',
                                data: formData,
                                success: function(response) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Ditolak!',
                                        text: response.success,
                                        timer: 3000
                                    }).then((result) => {
                                        window.location.href = redirectUrl;
                                    });

                                    setTimeout(function() {
                                        location.location.href = redirectUrl;
                                    }, 3000);
                                },
                                error: function(xhr) {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Oops...',
                                        text: 'Terjadi kesalahan saat memperbarui status!'
                                    });
                                }
                            });
                        }
                    }); 
                });

                $('.tombol-batal').on('click', function() {
                    let kegiatanId = $(this).data('id');
                    let url = "{{ route('lampiranKegiatan.batal', ':kegiatanId') }}";
                    url = url.replace(':kegiatanId', kegiatanId);

                    let redirectUrl = "{{ route('lampiranKegiatan.review', $lampiranKegiatan->kegiatan->id) }}";

                    Swal.fire({
                        title: 'Apakah Anda yakin?',
                        text: "Anda tidak akan bisa mengembalikan ini!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#00ac69',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: 'Ya, batal!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $.ajax({
                                url: url,
                                type: 'POST',
                                data: {
                                    _token: '{{ csrf_token() }}'
                                },
                                success: function(response) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Dibatalkan!',
                                        text: response.success,
                                        timer: 3000
                                    }).then((result) => {
                                        window.location.href = redirectUrl;
                                    });

                                    setTimeout(function() {
                                        location.location.href = redirectUrl;
                                    }, 3000);
                                },
                                error: function(xhr) {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Oops...',
                                        text: 'Terjadi kesalahan saat memperbarui status!'
                                    });
                                }
                            });
                        }
                    });
                });

                $('.tombol-setujui').on('click', function() {
                    let kegiatanId = $(this).data('id');
                    let url = "{{ route('lampiranKegiatan.setujui', ':kegiatanId') }}";
                    url = url.replace(':kegiatanId', kegiatanId);

                    let redirectUrl = "{{ route('lampiranKegiatan.review', $lampiranKegiatan->kegiatan->id) }}";

                    const formData = $('#formCatatanSetujui').serialize();

                    Swal.fire({
                        title: 'Apakah Anda yakin?',
                        text: "Anda tidak akan bisa mengembalikan ini!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#00ac69',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: 'Ya, setujui!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $.ajax({
                                url: url,
                                type: 'POST',
                                data: formData,
                                success: function(response) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Disetujui!',
                                        text: response.success,
                                        timer: 3000
                                    }).then((result) => {
                                        window.location.href = redirectUrl;
                                    });

                                    setTimeout(function() {
                                        location.location.href = redirectUrl;
                                    }, 3000);
                                },
                                error: function(xhr) {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Oops...',
                                        text: 'Terjadi kesalahan saat memperbarui status!'
                                    });
                                }
                            });
                        }
                    });
                });
            });
        </script>
    @endpush
@endrole
