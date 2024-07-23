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
  <!-- slick slider css -->
  <link rel="stylesheet" href="{{ asset($activeTemplateTrue. 'css/lib/slick.css') }}">

  <link rel="stylesheet" href="{{ asset($activeTemplateTrue. 'css/lightcase.css') }}">

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


    @include($activeTemplate.'partials.header')

    <div class="main-wrapper">

        @if(!request()->routeIs('home'))
            @include($activeTemplate.'partials.breadcumb')
        @endif

        @yield('content')

    </div>

    @stack('modal')


@php
    $cookie = App\Models\Frontend::where('data_keys','cookie.data')->first();
@endphp

    <!-- Modal -->
    <div class="cookie-modal" id="cookieModal">
        <div class="container">
            <div class="cookie-header mb-1">
                <h5 class="text--base">@lang('Cookie Policy')</h5>
                <button class="close-btn"><i class="fas fa-times"></i></button>
            </div>
            <p class="mb-2 d-inline">
                @php echo @$cookie->data_values->description @endphp
            </p>

            <a class="btn btn-sm btn--success ms-3" href="{{ @$cookie->data_values->link }}" target="_blank">@lang('Learn More')</a>
            <button type="button" class="btn btn-sm btn--base cookie-accept">@lang('Accept')</button>
        </div>
    </div>


    @include($activeTemplate.'partials.footer')

    <!-- jQuery library -->
  <script src="{{ asset($activeTemplateTrue . 'js/lib/jquery-3.5.1.min.js') }}"></script>

  <script src="{{ asset($activeTemplateTrue . 'js/lightcase.js') }}"></script>

  <!-- bootstrap js -->
  <script src="{{ asset($activeTemplateTrue . 'js/lib/bootstrap.bundle.min.js') }}"></script>
  <!-- slick slider js -->
  <script src="{{ asset($activeTemplateTrue . 'js/lib/slick.min.js') }}"></script>
  <!-- scroll animation -->
  <script src="{{ asset($activeTemplateTrue . 'js/lib/wow.min.js') }}"></script>
  <!-- main js -->
  <script src="{{ asset($activeTemplateTrue . 'js/app.js') }}"></script>

  @stack('script-lib')

  @stack('script')

  @include('partials.plugins')

    <script>
        "use strict";
        (function ($) {


            

            $(".langSel").on("change", function() {
                window.location.href = "{{url('/')}}/change/"+$(this).val();
            });

            let myModal = document.getElementById('cookieModal');
            @if(@$cookie->data_values->status && !session('cookie_accepted'))
                myModal.classList.add('show');
            @else
                myModal.classList.remove('show');
            @endif

            $('.cookie-modal .close-btn').on('click', function(){
                $('#cookieModal').removeClass('show');
            });

            $('.cookie-accept').on('click', function(){
                $.get("{{ route('cookie.accept') }}",
                    function (response) {
                        if(response.success){
                            notify('success', response.success);
                            $('#cookieModal').removeClass('show');
                        }
                    }
                );
            });

        })(jQuery);
    </script>

  @include('partials.notify')


  </body>
</html>
