@extends('admin.layouts.app')
@section('panel')

    <div class="row mb-none-30">
        <div class="col-lg-12 col-md-12 mb-30">
            <div class="card">

                <div class="card-body">
                    <form action="" method="POST" action="">
                        @csrf
                        <h3 class="border-bottom mb-3">@lang('Configurations')</h3>
                        <div class="row">
                            <div class="form-group col-md-3">
                                <label class="form-control-label font-weight-bold">@lang('Force Secure Password')</label>
                                <input type="checkbox" data-width="100%" data-size="large" data-onstyle="-success" data-offstyle="-danger" data-toggle="toggle" data-on="@lang('Enable')" data-off="@lang('Disabled')" name="secure_password" @if($general->secure_password) checked @endif>
                            </div>
                            <div class="form-group col-md-3">
                                <label class="form-control-label font-weight-bold">@lang('Agree policy')</label>
                                <input type="checkbox" data-width="100%" data-size="large" data-onstyle="-success" data-offstyle="-danger" data-toggle="toggle" data-on="@lang('Enable')" data-off="@lang('Disabled')" name="agree" @if($general->agree) checked @endif>
                            </div>

                            <div class="form-group col-md-3">
                                <label class="form-control-label font-weight-bold">@lang('User Registration')</label>
                                <input type="checkbox" data-width="100%" data-size="large" data-onstyle="-success" data-offstyle="-danger" data-toggle="toggle" data-on="@lang('Enable')" data-off="@lang('Disabled')" name="registration" @if($general->registration) checked @endif>
                            </div>

                            <div class="form-group col-md-3">
                                <label class="form-control-label font-weight-bold">@lang('Force SSL')</label>
                                <input type="checkbox" data-width="100%" data-size="large" data-onstyle="-success" data-offstyle="-danger" data-toggle="toggle" data-on="@lang('Enable')" data-off="@lang('Disabled')" name="force_ssl" @if($general->force_ssl) checked @endif>
                            </div>
                            <div class="form-group col-md-3">
                                <label class="form-control-label font-weight-bold"> @lang('Email Verification')</label>
                                <input type="checkbox" data-width="100%" data-size="large" data-onstyle="-success" data-offstyle="-danger" data-toggle="toggle" data-on="@lang('Enable')" data-off="@lang('Disable')" name="ev" @if($general->ev) checked @endif>
                            </div>
                            <div class="form-group col-md-3">
                                <label class="form-control-label font-weight-bold">@lang('Email Notification')</label>
                                <input type="checkbox" data-width="100%" data-size="large" data-onstyle="-success" data-offstyle="-danger" data-toggle="toggle" data-on="@lang('Enable')" data-off="@lang('Disable')" name="en" @if($general->en) checked @endif>
                            </div>
                            <div class="form-group col-md-3">
                                <label class="form-control-label font-weight-bold"> @lang('SMS Verification')</label>
                                <input type="checkbox" data-width="100%" data-size="large" data-onstyle="-success" data-offstyle="-danger" data-toggle="toggle" data-on="@lang('Enable')" data-off="@lang('Disable')" name="sv" @if($general->sv) checked @endif>
                            </div>
                            <div class="form-group col-md-3">
                                <label class="form-control-label font-weight-bold">@lang('SMS Notification')</label>
                                <input type="checkbox" data-width="100%" data-size="large" data-onstyle="-success" data-offstyle="-danger" data-toggle="toggle" data-on="@lang('Enable')" data-off="@lang('Disable')" name="sn" @if($general->sn) checked @endif>
                            </div>
                        </div>

                        <h3 class="border-bottom my-3">@lang('Modules')</h3>
                        <div class="row">
                            <div class="form-group col-md-3">
                                <label class="form-control-label font-weight-bold">@lang('Deposit')</label>
                                <input type="checkbox" data-width="100%" data-size="large" data-onstyle="-success" data-offstyle="-danger" data-toggle="toggle" data-on="@lang('Enable')" data-off="@lang('Disabled')" name="module[deposit]'" @if($modules && $modules->deposit) checked @endif>
                            </div>

                            <div class="form-group col-md-3">
                                <label class="form-control-label font-weight-bold">@lang('Withdraw')</label>
                                <input type="checkbox" data-width="100%" data-size="large" data-onstyle="-success" data-offstyle="-danger" data-toggle="toggle" data-on="@lang('Enable')" data-off="@lang('Disabled')" name="module[withdraw]" @if($modules && $modules->withdraw) checked @endif>
                            </div>

                            <div class="form-group col-md-3">
                                <label class="form-control-label font-weight-bold">@lang('DPS')</label>
                                <input type="checkbox" data-width="100%" data-size="large" data-onstyle="-success" data-offstyle="-danger" data-toggle="toggle" data-on="@lang('Enable')" data-off="@lang('Disabled')" name="module[dps]" @if($modules && $modules->dps) checked @endif>
                            </div>

                            <div class="form-group col-md-3">
                                <label class="form-control-label font-weight-bold">@lang('FDR')</label>
                                <input type="checkbox" data-width="100%" data-size="large" data-onstyle="-success" data-offstyle="-danger" data-toggle="toggle" data-on="@lang('Enable')" data-off="@lang('Disabled')" name="module[fdr]" @if($modules && $modules->fdr) checked @endif>
                            </div>

                            <div class="form-group col-md-3">
                                <label class="form-control-label font-weight-bold">@lang('Loan')</label>
                                <input type="checkbox" data-width="100%" data-size="large" data-onstyle="-success" data-offstyle="-danger" data-toggle="toggle" data-on="@lang('Enable')" data-off="@lang('Disabled')" name="module[loan]" @if($modules && $modules->loan) checked @endif>
                            </div>
                            <div class="form-group col-md-3">
                                <label class="form-control-label font-weight-bold">@lang('Own Bank Transfer')</label>
                                <input type="checkbox" data-width="100%" data-size="large" data-onstyle="-success" data-offstyle="-danger" data-toggle="toggle" data-on="@lang('Enable')" data-off="@lang('Disabled')" name="module[own_bank]" @if($modules && $modules->own_bank) checked @endif>
                            </div>
                            <div class="form-group col-md-3">
                                <label class="form-control-label font-weight-bold">@lang('Other Bank Transfer')</label>
                                <input type="checkbox" data-width="100%" data-size="large" data-onstyle="-success" data-offstyle="-danger" data-toggle="toggle" data-on="@lang('Enable')" data-off="@lang('Disabled')" name="module[other_bank]" @if($modules && $modules->other_bank) checked @endif>
                            </div>

                            <div class="form-group col-md-3">
                                <label class="form-control-label font-weight-bold">@lang('OTP Via Email')</label>
                                <input type="checkbox" data-width="100%" data-size="large" data-onstyle="-success" data-offstyle="-danger" data-toggle="toggle" data-on="@lang('Enable')" data-off="@lang('Disabled')" name="module[otp_email]" @if($modules && @$modules->otp_email) checked @endif>
                            </div>

                            <div class="form-group col-md-3">
                                <label class="form-control-label font-weight-bold">@lang('OTP Via SMS')</label>
                                <input type="checkbox" data-width="100%" data-size="large" data-onstyle="-success" data-offstyle="-danger" data-toggle="toggle" data-on="@lang('Enable')" data-off="@lang('Disabled')" name="module[otp_sms]" @if($modules && @$modules->otp_sms) checked @endif>
                            </div>

                            <div class="form-group col-md-3">
                                <label class="form-control-label font-weight-bold">@lang('Referral System')</label>
                                <input type="checkbox" data-width="100%" data-size="large" data-onstyle="-success" data-offstyle="-danger" data-toggle="toggle" data-on="@lang('Enable')" data-off="@lang('Disabled')" name="module[referral_system]" @if($modules && @$modules->referral_system) checked @endif>
                            </div>

                            <div class="form-group col-md-3">
                                <label class="form-control-label font-weight-bold">@lang('KYC Verification')</label>
                                <input type="checkbox" data-width="100%" data-size="large" data-onstyle="-success" data-offstyle="-danger" data-toggle="toggle" data-on="@lang('Enable')" data-off="@lang('Disabled')" name="module[kyc]" @if($modules && @$modules->kyc) checked @endif>
                            </div>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn--primary btn-block btn-lg">@lang('Update')</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@stop
