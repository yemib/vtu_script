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
                                    <th>@lang('Loan No. | Plan')</th>
                                    <th>@lang('Amount')</th>
                                    <th>@lang('Installment Amount')</th>
                                    <th>@lang('Installment')</th>
                                    <th>@lang('Next Installment')</th>
                                    <th>@lang('Paid')</th>
                                    <th>@lang('Status')</th>
                                </tr>
                            </thead>
                            <tbody>

                                @forelse($loans as $loan)
                                <tr>
                                    <td data-label="@lang('Loan No. | Plan')">
                                        <strong>{{ __($loan->trx) }}</strong>
                                        <small class="d-block text--base">{{ __($loan->plan->name) }}</small>
                                    </td>

                                    <td data-label="@lang('Amount')">
                                        <strong>
                                            {{ $general->cur_sym.showAmount($loan->amount) }}
                                        </strong>
                                        <small class="d-block text--base">
                                            {{ $general->cur_sym.showAmount($loan->final_amount) }} @lang('Need to pay')
                                        </small>

                                    </td>

                                    <td data-label="@lang('Installment Amount')">
                                        <strong>{{ $general->cur_sym.showAmount($loan->per_installment) }}</strong>
                                        <small class="d-block text--base">
                                            @lang("In Every") {{ $loan->installment_interval }} @lang("Days")
                                        </small>
                                    </td>

                                    <td data-label="@lang('Installment')">
                                        <strong>
                                            @lang('Total') : {{ $loan->total_installment }}
                                        </strong>
                                        <small class="d-block text--base">
                                            @lang('Given') : {{ $loan->given_installment }}
                                        </small>
                                    </td>

                                    <td data-label="@lang('Next Installment')">
                                        @if($loan->next_installment_date)
                                        <strong>{{ showDateTime($loan->next_installment_date, 'd M, Y') }}</strong>
                                        <small class="d-block text--base">
                                            {{ diffForHumans($loan->next_installment_date, 'd M, Y') }}
                                        </small>
                                        @else
                                        @lang('...')
                                        @endif
                                    </td>

                                    <td data-label="@lang('Paid')">
                                        <strong>{{ $general->cur_sym.showAmount($loan->paid_amount) }}</strong>
                                        <span class="d-block text--base">
                                            @php
                                                $remainingAmount = $loan->final_amount - $loan->paid_amount;
                                            @endphp
                                            <small class="text--base">
                                                {{ $general->cur_sym.showAmount($remainingAmount) }} @lang('Remaining')
                                            </small>
                                        </span>
                                    </td>

                                    <td data-label="@lang('Status')">
                                        @if($loan->status == 0)
                                            <span class="badge badge--warning">@lang('Requested')</span>
                                        @elseif($loan->status == 1)
                                            <span class="badge badge--info">@lang('Running')</span>
                                        @elseif($loan->status == 2)
                                            <span class="badge badge--success">@lang('Paid')</span>

                                        @elseif($loan->status == 3)
                                            <span class="badge badge--danger">@lang('Rejected')</span>
                                            <span class="admin-feedback" data-feedback="{{ __($loan->admin_feedback) }}"><i class="fas fa-info-circle"></i></span>
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
                        {{ $loans->links() }}
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
                    <strong class="modal-title">@lang('Confirmation Alert')!</strong>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </a>
                </div>
                <form action="{{ route('user.fdr.close') }}" method="post">
                    @csrf
                    <input type="hidden" name="user_token" required>
                    <div class="modal-body">
                        <div class="form-group">
                            <input type="hidden" name="id" class="transferId" required>
                        </div>
                        <div class="content">
                          <p>@lang('Are You Sure To Close This Investment')?</p>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-md btn--danger text-white" data-bs-dismiss="modal">@lang('No')</button>
                        <button type="submit" class="btn btn-md custom--bg text-white">@lang('Yes')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="adminFeedback">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <strong class="modal-title">@lang('Reason of Rejection')!</strong>
                </div>
                <div class="modal-body">

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-md btn--danger text-white" data-bs-dismiss="modal">@lang('Close')</button>
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

            $('.admin-feedback').on('click', function(){
                var modal = $('#adminFeedback');

                console.log($(this).data('feedback'));

                modal.find('.modal-body').text($(this).data('feedback'));
                modal.modal('show');
            });

        })(jQuery);
    </script>
@endpush


@push('bottom-menu')
    <li>
        <a href="{{ route('user.loan.plans') }}">@lang('Loan Plans')</a>
    </li>
    <li>
        <a href="{{ route('user.loan.list') }}" class="active">@lang('My Loan List')</a>
    </li>
@endpush
