<script src="{{ asset('assets/admin/libs/jquery/jquery-3.7.1.min.js') }}"></script>
<script src="{{ asset('assets/admin/libs/datatables/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/admin/libs/datatables/js/dataTables.bootstrap5.min.js') }}"></script>
<script src="{{ asset('assets/admin/libs/datatables/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('assets/admin/libs/datatables/js/responsive.bootstrap5.min.js') }}"></script>
<script src="{{ asset('assets/admin/libs/moment/moment.min.js') }}"></script>
<script src="{{ asset('assets/admin/libs/sweetalert2/js/sweetalert2.all.min.js') }}"></script>

<script>
    $(document).ready(function() {
        // Setup AJAX
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // Menampilkan data dengan DataTable AJAX
        $('#myDataTables').DataTable({
            responsive: true,
            order: [
                [1, 'desc']
            ],
            processing: true,
            serverSide: true,
            ajax: "{{ route('bidangKepakaran.indexAjax') }}",
            columns: [{
                data: 'bidang_kepakaran',
                defaultContent: '',
            }, {
                data: 'aksi',
                defaultContent: '',
                orderable: false,
                searchable: false
            }]
        });

        // Tambah Data
        $('.tambah-data').click(function(e) {
            e.preventDefault();
            $('#modalForm').modal('show');

            $('.tombol-simpan').html('Simpan');

            // Bersihkan input
            $('#bidangKepakaran').val('');

            // Sembunyikan alert
            $('#myAlertDanger').addClass('d-none');

            // fungsi off adalah untuk menghilangkan fungsi tombol simpan sebelumnya
            // fungsi on adalah untuk menambahkan fungsi tombol simpan yang baru
            $('.tombol-simpan').off('click').on('click', function() {
                const bidangKepakaran = $('#bidangKepakaran').val();

                $.ajax({
                    url: "{{ route('bidangKepakaran.store') }}",
                    type: 'POST',
                    data: {
                        bidangKepakaran: bidangKepakaran,
                    },
                    beforeSend: function() {
                        $('.tombol-simpan').attr('disabled', 'disabled');
                        $('.tombol-simpan').html(
                            '<i class="fa fa-spinner fa-spin"></i>');
                    },
                    success: function(response) {
                        $('#listValidation').html('');

                        // Jika ada error validasi
                        if (response.errors) {
                            $('#myAlertDanger').removeClass('d-none');
                            $('#listValidation').append('<ul class="mb-2">');
                            $.each(response.errors, function(key, value) {
                                $('#listValidation').find('ul').append(`
                                    <li>${value}</li>
                                `);
                            })
                            $('#listValidation').append('</ul>');

                            $('#modalForm').modal('show');
                        } else {
                            $('#bidangKepakaran').val('');

                            $('#modalForm').modal('hide');
                            $('#myDataTables').DataTable().ajax.reload();

                            // Toast notifikasi
                            const Toast = Swal.mixin({
                                toast: true,
                                position: 'top-end',
                                showConfirmButton: false,
                                timer: 4000,
                                timerProgressBar: true,
                                didOpen: (toast) => {
                                    toast.addEventListener(
                                        'mouseenter',
                                        Swal.stopTimer)
                                    toast.addEventListener(
                                        'mouseleave',
                                        Swal.resumeTimer)
                                }
                            })

                            Toast.fire({
                                icon: 'success',
                                title: 'Data berhasil ditambahkan!'
                            })

                        }
                    },
                    complete: function() {
                        $('.tombol-simpan').removeAttr('disabled');
                        $('.tombol-simpan').html('Simpan');
                    },
                });
            });


        });

        // Edit Data
        $('body').on('click', '.tombol-edit', function(e) {
            e.preventDefault();

            const id = $(this).data('id');

            $('.tombol-simpan').html('Update');

            $('#myAlertDanger').addClass('d-none');

            $.ajax({
                url: "/dashboard/bidang-kepakaran/" + id + "/edit",
                type: 'GET',
                success: function(response) {
                    $('#modalForm').modal('show');

                    $('#bidangKepakaran').val(response.result.bidang_kepakaran);

                    $('.tombol-simpan').off('click').on('click', function() {
                        const bidangKepakaran = $('#bidangKepakaran').val();

                        const url = "{{ route('bidangKepakaran.update', ':id') }}";
                        const urlEdit = url.replace(':id', id);

                        $.ajax({
                            url: urlEdit,
                            type: 'PUT',
                            data: {
                                bidangKepakaran: bidangKepakaran,
                            },
                            beforeSend: function() {
                                $('.tombol-simpan').prop('disabled',
                                    true);
                                $('.tombol-simpan').html(
                                    '<i class="fa fa-spinner fa-spin"></i>'
                                );
                            },
                            success: function(response) {
                                $('#listValidation').html('');

                                // Jika ada error validasi
                                if (response.errors) {
                                    $('#myAlertDanger').removeClass(
                                        'd-none');
                                    $('#listValidation').append(
                                        '<ul class="mb-2">');
                                    $.each(response.errors,
                                        function(
                                            key, value) {
                                            $('#listValidation')
                                                .find('ul')
                                                .append(`
                                    <li>${value}</li>
                                `);
                                        })
                                    $('#listValidation').append(
                                        '</ul>');

                                    $('#modalForm').modal(
                                        'show');
                                } else {
                                    $('#bidangKepakaran').val('');

                                    $('#modalForm').modal(
                                        'hide');
                                    $('#myDataTables').DataTable()
                                        .ajax
                                        .reload();

                                    // Toast notifikasi
                                    const Toast = Swal.mixin({
                                        toast: true,
                                        position: 'top-end',
                                        showConfirmButton: false,
                                        timer: 4000,
                                        timerProgressBar: true,
                                        didOpen: (
                                            toast) => {
                                            toast
                                                .addEventListener(
                                                    'mouseenter',
                                                    Swal
                                                    .stopTimer
                                                )
                                            toast
                                                .addEventListener(
                                                    'mouseleave',
                                                    Swal
                                                    .resumeTimer
                                                )
                                        }
                                    })

                                    Toast.fire({
                                        icon: 'success',
                                        title: 'Data berhasil diupdate!'
                                    })

                                }
                            },
                            complete: function() {
                                // Mengaktifkan kembali tombol update
                                $('.tombol-simpan').prop('disabled',
                                    false);
                                $('.tombol-simpan').html('Update');
                            },
                        });
                    });
                }
            });
        });

        // Hapus Data
        $('body').on('click', '.tombol-hapus', function(e) {
            e.preventDefault();

            const id = $(this).data('id');

            Swal.fire({
                title: 'Apakah anda yakin?',
                text: "Data akan dihapus secara permanen!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Hapus',
                cancelButtonText: 'Batal',
            }).then((result) => {
                if (result.isConfirmed) {
                    const url = "{{ route('bidangKepakaran.destroy', ':id') }}";
                    const urlDelete = url.replace(':id', id);

                    $.ajax({
                        url: urlDelete,
                        type: 'DELETE',
                        data: {
                            _token: '{{ csrf_token() }}',
                        },
                        success: function(response) {
                            $('#myDataTables').DataTable().ajax.reload();

                            // Toast notifikasi
                            const Toast = Swal.mixin({
                                toast: true,
                                position: 'top-end',
                                showConfirmButton: false,
                                timer: 4000,
                                timerProgressBar: true,
                                didOpen: (toast) => {
                                    toast.addEventListener(
                                        'mouseenter',
                                        Swal.stopTimer)
                                    toast.addEventListener(
                                        'mouseleave',
                                        Swal.resumeTimer)
                                }
                            })

                            Toast.fire({
                                icon: 'success',
                                title: 'Data berhasil dihapus!'
                            })
                        }
                    });
                }
            })
        });
    });
</script>
