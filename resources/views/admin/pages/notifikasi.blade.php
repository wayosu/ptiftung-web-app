@extends('admin.layouts.app')

@push('css')
    <link href="{{ asset('assets/admin/libs/sweetalert2/css/sweetalert2.min.css') }}" rel="stylesheet" />
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
                    </div>
                    <div class="col-12 col-md-auto mb-3">
                        <a class="btn btn-sm btn-light text-primary" href="{{ request()->fullUrl() }}" role="button">
                            <i class="fa-solid fa-arrows-rotate me-1"></i>
                            Segarkan
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Konten Halaman Utama -->
    <div class="container-xl px-4 mt-4">
        <div class="row g-4">
            <!-- Notifikasi Belum Dibaca -->
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex gap-3 flex-row align-items-center justify-content-between mb-2">
                            <h1 class="text-xs fw-bolder mb-0 text-nowrap bg-danger text-light p-1 px-2 rounded-1">
                                Belum Dibaca
                            </h1>
                            <hr class="w-100 border border-2 rounded-2 border-danger" />
                        </div>
                        <div class="d-flex gap-2 flex-column" id="belumDibaca">
                            @forelse ($notifikasisBelumDibaca as $notifikasi)
                                <div class="d-flex gap-1 flex-column border border-1 rounded-2 p-2 w-100 notifikasi-item" data-id="{{ $notifikasi->id }}">
                                    <div class="d-flex gap-2 align-items-center justify-content-between">
                                        <span class="text-xs text-muted">{{ $notifikasi->created_at->isoFormat('dddd, D MMMM Y') }} pukul {{ $notifikasi->created_at->isoFormat('H:mm') }}</span>
                                        <div class="d-flex gap-2">
                                            <a href="javascript:void(0)" class="text-xs text-muted mark-as-read">
                                                <i class="fa-solid fa-circle-check"></i>
                                            </a>
                                            <a href="javascript:void(0)" class="text-xs text-muted delete-notification">
                                                <i class="fa-solid fa-trash"></i>
                                            </a>
                                        </div>
                                    </div>
                                    <p class="mb-0">{{ $notifikasi->pesan }}</p>
                                </div>
                            @empty
                                <div class="d-flex gap-1 flex-column border border-1 rounded-2 p-2 w-100">
                                    <p class="mb-0 text-sm text-muted">Tidak ada notifikasi yang belum dibaca.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            <!-- Notifikasi Sudah Dibaca -->
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex gap-3 flex-row align-items-center justify-content-between mb-2">
                            <h1 class="text-xs fw-bolder mb-0 text-nowrap bg-success text-light p-1 px-2 rounded-1">
                                Sudah Dibaca
                            </h1>
                            <hr class="w-100 border border-2 rounded-2 border-success" />
                        </div>
                        <div class="d-flex gap-2 flex-column" id="sudahDibaca">
                            @forelse ($notifikasisSudahDibaca as $notifikasi)
                                <div class="d-flex gap-1 flex-column border border-1 rounded-2 p-2 w-100 notifikasi-item" data-id="{{ $notifikasi->id }}">
                                    <div class="d-flex gap-2 align-items-center justify-content-between">
                                        <span class="text-xs text-muted">{{ $notifikasi->created_at->isoFormat('dddd, D MMMM Y') }} pukul {{ $notifikasi->created_at->isoFormat('H:mm') }}</span>
                                        <a href="javascript:void(0)" class="text-xs text-muted delete-notification">
                                            <i class="fa-solid fa-trash"></i>
                                        </a>
                                    </div>
                                    <p class="mb-0">{{ $notifikasi->pesan }}</p>
                                </div>
                            @empty
                                <div class="d-flex gap-1 flex-column border border-1 rounded-2 p-2 w-100">
                                    <p class="mb-0 text-sm text-muted">Tidak ada notifikasi yang sudah dibaca.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script src="{{ asset('assets/admin/libs/sweetalert2/js/sweetalert2.all.min.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.mark-as-read').forEach(function(item) {
                item.addEventListener('click', function(event) {
                    event.preventDefault(); // Mencegah aksi default tautan
                    var notifikasiItem = this.closest('.notifikasi-item');
                    var notifikasiId = notifikasiItem.getAttribute('data-id');

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
                            // Pindahkan notifikasi ke bagian "Sudah Dibaca"
                            var sudahDibacaContainer = document.getElementById('sudahDibaca');
                            notifikasiItem.classList.remove('notifikasi-item');

                            // Hapus tombol mark-as-read
                            var markAsReadButton = notifikasiItem.querySelector('.mark-as-read');
                            if (markAsReadButton) {
                                markAsReadButton.remove();
                            }

                            sudahDibacaContainer.appendChild(notifikasiItem);
                            updateUnreadCount();
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal',
                                text: 'Terjadi kesalahan saat menandai notifikasi.',
                            });
                        }
                    })
                    .catch(error => {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            text: 'Terjadi kesalahan saat menandai notifikasi.',
                        });
                    });
                });
            });

            document.querySelectorAll('.delete-notification').forEach(function(item) {
                item.addEventListener('click', function(event) {
                    event.preventDefault(); // Mencegah aksi default tautan
                    var notifikasiItem = this.closest('.notifikasi-item');
                    var notifikasiId = notifikasiItem.getAttribute('data-id');

                    Swal.fire({
                        title: 'Apakah Anda yakin?',
                        text: "Notifikasi ini akan dihapus!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: 'Ya, hapus!',
                        cancelButtonText: 'Batal',
                    }).then((result) => {
                        if (result.isConfirmed) {
                            fetch("{{ route('notifikasi.delete') }}", {
                                method: 'DELETE',
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    'Content-Type': 'application/json'
                                },
                                body: JSON.stringify({ id: notifikasiId })
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    notifikasiItem.remove();
                                    updateUnreadCount();
                                    Swal.fire(
                                        'Dihapus!',
                                        'Notifikasi telah dihapus.',
                                        'success'
                                    );
                                } else {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Gagal',
                                        text: 'Terjadi kesalahan saat menghapus notifikasi.',
                                    });
                                }
                            })
                            .catch(error => {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Gagal',
                                    text: 'Terjadi kesalahan saat menghapus notifikasi.',
                                });
                            });
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
    </script>
@endpush
