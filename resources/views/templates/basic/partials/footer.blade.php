@php
    $footer     = getContent('footer.content', true);
    $datas      = getContent('footer.element');
    $contacts   = getContent('contact_us.element');
    $about      = getContent('about.content', true);
    $links      = getContent('pages.element');
@endphp

<!-- footer section start -->
<footer class="footer position-relative z-index-2">
    <div class="footer-bottom-wave">
      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320">
        <path fill="#ffffff" fill-opacity="1"
         d="M0,256L48,266.7C96,277,192,299,288,282.7C384,267,480,213,576,165.3C672,117,768,75,864,96C960,117,1056,203,1152,213.3C1248,224,1344,160,1392,128L1440,96L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path></svg>
    </div>

    <div class="container">
      <div class="row gy-4">
        <div class="col-lg-4 col-sm-6 order-lg-1 order-1">
          <div class="footer-widget">
            <h3 class="footer-widget__title">@lang('About Us')</h3>
            <p>{{ __(@$about->data_values->subheading) }}</p>
            <ul class="social-media-links d-flex align-items-center mt-3">
              @foreach($datas as $data)
                <li>
                  <a href="{{ $data->data_values->social_link }}" target="_blank">
                    @php echo $data->data_values->social_icon; @endphp
                  </a>
                </li>
              @endforeach
            </ul>
          </div>
        </div>
        <div class="col-lg-2 col-sm-6 order-lg-2 order-3">
          <div class="footer-widget">
            <h3 class="footer-widget__title">@lang('Quick Links')</h3>
            <ul class="short-link-list">
                <li><a href="{{ route('user.login') }}">@lang('Login')</a></li>
                <li><a href="{{ route('user.register') }}">@lang('Register')</a></li>
                <li><a href="{{ route('contact') }}">@lang('Contact')</a></li>
            </ul>
          </div>
        </div>
        <div class="col-lg-2 col-sm-6 order-lg-3 order-4">
          <div class="footer-widget">
            <h3 class="footer-widget__title">@lang('Page')</h3>
            <ul class="short-link-list">
                @foreach($links as $link)
                    <li>
                        <a href="{{ route('page', [$link->id,slug($link->data_values->title)]) }}">
                        {{ __($link->data_values->title) }}</a>
                    </li>
                @endforeach
            </ul>
          </div>
        </div>
        <div class="col-lg-4 col-sm-6 order-lg-4 order-2">
          <div class="footer-widget">
            <h3 class="footer-widget__title">@lang('Contact Us')</h3>
            <ul class="footer-info-list">
             {{--    @foreach($contacts as $contact)
                <li>
                    @php echo $contact->data_values->icon; @endphp
                    <p>{{ $contact->data_values->address }}</p>
                </li>
                @endforeach --}}
            </ul>
          </div>
        </div>
      </div>
      <div class="footer__bottom">
        <div class="row gy-4 align-items-center">
          <div class="col-lg-3 col-sm-6 order-lg-1 order-1 text-sm-start text-center">
            <a href="{{ route('home') }}" class="footer-logo"><img src="{{getImage(imagePath()['logoIcon']['path'] .'/logo.png')}}" alt="logo"></a>
          </div>

          <div class="col-lg-9 col-sm-6 order-lg-3 order-2 text-sm-end text-center">
            <p>{{-- {{ __(@$footer->data_values->text) }} --}}</p>
          </div>
        </div>
      </div>
    </div>
  </footer>
  <!-- footer section end -->
