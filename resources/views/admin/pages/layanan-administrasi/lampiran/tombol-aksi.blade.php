@role('Mahasiswa')
    <a class="btn btn-datatable btn-icon btn-transparent-dark tombol-detail" href="javascript:void(0)" title="Detail Lampiran" data-id="{{ $lampirans->id }}">
        <i class="fas fa-eye"></i>
    </a>

    <form id="deleteForm" class="d-none" method="POST">
        @csrf
        @method('DELETE')
    </form>
    <a class="btn btn-datatable btn-icon btn-transparent-dark tombol-hapus" href="javascript:void(0)" title="Hapus"
        data-id="{{ $lampirans->id }}">
        <i class="fas fa-trash"></i>
    </a>
@endrole

@role('Superadmin|Kaprodi|Dosen')
    @if (isset($kegiatans->lampiranKegiatans) && count($kegiatans->lampiranKegiatans) > 0)    
        @if ($allReviewed)
            <a href="#" class="btn btn-sm btn-light tombol-detail-lampiran" href="javascript:void(0)" title="Detail Lampiran" data-id="{{ $kegiatans->id }}">
                <i class="fa-solid fa-list fa-sm me-1"></i>
                Detail Lampiran
            </a>
        @else
            <a class="btn btn-sm btn-primary tombol-review" href="javascript:void(0)" title="Detail Lampiran" data-id="{{ $kegiatans->id }}">
                <i class="fa-solid fa-magnifying-glass fa-sm me-1"></i>
                Review Lampiran
            </a>
        @endif
    @else
        <span class="d-block px-2 py-1 text-sm bg-yellow-soft text-yellow rounded text-center">
            <i class="fa-solid fa-circle-exclamation me-1"></i>
            Tidak ada lampiran
        </span>
    @endif
@endrole

@role('Kajur')
    <a href="#" class="btn btn-sm btn-light tombol-detail-lampiran" href="javascript:void(0)" title="Detail Lampiran" data-id="{{ $kegiatans->id }}">
        <i class="fa-solid fa-list fa-sm me-1"></i>
        Detail Lampiran
    </a>
@endrole
