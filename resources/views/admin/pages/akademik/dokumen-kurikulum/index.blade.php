@extends('admin.layouts.app')

@push('css')
    <link href="{{ asset('assets/admin/libs/sweetalert2/css/sweetalert2.min.css') }}" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap5-toggle@5.0.4/css/bootstrap5-toggle.min.css" rel="stylesheet">

    <style>
        .text-justify {
            text-align: justify;
        }

        .textarea-deskripsi {
            line-height: 1.5 !important;
        }

        .toggle-group>.btn-xs {
            padding: 0.25rem !important;
        }
    </style>
@endpush

@section('content')
    <!-- Konten Header -->
    <header class="page-header page-header-compact page-header-light border-bottom bg-white mb-4">
        <div class="container-fluid px-4">
            <div class="page-header-content">
                <div class="row align-items-center justify-content-between pt-3">
                    <div class="col-auto mb-3">
                        <h1 class="page-header-title">
                            <div class="page-header-icon"><i class="{{ $icon ?? '' }}"></i></div>
                            {{ $title ?? '' }}
                        </h1>
                        <p class="mb-0 small mt-1">
                            {{ $subtitle ?? '' }}
                        </p>
                    </div>
                    <div class="col-12 col-xl-auto mb-3">
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
    <div class="container-fluid px-4">
        <div class="row g-4">
            <div class="col-xl-4">
                <div class="card">
                    <div class="card-header">Form {{ $title ?? '' }}</div>
                    <div class="card-body">
                        <form action="{{ route('dokumenKurikulum.store') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="small mb-1" for="keteranganField">
                                    Keterangan
                                    <span class="text-danger">*</span>
                                </label>
                                <textarea name="keterangan" id="keteranganField" rows="5"
                                    class="form-control textarea-deskripsi @error('keterangan') is-invalid @enderror" spellcheck="false"
                                    placeholder="Masukkan keterangan"></textarea>
                                @error('keterangan')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label class="small mb-1" for="tautanField">
                                    Dokumen
                                    <span class="text-danger">
                                        *<small>Tautan Google Drive</small>
                                    </span>
                                </label>
                                <input type="text" name="link_gdrive" id="tautanField"
                                    class="form-control @error('link_gdrive') is-invalid @enderror"
                                    placeholder="Masukkan dokumen menggunakan tautan google drive">
                                @error('link_gdrive')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <button class="btn btn-light" type="reset">
                                <i class="fa-solid fa-rotate-left me-1"></i>
                                Atur Ulang
                            </button>
                            <button class="btn btn-primary" type="submit">
                                <i class="fa-solid fa-floppy-disk me-1"></i>
                                Simpan
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-xl-8">
                <div class="card">
                    <div class="card-body overflow-hidden">
                        @if (count($dokumenKurikulums) > 0)
                            <div class="d-flex flex-column gap-3 w-100">
                                @foreach ($dokumenKurikulums as $dokumenKurikulum)
                                    <div
                                        class="d-flex flex-column flex-lg-row gap-4 align-items-start align-items-lg-center justify-content-between w-100 border border-2 border-muted rounded-2 p-3">
                                        <div class="w-auto">
                                            <div class="d-flex gap-3 align-items-center">
                                                <span class="text-xs text-muted">
                                                    <i class="fa-solid fa-calendar fa-xs"></i>
                                                    {{ \Carbon\Carbon::parse($dokumenKurikulum->created_at)->isoFormat('dddd, D MMMM Y H:mm') }}</span>
                                                <span class="text-xs text-muted">
                                                    <i class="fa-solid fa-user fa-xs"></i>
                                                    {{ $dokumenKurikulum->createdBy->name }}
                                                </span>
                                            </div>
                                            <h6 class="fw-bolder mt-2 mb-1">Keterangan</h6>
                                            <p class="text-justify mb-0">
                                                {{ $dokumenKurikulum->keterangan }}
                                            </p>
                                        </div>
                                        <div class="w-auto">
                                            <div class="d-flex gap-2 align-items-center">
                                                <input type="checkbox" {{ $dokumenKurikulum->active ? 'checked' : '' }}
                                                    data-toggle="toggle" data-onlabel="Aktif" data-offlabel="Tidak Aktif"
                                                    data-onstyle="success" data-offstyle="danger" data-size="xs"
                                                    data-id="{{ $dokumenKurikulum->id }}" class="toggle-status">
                                                <a href="{{ $dokumenKurikulum->link_gdrive }}"
                                                    class="btn btn-datatable btn-icon btn-transparent-dark text-primary"
                                                    target="_blank">
                                                    <i class="fa-solid fa-file-lines fa-xl"></i>
                                                </a>
                                                <a href="javascript:void(0)" title="Hapus"
                                                    class="btn btn-datatable btn-icon btn-transparent-dark text-danger tombol-hapus"
                                                    data-id="{{ $dokumenKurikulum->id }}">
                                                    <i class="fa-solid fa-trash fa-xl"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                                <form id="deleteForm" class="d-none" method="POST">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </div>
                        @else
                            <div class="p-3 border border-2 border-muted rounded-2">
                                <h6 class="text-center text-muted mb-0">
                                    <i class="fa-solid fa-triangle-exclamation fa-sm"></i>
                                    Tidak ada data yang ditemukan. Silahkan tambahkan data dokumen.
                                </h6>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script src="{{ asset('assets/admin/libs/jquery/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('assets/admin/libs/sweetalert2/js/sweetalert2.all.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap5-toggle@5.0.4/js/bootstrap5-toggle.jquery.min.js"></script>

    <script>
        $(document).ready(function() {
            $('input[data-toggle="toggle"]').bootstrapToggle();

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

            // menangani status toggle
            $('.toggle-status').change(function() {
                var id = $(this).data('id');
                var status = $(this).prop('checked') ? 1 : 0;

                // Cek apakah toggle sedang diaktifkan atau dinonaktifkan
                if (status == 1) {
                    // Jika toggle sedang diaktifkan
                    // Nonaktifkan toggle lainnya
                    $('.toggle-status').not(this).each(function() {
                        if ($(this).prop('checked')) {
                            $(this).bootstrapToggle('off');
                        }
                    });
                }

                // Kirim request AJAX untuk memperbarui status di backend
                $.ajax({
                    url: "{{ route('dokumenKurikulum.updateStatus') }}", // Ganti dengan URL rute yang sesuai
                    method: 'POST',
                    data: {
                        id: id,
                        status: status,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        // Handle respons dari backend (jika diperlukan)
                        // console.log(response);

                        // Nonaktifkan toggle lainnya
                        // Panggil fungsi untuk menangani respons success
                        handleSuccess(response);
                    },
                    error: function(xhr, status, error) {
                        // Handle error (jika diperlukan)
                        // console.error(xhr.responseText);

                        // Panggil fungsi untuk menangani respons error
                        handleError(xhr);
                    }
                });
            });

            // Fungsi untuk menangani respons success
            function handleSuccess(response) {
                if (response.status == 1) {
                    // Tampilkan pesan sukses
                    // console.log("Status di aktifkan: " + response.message);
                    Toast.fire({
                        icon: 'success',
                        title: 'Berhasil Diaktifkan',
                    })
                }
                // else {
                //     // Tampilkan pesan gagal
                //     console.log("Status di nonaktifkan: " + response.message);
                // }
            }

            // Fungsi untuk menangani respons error
            function handleError(xhr) {
                // Tampilkan pesan error
                // console.error(xhr.responseText);

                // Contoh: Tampilkan pesan menggunakan library Swal (SweetAlert)
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Terjadi kesalahan saat memperbarui status!'
                });
            }

            // konfirmasi hapus dengan swal
            $('body').on('click', '.tombol-hapus', function(e) {
                e.preventDefault();

                // mengekstrak URL hapus dari formulir
                const dataId = $(this).data('id');
                const deleteUrl = "{{ route('dokumenKurikulum.destroy', ':id') }}";
                const newDeleteUrl = deleteUrl.replace(':id', dataId);

                Swal.fire({
                    title: 'Apakah anda yakin?',
                    html: `Jika anda yakin, silahkan ketik "<b>Hapus Data</b>" di bawah ini. Jika tidak, klik tombol <b>Batal</b>`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Hapus Data',
                    cancelButtonText: 'Batal',
                    input: 'text',
                    inputAttributes: {
                        autocomplete: 'off', // menonaktifkan pelengkapan otomatis
                    },
                    inputValidator: (value) => {
                        const trimmedValue = value.trim();

                        if (!trimmedValue) {
                            return 'Mohon diisi dengan benar!'
                        } else if (trimmedValue.toLowerCase() === 'hapus data') {
                            $('#deleteForm').attr('action', newDeleteUrl).submit();
                        } else {
                            return 'Mohon diisi dengan benar!';
                        }
                    },
                });
            });
        });
    </script>
@endpush
