{{-- <li class="nav-item dropdown no-caret d-none d-sm-block me-3 dropdown-notifications">
    <a class="btn btn-icon btn-transparent-dark dropdown-toggle" id="navbarDropdownAlerts"
       href="javascript:void(0);" role="button" data-bs-toggle="dropdown" aria-haspopup="true"
       aria-expanded="false">
       <i data-feather="bell"></i>
       @if ($unreadNotifikasis->count() > 0)
           <span class="badge bg-danger rounded-pill" style="font-size: 8px;" id="unreadCount">
               {{ $unreadNotifikasis->count() }}
           </span>
       @endif
    </a>
    <div class="dropdown-menu dropdown-menu-end border-0 shadow animated--fade-in-up"
         aria-labelledby="navbarDropdownAlerts">
        <h6 class="dropdown-header dropdown-notifications-header">
            <i class="me-2" data-feather="bell"></i>
            Notifikasi
        </h6>

        @if ($notifikasis->count() > 0)
            @foreach ($notifikasis->take(5) as $notifikasi)
                <a class="dropdown-item dropdown-notifications-item" href="#!" 
                   data-id="{{ $notifikasi->id }}" data-dibaca="{{ $notifikasi->dibaca }}">
                    <div class="dropdown-notifications-item-content w-100">
                        <div class="dropdown-notifications-item-content-details">
                            @if ($notifikasi->dibaca === 0)
                                <span class="badge bg-danger rounded-pill mb-1">
                                    Terbaru!
                                </span>
                            @endif
                            <div class="d-flex align-items-center justify-content-between">
                                <span>
                                    {{ $notifikasi->created_at->isoFormat('dddd, D MMMM Y') }}
                                </span>
                                <span>
                                    {{ $notifikasi->created_at->isoFormat('H:mm') }}
                                </span>
                            </div>
                        </div>
                        <div class="dropdown-notifications-item-content-text text-wrap">
                            {{ $notifikasi->pesan }}
                        </div>
                    </div>
                </a>
            @endforeach
            <a class="dropdown-item dropdown-notifications-footer" href="{{ route('notifikasi.index') }}">Lihat Semua</a>
        @else
            <div class="text-center">
                <div class="p-3 text-muted">
                    Anda tidak memiliki notifikasi.
                </div>
            </div>
        @endif
    </div>
</li>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.dropdown-notifications-item').forEach(function (item) {
            var dibaca = item.getAttribute('data-dibaca');
            
            // Ubah kursor jika notifikasi sudah dibaca
            if (dibaca === '1') {
                item.style.cursor = 'default';
            }

            item.addEventListener('click', function (event) {
                event.stopPropagation(); // Mencegah dropdown agar tidak tertutup
                
                var notifikasiId = this.getAttribute('data-id');
                var dibaca = this.getAttribute('data-dibaca');

                // Jika notifikasi sudah dibaca, tidak ada aksi
                if (dibaca === '1') {
                    this.style.cursor = 'default';
                    return;
                }

                fetch("{{ route('notifikasi.dibaca') }}", {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ id: notifikasiId })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        this.querySelector('.badge').remove(); // Hapus badge 'Terbaru!'
                        this.setAttribute('data-dibaca', '1'); // Set notifikasi sebagai dibaca
                        this.style.cursor = 'default'; // Ubah kursor ke default
                        updateUnreadCount(); // Panggil fungsi untuk memperbarui jumlah notifikasi belum dibaca
                    }
                });
            });
        });

        function updateUnreadCount() {
            fetch("{{ route('notifikasi.jumlah') }}")
            .then(response => response.json())
            .then(data => {
                var unreadCountElement = document.getElementById('unreadCount');
                if (data.unread_count > 0) {
                    unreadCountElement.textContent = data.unread_count;
                } else {
                    unreadCountElement.remove(); // Hapus badge jika tidak ada notifikasi yang belum dibaca
                }
            });
        }
    });
</script> --}}
