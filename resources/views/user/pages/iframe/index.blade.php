@extends('user.layouts.app')
@section('title', 'Price List')

@push('custom_styles')
<link href="{{ asset('css/admin/datatables.min.css') }}" rel="stylesheet">
@endpush

@section('content')
    <table id="yajra_datatable" class="table table-bordered table-hover dataTable dtr-inline">
        <thead>
        <tr>
            <th>#ID</th>
            <th>Price List</th>
            <th>Client</th>
            <th>Status</th>
            <th>Action</th>
        </tr> 
        </thead>
    </table>

@endsection

@push('custom_scripts')
<script src="{{ asset('js/admin/jquery.dataTables.min.js') }}"></script>
<script type="text/javascript">
    function initDataTable() {
        var YajraDataTable = $('#yajra_datatable').DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": "{{ route('user.iframe.listajax') }}",
            "columns":[
                {
                    "data": "id",
                    "name": "id"
                }, {
                    "data": "name",
                    "name": "name"
                }, {
                    "data": "user",
                    "name": "user",
                    orderable: false, 
                    searchable: false
                }, {
                    "data": "status",
                    "name": "status"
                }, {
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