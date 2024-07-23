<!-- meta tags and other links -->
<!DOCTYPE html>
<html lang="en">
<head>
 
 
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>{{ $general->sitename($pageTitle ?? '') }}</title>
  @include('partials.seo')

  <!-- bootstrap 5  -->
  <link rel="stylesheet" href="{{ asset($activeTemplateTrue. 'css/lib/bootstrap.min.css') }}">
  <!-- fontawesome 5  -->
  <link rel="stylesheet" href="{{ asset($activeTemplateTrue. 'css/all.min.css') }}">
  <!-- lineawesome font -->
  <link rel="stylesheet" href="{{ asset($activeTemplateTrue. 'css/line-awesome.min.css') }}">

  <link rel="stylesheet" href="{{ asset($activeTemplateTrue. 'css/custom.css') }}">

  <!-- main css -->
  <link rel="stylesheet" href="{{ asset($activeTemplateTrue. 'css/main.css') }}">

  <link rel="stylesheet" href="{{asset($activeTemplateTrue.'css/color.php?color='.$general->base_color.'&secondColor='.$general->secondary_color)}}">

  @stack('style-lib')

  @stack('style')

</head>
  <body>

    <div class="preloader">
        <div class="dl">
            <div class="dl__container">
            <div class="dl__corner--top"></div>
            <div class="dl__corner--bottom"></div>
            </div>
            <div class="dl__square"></div>
        </div>
    </div>


    <div class="main-wrapper">
        @yield('content')

    </div>




@include('partials.plugins')

    <!-- jQuery library -->
  <script src="{{ asset($activeTemplateTrue . 'js/lib/jquery-3.5.1.min.js') }}"></script>

  <script src="{{ asset($activeTemplateTrue . 'js/lightcase.js') }}"></script>

  <!-- bootstrap js -->
  <script src="{{ asset($activeTemplateTrue . 'js/lib/bootstrap.bundle.min.js') }}"></script>
  <script src="{{ asset($activeTemplateTrue . 'js/lib/slick.min.js') }}"></script>
  <!-- scroll animation -->
  <script src="{{ asset($activeTemplateTrue . 'js/lib/wow.min.js') }}"></script>

  @stack('script-lib')
  <!-- main js -->
  <script src="{{ asset($activeTemplateTrue . 'js/app.js') }}"></script>

  @stack('script')


  @include('partials.notify')


  </body>
</html>