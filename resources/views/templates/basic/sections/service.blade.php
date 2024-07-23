@php
    $service = getContent('service.content', true);
    $elements = getContent('service.element');
@endphp

<!-- service section start -->
<section id="services" class="service-section position-relative z-index-2 pt-50 pb-100">
    <div class="top-wave">
        <img src="{{ asset($activeTemplateTrue. 'images/elements/white-wave-2.png') }}" alt="wave image">
    </div>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xxl-7 col-xl-8 col-lg-10">
                <div class="section-header text-center">
                    <div class="section-top-title border-left custom--cl">{{ __($service->data_values->title) }}</div>
                    <h2 class="section-title">{{ __(@$service->data_values->heading) }}</h2>
                </div>
            </div>
        </div><!-- row end -->
        <div class="row justify-content-center gy-4">

            @foreach($elements as $element)
            <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-duration="0.5s" data-wow-delay="0.3s">
                <div class="service-card rounded-3">
                    <div class="service-card__icon rounded-2 custom--cl">
                        @php echo $element->data_values->icon @endphp
                    </div>
                    <div class="service-card__content">
                        <h3 class="title">{{ __($element->data_values->heading) }}</h3>
                        <p class="mt-2">{{ __($element->data_values->subheading) }}</p>
                    </div>
                </div><!-- service-card end -->
            </div>
            @endforeach

        </div>
    </div>
</section>
<!-- service section end -->
