@extends('admin.layouts.app')

@push('css')
    <link rel="stylesheet" href="{{ asset('assets/admin/libs/select2/css/select2.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/admin/libs/select2/css/select2-bootstrap-5-theme.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/admin/libs/yearpicker/yearpicker.css') }}">

    <style>
        .select2-container--bootstrap-5 .select2-selection {
            min-height: calc(1.5em + 0.75rem + 15px) !important;
            font-size: 0.875rem !important;
        }

        .select2-container--bootstrap-5 .select2-selection--single {
            padding: 0.775rem 1.1rem !important;
        }
    </style>
@endpush

@section('content')
    <!-- Konten Header -->
    <header class="page-header page-header-compact page-header-light border-bottom bg-white mb-4">
        <div class="container-xl px-4">
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
                        <a class="btn btn-sm btn-light text-primary" href="{{ route('penelitian.index') }}">
                            <i class="fa-solid fa-arrow-left me-1"></i>
                            Kembali
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Konten Halaman Utama -->
    <div class="container-xl px-4 mt-4">
        <form
            action="@if (isset($penelitian)) {{ route('penelitian.update', $penelitian->id) }} @else {{ route('penelitian.store') }} @endif"
            method="POST" class="row">
            @csrf
            @if (isset($penelitian))
                @method('PUT')
            @endif

            <div class="col-xl-6">
                <div class="card mb-4">
                    <div class="card-header">Form {{ $title ?? '' }}</div>
                    @if (isset($penelitian) && $penelitian->createdBy)
                        <div class="card-header bg-white">
                            <div
                                class="d-flex flex-column flex-md-row-reverse align-items-start align-items-md-center justify-content-between">
                                <div class="text-xs text-muted">
                                    <i class="fa-solid fa-user fa-xs me-1"></i>
                                    <span>
                                        {{ $penelitian->createdBy->name }}
                                    </span>
                                </div>
                                <div class="text-xs text-muted">
                                    <i class="fa-solid fa-calendar fa-xs me-1"></i>
                                    <span>
                                        {{ \Carbon\Carbon::parse($penelitian->created_at)->isoFormat('dddd, D MMMM Y H:mm') }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    @endif
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="small mb-1" for="tahunField">
                                Tahun
                                <span class="text-danger">*</span>
                            </label>
                            <input class="form-control @error('tahun') is-invalid @enderror" name="tahun" id="tahunField"
                                type="text" placeholder="{{ $penelitian->tahun ?? 'Masukkan tahun' }}"
                                autocomplete="off" />
                            @if (isset($penelitian))
                                <input type="hidden" name="tahun_lama" value="{{ $penelitian->tahun ?? '' }}">
                            @endif
                            @error('tahun')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="small mb-1" for="dosenField">
                                Dosen
                                <span class="text-danger">*</span>
                            </label>
                            <select name="dosen_id" id="dosenField"
                                class="form-select select2 @error('dosen_id') is-invalid @enderror">
                                <option></option>
                                @foreach ($namaDosen as $item)
                                    <option value="{{ $item['id'] }}"
                                        {{ old('dosen_id', $penelitian->dosen_id ?? '') == $item['id'] ? 'selected' : '' }}>
                                        {{ $item['name'] }}</option>
                                @endforeach
                            </select>
                            @error('dosen_id')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="small mb-1" for="jabatanField">
                                Jabatan
                                <span class="text-danger">*</span>
                            </label>
                            <input class="form-control @error('jabatan') is-invalid @enderror" name="jabatan"
                                id="jabatanField" type="text" placeholder="Masukkan jabatan"
                                value="{{ old('jabatan', $penelitian->jabatan ?? '') }}" autocomplete="off" />
                            @error('jabatan')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="small mb-1" for="skimField">
                                SKIM
                                <span class="text-danger">*</span>
                            </label>
                            <input class="form-control @error('skim') is-invalid @enderror" name="skim" id="skimField"
                                type="text" placeholder="Masukkan skim"
                                value="{{ old('skim', $penelitian->skim ?? '') }}" autocomplete="off" />
                            @error('skim')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="small mb-1" for="judulField">
                                Judul
                                <span class="text-danger">*</span>
                            </label>
                            <input class="form-control @error('judul') is-invalid @enderror" name="judul" id="judulField"
                                type="text" placeholder="Masukkan judul"
                                value="{{ old('judul', $penelitian->judul ?? '') }}" autocomplete="off" />
                            @error('judul')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="small mb-1" for="sumberDanaField">
                                Sumber Dana
                                <span class="text-danger">*</span>
                            </label>
                            <input class="form-control @error('sumber_dana') is-invalid @enderror" name="sumber_dana"
                                id="sumberDanaField" type="text" placeholder="Masukkan sumber dana"
                                value="{{ old('sumber_dana', $penelitian->sumber_dana ?? '') }}" autocomplete="off" />
                            @error('sumber_dana')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="small mb-1" for="jumlahDanaField">
                                Jumlah Dana (Rp)
                                <span class="text-danger">*</span>
                            </label>
                            <input class="form-control @error('jumlah_dana') is-invalid @enderror" name="jumlah_dana"
                                id="jumlahDanaField" type="text" placeholder="Masukkan jumlah dana"
                                value="{{ old('jumlah_dana', $penelitian->jumlah_dana ?? '') }}" autocomplete="off" />
                            @error('jumlah_dana')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <button class="btn btn-light" type="reset">
                            <i class="fa-solid fa-rotate-left me-1"></i>
                            Atur Ulang
                        </button>
                        <button class="btn btn-primary" type="submit">
                            <i class="fa-solid fa-floppy-disk me-1"></i>
                            @if (isset($penelitian))
                                Perbarui
                            @else
                                Simpan
                            @endif
                        </button>
                    </div>
                    @if (isset($penelitian) && $penelitian->updatedBy)
                        <div class="card-footer p-2 bg-white">
                            <div
                                class="d-flex gap-3 py-2 align-items-center text-start bg-white text-muted text-xs overflow-hidden">
                                <div class="px-3 border-end border-2">
                                    <i class="fa-solid fa-circle-info"></i>
                                </div>
                                <div>
                                    Terakhir diperbarui oleh
                                    <span class="fw-bolder">
                                        {{ $penelitian->updatedBy->name }}
                                    </span>
                                    pada
                                    <span class="fw-bolder">
                                        {{ \Carbon\Carbon::parse($penelitian->updated_at)->isoFormat('dddd, D MMMM Y') }}
                                    </span>
                                    pukul
                                    <span class="fw-bolder">
                                        {{ \Carbon\Carbon::parse($penelitian->updated_at)->isoFormat('H:mm') }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </form>
    </div>
@endsection

@push('js')
    <script src="{{ asset('assets/admin/libs/jquery/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('assets/admin/libs/select2/js/select2.min.js') }}"></script>
    <script src="{{ asset('assets/admin/libs/yearpicker/yearpicker.js') }}"></script>

    <script>
        $(document).ready(function() {
            // inisialisasi select2
            $('.select2').select2({
                theme: 'bootstrap-5',
                dropdownCssClass: "select2--small",
                width: '100%',
                placeholder: "-- Pilih Dosen --",
                allowClear: true
            });

            $('#tahunField').yearpicker();

            $('#jumlahDanaField').on('keyup', function(e) {
                // tambahkan 'Rp.' pada saat form di ketik
                // gunakan fungsi formatRupiah() untuk mengubah angka yang di ketik menjadi format angka
                $(this).val(formatRupiah($(this).val(), 'Rp. '));
            });

            /* Fungsi formatRupiah */
            function formatRupiah(angka, prefix) {
                var number_string = angka.replace(/[^,\d]/g, '').toString(),
                    split = number_string.split(','),
                    sisa = split[0].length % 3,
                    rupiah = split[0].substr(0, sisa),
                    ribuan = split[0].substr(sisa).match(/\d{3}/gi);

                // tambahkan titik jika yang di input sudah menjadi angka ribuan
                if (ribuan) {
                    separator = sisa ? '.' : '';
                    rupiah += separator + ribuan.join('.');
                }

                rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
                return prefix == undefined ? rupiah : (rupiah ? 'Rp. ' + rupiah : '');
            }
        });
    </script>
@endpush
