@extends('admin.layouts.app')

@push('css')
    <link href="{{ asset('assets/admin/libs/datatables/css/dataTables.bootstrap5.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/admin/libs/datatables/css/responsive.bootstrap5.min.css') }}" rel="stylesheet" />

    <style>
        #myDataTables {

            td,
            th {
                vertical-align: middle;
            }
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
                        <a class="btn btn-sm btn-light text-primary dropdown-toggle" id="navbarDropdownDocs"
                            href="javascript:void(0);" role="button" data-bs-toggle="dropdown" aria-haspopup="true"
                            aria-expanded="false">
                            <i class="fa-solid fa-users-gear me-1"></i>
                            By Role
                        </a>
                        <div class="dropdown-menu dropdown-menu-end py-0 me-sm-n15 me-lg-0 o-hidden animated--fade-in-up"
                            aria-labelledby="navbarDropdownDocs">
                            <a class="dropdown-item small py-2" href="#" target="_blank">
                                Admin
                            </a>
                            <div class="dropdown-divider m-0"></div>
                            <a class="dropdown-item small py-2" href="#" target="_blank">
                                Dosen
                            </a>
                            <div class="dropdown-divider m-0"></div>
                            <a class="dropdown-item small py-2" href="#" target="_blank">
                                Mahasiswa
                            </a>
                        </div>
                        <a class="btn btn-sm btn-primary text-light" href="javascript:void(0)" type="button"
                            data-bs-toggle="modal" data-bs-target="#exampleModalCenter">
                            <i class="me-1" data-feather="user-plus"></i>
                            Tambah User
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
                <table id="myDataTables" class="table table-bordered dt-responsive nowrap" style="width:100%">
                    <thead>
                        <tr>
                            <th width="50%">User</th>
                            <th>Role</th>
                            <th>Tanggal Dibuat</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar me-2">
                                        <img class="avatar-img img-fluid"
                                            src="{{ asset('assets/admin/img/profile-1.png') }}" />
                                    </div>
                                    Admin PTI
                                </div>
                            </td>
                            <td>Admin</td>
                            <td>02/01/2024</td>
                            <td>
                                <a class="btn btn-datatable btn-icon btn-transparent-dark me-2" href="#"
                                    title="Detail">
                                    <i data-feather="eye"></i>
                                </a>
                                <a class="btn btn-datatable btn-icon btn-transparent-dark me-2" href="#"
                                    title="Edit">
                                    <i data-feather="edit"></i>
                                </a>
                                <a class="btn btn-datatable btn-icon btn-transparent-dark" href="#!" title="Hapus">
                                    <i data-feather="trash-2"></i>
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar me-2">
                                        <img class="avatar-img img-fluid"
                                            src="{{ asset('assets/admin/img/profile-1.png') }}" />
                                    </div>
                                    Admin PTI
                                </div>
                            </td>
                            <td>Admin</td>
                            <td>03/01/2024</td>
                            <td>
                                <a class="btn btn-datatable btn-icon btn-transparent-dark me-2" href="#"
                                    title="Detail">
                                    <i data-feather="eye"></i>
                                </a>
                                <a class="btn btn-datatable btn-icon btn-transparent-dark me-2" href="#"
                                    title="Edit">
                                    <i data-feather="edit"></i>
                                </a>
                                <a class="btn btn-datatable btn-icon btn-transparent-dark" href="#!" title="Hapus">
                                    <i data-feather="trash-2"></i>
                                </a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalCenterTitle">Vertically Centered Modal</h5>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">...</div>
                <div class="modal-footer"><button class="btn btn-secondary" type="button"
                        data-bs-dismiss="modal">Close</button><button class="btn btn-primary" type="button">Save
                        changes</button></div>
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
    <script>
        $(document).ready(function() {
            $('#myDataTables').DataTable({
                responsive: false,
                scrollX: true,
                order: [
                    [2, 'desc']
                ]
            });
        });
    </script>
@endpush
