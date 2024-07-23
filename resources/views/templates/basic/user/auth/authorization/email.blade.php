@extends($activeTemplate .'layouts.auth')
@section('content')

@php
    $loginBg     = getContent('login_bg.content', true);
    $links = getContent('pages.element');
@endphp


<section class="account-section bg_img" style="background-image: url(' {{ getImage( 'assets/images/frontend/login_bg/' .@$loginBg->data_values->image, '1920x1280') }} ');">
    <div class="account-section-left">
        <div class="account-section-left-inner">
          <h2 class="text-white">@lang('Verify Your Email')</h2>
          <p class="text-white mt-3">@lang('A verification code has been sent to your email') <span class="text--base">{{auth()->user()->email}}</span> <br> @lang('Please check including your Junk/Spam folder.') @lang("If not found") <a href="{{route('user.send.verify.code')}}?type=email" class="text--base fw-bold">@lang('Resend Code')</a></p>
        </div>
    </div>
    <div class="account-section-right">
        <div class="top text-center">
            <a href="{{route('home')}}" class="account-logo">
                <img src="{{getImage(imagePath()['logoIcon']['path'] .'/logo.png')}}" alt="logo">
            </a>
        </div>
        <div class="middle">
          <form class="account-form" method="POST" action="{{route('user.verify.email')}}">
            @csrf
            <div class="form-group">
              <label for="code">@lang('Verification Code') <span class="text--danger">*</span></label>
              <input type="text" name="email_verified_code" placeholder="@lang('Code')" class="form--control" maxlength="7" id="code" autocomplete="off" required>
            </div>

          @if ($errors->has('resend'))
            <small class="text-danger">{{ $errors->first('resend') }}</small>
          @endif

            <button type="submit" class="btn btn--base w-100">@lang('Submit')</button>

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
    (function($){
        "use strict";
        $('#code').on('input change', function () {
          var xx = document.getElementById('code').value;

              $(this).val(function (index, value) {
                 value = value.substr(0,7);
                  return value.replace(/\W/gi, '').replace(/(.{3})/g, '$1 ');
              });

      });
    })(jQuery)
</script>
@endpush
