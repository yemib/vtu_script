@if(checkIsOtpEnable($general))
    <div class="form-group mt-0">
        <label for="verification">@lang('Authorization Mode') *</label>
        <select name="verification" id="verification" class="form--control select" required>
            <option disabled selected value="">@lang('Select One')</option>
            @if(auth()->user()->ts)
                <option value="1">@lang('Google Authenticator')</option>
            @endif
            @if($general->modules->otp_email)
                <option value="2">@lang('Email')</option>
            @endif
            @if($general->modules->otp_sms)
                <option value="3">@lang('Sms')</option>
            @endif
        </select>
    </div>
@endif
