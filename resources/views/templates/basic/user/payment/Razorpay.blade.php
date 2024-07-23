@extends($activeTemplate.'layouts.master')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-4 col-lg-5 col-md-7 col-sm-9">
                <div class="card border--base">
                    <div class="card-body text-center">
                        <img src="{{$deposit->gatewayCurrency()->methodImage()}}" class="w-50" alt="@lang('Image')">
                        <form action="{{$data->url}}" method="{{$data->method}}">
                            <h4 class="text-center mt-4">@lang('Please Pay') {{getAmount($deposit->final_amo)}} {{$deposit->method_currency}}</h4>
                            <h4 class="my-3 text-center">@lang('To Get') {{getAmount($deposit->amount)}}  {{__($general->cur_text)}}</h4>
                            <script src="{{$data->checkout_js}}"
                                @foreach($data->val as $key=>$value)
                                    data-{{$key}}="{{$value}}"
                                @endforeach >
                            </script>
                            <input type="hidden" custom="{{$data->custom}}" name="hidden">
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


@push('script')
    <script>
        (function ($) {
            "use strict";
            $('input[type="submit"]').addClass("ml-4 mt-4 btn custom--bg w-100 text-white");
        })(jQuery);
    </script>
@endpush
