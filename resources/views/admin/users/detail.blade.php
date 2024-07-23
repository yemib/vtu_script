@extends('admin.layouts.app')

@section('panel')
    <div class="row mb-none-30">
        <div class="col-xl-3 col-lg-5 col-md-5 col-sm mb-30">

           
           
           
            <div class="card b-radius--10 overflow-hidden mt-30 box--shadow1">
                <div class="card-body">
                    <h5 class="mb-20 text-muted">@lang('User information')</h5>
                    <ul class="list-group">

                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('Username')
                            <span class="font-weight-bold">{{$user->username}}</span>
                        </li>


                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('Status')
                            @if($user->status == 1)
                                <span class="badge badge-pill bg--success">@lang('Active')</span>
                            @elseif($user->status == 0)
                                <span class="badge badge-pill bg--danger">@lang('Banned')</span>
                            @endif
                        </li>

                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('Balance')
                            <span class="font-weight-bold">{{showAmount($user->balance)}}  {{__($general->cur_text)}}</span>
                        </li>
                    </ul>
                </div>
            </div>
            
                       
            <div class="card b-radius--10 overflow-hidden mt-30 box--shadow1">
                <div class="card-body">
                    <h5 class="mb-20 text-muted">@lang('User action')</h5>
                    <a data-toggle="modal" href="#addSubModal" class="btn btn--success btn--shadow btn-block btn-lg">
                        @lang('Add/Subtract Balance')
                    </a>
                    <a href="{{ route('admin.users.login.history.single', $user->id) }}"
                       class="btn btn--primary btn--shadow btn-block btn-lg">
                        @lang('Login Logs')
                    </a>
                    <a href="{{route('admin.users.email.single',$user->id)}}"
                       class="btn btn--info btn--shadow btn-block btn-lg">
                        @lang('Send Email')
                    </a>
					
					
                    <a href="{{route('admin.users.login',$user->id)}}" target="_blank" class="btn btn--dark btn--shadow btn-block btn-lg">
                        @lang('Login as User')
                    </a>
					
					
					
					
					
                    <!--
                    <a href="{{route('admin.users.email.log',$user->id)}}" class="btn btn--warning btn--shadow btn-block btn-lg">
                        @lang('Email Log')
                    </a>   -->
                </div>
				
				
            </div>
            
            
           
            <div class="card b-radius--10 overflow-hidden box--shadow1">
                <div class="card-body p-0">
                    <div class="p-3 bg--white">
                        <div class="">
                            <img src="{{ getImage(imagePath()['profile']['user']['path'].'/'.$user->image,imagePath()['profile']['user']['size'])}}" alt="@lang('Profile Image')" class="b-radius--10 w-100">
                        </div>
                        <div class="mt-15">
                            <h4 class="">{{$user->fullname}}</h4>
                            <span class="text--small">@lang('Joined At') <strong>{{showDateTime($user->created_at,'d M, Y h:i A')}}</strong></span>
                        </div>
                    </div>
                </div>
            </div>


        </div>

        <div class="col-xl-9 col-lg-7 col-md-7 mb-30">

            <div class="row mb-none-30">
 
                <div class="col-xl-3 col-lg-6 col-sm-6 mb-30">
                    <div class="dashboard-w1 bg--12 b-radius--10 box-shadow has--link">
                        <a href="{{route('admin.users.transactions',$user->id)}}" class="item--link"></a>
                        <div class="icon">
                            <i class="la la-exchange-alt"></i>
                        </div>
                        <div class="details">
                            <div class="numbers">
                                <span class="amount">{{$totalTransaction}}</span>
                            </div>
                            <div class="desciption">
                                <span>@lang('Transactions')</span>
                            </div>
                        </div>
                    </div>
                </div><!-- dashboard-w1 end -->


            </div>


            <div class="card mt-50">
                <div class="card-body">
                    <h5 class="card-title border-bottom pb-2">@lang('Information of') {{$user->fullname}}</h5>

                    <form action="{{route('admin.users.update',[$user->id])}}" method="POST"
                          enctype="multipart/form-data">
                        @csrf

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group ">
                                    <label class="form-control-label font-weight-bold">@lang('First Name')<span class="text-danger">*</span></label>
                                    <input class="form-control" type="text" name="firstname" value="{{$user->firstname}}">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-control-label  font-weight-bold">@lang('Last Name') <span class="text-danger">*</span></label>
                                    <input class="form-control" type="text" name="lastname" value="{{$user->lastname}}">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group ">
                                    <label class="form-control-label font-weight-bold">@lang('Email') <span class="text-danger">*</span></label>
                                    <input class="form-control" type="email" name="email" value="{{$user->email}}">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-control-label  font-weight-bold">@lang('Mobile Number') <span class="text-danger">*</span></label>
                                    <input class="form-control" type="text" name="mobile" value="{{$user->mobile}}">
                                </div>
                            </div>
                        </div>


                        <div class="row mt-4">
                     
                            
                            
                            <div class="col-md-12">
                                <div class="form-group ">
  <label class="form-control-label font-weight-bold">Change Role </label>
                                    
                                    <?php   $roles  = App\Models\role::get();   ?>
                                    
               <select  class="form-control"   name="role">
                                
                       @foreach($roles  as $role) 
                           @if($role->name != 'Resseller')     
      <option @if($role->name == @$user->role ) selected @endif  value="{{$role->name}}">{{ $role->name }}</option>
                                	
                              @endif  	
                                	@endforeach
                                </select>
                                    
                                    
                                    
                                </div>
                            </div>
                            
                                     
                            @if($user->role  ==  'Manager')
                            
                            
                            <div class="col-md-12">
                               <?php 
$users  = App\Models\User::where( [['role'  ,  "Referral"]  , ['manager' ,  0]   ])->orderby('id'   ,  'desc')->paginate(50);   ?> 
                         @if(count($users)  !=  0 )             
                               
                               
                                <div class="form-group ">
  <label class="form-control-label font-weight-bold">Add  User</label>
                                    

                                    
               <select multiple  class="form-control"   name="add_user[]">
                                
                       @foreach($users  as $ref) 
                                
      <option  value="{{  $ref->id    }}"> {{  $ref->email    }}   ({{  $ref->getFullnameAttribute()  }})  </option>
                                	
                                	
                                	@endforeach
                                </select>
                                    
                                    
                                    
                                </div>
                            
                            @else 
                            
                            <h3> No User Available </h3>
                            
                            @endif
                            
                            </div>
                            
                            
                            @endif                     
                            @if($user->role  ==  'Super Manager')
                            
                            
                            <div class="col-md-12">
                                <div class="form-group ">
  <label class="form-control-label font-weight-bold">Assign Manager  to  SuperManager  </label>
                                    
                                    <?php 
	
									
$users  = App\Models\User::where( [['role'  ,  "Manager"]   ,  ['super_manager'  ,     0  ]    ])->orderby('id'   ,  'desc')->paginate(50);   ?>
             
                                   @if(count($users)  !=  0 )   
                                    
                                                                          
               <select multiple  class="form-control"   name="add_manager[]">
                                
                       @foreach($users  as $ref) 
                                
      <option  value="{{  $ref->id    }}"> {{  $ref->email    }}   ({{  $ref->getFullnameAttribute()  }})  </option>
                                	
                                	
                                	@endforeach
                                </select>
                                    
                      
                                   @else  
                                   
                                   <h3> No Manager Available </h3>
                                    @endif              
                                    
                                </div>
                            </div>
                            
                            
                            @endif
                            
                            <!---  Add user under the manager okay  !!  --->
                            
                            
                            
                            
                        </div>


                        <div class="row">
                            <div class="form-group col-xl-4">
                                <label class="form-control-label font-weight-bold">@lang('Status') </label>
                                <input type="checkbox"
                                    data-onstyle="-success" data-offstyle="-danger"
                                    data-toggle="toggle" data-on="@lang('Active')" data-off="@lang('Banned')" data-width="100%"
                                    name="status"
                                    @if($user->status) checked @endif>
                            </div>

                            <div class="form-group  col-xl-4 ">
                                <label class="form-control-label font-weight-bold">@lang('Email Verification') </label>
                                <input type="checkbox" data-width="100%" data-onstyle="-success" data-offstyle="-danger"
                                       data-toggle="toggle" data-on="@lang('Verified')" data-off="@lang('Unverified')" name="ev"
                                       @if($user->ev) checked @endif>

                            </div>

                            <div class="form-group  col-xl-4 ">
                                <label class="form-control-label font-weight-bold">@lang('SMS Verification') </label>
                                <input type="checkbox" data-width="100%" data-onstyle="-success" data-offstyle="-danger"
                                       data-toggle="toggle" data-on="@lang('Verified')" data-off="@lang('Unverified')" name="sv"
                                       @if($user->sv) checked @endif>

                            </div>
                            
                            <div class="form-group col-xl-4">
                                <label class="form-control-label font-weight-bold">@lang('2FA Status') </label>
                                <input type="checkbox" data-width="100%" data-onstyle="-success" data-offstyle="-danger"
                                       data-toggle="toggle" data-on="@lang('Active')" data-off="@lang('Deactive')" name="ts"
                                       @if($user->ts) checked @endif>
                            </div>

                            <div class="form-group col-xl-4">
                                <label class="form-control-label font-weight-bold">@lang('2FA Verification') </label>
                                <input type="checkbox" data-width="100%" data-onstyle="-success" data-offstyle="-danger" data-toggle="toggle" data-on="@lang('Verified')" data-off="@lang('Unverified')" name="tv" @if($user->tv) checked @endif>
                            </div>

                            <div class="form-group col-xl-4">
                                <label class="form-control-label font-weight-bold">@lang('KYC Verification') </label>
                                <input type="checkbox" data-width="100%" data-onstyle="-success" data-offstyle="-danger" data-toggle="toggle" data-on="@lang('Verified')" data-off="@lang('Unverified')" name="kycv" @if($user->kycv) checked @endif>
                            </div>       
							
							<div class="form-group col-xl-4">
                                <label class="form-control-label font-weight-bold">@lang('Upgrade to Resseller') </label>
                                <input type="checkbox" data-width="100%" data-onstyle="-success" data-offstyle="-danger" data-toggle="toggle" data-on="@lang('Verified')" data-off="@lang('Unverified')" name="resseller" @if($user->resseller) checked @endif>
                            </div>
                            
                        </div>

                        <div class="row mt-4">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <button type="submit" class="btn btn--primary btn-block btn-lg">@lang('Save Changes')
                                    </button>
                                </div>
                            </div>

                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>



    {{-- Add Sub Balance MODAL --}}
    <div id="addSubModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Add / Subtract Balance')</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{route('admin.users.add.sub.balance', $user->id)}}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <input type="checkbox" data-width="100%" data-onstyle="-success" data-offstyle="-danger" data-toggle="toggle" data-on="@lang('Add Balance')" data-off="@lang('Subtract Balance')" name="act" checked>
                            </div>


                            <div class="form-group col-md-12">
                                <label>@lang('Amount')<span class="text-danger">*</span></label>
                                <div class="input-group has_append">
                                    <input type="text" name="amount" class="form-control" placeholder="@lang('Please provide positive amount')">
                                    <div class="input-group-append">
                                        <div class="input-group-text">{{ __($general->cur_sym) }}</div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form-group col-md-12">
                                <label>@lang('Reason')<span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <textarea required type="text" name="reason" class="form-control" placeholder="@lang('Please provide Reason')"></textarea>
                                  
                                </div>
                            </div>
                            
                            
                            
                            
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn--dark" data-dismiss="modal">@lang('Close')</button>
                        <button type="submit" class="btn btn--success">@lang('Submit')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection
