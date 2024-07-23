@extends('admin.layouts.app')

@section('panel')
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-body p-0">
                    <ul class="list-group">
                        <li class="list-group-item bg--primary font-weight-bold">
                            @lang('Sender\'s Information')
                        </li>
                        <li class="list-group-item d-flex flex-wrap justify-content-between">
                            <span>@lang('Account Name')</span>
                            <span class="text--primary font-weight-bold">
                                {{ $transfer->user->username }}
                            </span>
                        </li>
                        <li class="list-group-item d-flex flex-wrap justify-content-between">
                            <span>
                                @lang('Account Number')
                            </span>
                            <span class="text--cyan font-weight-bold">
                                {{ $transfer->user->account_number }}
                            </span>
                        </li>
                        <li class="list-group-item d-flex flex-wrap justify-content-between">
                            <span>@lang('Send at')</span>
                            <span class="">
                                {{ showDateTime($transfer->user->created_at, 'd M, Y h:i A') }}
                            </span>
                        </li>
                        <li class="list-group-item d-flex flex-wrap justify-content-between">
                            <span>@lang('TRX')</span>
                            <span class="text--info font-weight-bold">
                                {{ $transfer->trx }}
                            </span>
                        </li>

                        <li class="list-group-item d-flex flex-wrap justify-content-between">
                            <span>
                                @lang('Amount')
                            </span>
                            <span class="text--success font-weight-bold">
                                {{ $general->cur_sym.showAmount($transfer->amount) }}
                            </span>
                        </li>

                        <li class="list-group-item d-flex flex-wrap justify-content-between">
                            <span>
                                @lang('Charge')
                            </span>
                            <span class="text--red font-weight-bold">
                                {{ $general->cur_sym.showAmount($transfer->charge) }}
                            </span>
                        </li>

                        <li class="list-group-item d-flex flex-wrap justify-content-between">
                            <span>
                                @lang('Including Charge')
                            </span>

                            <span class="text--deep-purple font-weight-bold">
                                {{ $general->cur_sym.showAmount($transfer->final_amount) }}
                            </span>
                        </li>

                        <li class="list-group-item d-flex flex-wrap justify-content-between">
                            <span>
                                @lang('Status')
                            </span>

                            <span class="text--info font-weight-bold">
                                @if($transfer->status == 0)
                                    <span class="badge badge--warning">@lang('Pending')</span>
                                @elseif($transfer->status == 1)
                                    <span class="badge badge--success">@lang('Completed')</span>
                                @elseif($transfer->status == 2)
                                    <span class="badge badge--warning">@lang('Rejected')</span>
                                @endif
                            </span>
                        </li>

                    </ul>
                </div>
            </div>
        </div>

        <div class="col-lg-6 mt-3 mt-lg-0">
            <div class="card">
                <div class="card-body p-0">
                    <ul class="list-group">
                        <li class="list-group-item bg--primary font-weight-bold">
                            @lang('Receiver\'s Information')
                        </li>
                        <li class="list-group-item d-flex flex-wrap justify-content-between">
                            <span>
                                @lang('Bank Name')
                            </span>
                            <span class="text--danger font-weight-bold">
                                {{ $transfer->bank->name??$general->sitename }}
                            </span>
                        </li>
                        <li class="list-group-item d-flex flex-wrap justify-content-between">
                            <span>@lang('Account Name')</span>
                            <span class="text--blue font-weight-bold">
                                {{ $transfer->beneficiary->account_name }}
                            </span>

                            <li class="list-group-item d-flex flex-wrap justify-content-between">
                                <span>
                                    @lang('Account Number')
                                </span>
                                <span class="text--deep-purple font-weight-bold">
                                    {{ $transfer->beneficiary->account_number }}
                                </span>
                            </li>
                            @if($transfer->beneficiary->details)
                                @foreach ($transfer->beneficiary->details as $key => $item)
                                    <li class="list-group-item d-flex flex-wrap justify-content-between">
                                        <span>
                                            @lang(ucFirst($key))
                                        </span>
                                        <span class="text--info font-weight-bold">
                                            {{ $transfer->beneficiary->account_number }}
                                        </span>
                                    </li>
                                @endforeach
                            @endif

                            <li class="list-group-item d-flex flex-wrap justify-content-between">
                                <span>
                                    @lang('Recivable Amount')
                                </span>
                                <span class="text--success font-weight-bold">
                                    {{ $general->cur_sym.showAmount($transfer->amount) }}
                                </span>
                            </li>
                        </li>
                        @if($transfer->status == 0)
                        <li class="list-group-item d-flex flex-wrap justify-content-between">
                            <button class="btn btn--danger cancelBtn">@lang('Reject Transfer')</button>
                            <button class="btn btn--primary confirmBtn">@lang('Complete Transfer')</button>
                        </li>
                        @endif
                    </ul>

                </div>
            </div>


        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="cancelModal">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Confirmation Alert')</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                </div>
                <div class="modal-body">
                    @lang('Are you sure to reject this transfer?')
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn--dark" data-dismiss="modal">@lang('No')</button>
                    <form action="{{ route('admin.transfers.reject', $transfer->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn--primary">@lang('Yes')</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal -->

    <div class="modal fade" id="confirmModal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Confirmation Alert')</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                </div>
                <div class="modal-body">
                    @lang('Are you sure that this transfer has been completed?')
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn--dark" data-dismiss="modal">@lang('No')</button>
                    <form action="{{ route('admin.transfers.accept', $transfer->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn--primary">@lang('Yes')</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection


@push('script')
    <script>
        'use strict';
        (function($){
            $('.cancelBtn').on('click', function(){
                let modal = $('#cancelModal');
                modal.modal('show');
            })
            $('.confirmBtn').on('click', function(){
                let modal = $('#confirmModal');
                modal.modal('show');
            })
        })(jQuery)
    </script>
@endpush
