@php
	$captcha = loadCustomCaptcha($height = 46, $width = '100%');
@endphp
@if($captcha)
    <div class="form-group col-lg-12">
        <label for="recaptcha-code">@lang('Captcha')</label>
        @php echo $captcha @endphp
        <input type="text" name="captcha" id="recaptcha-code" placeholder="@lang('Enter Captcha')" class="form--control mt-3" autocomplete="off">
    </div>
@endif

@push('style')
<style>
    .capcha div{
        width: 100% !important;
    }
</style>
@endpush
