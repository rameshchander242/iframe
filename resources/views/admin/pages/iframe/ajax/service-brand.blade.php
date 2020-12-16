@foreach ($categories as $category)
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