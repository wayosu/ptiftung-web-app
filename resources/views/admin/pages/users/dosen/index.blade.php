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
                    <div class="col-12 col-xl-auto mb-3">
                        <a id="btnSegarkanDatatables" class="btn btn-sm btn-light text-primary" href="javascript:void(0)" role="button">
                            <i class="fa-solid fa-arrows-rotate me-1"></i>
                            Segarkan
                        </a>
                        @include('admin.pages.users.filter-berdasarkan')
                        <a class="btn btn-sm btn-light text-primary" href="{{ route('bidangKepakaran.index') }}">
                            <i class="fa-regular fa-lightbulb me-1"></i>
                            Daftar Bidang Kepakaran
                        </a>
                        <a class="btn btn-sm btn-light text-primary" href="{{ route('users.createDosen') }}">
                            <i class="fa-solid fa-plus me-1"></i>
                            Tambah Dosen
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Main page content-->
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
                            <th>NIP</th>
                            <th>Nama</th>
                            <th>Program Studi</th>
                            <th>JAFA</th>
                            <th>Jenis Kelamin</th>
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
            // initialize datatables
            var table = $('#myDataTables').DataTable({
                responsive: true,
                order: [
                    [2, 'asc'],
                    [1, 'asc'],
                    [0, 'asc']
                ],
                language: {
                    url: 'https://cdn.datatables.net/plug-ins/1.13.1/i18n/id.json'
                },
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('users.byDosen') }}",
                    type: 'GET',
                    data: function(d) {
                        d.program_studi = $('#program_studi').val();
                    }
                },
                columns: [
                    { data: 'dosen.nip' },
                    { 
                        data: 'name', 
                        render: function(data, type, row) {
                            let roleName = '';
                            let badge = '';
                            switch (row.role_names) {
                                case 'Kajur':
                                    roleName = 'Kepala Jurusan Infomatika'; // Perbaikan nama role jika perlu
                                    badge = '<span class="badge bg-indigo ms-1"><i class="fa-solid fa-shield fa-xs me-1"></i>' + roleName + '</span>';
                                    break;
                                case 'Kaprodi':
                                    roleName = 'Kepala Program Studi ' + (row.dosen.program_studi === 'SISTEM INFORMASI' ? 'SI' : 'PTI'); // Menyesuaikan dengan program studi
                                    badge = '<span class="badge bg-pink ms-1"><i class="fa-solid fa-shield fa-xs me-1"></i>' + roleName + '</span>';
                                    break;
                                default:
                                    badge = '';
                            }

                            return data + badge;
                        }
                    },
                    { data: 'dosen.program_studi' },
                    { data: 'dosen.jafa' },
                    { data: 'dosen.jenis_kelamin' },
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

            // confirm delete with swal
            $('body').on('click', '.tombol-hapus', function(e) {
                e.preventDefault();

                // Extracting the delete URL from the form
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
                        autocomplete: 'off', // Disable autocomplete
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

        function setChangeRole(userId, role) {
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: `Anda akan mengubah role user ini menjadi ${role}.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, ubah role!',
                confirmButtonColor: '#00ac69',
                cancelButtonText: 'Batal',
                cancelButtonColor: '#6c757d',
            }).then((result) => {
                if (result.isConfirmed) {
                    const token = "{{ csrf_token() }}";
                    let url = "{{ route('users.changeRole', ':userId') }}"; // Sesuaikan URL endpoint sesuai dengan rute Anda
                    url = url.replace(':userId', userId);

                    $.ajax({
                        url: url,
                        type: 'POST',
                        data: {
                            role: role,
                            _token: token
                        },
                        success: function(data) {
                            if (data.success) {
                                Swal.fire(
                                    'Berhasil!',
                                    'Role user telah diubah.',
                                    'success'
                                ).then(() => {
                                    // Refresh datatables
                                    $('#myDataTables').DataTable().ajax.reload();
                                });
                            } else {
                                Swal.fire(
                                    'Gagal!',
                                    data.error || 'Terjadi kesalahan saat mengubah role.',
                                    'error'
                                );
                            }
                        },
                        error: function(xhr, status, error) {
                            Swal.fire(
                                'Gagal!',
                                'Terjadi kesalahan saat mengubah role.',
                                'error'
                            );
                            console.error('Error:', error);
                        }
                    });
                }
            });

            return false;
        }
    </script>
@endpush
