
@extends($activeTemplate.'layouts.master')
@section('content')
    <div class="container">
        <div class="custom--card">
            <div class="table-responsive--md mt-3">
                <table class="table custom--table">
                    <thead>
                        <tr>
                            <th>@lang('Acccount Name')</th>
                            <th>@lang('Short Name')</th>
                            <th>@lang('Account Number')</th>
                            <th>@lang('Bank')</th>
                            <th>@lang('Action')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($beneficiaries as $beneficiary)
                        <tr>
                            <td data-label="@lang('Acccount Name')">
                                {{ $beneficiary->short_name }}
                            </td>
                            <td data-label="@lang('Short Name')">
                                {{ $beneficiary->account_name }}
                            </td>
                            <td data-label="@lang('Account Number')">
                                {{ $beneficiary->account_number }}
                            </td>
                            <td data-label="@lang('Bank')">
                                {{ $beneficiary->bank->name }}
                            </td>
                            <td data-label="@lang('Action')">
                                <span data-bs-toggle="tooltip" data-bs-placement="top" title="@lang('Details')">
                                    <button class="btn btn-sm btn--base seeDetails"
                                        data-bank_name="{{ $beneficiary->bank->name }}"
                                        data-short_name="{{ $beneficiary->short_name }}"
                                        data-details='@json($beneficiary->details)'

                                    >
                                        <i class="la la-desktop"></i>
                                    </button>
                                </span>

                                <span data-bs-toggle="tooltip" data-bs-placement="top" title="@lang('Transfer Money')">
                                    <button type="button"
                                    data-name="{{ $beneficiary->short_name }}"
                                    data-bank_name="{{ $beneficiary->bank->name }}"
                                    daa
                                    data-id="{{ $beneficiary->id }}"

                                    data-minimum_amount="{{ $general->cur_sym.showAmount($beneficiary->bank->minimum_limit) }}"
                                    data-maximum_amount="{{ $general->cur_sym.showAmount($beneficiary->bank->maximum_limit) }}"
                                    data-daily_limit="{{ $general->cur_sym.showAmount($beneficiary->bank->daily_maximum_limit) }}"
                                    data-monthly_limit="{{ $general->cur_sym.showAmount($beneficiary->bank->monthly_maximum_limit) }}"
                                    data-daily_count="{{ $beneficiary->bank->daily_total_transaction }}"
                                    data-monthly_count="{{ $beneficiary->bank->monthly_total_transaction }}"
                                        class="btn btn-sm btn--base sendBtn"
                                    >
                                        <i class="las la-random"></i>
                                    </button>
                                </span>
                            </td>

                        </tr>
                        @empty
                            <tr><td colspan="100%" class="text-center">@lang($emptyMessage)</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{$beneficiaries->links()}}
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
            <li>
                <a class="active" href="{{ route('user.transfer.other') }}">@lang('Transfer To Other Bank')</a>
            </li>
        @endif

        <li>
            <a href="{{ route('user.transfer.history') }}">@lang('Transfer History')</a>
        </li>

    @endif
@endpush


@push('modal')
    <!-- Modal -->
    <div class="modal fade" id="detailsModal">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Benficiary Details')</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <ul class="list-group list-group-flush">
                    </ul>
                </div>

            </div>
        </div>
    </div>



    <div class="modal fade" id="sendModal">
        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Transfer Money')</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <form action="{{ route('user.action') }}" method="post">
                    @csrf
                    <div class="modal-body">

                        <div class="row">
                            <div class="col-xl-5 mb-3">
                                <div class="card shadow-none">
                                    <div class="card-header bg--dark">
                                        <h5 class="border-0 py-1 px-2 text-white">@lang('Transfer Limit')</h5>
                                    </div>
                                    <div class="card-body">
                                        <ul class="caption-list-two">
                                            <li>
                                                <span class="caption">@lang('Minimum Amount')</span>
                                                <span class="value minimum_amount"></span>
                                            </li>
                                            <li>
                                                <span class="caption">@lang('Maximum Amount')</span>
                                                <span class="value maximum_amount"></span>
                                            </li>

                                            <li>
                                                <span class="caption">@lang('Daily Available')</span>
                                                <span class="value daily_limit"></span>
                                            </li>

                                            <li>
                                                <span class="caption">@lang('Monthly Available')</span>
                                                <span class="value monthly_limit"></span>
                                            </li>

                                            <li>
                                                <span class="caption">@lang('Daily Available Count')</span>
                                                <span class="value daily_count"></span>
                                            </li>

                                            <li>
                                                <span class="caption"> @lang('Monthly Available Count')</span>
                                                <span class="value monthly_count"></span>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-7">

                                <input type="hidden" name="id">
                        <input type="hidden" name="type" value="other_transfer">

                        <div class="form-group">
                            <label>@lang('Bank')</label>
                            <input type="text" class="bank-name form--control" class="form-control" readonly>
                        </div>

                        <div class="form-group">
                            <label>@lang('Recipient')</label>
                            <input type="text" class="short-name form--control" class="form-control" readonly>
                        </div>

                        <div class="form-group">
                            <label>@lang('Amount')</label>
                            <div class="input-group">
                                <input type="text" name="amount" placeholder="@lang('Enter an Amount')" class="form--control numeric-validation" required >
                                <span class="input-group-text bg--base text-white border--base">@lang($general->cur_text)</span>
                            </div>
                        </div>

                        @if(checkIsOtpEnable($general))
                            @include($activeTemplate.'partials.otp_field')
                        @endif
                            </div>
                        </div>


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
                console.log($(this).data('minimum_amount'));
                modal.find('.minimum_amount').text($(this).data('minimum_amount'));
                modal.find('.maximum_amount').text($(this).data('maximum_amount'));
                modal.find('.daily_limit').text($(this).data('daily_limit'));
                modal.find('.monthly_limit').text($(this).data('monthly_limit'));
                modal.find('.daily_count').text($(this).data('daily_count'));
                modal.find('.monthly_count').text($(this).data('monthly_count'));

                modal.find('.bank-name').val($(this).data('bank_name'));
                modal.find('.short-name').val($(this).data('name'));
                modal.find('input[name=id]').val($(this).data('id'));
                modal.modal('show');
            });

            $('.seeDetails').on('click', function(){
                let modal       = $('#detailsModal');
                let details     = $(this).data('details');
                let imagePath   = "{{asset(imagePath()['transfer']['beneficiary_data']['path'])}}/";
                let html    = `
                    <li class="list-group-item d-flex flex-wrap justify-content-between">
                        <span>@lang('Bank Name')</span>
                        ${$(this).data('bank_name')}
                    </li>
                    <li class="list-group-item d-flex flex-wrap justify-content-between">
                        <span>@lang('Short Name')</span>
                        ${$(this).data('short_name')}
                    </li>
                `;


                $.each(details, function (i, value) {
                    if(value.type == 'file'){
                        html +=
                        `
                            <li class="list-group-item d-flex flex-wrap justify-content-between">
                                <span>@lang('${titleCase(i)}')</span>
                                <img class="w-75" src="${imagePath}${value.value}">
                            </li>
                        `;
                    }else {
                        html +=
                        `
                            <li class="list-group-item d-flex flex-wrap justify-content-between">
                                <span>@lang('${titleCase(i)}')</span>
                                ${value.value}
                            </li>
                        `
                    }
                });
                modal.find('.modal-body .list-group').html(html);
                modal.modal('show');
            });
        })(jQuery)

    </script>
@endpush
