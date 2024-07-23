@extends($activeTemplate.'layouts.master')
@section('content')

    <div class="container">

        <div class="row align-items-center mb-3">
            <div class="col-6">
                <h6>@lang('My Referred Users')</h6>
            </div>
            <div  style="" class="col-6 text-end">
                <a href="{{ route('user.referral.commissions.logs') }}" class="btn btn-sm btn--base"><i class="las la-list"> </i>@lang('Commission Logs')</a>
            </div>
        </div>

        <div class="row justify-content-center mb-none-30">
            <div class="col-lg-12">
                <div class="custom--card">
                    <div class="table-responsive--md">
                        <table class="table custom--table">
                            <thead>
                                <tr>
                                    <th>@lang('S.N.')</th>
                                    <th>@lang('Full Name')</th>
                                    <th>@lang('Joined At')</th>
                                </tr>
                            </thead>
                            <tbody class="list">
                                @foreach ($referees as $user)
                                   
                                   <tr>
                                    <td data-lable="@lang('S.N.')">{{ $referees->firstItem() + $loop->index }}</td>
                                    <td data-lable="@lang('Full Name')">{{ $user->fullname }}</td>
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

@endsection

@push('bottom-menu')
<li><a href="{{ route('user.profile.setting') }}">@lang('Profile')</a></li>
<li><a class="active" href="{{ route('user.referral.users') }}">@lang('Referral')</a></li>
<li><a href="{{ route('user.twofactor') }}">@lang('2FA Security')</a></li>
<li><a href="{{ route('user.change.password') }}">@lang('Change Password')</a></li>
<li><a href="{{ route('user.transaction.history') }}">@lang('Transactions')</a></li>
<li><a class="{{ menuActive(['ticket.*']) }}" href="{{ route('ticket') }}">@lang('Support Tickets')</a></li>
@endpush




