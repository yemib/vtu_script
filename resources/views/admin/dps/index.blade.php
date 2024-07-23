@extends('admin.layouts.app')

@section('panel')
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body p-0">
                <div class="table-responsive--sm table-responsive">
                    <table class="table table--light style--two">
                        <thead>
                            <tr>
                                <th>@lang('DPS No. | Plan')</th>
                                <th>@lang('User')</th>
                                <th>@lang('Amount to Pay')</th>
                                <th>@lang('Installment')</th>
                                <th>@lang('Next Installment')</th>
                                <th>@lang('After Matured')</th>
                                <th>@lang('Status')</th>
                                <th>@lang('Action')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($data as $dps)
                            <tr>
                                <td data-label="@lang('DPS No. | Plan')">
                                    <span class="font-weight-bold">{{ __($dps->trx) }}</span>
                                    <span class="d-block text--info">{{ __($dps->plan->name) }}</span>
                                </td>
                                <td data-label="@lang('User')">
                                    <span class="font-weight-bold d-block">{{ $dps->user->fullname }}</span>
                                    <span class="small">
                                    <a href="{{ route('admin.users.detail', $dps->user_id) }}"><span>@</span>{{ $dps->user->username }}</a>
                                    </span>
                                </td>
                                <td data-label="@lang('Amount to Pay')">
                                    <span>{{ $general->cur_sym.showAmount($dps->per_installment) }}</span>
                                    <span class="text--info d-block">@lang('Per') {{ $dps->installment_interval }} @lang('days')</span>
                                </td>

                                <td data-label="@lang('Installment')">
                                    <span>@lang('Total') : {{ $dps->total_installment }}</span>
                                    <span class="text--info d-block">@lang('Given') : {{ $dps->given_installment }}</span>
                                </td>

                                <td data-label="@lang('Next Installment')">
                                    <span>{{ showDateTime($dps->next_installment_date, 'd M, Y') }}</span>
                                    <span class="text--info d-block">
                                        {{ diffForHumans($dps->next_installment_date) }}
                                    </span>
                                </td>

                                <td data-label="@lang('After Matured')">
                                    @php
                                        $totalDeposit   = $dps->per_installment * $dps->total_installment;
                                        $profit         = $totalDeposit * $dps->interest_rate / 100 ;
                                    @endphp
                                    <span>
                                        {{ $general->cur_sym.showAmount($totalDeposit + $profit) }}
                                    </span>
                                    <span class="text--info font-weight-bold d-block">
                                        {{ $general->cur_sym.showAmount($totalDeposit) }}
                                        + {{ getAmount($dps->interest_rate) }}%
                                    </span>
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
                                    <a href="{{ route('admin.dps.isntallments', $dps->id) }}" class="icon-btn" data-toggle="tooltip" data-title="Installment History">
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
            <input type="text" name="search" class="form-control" placeholder="@lang('DPS No.')" value="{{ request()->search ?? '' }}">
            <div class="input-group-append">
                <button class="btn btn--primary" type="submit"><i class="fa fa-search"></i></button>
            </div>
        </div>
    </form>
@endpush
