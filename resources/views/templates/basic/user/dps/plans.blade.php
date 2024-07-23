@extends($activeTemplate.'layouts.master')
@section('content')
    <div class="container">
        <div class="plan-area mb-none-30">
            <div class="row justify-content-center gy-4">
                @foreach($plans as $plan)
                    <div class="col-lg-4 col-md-6">

                        <div class="plan-card rounded-3 wow fadeInUp">
                            <div class="plan-card__header">
                                <div class="wave-shape">
                                    <img src="{{ asset($activeTemplateTrue. 'images/elements/wave.png') }}" alt="img">
                                </div>
                                <h4 class="plan-name">{{ __($plan->name) }}</h4>
                                <div class="plan-price">
                                    {{ $general->cur_sym.getAmount($plan->per_installment) }}
                                    <sub>/{{ $plan->installment_interval }}@lang('Days')</sub>
                                </div>
                            </div>

                            <div class="plan-card__body text-center">
                                <ul class="plan-feature-list">
                                    <li class="d-flex flex-wrap justify-content-between">
                                        <span>
                                            @lang('Interest Rate')
                                        </span>
                                        {{ getAmount($plan->interest_rate) }}%
                                    </li>

                                    <li class="d-flex flex-wrap justify-content-between">
                                        <span>
                                            @lang('Installment Interval')
                                        </span>
                                        {{ $plan->installment_interval }} @lang('Days')
                                    </li>

                                    <li class="d-flex flex-wrap justify-content-between">
                                        <span>
                                            @lang('Total Installment')
                                        </span>
                                        {{ $plan->total_installment }}
                                    </li>

                                    <li class="d-flex flex-wrap justify-content-between">
                                        <span>
                                            @lang('Deposit Amount')
                                        </span>
                                        {{ $general->cur_sym.showAmount($plan->total_installment * $plan->per_installment) }}
                                    </li>

                                    <li class="d-flex flex-wrap justify-content-between">
                                        <span>
                                            @lang('You Will Get')
                                        </span>
                                        {{ $general->cur_sym.showAmount($plan->final_amount) }}
                                    </li>
                                </ul>
                            </div>
                            <div class="plan-card__footer text-center">
                                @auth
                                    <button type="button" data-id="{{ $plan->id }}" class="btn btn-md w-100 btn--base dpsBtn">@lang('Apply Now')</button>
                                @else
                                    <a href="{{route('user.login')}}" class="btn btn-md w-100 btn--base">@lang('Apply Now')</a>
                                @endauth
                            </div>
                        </div><!-- plan-card end -->
                    </div>
                @endforeach
            </div>

            {{ $plans->links() }}

        </div>
    </div>
@endsection

@push('modal')
    <div class="modal fade" id="dpsModal">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <strong class="modal-title method-name" id="exampleModalLabel">@lang('Apply to Open a DPS')</strong>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                    </a>
                </div>
                <form action="{{ route('user.action') }}" method="post">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" name="id">
                        <input type="hidden" name="type" value="dps">
                        @if(checkIsOtpEnable($general))
                            @include($activeTemplate.'partials.otp_field')
                        @else
                            @lang('Are you sure to apply for this plan?')
                        @endif
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-md btn-danger text-white"
                            data-bs-dismiss="modal">@lang('Close')</button>
                        <button type="submit" class="btn btn-md custom--bg text-white">@lang('Confirm')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endpush


@push('script')
    <script>
        (function ($) {
            "use strict";
            $('.dpsBtn').on('click', (e) => {
                var modal = $('#dpsModal');
                var $this = $(e.currentTarget);
                modal.find('input[name=id]').val($this.data('id'));
                modal.modal('show');
            });
        })(jQuery);
    </script>
@endpush

@push('bottom-menu')
<li>
    <a href="{{ route('user.dps.plans') }}" class="active">@lang('Savings Plans')</a>
</li>
<li>
    <a href="{{ route('user.dps.list') }}">@lang('My Savings List')</a>
</li>
@endpush
