@extends('admin.layouts.app')

@section('panel')
    <div class="row">

        <div class="col-lg-12">
            <div class="card">
                <div class="card-body p-0">
                    <div class="table-responsive--lg table-responsive">
                        <table class="table table--light style--two">
                            <thead>
                                <tr>
                                    <th>@lang('Loan No. | Plan')</th>
                                    <th>@lang('User')</th>
                                    <th>@lang('Amount')</th>
                                    <th>@lang('Installment Amount')</th>
                                    <th>@lang('Installment')</th>
                                    <th>@lang('Next Installment')</th>
                                    <th>@lang('Status')</th>
                                    <th>@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($loans as $loan)
                                    <tr>
                                        <td data-label="@lang('Loan No. | Plan')">
                                            <span class="font-weight-bold">{{ __($loan->trx) }}</span>
                                            <span class="d-block text--info">{{ __($loan->plan->name) }}</span>
                                        </td>

                                        <td data-label="@lang('User')">
                                            <span class="font-weight-bold d-block">{{ $loan->user->fullname }}</span>
                                            <span class="small">
                                            <a href="{{ route('admin.users.detail', $loan->user_id) }}"><span>@</span>{{ $loan->user->username }}</a>
                                            </span>
                                        </td>

                                        <td data-label="@lang('Amount')">
                                            <span>
                                                {{ $general->cur_sym.showAmount($loan->amount) }}
                                            </span>
                                            <span class="d-block text--info">
                                                {{ $general->cur_sym.showAmount($loan->final_amount) }} @lang('Receivable')
                                            </span>

                                        </td>

                                        <td data-label="@lang('Installment Amount')">
                                            <span>{{ $general->cur_sym.showAmount($loan->per_installment) }}</span>
                                            <span class="d-block text--info">
                                                @lang("per") {{ $loan->installment_interval }} @lang("days")
                                            </span>
                                        </td>

                                        <td data-label="@lang('Installment')">
                                            <span>
                                                @lang('Total') : {{ $loan->total_installment }}
                                            </span>
                                            <span class="d-block text--info">
                                                @lang('Given') : {{ $loan->given_installment }}
                                            </span>
                                        </td>


                                        <td data-label="@lang('Next Installment')">
                                            @if($loan->next_installment_date)
                                            <span>{{ showDateTime($loan->next_installment_date, 'd M, Y') }}</span>
                                            <span class="d-block text--info">
                                                {{ diffForHumans($loan->next_installment_date, 'd M, Y') }}
                                            </span>
                                            @else
                                            @lang('...')
                                            @endif
                                        </td>

                                        
                                        <td data-label="@lang('Status')">
                                            @if($loan->status == 0)
                                                <span class="badge badge--dark">@lang('Pending')</span>
                                            @elseif($loan->status == 1)
                                                <span class="badge badge--warning">@lang('Running')</span>
                                            @elseif($loan->status == 2)
                                                <span class="badge badge--success">@lang('Paid')</span>

                                            @elseif($loan->status == 3)
                                                <span class="badge badge--danger">@lang('Rejected')</span>
                                                <span class="admin-feedback" data-feedback="{{ __($loan->admin_feedback) }}"><i class="fas fa-info-circle"></i></span>
                                            @endif
                                        </td>

                                        <td data-label="@lang('Action')">
                                            <a href="{{ route('admin.loan.details', $loan->id) }}" class="icon-btn">
                                                <i class="las la-desktop"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="100%" class="text-center">{{ __($emptyMessage) }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table><!-- table end -->
                    </div>
                </div>
                @if($loans->hasPages())
                    <div class="card-footer py-4">
                    {{ paginateLinks($loans) }}
                    </div>
                @endif
            </div><!-- card end -->
        </div>
    </div>


{{-- STATUS METHOD MODAL --}}
<div id="statusModal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">@lang('Confirmation')!</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('admin.loan.plan.status') }}" method="POST">
                @csrf
                <input type="hidden" name="id">
                <div class="modal-body">

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn--dark" data-dismiss="modal">@lang('No')</button>
                    <button type="submit" class="btn btn--primary">@lang('Yes')</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('script')
<script>
    (function ($) {

        "use strict";

        $('.statusBtn').on('click', (e)=> {
            var btn     = $(e.currentTarget);
            var modal   = $('#statusModal');
            var status  = btn.data('status');
            var message = status == 1 ? '@lang("Are you sure to disable this plan?")':'@lang("Are you sure to enable this plan?")';
            modal.find('.modal-body').text(message);
            modal.find('input[name=id]').val(btn.data('id'));
            modal.modal('show');
        });

    })(jQuery);
</script>
@endpush


@push('breadcrumb-plugins')
    <form action="" method="GET" class="form-inline float-sm-right bg--white">
        <div class="input-group has_append">
            <input type="text" name="search" class="form-control" placeholder="@lang('Loan No.')" value="{{ request()->search ?? '' }}">
            <div class="input-group-append">
                <button class="btn btn--primary" type="submit"><i class="fa fa-search"></i></button>
            </div>
        </div>
    </form>
@endpush
