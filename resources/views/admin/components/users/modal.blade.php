<!-- Modal -->
@if (isset($role) && $role === 'mahasiswa')
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
                            <input class="form-control" id="name" type="text" placeholder="Masukkan Nama" />
                        </div>
                        <div class="mb-2" id="nimInput">
                            <label class="small mb-1" for="nimField">NIM<span class="text-danger">*</span></label>
                            <input type="text" id="nimField" class="form-control" placeholder="Masukkan NIM">
                        </div>
                        <div class="mb-2">
                            <label class="small mb-1" for="prodiField">Program Studi<span
                                    class="text-danger">*</span></label>
                            <select id="prodiField" class="form-select" aria-label="Default select example">
                                <option value="" selected hidden>Pilih Program Studi</option>
                                <option value="pti">Pendidikan Teknologi Informasi (PTI)</option>
                                <option value="si">Sistem Informasi (SI)</option>
                            </select>
                        </div>
                        <div class="mb-2">
                            <label class="small mb-1" for="angkatanField">Angkatan<span
                                    class="text-danger">*</span></label>
                            <input type="text" id="angkatanField" class="form-control"
                                placeholder="Masukkan Angkatan">
                        </div>
                        <div class="mb-2" id="passInput">
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

    <div class="modal fade" id="modalDetailUser" tabindex="-1" role="dialog" aria-labelledby="modalDetailUserTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalDetailUserTitle">Modal Detail User</h5>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-1">
                        <label><b>Nim</b></label>
                        <p id="nimDetail"></p>
                    </div>
                    <div class="mb-1">
                        <label><b>Nama</b></label>
                        <p id="nameDetail"></p>
                    </div>
                    <div class="mb-1">
                        <label><b>Program Studi</b></label>
                        <p id="prodiDetail"></p>
                    </div>
                    <div class="mb-1">
                        <label><b>Angkatan</b></label>
                        <p id="angkatanDetail"></p>
                    </div>
                    <div class="mb-1">
                        <label><b>Role</b></label>
                        <br>
                        <span class="badge bg-info text-white" id="roleDetail"></span>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
@elseif (isset($role) && $role === 'dosen')
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
                            <input class="form-control" id="name" type="text" placeholder="Masukkan Nama" />
                        </div>
                        <div class="mb-2" id="nipInput">
                            <label class="small mb-1" for="nipField">NIP<span class="text-danger">*</span></label>
                            <input type="text" id="nipField" class="form-control" placeholder="Masukkan NIP">
                        </div>
                        <div class="mb-2" id="passInput">
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
@elseif (isset($role) && $role === 'admin')
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
                        <div class="mb-2" id="emailInput">
                            <label class="small mb-1" for="emailField">Email<span
                                    class="text-danger">*</span></label>
                            <input type="email" id="emailField" class="form-control" placeholder="Enter email">
                        </div>
                        <div class="mb-2" id="passInput">
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

    <div class="modal fade" id="modalDetailUser" tabindex="-1" role="dialog"
        aria-labelledby="modalDetailUserTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalDetailUserTitle">Modal Detail User</h5>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-1">
                        <label><b>Nama</b></label>
                        <p id="nameDetail"></p>
                    </div>
                    <div class="mb-1">
                        <label><b>Email</b></label>
                        <p id="emailDetail"></p>
                    </div>
                    <div class="mb-1">
                        <label><b>Role</b></label>
                        <br>
                        <span class="badge bg-dark text-white" id="roleDetail"></span>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
@else
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
                                <option value="" selected hidden>Pilih Role</option>
                                <option value="admin">Administrator</option>
                                <option value="dosen">Dosen</option>
                                <option value="mahasiswa">Mahasiswa</option>
                            </select>
                        </div>
                        <div class="mb-2" id="emailInput" style="display: none;">
                            <label class="small mb-1" for="emailField">Email<span
                                    class="text-danger">*</span></label>
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
@endif
