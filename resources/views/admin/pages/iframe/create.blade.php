@extends('admin.layouts.app')
@section('title','Create Iframe')

@section('content')
{!! Form::open(['method' => 'POST', 'route' => ['iframe.store'], 'files' => true,]) !!}
    <div class="form-group">
        {!!Form::label('name', 'Iframe Name')!!} <span class="text-danger">*</span>
        {!! Form::text('name', old('name'), ['class'=>'form-control', 'placeholder'=>'Name', 'required'=>true]) !!}
        {!! $errors->first('name', '<p class="text-danger">:message</p>') !!}
    </div>
    <div class="form-group">
        {!!Form::label('user_id', 'Client')!!} <span class="text-danger">*</span>
        {!! Form::select('user_id', $users, '', ['class'=>'form-control', 'placeholder'=>'-- Select Client --', 'id'=>'clientId', 'required'=>true]) !!}
        {!! $errors->first('user_id', '<p class="text-danger">:message</p>') !!}
    </div>
    <div class="accordion" id="accordionExample">
        <div class="card">
          <div class="card-header" id="headingLocation">
            <h5 class="mb-0">
              <a class="btn-link d-block" href="#" data-toggle="collapse" data-target="#collapseLocation" aria-expanded="true" aria-controls="collapseLocation">
                Store Locations
              </a>
            </h5>
          </div>
          <div id="collapseLocation" class="collapse show" aria-labelledby="headingLocation" data-parent="#accordionExample">
            <div class="card-body" id="location_group">
                Not any Record
            </div>
          </div>
        </div>

        <div class="card">
          <div class="card-header" id="headingCategory">
            <h5 class="mb-0">
              <a class="btn-link d-block" href="#" data-toggle="collapse" data-target="#collapseCategory" aria-expanded="false" aria-controls="collapseCategory">
                Categories
              </a>
              <button type="button" class="select_all select_all_cat btn btn-sm btn-info ">Select All <i class="fa fa-square"></i></button>
            </h5>
          </div>
          <div id="collapseCategory" class="collapse" aria-labelledby="headingCategory" data-parent="#accordionExample">
            
            <div class="card-body" id="category_group">
              Not any Record
            </div>
          </div>
        </div>

        <div class="card">
          <div class="card-header" id="headingBrand">
            <h5 class="mb-0">
              <a class="btn-link d-block" href="#" data-toggle="collapse" data-target="#collapseBrand" aria-expanded="false" aria-controls="collapseBrand">
                Brands & Services
              </a>
              <button type="button" class="select_all select_all_brand_service btn btn-sm btn-info ">Select All <i class="fa fa-square"></i></button>
            </h5>
          </div>
          <div id="collapseBrand" class="collapse" aria-labelledby="headingBrand" data-parent="#accordionExample">
            <div class="card-body bg-light" id="brand_group">
              Not any Record 
            </div>
          </div>
        </div>
    </div>
    <div class="form-group">
        {!!Form::label('iframe_color', 'Iframe Color')!!}
        {!! Form::color('iframe_color', '#bb2525', ['class'=>'form-control', 'placeholder'=>'Iframe Color']) !!}
    </div>
    <div class="form-group">
        {!!Form::label('success_page', 'Thanks Page Url')!!}
        {!! Form::text('success_page', '', ['class'=>'form-control', 'placeholder'=>'Thanks Page']) !!}
    </div>
    <div class="form-group">
        {!!Form::label('google_analytic', 'Google Analytics Code')!!}
        {!! Form::textarea('google_analytic', '', ['class'=>'form-control', 'placeholder'=>'Google Analytics Code', 'rows'=>'4']) !!}
    </div>
    <div class="form-group">
        {!!Form::label('description', 'Description')!!}
        {!! Form::textarea('description', old('description'), ['class'=>'form-control', 'placeholder'=>'Description', 'rows'=>'4']) !!}
    </div>
    <div class="form-group">
        {!!Form::label('', 'Status')!!}<br>
        {!! Form::hidden('status','0') !!}
        <div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
            {!! Form::checkbox('status', '1', true, ['class'=>'custom-control-input', 'id'=>'status']) !!}
            {!!Form::label('status', 'Publish', ['class'=>'custom-control-label'])!!}
        </div>
    </div>
    <hr>
    <div class="form-group text-center">
        {!! Form::submit('Save', ['class' => 'btn btn-primary btn-lg']) !!}
        &nbsp; &nbsp;
        {{ back_button(route('iframe.index')) }}
    </div>
{!! Form::close() !!}
@endsection

@push('custom_scripts')
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script type="text/javascript">
$(document).ready(function() {
    $('#clientId').change(function() {
        var _token = $('input[name="_token"]').val();
        var userId = $(this).val();
        $(this).before('<i class="ml-2 fa fa-spinner fa-spin"></i>');
        $.ajax({
            url: "{{ route('iframe.user.locations') }}", 
            method: "POST",
            data: {'id': userId, _token:_token},
            success: function(result){
                $("#location_group").html(result);
                $('.fa.fa-spinner.fa-spin').remove();
                $( ".sortable" ).sortable({ handle: ".cursor-grab", opacity: 0.6,  });
            }
        });
    });

    $(document).on('click', '#location-submit', function() {
        if ($('input[name="location[]"]:checked').length > 0) {
            var _token = $('input[name="_token"]').val();
            $(this).append('<i class="ml-2 fa fa-spinner fa-spin"></i>');
            $.ajax({
                url: "{{ route('iframe.categories') }}", 
                method: "POST",
                data: {_token:_token},
                success: function(result){
                    $("#category_group").html(result);
                    $('.fa.fa-spinner.fa-spin').remove();
                    $('.card-header .btn-link[data-target="#collapseCategory"').click();
                    $( ".sortable" ).sortable({ handle: ".cursor-grab", opacity: 0.6,  });
                }
            });

        }
    });

    $(document).on('click', '#category-submit', function() {
        if ($('input[name="category[]"]:checked').length > 0) {
            var catIds = [];
            $('input[name="category[]"]:checked').each( function() {
                catIds.push( $(this).val() );
            });
            var _token = $('input[name="_token"]').val();
            $(this).append('<i class="ml-2 fa fa-spinner fa-spin"></i>');
            $.ajax({
                url: "{{ route('iframe.category.brands') }}", 
                method: "POST",
                data: {'cats':catIds, _token:_token},
                success: function(result){
                    $("#brand_group").html(result);
                    $('.fa.fa-spinner.fa-spin').remove();
                    $('.card-header .btn-link[data-target="#collapseBrand"').click();
                    $( ".sortable" ).sortable({ handle: ".cursor-grab", opacity: 0.6,  });
                }
            });

        }
    });
    
    select_all('.select_all_cat', 'input[name="category[]"]');
    select_all('.select_all_brand_service', '#collapseBrand input:checkbox');
});
</script>
@endpush