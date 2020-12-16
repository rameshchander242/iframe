@extends('admin.layouts.app')
@section('title', 'Locations')

@push('custom_styles')
<link href="{{ asset('css/admin/datatables.min.css') }}" rel="stylesheet">
@endpush

@section('content')
<div class="content-header row mb-2">
    <div class="col-sm-4">
        {!! Form::select('user_id', $users, '', ['class'=>'form-control', 'id'=>'user_id', 'placeholder'=>'-- Select Client --']) !!}
    </div>
    <div class="col-sm-8 text-right">
        {!! edit_button( route('location.create') ,'fa-plus', ' Add New' ) !!}
    </div>
</div>

<div class="table-responsive1">
    <table id="yajra_datatable" class="table table-bordered table-hover dataTable dtr-inline">
        <thead>
        <tr>
            <th>#Sr</th>
            <th>Store Name</th>
            <th>City</th>
            <th>Address</th>
            <th>Client</th>
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
            "ajax": "{{ route('location.listajax') }}",
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
                    "data": "store_name",
                    "name": "store_name"
                },
                {
                    "data": "city",
                    "name": "city"
                },
                {
                    "data": "address_1",
                    "name": "address_1"
                },
                {
                    "data": "user",
                    "name": "user.name", 
                },
                {
                    "data": "status",
                    "name": "status"
                },
                {
                    data: 'action', 
                    name: 'action', 
                    orderable: false, 
                    searchable: true
                },
            ],
            'order': [0, 'asc'],
        });
        return YajraDataTable;
    }

    $(document).ready(function() {
        var YajraDataTable = initDataTable();
        $('#user_id').change( function() {
            YajraDataTable.columns( 4 ).search( this.value ).draw();
        });
    });
</script>
@endpush