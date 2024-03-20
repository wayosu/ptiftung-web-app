@extends('admin.layouts.app')

@push('css')
    <link href="{{ asset('assets/admin/libs/sweetalert2/css/sweetalert2.min.css') }}" rel="stylesheet" />
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
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <form id="formUpdatePengaturanSistem" action="{{ route('pengaturanSistem.update') }}"
                            method="POST">
                            @csrf
                            @method('PUT')
                            <div class="mb-3">
                                <label class="small mb-1" for="namaProgramStudiField">
                                    Nama Program Studi
                                </label>
                                <input type="text" id="namaProgramStudiField" name="nama_program_studi"
                                    class="form-control @error('nama_program_studi') is-invalid @enderror"
                                    value="{{ old('nama_program_studi', $namaProgramStudi ?? '') }}" placeholder="...">
                                @error('nama_program_studi')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label class="small mb-1" for="namaDasborField">
                                    Nama Dasbor
                                </label>
                                <input type="text" id="namaDasborField" name="nama_dasbor"
                                    class="form-control @error('nama_dasbor') is-invalid @enderror"
                                    value="{{ old('nama_dasbor', $namaDasbor ?? '') }}" placeholder="...">
                                @error('nama_dasbor')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <button class="btn btn-light" type="reset">
                                <i class="fa-solid fa-rotate-left me-1"></i>
                                Atur Ulang
                            </button>
                            <button type="submit" class="btn btn-primary"
                                onclick="return onSubmitFormUpdate('#formUpdatePengaturanSistem', 'pengaturan sistem')">
                                <i class="fa-solid fa-floppy-disk me-1"></i>
                                Perbarui
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row align-items-center gap-2">
                            <div class="col order-2 order-lg-1">
                                <form id="formUpdateLogo" action="{{ route('pengaturanSistem.updateLogo') }}" method="POST"
                                    enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')
                                    <div class="mb-3">
                                        <label class="small mb-1" for="logoField">
                                            Logo
                                        </label>
                                        <input type="file" id="logoField" name="logo"
                                            class="form-control @error('logo') is-invalid @enderror"
                                            value="{{ old('logo', $logo ?? '') }}">
                                        @error('logo')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <button class="btn btn-light" type="reset">
                                        <i class="fa-solid fa-rotate-left me-1"></i>
                                        Atur Ulang
                                    </button>
                                    <button type="submit" class="btn btn-primary"
                                        onclick="return onSubmitFormUpdate('#formUpdateLogo', 'logo')">
                                        <i class="fa-solid fa-floppy-disk me-1"></i>
                                        Perbarui
                                    </button>
                                </form>
                            </div>
                            @if (!empty($logo))
                                <div class="col-lg-6 order-1 order-lg-2 my-3 my-lg-0">
                                    <div class="text-center">
                                        <img src="{{ asset('storage/profilProgramStudi/' . $logo) }}" alt="Logo"
                                            class="img-fluid">
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex flex-column gap-2 mb-3">
                            <p class="small text-danger mb-0">
                                <b>Peringatan :</b>
                            </p>
                            <p class="small text-muted mb-0">
                                Saat Anda menekan tombol <b>"Setel ke Pengaturan Pabrik"</b>, semua data akan dihapus
                                dari sistem secara permanen.
                                Tindakan ini tidak dapat dibatalkan, dan setelah dihapus, data tidak dapat dikembalikan.
                            </p>
                            <p class="small text-danger mb-0">
                                <b>Pastikan Anda telah mencadangkan data yang diperlukan sebelum melanjutkan.</b>
                            </p>
                        </div>
                        <form id="formResetToFactory" action="{{ route('pengaturanSistem.resetToFactory') }}"
                            method="POST">
                            @csrf
                            <button type="submit" class="btn btn-outline-danger w-100"
                                onclick="return onSubmitFormResetToFactory()">
                                <i class="fa-solid fa-rotate-left me-1"></i>
                                Setel ke Pengaturan Pabrik
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
        });

        // fungsi sweet alert jika ingin memperbarui pengaturan sistem
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

        // fungsi sweet alert jika ingin setel ke pengaturan pabrik
        function onSubmitFormResetToFactory() {
            Swal.fire({
                icon: 'warning',
                title: 'Perhatian',
                html: 'Apakah anda yakin ingin melakukan setel ke pengaturan pabrik? Jika yakin, silahkan ketik "<b>Setel-Pengaturan-Pabrik</b>" di bawah ini. Jika tidak, klik tombol <b>Batal</b>.',
                showCancelButton: true,
                confirmButtonColor: '#e81500',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Setel ke Pengaturan Pabrik',
                cancelButtonText: 'Batal',
                input: 'text',
                inputAttributes: {
                    autocomplete: 'off', // Disable autocomplete
                },
                inputValidator: (value) => {
                    const trimmedValue = value.trim();

                    if (!trimmedValue) {
                        return 'Mohon diisi dengan benar!'
                    } else if (trimmedValue.toLowerCase() === 'setel-pengaturan-pabrik') {
                        $('#formResetToFactory').submit();
                    } else {
                        return 'Mohon diisi dengan benar!';
                    }
                },
            });

            return false;
        }
    </script>
@endpush
