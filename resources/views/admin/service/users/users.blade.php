<script src="{{ asset('assets/admin/libs/jquery/jquery-3.7.1.min.js') }}"></script>
<script src="{{ asset('assets/admin/libs/datatables/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/admin/libs/datatables/js/dataTables.bootstrap5.min.js') }}"></script>
<script src="{{ asset('assets/admin/libs/datatables/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('assets/admin/libs/datatables/js/responsive.bootstrap5.min.js') }}"></script>
<script src="{{ asset('assets/admin/libs/moment/moment.min.js') }}"></script>
<script src="{{ asset('assets/admin/libs/sweetalert2/js/sweetalert2.all.min.js') }}"></script>

<script>
    $(document).ready(function() {
        // Menampilkan data dengan DataTable AJAX
        $('#myDataTables').DataTable({
            responsive: true,
            order: [
                [2, 'desc']
            ],
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('users.indexAjax') }}",
                type: 'GET',
                csrf: '{{ csrf_token() }}',
            },
            columns: [{
                data: 'name',
                defaultContent: '',
                render: function(data) {
                    return `
                            <div class="d-flex align-items-center position-relative">
                                ${data}
                            </div>
                        `;
                }
            }, {
                data: 'role_names',
                defaultContent: '',
                render: function(data) {
                    let badgeClass = '';
                    let badgeText = '';

                    switch (data) {
                        case 'Admin':
                            badgeClass = 'bg-dark';
                            badgeText = 'Administrator';
                            break;
                        case 'Dosen':
                            badgeClass = 'bg-secondary';
                            badgeText = 'Dosen';
                            break;
                        case 'Mahasiswa':
                            badgeClass = 'bg-info';
                            badgeText = 'Mahasiswa';
                            break;
                        default:
                            badgeClass = 'bg-light';
                            badgeText = 'Unknown Role';
                            break;
                    }

                    return `
                        <div class="badge ${badgeClass} text-white">
                            ${badgeText}
                        </div>
                    `;
                }
            }, {
                data: 'created_at',
                defaultContent: '',
                render: function(data) {
                    return moment(data).format(
                        'DD-MM-YYYY HH:mm:ss');
                }
            }, {
                data: 'aksi',
                defaultContent: '',
                orderable: false,
                searchable: false
            }]
        });

        // Pemilihan role
        $('#roleSelect').change(function() {
            // Sembunyikan semua input field
            $('#emailInput, #nipInput, #nimInput, #passInput').hide();
            $('#emailField').val('');
            $('#nipField').val('');
            $('#nimField').val('');
            $('#passField').val('');

            // Tampilkan input field sesuai dengan role
            const selectedRole = $(this).val();
            if (selectedRole === 'admin') {
                $('#emailInput').show();
                $('#passInput input').val('admin123');
                $('#passInput').show();
            } else if (selectedRole === 'dosen') {
                $('#nipInput').show();
                $('#nipField').on('input', function() {
                    const nipValue = $(this).val();
                    $('#passField').val(nipValue);
                });
                $('#passInput').show();
            } else if (selectedRole === 'mahasiswa') {
                $('#nimInput').show();
                $('#nimField').on('input', function() {
                    const nimValue = $(this).val();
                    $('#passField').val(nimValue);
                });
                $('#passInput').show();
            }
        });

        // Tambah User
        $('.tambah-user').click(function(e) {
            e.preventDefault();
            $('#modalFormUser').modal('show');

            $('.tombol-simpan').html('Simpan');

            $('#name').val('');
            $('#roleSelect').val('');
            $('#emailField').val('');
            $('#nipField').val('');
            $('#nimField').val('');
            $('#passField').val('');

            $('#emailInput, #nipInput, #nimInput, #passInput').hide();

            $('#myAlertDanger').addClass('d-none');

            $('.tombol-simpan').off('click').on('click', function() {
                const name = $('#name').val();
                const role = $('#roleSelect').val();
                const email = $('#emailField').val();
                const nip = $('#nipField').val();
                const nim = $('#nimField').val();
                const password = $('#passField').val();

                $.ajax({
                    url: "{{ route('users.store') }}",
                    type: 'POST',
                    data: {
                        name: name,
                        role: role,
                        email: email,
                        nip: nip,
                        nim: nim,
                        password: password,
                        _token: '{{ csrf_token() }}',
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

                            $('#modalFormUser').modal('show');
                        } else {
                            $('#name').val('');
                            $('#roleSelect').val('');
                            $('#emailField').val('');
                            $('#nipField').val('');
                            $('#nimField').val('');
                            $('#passField').val('');

                            $('#modalFormUser').modal('hide');
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
                                title: 'User berhasil ditambahkan!'
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

        // Edit User
        $('body').on('click', '.tombol-edit', function(e) {
            e.preventDefault();

            const id = $(this).data('id');

            $('.tombol-simpan').html('Update');

            $('#myAlertDanger').addClass('d-none');

            $.ajax({
                url: "/dashboard/users/" + id + "/edit",
                type: 'GET',
                success: function(response) {
                    $('#modalFormUser').modal('show');

                    $('#name').val(response.result.name);
                    $('#roleSelect').val(response.result.roles[0].name);
                    $('#passInput label').html('Password');
                    $('#passField').removeAttr('readOnly');
                    $('#passField').val('');

                    $('#emailInput, #nipInput, #nimInput, #passInput').hide();

                    if (response.result.roles[0].name === 'admin') {
                        $('#emailInput').show();
                        $('#emailInput input').val(response.result.email);
                        $('#passInput').show();
                    } else if (response.result.roles[0].name === 'dosen') {
                        $('#nipInput').show();
                        $('#nipInput input').val(response.result.nip);
                        $('#passInput').show();
                    } else if (response.result.roles[0].name === 'mahasiswa') {
                        $('#nimInput').show();
                        $('#nimInput input').val(response.result.nim);
                        $('#passInput').show();
                    }

                    $('.tombol-simpan').off('click').on('click', function() {
                        const name = $('#name').val();
                        const role = $('#roleSelect').val();
                        const email = $('#emailField').val();
                        const nip = $('#nipField').val();
                        const nim = $('#nimField').val();
                        const password = $('#passField').val();

                        const url = "{{ route('users.update', ':id') }}";
                        const urlEdit = url.replace(':id', id);

                        $.ajax({
                            url: urlEdit,
                            type: 'PUT',
                            data: {
                                name: name,
                                role: role,
                                email: email,
                                nip: nip,
                                nim: nim,
                                password: password,
                                _token: '{{ csrf_token() }}',
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
                                    $.each(response.errors, function(
                                        key, value) {
                                        $('#listValidation')
                                            .find('ul').append(`
                                    <li>${value}</li>
                                `);
                                    })
                                    $('#listValidation').append(
                                        '</ul>');

                                    $('#modalFormUser').modal('show');
                                } else {
                                    $('#name').val('');
                                    $('#roleSelect').val('');
                                    $('#emailField').val('');
                                    $('#nipField').val('');
                                    $('#nimField').val('');
                                    $('#passField').val('');

                                    $('#modalFormUser').modal('hide');
                                    $('#myDataTables').DataTable().ajax
                                        .reload();

                                    // Toast notifikasi
                                    const Toast = Swal.mixin({
                                        toast: true,
                                        position: 'top-end',
                                        showConfirmButton: false,
                                        timer: 4000,
                                        timerProgressBar: true,
                                        didOpen: (toast) => {
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
                                        title: 'User berhasil diupdate!'
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

        // Hapus User
        $('body').on('click', '.tombol-hapus', function(e) {
            e.preventDefault();

            const id = $(this).data('id');

            Swal.fire({
                title: 'Apakah anda yakin?',
                text: "User akan dihapus secara permanen!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Hapus',
                cancelButtonText: 'Batal',
            }).then((result) => {
                if (result.isConfirmed) {
                    const url = "{{ route('users.destroy', ':id') }}";
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
                                title: 'User berhasil dihapus!'
                            })
                        }
                    });
                }
            })
        });
    });
</script>
