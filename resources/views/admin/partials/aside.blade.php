<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{route('admin.dashboard')}}" class="brand-link bg-light">
      <img src="{{ asset('images/logo.png') }}" alt="Repair Lift" class="brand-image"
           style="opacity: .8">
      <span class="brand-text font-weight-light">Admin Panel</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-2 pb-2 mb-2 d-flex">
        <div class="image">
          <i class="fa fa-user text-light fa-2x"></i>
        </div>
        <div class="info">
          <a href="#" class="d-block">{{ Auth::user()->name }}</a>
        </div>
      </div>

      <?php
      $navs = [
        'dashboard' => ['text'=>'Dashboard', 'icon'=>'tachometer-alt', 'link'=>'admin.dashboard'],
        'category'  => ['text'=>'Category', 'icon'=>'list-alt', 'link'=>'category.index'],
        'brand'     => ['text'=>'Brand', 'icon'=>'landmark', 'link'=>'brand.index'],
        'series'    => ['text'=>'Series', 'icon'=>'book', 'link'=>'series.index'],
        'item'    => ['text'=>'Items', 'icon'=>'mobile', 'link'=>'item.index'],
        'service' => ['text'=>'Services', 'icon'=>'users-cog', 'link'=>'service.index'],
        'client'  => ['text'=>'Clients', 'icon'=>'users', 'link'=>'client.index'],
        'location'=> ['text'=>'Locations', 'icon'=>'building', 'link'=>'location.index'],
        'email-template'  => ['text'=>'Message Template', 'icon'=>'building', 'link'=>'email-template.index'],
        'iframe'    => ['text'=>'Iframes', 'icon'=>'archive', 'link'=>'iframe.index'],
        'instruction'=> ['text'=>'Instructions', 'icon'=>'building', 'link'=>'instruction.index'],
        'error-log' => ['text'=>'Errors', 'icon'=>'building', 'link'=>'error-log.index'],
        'queries'   => ['text'=>'Queries', 'icon'=>'envelope', 'link'=>'admin.queries.index'],
        'profile'   => ['text'=>'Profile', 'icon'=>'user', 'link'=>'admin.profile'],
        'setting'   => ['text'=>'Setting', 'icon'=>'user', 'link'=>'setting.index'],
    ];
      ?>
      <!-- Sidebar Menu -->

      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          @foreach($navs as $k_nav=>$nav)
            <?php 
            $active = false; 
            $subNav = '';
            ob_start(); 
            ?>
            @if( isset($nav['subnav']) && is_array($nav['subnav']) )
              <ul class="nav nav-treeview subnav">
              @foreach($nav['subnav'] as $subnav)
                @if (strpos(Route::currentRouteName(), $subnav['link']) !== false || strpos(Route::currentRouteName(), $k_nav.'.') !== false)
                  <?php $active = true; ?>
                @endif
                <li class="nav-item {{ (strpos(Route::currentRouteName(), $subnav['link']) === 0) ? 'active' : '' }}">
                  <a href="{{ route($subnav['link']) }}" class="nav-link">
                    <i class="nav-icon fas fa-{{ $subnav['icon'] }}"></i>
                    <p>{{$subnav['text']}}</p>
                  </a>
                </li>
              @endforeach
              </ul>
            @endif
            <?php $subNav = ob_get_clean(); ?>

            <li class="nav-item has-treeview {{ ( strpos(Route::currentRouteName(), $nav['link']) === 0 || $active) ? 'active menu-open' : '' }}">
              <a href="{{ $nav['link'] == '#' ? $nav['link'] : route($nav['link']) }}" class="nav-link">
                <i class="nav-icon fas fa-{{ $nav['icon'] }}"></i>
                <p>{{$nav['text']}} @if(isset($nav['subnav']))<i class="right fas fa-angle-left"></i>@endif</p>
              </a>
              {!! $subNav !!}
            </li>
          @endforeach
          
          <li class="nav-item has-treeview">
            <a href="{{ route('admin.logout') }}" class="nav-link text-danger font-weight-bold">
              <i class="nav-icon fas fa-sign-out-alt"></i>
              <p>Logout</p>
            </a>
          </li>

        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>