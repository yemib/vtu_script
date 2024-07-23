@extends('admin.layouts.app')
@section('panel')
    <div class="row mb-none-30">
        <div class="col-lg-12 col-md-12 mb-30">
            <form action="" method="POST">
                @csrf
                <div class="card">
                    <div class="card-body">
                       
                                               <h4 class="text-muted my-3 border-bottom pb-2">VTU  KEY</h4>
                                               
          <div class="row">  
                                                  
                                                   
                 <div class="col-md-4">
                                <div class="form-group ">
                                    <label class="font-weight-bold">@lang('Key')</label>
                                    <div class="input-group">
                                        <input class="form-control form-control-lg" type="text" name="vtukey" value="{{ $general->vtukey }}">
                                     
                                    </div>
                                </div>
                            </div>
                                                  
                                                  
                                                                  
                                                   
                 <div class="col-md-4">
                                <div class="form-group ">
                                    <label class="font-weight-bold">@lang('User ID')</label>
                                    <div class="input-group">
                                        <input class="form-control form-control-lg" type="text" name="vtuuserid" value="{{ $general->vtuuserid }}">
                                    
                                    </div>
                                </div>
                            </div>       
                                                               
                 <div class="col-md-4">
                                <div class="form-group ">
                                    <label class="font-weight-bold">@lang('Admin Notification Email')</label>
                                    <div class="input-group">
                                        <input class="form-control form-control-lg" type="text" name="noti_email" value="{{ $general->noti_email }}">
                                    
                                    </div>
                                </div>
                            </div>
                                                  
                                                  
                                                  
                    
                                                                                  
	</div>
                     
                         <h4 class="text-muted my-3 border-bottom pb-2">MONIFY KEYS</h4>
                      
                      <div  class="row"> 
                      
                                                                   
                                                   
                 <div class="col-md-4">
                                <div class="form-group ">
                                    <label class="font-weight-bold">@lang('API Key')</label>
                                    <div class="input-group">
                                        <input class="form-control form-control-lg" type="text" name="monify_api_key" value="{{ $general->monify_api_key }}">
                                     
                                    </div>
                                </div>
                            </div>
                                                  
                                                  
                                                                  
                                                   
                 <div class="col-md-4">
                                <div class="form-group ">
                                    <label class="font-weight-bold">@lang('Monify User  Key')</label>
                                    <div class="input-group">
                                        <input class="form-control form-control-lg" type="text" name="monify_user_key" value="{{ $general->monify_user_key }}">
                                    
                                    </div>
                                </div>
                            </div>       
                                                               
                 <div class="col-md-4">
                                <div class="form-group ">
                                    <label class="font-weight-bold">@lang('Contract Code')</label>
                                    <div class="input-group">
                                        <input class="form-control form-control-lg" type="text" name="monify_contract_code" value="{{ $general->monify_contract_code }}">
                                    
                                    </div>
                                </div>
                            </div>
                                                 
                      
                      </div>
                       
                       
                          <h4 class="text-muted my-3 border-bottom pb-2">Basic Settings</h4>
                                               
                       
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group ">
                                    <label class="font-weight-bold"> @lang('Site Title') </label>
                                    <input class="form-control form-control-lg" type="text" name="sitename" value="{{$general->sitename}}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group ">
                                    <label class="font-weight-bold">@lang('Currency')</label>
                                    <input class="form-control form-control-lg" type="text" name="cur_text" value="{{$general->cur_text}}">
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group ">
                                    <label class="font-weight-bold">@lang('Currency Symbol') </label>
                                    <input class="form-control form-control-lg" type="text" name="cur_sym" value="{{$general->cur_sym}}">
                                </div>
                            </div>

                            <div class="form-group col-md-4">
                                <label class="font-weight-bold"> @lang('Timezone')</label>
                                <select class="select2-basic" name="timezone">
                                    @foreach($timezones as $timezone)
                                    <option value="'{{ @$timezone}}'" @if(config('app.timezone') == $timezone) selected @endif>{{ __($timezone) }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group ">
                                    <label class="font-weight-bold">@lang('OTP Expiration Time')</label>
                                    <div class="input-group">
                                        <input class="form-control form-control-lg" type="text" name="otp_time" value="{{ getAmount($general->otp_time) }}">
                                        <div class="input-group-append">
                                            <span class="input-group-text curency-text">
                                                @lang('Seconds')
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group ">
                                    <label class="font-weight-bold">@lang('Account Number Prefex')</label>
                                    <input class="form-control form-control-lg" type="text" name="account_no_prefix" value="{{ $general->account_no_prefix }}">
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group ">
                                    <label class="font-weight-bold">@lang('Account Number Length') <code>(@lang('Without Prefix'))</code></label>
                                    <input class="form-control form-control-lg" type="text" name="account_no_length" value="{{ $general->account_no_length }}">
                                </div>
                            </div>

                            <div class="form-group col-md-4">
                                <label class="font-weight-bold"> @lang('Site Base Color')</label>
                                <div class="input-group">
                                <span class="input-group-addon ">
                                    <input type='text' class="form-control form-control-lg colorPicker" value="{{$general->base_color}}"/>
                                </span>
                                    <input type="text" class="form-control form-control-lg colorCode" name="base_color" value="{{ $general->base_color }}"/>
                                </div>
                            </div>

                            <div class="form-group col-md-4">
                                <label class="font-weight-bold"> @lang('Site Secondary Color')</label>
                                <div class="input-group">
                                <span class="input-group-addon">
                                    <input type='text' class="form-control form-control-lg colorPicker" value="{{$general->secondary_color}}"/>
                                </span>
                                    <input type="text" class="form-control form-control-lg colorCode" name="secondary_color" value="{{ $general->secondary_color }}"/>
                                </div>
                            </div>
                        </div>

                        <h4 class="text-muted my-3 border-bottom pb-2">@lang("Money Transfer Settings Within $general->sitename")</h4>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group ">
                                    <label class="font-weight-bold">@lang('Fixed Charge')</label>
                                    <div class="input-group">
                                        <input class="form-control form-control-lg numeric-validation" type="text" name="fixed_transfer_charge" value="{{ getAmount($general->fixed_transfer_charge) }}">
                                        <div class="input-group-append">
                                            <span class="input-group-text curency-text">
                                                @lang($general->cur_text)
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group ">
                                    <label class="font-weight-bold">@lang('Percent Charge')</label>
                                    <div class="input-group">
                                        <input class="form-control form-control-lg numeric-validation" type="text" name="percent_transfer_charge" value="{{ getAmount($general->percent_transfer_charge )}}">
                                        <div class="input-group-append">
                                            <span class="input-group-text"> % </span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="font-weight-bold">@lang('Minimum Limit')/@lang('Transaction')</label>

                                    <div class="input-group">
                                        <input class="form-control form-control-lg numeric-validation" type="text" name="minimum_transfer_limit" value="{{ getAmount($general->minimum_transfer_limit )}}">
                                        <div class="input-group-append">
                                            <span class="input-group-text curency-text">
                                                @lang($general->cur_text)
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <div class="col-md-4">
                                <div class="form-group ">
                                    <label class="font-weight-bold">@lang('Daily Limit')</label>
                                    <div class="input-group">
                                        <input class="form-control form-control-lg numeric-validation" type="text" name="daily_transfer_limit" value="{{ getAmount($general->daily_transfer_limit )}}">
                                        <div class="input-group-append">
                                            <span class="input-group-text curency-text">
                                                @lang($general->cur_text)
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group ">
                                    <label class="font-weight-bold">@lang('Monthly Limit')</label>
                                    <div class="input-group">
                                        <input class="form-control form-control-lg numeric-validation" type="text" name="monthly_transfer_limit" value="{{ getAmount($general->monthly_transfer_limit )}}">
                                        <div class="input-group-append">
                                            <span class="input-group-text curency-text">
                                                @lang($general->cur_text)
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="card-footer">
                        <div class="form-group">
                            <button type="submit" class="btn btn--primary btn-block btn-lg">@lang('Update')</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('script-lib')
    <script src="{{ asset('assets/admin/js/spectrum.js') }}"></script>
@endpush

@push('style-lib')
    <link rel="stylesheet" href="{{ asset('assets/admin/css/spectrum.css') }}">
@endpush


@push('style')
    <style>
        .sp-replacer {
            padding: 0;
            border: 1px solid rgba(0, 0, 0, .125);
            border-radius: 5px 0 0 5px;
            border-right: none;
        }

        .sp-preview {
            width: 100px;
            height: 46px;
            border: 0;
        }

        .sp-preview-inner {
            width: 110px;
        }

        .sp-dd {
            display: none;
        }
        .select2-container .select2-selection--single {
            height: 44px;
        }
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 43px;
        }
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 43px;
        }
    </style>
@endpush

@push('script')
    <script>
        (function ($) {
            "use strict";
            $('.colorPicker').spectrum({
                color: $(this).data('color'),
                change: function (color) {
                    $(this).parent().siblings('.colorCode').val(color.toHexString().replace(/^#?/, ''));
                }
            });

            $('.colorCode').on('input', function () {
                var clr = $(this).val();
                $(this).parents('.input-group').find('.colorPicker').spectrum({
                    color: clr,
                });
            });

            $('input[name=cur_text').on('input', function(){
                $('.curency-text').text($(this).val())
            })

            $('.select2-basic').select2({
                dropdownParent: $('.card-body')
            });

            $('select[name=timezone]').val();
        })(jQuery);

    </script>
@endpush

