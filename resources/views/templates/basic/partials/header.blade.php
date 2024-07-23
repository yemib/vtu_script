    <!-- header-section start  -->
    <header class="header">
        <div class="header__bottom">
          <div class="container">
            <nav class="navbar navbar-expand-lg p-0 align-items-center justify-content-between">
              <a class="site-logo site-title" href="{{ route('home') }}">
                <img src="{{getImage(imagePath()['logoIcon']['path'] .'/logo.png')}}" alt="logo">
            </a>
              <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="menu-toggle"></span>
              </button>
            <div class="collapse navbar-collapse mt-xl-0 mt-3" id="navbarSupportedContent">
                <ul class="navbar-nav main-menu m-auto" id="linkItem">
                  <li><a class="{{ menuActive('home') }}" href="{{route('home')}}">@lang('Home')</a></li>
                   
                   <?php 
                   /**
                    @foreach($pages as $k => $data)
                        <li>
                            <a class="@if($data->slug ==  Request::segment(1)) active @endif" href="{{route('pages',[$data->slug])}}">
                                {{__($data->name)}}
                            </a>
                        </li>
                    @endforeach
								
							**/	
								
								?>
                    
                    
                    <li><a class="{{ menuActive('contact') }}" href="{{ route('contact') }}">@lang('Contact')</a></li>
                </ul>
                <div class="nav-right">
                    <select  style="display: none !important"  class="language-select me-3 langSel">
                        @foreach($language as $item)
                            <option value="{{$item->code}}" @if(session('lang') == $item->code) selected  @endif>{{ __($item->name) }}</option>
                        @endforeach
                    </select>

                    @if(!Auth::user())
                        <a href="{{ route('user.login') }}" class="btn btn-sm py-2 btn-outline--gradient me-3">@lang('Sign In')</a>
                        <a href="{{ route('user.register') }}" class="btn btn-sm py-2 custom--bg text-white">@lang('Sign Up')</a>
                    @else
                        <a href="{{ route('user.home') }}" class="btn btn-sm py-2 btn-outline--gradient me-3">@lang('Dashboard')</a>
                        <a href="{{ route('user.logout') }}" class="btn btn-sm py-2 custom--bg text-white">@lang('Logout')</a>
                    @endif

                </div>
              </div>
            </nav>
          </div>
        </div><!-- header__bottom end -->
      </header>
      <!-- header-section end  -->
