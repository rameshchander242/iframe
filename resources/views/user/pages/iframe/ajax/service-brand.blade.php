@foreach ($categories as $category)
<h3 class="heading-danger">{{$category['name']}}</h3>
<div class="row">
    <div class="col-sm-6">
        <h4>Brands</h4>
        <div class="card">
            <div class="card-body div-max-height">
                <div class="list-group">
                @foreach ($category->brands as $brand)
                    <label class="list-group-item list-group-item-action">
                        {!! Form::checkbox('brand['.$category['id'].'][]', $brand['id'], false, ['id'=>'brand_'.$brand['id'], 'class="check"']) !!} &nbsp; 
                        {!! Html::image( upload_url('brand').$brand['image'], '', array('class' => 'img-thumb')) !!}
                        {{$brand['name']}}
                    </label>
                @endforeach
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6">
        <h4>Services</h4>
        <div class="card">
            <div class="card-body div-max-height">
                <div class="list-group">
                @foreach ($services as $service)
                    <label class="list-group-item list-group-item-action">
                        {!! Form::checkbox('service['.$category['id'].'][]', $service['id'], false, ['id'=>'service_'.$service['id'], 'class="check"']) !!} &nbsp; 
                        {!! Html::image( upload_url('service').$service['image'], '', array('class' => 'img-thumb')) !!}
                        {{$service['name']}}
                    </label>
                @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endforeach