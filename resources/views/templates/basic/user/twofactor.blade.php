@extends($activeTemplate.'layouts.master')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            @if(Auth::user()->ts)
            <div class="col-md-6">
                <div class="card border border--base">
                    <div class="card-header bg--base">
                        <h5 class="card-title m-0 p-2 text-white">@lang('Google Authenticator')</h5>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                        <h6>@lang('Your 2FA Verification is Enabled.')</h6>
                        </div>
                        <div class="form-group">

                            <button type="button" class="btn w-100 btn--danger" data-bs-toggle="modal" data-bs-target="#disableModal">@lang('Disable 2FA Authenticator')</button>
                        </div>
                    </div>
                </div>
            </div>
            @else
            <div class="col-md-8">
                <div class="card border border--base">
                    <div class="card-header bg--base">
                        <h5 class="card-title m-0 p-2 text-white">@lang('Google Authenticator')</h5>
                    </div>
                    <div class="card-body">
                        <div class="form-group mx-auto text-center">
                            <img class="mx-auto" src="{{$qrCodeUrl}}">
                            <p class="fs--14px mt-2">@lang('Use Google Authentication App to scan the QR code.') <a class="text--base" href="https://play.google.com/store/apps/details?id=com.google.android.apps.authenticator2&hl=en" target="_blank">@lang('App Link')</a></p>
                        </div>
                        <div class="form-group">
                            <div class="input-group">
                                <input type="text" name="key" value="{{$secret}}" class="form--control form-control-lg" id="referralURL" readonly>
                                <button class="input-group-text border--base bg--base text-white copytext" id="copyBoard" onclick="myFunction()"> <i class="fa fa-copy"></i> </button>
                            </div>
                        </div>

                        <div class="form-group mx-auto text-center">
                            <button type="button" class="btn custom--bg text-white w-100" data-bs-toggle="modal" data-bs-target="#enableModal">
                                @lang('Enable 2FA Authenticator')
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>

@endsection

@push('modal')
    <!--Enable Modal -->
    <div class="modal fade" id="enableModal" tabindex="-1" aria-labelledby="enableModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <!-- Modal content-->
            <div class="modal-content ">
                <div class="modal-header">
                    <h4 class="modal-title">@lang('Verify Your OTP')</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{route('user.twofactor.enable')}}" method="POST">
                    @csrf
                    <div class="modal-body ">
                        <div class="form-group">
                            <input type="hidden" name="key" value="{{$secret}}">
                            <input type="text" class="form--control" name="code" placeholder="@lang('Enter Google Authenticator Code')">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-md btn-danger" data-bs-dismiss="modal">@lang('Cancel')</button>
                        <button type="submit" class="btn btn-md text-white custom--bg">@lang('Verify')</button>
                    </div>
                </form>
            </div>

        </div>
    </div>

    <!--Disable Modal -->
    <div class="modal fade" id="disableModal" tabindex="-1" aria-labelledby="disableModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">@lang('Verify Your OTP')</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{route('user.twofactor.disable')}}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <input type="text" class="form--control" name="code" placeholder="@lang('Enter Google Authenticator Code')">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-md btn-danger" data-bs-dismiss="modal">@lang('Cancel')</button>
                        <button type="submit" class="btn btn-md text-white custom--bg">@lang('Verify')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endpush

@push('script')
    <script>
        "use strict";
        function myFunction() {
            var copyText = document.getElementById("referralURL");
            copyText.select();
            copyText.setSelectionRange(0, 99999);
            /*For mobile devices*/
            document.execCommand("copy");
            iziToast.success({message: "Copied: " + copyText.value, position: "topRight"});
        }
    </script>
@endpush


@push('bottom-menu')
    <li><a href="{{ route('user.profile.setting') }}">@lang('Profile')</a></li>
    @if($general->modules->referral_system)
        <li><a href="{{ route('user.referral.users') }}">@lang('Referral')</a></li>
    @endif
    <li><a class="active" href="{{ route('user.twofactor') }}">@lang('2FA Security')</a></li>
    <li><a href="{{ route('user.change.password') }}">@lang('Change Password')</a></li>
    <li><a href="{{ route('user.transaction.history') }}">@lang('Transactions')</a></li>
    <li><a class="{{ menuActive(['ticket.*']) }}" href="{{ route('ticket') }}">@lang('Support Tickets')</a></li>
@endpush
