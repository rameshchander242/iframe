@extends('admin.layouts.app')
@section('title', 'Series')

@push('custom_styles')
<link href="{{ asset('css/admin/datatables.min.css') }}" rel="stylesheet">
@endpush

@section('content')
<div class="text-right mb-2">
    {!! edit_button( route('series.create') ,'fa-plus', ' Add New' ) !!}
</div>
<div class="content-header row">
    <div class="col-sm-4">
    {!! Form::select('category', $categories, '', ['class'=>'form-control', 'id'=>'category', 'placeholder'=>'-- Select Category --']) !!}
    </div>
    <div class="col-sm-4">
    {!! Form::select('brand', $brands, '', ['class'=>'form-control', 'id'=>'brand', 'placeholder'=>'-- Select Brand --']) !!}
    </div>
</div>

<div class="table-responsive1">
    <table id="yajra_datatable" class="table table-bordered table-hover dataTable dtr-inline">
        <thead>
        <tr>
            <th>#Sr</th>
            <th>Image</th>
            <th>Series</th>
            <th>Category</th>
            <th>Brand</th>
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
            "ajax": "{{ route('series.listajax') }}",
            "rowCallback": function (nRow, aData, iDisplayIndex) {
                var oSettings = this.fnSettings ();
                $("td:first", nRow).html(oSettings._iDisplayStart+iDisplayIndex +1);
                return nRow;
            },
            "columns":[
                {
                    "data": "id",
                    "name": "id",
                },
                {
                    "data": "image",
                    "name": "image", 
                    orderable: false
                },
                {
                    "data": "name",
                    "name": "name"
                },
                {
                    "data": "category",
                    "name": "category.name", 
                },
                {
                    "data": "brand",
                    "name": "brand.name", 
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
        $('#category').change( function() {
            YajraDataTable.columns( 3 ).search( this.value ).draw();
        });
        $('#brand').change( function() {
            YajraDataTable.columns( 4 ).search( this.value ).draw();
        });
    });
</script>
@endpush