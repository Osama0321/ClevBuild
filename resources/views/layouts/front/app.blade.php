<!DOCTYPE html>
<html lang="en">
<head>
  <title>@isset($title) {{$title}} - @endif {{ ($globalsettings->getValue('site_title')) ?
    $globalsettings->getValue('site_title') : config('app.name', 'Laravel') }}</title>
  {!! RecaptchaV3::initJs() !!}
  <!-- Required meta tags -->
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta name="firebase_ip" content="{{ route('notification.store') }}">
  @yield('meta')
  <meta property="og:url"   content="{{Request::url()}}">
  <meta property="og:type"  content="website">
  <meta name="copyright"    content="Copyright {{date('Y')}} © {{ ($globalsettings->getValue('site_title')) ? $globalsettings->getValue('site_title') : config('app.name', 'Laravel') }}. Created by What Boxes" />
  <meta name="language"     content="en">
  <meta name="format-detection" content="telephone=no">
  <meta name="googlebot" content="noindex">
  <meta name="robots" content="noindex" />
  <meta name="googlebot-news" content="nosnippet">

  <!-- Bootstrap CSS -->
  <link href="{{ asset('css/front/bootstrap.min.css') }}" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('fontawesome6/css/all.css') }}">
  <link rel="stylesheet" href="{{ asset('css/front/animate.min.css') }}" />
  <link rel="stylesheet" type="text/css" href="{{ asset('css/front/slick.css') }}"/>
  <link rel="stylesheet" href="{{ asset('css/front/fancybox.css') }}" />
  <link rel="stylesheet" href="{{ asset('css/front/custom.min.css') }}" />
  <link rel="stylesheet" href="{{ asset('css/front/responsive.min.css') }}" />
  <link rel="shortcut icon" type="image/png" href="{{ asset('images/faveicon.png') }}">
  <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
  
  @if($globalsettings->getValue('header_scripts'))
  {!!$globalsettings->getValue('header_scripts')!!}
  @endif

  <!--Start of Tawk.to Script-->
  <script type="text/javascript">
  var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
  (function(){
  var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
  s1.async=true;
  s1.src='https://embed.tawk.to/65817e3b07843602b80399a9/1hi0ta6k4';
  s1.charset='UTF-8';
  s1.setAttribute('crossorigin','*');
  s0.parentNode.insertBefore(s1,s0);
  })();
  </script>
  <!--End of Tawk.to Script-->
</head>

<body>
<!-- Get A Quote Buttons -->
<div class="getquotebuttons">
  <ul>
    <li><a href="tel:+123456789"><i class="fa-solid fa-phone"></i> (123)-456-789 </a></li>
    <li><a href="{{ route('pages.show', ['pages' => 'get-free-quote']) }}" class="greybtn"><i class="fa-sharp fa-solid fa-comment"></i> Get a Quote </a></li>
  </ul>
</div>

<!-- BEGIN menu Section -->
<x-front.section.menu />
<!-- END menu Section -->

@yield('content')

<!-- BEGIN footer Section -->
<x-front.section.footer />
<!-- END footer Section -->
<script src="{{ asset('js/front/jquery-3.5.1.min.js') }}"></script>    
<script src="{{ asset('js/front/bootstrap.min.js') }}"></script>
<script src="{{ asset('js/front/popper.min.js') }}"></script>
<script src="{{ asset('js/front/slick.js') }}"></script>
<script src="{{ asset('js/front/wow.min.js') }}"></script>
<script src="{{ asset('js/front/fancybox.umd.js') }}"></script>
<script src="{{ asset('js/front/custom.min.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


<script>
var navigator_info = window.navigator;
var screen_info = window.screen;
var uid = navigator_info.mimeTypes.length;
uid += navigator_info.userAgent.replace(/\D+/g, '');
uid += navigator_info.plugins.length;
uid += screen_info.height || '';
uid += screen_info.width || '';
uid += screen_info.pixelDepth || '';
console.log(uid);
</script>


<script src="https://www.gstatic.com/firebasejs/9.6.1/firebase-app-compat.js"></script>
<script src="https://www.gstatic.com/firebasejs/9.6.1/firebase-analytics-compat.js"></script>
<script src="https://www.gstatic.com/firebasejs/9.6.1/firebase-messaging-compat.js"></script>
<script src="/js/pushnotification.js"></script>
<script>
Fancybox.bind("[data-fancybox]", {
  // Your custom options
});    


if ("serviceWorker" in navigator) {
  window.addEventListener("load", function() {
    navigator.serviceWorker
      .register("{{asset('/firebase-messaging-sw.js')}}")
      .then(res => console.log("service worker registered"))
      .catch(err => console.log("service worker not registered", err))
  })
}
</script>

@if(session('msg'))
<script>
const Toast = Swal.mixin({
  toast: true,
  position: "top-end",
  showConfirmButton: false,
  timer: 8000,
  timerProgressBar: true,
  didOpen: (toast) => {
    toast.onmouseenter = Swal.stopTimer;
    toast.onmouseleave = Swal.resumeTimer;
  }
});
Toast.fire({
  icon: "{{session('msg_type')}}",
  title: "{{session('msg')}}"
});
</script>
@endif
<script>
document.addEventListener("DOMContentLoaded", function() {
    // Get all the anchor links in the navigation
    const links = document.querySelectorAll(".faqsbox a");
    $(".select2").select2();
    // Add click event listeners to each link
    links.forEach(link => {
        link.addEventListener("click", function(event) {
            // Prevent default behavior of the link
            event.preventDefault();

            // Get the target section's ID from the href attribute
            const targetId = this.getAttribute("href").substring(1);

            // Scroll smoothly to the target section
            document.getElementById(targetId).scrollIntoView({
                behavior: "smooth"
            });
        });
    });

    $('.numbersOnly').on('input', function() {
      // Replace any non-numeric input and 'e' with an empty string
      $(this).val($(this).val().replace(/[^0-9.]/g, '').replace('e', ''));
    });
});


</script>
@stack('scripts')
@if($globalsettings->getValue('footer_scripts'))
  {!!$globalsettings->getValue('footer_scripts')!!}
@endif
</body>
</html>