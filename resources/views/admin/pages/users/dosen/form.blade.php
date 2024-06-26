@extends('admin.layouts.app')

@push('css')
    <link href="{{ asset('assets/admin/libs/sweetalert2/css/sweetalert2.min.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('assets/admin/libs/datepicker/css/bootstrap-datepicker.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/admin/libs/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/admin/libs/select2/css/select2-bootstrap-5-theme.min.css') }}">
    <style>
        .rounded-circle-image {
            width: 150px;
            height: 150px;
            object-fit: cover;
            border-radius: 50%;
        }

        #togglePassword {
            width: 18px;
            height: 18px;
            cursor: pointer;
            background: white;
            right: -3px !important;
        }

        #defaultPassword {
            cursor: pointer;
        }

        #defaultPassword:hover {
            text-decoration: underline;
        }

        .btn-pendidikan {
            position: absolute;
            right: 0px;
            top: 50%;
            transform: translateY(-50%);
            margin: 0 10px;
            width: 20px;
            height: 20px;
            border-radius: 50% !important;
            padding: 0 !important;
            z-index: 5;
        }

        .add_pendidikan {
            color: #0061f2 !important;
            background: transparent !important;
        }

        .remove_pendidikan {
            color: #e81500 !important;
            background: transparent !important;
        }

        .select2-container--bootstrap-5 .select2-selection {
            padding: 0.875rem 1.125rem !important;
            font-size: 0.875rem !important;
        }

        .select2-container--bootstrap-5 .select2-selection--multiple .select2-search .select2-search__field {
            line-height: 1.7 !important;
        }

        .select2-selection__choice {
            background-color: #e9ecef;
            border: none !important;
            font-size: 12px;
            font-size: 0.85rem !important;
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
                        <a class="btn btn-sm btn-light text-primary" href="{{ route('users.byDosen') }}">
                            <i class="fa-solid fa-arrow-left me-1"></i>
                            Kembali
                        </a>
                        <a class="btn btn-sm btn-light text-primary" href="{{ route('bidangKepakaran.index') }}">
                            <i class="fa-regular fa-lightbulb me-1"></i>
                            Daftar Bidang Kepakaran
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <div class="container-xl px-4 mt-4">
        <form id="thisForm"
            action="@if (isset($user)) {{ route('users.updateDosen', $user->id) }} @else {{ route('users.storeDosen') }} @endif"
            method="POST" class="row gap-4" enctype="multipart/form-data">
            @csrf
            @if (isset($user))
                @method('PUT')
            @endif

            <div class="col-xl-12">
                <div class="card mb-4 mb-xl-0">
                    <div class="card-header">Foto Profil</div>
                    <div class="card-body text-center">
                        <img class="rounded-circle-image mb-2" id="previewImage"
                            src="{{ isset($user)
                                ? ($user->foto
                                    ? asset('storage/usersProfile/' . $user->foto)
                                    : asset('assets/admin/img/user-placeholder.svg'))
                                : asset('assets/admin/img/user-placeholder.svg') }}"
                            alt="" />
                        <div class="small font-italic text-muted mb-4">JPG atau PNG tidak lebih besar dari 2 MB</div>
                        <input type="file" name="foto" id="photoInput" class="d-none"
                            accept="image/jpeg, image/png" />
                        <label for="photoInput" id="unggahFoto" class="btn btn-primary" type="button">
                            <i class="fas fa-upload me-1"></i>
                            Unggah Foto
                        </label>
                        @error('foto')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="col-xl-12">
                <div class="card mb-4">
                    <div class="card-header">Informasi Dosen</div>
                    <div class="card-body">
                        @if (!isset($user))
                            <div class="mb-3">
                                <label class="small mb-1" for="nameField">
                                    Nama Lengkap
                                    <span class="text-danger">*</span>
                                </label>
                                <input class="form-control @error('name') is-invalid @enderror" name="name"
                                    id="nameField" type="text" placeholder="Masukkan nama lengkap dosen"
                                    value="{{ old('name', $user->name ?? '') }}" />
                                @error('name')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="row">
                                <div class="col-12 col-md-6">
                                    <div class="mb-3">
                                        <label class="small mb-1" for="jafaField">
                                            JAFA (Jabatan Fungsional/Akademik Dosen)
                                            <span class="text-danger">*</span>
                                        </label>
                                        <input class="form-control @error('jafa') is-invalid @enderror" name="jafa"
                                            id="jafaField" type="text" placeholder="Masukkan jafa dosen"
                                            value="{{ old('jafa', $user->jafa ?? '') }}" />
                                        @error('jafa')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label class="small mb-1">
                                            Jenis Kelamin
                                            <span class="text-danger">*</span>
                                        </label>
                                        <div class="form-check">
                                            <input class="form-check-input" id="jk-lakilaki" type="radio"
                                                name="jenis_kelamin" value="Laki-laki">
                                            <label class="form-check-label" for="jk-lakilaki">Laki-laki</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" id="jk-perempuan" type="radio"
                                                name="jenis_kelamin" value="Perempuan">
                                            <label class="form-check-label" for="jk-perempuan">Perempuan</label>
                                        </div>
                                        @error('jenis_kelamin')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label class="small mb-1" for="umurField">
                                            Umur
                                            <span class="text-danger">*</span>
                                        </label>
                                        <input class="form-control @error('umur') is-invalid @enderror" name="umur"
                                            id="umurField" type="text" placeholder="Masukkan umur dosen"
                                            value="{{ old('umur', $user->dosen->umur ?? '') }}" />
                                        @error('umur')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="mb-3">
                                        <label class="small mb-1" for="nipField">
                                            NIP
                                            <span class="text-danger">*</span>
                                        </label>
                                        <input class="form-control @error('nip') is-invalid @enderror" name="nip"
                                            id="nipField" type="nip" placeholder="Masukkan nip dosen"
                                            value="{{ old('nip', $user->nip ?? '') }}" autocomplete="off" />
                                        @error('nip')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label class="small mb-1" for="emailField">
                                            Email
                                            <span class="text-danger">*</span>
                                        </label>
                                        <input class="form-control @error('email') is-invalid @enderror" name="email"
                                            id="emailField" type="email" placeholder="Masukkan email dosen"
                                            value="{{ old('email', $user->email ?? '') }}" autocomplete="off" />
                                        @error('email')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label class="small mb-1 w-100" for="passwordField">
                                            Password
                                            @if (!isset($user))
                                                <span class="text-danger">*</span>
                                            @endif
                                        </label>
                                        <div class="position-relative">
                                            <input class="form-control @error('password') is-invalid @enderror"
                                                name="password" id="passwordField" type="password"
                                                placeholder="Masukkan password dosen" value="{{ old('password') }}" />
                                            <span id="togglePassword"
                                                class="position-absolute top-50 end-0 translate-middle-y me-3 d-flex"
                                                data-feather="eye-off">
                                            </span>
                                        </div>
                                        @error('password')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="small mb-1" for="prodiField">
                                    Program Studi
                                    <span class="text-danger">*</span>
                                </label>
                                <select class="form-control @error('program_studi') is-invalid @enderror" name="program_studi"
                                    id="prodiField">
                                    <option value="" selected hidden>-- Pilih Program Studi --</option>
                                    <option value="SISTEM INFORMASI" @if (isset($user) && $user->dosen->program_studi == 'SISTEM INFORMASI') selected @endif>
                                        SISTEM INFORMASI
                                    </option>
                                    <option value="PEND. TEKNOLOGI INFORMASI" @if (isset($user) && $user->dosen->program_studi == 'PEND. TEKNOLOGI INFORMASI') selected @endif>
                                        PEND. TEKNOLOGI INFORMASI
                                    </option>
                                </select>
                                @error('program_studi')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        @else
                            <div class="mb-3">
                                <label class="small mb-1" for="nameField">
                                    Nama Lengkap
                                    <span class="text-danger">*</span>
                                </label>
                                <input class="form-control @error('name') is-invalid @enderror" name="name"
                                    id="nameField" type="text" placeholder="Masukkan nama lengkap dosen"
                                    value="{{ old('name', $user->name ?? '') }}" />
                                @error('name')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="row">
                                <div class="col-12 col-md-6">
                                    <div class="mb-3">
                                        <label class="small mb-1" for="jafaField">
                                            JAFA (Jabatan Fungsional/Akademik Dosen)
                                            <span class="text-danger">*</span>
                                        </label>
                                        <input class="form-control @error('jafa') is-invalid @enderror" name="jafa"
                                            id="jafaField" type="text" placeholder="Masukkan jafa dosen"
                                            value="{{ old('jafa', $user->dosen->jafa ?? '') }}" />
                                        @error('jafa')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label class="small mb-1">
                                            Jenis Kelamin
                                            <span class="text-danger">*</span>
                                        </label>
                                        <div class="form-check">
                                            <input class="form-check-input" id="jk-lakilaki" type="radio"
                                                name="jenis_kelamin" value="Laki-laki"
                                                {{ optional($user->dosen)->jenis_kelamin == 'Laki-laki' ? 'checked' : '' }}>
                                            <label class="form-check-label" for="jk-lakilaki">Laki-laki</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" id="jk-perempuan" type="radio"
                                                name="jenis_kelamin" value="Perempuan"
                                                {{ optional($user->dosen)->jenis_kelamin == 'Perempuan' ? 'checked' : '' }}>
                                            <label class="form-check-label" for="jk-perempuan">Perempuan</label>
                                        </div>
                                        @error('jenis_kelamin')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label class="small mb-1" for="umurField">
                                            Umur
                                            <span class="text-danger">*</span>
                                        </label>
                                        <input class="form-control @error('umur') is-invalid @enderror" name="umur"
                                            id="umurField" type="text" placeholder="Masukkan umur dosen"
                                            value="{{ old('umur', $user->dosen->umur ?? '') }}" />
                                        @error('umur')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="mb-3">
                                        <label class="small mb-1" for="nipField">
                                            NIP
                                            <span class="text-danger">*</span>
                                        </label>
                                        <input class="form-control @error('nip') is-invalid @enderror" name="nip"
                                            id="nipField" type="nip" placeholder="Masukkan nip dosen"
                                            value="{{ old('nip', $user->dosen->nip ?? '') }}" autocomplete="off" />
                                        @error('nip')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label class="small mb-1" for="emailField">
                                            Email
                                            <span class="text-danger">*</span>
                                        </label>
                                        <input class="form-control @error('email') is-invalid @enderror" name="email"
                                            id="emailField" type="email" placeholder="Masukkan email dosen"
                                            value="{{ old('email', $user->email ?? '') }}" autocomplete="off" />
                                        @error('email')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label class="small mb-1" for="prodiField">
                                            Program Studi
                                            <span class="text-danger">*</span>
                                        </label>
                                        <select class="form-control @error('program_studi') is-invalid @enderror" name="program_studi"
                                            id="prodiField">
                                            <option value="" selected hidden>-- Pilih Program Studi --</option>
                                            <option value="SISTEM INFORMASI" @if (isset($user) && $user->dosen->program_studi == 'SISTEM INFORMASI') selected @endif>
                                                SISTEM INFORMASI
                                            </option>
                                            <option value="PEND. TEKNOLOGI INFORMASI" @if (isset($user) && $user->dosen->program_studi == 'PEND. TEKNOLOGI INFORMASI') selected @endif>
                                                PEND. TEKNOLOGI INFORMASI
                                            </option>
                                        </select>
                                        @error('program_studi')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        @endif

                        <div class="d-flex gap-3 align-items-center mt-3">
                            <hr class="flex-grow-1 border-bottom">
                            <span class="text-muted small fw-600">Informasi Lainnya</span>
                            <hr class="flex-grow-1 border-bottom">
                        </div>

                        <div class="mb-3">
                            <label class="small mb-1" for="biografiField">
                                Biografi
                            </label>
                            <textarea name="biografi" rows="5" id="biografiField" class="form-control" placeholder="Biografi dosen...">{{ old('biografi', $user->dosen->biografi ?? '') }}</textarea>
                        </div>
                        <div class="mb-3">
                            <label class="small mb-1" for="pendidikanField">
                                Pendidikan
                            </label>
                            <div class="position-relative">
                                <div class="input-group input-group-joined pendidikanGroup">
                                    <span class="input-group-text">
                                        <i class="fa-solid fa-user-graduate"></i>
                                    </span>
                                    <input type="text" id="pendidikanField" name="pendidikan[]" class="form-control"
                                        value="{{ $user->dosen->pendidikans[0]->pendidikan ?? '' }}" placeholder="...">
                                </div>
                                <button class="btn btn-primary btn-pendidikan add_pendidikan" type="button">
                                    <i class='fa-solid fa-plus-circle'></i>
                                </button>
                            </div>
                        </div>
                        <div id="extraPendidikanField">
                            @if (isset($user))
                                @if (isset($user->dosen->pendidikans))
                                    @foreach ($user->dosen->pendidikans as $item)
                                        @if ($loop->iteration != 1)
                                            <div class="position-relative mb-3">
                                                <div class="input-group input-group-joined pendidikanGroup">
                                                    <span class="input-group-text">
                                                        <i class="fa-solid fa-user-graduate"></i>
                                                    </span>
                                                    <input type="text" id="pendidikanField" name="pendidikan[]"
                                                        class="form-control" value="{{ $item->pendidikan ?? '' }}"
                                                        placeholder="...">
                                                </div>
                                                <button class="btn btn-danger btn-pendidikan remove_pendidikan"
                                                    type="button">
                                                    <i class='fa-solid fa-minus-circle'></i>
                                                </button>
                                            </div>
                                        @endif
                                    @endforeach
                                @endif
                            @endif
                        </div>
                        <div class="mb-3">
                            <label class="small mb-1" for="bidangKepakaranField">
                                Bidang Kepakaran
                            </label>
                            <select name="bidang_kepakaran[]" id="bidangKepakaranField" class="form-select select2"
                                multiple>
                                @if (!isset($user))
                                    @foreach ($bidangKepakarans as $item)
                                        <option value="{{ $item->id }}">{{ $item->bidang_kepakaran }}</option>
                                    @endforeach
                                @else
                                    @foreach ($bidangKepakarans as $item)
                                        <option value="{{ $item->id }}"
                                            @if ($user->dosen && $user->dosen->bidangKepakarans->contains($item->id)) selected @endif>
                                            {{ $item->bidang_kepakaran }}
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="small mb-1" for="minatPenelitianField">
                                Minat Penelitian
                            </label>
                            <input class="form-control" name="minat_penelitian" id="minatPenelitianField" type="text"
                                placeholder="Masukkan minat penelitan dosen"
                                value="{{ old('minat_penelitian', $user->dosen->minat_penelitian ?? '') }}">
                        </div>
                        <div class="mb-3">
                            <label class="small mb-1" for="linkScopusField">
                                Tautan Profil - Scopus
                            </label>
                            <div class="input-group input-group-joined">
                                <span class="input-group-text">
                                    <i data-feather="link"></i>
                                </span>
                                <input class="form-control ps-0" name="link_scopus" id="linkScopusField" type="text"
                                    placeholder="https://www.scopus.com/..."
                                    value="{{ old('link_scopus', $user->dosen->link_scopus ?? '') }}">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="small mb-1" for="linkSintaField">
                                Tautan Profil - Sinta
                            </label>
                            <div class="input-group input-group-joined">
                                <span class="input-group-text">
                                    <i data-feather="link"></i>
                                </span>
                                <input class="form-control ps-0" name="link_sinta" id="linkSintaField" type="text"
                                    placeholder="https://sinta.kemdikbud.go.id/..."
                                    value="{{ old('link_sinta', $user->dosen->link_sinta ?? '') }}">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="small mb-1" for="linkGscholarField">
                                Tautan Profil - Google Scholar
                            </label>
                            <div class="input-group input-group-joined">
                                <span class="input-group-text">
                                    <i data-feather="link"></i>
                                </span>
                                <input class="form-control ps-0" name="link_gscholar" id="linkGscholarField"
                                    type="text" placeholder="https://scholar.google.com/..."
                                    value="{{ old('link_gscholar', $user->dosen->link_gscholar ?? '') }}">
                            </div>
                        </div>

                        <button class="btn btn-light" type="reset">
                            <i class="fa-solid fa-rotate-left me-1"></i>
                            Atur Ulang
                        </button>
                        <button class="btn btn-primary" type="submit" onclick="return onSubmit()">
                            <i class="fa-solid fa-floppy-disk me-1"></i>
                            @if (isset($user))
                                Perbarui
                            @else
                                Simpan
                            @endif
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection

@push('js')
    <script src="{{ asset('assets/admin/libs/jquery/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('assets/admin/libs/sweetalert2/js/sweetalert2.all.min.js') }}"></script>
    <script src="{{ asset('assets/admin/libs/datepicker/js/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ asset('assets/admin/libs/select2/js/select2.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            // inisialisasi select2
            $('.select2').select2({
                theme: 'bootstrap-5',
                dropdownCssClass: "select2--small",
                placeholder: "Cari Bidang Kepakaran...",
                width: '100%',
            });

            // menangani perubahan input file
            $('#photoInput').on('change', function() {
                previewImage(this);
            });

            // memicu input file klik pada tombol klik
            $('#unggahFoto').on('click', function(e) {
                e.preventDefault();

                // mengatur ulang nilai input file
                $('#photoInput').val('');

                // memicu peristiwa klik
                $('#photoInput').click();
            });

            // menangani default password (nip)
            $('#nipField').on('input', function() {
                $('#passwordField').val(this.value);
            });

            // menangani bootstrap datepicker
            $('#angkatanField').datepicker({
                format: 'yyyy',
                viewMode: 'years',
                minViewMode: 'years',
                autoclose: true,
                todayHighlight: true,
                endDate: new Date(),
            });

            // menangani event toggle password
            $('#togglePassword').click(function() {
                const input = $('#passwordField');
                const icon = $(this);

                if (input.attr('type') === 'password') {
                    input.attr('type', 'text');
                    icon.html(feather.icons['eye'].toSvg());
                } else {
                    input.attr('type', 'password');
                    icon.html(feather.icons['eye-off'].toSvg());
                }
            });

            // menangani event tombol hapus pendidikan
            $('#extraPendidikanField').on('click', '.remove_pendidikan', function() {
                $(this).parent().remove();
            });

            // menangani event tombol tambah pendidikan
            $('.add_pendidikan').on('click', function() {
                const appendField = `
                    <div class="position-relative mb-3">
                        <div class="input-group input-group-joined pendidikanGroup">
                            <span class="input-group-text">
                                <i class="fa-solid fa-user-graduate"></i>
                            </span>
                            <input type="text" id="pendidikanField" name="pendidikan[]" class="form-control" placeholder="...">
                        </div>
                        <button class="btn btn-danger btn-pendidikan remove_pendidikan" type="button">
                            <i class='fa-solid fa-minus-circle'></i>
                        </button>
                    </div>
				`;
                $('#extraPendidikanField').append(appendField);
            });

        });

        // fungsi melihat pratinjau gambar
        function previewImage(input) {
            if (input.files && input.files[0]) {
                // validasi ukuran file (maksimum 2MB)
                if (input.files[0].size > 2 * 1024 * 1024) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Maaf terjadi kesalahan',
                        text: 'Ukuran file maksimal 2 MB'
                    });

                    // mengatur ulang gambar pratinjau
                    $('#previewImage').attr('src', '{{ asset('assets/admin/img/user-placeholder.svg') }}');
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

        // fungsi konfirmasi submit form
        function onSubmit() {
            Swal.fire({
                title: 'Apakah anda yakin?',
                html: 'Jika anda yakin, silahkan klik tombol <b>Yakin</b> di bawah ini. Jika tidak, klik tombol <b>Batal</b>.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#00ac69',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yakin',
                cancelButtonText: 'Batal',
            }).then((result) => {
                if (result.isConfirmed) {
                    $('#thisForm').submit();
                }
            });

            return false;
        }
    </script>
@endpush
