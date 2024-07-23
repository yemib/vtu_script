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
                                    <th>@lang('TRX | Bank')</th>
                                    <th>@lang('Send By')</th>
                                    <th>@lang('Received By')</th>
                                    <th>@lang('Amount')</th>
                                    <th>@lang('Final Amount')</th>
                                    <th>@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody>

                                @forelse ($transfers as $transfer)
                                    <tr>
                                        <td data-label="@lang('TRX | Bank')">
                                            <span>
                                                {{ $transfer->trx }}
                                            </span>

                                            <span class="d-block text--primary small">
                                                @if($transfer->bank_id==0)
                                                    {{$general->sitename}}
                                                @else
                                                    <a href="{{ route('admin.bank.edit', $transfer->bank->id) }}">@lang($transfer->bank->name)</a>
                                                @endif
                                            </span>

                                        </td>

                                        <td data-label="@lang('Send By')">

                                            <span class="d-block">
                                                {{ $transfer->user->account_number }}
                                            </span>

                                            <a href="{{ route('admin.users.detail', $transfer->user_id) }}">
                                                <span>@</span>{{ $transfer->user->username }}
                                            </a>
                                        </td>

                                        <td data-label="@lang('Received By')">

                                            <span class="d-block">
                                                {{ $transfer->beneficiary->account_number }}
                                            </span>

                                            <a href="{{ route('admin.users.detail', $transfer->user_id) }}">
                                                <span>@</span>{{ $transfer->beneficiary->account_number }}
                                            </a>
                                        </td>



                                        <td data-label="@lang('Amount')">
                                            <span class="font-weight-bold">
                                                {{ $general->cur_sym.showAmount($transfer->amount) }}
                                            </span>

                                            <span class="small d-block">
                                                @lang('Charge')
                                                <span class="text--info font-weight-bold">
                                                    {{ $general->cur_sym.showAmount($transfer->charge) }}
                                                </span>
                                            </span>
                                        </td>

                                        <td  data-label="@lang('Final Amount')">
                                            <span class="font-weight-bold">
                                                {{ $general->cur_sym.showAmount($transfer->final_amount) }}
                                            </span>
                                            <span class="text--info d-block">
                                                @lang('With Charge')
                                            </span>
                                        </td>

                                        <td data-label="@lang('Action')">
                                            <a class="icon-btn" href="{{ route('admin.transfers.details', $transfer->id) }}">
                                                <i class="la la-desktop"></i>
                                            </a>
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="100%" class="text-center">@lang($emptyMessage)</td></tr>
                                @endforelse
                            </tbody>
                        </table><!-- table end -->
                    </div>
                </div>
                @if($transfers->hasPages())
                    <div class="card-footer py-4">
                    {{ paginateLinks($transfers) }}
                    </div>
                @endif
            </div><!-- card end -->
        </div>
    </div>
@endsection


@push('breadcrumb-plugins')
    <form action="" method="GET" class="form-inline float-sm-right bg--white">
        <div class="input-group has_append">
            <input type="text" name="search" class="form-control" placeholder="@lang('TRX No.')" value="{{ request()->search ?? '' }}">
            <div class="input-group-append">
                <button class="btn btn--primary" type="submit"><i class="fa fa-search"></i></button>
            </div>
        </div>
    </form>
@endpush
