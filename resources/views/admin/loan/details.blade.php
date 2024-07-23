@extends('admin.layouts.app')

@section('panel')
    <div class="row mb-none-30">
        <div class="col-xl-4 mb-30">
            <div class="card b-radius--10 overflow-hidden box--shadow1">
                <div class="card-body">
                    <h5 class="mb-20 text-muted">@lang('Plan Name') :  {{__(@$loan->plan->name)}}</h5>

                    <ul class="list-group">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('Date')
                            <span class="font-weight-bold">{{ showDateTime($loan->created_at, 'd M, Y, h:i A') }}</span>
                        </li>

                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('Trx Number')
                            <span class="font-weight-bold">{{ $loan->trx }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('Username')
                            <span class="font-weight-bold">
                                <a href="{{ route('admin.users.detail', $loan->user_id) }}">{{ @$loan->user->username }}</a>
                            </span>
                        </li>

                      
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('Amount')
                            <span class="font-weight-bold">{{ showAmount($loan->amount ) }} {{ __($general->cur_text) }}</span>
                        </li>

                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('Total Payable')
                            <span class="font-weight-bold">{{ showAmount($loan->final_amount) }} {{__($general->cur_text)}}</span>
                        </li>



                        @php
                            $profit = $loan->final_amount - $loan->amount;
                        @endphp

                        @if($profit < 0)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                @lang('Loss')
                                <span class="font-weight-bold text--danger">{{ showAmount($profit) }} {{__($general->cur_text)}}</span>
                            </li>
                        @elseif($profit > 0)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                @lang('Profit')
                                <span class="font-weight-bold text--success">{{ showAmount($profit) }} {{__($general->cur_text)}}</span>
                            </li>
                        @else
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                @lang('Profit')
                                <span class="font-weight-bold text--warning">{{ showAmount($profit) }} {{__($general->cur_text)}}</span>
                            </li>
                        @endif

                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('Per Installment')
                            <span class="font-weight-bold">{{ showAmount($loan->per_installment) }} {{__($general->cur_text)}}</span>
                        </li>

                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('Total Installment')
                            <span class="font-weight-bold">{{$loan->total_installment }}</span>
                        </li>

                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('Given Installment')
                            <span class="font-weight-bold">{{$loan->given_installment }}</span>
                        </li>

                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('Status')
                            @if($loan->status == 0)
                                <span class="badge badge--dark">@lang('Pending')</span>
                            @elseif($loan->status == 1)
                                <span class="badge badge--warning">@lang('Running')</span>
                            @elseif($loan->status == 2)
                                <span class="badge badge--success">@lang('Paid')</span>
                            @elseif($loan->status == 3)
                                <span class="badge badge--danger">@lang('Rejected')</span>
                            @endif
                        </li>
                    </ul>
                </div>
            </div>

            @if($installments->count())
                <div class="card mt-3 b-radius--10 overflow-hidden box--shadow1">
                    <div class="card-body">
                        <h5 class="mb-20 text-muted">@lang('Installment Logs')</h5>
                        <ul class="list-group">
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span class="font-weight-bold">@lang('Date')</span>
                                <span class="font-weight-bold">@lang('Amount')</span>
                            </li>
                            @foreach($installments as $installment)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <span>{{ showDateTime($installment->created_at, 'd M, Y, h:i A') }}</span>
                                    <span>{{ showAmount($installment->amount) }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif
        </div>
        <div class="col-xl-8 mb-30">

            <div class="card b-radius--10 overflow-hidden box--shadow1">
                <div class="card-body">
                    <h5 class="card-title border-bottom pb-2">@lang('User Information')</h5>

                    @if($loan->user_details)
                        @foreach(json_decode($loan->user_details) as $key => $item)

                            @if($item->type == 'file')
                                <div class="row mt-4">
                                    <div class="col-md-8">
                                        <h6>{{__(inputTitle($key))}}</h6>
                                        <img src="{{getImage('assets/images/verify/loan/'.$item->value)}}" alt="@lang('Image')">
                                    </div>
                                </div>
                            @else
                                <div class="row mt-4">
                                    <div class="col-md-12">
                                        <h6>{{__(inputTitle($key))}}</h6>
                                        <p>{{$item->value}}</p>
                                    </div>
                                </div>

                            @endif
                        @endforeach
                    @endif


                    @if($loan->status == 0)
                        <div class="row mt-4">
                            <div class="col-md-12">
                                <button class="btn btn--success ml-1 approveBtn" data-toggle="tooltip" data-original-title="@lang('Approve')" data-id="{{ $loan->id }}">
                                    <i class="fas la-check"></i> @lang('Approve')
                                </button>

                                <button class="btn btn--danger ml-1 rejectBtn" data-toggle="tooltip" data-original-title="@lang('Reject')" data-id="{{ $loan->id }}">
                                    <i class="fas fa-ban"></i> @lang('Reject')
                                </button>
                            </div>
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>



    {{-- APPROVE MODAL --}}
    <div id="approveModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Confirmation Alert')</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('admin.loan.approve') }}" method="POST">
                    @csrf
                    <input type="hidden" name="id">
                    <div class="modal-body">
                        <p>@lang('Are you sure to approve this loan?')</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn--dark" data-dismiss="modal">@lang('Close')</button>
                        <button type="submit" class="btn btn--success">@lang('Approve')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- REJECT MODAL --}}
    <div id="rejectModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Confirmation Alert')</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{route('admin.loan.reject')}}" method="POST">
                    @csrf
                    <input type="hidden" name="id">

                    <div class="modal-body">
                        <strong>@lang('Reason of Rejection')</strong>
                        <textarea name="details" class="form-control pt-3" rows="3" placeholder="@lang('Provide the Details')" required=""></textarea>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn--dark" data-dismiss="modal">@lang('Close')</button>
                        <button type="submit" class="btn btn--danger">@lang('Reject')</button>
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
            $('.approveBtn').on('click', function() {
                var modal = $('#approveModal');
                modal.find('input[name=id]').val($(this).data('id'));
                modal.modal('show');
            });

            $('.rejectBtn').on('click', function() {
                var modal = $('#rejectModal');
                modal.find('input[name=id]').val($(this).data('id'));
                modal.modal('show');
            });
        })(jQuery);

    </script>
@endpush
