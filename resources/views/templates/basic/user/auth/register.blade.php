@extends($activeTemplate.'layouts.auth')

@section('content')

@php
    $signupBg = getContent('signup_bg.content', true);
    $links = getContent('pages.element');
@endphp

  <section class="account-section registration-section bg_img" style="background-image: url(' {{ getImage( 'assets/images/frontend/signup_bg/' .@$signupBg->data_values->image, '1920x1280') }} ');">
    <div class="account-section-left">
        <div class="account-section-left-inner">
           

        </div>
    </div>
    <div class="account-section-right">
        <div class="top text-center">
            <a href="{{route('home')}}" class="account-logo">
                <img src="{{getImage(imagePath()['logoIcon']['path'] .'/logo.png')}}" alt="logo">
            </a>
             <h4 class="title text-white mb-2">{{ __(@$signupBg->data_values->heading) }}</h4>
            <p class="text-white">{{ __(@$signupBg->data_values->subheading) }}</p>
        </div>
        <div class="middle">
           
           
           
            <form class="account-form" action="@if(session('signupuser'))  {{route('user.user_register_post')}}  @else  {{ route('user.register') }}  @endif" method="POST" onsubmit="return submitUserForm();">
               
                <div class="row">
                    @csrf

                    @if(session()->get('reference') != null && $general->modules->referral_system)
                        <h6 class="text-white text-center mb-3">@lang('Referred By'): {{ session()->get('reference') }}</h6>
                    @endif
                    
                    

                    <div class="col-lg-6 form-group">
                        <label for="firstname">@lang('First Name') *</label>
                        <input id="firstname" placeholder="First Name" type="text" class="form--control" name="firstname" value="{{ old('firstname') }}" required>
                    </div>

                    <div class="col-lg-6 form-group">
                        <label for="lastname">@lang('Last Name') *</label>
                        <input id="lastname" placeholder="Last Name" type="text" class="form--control" name="lastname" value="{{ old('lastname') }}" required>
                    </div>

                    <div class="col-lg-6 form-group">
                        <label for="username">@lang('Username') *</label>
                        <input id="username" id="username" type="text" class="form--control checkUser" name="username" value="{{ old('username') }}" placeholder="@lang('Enter username')" required>
                        <small class="text-danger usernameExist"></small>
                    </div>
                    <div class="col-lg-6 form-group">
                        <label for="email">@lang('E-Mail Address') *</label>
                        <input id="email" type="email" class="form--control checkUser" name="email" value="{{ old('email') }}" placeholder="@lang('Enter email address')" required>
                    </div>

                   
                   <?php  
					/*
                    <div class="col-lg-6 form-group">
                        <label for="lastname">@lang('Country') *</label>
                        <select name="country" id="country" class="form--control">
                            @foreach($countries as $key => $country)
                                <option data-mobile_code="{{ $country->dial_code }}" value="{{ $country->country }}" data-code="{{ $key }}">{{ __($country->country) }}</option>
                            @endforeach
                        </select>
                    </div>  */
                       
					?>
                    
                    

                    <div class="col-lg-6 form-group">
                        <label for="mobile">@lang('Mobile') *</label>
                        <div class="input-group">
<!--
                            <span class="input-group-text bg--base border--base text-white">
                            <span name="moblie" class="border-0 bg-transparent mobile-code">

                            </span>
                            <input type="hidden" name="mobile_code">
                            <input type="hidden" name="country_code">
                            </span>   -->

                            <input type="text" name="mobile" id="mobile" value="{{ old('mobile') }}" class="form--control checkUser" placeholder="@lang('Your Phone Number')" required>
                        </div>
                        <small class="text-danger mobileExist"></small>
                    </div>
                    
                    <!--

                    <div class="col-lg-12 form-group">
                        <label for="city">@lang('City') *</label>
                        <input id="city" id="city" type="text" class="form--control checkUser" name="city" value="{{ old('city') }}" placeholder="@lang('Enter City')" required>
                    </div>

                    <div  style="display: none" class="col-lg-6 form-group">
                        <label for="zip">@lang('Zip Code') *</label>
                        <input   id="zip" id="zip" type="text" class="form--control checkUser" name="zip" value="1234" placeholder="@lang('Enter Zip')" >
                    </div>

                    <div class="col-lg-12 form-group">
                        <label for="address">@lang('Address') *</label>
                        <input id="address" id="address" type="text" class="form--control checkUser" name="address" value="{{ old('address') }}" placeholder="@lang('Enter Address')" required>
                    </div>
 --->

                   
                   
                   <?php
					//check  the link  okay  
					
					if(session('reference')){
						 //placr the  referral 
						
						$referral =   session('reference') ; 
					}  
					
					?>
                   
                    <div class="col-lg-6 form-group">
                       
                        <label for="referral">Referral Username</label>
                        <input    id="referral" type="" class="form--control" name="referral"  value="@if(isset($referral)){{$referral}}@endif"  placeholder="Referral  Username">
                    </div>
                    
                   
                   
                    <div class="col-lg-6 form-group hover-input-popup">
                        <label for="password">@lang('Password') *</label>
                        <input type="password" id="password" name="password" autocomplete="off" class="form--control" placeholder="@lang('Enter Password')">
                        @if($general->secure_password)
                        <div class="input-popup">
                            <p class="text-danger my-1 capital"><small>@lang('Minimum 1 capital letter is required')</small></p>
                            <p class="text-danger my-1 lower"><small>@lang('Minimum 1 small letter is required')</small></p>
                            <p class="text-danger my-1 number"><small>@lang('Minimum 1 number is required')</small></p>
                            <p class="text-danger my-1 special"><small>@lang('Minimum 1 special character is required')</small></p>
                            <p class="text-danger my-1 minimum"><small>@lang('Minimum 6 characters')</small></p>
                            </div>
                        @endif
                    </div>
                    
                    <div class="col-lg-6 form-group">
                        <label for="password-confirm">@lang('Confirm Password') *</label>
                        <input id="password-confirm" type="password" class="form--control" name="password_confirmation" required autocomplete="new-password" placeholder="@lang('Confirm password')">
                    </div>
                    
                    
         
                   
                    <?php   //echo loadReCaptcha()     

                  //  @include($activeTemplate.'partials.custom_captcha')
  ?>
                    @if($general->agree)
                        <div class="col-lg-12 form-group">
                            <div class="form-check custom--checkbox">
                                <input type="checkbox" class="form-check-input" id="agree" name="agree" required>
                                <label for="agree" class="form-check-label">
                                    @lang('I agree with')
                                    @foreach($links as $link)
                                        <a href="{{ route('page', [$link->id,slug($link->data_values->title)]) }}" target="blank" class="text--base">
                                        {{ __($link->data_values->title) }}</a>
                                        {{ $loop->last ? '.' : ',' }}
                                    @endforeach
                                </label>
                            </div>
                        </div>
                    @endif
                    
                    
                    

                    <div class="col-lg-6">
                        <button type="submit" class="btn btn--base text-white w-50">@lang('Sign Up')</button>
                        

                    </div>
                    
                    
                     <div class="col-lg-6">
                     
                                             
            <p class="mt-xl-5 mt-3 text-white">@lang("Have an account")? <a href="{{ route('user.login') }}" class="text--base">@lang('Login here')</a></p>
                   
					</div> 

                </div>
            </form>
        </div>
        <div class="bottom">
            <div class="row">
                <div class="col-xl-12">
                    <ul class="d-flex flex-wrap align-items-center account-short-link justify-content-center">
                            @foreach($links as $link)
                                <li><a href="{{ route('page', [$link->id,slug($link->data_values->title)]) }}" target="blank">
                        {{ __($link->data_values->title) }}</a>
                        {{ $loop->last ? '.' : ',' }}</li>
                            @endforeach
                        </ul>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection


@push('modal')
<div class="modal fade" id="existModalCenter" tabindex="-1" role="dialog" aria-labelledby="existModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="existModalLongTitle">@lang('You are with us')</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <h6 class="text-center">@lang('You have been already signing up with us')</h6>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-md btn-danger" data-bs-dismiss="modal">@lang('Close')</button>
          <a href="{{ route('user.login') }}" class="btn btn-md custom--bg text-white">@lang('Login')</a>
        </div>
      </div>
    </div>
</div>
@endpush


@push('style')
<style>
    .country-code .input-group-prepend .input-group-text{
        background: #fff !important;
    }
    .country-code select{
        border: none;
    }
    .country-code select:focus{
        border: none;
        outline: none;
    }
</style>
@endpush

@push('script-lib')
<script src="{{ asset('assets/global/js/secure_password.js') }}"></script>
@endpush
@push('script')
    <script>

      "use strict";

      function submitUserForm() {
            var response = grecaptcha.getResponse();
            if (response.length == 0) {
                document.getElementById('g-recaptcha-error').innerHTML = '<span class="text-danger">@lang("Captcha field is required.")</span>';
                return false;
            }
            return true;
        }
		
        (function ($) {
			
	        @if($general->secure_password)
                $('input[name=password]').on('input',function(){
                    secure_password($(this));
                });
            @endif
			
        $('.checkUser').on('focusout',function(e){
			
                var url = '{{ route('user.checkUser') }}';
			
                var value = $(this).val();
			
                var token = '{{ csrf_token() }}';
			
                if ($(this).attr('name') == 'mobile') {
                    var mobile = `${$('.mobile-code').text().substr(1)}${value}`;
                    var data = {mobile:mobile,_token:token}
                }
			
                if ($(this).attr('name') == 'email') {
                    var data = {email:value,_token:token}
                }
			
                if ($(this).attr('name') == 'username') {
                    var data = {username:value,_token:token}
                }
			
                $.post(url,data,function(response) {
                  if (response['data'] && response['type'] == 'email') {
                    $('#existModalCenter').modal('show');
                  }else if(response['data'] != null){
                    $(`.${response['type']}Exist`).text(`${response['type']} already exist`);
                  }else{
                    $(`.${response['type']}Exist`).text('');
                  }
                });
            });

        }
			
			
			)(jQuery);

    </script>
@endpush
