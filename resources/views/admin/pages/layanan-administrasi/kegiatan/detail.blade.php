@extends('admin.layouts.app')

@push('css')
<link href="{{ asset('assets/admin/libs/datatables/css/dataTables.bootstrap5.min.css') }}" rel="stylesheet" />
<link href="{{ asset('assets/admin/libs/datatables/css/responsive.bootstrap5.min.css') }}" rel="stylesheet" />
<link rel="stylesheet" href="{{ asset('assets/admin/libs/select2/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/admin/libs/select2/css/select2-bootstrap-5-theme.min.css') }}">
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

        .select2-container--bootstrap-5 .select2-selection {
            font-size: 0.875rem !important;
        }

        .select2-container--bootstrap-5 .select2-dropdown .select2-results__options .select2-results__option {
            font-size: 0.875rem !important;
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
                        <a class="btn btn-sm btn-light text-primary" href="{{ route('kegiatan.index') }}">
                            <i class="fa-solid fa-arrow-left me-1"></i>
                            Kembali
                        </a>
                        <a id="btnSegarkanDatatables" class="btn btn-sm btn-light text-primary" href="javascript:void(0)" role="button">
                            <i class="fa-solid fa-arrows-rotate me-1"></i>
                            Segarkan
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Main page content-->
    <div class="container-fluid px-4">
        <div class="row g-4">
            <div class="col-12 col-xl-4">
                <form id="thisForm" action="{{ route('kegiatan.store', $kegiatanMahasiswaId) }}" method="POST" class="card">
                    @csrf
                    <div class="card-header">Form Tambah Peserta Kegiatan</div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="small mb-1" for="angkatanField">
                                Angkatan
                                <span class="text-danger">*</span>
                            </label>
                            <select name="angkatan" id="angkatanField" class="form-select select2 @error('angkatan') is-invalid @enderror">
                                <option></option>
                                @foreach ($angkatanList->sortDesc() as $item)
                                    <option value="{{ $item }}" {{ old('angkatan') == $item ? 'selected' : '' }}>
                                        {{ $item }}
                                    </option>
                                @endforeach
                            </select>
                            @error('angkatan')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="small mb-1" for="mahasiswaField">
                                Mahasiswa
                                <span class="text-danger">*</span>
                            </label>
                            <select name="mahasiswa_id" id="mahasiswaField" class="form-select select2 @error('mahasiswa_id') is-invalid @enderror">
                                <option></option>
                            </select>
                            @error('mahasiswa_id')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="small mb-1" for="dosenField">
                                Dosen Pilimbing Lapangan
                                <span class="text-danger">*</span>
                            </label>
                            <select name="dosen_id" id="dosenField" class="form-select select2 @error('dosen_id') is-invalid @enderror">
                                <option></option>
                                @foreach ($dosenList as $item)
                                    <option value="{{ $item['id'] }}" {{ old('dosen_id') == $item['id']? 'selected' : '' }}>
                                        {{ $item['name'] }}
                                    </option>
                                @endforeach
                            </select>
                            @error('dosen_id')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <button class="btn btn-light" type="reset" id="resetButton">
                            <i class="fa-solid fa-rotate-left me-1"></i>
                            Atur Ulang
                        </button>
                        <button class="btn btn-primary" type="submit" onclick="return onSubmitForm()">
                            <i class="fa-solid fa-plus-circle me-1"></i>
                            Tambah Peserta
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
                                    <th>NIM</th>
                                    <th>Nama Mahasiswa</th>
                                    <th>Angkatan</th>
                                    <th>Dosen Pembimbing Lapangan</th>
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
    </div>
@endsection

@push('js')
    <script src="{{ asset('assets/admin/libs/jquery/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('assets/admin/libs/datatables/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/admin/libs/datatables/js/dataTables.bootstrap5.min.js') }}"></script>
    <script src="{{ asset('assets/admin/libs/datatables/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('assets/admin/libs/datatables/js/responsive.bootstrap5.min.js') }}"></script>
    <script src="{{ asset('assets/admin/libs/sweetalert2/js/sweetalert2.all.min.js') }}"></script>
    <script src="{{ asset('assets/admin/libs/select2/js/select2.min.js') }}"></script>

    <script>
        $(document).ready(function() {
            // inisialisasi datatables
            $('#myDataTables').DataTable({
                responsive: true,
                order: [
                    [0, 'asc'],
                    [2, 'asc'],
                ],
                language: {
                    url: 'https://cdn.datatables.net/plug-ins/1.13.1/i18n/id.json'
                },
                processing: true,
                serverSide: true,
                ajax: "{{ route('kegiatan.dataTables', $kegiatanMahasiswaId) }}",
                columns: [
                    { data: 'mahasiswa.nim', },
                    { data: 'mahasiswa.user.name', },
                    { data: 'mahasiswa.angkatan', },
                    { data: 'dosen.user.name', },
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

            // inisialisasi select2
            $('#angkatanField').select2({
                theme: 'bootstrap-5',
                placeholder: "Pilih Angkatan...",
                allowClear: true,
                width: '100%'
            });

            $('#mahasiswaField').select2({
                theme: 'bootstrap-5',
                placeholder: "Pilih Mahasiswa...",
                allowClear: true,
                width: '100%'
            });

            $('#dosenField').select2({
                theme: 'bootstrap-5',
                placeholder: "Pilih Dosen Pilimbing Lapangan...",
                allowClear: true,
                width: '100%'
            });

            // Kondisi awal mahasiswaField
            $('#mahasiswaField').val(null).trigger('change').prop('disabled', true);

            // Event listener untuk perubahan pada angkatanField
            $('#angkatanField').on('change', function() {
                var angkatan = $(this).val();
                var kegiatanMahasiswaId = {{ $kegiatanMahasiswaId }};
                if (angkatan) {
                    $.ajax({
                        url: '{{ route("kegiatan.getMahasiswaByAngkatan") }}',
                        type: 'GET',
                        data: { angkatan: angkatan, kegiatan_mahasiswa_id: kegiatanMahasiswaId },
                        success: function(data) {
                            $('#mahasiswaField').empty().append('<option></option>'); // Kosongkan dan tambahkan placeholder
                            $.each(data, function(key, value) {
                                $('#mahasiswaField').append('<option value="' + value.id + '">' + value.nim + ' - ' + value.name + '</option>');
                            });
                            $('#mahasiswaField').prop('disabled', false); // Aktifkan select2
                        }
                    });
                } else {
                    $('#mahasiswaField').empty().append('<option></option>').prop('disabled', true); // Kosongkan dan nonaktifkan select2
                }
            });

            // Event listener untuk tombol reset
            $('#resetButton').on('click', function() {
                // Reset semua select2
                $('#angkatanField').val(null).trigger('change');
                $('#mahasiswaField').val(null).trigger('change').prop('disabled', true);
                $('#dosenField').val(null).trigger('change');
            });

            // Event listener untuk form reset
            $('#thisForm').on('reset', function() {
                // Reset semua select2 setelah form direset
                setTimeout(function() {
                    $('#angkatanField').val(null).trigger('change');
                    $('#mahasiswaField').val(null).trigger('change').prop('disabled', true);
                    $('#dosenField').val(null).trigger('change');
                }, 0);
            });

            // refresh datatables on click #btnSegarkanDatatables
            $('#btnSegarkanDatatables').on('click', function() {
                $('#myDataTables').DataTable().ajax.reload();
            });
            
            // konfirmasi tombol hapus menggunakan swal
            $('body').on('click', '.tombol-hapus', function(e) {
                e.preventDefault();

                // mengekstrak URL 'hapus' dari formulir
                const dataId = $(this).data('id');
                const deleteUrl = "{{ route('kegiatan.destroy', ':id') }}";
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
