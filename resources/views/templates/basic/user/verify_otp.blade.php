@extends($activeTemplate.'layouts.master')
@section('content')


<!-- dashboard section start -->
<section class="d-flex flex-wrap justify-content-center align-items-center bg-transparent">
    <div class="container">
        <div class="row justify-content-center mb-none-30">
            <div class="col-lg-5 col-md-6 mb-30">
                <div class="account-wrapper">
                    <div class="left d-block">
                        <div class="top text-center">
                            <h4 class="text-white mb-2">@lang('Verify your OTP')</h4>


                            @if($action->otp_type == 2 || $action->otp_type == 3)

                                @if($action->otp_type == 2)
                                <p>@lang('Please check your email to get a six digit OTP')</p>
                                @else
                                    <p>@lang('Please check your mobile to get a six digit OTP')</p>
                                @endif
                                @php
                                    $startTime  = \Carbon\Carbon::now();
                                    $finishTime = \Carbon\Carbon::parse($action->expired_at);

                                    $totalDuration = $finishTime->diffInSeconds($startTime);
                                    if($startTime > $finishTime) {
                                        $totalDuration = 0;
                                    }
                                @endphp

                                <p class="mt-2">@lang('OTP will be expired in the next')</p>

                                <div class="d-flex justify-content-center">
                                    <div class="expired-time-circle @if($totalDuration ==0) danger-border  @endif">
                                        <div class="exp-time">{{ $totalDuration }}</div>
                                        @lang('Seconds')
                                        <div class="animation-circle"></div>
                                    </div>

                                    <div class="border-circle"></div>
                                </div>

                                <div class="try-btn-wrapper d-none mt-2">
                                    <p class="text-danger ">@lang('Your OTP has been expired') </p>
                                    <form method="POST" action="{{ route('user.otp.resend') }}" class="w-100 mt-2">
                                        @csrf
                                        <button type="submit" class="w-100 btn btn-sm btn--base text-white">@lang('Resend OTP')</button>
                                    </form>
                                </div>
                            @endif
                        </div>

                        @if($action->otp_type == 1)
                            <div class="text-white text-center" id="otp-time">{{ \Carbon\Carbon::now()->format('h:i:s A') }}</div>
                        @endif

                    </div>

                    <div class="right">
                        <form class="account-form" action="{{ route('user.otp.check') }}" method="post">
                            @csrf
                            <div class="form-group mb-3">
                                <input type="text" placeholder="@lang('Enter Your Otp')" class="form--control integer-validation" name="otp" required autocomplete="off">
                            </div>
                            <div class="form-group mb-0">
                                <button type="submit" class="btn btn-md btn--base w-100">@lang('Verify')</button>
                            </div>
                        </form>
                    </div>
            </div>
        </div><!-- row end -->
    </div>
</section>
<!-- dashboard section end -->
@endsection

@push('script')
    <script>
        'use strict';
        (function($){
            let seconds = Number($('.exp-time').text());

            @if($action->otp_type == 1)
                setInterval(function(){
                    $("#otp-time").load(window.location.href + " #otp-time" );

                }, 1000);
            @endif

            setInterval(function(){
                seconds = Number($('.exp-time').text());
                if(seconds == 0) {
                    $('.try-btn-wrapper').removeClass('d-none');
                    $('.expired-time-circle').addClass('danger-border')
                }

                $(".exp-time").load(window.location.href + " .exp-time" );

            }, 1000);

        })(jQuery)
    </script>
@endpush


@isset($totalDuration)
    @push('style')
    <style>
        .animation-circle {
        position: absolute;
        top: 0;
        left: 0;
        border: 4px solid #f44336;
        height: 100%;
        width: 100%;
        border-radius: 150px;
        box-shadow: 1px 1px 1px 1px rgba(255, 0, 0, 0.5);
        transform: rotateY(180deg);
        animation-name: clipCircle;
        animation-duration: {{ $totalDuration }}s;
        animation-iteration-count: 1;
        animation-timing-function: cubic-bezier(0, 0, 1, 1);
        z-index: 1;
    }
    .account-wrapper .left .top {
        margin-top: 0;
    }
    .account-wrapper .left,
    .account-wrapper .right {
        width: 100%;
    }
    .account-wrapper .right {
        padding-left: 0;
        margin-top: 35px;
    }
    </style>
    @endpush
@endisset
