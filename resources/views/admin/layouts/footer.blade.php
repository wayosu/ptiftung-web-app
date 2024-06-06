<footer class="footer-admin mt-auto footer-light">
    <div class="container-xl px-4">
        <div class="row">
            <div class="col-md-6 small">
                Copyright © 
                @role('Kaprodi|Dosen')
                    @if (Auth::user()->dosen->program_studi == "PEND. TEKNOLOGI INFORMASI")
                        Pendidikan Teknologi Informasi
                    @else
                        Sistem Informasi
                    @endif
                @endrole
                @role('Mahasiswa')
                    @if (Auth::user()->mahasiswa->program_studi == "PEND. TEKNOLOGI INFORMASI")
                        Pendidikan Teknologi Informasi
                    @else
                        Sistem Informasi
                    @endif
                @endrole
                @role('Superadmin|Admin|Kajur')
                    Teknik Informatika
                @endrole
                {{ date('Y') }}
            </div>
            <div class="col-md-6 text-md-end small">
                <a href="#!">v.2.0</a>
            </div>
        </div>
    </div>
</footer>
