@extends($activeTemplate.'layouts.auth')
@section('content')


@php
    $loginBg     = getContent('login_bg.content', true);
    $links = getContent('pages.element');
@endphp


<section class="account-section bg_img" style="background-image: url(' {{ getImage( 'assets/images/frontend/login_bg/' .@$loginBg->data_values->image, '1920x1280') }} ');">
    <div class="account-section-left">
        <div class="account-section-left-inner">
        
        </div>
    </div>
    <div class="account-section-right">
        <div class="top text-center">
            <a href="{{route('home')}}" class="account-logo">
                <img src="{{getImage(imagePath()['logoIcon']['path'] .'/logo.png')}}" alt="logo">
            </a>
            
              <h2 class="text-white mb-2">@lang('Reset Password')</h2>
            <p class="text-white">@lang('Now you can reset a new password to get access to your account.') <a href="{{ route('user.login') }}" class="text--base">@lang('Login Here')</a></p>
            
        </div>
        <div class="middle">
          <form class="account-form" method="POST" action="{{ route('user.password.update') }}">
            @csrf

            <input type="hidden" name="email" value="{{ $email }}">
            <input type="hidden" name="token" value="{{ $token }}">

            <div class="form-group hover-input-popup">
                <label for="password">@lang('Password')</label>
                <input id="password" type="password" class="form--control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password" placeholder="New Password">

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
            <div class="form-group">
                <label for="password-confirm">@lang('Confirm Password')</label>
                <input id="password-confirm" type="password" class="form--control" name="password_confirmation" required autocomplete="new-password" placeholder="Confirm Password">
            </div>

            <button type="submit" class="btn btn--base w-100">@lang('Reset Password')</button>

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
@push('script-lib')
<script src="{{ asset('assets/global/js/secure_password.js') }}"></script>
@endpush


@push('script')
<script>
    (function ($) {
        "use strict";
        @if($general->secure_password)
            $('input[name=password]').on('input',function(){
                secure_password($(this));
            });
        @endif
    })(jQuery);
</script>
@endpush

