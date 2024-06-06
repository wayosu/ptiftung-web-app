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
    <!-- Header content-->
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
                    @role('Mahasiswa')
                        @if (isset($kegiatan))
                            <div class="col-12 col-xl-auto mb-3">
                                <a id="btnSegarkanDatatables" class="btn btn-sm btn-light text-primary" href="javascript:void(0)" role="button">
                                    <i class="fa-solid fa-arrows-rotate me-1"></i>
                                    Segarkan
                                </a>
                            </div>
                        @endif
                    @else
                        <div class="col-12 col-xl-auto mb-3">
                            <a id="btnSegarkanDatatables" class="btn btn-sm btn-light text-primary" href="javascript:void(0)" role="button">
                                <i class="fa-solid fa-arrows-rotate me-1"></i>
                                Segarkan
                            </a>
                        </div>
                    @endrole
                </div>
            </div>
        </div>
    </header>

    <!-- Main page content-->
    <div class="container-fluid px-4">
        @role('Mahasiswa')
            @if (isset($kegiatan))
                <div class="row g-4">
                    <div class="col-12 col-xl-4">
                        <form id="thisForm" action="{{ route('lampiranKegiatan.store', $kegiatan->id) }}" method="POST" class="card" enctype="multipart/form-data">
                            @csrf
                            <div class="card-header">Form Tambah Lampiran Kegiatan</div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label class="small mb-1">
                                        Dosen Pembimbing Lapangan
                                    </label>
                                    <p class="fw-bolder">{{ $kegiatan->dosen->user->name }}</p>
                                </div>
                                <div class="mb-3">
                                    <label class="small mb-1" for="keteranganLampiranField">
                                        Keterangan Lampiran
                                        <span class="text-danger">*</span>
                                    </label>
                                    <input class="form-control @error('keterangan_lampiran') is-invalid @enderror" name="keterangan_lampiran" id="keteranganLampiranField"
                                        type="text" placeholder="Masukkan keterangan lampiran"
                                        value="{{ old('keterangan_lampiran') }}" />
                                    @error('keterangan_lampiran')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label class="small mb-1" for="fileLampiranField">
                                        File Lampiran
                                        <span class="text-danger">*</span>
                                    </label>
                                    <input class="form-control @error('file_lampiran.*') is-invalid @enderror" name="file_lampiran[]" id="fileLampiranField" type="file" multiple />
                                    @error('file_lampiran.*')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                                <button class="btn btn-light" type="reset" id="resetButton">
                                    <i class="fa-solid fa-rotate-left me-1"></i>
                                    Atur Ulang
                                </button>
                                <button class="btn btn-primary" type="submit" onclick="return onSubmitForm()">
                                    <i class="fa-solid fa-plus-circle me-1"></i>
                                    Tambah Lampiran
                                </button>
                            </div>
                        </form>
                    </div>
                    <div class="col-12 col-xl-8">
                        <div class="card">
                            <div class="card-body overflow-hidden">
                                <table id="myDataTables" class="table table-bordered dt-responsive wrap" style="width: 100%;">
                                    <thead>
                                        <tr>
                                            <th>Keterangan Lampiran</th>
                                            <th>Status</th>
                                            <th>Catatan Dosen</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal -->
                <div class="modal fade" id="catatanModal" tabindex="-1" aria-labelledby="catatanModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="catatanModalLabel">Catatan Dosen</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div id="modalCatatanContent"></div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup Catatan</button>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div class="card shadow-none">
                    <div class="card-body text-center">
                        <div class="d-flex flex-column gap-3 align-items-center">
                            <i class="fa-solid fa-triangle-exclamation fa-3x text-warning"></i>
                            <div>
                                <h5 class="mb-1 fw-bolder text-capitalize">Tidak ada data</h5>
                                <p class="mb-0 small">Anda belum terdaftar pada {{ $title ?? '' }}. Silahkan hubungi Kaprodi.</p>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        @endrole

        @role('Superadmin|Admin|Kajur|Kaprodi|Dosen')
            <div class="row g-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body overflow-hidden">
                            <table id="myDataTables" class="table table-bordered dt-responsive wrap" style="width: 100%;">
                                <thead>
                                    <tr>
                                        <th>NIM</th>
                                        <th>Mahasiswa</th>
                                        <th>Angkatan</th>
                                        @role('Superadmin|Kajur|Kaprodi')
                                            <th>Dosen Pembimbing Lapangan</th>
                                        @endrole
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        @endrole
    </div>
@endsection

@push('js')
    <script src="{{ asset('assets/admin/libs/jquery/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('assets/admin/libs/datatables/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/admin/libs/datatables/js/dataTables.bootstrap5.min.js') }}"></script>
    <script src="{{ asset('assets/admin/libs/datatables/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('assets/admin/libs/datatables/js/responsive.bootstrap5.min.js') }}"></script>
    <script src="{{ asset('assets/admin/libs/sweetalert2/js/sweetalert2.all.min.js') }}"></script>

    <script>
        $(document).ready(function() {
            @role('Mahasiswa')
                const dataTablesUrl = "{{ route('lampiranKegiatan.dataTables', $kegiatan->id) }}";
                // inisialisasi datatables
                $('#myDataTables').DataTable({
                    responsive: true,
                    order: [
                        [0, 'asc'],
                    ],
                    language: {
                        url: 'https://cdn.datatables.net/plug-ins/1.13.1/i18n/id.json'
                    },
                    processing: true,
                    serverSide: true,
                    ajax: dataTablesUrl,
                    columns: [
                        { data: 'keterangan_lampiran', },
                        { 
                            data: 'status', 
                            render: function(data) {
                                if(data === 'belum direview') {
                                    return '<span class="badge bg-warning">Belum Direview</span>';
                                } else if(data === 'disetujui') {
                                    return '<span class="badge bg-success">Disetujui</span>';
                                } else if (data === 'ditolak') {
                                    return '<span class="badge bg-danger">Ditolak</span>';
                                }
                            }
                        },
                        { 
                            data: 'catatan_dosen', 
                            render: function(data, type, row) {
                                const escape = (data) => {
                                    return data.replace(/&nbsp;/g, " ").replace(/\n/g, "<br />");
                                }
                                
                                if(data) {
                                    return `<button type="button" class="btn btn-sm btn-light view-catatan" data-catatan="${escape(data)}" data-bs-toggle="modal" data-bs-target="#catatanModal"><i class="fa-solid fa-pen fa-sm me-1"></i> Lihat Catatan</button>`;
                                } else {
                                    return '-';
                                }
                            }
                        },
                        {
                            data: 'aksi',
                            orderable: false,
                            searchable: false
                        },
                    ]
                });

                $(document).on('click', '.view-catatan', function() {
                    var catatan = unescape($(this).data('catatan'));
                    $('#modalCatatanContent').html(catatan);  // Use .html() to render HTML content
                    $('#catatanModal').modal('show');
                });
            @endrole

            @role('Superadmin|Admin|Kajur|Kaprodi|Dosen')
                const dataTablesUrl = "{{ route('lampiranKegiatan.getDataTables', $active) }}";
                // inisialisasi datatables
                $('#myDataTables').DataTable({
                    responsive: true,
                    order: [
                        [2, 'desc'],
                        [0, 'asc'],
                    ],
                    language: {
                        url: 'https://cdn.datatables.net/plug-ins/1.13.1/i18n/id.json'
                    },
                    processing: true,
                    serverSide: true,
                    ajax: dataTablesUrl,
                    columns: [
                        { data: 'mahasiswa.nim', },
                        { data: 'mahasiswa.user.name', },
                        { data: 'mahasiswa.angkatan', },
                        @role('Superadmin|Kajur|Kaprodi')
                            { data: 'dosen.user.name', },
                        @endrole
                        {
                            data: 'aksi',
                            orderable: false,
                            searchable: false
                        },
                    ]
                });
            @endrole

            

            // refresh datatables on click #btnSegarkanDatatables
            $('#btnSegarkanDatatables').on('click', function() {
                $('#myDataTables').DataTable().ajax.reload();
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

            $('body').on('click', '.tombol-detail', function(e) {
                e.preventDefault();

                // mengekstrak URL 'detail' dari formulir
                const dataId = $(this).data('id');
                const detailUrl = "{{ route('lampiranKegiatan.detail', ':id') }}";
                const newDetailUrl = detailUrl.replace(':id', dataId);
                window.location.href = newDetailUrl;
            });

            $('body').on('click', '.tombol-detail-lampiran', function(e) {
                const dataId = $(this).data('id');
                const detailUrl = "{{ route('lampiranKegiatan.review', ':id') }}";
                const newDetailUrl = detailUrl.replace(':id', dataId);
                window.location.href = newDetailUrl;
            });

            $('body').on('click', '.tombol-review', function(e) {
                e.preventDefault();

                // mengekstrak URL 'review' dari formulir
                const dataId = $(this).data('id');
                const reviewUrl = "{{ route('lampiranKegiatan.review', ':id') }}";
                const newReviewUrl = reviewUrl.replace(':id', dataId);

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
                        window.location.href = newReviewUrl;
                    }
                });
            });

            // konfirmasi tombol hapus menggunakan swal
            $('body').on('click', '.tombol-hapus', function(e) {
                e.preventDefault();

                // mengekstrak URL 'hapus' dari formulir
                const dataId = $(this).data('id');
                const deleteUrl = "{{ route('lampiranKegiatan.destroy', ':id') }}";
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

                        // memastikan inputan tidak kosong
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

        function onSubmitForm() {
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
