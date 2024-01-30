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
                    <div class="col-12 col-xl-auto mb-3">
                        <a class="btn btn-sm btn-light text-primary" href="{{ request()->fullUrl() }}" role="button">
                            <i class="fa-solid fa-arrows-rotate me-1"></i>
                            Refresh
                        </a>
                        @include('admin.pages.users.admin.filter-by')
                        <a class="btn btn-sm btn-light text-primary dropdown-toggle" id="navbarDropdownDocs"
                            href="javascript:void(0);" role="button" data-bs-toggle="dropdown" aria-haspopup="true"
                            aria-expanded="false">
                            <i class="fa-solid fa-user me-1"></i>
                            Tambah Data
                        </a>
                        <div class="dropdown-menu dropdown-menu-start py-0 me-sm-n15 me-lg-0 o-hidden animated--fade-in-up"
                            aria-labelledby="navbarDropdownDocs">
                            <a class="dropdown-item small py-2" href="{{ route('users.createAdmin') }}">
                                Admin
                            </a>
                            <div class="dropdown-divider m-0"></div>
                            <a class="dropdown-item small py-2" href="#">
                                Dosen
                            </a>
                            <div class="dropdown-divider m-0"></div>
                            <a class="dropdown-item small py-2" href="#">
                                Mahasiswa
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Main page content-->
    <div class="container-fluid px-4">
        <div class="card">
            <div class="card-body overflow-hidden">
                <table id="myDataTables" class="table table-bordered dt-responsive wrap" style="width: 100%;">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Role</th>
                            <th>Tanggal Dibuat</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (count($users) == 0)
                            <tr>
                                <td colspan="4" class="text-center">
                                    <div class="d-flex gap-1 justify-content-center align-items-center small">
                                        <i class="fa-solid fa-circle-info"></i>
                                        Data tidak ditemukan.
                                    </div>
                                </td>
                            </tr>
                        @else
                            @foreach ($users as $user)
                                <tr>
                                    <td>{{ $user->name }}</td>
                                    <td>
                                        @if ($user->role_name == 'Admin')
                                            <span class="badge bg-blue-soft text-blue">Admin</span>
                                        @elseif ($user->role_name == 'Dosen')
                                            <span class="badge bg-red-soft text-red">Dosen</span>
                                        @elseif ($user->role_name == 'Mahasiswa')
                                            <span class="badge bg-green-soft text-green">Mahasiswa</span>
                                        @endif
                                    </td>
                                    <td>{{ $user->created_at }}</td>
                                    <td>
                                        <a class="btn btn-datatable btn-icon btn-transparent-dark me-2" href="#"
                                            title="Detail">
                                            <i class="fas fa-eye"></i>
                                        </a>

                                        <a class="btn btn-datatable btn-icon btn-transparent-dark me-2" href="#"
                                            title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>

                                        <form id="deleteForm" class="d-none" method="POST">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                        <a class="btn btn-datatable btn-icon btn-transparent-dark tombol-hapus"
                                            href="javascript:void(0)" title="Hapus" data-user-id="{{ $user->id }}">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
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

    <script>
        $(document).ready(function() {
            // initialize datatables
            $('#myDataTables').DataTable({
                responsive: true,
                order: [
                    [2, 'desc']
                ],
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

            // toast notification
            @if (Session::has('success'))
                Toast.fire({
                    icon: 'success',
                    title: '{{ Session::get('success') }}'
                })
            @endif

            // confirm delete with swal
            $('.tombol-hapus').on('click', function(e) {
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
    </script>
@endpush
