<!-- meta tags and other links -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $general->sitename($pageTitle ?? '') }}</title>
    @include('partials.seo')

     @stack('style-lib')

    <!-- custom select box css -->
    <link rel="stylesheet" href="{{asset('assets/admin/css/vendor/nice-select.css')}}">
    <!-- select 2 css -->
    <link rel="stylesheet" href="{{asset('assets/admin/css/vendor/select2.min.css')}}">
    <!-- jvectormap css -->
    <link rel="stylesheet" href="{{asset('assets/admin/css/vendor/jquery-jvectormap-2.0.5.css')}}">
    <!-- datepicker css -->
    <link rel="stylesheet" href="{{asset('assets/admin/css/vendor/datepicker.min.css')}}">
    <!-- timepicky for time picker css -->
    <link rel="stylesheet" href="{{asset('assets/admin/css/vendor/jquery-timepicky.css')}}">
    <!-- bootstrap-clockpicker css -->
    <link rel="stylesheet" href="{{asset('assets/admin/css/vendor/bootstrap-clockpicker.min.css')}}">
    <!-- bootstrap-pincode css -->
    <link rel="stylesheet" href="{{asset('assets/admin/css/vendor/bootstrap-pincode-input.css')}}">
    <!-- dashdoard main css -->
    <link rel="stylesheet" href="{{asset('assets/admin/css/app.css')}}">

    @stack('style')

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

<?php
 // @include($activeTemplate.'partials.auth_header')    ?>




       <!-- page-wrapper start -->
    <div class="page-wrapper default-version">

             @if(!request()->routeIs('home'))
        @include($activeTemplate.'partials.breadcumb')
        @include($activeTemplate.'partials.bottom_menu')
        @endif




        @include($activeTemplate.'user.partials.sidenav')
        @include($activeTemplate.'user.partials.topnav')

        <div class="body-wrapper">
            <div class="bodywrapper__inner">



              @yield('content')


            </div><!-- bodywrapper__inner end -->
        </div><!-- body-wrapper end -->
    </div>









    @stack('modal')
<?php
  //  @include($activeTemplate.'partials.footer')   ?>

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


    <!-- jQuery library -->
<script src="{{asset('assets/admin/js/vendor/jquery-3.5.1.min.js')}}"></script>
<!-- bootstrap js -->
<script src="{{asset('assets/admin/js/vendor/bootstrap.bundle.min.js')}}"></script>
    <!-- bootstrap-toggle js -->
<script src="{{asset('assets/admin/js/vendor/bootstrap-toggle.min.js')}}"></script>

<!-- slimscroll js for custom scrollbar -->
<script src="{{asset('assets/admin/js/vendor/jquery.slimscroll.min.js')}}"></script>
<!-- custom select box js -->
<script src="{{asset('assets/admin/js/vendor/jquery.nice-select.min.js')}}"></script>




     <!---- place the script function  here   ---->

    <script>
	function  submit_form(path  , form_id  , display){
//animationn
		//$('#display_loading').attr('disabled' ,'disabled');
		 $('#'+display).html('<option value=""> Loading......</option>');
		 $('#loading_area').html('Loading......');

 //event.preventDefault();

$.ajax({
  url:path,
  method:"GET",
  data:$('#'+form_id).serialize(),
  success:function(data)
  {

	 $('#'+display).html(data);
	  $('input[type=submit]').show();
	   $('#loading_area').html('');

	//$('#'+display_data).attr('disabled' ,'disabled');
  }
}).fail(function(status) {
    alert('Error Occured' );
  });
}



function  get_ajax(path_here  , variable_value   , display){
	$.ajax({
        type: 'get',
        dataType: 'html',
        url:  path_here,
        data: "variable="+variable_value,
        success: function (response) {
           // console.log(response);
            //body(response)
			$('#'+display).html(response);
        }
    }).fail(function(status) {
    alert('Error Occured' );
  });

	}







     </script>

     <!--  paystack  script  here only for walllet   -->





<!-- main js -->
<script src="{{asset('assets/admin/js/app.js')}}"></script>

    @stack('script-lib')

    @include('partials.plugins')

    @include('partials.notify')

    @stack('script')

    <script>
        (function ($) {
            "use strict";
            $(".langSel").on("change", function () {
                window.location.href = "{{url('/')}}/change/" + $(this).val();
            });
        })(jQuery);
    </script>





</body>
<style>
	.inner-hero {

		display: none !important;
	}

	</style>
</html>
