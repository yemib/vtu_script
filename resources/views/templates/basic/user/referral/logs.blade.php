@extends($activeTemplate.'layouts.master')
@section('content')

    <div class="container">

      <?php 
		$user = auth()->user();
		$general = App\Models\GeneralSetting::first();
		
		?>

       
       
       <button type="button" data-bs-toggle="modal" data-bs-target="#depositModal" class="btn btn-sm btn--base"><i class="las la-plus-circle"></i> Bonus Balance :  {{ $general->cur_sym }}{{ showAmount($user->bonus_balance) }}</button>
       
       <br/>
       
        <div class="row align-items-center mb-3">
            <div class="col-6">
                <h6>@lang('My Referral Commission Log')</h6>
            </div>
            <div class="col-6 text-end">
                <button type="button" data-bs-toggle="modal" data-bs-target="#depositModal" class="btn btn-sm btn--base"><i class="las la-plus-circle"></i>@lang('Transfer To Wallet')</button>
            </div>
        </div>

        <div class="row justify-content-center mb-none-30">
            <div class="col-lg-12">
                <div class="custom--card">
                    <div class="table-responsive--md">
                        <table class="table custom--table">
                            <thead>
                                <tr>
                                    <th scope="col">@lang('Date')</th>
                                    <th scope="col">@lang('From')</th>
                                  <!--  <th scope="col">@lang('Level')</th>   -->
                                    <th scope="col">@lang('Percent')</th>
                                    <th scope="col">@lang('Amount')</th>
                                    <th scope="col">@lang('Type')</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($logs as $data)
             <tr @if($data->amount < 0) class="halka-golapi" @endif>
                                        <td data-label="@lang('Date')">{{showDateTime($data->created_at,'d M, Y')}}</td>
                                        <td data-label="@lang('From')"><strong>{{@$data->bywho->username}}</strong></td>
                                        
                                   <!--     <td data-label="@lang('Level')">{{__(ordinal($data->level))}} @lang('Level')</td>   -->
                                        
                                        
                                        <td data-label="@lang('Percent')">{{getAmount($data->percent)}} %</td>
                                        <td data-label="@lang('Amount')">{{__($general->cur_sym)}}{{getAmount($data->commission_amount)}}</td>
                                        <td data-label="@lang('Type')">{{__($data->type)}}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td class="text-right" colspan="100%">{{__($emptyMessage)}}</td>
                                    </tr>
                                @endforelse
                                </tbody>
                        </table>
                        
                        
                      
                    </div>
                </div>
            </div>
        </div>
          {{  $logs->links() }}
    </div>



<!-- Deposit Modal -->
    <div id="depositModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Transfer Bonus to Wallet')</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{route('user.transfer.bonus')}}" method="post">
                <div class="modal-body">
                        @csrf
                      
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
