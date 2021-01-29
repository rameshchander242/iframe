@extends('user.layouts.app')
@section('title', 'Repair Info')

@push('custom_styles')
<link href="{{ asset('css/admin/datatables.min.css') }}" rel="stylesheet">
@endpush

@section('content')
<div class="row mb-3">
    <div class="col">
        {!! Form::select('iframe', $iframes, key($iframes), ['class'=>'form-control', 'placeholder'=>'-- Select Price List --', 'id'=>'iframe']) !!}
    </div>
    <div class="col">
        {!! Form::select('location_id', $locations, key($locations), ['class'=>'form-control', 'placeholder'=>'-- Select Location --', 'id'=>'location']) !!}
    </div>
    <div class="col">
        {!! Form::select('category_id', $categories, '', ['class'=>'form-control', 'placeholder'=>'-- Select Category --', 'id'=>'category']) !!}
    </div>
    <div class="col">
        {!! Form::select('brand_id', $brands, '', ['class'=>'form-control', 'placeholder'=>'-- Select Brand --', 'id'=>'brand']) !!}
    </div>
</div>
<div class="table-responsive1">
    <table id="yajra_datatable" class="table table-bordered table-hover dataTable dtr-inline">
        <thead>
        <tr>
            <th>#Sr</th>
            <th>Price list</th>
            <th>Location</th>
            <th>Category</th>
            <th>Brand</th>
            <th>Series</th>
            <th>Item</th>
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
        var YajraDataTable = $('#yajra_datatable').on('preXhr.dt', function ( e, settings, data ) {
				data.columns[1]['search']['value'] = $('#iframe').val();
				data.columns[2]['search']['value'] = $('#location').val();
			} ).DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": "{{ route('user.iframe_info.listajax') }}",
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
                    "data": "iframe",
                    "name": "iframe.id",
                    visible: false,
                }, {
                    "data": "location",
                    "name": "location.store_name"
                }, {
                    "data": "category",
                    "name": "category.name"
                }, {
                    "data": "brand",
                    "name": "brand.name"
                }, {
                    "data": "series",
                    "name": "series.name"
                }, {
                    "data": "item",
                    "name": "item.name"
                }, {
                    data: 'action', 
                    name: 'action', 
                    orderable: false,
                    searchable: false
                },
            ],
            'order': [0, 'desc'],
        });
        return YajraDataTable;
    }

    $(document).ready(function() {
        var YajraDataTable = initDataTable();
        $('#iframe').on('change', function(){
            YajraDataTable.column(1).search( this.value ).draw();
        });
        $('#location').on('change', function(){
            YajraDataTable.column(2).search( this.value ).draw();
        });
        $('#category').on('change', function(){
            var _token = "{{ csrf_token() }}";
            var catName = this.value;
            $(this).prev('label').append('<i class="ml-2 fa fa-spinner fa-spin"></i>');
            $.ajax({
                url: "{{ route('ajax.category-brands') }}", 
                method: "POST",
                data: {'name': catName, _token:_token},
                success: function(result){
                    $( "select#brand" ).replaceWith( result );
                }
            });

            YajraDataTable.column(3).search( this.value ).column(4).search( '' ).draw();
        });
        $(document).on('change', '#brand', function(){
            YajraDataTable.column(4).search( this.value ).draw();
        });
        
        // $("#iframe").val($("#iframe option:eq(1)").val()).change();
        // $("#location").val($("#location option:eq(1)").val()).change();
    });
</script>

@endpush