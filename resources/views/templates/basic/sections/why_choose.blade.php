@php
    $choose = getContent('why_choose.content', true);
    $datas = getContent('why_choose.element');
@endphp

   <!-- why choose us section start -->
    <section class="pt-100 pb-50 bg_img dark--overlay-two" style="background-image: url('{{ getImage( 'assets/images/frontend/why_choose/' .@$choose->data_values->image) }}');">
        <div class="bottom-wave d-lg-block d-none">
          <img src="{{ asset($activeTemplateTrue. 'images/elements/white-wave-1.png') }}" alt="wave image">
        </div>
        <div class="container">
          <div class="row gy-4">
            <div class="col-xl-3 text-xl-start text-center">
              <div class="section-header">
                <div class="section-top-title border-left custom--cl">{{ __(@$choose->data_values->title) }}</div>
                <h2 class="section-title text-white">{{ __(@$choose->data_values->heading) }}</h2>
                <a href="{{ @$choose->data_values->btn_link }}" class="btn mt-4 custom--bg text-white">{{ __(@$choose->data_values->btn_text) }}</a>
              </div>
            </div>
            <div class="col-xl-9">
              <div class="row gy-4">

                @foreach($datas as $data)
                <div class="col-md-6 wow fadeInUp" data-wow-duration="0.5s" data-wow-delay="0.3s">
                  <div class="choose-card rounded-3">
                    <div class="choose-card__icon">
                      @php echo $data->data_values->icon @endphp
                    </div>
                    <div class="choose-card__content">
                      <h3 class="title">{{ __($data->data_values->heading) }}</h3>
                      <p>{{ __($data->data_values->subheading) }}</p>
                    </div>
                  </div><!-- choose-card end -->
                </div>
                @endforeach

              </div>
            </div>
          </div>
        </div>
      </section>
      <!-- why choose us section end -->
