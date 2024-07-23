@extends($activeTemplate .'layouts.auth')
@section('content')

@php
    $forgetPass = getContent('forget_pass.content', true);
    $links = getContent('pages.element');
@endphp

<section class="account-section bg_img" style="background-image: url(' {{ getImage( 'assets/images/frontend/forget_pass/' .@$forgetPass->data_values->image, '1920x1280') }} ');">
    <div class="account-section-left">
        <div class="account-section-left-inner">
            <h2 class="text-white">@lang('2FA Verification')</h2>
            <p class="text-white mt-3">@lang('Please check your google authenticator app to get the verification code')</p>
        </div>
    </div>
    <div class="account-section-right">
        <div class="top text-center">
            <a href="{{route('home')}}" class="account-logo">
                <img src="{{getImage(imagePath()['logoIcon']['path'] .'/logo.png')}}" alt="logo">
            </a>
        </div>
        <div class="middle">
            <form class="account-form" method="POST"
                action="{{ route('user.go2fa.verify') }}">
                @csrf
                <div class="form-group">
                    <label for="code">@lang('Verification Code') *</label>
                    <input id="code" type="text" placeholder="Code" class="form--control" name="code"
                        required autocomplete="code" required>
                </div>
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
        (function ($) {
            "use strict";
            $('#code').on('input change', function () {
                var xx = document.getElementById('code').value;

                $(this).val(function (index, value) {
                    value = value.substr(0, 7);
                    return value.replace(/\W/gi, '').replace(/(.{3})/g, '$1 ');
                });

            });
        })(jQuery)

    </script>
@endpush



