@extends('admin.layouts.app')

@push('css')
    <link href="{{ asset('assets/admin/libs/datatables/css/dataTables.bootstrap5.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/admin/libs/datatables/css/responsive.bootstrap5.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/admin/libs/sweetalert2/css/sweetalert2.min.css') }}" rel="stylesheet" />

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
                        <a class="btn btn-sm btn-light text-primary" href="{{ route('kurikulum.create') }}">
                            <i class="fa-solid fa-plus me-1"></i>
                            Tambah Kurikulum
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Konten Halaman Utama -->
    <div class="container-fluid px-4">
        <div class="card">
            <div class="card-body overflow-hidden">
                <table id="myDataTables" class="table table-bordered dt-responsive wrap" style="width: 100%;">
                    <thead>
                        <tr>
                            <th>Kode</th>
                            <th>Mata Kuliah</th>
                            <th>SKS</th>
                            <th>Sifat</th>
                            <th>Semester</th>
                            <th>Prasyarat</th>
                            <th>Tuatan Dokumen</th>
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

    <script>
        $(document).ready(function() {
            // inisialisasi datatables
            $('#myDataTables').DataTable({
                responsive: true,
                order: [
                    [4, 'desc']
                ],
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.1/i18n/id.json'
                },
                processing: true,
                serverSide: true,
                ajax: "{{ route('kurikulum.index') }}",
                columns: [{
                        data: 'kode_mk'
                    },
                    {
                        data: 'nama_mk'
                    },
                    {
                        data: 'sks'
                    },
                    {
                        data: 'sifat'
                    },
                    {
                        data: 'semester'
                    },
                    {
                        data: 'prasyarat',
                        render: function(data) {
                            if (data) {
                                return data;
                            } else {
                                return '-';
                            }
                        }
                    },
                    {
                        data: 'link_gdrive',
                        render: function(data) {
                            if (data) {
                                return '<a href="' + data +
                                    '" target="_blank" class="btn btn-sm btn-primary"><i class="fa-solid fa-file-lines me-1"></i> Lihat Dokumen</a>';
                            } else {
                                return '-';
                            }
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
                const deleteUrl = "{{ route('kurikulum.destroy', ':id') }}";
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