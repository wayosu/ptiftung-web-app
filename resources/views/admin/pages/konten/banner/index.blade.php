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
                        <a id="btnSegarkanDatatables" class="btn btn-sm btn-light text-primary" href="javascript:void(0)" role="button">
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
                <form action="{{ route('banner.store') }}" method="POST" class="row g-4 align-items-center"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="col-12">
                        <div>
                            <label class="small mb-1">
                                Banner
                                <span class="text-danger">*</span>
                            </label>
                            <label id="bannerLabel" class="custom-btn-upload @error('program_studi') border-danger @enderror" for="bannerField">
                                <div class="d-flex align-items-center gap-2">
                                    <i class="fa-solid fa-upload"></i>
                                    <span id="bannerText">Unggah Banner</span>
                                </div>
                                <button type="button" role="button" id="btnHapusBanner" class="btn-hapus-banner d-none">
                                    <i class="fa-solid fa-xmark fa-lg"></i>
                                </button>
                            </label>
                            <input class="d-none" name="banner" id="bannerField" type="file"
                                accept="image/jpg, image/jpeg, image/png" />
                            <span class="text-xs text-muted">Format JPG, JPEG, PNG max. 2MB. Rekomendasi 5002 x 1928</span>
                            @error('banner')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        @role('Superadmin|Admin|Kajur')
                            <div class="mt-3">
                                <label class="small mb-1" for="prodiField">
                                    Program Studi
                                    <span class="text-danger">*</span>
                                </label>
                                <select class="form-control @error('program_studi') is-invalid @enderror" name="program_studi"
                                    id="prodiField">
                                    <option value="" selected hidden>-- Pilih Program Studi --</option>
                                    <option value="SISTEM INFORMASI">
                                        SISTEM INFORMASI
                                    </option>
                                    <option value="PEND. TEKNOLOGI INFORMASI">
                                        PEND. TEKNOLOGI INFORMASI
                                    </option>
                                </select>
                                @error('program_studi')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        @endrole
                    </div>
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fa-solid fa-plus me-1"></i>
                            Tambah Banner
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
                            <th>Banner</th>
                            @role('Superadmin|Admin|Kajur')
                                <th>Program Studi</th>
                            @endrole
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
                ajax: "{{ route('banner.index') }}",
                columns: [{
                        data: 'gambar',
                        render: function(gambar) {
                            let path =
                                `{{ asset('storage/konten/banner/`+ gambar +`') }}`;
                            return `
                                <a href="${path}" data-lightbox="image" data-title="${gambar}" class="btn-img-banner">
                                    <img src="${path}" class="img-fluid" />
                                </a>
                            `;
                        }
                    },
                    @role('Superadmin|Admin|Kajur')
                    { data: 'program_studi' },
                    @endrole
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

            // event upload gambar
            $('#bannerField').change(function() {
                const label = $('#bannerLabel');
                const file = this.files[0];
                const fileName = file.name;
                const validImageTypes = ["image/jpeg", "image/png", "image/jpg"];

                // validasi tipe file (harus berupa gambar)
                if (!validImageTypes.includes(file.type)) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Maaf terjadi kesalahan',
                        text: 'File harus berupa gambar (JPEG, PNG, JPG)'
                    });

                    $(this).val(''); // reset nilai file input
                    label.find('span').text('Unggah Banner'); // kembalikan teks label
                    $('#btnHapusBanner').addClass('d-none'); // sembunyikan tombol hapus

                    return false;
                }

                // validasi ukuran file (maksimum 2MB)
                if (file.size > 2 * 1024 * 1024) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Maaf terjadi kesalahan',
                        text: 'Ukuran file banner maksimal 2 MB'
                    });

                    $(this).val(''); // reset nilai file input
                    label.find('span').text('Unggah Banner'); // kembalikan teks label
                    $('#btnHapusBanner').addClass('d-none'); // sembunyikan tombol hapus

                    return false;
                }

                label.find('span').text(fileName); // mengubah teks span di dalam label
                $('#btnHapusBanner').removeClass('d-none'); // tampilkan tombol hapus
            });

            // event hapus gambar
            $('#btnHapusBanner').click(function() {
                const label = $('#bannerLabel');
                label.find('span').text('Unggah Banner'); // mengatur kembali teks span di dalam label
                $('#btnHapusBanner').addClass('d-none'); // sembunyikan tombol hapus
                $('#bannerField').val(''); // kosongkan nilai file input
            });

            // konfirmasi hapus dengan swal
            $('body').on('click', '.tombol-hapus', function(e) {
                e.preventDefault();

                // mengekstrak URL hapus dari formulir
                const dataId = $(this).data('id');
                const deleteUrl = "{{ route('banner.destroy', ':id') }}";
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
