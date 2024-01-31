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
                <div class="d-flex gap-3 flex-column justify-content-center align-items-center">
                    <img class="rounded-circle-image"
                        src="{{ isset($user)
                            ? ($user->foto
                                ? asset('storage/usersProfile/' . $user->foto)
                                : asset('assets/admin/img/user-placeholder.svg'))
                            : asset('assets/admin/img/user-placeholder.svg') }}"
                        alt="profile-image">
                    <div class="text-center">
                        <h1 class="fw-bolder mb-2">{{ $user->name ?? '' }}</h1>
                        <h5 class="fw-400 mb-2">{{ $user->nim ?? '' }} - Angkatan {{ $user->mahasiswa->angkatan ?? '' }}
                        </h5>
                        <h6 class="fw-400 mb-3">Program Studi {{ $user->mahasiswa->program_studi ?? '' }}</h6>
                        <hr>
                        <span class="small text-muted">
                            Akun ini dibuat pada tanggal
                            {{ date('d F Y', strtotime($user->created_at)) }}
                            pukul
                            {{ date('H:i', strtotime($user->created_at)) }}
                        </span>
                        <hr>
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
