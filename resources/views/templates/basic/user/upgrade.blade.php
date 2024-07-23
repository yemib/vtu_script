@extends($activeTemplate.'layouts.master')
@section('content')

    <div class="container">

      <?php 
		$user = auth()->user();
		$general = App\Models\GeneralSetting::first();
		
		?>
   <br/>
      
      <?php 
		switch($type){
				
			case 'resseller':
				
				$title = "Resseller";
				
				break ;
			case 'manager':
				
				$title = "Manager";
				
				break;				
			case 'supermanager':
				$title = "Super Manager";
				
				break ; 
				
				default :
				
				$title = " ";
				
				
		}
		
		
		?>
      
                 
                 
                 <?php
					$role = App\Models\role::where('name'  , $title)->first();
					
					
					
					
					?>
      
       
        <div class="row align-items-center mb-3">
           <h3> Upgrade To {{  $title }}
                 
                 @if(isset($role->id))
                 <p>   Amount :  {{ $general->cur_sym }}{{ showAmount($role->levy) }} </p> 
                 
                 @endif
                 
                 
           </h3>
           
           <br/>
           
            <div class="col-6">
                <h6> <a  onClick="return confirm('Are you sure you want to upgrade?')"   href="/user/pay_upgrade/{{$type}}" type="button" class="btn btn-lg btn--base"><i class="las la-plus-upgrade"></i>Upgrade</a></h6>
            </div>
       
        </div>

        <div class="row justify-content-center mb-none-30">
            <div class="col-lg-12">
               
               
                <div class="custom--card">
      
                 
                 @if(isset($role->id))
                 
                 {!!  $role->instruction !!}
                 
                 @endif
                 
                 
                  
                </div>
            </div>
        </div>
    </div>




@endsection


