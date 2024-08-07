@extends($activeTemplate.'layouts.master')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-7 col-lg-12">
                <div class="custom--card">
                    <div class="card-body">
                        <h5 class="text-center">
                            @lang('You have requested to invest in FDR')
                        </h5>
                        <p class="text-center text--danger">(@lang('Be Sure Before Confirm'))</p>

                        <ul class="caption-list-two mt-3">
                            <li>
                                <span class="caption">@lang('Plan')</span>
                                <span class="value">@lang($plan->name)</span>
                            </li>
                            <li>
                                <span class="caption">@lang('Profit Rate')</span>
                                <span class="value">{{ getAmount($plan->interest_rate) }}%</span>
                            </li>

                            <li>
                                <span class="caption">@lang('Your Amount')</span>
                                <span class="value">{{ $general->cur_sym.showAmount($amount) }}</span>
                            </li>

                            <li>
                                <span class="caption">@lang('Profit in Every') {{ $plan->interest_interval }} @lang('Days')</span>
                                <span class="value">{{ $general->cur_sym.showAmount($amount*$plan->interest_rate/100) }}</span>
                            </li>

                            <li class="text--danger">
                                <span class="caption">@lang('Can\'t Be Withdrawn Till')</span>
                                <span class="value">{{ showDateTime(Carbon\Carbon::now()->addDays($plan->locked_days), 'd M, Y') }}</span>
                            </li>

                        </ul>

                        <div class="d-flex justify-content-end mt-3">
                            <a href="{{ route('user.action.cancel') }}" class="btn btn-md btn--dark me-3">@lang('Cancel')</a>
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
    <a href="{{ route('user.fdr.plans') }}">@lang('Investment Plans')</a>
</li>
<li>
    <a href="{{ route('user.fdr.list') }}">@lang('My Investment List')</a>
</li>
@endpush