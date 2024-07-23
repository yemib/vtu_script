@extends($activeTemplate.'layouts.master')
@section('content')
    <div class="container">
        <div class="row justify-content-center mt-4">
            <div class="col-xl-7 col-lg-12">
                <div class="card border">
                    <div class="card-body">
                        <h5 class="text-center">
                            @lang('You have requested to invest in DPS')
                        </h5>
                        <p class="text-center text--danger">(@lang('Be Sure Before Confirm'))</p>

                        <ul class="caption-list-two mt-3">
                            <li>
                                <span class="caption">@lang('Plan')</span>
                                <span class="value">@lang($plan->name)</span>
                            </li>

                            <li>
                                <span class="caption">@lang('Installment Interval')</span>
                                <span class="value">{{ $plan->installment_interval }} @lang('Days')</span>
                            </li>

                            <li>
                                <span class="caption">@lang('Total Installment')</span>
                                <span class="value">{{ $plan->total_installment }}</span>
                            </li>

                            <li>
                                <span class="caption">@lang('Per Installment')</span>
                                <span class="value">{{ $general->cur_sym.showAmount($plan->per_installment) }}</span>
                            </li>

                            <li>
                                <span class="caption">@lang('Total Investment')</span>
                                <span class="value">{{ $general->cur_sym.showAmount($plan->per_installment * $plan->total_installment) }}</span>
                            </li>

                            <li>
                                <span class="caption">@lang('Profit Rate')</span>
                                <span class="value">{{ getAmount($plan->interest_rate) }}%</span>
                            </li>

                            <li>
                                <span class="caption">@lang('Total Investment')</span>
                                <span class="value fw-bold">{{ $general->cur_sym.showAmount($plan->final_amount) }}</span>
                            </li>

                        </ul>

                        <div class="d-flex justify-content-end mt-3">
                            <a href="{{ route('user.action.cancel') }}" class="btn btn-md btn--danger me-3">@lang('Cancel')</a>
                            <form action="" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-md btn--base">@lang('Confirm')</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('bottom-menu')
<li>
    <a href="{{ route('user.dps.plans') }}">@lang('Savings Plans')</a>
</li>
<li>
    <a href="{{ route('user.dps.list') }}">@lang('My Savings List')</a>
</li>
@endpush
