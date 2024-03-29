@extends('admin.layouts.app')

@push('css')
    <link href="{{ asset('assets/admin/libs/sweetalert2/css/sweetalert2.min.css') }}" rel="stylesheet" />

    <style>
        .image-overlay {
            position: relative;
            overflow: hidden;
            border-top-left-radius: 0.35rem;
            border-top-right-radius: 0.35rem;
        }

        .image-overlay::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 100%;
            height: 100%;
            background-image: url({{ asset('assets/admin/img/bg-pattern-shapes.png') }});
            background-color: #0061f2;
        }

        .image-overlay::after {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.1);
        }

        .rounded-circle-image {
            width: 130px;
            height: 130px;
            object-fit: cover;
            border-radius: 50%;
        }

        .btn-edit-dosen {
            position: relative;
            transition: all 0.3s;
        }

        .btn-edit-dosen span {
            opacity: 0;
            visibility: hidden;
            position: absolute;
            top: 100%;
            left: 0;
            margin-left: 5px;
            padding: 0.5rem;
            background-color: #ffffff;
            border-radius: 5px;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.2);
            white-space: nowrap;
            transition: opacity 0.3s, visibility 0.3s;
        }

        .btn-edit-dosen:hover span {
            opacity: 1;
            visibility: visible;
        }

        .text-biografi {
            text-align: justify;
        }

        .toggle-biografi {
            cursor: pointer;
        }

        .ul-pendidikan {
            list-style-type: square;
            padding-left: 20px;
        }

        .comma {
            margin-left: -4px;
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
                            <div class="page-header-icon"><i class="{{ $icon ?? '' }}"></i></div>
                            {{ $title ?? '' }}
                        </h1>
                    </div>
                    <div class="col-12 col-md-auto mb-3">
                        <a class="btn btn-sm btn-light text-primary" href="{{ request()->fullUrl() }}" role="button">
                            <i class="fa-solid fa-arrows-rotate me-1"></i>
                            Segarkan
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Konten Halaman Utama -->
    <div class="container-xl px-4 mt-4">
        <div class="row g-4">
            <div class="col-xl-5">
                <div class="card">
                    <div class="card-body p-0 overflow-hidden position-relative">
                        <div class="image-overlay">
                            <div class="d-flex gap-3 p-4 flex-column flex-xl-row align-items-center z-1">
                                <img class="rounded-circle-image"
                                    src="{{ isset(Auth::user()->foto)
                                        ? (Auth::user()->foto
                                            ? asset('storage/usersProfile/' . Auth::user()->foto)
                                            : asset('assets/admin/img/user-placeholder.svg'))
                                        : asset('assets/admin/img/user-placeholder.svg') }}"
                                    alt="profile-image">
                                <div class="text-center text-xl-start">
                                    <h1 class="fw-bolder mb-2 text-white">{{ Auth::user()->name ?? '' }}</h1>
                                    @role('admin')
                                        <h2 class="text-lg text-white mb-0">{{ Auth::user()->email ?? '' }}</h2>
                                    @endrole
                                    @role('dosen')
                                        <h2 class="text-lg text-white mb-2">
                                            {{ Auth::user()->nip ?? '' }} -
                                            <span class="text-uppercase">{{ Auth::user()->dosen->jafa ?? '' }}</span>
                                        </h2>
                                        <h3 class="text-sm fw-300 text-white mb-0">
                                            {{ Auth::user()->dosen->jenis_kelamin ?? '' }} -
                                            {{ Auth::user()->dosen->umur ?? '' }} Tahun
                                        </h3>
                                    @endrole
                                </div>
                            </div>
                        </div>
                        @role('dosen')
                            <div class="p-4">
                                <div class="d-flex align-items-center mb-3">
                                    <h1 class="fw-bolder small text-uppercase mb-0">Detail Informasi Dosen</h1>
                                    <a href="#" class="btn-edit-dosen small">
                                        <i class="fa-solid fa-pen-to-square ms-1"></i>
                                        <span>Edit Informasi Dosen</span>
                                    </a>
                                </div>

                                <div id="biografiContainer" class="mb-3">
                                    <p class="text-biografi mb-0">
                                        {{ Auth::user()->dosen ? Str::limit(Auth::user()->dosen->biografi ?? '', 100) : '-' }}
                                    </p>
                                    @if (Auth::user()->dosen && strlen(Auth::user()->dosen->biografi ?? '') > 100)
                                        <a href="javascript:void(0)" class="text-primary toggle-biografi">
                                            Lihat Selengkapnya
                                        </a>
                                    @endif
                                </div>

                                <div class="mb-3">
                                    <h1 class="small fw-bolder mb-1">Pendidikan</h1>
                                    @if (Auth::user()->dosen && Auth::user()->dosen->pendidikans->count() > 0)
                                        <ul class="ul-pendidikan">
                                            @foreach (Auth::user()->dosen->pendidikans as $item)
                                                <li>{{ $item->pendidikan ?? '' }}</li>
                                            @endforeach
                                        </ul>
                                    @else
                                        -
                                    @endif
                                </div>

                                <div class="mb-3">
                                    <h1 class="small fw-bolder mb-2">Minat Penelitian</h1>
                                    <p class="mb-0">{{ Auth::user()->dosen->minat_penelitian ?? '-' }}</p>
                                </div>

                                <div class="mb-3">
                                    <h1 class="small fw-bolder mb-2">Bidang Kepakaran</h1>
                                    <div class="d-flex gap-1 flex-wrap">
                                        @if (Auth::user()->dosen && Auth::user()->dosen->bidangKepakarans->count() > 0)
                                            @foreach (Auth::user()->dosen->bidangKepakarans as $index => $item)
                                                <p class="mb-0">
                                                    {{ $item->bidang_kepakaran ?? '' }}
                                                    @if ($index < count(Auth::user()->dosen->bidangKepakarans) - 1)
                                                        <span class="comma">,</span>
                                                    @endif
                                                </p>
                                            @endforeach
                                        @else
                                            <p class="mb-0">-</p>
                                        @endif
                                    </div>
                                </div>

                                <div class="d-flex gap-2 flex-wrap border-top pt-3">
                                    <a href="{{ Auth::user()->dosen->link_scopus ?? '#' }}" class="btn btn-sm btn-primary"
                                        target="_blank">
                                        Profil Scopus
                                        <i class="fa-solid fa-arrow-up-right-from-square ms-1"></i>
                                    </a>
                                    <a href="{{ Auth::user()->dosen->link_sinta ?? '#' }}" class="btn btn-sm btn-primary"
                                        target="_blank">
                                        Profil Sinta
                                        <i class="fa-solid fa-arrow-up-right-from-square ms-1"></i>
                                    </a>
                                    <a href="{{ Auth::user()->dosen->link_gscholar ?? '#' }}" class="btn btn-sm btn-primary"
                                        target="_blank">
                                        Profil Google Scholar
                                        <i class="fa-solid fa-arrow-up-right-from-square ms-1"></i>
                                    </a>
                                </div>
                            </div>
                        @endrole
                    </div>
                </div>
            </div>
            <div class="col-xl-7">
                <div class="row gap-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">Informasi Akun</div>
                            <div class="card-body">
                                <form id="formUpdateInformasiAkun" method="POST"
                                    action="{{ route('informasiAkun.update') }}">
                                    @csrf
                                    @method('PUT')
                                    <div class="mb-3">
                                        <label class="small mb-1" for="nameField">
                                            Nama Lengkap
                                            <span class="text-danger">*</span>
                                        </label>
                                        <input class="form-control @error('name') is-invalid @enderror" name="name"
                                            id="nameField" type="text" placeholder="Masukkan nama lengkap anda"
                                            value="{{ old('name', Auth::user()->name ?? '') }}" />
                                        @error('name')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    @role('admin')
                                        <div class="mb-3">
                                            <label class="small mb-1" for="emailField">
                                                Email
                                                <span class="text-danger">*</span>
                                            </label>
                                            <input class="form-control @error('email') is-invalid @enderror" name="email"
                                                id="emailField" type="email" placeholder="Masukkan email anda"
                                                value="{{ old('email', Auth::user()->email ?? '') }}" />
                                            @error('email')
                                                <div class="text-danger small mt-1">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    @endrole
                                    @role('dosen')
                                        <div class="mb-3">
                                            <label class="small mb-1" for="nipField">
                                                NIP
                                                <span class="text-danger">*</span>
                                            </label>
                                            <input class="form-control @error('nip') is-invalid @enderror" name="nip"
                                                id="nipField" type="text" placeholder="Masukkan nip anda"
                                                value="{{ old('nip', Auth::user()->nip ?? '') }}" />
                                            @error('nip')
                                                <div class="text-danger small mt-1">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    @endrole
                                    <button class="btn btn-light" type="reset">
                                        <i class="fa-solid fa-rotate-left me-1"></i>
                                        Atur Ulang
                                    </button>
                                    <button type="submit" class="btn btn-primary"
                                        onclick="return onSubmitFormUpdate('#formUpdateInformasiAkun', 'informasi akun')">
                                        <i class="fa-solid fa-floppy-disk me-1"></i>
                                        Perbarui
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">Setel Ulang Password</div>
                            <div class="card-body">
                                <form id="formSetelUlangPassword" action="{{ route('passwordAkun.update') }}"
                                    method="POST">
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
                                                placeholder="Masukkan password baru"
                                                value="{{ old('password_baru') }}" />
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
                                            <input
                                                class="form-control @error('konfirmasi_password_baru') is-invalid @enderror"
                                                name="konfirmasi_password_baru" id="konfirmasiPasswordBaruField"
                                                type="password" placeholder="Konfirmasi password baru"
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
                                    <button type="submit" class="btn btn-primary"
                                        onclick="return onSubmitFormUpdate('#formSetelUlangPassword', 'password akun')">
                                        <i class="fa-solid fa-floppy-disk me-1"></i>
                                        Perbarui
                                    </button>
                                </form>
                            </div>
                        </div>
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

            $(".toggle-biografi").click(function() {
                const biografiContainer = $(this).closest("#biografiContainer");
                const biografiText = biografiContainer.find(".text-biografi");
                const fullBiografi = "{{ Auth::user()->dosen->biografi ?? '' }}";

                if ($(this).hasClass("expanded")) {
                    // Jika tombol sudah diperluas, tampilkan teks singkat
                    biografiText.text(fullBiografi.substring(0, 100) + "...");
                    $(this).removeClass("expanded").text("Lihat Selengkapnya");
                } else {
                    // Jika tombol belum diperluas, tampilkan teks penuh
                    biografiText.html(html_entity_decode(fullBiografi));
                    $(this).addClass("expanded").text("Sembunyikan");
                }
            });

            // Fungsi untuk mendekode HTML entities pada JavaScript
            function html_entity_decode(encodedString) {
                var textarea = document.createElement("textarea");
                textarea.innerHTML = encodedString;
                return textarea.value;
            }

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

        // fungsi sweet alert jika ingin memperbarui data
        function onSubmitFormUpdate(formId, text) {
            Swal.fire({
                icon: 'question',
                title: 'Perhatian',
                html: `Apakah anda yakin ingin memperbarui ${text}? Jika tidak, klik tombol <b>Batal</b>!`,
                showCancelButton: true,
                confirmButtonColor: '#00ac69',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yakin',
                cancelButtonText: 'Batal',
            }).then((result) => {
                if (result.isConfirmed) {
                    $(formId).submit();
                }
            });

            return false;
        }
    </script>
@endpush
