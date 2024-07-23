@extends($activeTemplate.'layouts.master')
@section('content')
<!-- dashboard section start -->
<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border--base">
                <div class="card-body">
                    <form action="" method="post" enctype="multipart/form-data">
                        @csrf

                        <h3 class="text-center mb-3">@lang('Be sure before apply')</h3>

                        <ul class="caption-list-two">
                            <li>
                                <span class="caption">@lang('Plan Name')</span>
                                <span class="value">@lang($plan->name)</span>
                            </li>
                            <li>
                                <span class="caption">@lang('Loan Amount')</span>
                                <span class="value">{{ $general->cur_sym.showAmount($amount) }}</span>
                            </li>
                            <li>
                                <span class="caption">@lang('Total Installment')</span>
                                <span class="value">{{ $plan->total_installment }}</span>
                            </li>

                            @php
                                $per_intallment = $amount * $plan->per_installment / 100;
                            @endphp

                            <li>
                                <span class="caption">@lang('Per Installment')</span>
                                <span class="value">{{ $general->cur_sym.showAmount($per_intallment) }}</span>
                            </li>

                            <li class="fw-bold text--danger">
                                <span class="caption">@lang('You Need To Pay')</span>
                                <span class="value">{{ $general->cur_sym.showAmount($per_intallment * $plan->total_installment) }}</span>
                            </li>

                        </ul>
                        <div class="form-group">
                            @php echo $plan->instruction @endphp
                        </div>

                            @if($requiredInfo)
                                @foreach($requiredInfo as $item)

                                    @if($item->type == 'text')
                                    <div class="form-group">
                                        <label for="field_{{ snakeCase($item->field_name) }}">
                                            {{ $item->field_name }}
                                            @if($item->validation == 'required') <span class="text--danger">*</span> @endif
                                        </label>
                                        <input class="form--control" type="{{ $item->type }}" id="field_{{ snakeCase($item->field_name) }}" name="{{ snakeCase($item->field_name) }}" @if($item->validation =='required') required @endif>
                                    </div>
                                    @elseif($item->type == 'textarea')

                                        <div class="form-group">
                                            <label for="field_{{ snakeCase($item->field_name) }}">
                                                {{ $item->field_name }}
                                                @if($item->validation == 'required') <span class="text--danger">*</span> @endif
                                            </label>
                                            <textarea class="form--control" type="{{ $item->type }}" id="field_{{ snakeCase($item->field_name) }}" name="{{ snakeCase($item->field_name) }}" @if($item->validation =='required') required @endif> </textarea>
                                        </div>

                                    @elseif($item->type == 'file')
                                    <div class="form-group">
                                        <label class="w-100">{{ $item->field_name }} @if($item->validation == 'required') <span class="text--danger">*</span> @endif</label>

                                        <div class="fileinput fileinput-new w-100" data-provides="fileinput">
                                            <div class="fileinput-new thumbnail withdraw-thumbnail"
                                                    data-trigger="fileinput">
                                                <img class="w-100" src="{{ getImage('/')}}" alt="@lang('Image')">
                                            </div>
                                            <div class="fileinput-preview fileinput-exists thumbnail"></div>
                                            <div class="img-input-div">
                                                <span class="btn-file overflow-visible">
                                                    <span class="fileinput-new"> @lang('Select') {{__($item->field_name)}}</span>
                                                    <span class="btn btn-sm px-4 btn--base fileinput-exists"> @lang('Change')</span>
                                                    <input type="file" name="{{ snakeCase($item->field_name) }}" accept="image/*" @if($item->validation == "required") required @endif>
                                                </span>
                                                <a href="#" class="btn btn-sm px-4 btn--danger fileinput-exists"
                                                data-dismiss="fileinput"> @lang('Remove')</a>
                                            </div>
                                        </div>

                                    </div>
                                    @endif
                                @endforeach
                            @endif

                            <button type="submit" class="btn btn--base w-100">@lang('Apply')</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- dashboard section end -->
@endsection


@push('bottom-menu')
    <li>
        <a href="{{ route('user.loan.plans') }}">@lang('Loan Plans')</a>
    </li>
    <li>
        <a href="{{ route('user.loan.list') }}">@lang('My Loan List')</a>
    </li>
@endpush


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
