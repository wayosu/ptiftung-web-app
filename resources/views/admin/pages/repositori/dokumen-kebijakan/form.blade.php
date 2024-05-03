@extends('admin.layouts.app')

@push('css')
    <link href="{{ asset('assets/admin/libs/sweetalert2/css/sweetalert2.min.css') }}" rel="stylesheet" />

    <style>
        .btn-hapus-dokumen {
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
                        <a class="btn btn-sm btn-light text-primary" href="{{ route('dokumenKebijakan.index') }}">
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
            action="@if (isset($dokumenKebijakan)) {{ route('dokumenKebijakan.update', $dokumenKebijakan->id) }} @else {{ route('dokumenKebijakan.store') }} @endif"
            method="POST" class="row" enctype="multipart/form-data">
            @csrf
            @if (isset($dokumenKebijakan))
                @method('PUT')
            @endif

            <div class="col-xl-6">
                <div class="card mb-4">
                    <div class="card-header">Form {{ $title ?? '' }}</div>
                    @if (isset($dokumenKebijakan) && $dokumenKebijakan->createdBy)
                        <div class="card-header bg-white">
                            <div
                                class="d-flex flex-column flex-md-row-reverse align-items-start align-items-md-center justify-content-between">
                                <div class="text-xs text-muted">
                                    <i class="fa-solid fa-user fa-xs me-1"></i>
                                    <span>
                                        {{ $dokumenKebijakan->createdBy->name }}
                                    </span>
                                </div>
                                <div class="text-xs text-muted">
                                    <i class="fa-solid fa-calendar fa-xs me-1"></i>
                                    <span>
                                        {{ \Carbon\Carbon::parse($dokumenKebijakan->created_at)->isoFormat('dddd, D MMMM Y H:mm') }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    @endif
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="small mb-1" for="kategoriField">
                                Kategori
                                <span class="text-danger">*</span>
                            </label>
                            <select name="kategori" id="kategoriField"
                                class="form-select @error('kategori') is-invalid @enderror">
                                <option value="" hidden>-- Pilih Kategori --</option>
                                @foreach ($kategoris as $key => $value)
                                    <option value="{{ $key }}"
                                        {{ old('kategori', $dokumenKebijakan->kategori ?? '') == $key ? 'selected' : '' }}>
                                        {{ $value }}</option>
                                @endforeach
                            </select>
                            @error('kategori')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="small mb-1" for="keteranganField">
                                Keterangan
                                <span class="text-danger">*</span>
                            </label>
                            <input class="form-control @error('keterangan') is-invalid @enderror" name="keterangan"
                                id="keteranganField" type="text" placeholder="Masukkan keterangan"
                                value="{{ old('keterangan', $dokumenKebijakan->keterangan ?? '') }}" autocomplete="off" />
                            @error('keterangan')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        @if (!empty($dokumenKebijakan->dokumen) && empty($dokumenKebijakan->link_dokumen))
                            <div class="mb-3">
                                <label class="small mb-1 d-flex align-items-center justify-content-between">
                                    <div>Dokumen</div>
                                    <a href="/storage/repositori/dokumen-kebijakan/{{ $dokumenKebijakan->dokumen }}"
                                        target="_blank">Lihat Dokumen</a>
                                </label>
                                <label id="dokumenLabel" class="custom-btn-upload" for="dokumenField">
                                    <div class="d-flex align-items-center gap-2">
                                        <i class="fa-solid fa-upload"></i>
                                        <span id="dokumenText">Unggah Dokumen</span>
                                    </div>
                                    <button type="button" role="button" id="btnHapusDokumen"
                                        class="btn-hapus-dokumen d-none">
                                        <i class="fa-solid fa-xmark fa-lg"></i>
                                    </button>
                                </label>
                                <input class="d-none" name="dokumen" id="dokumenField" type="file"
                                    accept="application/pdf, application/vnd.ms-excel, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/msword, application/vnd.openxmlformats-officedocument.wordprocessingml.document" />
                                <span class="text-xs text-muted">Format doc, docx, pdf, xls, xlsx max. 4MB</span>
                                @error('dokumen')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        @endif

                        @if (empty($dokumenKebijakan->dokumen) && !empty($dokumenKebijakan->link_dokumen))
                            <div class="mb-3">
                                <label class="small mb-1" for="tautanDokumenField">
                                    Tautan Dokumen
                                </label>
                                <div class="input-group input-group-joined">
                                    <span class="input-group-text">
                                        <i data-feather="link"></i>
                                    </span>
                                    <input class="form-control ps-0  @error('link_dokumen') is-invalid @enderror"
                                        name="link_dokumen" id="tautanDokumenField" type="text" placeholder="..."
                                        value="{{ old('link_dokumen', $dokumenKebijakan->link_dokumen ?? '') }}">
                                </div>
                                <span class="text-xs text-muted">Google Drive, DropBox, Lainnya</span>
                                @error('link_dokumen')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        @endif

                        @if (empty($dokumenKebijakan->dokumen) && empty($dokumenKebijakan->link_dokumen))
                            <div class="mb-3">
                                <label class="small mb-1">
                                    Dokumen
                                </label>
                                <label id="dokumenLabel" class="custom-btn-upload" for="dokumenField">
                                    <div class="d-flex align-items-center gap-2">
                                        <i class="fa-solid fa-upload"></i>
                                        <span id="dokumenText">Unggah Dokumen</span>
                                    </div>
                                    <button type="button" role="button" id="btnHapusDokumen"
                                        class="btn-hapus-dokumen d-none">
                                        <i class="fa-solid fa-xmark fa-lg"></i>
                                    </button>
                                </label>
                                <input class="d-none" name="dokumen" id="dokumenField" type="file"
                                    accept="application/pdf, application/vnd.ms-excel, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/msword, application/vnd.openxmlformats-officedocument.wordprocessingml.document" />
                                <span class="text-xs text-muted">Format doc, docx, pdf, xls, xlsx max. 4MB</span>
                                @error('dokumen')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label class="small mb-1" for="tautanDokumenField">
                                    Tautan Dokumen
                                    <span class="text-danger">*<small>Jika lebih dari satu dokumen</small></span>
                                </label>
                                <div class="input-group input-group-joined">
                                    <span class="input-group-text">
                                        <i data-feather="link"></i>
                                    </span>
                                    <input class="form-control ps-0  @error('link_dokumen') is-invalid @enderror"
                                        name="link_dokumen" id="tautanDokumenField" type="text" placeholder="..."
                                        value="{{ old('link_dokumen', $dokumenKebijakan->link_dokumen ?? '') }}">
                                </div>
                                <span class="text-xs text-muted">Google Drive, DropBox, Lainnya</span>
                                @error('link_dokumen')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        @endif
                        <button class="btn btn-light" type="reset">
                            <i class="fa-solid fa-rotate-left me-1"></i>
                            Atur Ulang
                        </button>
                        <button class="btn btn-primary" type="submit">
                            <i class="fa-solid fa-floppy-disk me-1"></i>
                            @if (isset($dokumenKebijakan))
                                Perbarui
                            @else
                                Simpan
                            @endif
                        </button>
                    </div>
                    @if (isset($dokumenKebijakan) && $dokumenKebijakan->updatedBy)
                        <div class="card-footer p-2 bg-white">
                            <div
                                class="d-flex gap-3 py-2 align-items-center text-start bg-white text-muted text-xs overflow-hidden">
                                <div class="px-3 border-end border-2">
                                    <i class="fa-solid fa-circle-info"></i>
                                </div>
                                <div>
                                    Terakhir diperbarui oleh
                                    <span class="fw-bolder">
                                        {{ $dokumenKebijakan->updatedBy->name }}
                                    </span>
                                    pada
                                    <span class="fw-bolder">
                                        {{ \Carbon\Carbon::parse($dokumenKebijakan->updated_at)->isoFormat('dddd, D MMMM Y') }}
                                    </span>
                                    pukul
                                    <span class="fw-bolder">
                                        {{ \Carbon\Carbon::parse($dokumenKebijakan->updated_at)->isoFormat('H:mm') }}
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
    <script src="{{ asset('assets/admin/libs/sweetalert2/js/sweetalert2.all.min.js') }}"></script>

    <script>
        $(document).ready(function() {
            @if (Session::has('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Maaf terjadi kesalahan',
                    text: '{{ Session::get('error') }}'
                })
            @endif

            // event upload dokumen
            $('#dokumenField').change(function() {
                const label = $('#dokumenLabel');
                const file = this.files[0];
                const fileName = file.name;
                const validDocTypes = [
                    "application/msword",
                    "application/vnd.openxmlformats-officedocument.wordprocessingml.document",
                    "application/pdf", "application/vnd.ms-excel",
                    "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"
                ];

                // validasi tipe file (harus berupa dokumen)
                if (!validDocTypes.includes(file.type)) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Maaf terjadi kesalahan',
                        text: 'File harus berupa dokumen (doc, docx, pdf, xls, xlsx)'
                    });

                    $(this).val(''); // reset nilai file input
                    label.find('span').text('Unggah Dokumen'); // kembalikan teks label
                    $('#btnHapusDokumen').addClass('d-none'); // sembunyikan tombol hapus

                    return false;
                }

                // validasi ukuran file (maksimum 4MB)
                if (file.size > 4 * 1024 * 1024) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Maaf terjadi kesalahan',
                        text: 'Ukuran dokumen maksimal 4 MB'
                    });

                    $(this).val(''); // reset nilai file input
                    label.find('span').text('Unggah Dokumen'); // kembalikan teks label
                    $('#btnHapusDokumen').addClass('d-none'); // sembunyikan tombol hapus

                    return false;
                }

                label.find('span').text(fileName); // mengubah teks span di dalam label
                $('#btnHapusDokumen').removeClass('d-none'); // tampilkan tombol hapus
            });

            // event hapus dokumen
            $('#btnHapusDokumen').click(function() {
                const label = $('#dokumenLabel');
                label.find('span').text('Unggah Dokumen'); // mengatur kembali teks span di dalam label
                $('#btnHapusDokumen').addClass('d-none'); // sembunyikan tombol hapus
                $('#dokumenField').val(''); // kosongkan nilai file input
            });
        });
    </script>
@endpush
