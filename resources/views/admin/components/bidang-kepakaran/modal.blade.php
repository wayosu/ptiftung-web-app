<div class="modal fade" id="modalForm" tabindex="-1" role="dialog" aria-labelledby="modalFormUserTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalFormUserTitle">Form Bidang Kepakaran</h5>
                <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="myAlertDanger" class="alert alert-red py-2 d-none small" role="alert">
                    <h6 class="mt-2 mb-1" style="color: #8b0d00;">
                        <i class="fas fa-circle-exclamation"></i>
                        Perhatikan kembali form yang anda masukkan!
                    </h6>
                    <hr>
                    <div id="listValidation">

                    </div>
                </div>
                <form>
                    <div class="mb-2">
                        <label class="small mb-1" for="bidangKepakaran">Bidang Kepakaran<span
                                class="text-danger">*</span></label>
                        <input class="form-control" id="bidangKepakaran" type="text"
                            placeholder="Masukkan Bidang Kepakaran" />
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Tutup</button>
                <button class="btn btn-primary tombol-simpan" type="button">Simpan</button>
            </div>
        </div>
    </div>
</div>
