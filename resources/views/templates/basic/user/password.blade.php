@extends($activeTemplate.'layouts.master')

@section('content')
    <div class="container">
        <div class="row justify-content-center mt-4">
            <div class="col-md-8">
                <div class="card border border--base">
                    <div class="card-header bg--base">
                        <h5 class="card-title m-0 p-2 text-white">@lang('Change Your Password')</h5>
                    </div>
                    <div class="card-body">
                        <form action="" method="post" class="register">
                            @csrf
                            <div class="form-group">
                                <label for="currentPassword">@lang('Current Password')</label>
                                <div class="input-group">
                                    <input id="currentPassword" placeholder="Current Password" type="password" class="form--control" name="current_password" required autocomplete="current-password">
                                    <span class="input-group-text bg--base border--base text-white"><i class="las la-user-lock"></i></span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="password">@lang('Password')</label>
                                <div class="input-group hover-input-popup">
                                    <input id="password" type="password" placeholder="New Password" class="form--control" name="password" required autocomplete="current-password">
                                    <span class="input-group-text bg--base border--base text-white"><i class="la la-key"></i></span>
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
                            </div>
                            <div class="form-group">
                                <label for="confirm_password">@lang('Confirm Password')</label>
                                <div class="input-group">
                                    <input id="password_confirmation" placeholder="Confirm Password" type="password" class="form--control" name="password_confirmation" required autocomplete="current-password">

                                    <span class="input-group-text bg--base border--base text-white"><i class="la la-key"></i></span>
                                </div>
                            </div>
                            <div class="form-group">
                                <input type="submit" class="mt-4 btn w-100 text-white custom--bg" value="@lang('Change Password')">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
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



@push('bottom-menu')
    <li><a href="{{ route('user.profile.setting') }}">@lang('Profile')</a></li>
    @if($general->modules->referral_system)
        <li><a href="{{ route('user.referral.users') }}">@lang('Referral')</a></li>
    @endif
    <li><a href="{{ route('user.twofactor') }}">@lang('2FA Security')</a></li>
    <li><a class="active" href="{{ route('user.change.password') }}">@lang('Change Password')</a></li>
    <li><a href="{{ route('user.transaction.history') }}">@lang('Transactions')</a></li>
    <li><a class="{{ menuActive(['ticket.*']) }}" href="{{ route('ticket') }}">@lang('Support Tickets')</a></li>
@endpush
