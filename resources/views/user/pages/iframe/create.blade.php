@extends('admin.layouts.app')
@section('title','Create Iframe')

@section('content')
{!! Form::open(['method' => 'POST', 'route' => ['user.iframe.store'], 'files' => true,]) !!}
    <div class="form-group">
        {!!Form::label('name', 'Iframe Name')!!} <span class="text-danger">*</span>
        {!! Form::text('name', old('name'), ['class'=>'form-control', 'placeholder'=>'Name', 'required'=>true]) !!}
    </div>
    <div class="form-group">
        {!!Form::label('name', 'Client')!!} <span class="text-danger">*</span>
        {!! Form::select('user_id', $users, '', ['class'=>'form-control', 'placeholder'=>'-- Select Client --', 'id'=>'clientId', 'required'=>true]) !!}
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
        {{ back_button(route('user.iframe.index')) }}
    </div>
{!! Form::close() !!}
@endsection

@push('custom_scripts')
<script type="text/javascript">
$(document).ready(function() {
    $('#clientId').change(function() {
        var _token = $('input[name="_token"]').val();
        var userId = $(this).val();
        $(this).before('<i class="ml-2 fa fa-spinner fa-spin"></i>');
        $.ajax({
            url: "{{ route('user.iframe.user.locations') }}", 
            method: "POST",
            data: {'id': userId, _token:_token},
            success: function(result){
                $("#location_group").html(result);
                $('.fa.fa-spinner.fa-spin').remove();
            }
        });
    });

    $(document).on('click', '#location-submit', function() {
        if ($('input[name="location[]"]:checked').length > 0) {
            var _token = $('input[name="_token"]').val();
            $(this).append('<i class="ml-2 fa fa-spinner fa-spin"></i>');
            $.ajax({
                url: "{{ route('user.iframe.categories') }}", 
                method: "POST",
                data: {_token:_token},
                success: function(result){
                    $("#category_group").html(result);
                    $('.fa.fa-spinner.fa-spin').remove();
                    $('.card-header .btn-link[data-target="#collapseCategory"').click();
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
                url: "{{ route('user.iframe.category.brands') }}", 
                method: "POST",
                data: {'cats':catIds, _token:_token},
                success: function(result){
                    $("#brand_group").html(result);
                    $('.fa.fa-spinner.fa-spin').remove();
                    $('.card-header .btn-link[data-target="#collapseBrand"').click();
                }
            });

        }
    });

});
</script>
@endpush