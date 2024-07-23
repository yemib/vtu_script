@extends($activeTemplate.'layouts.master')
@section('content')


<!-- dashboard section start -->
    <div class="container">

        <div class="row align-items-center mb-3">
            <div class="col-6">
                <h6>@lang('My Withdrawal History')</h6>
            </div>
            <div class="col-6 text-end">
                <button type="button" data-bs-toggle="modal" data-bs-target="#withdrawModal" class="btn btn-sm btn--base"><i class="las la-minus-circle"></i> @lang('Withdraw Now')</button>
            </div>
        </div>

      <div class="row justify-content-center mb-none-30">
        <div class="col-lg-12">
          <div class="custom--card">
              <div class="card-body p-0">
                <div class="table-responsive--md">
                    <table class="table custom--table">
                    <thead>
                        <tr>
                            <th scope="col">@lang('Date')</th>
                            <th scope="col">@lang('Method | TRX')</th>
                            <th scope="col">@lang('Amount | Charge')</th>
                            <th scope="col">@lang('Rate')</th>
                            <th scope="col">@lang('Final Amount')</th>
                            <th scope="col">@lang('Status')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($withdraws as $data)
                            <tr>
                                <td data-label="@lang('Date')">
                                    {{showDateTime($data->created_at, 'd M, Y h:i A')}}
                                    <span class="d-block">{{ diffForHumans($data->created_at) }}</span>
                                </td>
                                <td data-label="@lang('Method | TRX')">
                                    <span class="d-block text--base">{{ __($data->method->name) }}</span>
                                    <small class="d-block">
                                        {{$data->trx}}
                                    </small>
                                </td>

                                <td data-label="@lang('Amount | Charge')">
                                    <span class="d-block text--base">{{showAmount($data->amount)}} {{__($general->cur_text)}}</span>
                                    <span class="d-block text--danger">{{showAmount($data->charge)}} {{__($general->cur_text)}}</span>
                                </td>

                                <td data-label="@lang('Rate')">
                                    @lang('Per') @lang($general->cur_text)
                                    <span class="d-block text--base">
                                        {{showAmount($data->rate)}} {{__($data->currency)}}
                                    </span>
                                </td>

                                <td data-label="@lang('Final Amount')">
                                    <span class="d-block">{{showAmount($data->after_charge)}} {{__($general->cur_text)}}</span>

                                    <span class="d-block text--base">{{showAmount($data->final_amount)}} {{__($data->currency)}}</span>
                                </td>
                                <td data-label="@lang('Status')">
                                    @if($data->status == 2)
                                        <span class="badge badge--warning">@lang('Pending')</span>
                                    @elseif($data->status == 1)
                                        <span class="badge badge--success">@lang('Completed')
                                            <button class="bg-transparent text-info approveBtn" data-admin_feedback="{{$data->admin_feedback}}"><i class="fa fa-info-circle"></i></button>
                                        </span>
                                    @elseif($data->status == 3)
                                        <span class="badge badge--danger">@lang('Rejected')
                                            <button class="bg-transparent text-info approveBtn" data-admin_feedback="{{$data->admin_feedback}}"><i class="fa fa-info-circle"></i></button>
                                        </span>
                                    @endif
                                </td>

                            </tr>
                        @empty
                            <tr>
                                <td class="text-center" colspan="100%">{{ __($emptyMessage) }}</td>
                            </tr>
                        @endforelse
                        </tbody>

                    </table>
                    {{ $withdraws->links() }}
                </div>
              </div>
          </div>
        </div>
    </div>
  <!-- dashboard section end -->


@endsection


@push('modal')
{{-- Detail MODAL --}}
<div id="detailModal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">@lang('Details')</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="withdraw-detail"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-md btn-danger" data-bs-dismiss="modal">@lang('Close')</button>
            </div>
        </div>
    </div>
</div>

<div id="withdrawModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Withdraw Money')</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{route('user.action')}}" method="post">
                    @csrf
                    <input type="hidden" name="type" value="withdraw">

                    <div class="modal-body">
                        <div class="form-group">
                            <label for="paymentGateway" class="fw-bold">@lang('Withdraw Method')</label>
                            <select class="form--control" name="id" id="paymentGateway" required>
                                <option disabled selected value="">@lang('Select One')</option>
                                @foreach($withdrawMethod as $gateway)
                                    <option data-currency="{{ $gateway->currency }}"
                                        data-min_amount="{{getAmount($gateway->min_limit)}}"
                                        data-max_amount="{{getAmount($gateway->max_limit)}}"
                                        data-fix_charge="{{getAmount($gateway->fixed_charge)}}"
                                        data-percent_charge="{{getAmount($gateway->percent_charge)}}"
                                        value="{{ $gateway->id }}">
                                        @lang($gateway->name)
                                    </option>
                                @endforeach
                            </select>

                            <div  class="mt-3">
                                <p><small class="text--danger limit-info"></small></p>
                                <p><small class="text-danger charge-info"></small></p>
                            </div>

                        </div>
                        <div class="form-group">
                            <label class="fw-bold" for="amount">@lang('Amount')</label>
                            <div class="input-group">
                                <input id="amount" type="text" class="form--control numeric-validation" name="amount" placeholder="0.00" value="{{old('amount')}}" required>
                                <span class="input-group-text bg--base text-white border--base">{{__($general->cur_text)}}</span>
                            </div>
                        </div>

                        @if(checkIsOtpEnable($general))
                            @include($activeTemplate.'partials.otp_field')
                        @endif

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger btn-md" data-bs-dismiss="modal">@lang('Close')</button>
                        <button type="submit" class="btn btn--base btn-md">@lang('Confirm')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endpush


@push('script')
    <script>
        (function($){
            "use strict";
            $('.approveBtn').on('click', function() {
                var modal = $('#detailModal');
                var feedback = $(this).data('admin_feedback');

                modal.find('.withdraw-detail').html(`<p> ${feedback} </p>`);
                modal.modal('show');
            });

            $('#paymentGateway').on('change', function(){
                let option = $(this).find('option:selected');
                var minAmount       = option.data('min_amount');
                var maxAmount       = option.data('max_amount');
                var baseSymbol      = "{{$general->cur_text}}";
                var fixCharge       = option.data('fix_charge');
                var percentCharge   = option.data('percent_charge');
                var limitInfo       = `@lang('Deposit Limit'): ${minAmount} - ${maxAmount}  ${baseSymbol}`;
                $('.limit-info').text(limitInfo);
                var chargeInfo = `@lang('Charge'): ${fixCharge} ${baseSymbol}  ${(0 < percentCharge) ? ' + ' +percentCharge + ' % ' : ''}`;


                $('.charge-info').text(chargeInfo);
            });

        })(jQuery);

    </script>
@endpush
