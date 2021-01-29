@extends('admin.layouts.app')
@section('title', 'Message Templates')

@push('custom_styles')
<link href="{{ asset('css/admin/datatables.min.css') }}" rel="stylesheet">
@endpush

@section('content')
<div class="content-header row mb-2">
    <div class="col-sm-12 text-right">
        {!! edit_button( route('email-template.create') ,'fa-plus', ' Add New' ) !!}
    </div>
</div>
<div class="table-responsive1">
    <table id="yajra_datatable" class="table table-bordered table-hover dataTable dtr-inline">
        <thead>
        <tr>
            <th>#Sr</th>
            <th>Group</th>
            <th>Subject</th>
            <th>Message Type</th>
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
            "ajax": "{{ route('email-template.listajax') }}",
            "rowCallback": function (nRow, aData, iDisplayIndex) {
                var oSettings = this.fnSettings ();
                $("td:first", nRow).html(oSettings._iDisplayStart+iDisplayIndex +1);
                return nRow;
            },
            "columns":[
                {
                    "data": "id",
                    "name": "id"
                },{
                    "data": "group",
                    "name": "group",
                    orderable: false, 
                    searchable: false

                },{
                    "data": "subject",
                    "name": "subject"
                },{
                    "data": "email_type",
                    "name": "email_type"
                },{
                    "data": "status",
                    "name": "status"
                },{
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
    });
</script>
@endpush