<style>
.sortable td.text-nowrap {
    padding-left: 36px;
}
.sortable td span.cursor-grab {
    left: 0;
}
</style>

<div class="card">
  <div class="card-body table-responsive p-1">
      <ul class="nav nav-tabs">
          @foreach ($iframe['categories'] as $key=>$category)
          <li class="nav-item">
              <a class="nav-link bg-warning {{$key==0 ? 'active': ''}}" data-toggle="tab" href="#cat_{{$iframe['id']}}_{{$l_key}}_category{{$category['id']}}">{{$category['name']}}</a>
          </li>
          @endforeach
      </ul>
      <div class="tab-content">
          @foreach ($iframe['categories'] as $key=>$category)
          <?php $b = 0; ?>
          <div id="cat_{{$iframe['id']}}_{{$l_key}}_category{{$category['id']}}" class="col-12 tab-pane {{$key==0 ? 'active': ''}}"><br>
            <h3>{{$category['name']}}</h3>
            <table class="table sortable">
              @foreach ($category['brands'] as $brand)
                  <?php $b++; ?>
                  <thead>
                    <tr>
                      <th>{{$brand['name']}}</th>
                      @foreach ($category['services'] as $service)
                          <th>{{$service['name']}}</th>
                      @endforeach
                    </tr>
                  </thead>
                  <tbody>
                  <?php $brand_items = sort_data($brand['items']->toArray(), json_decode($iframe['item_service'], true)[$category['id']] ?? [], 'key'); ?>
                  @foreach ($brand_items as $item)
                    @if( in_array($item['id'], $iframe['item'][$category['id']] ?? []) )
                      <tr>
                          <td class="text-nowrap">
                            <span class="cursor-grab"><i class="fas fa-arrows-alt"></i></span>
                            {{$item['name']}}
                          </td>
                          @foreach ($iframe['categories'][$key]['services'] as $service)
                          <td>
                              <?php $item_service = json_decode($iframe['item_service'], true); ?>
                              <input class="form-control text-center input-sm" type="text" name="item_service[{{$category['id']}}][{{$item['id']}}][{{$service['id']}}][{{$l_key}}]" value="{{$item_service[$category['id']][$item['id']][$service['id']][$l_key] ?? ''}}">
                          </td>
                          @endforeach
                      </tr>
                    @endif
                  @endforeach
                  </tbody>  
              @endforeach
              @if ($b == 0)
                <thead>
                    <tr>
                        <th> -- </th>
                        @foreach ($category['services'] as $service)
                            <th>{{$service['name']}}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                <?php $category_items = sort_data($category['items']->toArray(), json_decode($iframe['item_service'], true)[$category['id']] ?? [], 'key'); ?>
                @foreach ($category_items as $item)
                  @if( in_array($item['id'], $iframe['item'][$category['id']] ?? []) )
                    <tr>
                        <td class="text-nowrap">
                            <span class="cursor-grab"><i class="fas fa-arrows-alt"></i></span>
                            {{$item['name']}}
                        </td>
                        @foreach ($iframe['categories'][$key]['services'] as $service)
                        <td>
                            <?php $item_service = json_decode($iframe['item_service'], true); ?>
                            <input class="form-control text-center input-sm" type="text" name="item_service[{{$category['id']}}][{{$item['id']}}][{{$service['id']}}][{{$l_key}}]" value="{{$item_service[$category['id']][$item['id']][$service['id']][$l_key] ?? ''}}">
                        </td>
                        @endforeach
                    </tr>
                  @endif
                @endforeach
                </tbody>
              @endif
              
            </table>
          </div>
          @endforeach
      </div>

  </div>

</div>