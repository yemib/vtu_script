@extends($activeTemplate.'layouts.master')
@section('content')

<!-- dashboard section start -->

<div class="container">
    <div class="plan-area mb-none-30">
        <div class="row justify-content-center gy-4">
            @forelse($plans as $plan)
                <div class="col-lg-4 col-md-6">
                    <div class="plan-card rounded-3 wow fadeInUp">
                        <div class="plan-card__header">
                            <div class="wave-shape">
                                <img src="{{ asset($activeTemplateTrue. 'images/elements/wave.png') }}" alt="img">
                            </div>
                            <h4 class="plan-name">{{ __($plan->name) }}</h4>
                            <div class="plan-price">
                                {{ getAmount($plan->per_installment) }}% <sub>/{{ $plan->installment_interval }} @lang('Days')</sub>
                            </div>
                        </div>
                        <div class="plan-card__body text-center">
                            <ul class="plan-feature-list">
                                <li class="d-flex flex-wrap justify-content-between">
                                    <span>@lang('Take Minimum')</span>
                                    {{ $general->cur_sym }}{{ showAmount($plan->minimum_amount) }}
                                </li>

                                <li class="d-flex flex-wrap justify-content-between">
                                    <span>@lang('Take Maximum')</span>
                                    {{ $general->cur_sym }}{{ showAmount($plan->maximum_amount) }}
                                </li>

                                <li class="d-flex flex-wrap justify-content-between">
                                    <span>
                                        @lang('Per Installment')
                                    </span>
                                    {{ getAmount($plan->per_installment) }}%
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


                            </ul>
                        </div>
                        <div class="plan-card__footer text-center">
                            <button type="button" data-id="{{ $plan->id }}" data-minimum="{{ $general->cur_sym }}{{ showAmount($plan->minimum_amount) }}" data-maximum="{{ $general->cur_sym }}{{ showAmount($plan->maximum_amount) }}" class="btn btn-md w-100 btn--base loanBtn">@lang('Apply Now')</button>
                        </div>
                    </div><!-- plan-card end -->
                </div>
            @endforeach
        </div>

        {{ $plans->links() }}

    </div><!-- row end -->
</div>
<!-- dashboard section end -->
@endsection

@push('modal')
    <div class="modal fade" id="loanModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <strong class="modal-title method-name" id="exampleModalLabel">@lang('Apply for Loan')</strong>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                    </a>
                </div>
                <form action="{{ route('user.loan.apply') }}" method="post">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" name="id">
                        <div class="form-group">
                            <label for="">@lang('Amount')</label>
                            <input type="text" name="amount" class="form-control" placeholder="@lang('Enter An Amount')">
                            <p><small class="text--danger min-limit mt-2"></small></p>
                            <p><small class="text-danger max-limit"></small></p>
                        </div>
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
            $('.loanBtn').on('click', (e) => {
                var modal = $('#loanModal');
                var $this = $(e.currentTarget);
                modal.find('input[name=id]').val($this.data('id'));
                modal.find('.min-limit').text(`Minimum Amount ${$this.data('minimum')}`);
                modal.find('.max-limit').text(`Maximum Amount ${$this.data('maximum')}`);
                modal.modal('show');
            });
        })(jQuery);
    </script>
@endpush



@push('bottom-menu')
    <li>
        <a href="{{ route('user.loan.plans') }}" class="active">@lang('Loan Plans')</a>
    </li>
    <li>
        <a href="{{ route('user.loan.list') }}">@lang('My Loan List')</a>
    </li>
@endpush
