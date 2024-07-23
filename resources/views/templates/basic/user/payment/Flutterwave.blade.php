@extends($activeTemplate.'layouts.master')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-xl-4 col-lg-5 col-md-7 col-sm-9">
            <div class="card border--base">
                <div class="card-body text-center">
                    <img src="{{$deposit->gatewayCurrency()->methodImage()}}" class="w-50" alt="@lang('Image')">
                    <h4 class="mt-4">
                        @lang('Please Pay') {{getAmount($deposit->final_amo)}} {{__($deposit->method_currency)}}
                    </h4>
                    <h4 class="my-3">@lang('To Get') {{getAmount($deposit->amount)}} {{__($general->cur_text)}}</h4>
                    <button type="button" class="btn custom--bg text-white w-100 mt-4 btn-custom2 " id="btn-confirm"
                        onClick="payWithRave()">@lang('Pay Now')</button>
                    <script src="https://api.ravepay.co/flwv3-pug/getpaidx/api/flwpbf-inline.js"></script>
                    <script>
                        "use strict"
                        var btn = document.querySelector("#btn-confirm");
                        btn.setAttribute("type", "button");
                        const API_publicKey = "{{$data->API_publicKey}}";

                        function payWithRave() {
                            var x = getpaidSetup({
                                PBFPubKey: API_publicKey,
                                customer_email: "{{$data->customer_email}}",
                                amount: "{{$data->amount }}",
                                customer_phone: "{{$data->customer_phone}}",
                                currency: "{{$data->currency}}",
                                txref: "{{$data->txref}}",
                                onclose: function () {
                                },
                                callback: function (response) {
                                    var txref = response.tx.txRef;
                                    var status = response.tx.status;
                                    var chargeResponse = response.tx.chargeResponseCode;
                                    if (chargeResponse == "00" || chargeResponse == "0") {
                                        window.location = '{{ url('ipn / flutterwave') }}/' + txref + '/' + status;
                                    } else {
                                        window.location = '{{ url('ipn / flutterwave') }}/' + txref + '/' + status;
                                    }
                                    // x.close(); // use this to close the modal immediately after payment.
                                }
                            });
                        }
                    </script>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
