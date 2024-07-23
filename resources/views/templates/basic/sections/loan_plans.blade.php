@php
    $loan = getContent('loan_plans.content', true);
    $plans = App\Models\LoanPlan::active()->latest()->limit(3)->get();
@endphp

@if($plans->count())
    <section class="pt-100 pb-100">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xl-5 col-lg-7">
                    <div class="section-header text-center">
                        <div class="section-top-title border-left custom--cl">{{ __(@$loan->data_values->title) }}</div>
                        <h2 class="section-title">{{ __(@$loan->data_values->heading) }}</h2>
                    </div>
                </div>
            </div><!-- row end -->

            <div class="row justify-content-center gy-4">
                @forelse($plans as $plan)
                    <div class="col-lg-4 col-md-6">
                        <div class="plan-card rounded-3 wow fadeInUp">
                            <span class="fdr-badge">@lang('LOAN')</span>
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
                                @auth
                                    <button type="button" data-id="{{ $plan->id }}" data-minimum="{{ $general->cur_sym }}{{ showAmount($plan->minimum_amount) }}" data-maximum="{{ $general->cur_sym }}{{ showAmount($plan->maximum_amount) }}" class="btn btn-md w-100 btn--base loanBtn">@lang('Apply Now')</button>
                                @else
                                    <a href="{{route('user.login')}}" class="btn btn-md w-100 btn--base">@lang('Apply Now')</a>
                                @endauth
                            </div>
                        </div><!-- plan-card end -->
                    </div>
                @endforeach

                <div class="col-12 d-flex justify-content-center">
                    <a href="{{ route('user.loan.plans') }}" class="btn btn--base">@lang('View More')</a>
                </div>
            </div>
        </div>
    </section>

    @auth
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
    @endauth

    @push('script')
        <script>
            (function ($) {
                "use strict";
                $('.loanBtn').on('click', (e) => {
                    var modal = $('#loanModal');
                    var $this = $(e.currentTarget);
                    modal.find('.min-limit').text(`Minimum Amount ${$this.data('minimum')}`);
                    modal.find('.max-limit').text(`Maximum Amount ${$this.data('maximum')}`);
                    modal.modal('show');
                });
            })(jQuery);
        </script>
    @endpush
@endif
