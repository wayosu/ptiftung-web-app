@extends('admin.layouts.app')

@push('css')
    @include('admin.components.style')
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
                        <a class="btn btn-sm btn-light text-primary" href="{{ route('users.byRole', 'dosen') }}">
                            <i class="fa-solid fa-user me-1"></i>
                            Daftar Dosen
                        </a>
                        <a class="btn btn-sm btn-primary text-light tambah-data" href="javascript:void(0)" type="button">
                            <i class="me-1" data-feather="plus"></i>
                            Tambah Data
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
                <table id="myDataTables" class="table table-bordered dt-responsive wrap" style="width: 100%;">
                    <thead>
                        <tr>
                            <th>Bidang Kepakaran</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>

    @include('admin.components.bidang-kepakaran.modal')
@endsection

@push('js')
    @include('admin.service.bidang-kepakaran.bidangKepakaran')
@endpush
