@extends('admin.layouts.app')

@section('panel')
<div class="row">

    <div class="col-lg-12">
        <div class="card">
            <div class="card-body p-0">
                <div class="table-responsive--md table-responsive">
                    <table class="table table--light style--two">
                        <thead>
                            <tr>
                                <th>@lang('FDR No. | Plan')</th>
                                <th>@lang('User')</th>
                                <th>@lang('Amount')</th>
                                <th>@lang('Profit')</th>
                                <th>@lang('Next Installment')</th>
                                <th>@lang('Lock In Period')</th>
                                <th>@lang('Status')</th>
                                <th>@lang('Action')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($data as $fdr)
                                <tr>
                                    <td data-label="@lang('FDR No.')">
                                        <span class="font-weight-bold">{{ __($fdr->trx) }}</span>
                                        <span class="d-block text--info">{{ __($fdr->plan->name) }}</span>
                                    </td>

                                    <td data-label="@lang('User')">
                                        <span class="font-weight-bold d-block">{{ $fdr->user->fullname }}</span>
                                        <span class="small">
                                        <a href="{{ route('admin.users.detail', $fdr->user_id) }}"><span>@</span>{{ $fdr->user->username }}</a>
                                        </span>
                                    </td>

                                    <td data-label="@lang('Amount')">
                                        <span>{{ $general->cur_sym.showAmount($fdr->amount) }}</span>
                                        <span class="d-block text--info">@lang('Profit') {{ getAmount($fdr->plan->interest_rate) }}% </span>
                                    </td>

                                    <td data-label="@lang('Profit')">
                                        <span>{{ $general->cur_sym.showAmount($fdr->interest) }}</span>
                                        <span class="text--info d-block">@lang('Per') {{ $fdr->interest_interval }} @lang('Days')</span>
                                    </td>

                                    <td data-label="@lang('Next Installment')">
                                        <span>
                                            {{ showDateTime($fdr->next_return_date, 'd M, Y') }}
                                        </span>
                                        <span class="d-block text--info">{{ diffForHumans($fdr->next_return_date, 'd M, Y') }}</span>
                                    </td>

                                    <td data-label="@lang('Lock In Period')">
                                        <span>
                                            {{ showDateTime($fdr->locked_date, 'd M, Y') }}
                                        </span>
                                        <span class="d-block text--info">{{ diffForHumans($fdr->locked_date, 'd M, Y') }}</span>

                                    </td>

                                    <td data-label="@lang('Status')">
                                        @if($fdr->status == 1)
                                            <span class="badge badge--success">@lang('Running')</span>
                                        @elseif($fdr->status == 2)
                                            <span class="badge badge--danger">@lang('Completed')</span>
                                        @endif
                                    </td>

                                    <td data-label="@lang('Action')">
                                        <a href="{{ route('admin.fdr.isntallments', $fdr->id) }}" class="icon-btn" data-toggle="tooltip" data-title="Installment History">
                                            <i class="las la-desktop"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                            <tr>
                                <td class="text-muted text-center" colspan="100%">{{ __($emptyMessage) }}</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table><!-- table end -->
                </div>
            </div>
            @if($data->hasPages())
                <div class="card-footer py-4">
                {{ paginateLinks($data) }}
                </div>
            @endif
        </div><!-- card end -->
    </div>
</div>
@endsection


@push('breadcrumb-plugins')
    <form action="" method="GET" class="form-inline float-sm-right bg--white">
        <div class="input-group has_append">
            <input type="text" name="search" class="form-control" placeholder="@lang('FDR No.')" value="{{ request()->search ?? '' }}">
            <div class="input-group-append">
                <button class="btn btn--primary" type="submit"><i class="fa fa-search"></i></button>
            </div>
        </div>
    </form>
@endpush
