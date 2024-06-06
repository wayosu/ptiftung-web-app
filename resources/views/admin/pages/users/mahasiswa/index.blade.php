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

        .rounded-circle-image {
            width: 150px;
            height: 150px;
            object-fit: cover;
            border-radius: 50%;
        }
    </style>
@endpush

@section('content')
    <!-- Konten Header-->
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
                        @include('admin.pages.users.filter-berdasarkan')
                        <a class="btn btn-sm btn-light text-primary" data-bs-toggle="modal"
                        data-bs-target="#modalImportMahasiswa" href="javascript:void(0);" role="button">
                            <i class="fa-solid fa-file-import me-1"></i>
                            Import Data
                        </a>
                        <a class="btn btn-sm btn-light text-primary" href="{{ route('users.createMahasiswa') }}">
                            <i class="fa-solid fa-plus me-1"></i>
                            Tambah Mahasiswa
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Konten Halaman Utama-->
    <div class="container-fluid px-4">
        <div class="card">
            <div class="card-body overflow-hidden">
                <div class="mb-3">
                    <label for="program_studi" class="mb-1">Filter berdasarkan Program Studi:</label>
                    <select name="program_studi" id="program_studi" class="form-control">
                        <option value="">Semua Program Studi</option>
                        <option value="SISTEM INFORMASI">Sistem Informasi</option>
                        <option value="PEND. TEKNOLOGI INFORMASI">Pendidikan Teknologi Informasi</option>
                    </select>
                </div>
                <table id="myDataTables" class="table table-bordered dt-responsive wrap" style="width: 100%;">
                    <thead>
                        <tr>
                            <th>NIM</th>
                            <th>Nama</th>
                            <th>Program Studi</th>
                            <th>Angkatan</th>
                            <th>Tanggal Dibuat</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>            
        </div>
    </div>

    <!--- modal import data --->
    <div class="modal fade" id="modalImportMahasiswa" tabindex="-1" role="dialog"
        aria-labelledby="examplemModalImportMahasiswaTitle" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <form id="formImport" method="POST" enctype="multipart/form-data" class="modal-content">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="examplemModalImportMahasiswaTitle">Import Data Mahasiswa</h5>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-4">
                        <h6 class="fw-bolder">Contoh Data CSV</h6>
                        <img src="{{ asset('assets/admin/img/contoh-data-csv.png') }}" alt="contoh-data-csv"
                            class="img-fluid border border-2 border-danger">
                    </div>
                    <p class="mb-1 text-sm">Silahkan pilih file CSV yang akan diimport.</p>
                    <input id="file" type="file" class="form-control" name="file" accept=".csv,text/csv">
                    <span id="pesan-error" class="text-xs text-danger"></span>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Tutup</button>
                    <button id="submit-btn" class="btn btn-primary" type="button">
                        Import Sekarang
                        <i class="fas fa-arrow-right ms-1"></i>
                    </button>
                </div>
            </form>
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
            var table = $('#myDataTables').DataTable({
                responsive: true,
                order: [
                    [2, 'asc'],
                    [0, 'asc']
                ],
                language: {
                    url: 'https://cdn.datatables.net/plug-ins/1.13.1/i18n/id.json'
                },
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('users.byMahasiswa') }}",
                    data: function(d) {
                        d.program_studi = $('#program_studi').val();
                    },
                    dataSrc: function(json) {
                        if (!json.data) {
                            return [];
                        }
                        return json.data;
                    }
                },
                columns: [
                    { data: 'mahasiswa.nim' },
                    { data: 'name' },
                    { data: 'mahasiswa.program_studi' },
                    { data: 'mahasiswa.angkatan' },
                    {
                        data: 'created_at',
                        render: function(data) {
                            return moment(data).locale('id').format('dddd, D MMMM YYYY HH:mm') + ' WITA';
                        }
                    },
                    {
                        data: 'aksi',
                        orderable: false,
                        searchable: false
                    }
                ]
            });

            $('#program_studi').on('change', function() {
                table.ajax.reload();
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

            // refresh datatables on click #btnSegarkanDatatables
            $('#btnSegarkanDatatables').on('click', function() {
                $('#myDataTables').DataTable().ajax.reload();
            });

            // konfirmasi tombol hapus menggunakan swal
            $('body').on('click', '.tombol-hapus', function(e) {
                e.preventDefault();

                // Mengekstrak URL 'hapus' dari formulir
                const userId = $(this).data('user-id');
                const deleteUrl = "{{ route('users.destroy', ':id') }}";
                const newDeleteUrl = deleteUrl.replace(':id', userId);

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
                        autocomplete: 'off', // Menonaktifkan pelengkapan otomatis
                    },
                    inputValidator: (value) => {
                        const trimmedValue = value.trim();

                        // Memastikan inputan tidak kosong
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

            // klik tombol 'import mahasiswa'
            $('#submit-btn').on('click', function(e) {
                e.preventDefault();

                // Disable the button and show loading text
                const button = $(this);
                button.prop('disabled', true);
                const originalText = button.html();
                button.html('Processing... <i class="fas fa-spinner fa-spin"></i>');

                const formData = new FormData($('#formImport')[0]);

                // Debug: Check if file is appended correctly
                if (!formData.has('file') || !formData.get('file').size) {
                    $('#pesan-error').text('File tidak ditemukan. Silahkan pilih file yang akan diimport.');
                    button.prop('disabled', false);
                    button.html(originalText);
                    return;
                }

                $.ajax({
                    type: 'POST',
                    url: "{{ route('users.importMahasiswa') }}",
                    data: formData,
                    processData: false, // Don't process the data
                    contentType: false, // Set content type to false
                    success: function(data) {
                        if (data.error) {
                            $('#pesan-error').text(data.error.join(', '));
                        } else {
                            $('#file').val('');
                            $('#formImport')[0].reset();

                            // Close modal
                            $('#modalImportMahasiswa').modal('hide');

                            // Display success message
                            Toast.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                text: data.success
                            });

                            // Refresh datatables
                            $('#myDataTables').DataTable().ajax.reload();

                            // Clear error message
                            $('#pesan-error').text('');
                        }
                    },
                    error: function(xhr, status, error) {
                        $('#pesan-error').text('Error: ' + error);
                    },
                    complete: function() {
                        // Re-enable the button and restore original text
                        button.prop('disabled', false);
                        button.html(originalText);
                    }
                });
            });
        });
    </script>
@endpush
