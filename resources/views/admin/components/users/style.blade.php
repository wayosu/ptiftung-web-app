<link href="{{ asset('assets/admin/libs/datatables/css/dataTables.bootstrap5.min.css') }}" rel="stylesheet" />
<link href="{{ asset('assets/admin/libs/datatables/css/responsive.bootstrap5.min.css') }}" rel="stylesheet" />
<link href="{{ asset('assets/admin/libs/sweetalert2/css/sweetalert2.min.css') }}" rel="stylesheet" />

<style>
    #myDataTables {

        td,
        th {
            vertical-align: middle;
        }
    }

    .dataTables_scroll {
        overflow: auto;
    }

    div.dataTables_wrapper div.row:nth-child(1) {
        align-items: center;
        margin-bottom: .5rem;
    }

    div.dataTables_wrapper div.row:nth-child(3) {
        align-items: center;
        margin-top: 1rem;
    }

    div.dataTables_info {
        padding-top: 0 !important;
    }

    table.dataTable.dtr-inline.collapsed>tbody>tr>td.dtr-control {
        display: flex;
        align-items: center;
    }
</style>
