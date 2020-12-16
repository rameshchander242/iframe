<div class="row sortable">
@foreach ( sort_data($categories, $iframe['category'] ?? []) as $category)
    <div class="col-sm-3">
        <span class="cursor-grab"><i class="fas fa-arrows-alt"></i></span>
        {!! Form::checkbox('category[]', $category['id'], in_array($category['id'], $iframe['category'] ?? []), ['id'=>'category_'.$category['id'], 'class="c-card"']) !!}
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