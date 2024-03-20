@extends('admin.layouts.app')

@push('css')
    <link href="{{ asset('assets/admin/libs/sweetalert2/css/sweetalert2.min.css') }}" rel="stylesheet" />
    <style>
        .rounded-circle-image {
            width: 150px;
            height: 150px;
            object-fit: cover;
            border-radius: 50%;
        }

        #togglePasswordBaru,
        #toggleKonfirmasiPasswordBaru {
            width: 18px;
            height: 18px;
            cursor: pointer;
            background: white;
            right: -3px !important;
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
                        <a id="tombolKembali" class="btn btn-sm btn-light text-primary" href="{{ URL::previous() }}">
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
        <div class="row g-4">
            <div class="col-12 col-md-4 col-xl-3">
                <div class="card">
                    <div class="card-header">Informasi Pengguna</div>
                    <div class="card-body">
                        <div class="text-center mb-3">
                            <img class="rounded-circle-image mb-2" id="previewImage"
                                src="{{ isset($user['foto']) ? asset('storage/usersProfile/' . $user['foto']) : asset('assets/admin/img/user-placeholder.svg') }}"
                                alt="" />
                        </div>
                        <div class="mb-3">
                            <label class="fw-bolder text-sm">Nama</label>
                            <p class="mb-0">{{ $user['name'] }}</p>
                        </div>
                        <div class="mb-3">
                            @if ($user['role_name'] == 'Admin')
                                <label class="fw-bolder text-sm">Email</label>
                                <p class="mb-0">{{ $user['email'] }}</p>
                            @elseif ($user['role_name'] == 'Dosen')
                                <label class="fw-bolder text-sm">NIP</label>
                                <p class="mb-0">{{ $user['nip'] }}</p>
                            @elseif ($user['role_name'] == 'Mahasiswa')
                                <label class="fw-bolder text-sm">NIM</label>
                                <p class="mb-0">{{ $user['nim'] }}</p>
                            @endif
                        </div>
                        <div class="mb-3">
                            <label class="fw-bolder text-sm">Peran</label>
                            <p class="mb-0">{{ $user['role_name'] }}</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card">
                    <div class="card-header">Setel Ulang Password</div>
                    <div class="card-body">
                        <form id="resetPasswordForm" method="POST"
                            action="{{ route('users.resetPassword', $user['id']) }}">
                            @csrf
                            @method('PUT')
                            <div class="mb-3">
                                <label class="small mb-1 w-100" for="passwordBaruField">
                                    Password Baru
                                    <span class="text-danger">*</span>
                                </label>
                                <div class="position-relative">
                                    <input class="form-control @error('password_baru') is-invalid @enderror"
                                        name="password_baru" id="passwordBaruField" type="password"
                                        placeholder="Masukkan password baru" value="{{ old('password_baru') }}" />
                                    <span id="togglePasswordBaru"
                                        class="position-absolute top-50 end-0 translate-middle-y me-3 d-flex"
                                        data-feather="eye-off">
                                    </span>
                                </div>
                                @error('password_baru')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label class="small mb-1 w-100" for="konfirmasiPasswordBaruField">
                                    Konfirmasi Password Baru
                                    <span class="text-danger">*</span>
                                </label>
                                <div class="position-relative">
                                    <input class="form-control @error('konfirmasi_password_baru') is-invalid @enderror"
                                        name="konfirmasi_password_baru" id="konfirmasiPasswordBaruField" type="password"
                                        placeholder="Konfirmasi password baru"
                                        value="{{ old('konfirmasi_password_baru') }}" />
                                    <span id="toggleKonfirmasiPasswordBaru"
                                        class="position-absolute top-50 end-0 translate-middle-y me-3 d-flex"
                                        data-feather="eye-off">
                                    </span>
                                </div>
                                @error('konfirmasi_password_baru')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <button class="btn btn-light" type="reset">
                                <i class="fa-solid fa-rotate-left me-1"></i>
                                Atur Ulang
                            </button>
                            <button class="btn btn-primary" type="submit" onclick="return onSubmit()">
                                <i class="fa-solid fa-floppy-disk me-1"></i>
                                Simpan
                            </button>
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
            // toast notifikasi
            @if (Session::has('success'))
                // munculkan dialog dengan pesan "tunggu sebentar, akan kembali ke halaman sebelumnya setelah 3 detik"
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil Mengatur Ulang Password',
                    text: 'Tunggu sebentar, akan kembali ke halaman sebelumnya setelah 3 detik',
                    allowOutsideClick: false,
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true
                }).then(() => {
                    history.go(-2);
                });

                // menonaktifkan tombol kembali
                $('#tombolKembali').addClass('disabled');
            @endif

            // menangani event toggle password baru
            $('#togglePasswordBaru').click(function() {
                const input = $('#passwordBaruField');
                const icon = $(this);

                if (input.attr('type') === 'password') {
                    input.attr('type', 'text');
                    icon.html(feather.icons['eye'].toSvg());
                } else {
                    input.attr('type', 'password');
                    icon.html(feather.icons['eye-off'].toSvg());
                }
            });

            // menangani event toggle konfirmasi password baru
            $('#toggleKonfirmasiPasswordBaru').click(function() {
                const input = $('#konfirmasiPasswordBaruField');
                const icon = $(this);

                if (input.attr('type') === 'password') {
                    input.attr('type', 'text');
                    icon.html(feather.icons['eye'].toSvg());
                } else {
                    input.attr('type', 'password');
                    icon.html(feather.icons['eye-off'].toSvg());
                }
            });
        });

        // fungsi sweet alert setelah menerima permintaan reset password
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
                    $('#resetPasswordForm').submit();
                }
            });

            return false;
        }
    </script>
@endpush
