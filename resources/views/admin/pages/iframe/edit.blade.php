@extends('admin.layouts.app')
@section('title','Edit Iframe')

@section('content')
{!! Form::open(['method' => 'PUT', 'route' => ['iframe.update', $iframe['id']], 'files' => true,]) !!}
    <div class="form-group">
        {!!Form::label('name', 'Iframe Name')!!} <span class="text-danger">*</span>
        {!! Form::text('name', $iframe['name'], ['class'=>'form-control', 'placeholder'=>'Name', 'required'=>true]) !!}
        {!! $errors->first('name', '<p class="text-danger">:message</p>') !!}
    </div>
    <div class="form-group">
        {!!Form::label('user_id', 'Client')!!} <span class="text-danger">*</span>
        {!! Form::select('user_id', $users, $iframe['user_id'], ['class'=>'form-control', 'placeholder'=>'-- Select Client --', 'id'=>'clientId', 'required'=>true]) !!}
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
                <div class="row sortable">
                    @foreach ( sort_data($locations, $iframe['location']) as $location)
                        <div class="col-sm-4">
                            <span class="cursor-grab"><i class="fas fa-arrows-alt"></i></span>
                            {!! Form::checkbox('location[]', $location['id'], in_array($location['id'], $iframe['location']), ['id'=>'location_'.$location['id'], 'class="c-card"']) !!}
                            <label for="location_{{$location['id']}}" class="d-block">
                            <div class="card">
                                <div class="card-body">
                                    <div class="card-state-icon"><i class="fa fa-check"></i></div>
                                        <h4>{{$location['store_name']}} ({{$location['city']}})</h4>
                                        <h5>Phone: {{$location['phone']}}</h5>
                                        <h5>Email: {{$location['email']}}</h5>
                                        <p class="small-meta">{{$location['address_1']}}</p>
                                </div>
                            </div>
                            </label>
                        </div>
                    @endforeach
                </div>
                <div class="form-group text-right">
                  <button type="button" id="location-submit" class="btn btn-primary">Next Step</button>
                </div>
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
              <div class="row sortable">
                @foreach ( sort_data($categories, $iframe['category']) as $category)
                    <div class="col-sm-3">
                        <span class="cursor-grab"><i class="fas fa-arrows-alt"></i></span>
                        {!! Form::checkbox('category[]', $category['id'], in_array($category['id'], $iframe['category']), ['id'=>'category_'.$category['id'], 'class="c-card"']) !!}
                        <label for="category_{{$category['id']}}" class="d-block">
                        <div class="card text-center">
                            <div class="card-body">
                                <div class="card-state-icon"><i class="fa fa-check"></i></div>
                                    {!! Html::image( upload_url('category').$category['image'], '', array('class' => 'img-thumbnail')) !!}
                                    <h4 class="mt-2">{{$category['name']}}</h4>
                            </div>
                        </div>
                        </label>
                    </div>
                @endforeach
              </div>
              <div class="form-group text-right">
                <button type="button" id="category-submit" class="btn btn-primary">Next Step</button>
              </div>
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
              @foreach ($catBrands as $category)
                <?php //$category['brands'] = sort_data($category['brands'], $iframe['brand'][$category['id']]); ?>
              
                <h3 class="heading-danger">{{$category['name']}}</h3>
                <div class="row">
                    <div class="col-sm-6">
                        <h4>Brands</h4>
                        <div class="card">
                            <div class="card-body div-max-height">
                              <div class="list-group sortable">
                               @if ($category['brands'])
                                  @foreach (sort_data($category['brands'], $iframe['brand'][$category['id']] ?? []) as $brand)
                                    <label class="list-group-item list-group-item-action">
                                      <span class="cursor-grab"><i class="fas fa-arrows-alt"></i></span>
                                      <?php $checked = in_array($brand['id'], $iframe['brand'][$category['id']] ?? []); ?>
                                        {!! Form::checkbox('brand['.$category['id'].'][]', $brand['id'], $checked, ['id'=>'brand_'.$brand['id'], 'class="check"']) !!} 
                                        &nbsp; 
                                        {!! Html::image( upload_url('brand').$brand['image'], '', array('class' => 'img-thumb')) !!}
                                        {{$brand['name']}}
                                        <input type="button" class="btn btn-primary float-right mt-2" value="Items" data-toggle="modal" data-target="#brand_popup_{{$category['id']}}_{{$brand['id']}}">
                                    </label>
                                    @include('admin.pages.iframe.popup_item_list')
                                    @push('custom_scripts')
                                    <script>
                                      select_all('#brand_popup_{{$category['id']}}_{{$brand['id']}} .select_all_items', '#brand_popup_{{$category['id']}}_{{$brand['id']}} input:checkbox');
                                    </script>
                                    @endpush
                                 @endforeach
                                @else
                                  <?php $brand = ['id'=>false, 'series'=>false, 'items'=>false]; ?>
                                  <input type="button" class="btn btn-primary float-right mt-2" value="Items" data-toggle="modal" data-target="#brand_popup_{{$category['id']}}_{{$brand['id']}}">
                                  @include('admin.pages.iframe.popup_item_list')
                                  @push('custom_scripts')
                                  <script>
                                    select_all('#brand_popup_{{$category['id']}}_{{$brand['id']}} .select_all_items', '#brand_popup_{{$category['id']}}_{{$brand['id']}} input:checkbox');
                                  </script>
                                  @endpush
                                @endif
                              </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <h4>Services</h4>
                        <div class="card">
                            <div class="card-body div-max-height">
                                <div class="list-group sortable">
                                  @foreach (sort_data($services, $iframe['service'][$category['id']] ?? []) as $service)
                                    <label class="list-group-item list-group-item-action">
                                        <span class="cursor-grab"><i class="fas fa-arrows-alt"></i></span>
                                        {!! Form::checkbox('service['.$category['id'].'][]', $service['id'], in_array($service['id'], ($iframe['service'][$category['id']] ?? [])), ['id'=>'service_'.$service['id'], 'class="check"']) !!} &nbsp; 
                                        <i class="w-30 fa-lg {{$service['icon']}}"></i>
                                        {{$service['name']}}
                                    </label>
                                  @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
              @endforeach
            </div>
          </div>
        </div>
    </div>
    <div class="form-group">
        {!!Form::label('iframe_color', 'Iframe Color')!!}
        {!! Form::color('iframe_color', $iframe['iframe_color'], ['class'=>'form-control', 'placeholder'=>'Iframe Color']) !!}
    </div>
    <div class="form-group">
        {!!Form::label('success_page', 'Thanks Page Url')!!}
        {!! Form::text('success_page', $iframe['success_page'], ['class'=>'form-control', 'placeholder'=>'Thanks Page']) !!}
    </div>
    <div class="form-group">
        {!!Form::label('google_analytic', 'Google Analytics Code')!!}
        {!! Form::textarea('google_analytic', $iframe['google_analytic'], ['class'=>'form-control', 'placeholder'=>'Google Analytics Code', 'rows'=>'4']) !!}
    </div>
    <div class="form-group">
        {!!Form::label('description', 'Description')!!}
        {!! Form::textarea('description', $iframe['description'], ['class'=>'form-control', 'placeholder'=>'Description', 'rows'=>'4']) !!}
    </div>
    <div class="form-group">
        {!!Form::label('', 'Status')!!}<br>
        {!! Form::hidden('status','0') !!}
        <div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
            {!! Form::checkbox('status', '1', $iframe['status']==1, ['class'=>'custom-control-input', 'id'=>'status']) !!}
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
  
  $( ".sortable" ).sortable({ handle: ".cursor-grab", opacity: 0.6,  });

    $('#clientId').change(function() {
        var _token = $('input[name="_token"]').val();
        var userId = $(this).val();
        $(this).before('<i class="ml-2 fa fa-spinner fa-spin"></i>');
        $.ajax({
            url: "{{ route('iframe.user.locations') }}", 
            method: "POST",
            data: {'id': userId, 'w_id':{{$iframe['id']}}, _token:_token},
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
                data: {_token:_token, 'w_id':{{$iframe['id']}}},
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
                data: {'cats':catIds, 'w_id':{{$iframe['id']}}, _token:_token},
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