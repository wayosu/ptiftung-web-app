@extends('admin.layouts.app')

@push('css')
    <link href="{{ asset('assets/admin/libs/datatables/css/dataTables.bootstrap5.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/admin/libs/datatables/css/responsive.bootstrap5.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/admin/libs/sweetalert2/css/sweetalert2.min.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/css/lightbox.min.css"
        integrity="sha512-ZKX+BvQihRJPA8CROKBhDNvoc2aDMOdAlcm7TUQY+35XYtrd3yh95QOOhsPDQY9QnKE0Wqag9y38OIgEvb88cA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <style>
        #myDataTables {

            td,
            th {
                vertical-align: middle;
            }
        }

        .dataTables_scroll {
            overflow: auto;
        }

        div.dataTables_wrapper div.row:nth-child(1) {
            align-items: center;
            margin-bottom: .5rem;
        }

        div.dataTables_wrapper div.row:nth-child(3) {
            align-items: center;
            margin-top: 1rem;
        }

        div.dataTables_info {
            padding-top: 0 !important;
        }

        table.dataTable.dtr-inline.collapsed>tbody>tr>td.dtr-control {
            display: flex;
            align-items: center;
        }

        .rounded-circle-image {
            width: 150px;
            height: 150px;
            object-fit: cover;
            border-radius: 50%;
        }

        .btn-hapus-banner {
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

        .btn-img-banner img {
            transition: opacity 0.2s ease;
        }

        .btn-img-banner:hover img {
            opacity: 0.5;
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
                            <div class="page-header-icon"><i data-feather="{{ $icon ?? '' }}"></i></div>
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
        <div class="card mb-4">
            <div class="card-body">
                <form action="{{ route('jurnal.store') }}" method="POST" class="row g-4 align-items-center">
                    @csrf
                    <div class="col-12">
                        <div class="mb-3">
                            <label class="small mb-1" for="judulJurnalField">
                                Judul Jurnal
                                <span class="text-danger">*</span>
                            </label>
                            <input class="form-control @error('judul_jurnal') is-invalid @enderror" name="judul_jurnal"
                                id="judulJurnalField" type="text" placeholder="Masukkan judul jurnal"
                                value="{{ old('judul_jurnal', $agenda->judul_jurnal ?? '') }}" />
                            @error('judul_jurnal')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-0">
                            <label class="small mb-1" for="linkJurnalField">
                                Link Jurnal
                                <span class="text-danger">*</span>
                            </label>
                            <input class="form-control @error('link_jurnal') is-invalid @enderror" name="link_jurnal"
                                id="linkJurnalField" type="text" placeholder="Masukkan link jurnal"
                                value="{{ old('link_jurnal', $agenda->link_jurnal ?? '') }}" />
                            @error('link_jurnal')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fa-solid fa-plus me-1"></i>
                            Tambah Jurnal
                        </button>
                    </div>
                </form>
            </div>
        </div>
        <div class="card">
            <div class="card-body overflow-hidden">
                <table id="myDataTables" class="table table-bordered dt-responsive wrap" style="width: 100%;">
                    <thead>
                        <tr>
                            <th>Judul Jurnal</th>
                            <th>Link Jurnal</th>
                            <th>Tanggal Dibuat</th>
                            <th>Dibuat Oleh</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script src="{{ asset('assets/admin/libs/jquery/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('assets/admin/libs/datatables/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/admin/libs/datatables/js/dataTables.bootstrap5.min.js') }}"></script>
    <script src="{{ asset('assets/admin/libs/datatables/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('assets/admin/libs/datatables/js/responsive.bootstrap5.min.js') }}"></script>
    <script src="{{ asset('assets/admin/libs/sweetalert2/js/sweetalert2.all.min.js') }}"></script>
    <script src="{{ asset('assets/admin/libs/moment/moment.min.js') }}"></script>
    <script src="{{ asset('assets/admin/libs/moment/moment-with-locales.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/js/lightbox.min.js"
        integrity="sha512-k2GFCTbp9rQU412BStrcD/rlwv1PYec9SNrkbQlo6RZCf75l6KcC3UwDY8H5n5hl4v77IDtIPwOk9Dqjs/mMBQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        $(document).ready(function() {
            // inisialisasi datatables
            $('#myDataTables').DataTable({
                responsive: true,
                order: [
                    [3, 'desc']
                ],
                language: {
                    url: 'https://cdn.datatables.net/plug-ins/1.13.1/i18n/id.json'
                },
                processing: true,
                serverSide: true,
                ajax: "{{ route('jurnal.index') }}",
                columns: [{
                        data: 'judul_jurnal',
                    },
                    {
                        data: 'link_jurnal',
                        render: function(data) {
                            return `<a href="${data}" target="_blank">${data}</a>`
                        }
                    },
                    {
                        data: 'created_at',
                        render: function(data) {
                            // with locale 'id'
                            return moment(data).locale('id').format('dddd, D MMMM YYYY HH:mm') +
                                ' WITA';
                        }
                    },
                    {
                        data: 'created_by.name',
                    },
                    {
                        data: 'aksi',
                        orderable: false,
                        searchable: false
                    },
                ]
            });

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

            // konfirmasi hapus dengan swal
            $('body').on('click', '.tombol-hapus', function(e) {
                e.preventDefault();

                // mengekstrak URL hapus dari formulir
                const dataId = $(this).data('id');
                const deleteUrl = "{{ route('jurnal.destroy', ':id') }}";
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
