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
                        <a class="btn btn-sm btn-light text-primary" href="{{ URL::previous() }}">
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
            action="@if (isset($user)) {{ route('users.updateAdmin', $user->id) }} @else {{ route('users.storeAdmin') }} @endif"
            method="POST" class="row" enctype="multipart/form-data">
            @csrf
            @if (isset($user))
                @method('PUT')
            @endif

            <div class="col-xl-4">
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

            <div class="col-xl-8">
                <div class="card mb-4">
                    <div class="card-header">Informasi Admin</div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="small mb-1" for="nameField">
                                Nama Lengkap
                                <span class="text-danger">*</span>
                            </label>
                            <input class="form-control @error('name') is-invalid @enderror" name="name" id="nameField"
                                type="text" placeholder="Masukkan nama lengkap anda"
                                value="{{ old('name', $user->name ?? '') }}" />
                            @error('name')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="small mb-1" for="emailField">
                                Email
                                <span class="text-danger">*</span>
                            </label>
                            <input class="form-control @error('email') is-invalid @enderror" name="email" id="emailField"
                                type="email" placeholder="Masukkan email anda"
                                value="{{ old('email', $user->email ?? '') }}" />
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
                                <span id="defaultPassword" class="text-primary float-end">Default
                                    Password</span>
                            </label>
                            <div class="position-relative">
                                <input class="form-control @error('password') is-invalid @enderror" name="password"
                                    id="passwordField" type="password" placeholder="Masukkan password anda"
                                    value="{{ old('password') }}" />
                                <span id="togglePassword"
                                    class="position-absolute top-50 end-0 translate-middle-y me-3 d-flex"
                                    data-feather="eye-off">
                                </span>
                            </div>
                            @error('password')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <button class="btn btn-light" type="reset">
                            <i class="fa-solid fa-rotate-left me-1"></i>
                            Atur Ulang
                        </button>
                        <button class="btn btn-primary" type="submit">
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
    <script>
        $(document).ready(function() {
            // Handle file input change
            $('#photoInput').on('change', function() {
                previewImage(this);
            });

            // Trigger file input click on button click
            $('#unggahFoto').on('click', function(e) {
                e.preventDefault();

                // Reset the value of the file input
                $('#photoInput').val('');

                // Trigger the click event
                $('#photoInput').click();
            });

            // Handle default password
            $('#defaultPassword').click(function() {
                $('#passwordField').val('password123');
            });

            // Handle toggle password
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
        });

        // toast config
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
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
            }
        });

        // Function to preview image
        function previewImage(input) {
            if (input.files && input.files[0]) {
                // Validate file size (2MB maximum)
                if (input.files[0].size > 2 * 1024 * 1024) {
                    Toast.fire({
                        icon: 'error',
                        title: 'Ukuran file harus kurang atau tidak lebih dari 2 MB.'
                    });

                    $('#previewImage').attr('src', '{{ asset('assets/admin/img/user-placeholder.svg') }}');
                    return;
                }

                var reader = new FileReader();

                reader.onload = function(e) {
                    $('#previewImage').attr('src', e.target.result);
                };

                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
@endpush
