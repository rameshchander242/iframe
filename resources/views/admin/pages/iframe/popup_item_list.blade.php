<div class="modal brand_popup" id="brand_popup_{{$category['id']}}_{{$brand['id']}}">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title">List of Items &nbsp; <button type="button" class="select_all select_all_items btn btn-sm btn-info ">Select All <i class="fa fa-square"></i></button></h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <!-- Modal body -->
      <div class="modal-body iframe_items">
        @if ($brand['series'])
          <div class="row">
            @foreach ($brand['series'] as $series)
              <div class="col-sm-6">
                <div class="card">
                  <div class="card-header"> 
                    <?php 
                      $checked = in_array($series['id'], $iframe['series'][$category['id']][$brand['id']] ?? []); ?>
                    <label class="select_series_items">
                    {!! Form::checkbox('series['.$category['id'].']['.$brand['id'].'][]', $series['id'], $checked, ['id'=>'series_item_'.$series['id'], 'class'=>"check", 'onchange'=>'select_all_checkbox(this, ".series_item_'.$series['id'].'")']) !!} 
                    {{$series['name']}} 
                    </label>
                  </div>
                  <div class="card-body p-0 series_item_{{$series['id']}}">
                    @if($series['items'])
                      @foreach ($series['items'] as $item)
                        <?php $checked = in_array($item['id'], $iframe['item'][$category['id']] ?? []); ?>
                        <label class="list-group-item list-group-item-action">
                            {!! Form::checkbox('item['.$category['id'].'][]', $item['id'], $checked, ['id'=>'item_'.$item['id'], 'class="check"']) !!} 
                            <img src="{{asset($item['image'])}}" class="img-thumb" alt="">
                            {{$item['name']}}
                        </label>
                      @endforeach
                    @endif
                  </div>
                </div>
              </div>
            @endforeach
          </div>
        @elseif($brand['items'])
          @foreach ($brand['items'] as $item)
            <?php $checked = in_array($item['id'], $iframe['item'][$category['id']] ?? []); ?>
            <label class="list-group-item list-group-item-action">
                {!! Form::checkbox('item['.$category['id'].'][]', $item['id'], $checked, ['id'=>'item_'.$item['id'], 'class="check"']) !!} 
                <img src="{{asset($item['image'])}}" class="img-thumb" alt="">
                {{$item['name']}}
            </label>
          @endforeach
        @elseif(!$brand['id'] && $category['items'])
          @foreach ($category['items'] as $item)
            <?php 
            $checked = in_array( $item['id'], $iframe['item'][$category['id']] ?? [] ); ?>
            <label class="list-group-item list-group-item-action">
                {!! Form::checkbox('item['.$category['id'].'][]', $item['id'], $checked, ['id'=>'item_'.$item['id'], 'class'=>"check"]) !!} 
                <img src="{{asset($item['image'])}}" class="img-thumb" alt="">
                {{$item['name']}}
            </label>
          @endforeach
        @endif
      </div>

      <!-- Modal footer -->
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Submit</button>
      </div>

    </div>
  </div>
</div>