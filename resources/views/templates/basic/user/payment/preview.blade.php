@extends($activeTemplate.'layouts.master')
@section('content')
    <div class="container">
      <div class="row justify-content-center mb-none-30">

            <div class="col-xl-4 col-lg-5 col-md-7 col-sm-9">
                <div class="card border--base">
                    <div class="card-body">
                        <ul class="caption-list text-center">
                            <li class="justify-content-center">
                                <img src="{{ $data->gatewayCurrency()->methodImage() }}" alt="@lang('Image')" class="w-50 mb-3"/>
                            </li>
                            <li class="justify-content-between">
                                @lang('Amount'):
                                <strong>
                                    {{getAmount($data->amount)}} {{__($general->cur_text)}}
                                </strong>
                            </li>
                            <li class="justify-content-between">
                                @lang('Charge'):
                                <strong>
                                    {{getAmount($data->charge)}} {{__($general->cur_text)}}
                                </strong>
                            </li>
                            <li class="justify-content-between">
                                @lang('Payable'): <strong> {{getAmount($data->amount + $data->charge)}}</strong>
                            </li>
                            <li class="justify-content-between">
                                @lang('Conversion Rate'): <strong>1 {{__($general->cur_text)}} = {{getAmount($data->rate)}}  {{__($data->baseCurrency())}}</strong>
                            </li>
                            <li class="justify-content-between">
                                @lang('In') {{$data->baseCurrency()}}:
                                <strong>{{getAmount($data->final_amo)}}</strong>
                            </li>

                            @if($data->gateway->crypto==1)
                                <li class="justify-content-center">
                                    @lang('Conversion with') &nbsp; <b> {{ __($data->method_currency) }}</b> @lang('and final value will Show on next step')
                                </li>
                            @endif
                        </ul>

                        <div class="mt-3">
                            @if( 1000 >$data->method_code)
                                <a href="{{route('user.deposit.confirm')}}" class="btn btn--base w-100">@lang('Pay Now')</a>
                            @else
                                <a href="{{route('user.deposit.manual.confirm')}}" class="btn btn--base w-100">@lang('Pay Now')</a>
                            @endif
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</section>
@endsection


