@extends($activeTemplate.'layouts.master')
@section('content')
    <div class="container">
        <div class="custom--card">
            <div class="table-responsive--md mt-3">
                <table class="table custom--table">
                    <thead>
                        <tr>
                            <th>@lang('TRX')</th>
                            <th>@lang('Account Name')</th>
                            <th>@lang('Account Number')</th>
                            <th>@lang('Bank')</th>
                            <th>@lang('Amount')</th>
                            <th>@lang('Status')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($transfers as $transfer)
                        <tr>
                            <td data-label="@lang('TRX')">
                                {{ $transfer->trx }}
                            </td>

                            <td data-label="@lang('Account Name')">
                                {{ $transfer->beneficiary->account_name }}
                            </td>

                            <td data-label="@lang('Account Number')">
                                {{ $transfer->beneficiary->account_number }}
                            </td>

                            <td data-label="@lang('Bank')">
                                {{ $transfer->bank->name??$general->sitename }}
                            </td>


                            <td data-label="@lang('Amount')">
                                {{ $general->cur_sym.showAmount($transfer->amount) }}
                            </td>

                            <td data-label="@lang('Status')">
                                @if($transfer->status == 1)
                                <span class="badge badge--success">
                                    @lang('Completed')
                                </span>
                                @elseif($transfer->status == 0)
                                <span class="badge badge--warning">
                                    @lang('Pending')
                                </span>
                                @elseif($transfer->status == 2)
                                    <span class="badge badge--danger">
                                        @lang('Rejected')
                                    </span>
                                @endif
                            </td>

                        </tr>
                        @empty
                            <tr>
                                <td colspan="100%" class="text-center">@lang($emptyMessage)</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{ $transfers->links() }}
    </div>
@endsection

@push('bottom-menu')
    @if($general->modules->own_bank || $general->modules->other_bank)
        <li>
            <a href="{{ route('user.transfer.beneficiary.manage') }}">@lang('Beneficiary Management')</a>
        </li>
        @if($general->modules->own_bank)
            <li>
                <a href="{{ route('user.transfer.own') }}">@lang('Transfer Within') @lang($general->sitename)</a>
            </li>
        @endif
        @if($general->modules->other_bank)
            <li><a href="{{ route('user.transfer.other') }}">@lang('Transfer To Other Bank')</a></li>
        @endif

        <li>
            <a class="active" href="{{ route('user.transfer.history') }}">@lang('Transfer History')</a>
        </li>
    @endif
@endpush
