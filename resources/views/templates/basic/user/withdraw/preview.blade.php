@extends($activeTemplate.'layouts.master')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-xxl-4 col-xl-5 col-lg-5">
                <div class="card border--base h-100">
                    <div class="card-body">
                        <ul class="caption-list-two">
                            <li>
                                <span class="caption">@lang('Requested Amount') </span>
                                <span class="value">{{showAmount($withdraw->amount)  }} @lang($general->cur_text)</span>
                            </li>
                            <li class="text--danger">
                                <span class="caption">@lang('Withdrawal Charge') </span>
                                <span class="value">{{showAmount($withdraw->charge) }} @lang($general->cur_text)</span>
                            </li>
                            <li>
                                <span class="caption">@lang('After Charge') </span>
                                <span class="value">{{showAmount($withdraw->after_charge) }} @lang($general->cur_text)</span>
                            </li>
                            <li>
                                <span class="caption">@lang('Conversion Rate') </span>
                                <span class="value"> 1 @lang($general->cur_text) = {{showAmount($withdraw->rate)  }} @lang($withdraw->currency)</span>
                            </li>
                            <li>
                                <span class="caption">@lang('You Will Get') </span>
                                <span class="value">{{showAmount($withdraw->final_amount) }} @lang($withdraw->currency)</span>
                            </li>
                            <li>
                                <span class="caption">@lang('Current Balance') </span>
                                <span class="value">{{ showAmount(auth()->user()->balance)}} @lang($general->cur_text) </span>
                                </span>
                            </li>
                            <li>
                                <span class="caption">@lang('Balance Will be') </span>
                                <span class="value">{{showAmount(auth()->user()->balance - ($withdraw->amount))}} @lang($general->cur_text) </span>
                                </span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="col-xxl-8 col-xl-7 col-lg-7 mt-3 mt-lg-0">
                <div class="card border--base">
                    <div class="card-body">
                        <form action="{{route('user.withdraw.submit')}}" method="post" enctype="multipart/form-data">
                            @csrf
                            @if($withdraw->method->user_data)
                            @foreach($withdraw->method->user_data as $k => $v)
                                @if($v->type == "text")
                                    <div class="form-group">
                                        <label><strong>@lang($v->field_level) @if($v->validation == 'required') <span class="text--danger">*</span>  @endif</strong></label>
                                        <input type="text" name="{{$k}}" class="form--control" value="{{old($k)}}" placeholder="@lang($v->field_level)" @if($v->validation == "required") required @endif>
                                        @if ($errors->has($k))
                                            <span class="text--danger">{{ __($errors->first($k)) }}</span>
                                        @endif
                                    </div>
                                @elseif($v->type == "textarea")
                                    <div class="form-group">
                                        <label><strong>@lang($v->field_level) @if($v->validation == 'required') <span class="text--danger">*</span>  @endif</strong></label>
                                        <textarea name="{{$k}}"  class="form-control"  placeholder="@lang($v->field_level)" rows="3" @if($v->validation == "required") required @endif>{{old($k)}}</textarea>
                                        @if ($errors->has($k))
                                            <span class="text--danger">{{ __($errors->first($k)) }}</span>
                                        @endif
                                    </div>
                                @elseif($v->type == "file")
                                    <label><strong>@lang($v->field_level) @if($v->validation == 'required') <span class="text--danger">*</span>  @endif</strong></label>
                                    <div class="form-group">
                                        <div class="fileinput fileinput-new w-100" data-provides="fileinput">
                                            <div class="fileinput-new thumbnail withdraw-thumbnail"
                                                    data-trigger="fileinput">
                                                <img class="w-100" src="{{ getImage('/')}}" alt="@lang('Image')">
                                            </div>
                                            <div class="fileinput-preview fileinput-exists thumbnail wh-200-150"></div>
                                            <div class="img-input-div">
                                                <span class="btn-file overflow-visible">
                                                    <span class="fileinput-new"> @lang('Select') @lang($v->field_level)</span>
                                                    <span class="btn btn-sm px-4 btn--base fileinput-exists"> @lang('Change')</span>
                                                    <input type="file" name="{{$k}}" accept="image/*" @if($v->validation == "required") required @endif>
                                                </span>
                                                <a href="#" class="btn btn-sm px-4 btn--danger fileinput-exists"
                                                data-dismiss="fileinput"> @lang('Remove')</a>
                                            </div>
                                        </div>
                                        @if ($errors->has($k))
                                            <br>
                                            <span class="text--danger">{{ __($errors->first($k)) }}</span>
                                        @endif
                                    </div>
                                @endif
                            @endforeach
                            @endif
                            <div class="form-group">
                                <button type="submit" class="btn custom--bg w-100 text-white mt-4 text-center">@lang('Confirm')</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


@push('style')
<style>
    .d-widget:hover{
        transform: translateY(0px) !important;
    }
    @media(max-width: 420px){
        .d-widget__content{
            text-align: start !important;
        }
    }
    .withdraw-thumbnail{
        max-width: 220px;
        max-height: 220px
    }
    .fileinput .thumbnail > img{
        max-width: 100%;
        max-height: 250px;
    }
</style>
@endpush

@push('script-lib')
<script src="{{asset($activeTemplateTrue.'/js/bootstrap-fileinput.js')}}"></script>
@endpush
@push('style-lib')
<link rel="stylesheet" href="{{asset($activeTemplateTrue.'/css/bootstrap-fileinput.css')}}">
@endpush

@push('script')
<script>
    (function($){
        "use strict";
            $('.withdraw-thumbnail').hide();
            $('.clickBtn').on('click', function() {
                var classNmae = $('.fileinput').attr('class');
                if(classNmae != 'fileinput fileinput-exists'){
                    $('.withdraw-thumbnail').hide();
                }else{
                    $('.fileinput-preview img').css({"width":"100%", "height":"300px", "object-fit":"contain"});

                    $('.withdraw-thumbnail').show();
                }

            });

    })(jQuery);
</script>
@endpush
