@extends('admin.layouts.app')
@section('title', 'Brands')

@push('custom_styles')
<link href="{{ asset('css/admin/datatables.min.css') }}" rel="stylesheet">
@endpush

@section('content')
<div class="text-right mb-2">
    {!! edit_button( route('brand.create') ,'fa-plus', ' Add New' ) !!}
</div>

    <table id="yajra_datatable" class="table table-bordered table-hover dataTable dtr-inline">
        <thead>
        <tr>
            <th>#Sr</th>
            <th>Image</th>
            <th>Brand</th>
            <th>Category</th>
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
            "scrollX": true,
            "ajax": "{{ route('brand.listajax') }}",
            "rowCallback": function (nRow, aData, iDisplayIndex) {
                var oSettings = this.fnSettings ();
                $("td:first", nRow).html(oSettings._iDisplayStart+iDisplayIndex +1);
                return nRow;
            },
            "columns":[
                {
                    "data": "id",
                    "name": "id",
                }, {
                    "data": "image",
                    "name": "image", 
                    orderable: false, 
                    searchable: false
                }, {
                    "data": "name",
                    "name": "name"
                }, {
                    "data": "category",
                    "name": "category.name", 
                    orderable: false, 
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