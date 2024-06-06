@extends('admin.layouts.app')

@push('css')
    <link href="{{ asset('assets/admin/libs/sweetalert2/css/sweetalert2.min.css') }}" rel="stylesheet" />

    <style>
        .textarea-deskripsi {
            line-height: 1.5 !important;
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
                        <a class="btn btn-sm btn-light text-primary" href="{{ route('profilLulusan.index') }}">
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
            action="@if (isset($profilLulusan)) {{ route('profilLulusan.update', $profilLulusan->id) }} @else {{ route('profilLulusan.store') }} @endif"
            method="POST" class="row" enctype="multipart/form-data">
            @csrf
            @if (isset($profilLulusan))
                @method('PUT')
            @endif

            @if (isset($profilLulusan))
                <div class="col-xl-4">
                    <div class="card mb-4">
                        <div class="card-body p-0 overflow-hidden">
                            <img src="{{ asset('storage/akademik/profil-lulusan/' . $profilLulusan->gambar) }}"
                                alt="profil-lulusan-image" class="img-fluid">
                            <div class="d-flex border-top flex-column gap-3 p-3">
                                <div>
                                    <h1 class="small fw-bolder mb-2">Judul</h1>
                                    <p class="mb-0">{{ $profilLulusan->judul }}</p>
                                </div>
                                <div>
                                    <h1 class="small fw-bolder mb-2">Subjudul</h1>
                                    <p class="mb-0">{{ $profilLulusan->subjudul }}</p>
                                </div>
                                <div>
                                    <h1 class="small fw-bolder mb-2">Deskripsi</h1>
                                    <p class="mb-0">{{ $profilLulusan->deskripsi }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <div class="{{ isset($profilLulusan) ? 'col-xl-8' : 'col-xl-6' }}">
                <div class="card mb-4">
                    <div class="card-header">Form Profil Lulusan</div>
                    @if (isset($profilLulusan) && $profilLulusan->createdBy)
                        <div class="card-header bg-white">
                            <div
                                class="d-flex flex-column flex-md-row-reverse align-items-start align-items-md-center justify-content-between">
                                <div class="text-xs text-muted">
                                    <i class="fa-solid fa-user fa-xs me-1"></i>
                                    <span>
                                        {{ $profilLulusan->createdBy->name }}
                                    </span>
                                </div>
                                <div class="text-xs text-muted">
                                    <i class="fa-solid fa-calendar fa-xs me-1"></i>
                                    <span>
                                        {{ \Carbon\Carbon::parse($profilLulusan->created_at)->isoFormat('dddd, D MMMM Y H:mm') }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    @endif
                    <div class="card-body">
                        @role ('Superadmin|Admin|Kajur')
                            <div class="mb-3">
                                <label class="small mb-1" for="prodiField">
                                    Program Studi
                                    <span class="text-danger">*</span>
                                </label>
                                <select name="program_studi" id="prodiField"
                                    class="form-select @error('program_studi') is-invalid @enderror">
                                    <option value="" selected hidden>-- Pilih Program Studi --</option>
                                    <option value="SISTEM INFORMASI" @if (isset($profilLulusan) && $profilLulusan->program_studi == 'SISTEM INFORMASI') selected @endif>
                                        SISTEM INFORMASI
                                    </option>
                                    <option value="PEND. TEKNOLOGI INFORMASI" @if (isset($profilLulusan) && $profilLulusan->program_studi == 'PEND. TEKNOLOGI INFORMASI') selected @endif>
                                        PEND. TEKNOLOGI INFORMASI
                                    </option>
                                </select>
                                @error('program_studi')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        @endrole
                        <div class="mb-3">
                            <label class="small mb-1" for="judulField">
                                Judul
                                <span class="text-danger">*</span>
                            </label>
                            <input class="form-control @error('judul') is-invalid @enderror" name="judul" id="judulField"
                                type="text" placeholder="Masukkan judul"
                                value="{{ old('judul', $profilLulusan->judul ?? '') }}" />
                            @error('judul')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="small mb-1" for="subjudulField">
                                Subjudul
                                <span class="text-danger">*</span>
                            </label>
                            <input class="form-control @error('subjudul') is-invalid @enderror" name="subjudul"
                                id="subjudulField" type="text" placeholder="Masukkan subjudul"
                                value="{{ old('subjudul', $profilLulusan->subjudul ?? '') }}" />
                            @error('subjudul')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="small mb-1" for="deskripsiField">
                                Deskripsi
                                <span class="text-danger">*</span>
                            </label>
                            <textarea name="deskripsi" id="deskripsiField" rows="4" spellcheck="false"
                                class="form-control textarea-deskripsi @error('deskripsi') is-invalid @enderror" placeholder="Masukkan deskripsi">{{ old('deskripsi', $profilLulusan->deskripsi ?? '') }}</textarea>
                            @error('deskripsi')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="small mb-1">
                                Gambar
                                @if (!isset($profilLulusan))
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
                            @if (isset($profilLulusan))
                                Perbarui
                            @else
                                Simpan
                            @endif
                        </button>
                    </div>
                    @if (isset($profilLulusan) && $profilLulusan->updatedBy)
                        <div class="card-footer p-2 bg-white">
                            <div
                                class="d-flex gap-3 py-2 align-items-center text-start bg-white text-muted text-xs overflow-hidden">
                                <div class="px-3 border-end border-2">
                                    <i class="fa-solid fa-circle-info"></i>
                                </div>
                                <div>
                                    Terakhir diperbarui oleh
                                    <span class="fw-bolder">
                                        {{ $profilLulusan->updatedBy->name }}
                                    </span>
                                    pada
                                    <span class="fw-bolder">
                                        {{ \Carbon\Carbon::parse($profilLulusan->updated_at)->isoFormat('dddd, D MMMM Y') }}
                                    </span>
                                    pukul
                                    <span class="fw-bolder">
                                        {{ \Carbon\Carbon::parse($profilLulusan->updated_at)->isoFormat('H:mm') }}
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
