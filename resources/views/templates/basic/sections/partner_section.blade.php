@php
    $content        = getContent('partner_section.content', true);
    $elements   = getContent('partner_section.element');
@endphp

<section class="pt-50 pb-50">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-6 text-center">
        <div class="section-header">
          <h2 class="section-title">@lang($content->data_values->heading)</h2>
        </div>
      </div>
    </div>
  <div class="row wow fadeInUp" data-wow-duration="0.5s" data-wow-delay="0.3s">
            <div class="col-lg-12">
                <div class="brand-slider">

                    @foreach($elements as $element)
                        <div class="single-slide">
                            <div class="brand-item">
                                <img src="{{ getImage( 'assets/images/frontend/partner_section/' .@$element->data_values->image, '300x300') }}"
                                    alt="image">
                            </div>
                        </div><!-- single-slide end -->
                    @endforeach
                </div>
            </div>
        </div><!-- row end -->
  </div>
</section>
