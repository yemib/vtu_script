@php
    $subscribe = getContent('subscribe.content', true);
    $gateWays = App\Models\Gateway::where('status', 1)->get();
@endphp
<!-- subsacribe section start -->
<section class="subscribe-section">
    <div class="container">
        <div class="row gy-3 justify-content-between align-items-center">
            <div class="col-xxl-5 col-xl-6 col-lg-4 text-lg-start text-center wow fadeInUp" data-wow-duration="0.5s" data-wow-delay="0.3s">
                <h4 class="text-white">{{ __(@$subscribe->data_values->heading) }}</h4>
            </div>
            <div class="col-xxl-7 col-xl-6 col-lg-8 wow fadeInUp" data-wow-duration="0.5s" data-wow-delay="0.3s">
                <form class="subscribe-form" id="subscribeForm">
                    @csrf
                    <div class="custom-icon-field">
                        <input type="email" name="email" class="form--control" placeholder="Enter email address">
                        <i class="las la-envelope"></i>
                    </div>
                    <button type="submit" class="btn custom--bg">@lang('Subsscribe')</button>
                </form>
            </div>
        </div><!-- row end -->


    </div>
</section>
<!-- subsacribe section end -->
@push('script')

    <script>
        (function ($) {
            "use strict";
            var form = $("#subscribeForm");
            form.on('submit', function (e) {
                e.preventDefault();
                var data = form.serialize();
                $.ajax({
                    url: `{{ route('subscribe') }}`,
                    method: 'post',
                    data: data,
                    success: function (response) {
                        if (response.success) {
                            form.find('input[name=email]').val('');
                            notify('success', response.message);
                        }else{
                            notify('error', response.error);
                        }
                    }
                });
            });

        })(jQuery);
    </script>
@endpush
