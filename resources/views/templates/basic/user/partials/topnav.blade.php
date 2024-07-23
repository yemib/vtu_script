<!-- navbar-wrapper start -->
<nav class="navbar-wrapper">
    <form class="navbar-search" onsubmit="return false;">
        <button type="submit" class="navbar-search__btn">
            <i class="las la-search"></i>
        </button>
        <input type="search" name="navbar-search__field" id="navbar-search__field"
               placeholder="Search...">
        <button type="button" class="navbar-search__close"><i class="las la-times"></i></button>

        <div id="navbar_search_result_area">
            <ul class="navbar_search_result">
            	
            
            	
            </ul>
        </div>
    </form>

    <div class="navbar__left">
        <button class="res-sidebar-open-btn"><i class="las la-bars"></i></button>
        <button type="button" class="fullscreen-btn">
            <i class="fullscreen-open las la-compress" onclick="openFullscreen();"></i>
            <i class="fullscreen-close las la-compress-arrows-alt" onclick="closeFullscreen();"></i>
        </button>
        
        
        
          <ul class="navbar__action-list">
          
          
          
          <?php  $user  =  auth()->user()  ; 
			  //get currency symbol  
			  
			 $general = App\Models\GeneralSetting::first();
			  
			  ?>
          <li> 
          <a   href="/user/deposits"  class="btn btn-sm py-2 custom--bg text-white"> Balance     : {{$general->cur_sym }}  {{  number_format( $user->balance  ,  2)   }} </a>  </li>
          
          
          
          <li></li>
          <li></li>
          <li></li>
          <li></li>
          <li > <strong>  Level  :   {{ $user->role }}</strong> </li>
		</ul>
        
    </div>

    <div class="navbar__right">
        <ul class="navbar__action-list">
     
    
       
       
	<li>
            		 <a href="{{ route('user.logout') }}" class="btn btn-sm py-2 custom--bg text-white"> <i class="fa fa-sign-out-alt" aria-hidden="true"></i> @lang('Logout')</a>
            		
            	</li>
        </ul>
    </div>
</nav>
<!-- navbar-wrapper end -->
