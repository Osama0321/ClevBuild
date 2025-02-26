<!DOCTYPE html>
<html lang="en">

<head>
  <title>@isset($title) {{$title}} - @endif {{ ($globalsettings->getValue('site_title')) ?
    $globalsettings->getValue('site_title') : config('app.name', 'Laravel') }}</title>
 {!! RecaptchaV3::initJs() !!}
  <script src="/js/front/leaflet.min.js"></script>
  <meta name="firebase_ip" content="{{ route('notification.store') }}">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  @yield('meta')
  <meta property="og:url" content="{{Request::url()}}">
  <meta property="og:type" content="website">
  <meta name="copyright"
    content="Copyright {{date('Y')}} © {{ ($globalsettings->getValue('site_title')) ? $globalsettings->getValue('site_title') : config('app.name', 'Laravel') }}. Created by Geeksroot" />
  <meta name="language" content="en">
  <meta name="format-detection" content="telephone=no">
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta http-equiv="X-UA-Compatible" content="ie=edge" />
  <meta name="apple-mobile-web-app-status-bar" content="#db4938" />
  <meta name="theme-color" content="#db4938" />
  @if (Str::startsWith($current = url()->current(), 'https://www'))
  <link rel="canonical" href="{{ str_replace('https://www.', 'https://', $current) }}">
  @else
  <link rel="canonical" href="{{ str_replace('https://', 'https://www.', $current) }}">
  @endif
  @if($globalsettings->getValue('site_fav'))
  <link rel="icon" type="image/x-icon" href="{{$globalsettings->getValue('site_fav')}}">
  @endif
  <link rel="manifest"
    href="/manifest.php?name={{ ($globalsettings->getValue('site_title')) ? $globalsettings->getValue('site_title') : config('app.name', 'Laravel') }}&ser={!! str_replace('"', "'", serialize($icons)) !!}" />
  <!-- ios support -->
  @foreach($icons as $icon)
  <link rel="apple-touch-icon" href="{{$icon['src']}}" />
  @endforeach
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;300;400;500;700;900&family=Saira+Condensed:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="/css/front/style.min.css" />
  <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="https://kit.fontawesome.com/bf7b09a514.js" crossorigin="anonymous"></script>

  @if($globalsettings->getValue('header_scripts'))
  {!!$globalsettings->getValue('header_scripts')!!}
  @endif

  <script src="/js/front/app.min.js"></script>
  <style>
    section.MapFilter form button {
      position: absolute;
      right: 12px;
      height: 100%;
      width: 75px;
      background: transparent;
      color: #fff;
      border: none;
      border-top-right-radius: 9px;
      border-bottom-right-radius: 9px;
    }

    .FormArea .input-field {
      position: relative;
    }
  </style>

</head>
@if($globalsettings->getValue('home_page') && isset($pages) && $pages->slug == $globalsettings->getValue('home_page'))

<body class="home {{ (isset($body_class)) ? $body_class : '' }}">
  @else

  <body class="draw-close {{ (isset($body_class)) ? $body_class : '' }}">
    @endif

    <header id="masthead" class="site-header navbar-static-top navbar-light" role="banner">
      <div class="container-fluid">
        <nav class="navbar navbar-expand-xl p-0">
          <div class="navbar-brand">
            <a href="{{route('home')}}">
              @if($globalsettings->getValue('site_logo'))
              <img src="{{ $globalsettings->getValue('site_logo') }}"
                alt="{{ ($globalsettings->getValue('site_title')) ? $globalsettings->getValue('site_title') : config('app.name', 'Laravel') }}"
                style="max-width: 70px  !important;">
              @else
              {{ ($globalsettings->getValue('site_title')) ? $globalsettings->getValue('site_title') :
              config('app.name', 'Laravel') }}
              @endif
            </a>
          </div>
          <button type="button" class="drawer-toggle drawer-hamburger">
            <span class="sr-only">toggle navigation</span>
            <span class="drawer-hamburger-icon"></span>
          </button>
          <div id="main-nav" class="collapse navbar-collapse justify-content-end">
            <ul id="menu-main-menu" class="navbar-nav">
              @if(isset($primarymenu) && !empty($primarymenu))
              @foreach($primarymenu as $menu)
              <li class="nav-item" id="{{$menu->attr_id}}">
                <a href="{{get_url($menu->link)}}" class="nav-link  {{$menu->attr_class}}">
                  @if($menu->attr_class == 'btn-custom')
                  <span class="text">{{$menu->title}}</span> <span class="vector">
                    <img class="bf-hover" src="{{ asset('images/front/vc_btn-banner.png') }}">
                    <img class="aft-hover" src="{{ asset('images/front/vc_btn-banner_pink.png') }}">
                  </span>
                  @else {{$menu->title}}
                  @endif
                </a>
              </li>
              @endforeach

              @endif
            </ul>
            @if($globalsettings->getValue('sidebar_logo'))
            <div class="menu_logo">
              <figure class="m-0">
                <img src="{{$globalsettings->getValue('sidebar_logo')}} "
                  alt="{{ ($globalsettings->getValue('site_title')) ? $globalsettings->getValue('site_title') : config('app.name', 'Laravel') }}">
              </figure>
              <h5>{{ ($globalsettings->getValue('site_title')) ? $globalsettings->getValue('site_title') :
                config('app.name', 'Laravel') }}</h5>
            </div>
            @endif
            <div class="head-social">

              @php
              $socialmedia = array(
              'facebook' => 'fab fa-facebook-f',
              'instagram' => 'fab fa-instagram',
              'twitter' => 'fab fa-twitter',
              'linkedin' => 'fab fa-linkedin',
              'youtube' => 'fab fa-youtube',
              'vimeo' => 'fab fa-vimeo',
              );
              @endphp
              <h4 class="ft-saira">FOLLOW US</h4>
              <ul>
                @foreach($socialmedia as $socialmedianame => $socialmediaicon)
                @if($socialmedialinks->getValue($socialmedianame))
                <li>
                  <a href="{{$socialmedialinks->getValue($socialmedianame)}}" target="_blank">
                    <i class="{{$socialmediaicon}}"></i>
                  </a>
                </li>
                @endif
                @endforeach
              </ul>
            </div>
          </div>
        </nav>
      </div>
    </header>


    @yield('content')

    <x-front.section.footer instagram="{{$socialmedialinks->getValue('instagram')}}" />

    <script type="text/javascript" src="//s7.addthis.com/js/250/addthis_widget.js"></script>
   
    <script src="https://www.gstatic.com/firebasejs/9.6.1/firebase-app-compat.js"></script>
    <script src="https://www.gstatic.com/firebasejs/9.6.1/firebase-analytics-compat.js"></script>
    <script src="https://www.gstatic.com/firebasejs/9.6.1/firebase-messaging-compat.js"></script>
    <script src="/js/pushnotification.js"></script>
    <script type="text/javascript" src="https://js.stripe.com/v2/"></script>


    <script>
      function backAway(){
        //if it was the first page
        if(history.length === 1){
            window.location = "{{env('APP_URL')}}"
        } else {
            history.back();
        }
    }

    </script>
    @stack('scripts')
    @if($globalsettings->getValue('footer_scripts'))
    {!!$globalsettings->getValue('footer_scripts')!!}
    @endif
  </body>

</html>