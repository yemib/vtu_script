@extends($activeTemplate.'layouts.auth')

@section('content')

@php
    $loginBg     = getContent('login_bg.content', true);
    $links = getContent('pages.element');
@endphp

<section class="account-section bg_img" style="background-image: url(' {{ getImage( 'assets/images/frontend/login_bg/' .@$loginBg->data_values->image, '1920x1280') }} ');">
	
	
	
    <div class="account-section-left">
        <div class="account-section-left-inner">
         
          <!--  <p class="text-white">{{ __(@$loginBg->data_values->subheading) }}</p>  -->

  
        </div>
    </div>
	
	
    <div class="account-section-right" >
        <div class="top text-center">
            <a href="{{route('home')}}" class="account-logo">
                <img src="{{getImage(imagePath()['logoIcon']['path'] .'/logo.png')}}" alt="logo">
            </a>
			<br/>
			
			   <h8 align="center"  class="title text-white mb-2">{{ __(@$loginBg->data_values->heading) }}</h8>
			
        </div>
        <div class="middle"  >
            <form class="account-form" method="POST" action="{{ route('user.login') }}"
                onsubmit="return submitUserForm();">
                @csrf
                <div class="form-group">
                    <label for="username">@lang('Username or Email') *</label>
                    <input type="text" name="username" value="{{ old('username') }}"
                        placeholder="@lang('Username or Email')" class="form--control" required>
                </div>
                <div class="form-group">
                    <label for="password">@lang('Password') *</label>
                    <input id="password" type="password" placeholder="Password" class="form--control"
                        name="password" required autocomplete="current-password" required>
                </div>

                <div class="row">
                    <div class="col-md-7">
                        <div class="form-check custom--checkbox">
                            <input class="form-check-input" type="checkbox" name="remember" id="remember"
                                {{ old('remember') ? 'checked' : '' }}>
                            <label class="form-check-label" for="remember">
                                @lang('Remember Me')
                            </label>
                        </div>
                    </div>
                    <div class="col-md-5 text-md-end">
                        <a href="{{ route('user.password.request') }}" class="custom--cl">
                            @lang('Forgot password')?
                        </a>
                    </div>
                </div>

               <?php 
               /*
                <div class="form-group">
                    <div class="col-md-12">
                        @php echo loadReCaptcha() @endphp
                    </div>
                </div>
                @include($activeTemplate.'partials.custom_captcha')
				*/	
					
                  ?>
                
<div align="center"> 
                <button  style="border-radius: 10px" type="submit" class="btn btn--base w-50">@lang('Sign In')</button>
				
	</div>
				<p class="mt-xl-5 mt-3 text-white">@lang("Haven't an account")? <a href="{{ route('user.register') }}" class="text--base">@lang('Signup here')</a></p>
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

@push('script')
    <script>
        "use strict";

        function submitUserForm() {
            var response = grecaptcha.getResponse();
            if (response.length == 0) {
                document.getElementById('g-recaptcha-error').innerHTML =
                    '<span style="color:red;">@lang("Captcha field is required.")</span>';
                return false;
            }
            return true;
        }

        function verifyCaptcha() {
            document.getElementById('g-recaptcha-error').innerHTML = '';
        }

    </script>
@endpush
