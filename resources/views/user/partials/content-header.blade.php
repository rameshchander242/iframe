<div class="content-header mb-3 navbar-white">
  <div class="container-fluid">
    <div class="row">
      <div class="col-sm-6">
        <h1 class="m-0 text-dark">@yield('title')</h1>
      </div><!-- /.col -->
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <?php $link = ""; ?>
          @if( count(Request::segments()) <= 1 && Request::segment(1) != 'dashboard')
            <li class="breadcrumb-item"><a href="{{route('front')}}">Dashboard</a></li>
          @endif
          @for($i = 1; $i <= count(Request::segments()); $i++)
            @if (Request::segment($i) == 'dashboard')
              @continue;
            @endif
            <?php  
              $b_title = Request::segment($i);
              $b_title = is_numeric ( $b_title ) ? 'View' : $b_title; 
              $b_title = ucwords( str_replace('-', ' ', str_replace('admin','Dashboard',$b_title) ) );
            ?>
            @if($i < count(Request::segments()))
              <?php $link .= "/" . Request::segment($i); ?>
              <li class="breadcrumb-item"><a href="<?= URL::to($link) ?>">{{ $b_title }}</a></li>
            @else 
              <li class="breadcrumb-item">{{ $b_title }}</li>
            @endif
          @endfor
        </ol>
      </div><!-- /.col -->
    </div><!-- /.row -->
  </div><!-- /.container-fluid -->
</div>