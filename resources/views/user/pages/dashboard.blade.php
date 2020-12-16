@extends('user.layouts.app')
@section('title', 'Dashboard')

@section('content')
<?php $catChart = $modelChart = $serviceChart = []; ?>



<div class="container-fluid mb-4">
  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-header">
          <h5 class="m-0">Daily Opportunity</h5>
        </div>
        <div class="card-body">
          <h6 class="card-title">Average Daily Opportunity</h6>

          <p class="card-text">
            <span class="display-4 font-weight-bold">$ {{ number_format($daily_opportunity['total'], 2)}} / day</span>
          </p>
        </div>
      </div>
    </div>
  </div>
  <!-- /.row -->
</div><!-- /.container-fluid -->

<div class="container-fluid">
  <form>
  <div class="row mb-4">
    <div class="col">
      <input type="date" name="from" class="form-control" value="{{$from ?? ''}}">
    </div>
    <div class="col">
      <input type="date" name="to" class="form-control" value="{{$to ?? ''}}">
    </div>
    <div class="col">
      <input type="submit" value="Search" class="btn btn-primary">
    </div>
  </div>
  </form>
        <div class="row">
          <div class="col-sm-4">
            <canvas id="catChart" class="mb-3"></canvas>
            <div class="card">
              <div class="card-header">
                <h5 class="m-0">Lead By Categories</h5>
              </div>
              <div class="card-body p-0">
                <table class="table table-sm table-striped">
                  <tr>
                    <th>Category</th>
                    <th>Leads</th>
                  </tr>
                  @if ($categories)
                    @foreach($categories as $category)
                      <?php 
                        $catChart['label'][] = $category['category']['name'];
                        $catChart['data'][] = $category['total'];
                      ?>
                      <tr>
                        <td>{{$category['category']['name']}}</td>
                        <td>{{$category['total']}}</td>
                      </tr>
                    @endforeach
                  @else
                    <tr>
                      <td colspan="2" class="text-danger">Not Any Record</td>
                    </tr>
                  @endif
                </table>
              </div>
            </div>
          </div>
          <div class="col-sm-4">
            <canvas id="modelChart" class="mb-3"></canvas>
            <div class="card">
              <div class="card-header">
                <h5 class="m-0">Lead By Models</h5>
              </div>
              <div class="card-body p-0">
                <table class="table table-sm table-striped">
                  <tr>
                    <th>Model</th>
                    <th>Leads</th>
                  </tr>
                  @if ($items)
                    @foreach($items as $item)
                      <?php 
                        $modelChart['label'][] = $item['item']['name'];
                        $modelChart['data'][] = $item['total'];
                      ?>
                      <tr>
                        <td>{{$item['item']['name']}}</td>
                        <td>{{$item['total']}}</td>
                      </tr>
                    @endforeach
                  @else
                    <tr>
                      <td colspan="2" class="text-danger">Not Any Record</td>
                    </tr>
                  @endif
                </table>
              </div>
            </div>
          </div>
          <div class="col-sm-4">
            <canvas id="serviceChart" class="mb-3"></canvas>
            <div class="card">
              <div class="card-header">
                <h5 class="m-0">Lead By Services</h5>
              </div>
              <div class="card-body p-0">
                <table class="table table-sm table-striped">
                  <tr>
                    <th>Service</th>
                    <th>Leads</th>
                  </tr>
                  @if ($services)
                    @foreach($services as $service)
                      <?php 
                        $serviceChart['label'][] = $service['service']['name'];
                        $serviceChart['data'][] = $service['total'];
                      ?>
                      <tr>
                        <td>{{$service['service']['name']}}</td>
                        <td>{{$service['total']}}</td>
                      </tr>
                    @endforeach
                  @else
                    <tr>
                      <td colspan="2" class="text-danger">Not Any Record</td>
                    </tr>
                  @endif
                </table>
              </div>
            </div>
          </div>
        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->

<div class="row">
  <div class="col-sm-12 overflow-auto" style="max-height:570px;">
    <h3 class="text-warning border-bottom border-warning">Widget Instructions</h3>
    @foreach ($instructions as $instruction)
      <h5>{{$instruction['title']}}: </h5>
      {{$instruction['detail']}}
      <hr>
    @endforeach
  </div>
</div>
@endsection

@push('custom_scripts')
<script src="{{ asset('js/chart.js/Chart.min.js') }}"></script>
<script>
  var ctx = document.getElementById("catChart")
  ctx.height = 240;
  var data = {
    datasets: [{ 
      backgroundColor: [
        "#2ecc71",
        "#3498db",
        "#95a5a6",
        "#9b59b6",
        "#f1c40f",
        "#e74c3c",
        "#34495e"
      ],
      data: {!!json_encode($catChart['data'] ?? ['1'])!!} 
    }],
    labels: {!!json_encode($catChart['label'] ?? ['Not Record'])!!}
  };
  var myPieChart = new Chart(ctx, {
    type: 'pie',
    data: data,
    options: {}
  });
  
  var ctx = document.getElementById("modelChart")
  ctx.height = 240;
  var data = {
    datasets: [{ 
      backgroundColor: [
        "#2ecc71",
        "#3498db",
        "#95a5a6",
        "#9b59b6",
        "#f1c40f",
        "#e74c3c",
        "#34495e"
      ],
      data: {!!json_encode($modelChart['data'] ?? ['1'])!!} 
    }],
    labels: {!!json_encode($modelChart['label'] ?? ['Not Record'])!!}
  };
  var myPieChart = new Chart(ctx, {
    type: 'pie',
    data: data,
    options: {}
  });
  
  var ctx = document.getElementById("serviceChart")
  ctx.height = 240;
  var data = {
    datasets: [{ 
      backgroundColor: [
        "#2ecc71",
        "#3498db",
        "#95a5a6",
        "#9b59b6",
        "#f1c40f",
        "#e74c3c",
        "#34495e"
      ],
      data: {!!json_encode($serviceChart['data'] ?? [1])!!} 
    }],
    labels: {!!json_encode($serviceChart['label'] ?? ['Not Record'])!!}
  };
  var myPieChart = new Chart(ctx, {
    type: 'pie',
    data: data,
    options: {}
  });
</script>
@endpush
