<!-- Modal -->
<div class="modal fade" id="detailModal{{ $user->id }}" tabindex="-1" role="dialog" aria-labelledby="detailModalTitle"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="detailModalTitle">Detail Profil Admin</h5>
                <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body py-5">
                <div class="d-flex gap-3 justify-content-center align-items-center">
                    <img class="rounded-circle-image"
                        src="{{ isset($user)
                            ? ($user->foto
                                ? asset('storage/usersProfile/' . $user->foto)
                                : asset('assets/admin/img/user-placeholder.svg'))
                            : asset('assets/admin/img/user-placeholder.svg') }}"
                        alt="profile-image">
                    <div class="">
                        <h1 class="fw-bolder mb-1">{{ $user->name }}</h1>
                        <h4 class="fw-400 mb-2">{{ $user->email }}</h4>
                        <h6><span class="badge bg-primary text-white">Admin</span></h6>
                        <span class="small">
                            Akun ini dibuat pada tanggal
                            {{ date('d F Y', strtotime($user->created_at)) }}
                            pukul
                            {{ date('H:i', strtotime($user->created_at)) }}
                        </span>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Tutup</button>
                <a href="{{ route('users.editAdmin', $user->id) }}" class="btn btn-primary">Ubah Profil</a>
            </div>
        </div>
    </div>
</div>
