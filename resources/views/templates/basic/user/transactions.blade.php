@extends($activeTemplate.'layouts.master')
@section('content')

<!-- dashboard section start -->
<div class="container">
    <div class="row justify-content-center mb-none-30">
        <div class="col-lg-12">
           
           
      <strong>     {{$pageTitle}}</strong>
           
            <div class="custom--card">
                <div class="table-responsive--md"   style="">
                    <table class="table custom--table">
                        <thead>
                            <tr>
                                <th>@lang('Date')</th>
                                <th>@lang('Trx')</th>
                             
                                <th>@lang('Amount')</th>
                                <th>@lang('Post Balance')</th>
                                   <th>@lang('Details')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($histories as $index => $data)
                                <tr>
                                    <td data-label="Date">
                                        {{ showDateTime($data->created_at, 'd M, Y h:i A') }}
                                    </td>
                                    <td data-label="Trx">
                                        {{ $data->trx }}
                                    </td>

                               

                                    <td data-label="Amount" class="fw-bold @if($data->trx_type == '-') text--danger @endif">
                                        {{ $data->trx_type }}
                                        {{ showAmount($data->amount) }}
                                        {{ __($general->cur_text) }}
                                    </td>
                                    <td data-label="Post Balance">
                                        {{ showAmount($data->post_balance) }}
                                        {{ __($general->cur_text) }}
                                    </td>
                                    
                                         <td data-label="Details">
                                       {{  __($data->details) }} 
                                       
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="100%" class="text-center">{{ __($emptyMessage) }}</td>
                                </tr>
                            @endforelse

                        </tbody>
                    </table>
                    {{ $histories->links() }}
                </div>
            </div>
        </div>
    </div>
    <!-- dashboard section end -->
</div>
@endsection

@push('bottom-menu')
    <li><a href="{{ route('user.profile.setting') }}">@lang('Profile')</a></li>
    @if($general->modules->referral_system)
        <li><a href="{{ route('user.referral.users') }}">@lang('Referral')</a></li>
    @endif
    <li><a href="{{ route('user.twofactor') }}">@lang('2FA Security')</a></li>
    <li><a href="{{ route('user.change.password') }}">@lang('Change Password')</a></li>
    <li><a class="active" href="{{ route('user.transaction.history') }}">@lang('Transactions')</a></li>
    <li><a class="{{ menuActive(['ticket.*']) }}" href="{{ route('ticket') }}">@lang('Support Tickets')</a></li>
@endpush
