<a class="btn btn-datatable btn-icon btn-transparent-dark me-2" href="{{ route('sarana.detailImage', $saranas->id) }}"
    title="Detail Gambar">
    <i class="fas fa-images"></i>
</a>


<a class="btn btn-datatable btn-icon btn-transparent-dark me-2" href="{{ route('sarana.edit', $saranas->id) }}"
    title="Edit">
    <i class="fas fa-edit"></i>
</a>

<form id="deleteForm" class="d-none" method="POST">
    @csrf
    @method('DELETE')
</form>
<a class="btn btn-datatable btn-icon btn-transparent-dark tombol-hapus" href="javascript:void(0)" title="Hapus"
    data-id="{{ $saranas->id }}">
    <i class="fas fa-trash"></i>
</a>
