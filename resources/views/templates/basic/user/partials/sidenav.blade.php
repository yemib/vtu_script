<div class="sidebar {{ sidebarVariation()['selector'] }} {{ sidebarVariation()['sidebar'] }} {{ @sidebarVariation()['overlay'] }} {{ @sidebarVariation()['opacity'] }}"
     data-background="{{getImage('assets/admin/images/sidebar/2.jpg','400x800')}}">
    <button class="res-sidebar-close-btn"><i class="las la-times"></i></button>
    <div class="sidebar__inner">
        <div class="sidebar__logo">
            <a href="/user/dashboard" class="sidebar__main-logo"><img
                    src="{{getImage(imagePath()['logoIcon']['path'] .'/logo.png')}}" alt="@lang('image')"></a>
            <a href="/user/dashboard" class="sidebar__logo-shape"><img
                    src="{{getImage(imagePath()['logoIcon']['path'] .'/favicon.png')}}" alt="@lang('image')"></a>
            <button type="button" class="navbar__expand"></button>
        </div>

        <div class="sidebar__menu-wrapper" id="sidebar__menuWrapper">


                     <ul class="sidebar__menu">
                   <li class="sidebar-menu-item {{menuActive('user.home')}}">

                        <a class="" href="{{ route('user.home') }}">
                          <i class="menu-icon las la-home"></i>
			<span class="menu-title">   @lang('Dashboard')    </span>  </a>
                    </li>


                        <li  class="sidebar-menu-item {{menuActive('user.transaction.history*')}}">

                            <a class="" href="{{ route('user.transaction.history') }}">
                            <i class="menu-icon las la-credit-card"></i>
                            	<span class="menu-title">
                         Transactions
								</span>
                            </a>
                        </li>



                        <li  class="sidebar-menu-item  {{menuActive('user.deposit.history*')}}">

                            <a class="" href="/user/deposits">
                            <i class="menu-icon la la-money-bill-wave"></i>
                            <span class="menu-title">
                           Fund Wallet
								</span>

                            </a>
                        </li>



                        <li   class="sidebar-menu-item   {{ menuActive('user.data*')}}">

                            <a class="" href="{{ route('user.data') }}">
                            <i class="menu-icon las la-money-bill"></i>
                             <span class="menu-title">
                        Data
								</span>
                            </a>
                        </li>



                        <li  class="sidebar-menu-item  {{menuActive('user.airtime*')}}">

                            <a class="" href="{{ route('user.airtime') }}">
                            <i class="menu-icon las la-box"></i>
                             <span class="menu-title">
                          Airtime
								</span>
                            </a>
                        </li>

                   {{--
                        <li  class="sidebar-menu-item  {{menuActive('user.cables*')}}" >

                            <a class="" href="{{ route('user.cables') }}">
                            <i class="menu-icon las la-hand-holding-usd"></i>
                             <span class="menu-title">
                           Cables
								</span>
                            </a>
                        </li>



                        <li  class="sidebar-menu-item  {{menuActive('user.bills*')}}">
                            <a class="" href="{{ route('user.bills') }}">
                            <i class="menu-icon las la-hand-holding-usd"></i>
                            <span class="menu-title">
                            Bills
								</span>
                            </a>
                        </li>
                                --}}
                        {{--      @if(auth()->user()->role  ==  'Manager'   || auth()->user()->role  ==  'Super Manager'   )

                                        <li  class="sidebar-menu-item  {{menuActive('user.my_downlines.users*')}}">
                            <a class="" href="{{ route('user.my_downlines.users') }}">
                            <i class="menu-icon las la-hand-holding-usd"></i>
                            <span class="menu-title">
                      My Downlines
								</span>
                            </a>
                        </li>





                             @endif
 --}}



                   <li  class="sidebar-menu-item  {{menuActive('user.referral.users*')}}">
                            <a class="" href="{{ route('user.referral.users') }}">
                            <i class="menu-icon las la-hand-holding-usd"></i>
                            <span class="menu-title">
                      My Referral
								</span>
                            </a>
                        </li>



                               <li  class="sidebar-menu-item  {{menuActive('user.profile.setting*')}}">
                            <a class="" href="{{ route('user.profile.setting') }}">
                            <i class="menu-icon las la-hand-holding-usd"></i>
                            <span class="menu-title">
                       Profile
								</span>
                            </a>
                        </li>

                      <!--   Register  --->
                           <li  class="sidebar-menu-item  {{menuActive('user.transfer*')}}">
                            <a class="" href="{{ route('user.user_register') }}">
                            <i class="menu-icon las la-hand-holding-usd"></i>
                            <span class="menu-title">
                     Register  User
								</span>
                            </a>
                        </li>


               {{--      <li  style="" class="sidebar-menu-item  ">

                        <a class="" href="">
                        <i class="menu-icon la la-ticket"></i>
                        <span class="menu-title">
                        @lang('Float')
							</span>
                        </a>
                    </li>
 --}}
                    <li  style="" class="sidebar-menu-item  {{ menuActive([ 'ticket', 'ticket.open', 'ticket.view']) }}">

                        <a class="" href="{{ route('ticket') }}">
                        <i class="menu-icon la la-ticket"></i>
                        <span class="menu-title">
                        @lang('Support Ticket')
							</span>
                        </a>
                    </li>



                </ul>


        </div>
    </div>
</div>
<!-- sidebar end -->
