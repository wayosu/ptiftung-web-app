<!-- Modal -->
<div class="modal fade" id="modalFormUser" tabindex="-1" role="dialog" aria-labelledby="modalFormUserTitle"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalFormUserTitle">Modal Form User</h5>
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
                        <label class="small mb-1" for="name">Nama<span class="text-danger">*</span></label>
                        <input class="form-control" id="name" type="text" placeholder="Enter name" />
                    </div>
                    <div class="mb-2">
                        <label class="small mb-1">Role<span class="text-danger">*</span></label>
                        <select id="roleSelect" class="form-select" aria-label="Default select example">
                            <option selected="" disabled="">Select a role:</option>
                            <option value="admin">Administrator</option>
                            <option value="dosen">Dosen</option>
                            <option value="mahasiswa">Mahasiswa</option>
                        </select>
                    </div>
                    <div class="mb-2" id="emailInput" style="display: none;">
                        <label class="small mb-1" for="emailField">Email<span class="text-danger">*</span></label>
                        <input type="email" id="emailField" class="form-control" placeholder="Enter email">
                    </div>
                    <div class="mb-2" id="nipInput" style="display: none;">
                        <label class="small mb-1" for="nipField">NIP<span class="text-danger">*</span></label>
                        <input type="text" id="nipField" class="form-control" placeholder="Enter NIP">
                    </div>
                    <div class="mb-2" id="nimInput" style="display: none;">
                        <label class="small mb-1" for="nimField">NIM<span class="text-danger">*</span></label>
                        <input type="text" id="nimField" class="form-control" placeholder="Enter NIM">
                    </div>
                    <div class="mb-2" id="passInput" style="display: none;">
                        <label class="small mb-1" for="passField">Password (default)<span
                                class="text-danger">*</span></label>
                        <input type="text" id="passField" class="form-control" readonly>
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
