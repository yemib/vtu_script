@extends($activeTemplate.'layouts.master')
@section('content')
    <div class="container">
        <form class="register" action="" method="post" enctype="multipart/form-data">
        @csrf
        <div class="row justify-content-center gy-4">

            <div class="col-xl-4 col-lg-5">
                <div class="card">
                    <div class="card-body">
                        <div class="avatar-upload">
                            <div class="avatar-edit">
                                <input type='file' name="image" id="imageUpload" accept=".png, .jpg, .jpeg" />
                                <label for="imageUpload"></label>
                            </div>
                            <div class="avatar-preview">
                                <div id="imagePreview" style="background-image: url({{ getImage(imagePath()['profile']['user']['path'].'/'. $user->image,imagePath()['profile']['user']['size']) }});">
                                </div>
                            </div>
                        </div>
                        <ul class="caption-list-two">
                            <li>
                                <span class="caption">@lang('Name')</span>
                                <span class="value">{{$user->firstname}} {{$user->lastname}}</span>
                            </li>
                            <li>
                                <span class="caption">@lang('E-mail Address')</span>
                                <span class="value">{{$user->email}}</span>
                            </li>
                            <li>
                                <span class="caption">@lang('Mobile Number')</span>
                                <span class="value">{{$user->mobile}}</span>
                            </li>
                            <li>
                                <span class="caption">@lang('Country')</span>
                                <span class="value">{{$user->address->country}}</span>
                            </li>
                            <li>
                                <span class="caption">@lang('City')</span>
                                <span class="value">{{$user->address->city}}</span>
                            </li>
                            <li>
                                <span class="caption">@lang('Zip Code')</span>
                                <span class="value">{{$user->address->zip}}</span>
                            </li>
                            <li>
                                <span class="caption">@lang('Address')</span>
                                <span class="value">{{@$user->address->address}}</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-xl-8 col-lg-7">
                <div class="card">
                    <div class="card-body p-4">
                        <div class="row">
                            <div class="form-group col-12">
                                <label for="InputFirstname" class="col-form-label">@lang('First Name'):</label>
                                <input type="text" class="form--control" id="InputFirstname" name="firstname" placeholder="@lang('First Name')" value="{{$user->firstname}}" >
                            </div>
                            <div class="form-group col-12">
                                <label for="lastname" class="col-form-label">@lang('Last Name'):</label>
                                <input type="text" class="form--control" id="lastname" name="lastname" placeholder="@lang('Last Name')" value="{{$user->lastname}}">
                            </div>
                            <div class="form-group ">
                                <button type="submit" class="btn btn--base w-100">@lang('Update Profile')</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
      </form>
    </div>
@endsection
@push('style-lib')
    <link href="{{ asset($activeTemplateTrue.'css/bootstrap-fileinput.css') }}" rel="stylesheet">
@endpush
@push('style')
    <link rel="stylesheet" href="{{asset('assets/admin/build/css/intlTelInput.css')}}">
    <style>
        .intl-tel-input {
            position: relative;
            display: inline-block;
            width: 100%;!important;
        }
    </style>
@endpush

@push('script')
    <script>
        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    $('#imagePreview').css('background-image', 'url('+e.target.result +')');
                    $('#imagePreview').hide();
                    $('#imagePreview').fadeIn(650);
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        $("#imageUpload").change(function() {
            readURL(this);
        });

    </script>
@endpush

@push('bottom-menu')

<li><a class="active" href="{{ route('user.profile.setting') }}">@lang('Profile')</a></li>
@if($general->modules->referral_system)
<li><a href="{{ route('user.referral.users') }}">@lang('Referral')</a></li>
@endif
<li><a href="{{ route('user.twofactor') }}">@lang('2FA Security')</a></li>
<li><a href="{{ route('user.change.password') }}">@lang('Change Password')</a></li>
<li><a href="{{ route('user.transaction.history') }}">@lang('Transactions')</a></li>
<li><a class="{{ menuActive(['ticket.*']) }}" href="{{ route('ticket') }}">@lang('Support Tickets')</a></li>
@endpush
