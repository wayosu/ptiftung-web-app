@extends('admin.layouts.app')

@push('css')
    <link href="{{ asset('assets/admin/libs/sweetalert2/css/sweetalert2.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/admin/libs/datatables/css/dataTables.bootstrap5.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/admin/libs/datatables/css/responsive.bootstrap5.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/admin/libs/datatables/css/buttons.bootstrap5.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/admin/libs/datatables/css/buttons.dataTables.min.css') }}" rel="stylesheet" />

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
                        <a id="btnSegarkanDatatables" class="btn btn-sm btn-light text-primary" href="javascript:void(0)" role="button">
                            <i class="fa-solid fa-arrows-rotate me-1"></i>
                            Segarkan
                        </a>
                        @role('Superadmin|Admin|Kajur|Kaprodi')
                            <a class="btn btn-sm btn-light text-primary" href="{{ route('dokumenLainnya.create') }}">
                                <i class="fa-solid fa-plus me-1"></i>
                                Tambah Dokumen Lainnya
                            </a>
                        @endrole
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Konten Halaman Utama -->
    <div class="container-fluid px-4">
        <div class="card">
            <div class="card-body overflow-hidden">
                <div class="table-responsive">
                    <table id="myDataTables" class="table table-bordered dt-responsive nowrap" style="width: 100%;">
                        <thead>
                            <tr>
                                @role('Superadmin|Admin|Kajur')
                                    <th>Program Studi</th>
                                @endrole
                                <th>Keterangan</th>
                                <th>Dokumen</th>
                                @role('Superadmin|Admin|Kajur|Kaprodi')
                                    <th>Aksi</th>
                                @endrole
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
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
    <script src="{{ asset('assets/admin/libs/datatables/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('assets/admin/libs/datatables/js/buttons.bootstrap5.js') }}"></script>
    <script src="{{ asset('assets/admin/libs/datatables/js/jszip.min.js') }}"></script>
    <script src="{{ asset('assets/admin/libs/datatables/js/pdfmake.min.js') }}"></script>
    <script src="{{ asset('assets/admin/libs/datatables/js/vfs_fonts.js') }}"></script>
    <script src="{{ asset('assets/admin/libs/datatables/js/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('assets/admin/libs/datatables/js/buttons.print.min.js') }}"></script>
    <script src="{{ asset('assets/admin/libs/sweetalert2/js/sweetalert2.all.min.js') }}"></script>

    <script>
        $(document).ready(function() {
            // inisialisasi datatables
            @role('Superadmin|Admin|Kajur')
                const dataArrayForDataTables = [0, 1, 2];
            @endrole
            @role('Kaprodi|Dosen')
                const dataArrayForDataTables = [0, 1];
            @endif
            $('#myDataTables').DataTable({
                responsive: true,
                order: [
                    [0, 'asc']
                ],
                language: {
                    url: 'https://cdn.datatables.net/plug-ins/1.13.1/i18n/id.json'
                },
                processing: true,
                serverSide: true,
                ajax: "{{ route('dokumenLainnya.index') }}",
                columns: [
                    @role('Superadmin|Admin|Kajur')
                    { data: 'program_studi' },
                    @endrole
                    { data: 'keterangan', },
                    {
                        data: 'dokumen',
                        orderable: false,
                        render: function(data, type, row) {
                            if (row.dokumen) {
                                return '<a href="/storage/repositori/dokumen-lainnya/' + row
                                    .dokumen +
                                    '" target="_blank" class="btn btn-sm btn-primary"><i class="fa-solid fa-arrow-up-right-from-square me-1"></i> Lihat Dokumen</a>';
                            } else if (row.link_dokumen) {
                                return '<a href="' + row.link_dokumen +
                                    '" target="_blank" class="btn btn-sm btn-primary"><i class="fa-solid fa-arrow-up-right-from-square me-1"></i> Lihat Dokumen</a>';
                            } else {
                                return '-';
                            }
                        }
                    },
                    @role('Superadmin|Admin|Kajur|Kaprodi')
                    {
                        data: 'aksi',
                        orderable: false,
                        searchable: false
                    },
                    @endrole
                ],
                dom: '<"d-flex flex-wrap justify-content-between align-items-center gap-1 gap-md-2"B<"d-flex flex-wrap align-items-center justify-content-center gap-2 gap-md-3"fl>>rtip',
                buttons: [
                    { 
                        extend: 'copy', 
                        className: 'btn btn-sm btn-dark border',
                        exportOptions: {
                            columns: dataArrayForDataTables,
                            format: {
                                body: function(data, row, column, node) {
                                    // Jika kolom adalah kolom "dokumen"
                                    @role('Superadmin|Admin|Kajur')
                                        if (column === 2) {
                                            // ambil isi href dari kolom "dokumen"
                                            const href = $(node).find('a').attr('href');
                                            if (href) {
                                                return href;
                                            } else {
                                                return '-';
                                            }
                                        } else {
                                            return data;
                                        }
                                    @endrole
                                    @role('Kaprodi|Dosen')
                                        if (column === 2) {
                                            // ambil isi href dari kolom "dokumen"
                                            const href = $(node).find('a').attr('href');
                                            if (href) {
                                                return href;
                                            } else {
                                                return '-';
                                            }
                                        } else {
                                            return data;
                                        }
                                    @endrole
                                },
                            },
                        }
                    },
                    { 
                        extend: 'csv',
                        title: 'Dokumen Lainnya',
                        filename: 'dokumen-lainnya-' + new Date().toISOString().slice(0, 10),
                        className: 'btn btn-sm btn-dark border',
                        exportOptions: {
                            columns: dataArrayForDataTables,
                            format: {
                                body: function(data, row, column, node) {
                                    // Jika kolom adalah kolom "dokumen"
                                    @role('Superadmin|Admin|Kajur')
                                        if (column === 2) {
                                            // ambil isi href dari kolom "dokumen"
                                            const href = $(node).find('a').attr('href');
                                            if (href) {
                                                return href;
                                            } else {
                                                return '-';
                                            }
                                        } else {
                                            return data;
                                        }
                                    @endrole
                                    @role('Kaprodi|Dosen')
                                        if (column === 2) {
                                            // ambil isi href dari kolom "dokumen"
                                            const href = $(node).find('a').attr('href');
                                            if (href) {
                                                return href;
                                            } else {
                                                return '-';
                                            }
                                        } else {
                                            return data;
                                        }
                                    @endrole
                                },
                            },
                        }
                    },
                    { 
                        extend: 'excel',
                        title: 'Dokumen Lainnya',
                        filename: 'dokumen-lainnya-' + new Date().toISOString().slice(0, 10),
                        className: 'btn btn-sm btn-dark border',
                        exportOptions: {
                            columns: dataArrayForDataTables,
                            format: {
                                body: function(data, row, column, node) {
                                    // Jika kolom adalah kolom "dokumen"
                                    @role('Superadmin|Admin|Kajur')
                                        if (column === 2) {
                                            // ambil isi href dari kolom "dokumen"
                                            const href = $(node).find('a').attr('href');
                                            if (href) {
                                                return href;
                                            } else {
                                                return '-';
                                            }
                                        } else {
                                            return data;
                                        }
                                    @endrole
                                    @role('Kaprodi|Dosen')
                                        if (column === 2) {
                                            // ambil isi href dari kolom "dokumen"
                                            const href = $(node).find('a').attr('href');
                                            if (href) {
                                                return href;
                                            } else {
                                                return '-';
                                            }
                                        } else {
                                            return data;
                                        }
                                    @endrole
                                },
                            },
                        },
                    },
                    { 
                        extend: 'pdf',
                        title: 'Dokumen Lainnya',
                        filename: 'dokumen-lainnya-' + new Date().toISOString().slice(0, 10),
                        className: 'btn btn-sm btn-dark border',
                        exportOptions: {
                            columns: dataArrayForDataTables,
                            format: {
                                body: function(data, row, column, node) {
                                    // Jika kolom adalah kolom "dokumen"
                                    @role('Superadmin|Admin|Kajur')
                                        if (column === 2) {
                                            // ambil isi href dari kolom "dokumen"
                                            const href = $(node).find('a').attr('href');
                                            if (href) {
                                                return href;
                                            } else {
                                                return '-';
                                            }
                                        } else {
                                            return data;
                                        }
                                    @endrole
                                    @role('Kaprodi|Dosen')
                                        if (column === 2) {
                                            // ambil isi href dari kolom "dokumen"
                                            const href = $(node).find('a').attr('href');
                                            if (href) {
                                                return href;
                                            } else {
                                                return '-';
                                            }
                                        } else {
                                            return data;
                                        }
                                    @endrole
                                },
                            },
                        },
                        customize: function(doc) {
                            // atur orientasi PDF
                            doc.pageOrientation = 'landscape';

                            // atur style vertical alignment menjadi middle
                            doc.styles.tableBodyEven.alignment = 'left';
                            doc.styles.tableBodyOdd.alignment = 'left'; 

                            // atur style alignment menjadi middle center
                            doc.styles.tableHeader.alignment = 'center';

                            // Mengatur lebar kolom menjadi proporsi yang sama
                            doc.content[1].table.widths = Array(doc.content[1].table.body[0].length + 1).join('*').split('');
                            
                            // Mengatur margin menjadi nol untuk membuat tabel menjadi full width
                            doc.content[1].margin = [0, 0, 0, 0]; 
                            
                            // Mengatur orientasi PDF menjadi landscape
                            doc.content[1].layout = 'landscape';

                            // Menambahkan hyperlink ke dalam PDF
                            doc.content[1].table.body.forEach(function(row) {
                                row.forEach(function(cell) {
                                    if (typeof cell.text === 'string' && cell.text.startsWith('http')) {
                                        cell.text = { text: cell.text, link: cell.text, color: 'blue', decoration: 'underline' };
                                    }
                                });
                            });
                        },
                    },
                    { 
                        extend: 'print', 
                        title: 'Dokumen Lainnya',
                        className: 'btn btn-sm btn-dark border',
                        exportOptions: {
                            columns: dataArrayForDataTables,
                            format: {
                                body: function(data, row, column, node) {
                                    // Jika kolom adalah kolom "dokumen"
                                    @role('Superadmin|Admin|Kajur')
                                        if (column === 2) {
                                            // ambil isi href dari kolom "dokumen"
                                            const href = $(node).find('a').attr('href');
                                            if (href) {
                                                return href;
                                            } else {
                                                return '-';
                                            }
                                        } else {
                                            return data;
                                        }
                                    @endrole
                                    @role('Kaprodi|Dosen')
                                        if (column === 2) {
                                            // ambil isi href dari kolom "dokumen"
                                            const href = $(node).find('a').attr('href');
                                            if (href) {
                                                return href;
                                            } else {
                                                return '-';
                                            }
                                        } else {
                                            return data;
                                        }
                                    @endrole
                                },
                            },
                        },
                        customize: function(win) {
                            // Mengatur margin untuk memastikan tabel tidak melewati batas
                            $(win.document.body).css('margin', '10px');

                            // Mengatur ukuran font untuk memastikan tabel tidak terlalu besar
                            $(win.document.body).css('font-size', '10pt');

                            // Mengatur lebar kolom menjadi proporsi yang sama
                            $(win.document.body).find('table').css('width', '100%');

                            // Mengatur orientasi halaman menjadi landscape
                            $(win.document.body).find('table').addClass('landscape');

                            // Menambahkan style untuk memastikan tabel tidak melewati batas margin
                            $(win.document.body).find('table').css('table-layout', 'fixed');
                            $(win.document.body).find('table').css('word-wrap', 'break-word');

                            // Menambahkan style untuk memastikan isi kolom dibungkus dengan benar
                            $(win.document.body).find('table th, table td').css('white-space', 'normal');
                            $(win.document.body).find('table th, table td').css('overflow', 'hidden');
                            $(win.document.body).find('table th, table td').css('text-overflow', 'ellipsis');

                            $(win.document.body).find('table th').css('vertical-align', 'middle');
                            $(win.document.body).find('table th').css('text-align', 'center');
                            $(win.document.body).find('table th').css('padding', '5px 5px');

                            $(win.document.body).find('table td').css('vertical-align', 'middle');
                            $(win.document.body).find('table td').css('padding', '2px 5px');
                        }
                    }
                ]
            });

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

            // konfirmasi tombol hapus menggunakan swal
            $('body').on('click', '.tombol-hapus', function(e) {
                e.preventDefault();

                // mengekstrak URL 'hapus' dari formulir
                const dataId = $(this).data('id');
                const deleteUrl = "{{ route('dokumenLainnya.destroy', ':id') }}";
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
    </script>
@endpush
