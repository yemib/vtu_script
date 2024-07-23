@extends($activeTemplate.'layouts.master')
@section('content')
    <div class="container">

        <div class="row">
            <div class="col-xl-4 mb-3">
                <div class="card rounded-3">
                    <div class="card-header bg--dark">
                        <h6 class="text-white border-0 py-1 px-2">@lang('Transfer Limit')</h6>
                    </div>
                    <div class="card-body p-2">
                        <ul class="caption-list-two">
                            <li>
                                <span class="caption">@lang('Minimum Amount')</span>
                                <span class="value">{{ $general->cur_sym.showAmount($general->minimum_transfer_limit) }}</span>
                            </li>

                            <li>
                                <span class="caption">@lang('Daily Available')</span>
                                <span class="value">{{ $general->cur_sym.showAmount($general->daily_transfer_limit) }}</span>
                            </li>

                            <li>
                                <span class="caption">@lang('Monthly Available')</span>
                                <span class="value">{{ $general->cur_sym.showAmount($general->monthly_transfer_limit) }}</span>
                            </li>

                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-xl-8">
                <div class="custom--card">
                    <div class="table-responsive--md">
                        <table class="table custom--table">
                            <thead>
                                <tr>
                                    <tr>
                                        <th>@lang('Bank')</th>
                                        <th>@lang('Account No.')</th>
                                        <th>@lang('Account Name')</th>
                                        <th>@lang('Short Name')</th>
                                        <th>@lang('Details')</th>
                                    </tr>
                                </tr>
                            </thead>

                            <tbody>
                                @forelse ($ownBeneficiaries as $beneficiary)
                                    <tr>
                                        <td data-label="@lang('Bank')">
                                            {{ __($general->sitename) }}
                                        </td>

                                        <td data-label="@lang('Account No.')">
                                            {{ $beneficiary->account_number }}
                                        </td>
                                        <td data-label="@lang('Account Name.')">
                                            {{ $beneficiary->account_name }}
                                        </td>

                                        <td data-label="@lang('Short Name')">
                                            {{ $beneficiary->short_name }}
                                        </td>
                                        <td data-label="@lang('Details')">
                                            <button class="btn btn-sm btn--base sendBtn"
                                                data-id="{{ $beneficiary->id }}"
                                            >
                                                <i class="la la-random"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="100%" class="text-center">@lang($emptyMessage)</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                {{$ownBeneficiaries->links()}}
            </div>
        </div>

    </div>
@endsection
@push('modal')
    <!-- Modal -->
    <div class="modal fade" id="sendModal">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Transfer Money')</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <form action="{{ route('user.action') }}" method="post">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" name="id">
                        <input type="hidden" name="type" value="own_transfer">

                        <div class="form-group">
                            <label>@lang('Amount')</label>
                            <div class="input-group">
                                <input type="text" name="amount" placeholder="@lang('Enter an Amount')" class="form--control numeric-validation" required>
                                <span class="input-group-text text-white bg--base border--base">@lang($general->cur_text)</span>
                            </div>

                        </div>

                        @if(checkIsOtpEnable($general))
                            @include($activeTemplate.'partials.otp_field')
                        @endif
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-sm btn--danger" data-bs-dismiss="modal">@lang('Cancel')</button>
                        <button type="submit" class="btn btn-sm btn--base">@lang('Send')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endpush


@push('script')
    <script>
        'use strict';
        (function($){
            $('.sendBtn').on('click', function(){
                let modal = $('#sendModal');
                modal.find('input[name=id]').val($(this).data('id'))
                modal.modal('show');
            });
        })(jQuery)
    </script>
@endpush

@push('bottom-menu')
    @if($general->modules->own_bank || $general->modules->other_bank)
        <li>
            <a href="{{ route('user.transfer.beneficiary.manage') }}">@lang('Beneficiary Management')</a>
        </li>
        @if($general->modules->own_bank)
            <li>
                <a class="active" href="{{ route('user.transfer.own') }}">@lang('Transfer Within') @lang($general->sitename)</a>
            </li>
        @endif
        @if($general->modules->other_bank)
            <li><a href="{{ route('user.transfer.other') }}">@lang('Transfer To Other Bank')</a></li>
        @endif

        <li>
            <a href="{{ route('user.transfer.history') }}">@lang('Transfer History')</a>
        </li>
    @endif
@endpush

