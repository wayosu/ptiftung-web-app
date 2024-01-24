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
                [2, 'desc']
            ],
            processing: true,
            serverSide: true,
            ajax: "{{ route('users.indexAjax') }}",
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
    });
</script>
