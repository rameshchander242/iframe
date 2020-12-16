@extends('admin.layouts.app')
@section('title', 'Clients')

@push('custom_styles')
<link href="{{ asset('css/admin/datatables.min.css') }}" rel="stylesheet">
@endpush

@section('content')
    <div class="text-right mb-2">
        {!! edit_button( route('client.create') ,'fa-plus', ' Add New' ) !!}
    </div>

<div class="table-responsive1">
    <table id="yajra_datatable" class="table table-bordered table-hover dataTable dtr-inline">
        <thead>
        <tr>
            <th>Sr</th>
            <th>Name</th>
            <th>Image</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Status</th>
            <th>Action</th>
        </tr> 
        </thead>
    </table>
</div>
@endsection

@push('custom_scripts')
<script src="{{ asset('js/admin/jquery.dataTables.min.js') }}"></script>
<script type="text/javascript">
    function initDataTable() {
        var YajraDataTable = $('#yajra_datatable').DataTable({
            "processing": true,
            "serverSide": true,
            "scrollX": true,
            "ajax": "{{ route('client.listajax') }}",
            "rowCallback": function (nRow, aData, iDisplayIndex) {
                var oSettings = this.fnSettings ();
                $("td:first", nRow).html(oSettings._iDisplayStart+iDisplayIndex +1);
                return nRow;
            },
            "columns":[
                {
                    "data": "id",
                    "name": "id"
                },
                {
                    "data": "name",
                    "name": "name"
                },
                {
                    "data": "image",
                    "name": "image", 
                    orderable: false, 
                },
                {
                    "data": "email",
                    "name": "email"
                },
                {
                    "data": "phone",
                    "name": "phone"
                },
                {
                    "data": "status",
                    "name": "status"
                },
                {
                    data: 'action', 
                    name: 'action', 
                    orderable: false, 
                    searchable: false
                },
            ],
            'order': [0, 'desc'], // Order on init. Number is the column, starting at 0
        });
        return YajraDataTable;
    }

    $(document).ready(function() {
        var YajraDataTable = initDataTable();
    });
</script>
@endpush