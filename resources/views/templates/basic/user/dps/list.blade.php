@extends($activeTemplate.'layouts.master')
@section('content')
<div class="container">
    <div class="row justify-content-center mb-none-30">
        <div class="col-lg-12">
            <div class="custom--card">
                <div class="card-body p-0">
                    <div class="table-responsive--md">
                        <table class="table custom--table">
                            <thead>
                                <tr>
                                    <th>@lang('No. | Plan')</th>
                                    <th>@lang('Amount to Pay')</th>
                                    <th>@lang('Installment')</th>
                                    <th>@lang('Next Installment')</th>
                                    <th>@lang('After Matured')</th>
                                    <th>@lang('Status')</th>
                                    <th>@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody>

                                @forelse($allDps as $dps)
                                <tr>
                                    <td data-label="@lang('DPS No. | Plan')">
                                        <strong>{{ __($dps->trx) }}</strong>
                                        <small class="d-block text--base">{{ __($dps->plan->name) }}</small>
                                    </td>

                                    <td data-label="@lang('Amount to Pay')">
                                        <strong>{{ $general->cur_sym.showAmount($dps->per_installment) }}</strong>
                                        <small class="text--base d-block">@lang('Per') {{ $dps->installment_interval }} @lang('days')</small>
                                    </td>

                                    <td data-label="@lang('Installment')">
                                        <strong>@lang('Total') : {{ $dps->total_installment }}</strong>
                                        <small class="text--base d-block">@lang('Given') : {{ $dps->given_installment }}</small>
                                    </td>

                                    <td data-label="@lang('Next Installment')">
                                        <strong>{{ showDateTime($dps->next_installment_date, 'd M, Y') }}</strong>
                                        <small class="text--base d-block">
                                            {{ diffForHumans($dps->next_installment_date) }}
                                        </small>
                                    </td>

                                    <td data-label="@lang('After Matured')">
                                        @php
                                            $totalDeposit   = $dps->per_installment * $dps->total_installment;
                                            $profit         = $totalDeposit * $dps->interest_rate / 100 ;
                                        @endphp
                                        <span class="fw-bold">
                                            {{ $general->cur_sym.showAmount($totalDeposit + $profit) }}
                                        </span>
                                        <small class="text--base d-block">
                                            {{ $general->cur_sym.showAmount($totalDeposit) }}
                                            + {{ getAmount($dps->interest_rate) }}%
                                        </small>
                                    </td>

                                    <td data-label="@lang('Status')">
                                        @if($dps->status == 1)
                                            <span class="badge badge--success">@lang('Running')</span>
                                        @elseif($dps->status == 2)
                                            <span class="badge badge--warning">@lang('Matured')</span>
                                        @elseif($dps->status == 0)
                                            <span class="badge badge--danger">@lang('Closed')</span>
                                        @endif
                                    </td>

                                    <td data-label="@lang('Action')">

                                        @if($dps->status == 1)
                                            <button type="button" data-bs-toggle="tooltip" title="@lang('Close')" class="btn btn--base btn-sm cantCloseBtn">
                                                <i class="la la-wallet"></i>
                                            </button>
                                        @elseif($dps->status == 2)
                                            <button type="button" data-bs-toggle="tooltip" title="@lang('Close')" data-token="{{ encrypt($dps->id) }}" class="btn btn--base btn-sm closeBtn">
                                                <i class="fas fa-money-bill-alt"></i>
                                            </button>
                                        @elseif($dps->status == 0)
                                            <button type="button" data-bs-toggle="tooltip" title="@lang('Close')" class="btn btn--base btn-sm" disabled>
                                                <i class="la la-wallet"></i>
                                            </button>
                                        @endif

                                    </td>

                                </tr>
                                @empty
                                <tr>
                                    <td colspan="100%" class="text-center">{{ __($emptyMessage) }}</td>
                                </tr>
                                @endforelse

                            </tbody>
                        </table>
                        {{ $allDps->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- dashboard section end -->
    @push('modal')
    <div class="modal fade" id="closeFdr" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <strong class="modal-title">@lang('Close DPS')!</strong>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </a>
                </div>
                <form action="{{ route('user.dps.close') }}" method="post">
                    @csrf
                    <input type="hidden" name="user_token" required>
                    <div class="modal-body">
                        <div class="content">
                          <p>@lang('Are you sure to withdraw the dps amount?')</p>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-md btn-danger text-white" data-bs-dismiss="modal">@lang('No')</button>
                        <button type="submit" class="btn btn-md custom--bg text-white">@lang('Yes')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="cantCloseFdr" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <strong class="modal-title">@lang('Close DPS')</strong>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </a>
                </div>
                <div class="modal-body py-4">
                    <h5 class="text-center text--danger">
                        @lang('Sorry! you can\'t close a DPS before mature') <strong class="date"></strong> </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endpush
@endsection

@push('script')
    <script>
        (function($){
            "use strict";
            $('.closeBtn').on('click', function() {
                var modal = $('#closeFdr');
                modal.find('input[name=user_token]').val($(this).data('token'));
                modal.modal('show');
            });

            $('.cantCloseBtn').on('click', function() {
                var modal = $('#cantCloseFdr');
                modal.find('.date').text($(this).data('date'));

                modal.modal('show');
            });

        })(jQuery);
    </script>
@endpush


@push('bottom-menu')
    <li>
        <a href="{{ route('user.dps.plans') }}">@lang('Savings Plans')</a>
    </li>
    <li>
        <a href="{{ route('user.dps.list') }}" class="active">@lang('My Savings List')</a>
    </li>
@endpush
