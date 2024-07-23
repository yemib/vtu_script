@extends('templates.basic.layouts.auth')

@section('content')

@php
    $forgetPass = getContent('forget_pass.content', true);
    $links = getContent('pages.element');
@endphp

<section class="account-section bg_img" style="background-image: url(' {{ getImage( 'assets/images/frontend/forget_pass/' .@$forgetPass->data_values->image, '1920x1280') }} ');">
    <div class="account-section-left">
        <div class="account-section-left-inner">
         
        </div>
    </div>
    <div class="account-section-right">
        <div class="top text-center">
            <a href="{{route('home')}}" class="account-logo">
                <img src="{{getImage(imagePath()['logoIcon']['path'] .'/logo.png')}}" alt="logo">
            </a>
            
               <h2 class="title text-white mb-2">{{ __(@$forgetPass->data_values->heading) }}</h2>
            <p class="text-white">{{ __(@$forgetPass->data_values->subheading) }}</p>
            
        </div>
        <div class="middle">
            <form class="account-form" method="POST" action="{{ route('user.password.email') }}">
              @csrf
              <div class="form-group">
              <label for="type">@lang('My')</label>
                  <select class="select" name="type" id="type">
                      <option value="email">@lang('E-Mail Address')</option>
                      <option value="username">@lang('Username')</option>
                  </select>
              </div>
              <div class="form-group">
              <label class="my_value"></label>
              <input type="text" class="form--control @error('value') is-invalid @enderror" name="value" value="{{ old('value') }}" required autofocus="off">
              </div>
              <button type="submit" class="btn btn--base w-100">@lang('Submit')</button>
          </form>
        </div>
        <div class="bottom">
            <div class="row">
                <div class="col-xl-12">
                    <ul class="d-flex flex-wrap align-items-center account-short-link justify-content-center">
                            @foreach($links as $link)
                                <li><a href="{{ route('page', [$link->id,slug($link->data_values->title)]) }}" target="blank">
                        {{ __($link->data_values->title) }}</a>
                        {{ $loop->last ? '.' : ',' }}</li>
                            @endforeach
                        </ul>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
@push('script')
<script>

    (function($){
        "use strict";

        myVal();
        $('select[name=type]').on('change',function(){
            myVal();
        });
        function myVal(){
            $('.my_value').text($('select[name=type] :selected').text());
        }
    })(jQuery)
</script>
@endpush
