@php
    $faq        = getContent('faq.content', true);
    $faqs       = getContent('faq.element', false, null, true);
    $faqs       = $faqs->chunk(ceil($faqs->count()/2));
@endphp

@if($faq)
    <section id="faq" class="pt-100 pb-100 section--bg">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xxl-5 col-lg-7 wow fadeInLeft" data-wow-duration="0.5s" data-wow-delay="0.3s">
                    <div class="section-header text-center">
                        <h2 class="section-title">{{ __(@$faq->data_values->heading) }}</h2>
                        <p class="mt-2">{{ __(@$faq->data_values->subheading) }}</p>
                    </div>
                </div>
            </div><!-- row end -->
            <div class="accordion custom--accordion" id="faqAccordion">
                <div class="row gy-4 justify-content-center">

                    @isset($faqs[0])
                    <div class="col-lg-6 wow fadeInRight" data-wow-duration="0.5s" data-wow-delay="0.3s">
                        @foreach($faqs[0] as $element)
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="h-{{ $element->id }}">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#c-{{ $element->id }}" aria-expanded="false" aria-controls="c-{{ $element->id }}">
                                        {{ __($element->data_values->question) }}
                                    </button>
                                </h2>
                                <div id="c-{{ $element->id }}" class="accordion-collapse collapse"
                                    aria-labelledby="h-{{ $element->id }}" data-bs-parent="#faqAccordion">
                                    <div class="accordion-body">
                                        <p>{{ __($element->data_values->answer) }}</p>
                                    </div>
                                </div>
                            </div><!-- accordion-item-->
                        @endforeach
                    </div>
                    @endisset
                    @isset($faqs[1])
                    <div class="col-lg-6 wow fadeInRight" data-wow-duration="0.5s" data-wow-delay="0.3s">
                        @foreach($faqs[1] as $element)
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="h-{{ $element->id }}">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#c-{{ $element->id }}" aria-expanded="false" aria-controls="c-{{ $element->id }}">
                                        {{ __($element->data_values->question) }}
                                    </button>
                                </h2>
                                <div id="c-{{ $element->id }}" class="accordion-collapse collapse"
                                    aria-labelledby="h-{{ $element->id }}" data-bs-parent="#faqAccordion">
                                    <div class="accordion-body">
                                        <p>{{ __($element->data_values->answer) }}</p>
                                    </div>
                                </div>
                            </div><!-- accordion-item-->
                        @endforeach
                    </div>
                    @endisset
                </div>
            </div>
        </div>
    </section>
@endif
