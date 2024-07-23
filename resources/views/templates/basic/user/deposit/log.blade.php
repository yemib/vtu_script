@extends($activeTemplate.'layouts.master')
@section('content')

<!-- dashboard section start -->
    <div class="container">
       
       <?php
		$bank  =  App\Models\bank_account::where('user_id'   , auth()->user()->id)->first()  ;  
		
		
		
		?>
       
       @if(isset($bank->id))
       
       <h7 class="alert alert-info">Easily Make  Transfer To  The Bank  Details Below.  And Refresh the Page To Update  Your Wallet.   </h7>
       <p>
       	<strong>Bank  Name  :</strong> {{ $bank->bankName }} 
       	<br/>
      	       	
      	        	<strong>Account  Name :</strong>   {{
       	      	$bank->accountName }}   	
       	   	
       	
       	<br/>
       	       	<strong>Account  Number  :  </strong>  {{$bank->accountNumber }}
 
       	  
       </p>
       
       @endif
       
        <div class="row align-items-center mb-3">
        
		</div>
       
       
        <div class="row align-items-center mb-3">
            <div class="col-lg-6">
                <h6>@lang('My Deposit History')</h6>
            </div>
            <div class="col-lg-6 text-lg-end">
                <button type="button" data-bs-toggle="modal" data-bs-target="#depositModal" class="btn btn-sm btn--base"><i class="las la-plus-circle"></i> @lang('Deposit Now')</button>
            </div>
        </div>
        <div class="custom--card">
            <div class="card-body p-0">
            <div class="table-responsive--md">
                <table class="table custom--table">
                    <thead>
                        <tr>
                            <th scope="col">@lang('Date')</th>
                            <th scope="col">@lang('TRX | Method')</th>
                            <th scope="col">@lang('Amount | Charge')</th>
                            <th scope="col">@lang('Rate')</th>
                            <th scope="col">@lang('Payable Amount')</th>
                            <th scope="col">@lang('Status')</th>
                            <th scope="col">@lang('Details')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(count($logs) > 0)
                            @foreach($logs as $data)
                                <tr>
                                    <td data-label="@lang('Date')">
                                        {{showDateTime($data->created_at, 'd M, Y h:i A')}}
                                        <small class="d-block text--base">
                                            {{ diffForHumans($data->created_at) }}
                                        </small>
                                    </td>

                                    <td data-label="@lang('TRX | Method')">
                                        <span class="d-block">
                                            {{$data->trx}}
                                        </span>
                                        <small class="d-block text--base">
                                            {{ __(@$data->gateway->name) }}
                                        </small>
                                    </td>

                                    <td data-label="@lang('Amount | Charge')">
                                        <strong>{{showAmount($data->amount)}} {{__($general->cur_text)}}</strong>
                                        <small class="d-block text--danger">{{showAmount($data->charge)}} {{__($general->cur_text)}}</small>
                                    </td>

                                    <td data-label="@lang('Rate')">
                                        <span>{{showAmount($data->rate)}} {{__($data->method_currency)}}</span>
                                        <small class="d-block text--base">
                                            @lang('Per') @lang($general->cur_text)
                                        </small>
                                    </td>

                                    <td data-label="@lang('Payable Amount')">
                                        <strong>
                                            {{ showAmount($data->final_amo) }} {{__($data->method_currency)}}
                                        </strong>

                                        <small class="d-block text--base">
                                            {{ showAmount($data->amount + $data->charge)}} {{ __($general->cur_text) }}
                                        </small>
                                    </td>


                                    <td data-label="@lang('Status')">
                                        @if($data->status == 1)
                                            <span class="badge badge--success">@lang('Complete')</span>
                                        @elseif($data->status == 2)
                                            <span class="badge badge--warning">@lang('Pending')</span>
                                        @elseif($data->status == 3)
                                            <span class="badge badge--danger">@lang('Cancel')</span>
                                        @endif

                                        @if($data->admin_feedback != null)
                                            <button class="btn-info btn-rounded badge detailBtn" data-admin_feedback="{{$data->admin_feedback}}"><i class="fa fa-info"></i></button>
                                        @endif
                                    </td>

                                    @php
                                        $details = ($data->detail != null) ? json_encode($data->detail) : null;
                                    @endphp

                                    <td data-label="@lang('Details')">
                                        <a href="javascript:void(0)" class="btn btn-primary btn-sm detailModal approveBtn"
                                        data-info="{{$details}}"
                                        data-id="{{ $data->id }}"
                                        data-amount="{{ showAmount($data->amount)}} {{ __($general->cur_text) }}"
                                        data-charge="{{ showAmount($data->charge)}} {{ __($general->cur_text) }}"
                                        data-after_charge="{{ showAmount($data->amount + $data->charge)}} {{ __($general->cur_text) }}"
                                        data-rate="{{ showAmount($data->rate)}} {{ __($data->method_currency) }}"
                                        data-payable="{{ showAmount($data->final_amo)}} {{ __($data->method_currency) }}">
                                            <i class="fa fa-desktop"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="100%"> @lang('No deposit yet')!</td>
                            </tr>
                        @endif
                    </tbody>
                </table>

                {{ $logs->links() }}
            </div>
            </div>
        </div>
    </div>

  <!-- dashboard section end -->

@endsection


@push('modal')
    {{-- APPROVE MODAL --}}
    <div id="approveModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Details')</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <ul class="list-group list-group-flush border">
                        <li class="border-bottom p-2 d-flex flex-wrap justify-content-between">@lang('Amount') : <span class="withdraw-amount value"></span></li>
                        <li class="border-bottom p-2 d-flex flex-wrap justify-content-between">@lang('Charge') : <span class="withdraw-charge "></span></li>
                        <li class="border-bottom p-2 d-flex flex-wrap justify-content-between">@lang('After Charge') : <span class="withdraw-after_charge"></span></li>
                        <li class="border-bottom p-2 d-flex flex-wrap justify-content-between">@lang('Conversion Rate') : <span class="withdraw-rate"></span></li>
                        <li class="d-flex p-2 flex-wrap justify-content-between">@lang('Payable Amount') : <span class="withdraw-payable"></span></li>
                    </ul>
                    <ul class="list-group withdraw-detail mt-1">
                    </ul>
                </div>

            </div>
        </div>
    </div>

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
                    <button type="button" class="btn btn-danger btn-md" data-bs-dismiss="modal">@lang('Close')</button>
                </div>
            </div>
        </div>
    </div>


<!-- Deposit Modal -->
    <div id="depositModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Deposit Money')</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{route('user.deposit.insert')}}" method="post">
                <div class="modal-body">
                        @csrf
                        <input type="hidden" name="currency">
                        <div class="form-group">
                            <label for="paymentGateway" class="fw-bold">@lang('Payment Method')</label>
                            <select class="form--control" name="method_code" id="paymentGateway">
                                <option disabled selected value="">@lang('Select One')</option>
                                @foreach($gatewayCurrency as $gateway)
                                    <option
                                        data-currency="{{ $gateway->currency }}"
                                        data-min_amount="{{getAmount($gateway->min_amount)}}"
                                        data-max_amount="{{getAmount($gateway->max_amount)}}"
                                        data-fix_charge="{{getAmount($gateway->fixed_charge)}}"
                                        data-percent_charge="{{getAmount($gateway->percent_charge)}}"
                                        value="{{ $gateway->method_code }}">
                                        @lang($gateway->name)
                                    </option>
                                @endforeach
                            </select>

                            <div class="mt-3">
                                <p><small class="text--danger depositLimit mt-2"></small></p>
                                <p><small class="text-danger depositCharge"></small></p>
                            </div>

                        </div>
                        <div class="form-group">
                            <label class="fw-bold" for="amount">@lang('Amount')</label>
                            <div class="input-group">
                                <input id="amount" type="text" class="form--control" name="amount" placeholder="0.00" value="{{old('amount')}}" required>
                                <span class="input-group-text bg--base text-white border--base">{{__($general->cur_text)}}</span>
                            </div>
                        </div>
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
        (function ($) {
            "use strict";
            $('.approveBtn').on('click', function() {
                var modal = $('#approveModal');
                modal.find('.withdraw-amount').text($(this).data('amount'));
                modal.find('.withdraw-charge').text($(this).data('charge'));
                modal.find('.withdraw-after_charge').text($(this).data('after_charge'));
                modal.find('.withdraw-rate').text($(this).data('rate'));
                modal.find('.withdraw-payable').text($(this).data('payable'));
                var list = [];
                var details =  Object.entries($(this).data('info'));

                var ImgPath = "{{asset(imagePath()['verify']['deposit']['path'])}}/";
                var singleInfo = '';
                for (var i = 0; i < details.length; i++) {
                    if (details[i][1].type == 'file') {
                        singleInfo += `<li class="list-group-item">
                                            <span class="font-weight-bold "> ${details[i][0].replaceAll('_', " ")} </span> : <img src="${ImgPath}/${details[i][1].field_name}" alt="@lang('Image')" class="w-100">
                                        </li>`;
                    }else{
                        singleInfo += `<li class="list-group-item">
                                            <span class="font-weight-bold "> ${details[i][0].replaceAll('_', " ")} </span> : <span class="font-weight-bold ml-3">${details[i][1].field_name}</span>
                                        </li>`;
                    }
                }

                if (singleInfo)
                {
                    modal.find('.withdraw-detail').html(`<br><strong class="my-3">@lang('Payment Information')</strong>  ${singleInfo}`);
                }else{
                    modal.find('.withdraw-detail').html(`${singleInfo}`);
                }
                modal.modal('show');
            });

            $('.detailBtn').on('click', function() {
                var modal = $('#detailModal');
                var feedback = $(this).data('admin_feedback');
                modal.find('.withdraw-detail').html(`<p> ${feedback} </p>`);
                modal.modal('show');
            });

            $('#paymentGateway').on('change', function(){
                let option = $(this).find('option:selected');
                $('input[name=currency]').val(option.data('currency'));
                var minAmount       = option.data('min_amount');
                var maxAmount       = option.data('max_amount');
                var baseSymbol      = "{{$general->cur_text}}";
                var fixCharge       = option.data('fix_charge');
                var percentCharge   = option.data('percent_charge');
                var depositLimit    = `@lang('Deposit Limit'): ${minAmount} - ${maxAmount}  ${baseSymbol}`;
                $('.depositLimit').text(depositLimit);
                var depositCharge = `@lang('Charge'): ${fixCharge} ${baseSymbol}  ${(0 < percentCharge) ? ' + ' +percentCharge + ' % ' : ''}`;
                $('.depositCharge').text(depositCharge);
            });
        })(jQuery);
    </script>
@endpush
