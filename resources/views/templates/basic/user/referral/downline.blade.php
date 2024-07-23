@extends($activeTemplate.'layouts.master')
@section('content')

    <div class="container">

        <div class="row align-items-center mb-3">
            <div class="col-6">
                <h6>@lang('My Downlines')</h6>
            </div>
            <div  style="" class="col-6 text-end">
                <a href="{{ route('user.referral.commissions.logs') }}" class="btn btn-sm btn--base"><i class="las la-list"> </i>@lang('Commission Logs')</a>
            </div>
        </div>

        <div class="row justify-content-center mb-none-30"  style="overflow: auto !important">
            <div class="col-lg-12">
                <div class="custom--card">
                    <div class="table-responsive--md">
                        <table class="table custom--table">
                            <thead>
                                <tr>
                                    <th>@lang('S.N.')</th>
                                    <th>@lang('Full Name')</th>
                                    <th>@lang('Email')</th>
                                    <th>@lang('Level')</th>
                                    <th>@lang('Phone')</th>
                                    <th>@lang('Balance')</th>
                                    <th>@lang('Action')</th>
                                    <th>@lang('Joined At')</th>
                                </tr>
                            </thead>
                            <tbody class="list">
                                @foreach ($referees as $user)
                                   <tr> 
                                    <td data-lable="@lang('S.N.')">{{ $referees->firstItem() + $loop->index }}</td>
                                    <td data-lable="@lang('Full Name')">{{ $user->fullname }}</td>  
                                    
                                      
                                     <td data-
                                    
                                    lable="@lang('Email')">{{ $user->email }}</td> 
                                    
                                       
                                     <td data-
                                    
                                    lable="@lang('Email')">{{ $user->role }}</td> 
                                    
                                    
                                    
                                    <td data-
                                    
                                    lable="@lang('Email')">{{ $user->mobile }}</td>
                                    
                                    <td>
                                    <?php  $trans = App\Models\Transaction::where('user_id'  , $user->id)->count() ?>
                                     
                                      {{ $user->balance }}
                                        </td>
                                        
                                        <td> 
                                        <a onClick="$('#nameuser').html('{{ $user->fullname}}')  ;  $('#iduser').val({{$user->id}})" data-bs-toggle="modal"   data-bs-target="#depositModal"  class="btn btn-sm btn--base" >  Wallet </a> 
                                        <a    href="/user/downline_transactions/{{$user->id}}" class="btn btn-sm btn--base"> Transactions </a> 
                                        
                                        </td>
                                    
                                    <td data-lable="@lang('Joined At')">{{ showDateTime($user->created_at, 'd M, Y h:i A') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        {{$referees->links()}}
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    
    
    <!-- Deposit Modal -->
    <div id="depositModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Deposit Money For ')</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{route('user.downline_deposit.users')}}" method="post">
                <div class="modal-body">
                        @csrf
                          <div class="form-group">
                            <label class="fw-bold" for="amount"> Name : </label>
                            
                     <label   id="nameuser"></label>
                     
                     <input  name="userid"   type="hidden" value=""   id="iduser"   />
					</div>
                      
                        <div class="form-group">
                            <label class="fw-bold" for="amount">@lang('Amount')</label>
                            <div class="input-group">
                                <input id="amount" type="text" class="form--control" name="amount" placeholder="0.00" value="{{old('amount')}}" required>
                                <span class="input-group-text bg--base text-white border--base">{{__($general->cur_text)}}</span>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger btn-md" data-bs-dismiss="modal">@lang('Close')</button>
                        <button type="submit" class="btn btn--base btn-md">@lang('Confirm')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    

@endsection

@push('bottom-menu')
<li><a href="{{ route('user.profile.setting') }}">@lang('Profile')</a></li>
<li><a class="active" href="{{ route('user.referral.users') }}">@lang('Referral')</a></li>
<li><a href="{{ route('user.twofactor') }}">@lang('2FA Security')</a></li>
<li><a href="{{ route('user.change.password') }}">@lang('Change Password')</a></li>
<li><a href="{{ route('user.transaction.history') }}">@lang('Transactions')</a></li>
<li><a class="{{ menuActive(['ticket.*']) }}" href="{{ route('ticket') }}">@lang('Support Tickets')</a></li>
@endpush




