@extends('user.layouts.app')
@section('title', 'Leads')

@push('custom_styles')
<link href="{{ asset('css/admin/datatables.min.css') }}" rel="stylesheet">
@endpush

@section('content')
<div class="row">
    <div class="col-sm-4">
        {!! Form::select('location', $locations, '', ['id'=>'location', 'placeholder'=>'-- Select Location --', 'class'=>'form-control']) !!}
    </div>
    <div class="col-sm-4">
        {!! Form::select('category', $categories, '', ['id'=>'category', 'placeholder'=>'-- Select Category --', 'class'=>'form-control']) !!}
    </div>
    <div class="col-sm-4">
        {!! Form::select('service', $services, '', ['id'=>'service', 'placeholder'=>'-- Select Service --', 'class'=>'form-control']) !!}
    </div>
</div>
<br>
    <table id="yajra_datatable" class="table table-bordered table-hover dataTable dtr-inline">
        <thead>
        <tr>
            <th>#Sr</th>
            <th>Location</th>
            <th>Category</th>
            <th>Item</th>
            <th>Service</th>
            <th>Name</th>
            <th>Phone</th>
            <th>Email</th>
            <th>Created</th>
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
            "ajax": "{{ route('user.queries.ajax') }}",
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
                    "data": "location",
                    "name": "location.store_name", 
                }, {
                    "data": "category",
                    "name": "category.name", 
                }, {
                    "data": "item",
                    "name": "item.name", 
                }, {
                    "data": "service",
                    "name": "service.name", 
                }, {
                    "data": "fullname",
                    "name": "fullname"
                }, {
                    "data": "phone",
                    "name": "phone"
                }, {
                    "data": "email",
                    "name": "email"
                }, {
                    "data": "created_at",
                    "name": "created_at"
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
        $('#location').change( function() {
            YajraDataTable.columns( 1 ).search( this.value ).draw();
        });
        $('#category').change( function() {
            YajraDataTable.columns( 2 ).search( this.value ).draw();
        });
        $('#service').change( function() {
            YajraDataTable.columns( 4 ).search( this.value ).draw();
        });
    });
</script>

@endpush