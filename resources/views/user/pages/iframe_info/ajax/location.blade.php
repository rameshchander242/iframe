<div class="row">
@foreach ($locations as $location)
    <div class="col-sm-4">
        {!! Form::checkbox('location[]', $location['id'], false, ['id'=>'location_'.$location['id'], 'class="c-card"']) !!}
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