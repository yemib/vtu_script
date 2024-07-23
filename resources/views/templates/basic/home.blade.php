@extends($activeTemplate.'layouts.frontend')

@section('content')

@php
$banner = getContent('banner.content', true);
@endphp
<!-- hero section start -->
<section class="hero bg_img"
    style="background-image: url('{{ getImage( 'assets/images/frontend/banner/' .@$banner->data_values->image, '1920x1280') }}');">

    <div class="hero__wave-shape">
        <img src="{{ asset($activeTemplateTrue. 'images/elements/white-wave-1.png') }}" alt="wave image">
    </div>

    <div class="hero__wave-shape two">
        <img src="{{ asset($activeTemplateTrue. 'images/elements/white-wave-1.png') }}" alt="wave image">
    </div>
    <div class="container">
        <div  style="  justify-content: right !important;"  class="row justify-content-center">
            <div  style="" class="col-lg-6 text-center">
               <!--get the setting color  ---->
               <?php
               $get_setting  =  App\Models\GeneralSetting::first() ;

				   ?>
               <style>
				   .new_color{

					 color: #{!!$get_setting->base_color!!}  !important  ;
					    text-shadow: -1px 0 white, 0 1px white, 1px 0 white, 0 -1px white;
				   }
				</style>

                <h2   class="hero__title  new_color wow fadeInUp" data-wow-duration="0.5s" data-wow-delay="0.3s">
                   Welcome To {{  $get_setting->sitename }}

                   <?php  //{{ __(@$banner->data_values->heading) }}    ?>
                </h2>
                <p class="text-white mt-4 wow fadeInUp" data-wow-duration="0.5s" data-wow-delay="0.3s">
                   We provide all networks with affordable data bundles, bill payment, and airtime.
                  <?php  //    {{ __(@$banner->data_values->subheading) }}   ?>
                </p>
                <a href="{{ @$banner->data_values->button_link }}" class="btn custom--bg text-white mt-4 wow fadeInUp"
                    data-wow-duration="0.5s" data-wow-delay="0.3s">
                    {{ __(@$banner->data_values->button_text) }}</a>
                </a>
            </div>
        </div>
    </div>
</section>



<!-- hero section end -->

<?php
/*
@if($sections->secs != null)
@foreach(json_decode($sections->secs) as $sec)
    @include($activeTemplate.'sections.'.$sec)
@endforeach
@endif

	*/
	?>


<div class="feature-section pb-100">
    <div class="container">
        <div class="row gy-4">

                        <div class="col-xl-5 col-sm-6 wow fadeInUp" data-wow-duration="0.5s" data-wow-delay="0.3s" style="visibility: visible; animation-duration: 0.5s; animation-delay: 0.3s; animation-name: fadeInUp;">
                <div class="feature-card rounded-3">
                    <div class="icon">
                        <i class="fas fa-file-invoice-dollar"></i>                    </div>
                    <h3 class="title">We're Fast</h3>
                    <p>Our website is automated to deliver order immediately you process it.</p>
                </div>
            </div>
                        <div class="col-xl-3 col-sm-6 wow fadeInUp" data-wow-duration="0.5s" data-wow-delay="0.3s" style="visibility: visible; animation-duration: 0.5s; animation-delay: 0.3s; animation-name: fadeInUp;">
                <div class="feature-card rounded-3">
                    <div class="icon">
                        <i class="fas fa-coins"></i>                    </div>
                    <h3 class="title">We're Reliable</h3>
                    <p>With our many years of expertise and engineers, we have been able to thoroughly optimize our platform for reliability and dependability in recent years.</p>
                </div>
            </div>
                        <div class="col-xl-3 col-sm-6 wow fadeInUp" data-wow-duration="0.5s" data-wow-delay="0.3s" style="visibility: visible; animation-duration: 0.5s; animation-delay: 0.3s; animation-name: fadeInUp;">
                <div class="feature-card rounded-3">
                    <div class="icon">
                        <i class="fas fa-wallet"></i>                    </div>
                    <h3 class="title">We are customer friendly</h3>
                    <p>We believe in the fact that customer is king. We have therefore created a strong support to attend to issue you face on our platform.</p>
                </div>
            </div>

                         <div class="col-xl-3 col-sm-6 wow fadeInUp" data-wow-duration="0.5s" data-wow-delay="0.3s" style="visibility: visible; animation-duration: 0.5s; animation-delay: 0.3s; animation-name: fadeInUp; display: none">
                <div class="feature-card rounded-3">
                    <div class="icon">
                        <i class="fas fa-exchange-alt"></i>                    </div>
                    <h3 class="title">Transfer Money</h3>
                    <p>You are able to transfer your funds within the Viserbank or other banks we support by adding your beneficiaries</p>
                </div>
            </div>
                    </div>
    </div>
</div>


@endsection
