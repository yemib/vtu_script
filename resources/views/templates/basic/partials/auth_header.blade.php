<header class="header">
    <div class="header__bottom">
        <div class="container">
        <nav class="navbar navbar-expand-lg p-0 align-items-center justify-content-between">
            <a class="site-logo site-title" href="{{ route('home') }}"><img src="{{getImage(imagePath()['logoIcon']['path'] .'/logo.png')}}" alt="logo"></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="menu-toggle"></span>
            </button>
            <div class="collapse navbar-collapse mt-xl-0 mt-3" id="navbarSupportedContent">
                <ul class="navbar-nav main-menu m-auto">
                    <li>
                        <a class="{{menuActive('user.home')}}" href="{{ route('user.home') }}">@lang('Dashboard')</a>
                    </li>

                    @if($general->modules->deposit)
                        <li>
                            <a class="{{menuActive('user.deposit*')}}" href="{{ route('user.deposit.history') }}">@lang('Deposit')</a>
                        </li>
                    @endif

                    @if($general->modules->withdraw)
                        <li>
                            <a class="{{menuActive('user.withdraw*')}}" href="{{ route('user.withdraw.history') }}">@lang('Withdraw')</a>
                        </li>
                    @endif

                    @if($general->modules->fdr)
                        <li>
                            <a class="{{ menuActive('user.fdr*')}}" href="{{ route('user.fdr.plans') }}">@lang('FDR')</a>
                        </li>
                    @endif

                    @if($general->modules->dps)
                        <li>
                            <a class="{{menuActive('user.dps*')}}" href="{{ route('user.dps.plans') }}">@lang('DPS')</a>
                        </li>
                    @endif

                    @if($general->modules->loan)
                        <li>
                            <a class="{{menuActive('user.loan*')}}" href="{{ route('user.loan.plans') }}">@lang('Loan')</a>
                        </li>
                    @endif

                    @if($general->modules->own_bank || $general->modules->other_bank)
                        <li>
                            <a class="{{menuActive('user.transfer*')}}" href="{{ route('user.transfer.beneficiary.manage') }}">@lang('Transfer')</a>
                        </li>
                    @endif

                    <li>
                        <a class="{{ menuActive(['user.profile.setting', 'user.twofactor', 'user.change.password', 'user.transaction.history', 'ticket', 'ticket.open', 'ticket.view', 'user.referral.*']) }}" href="{{ route('user.profile.setting') }}">@lang('More')</a>
                    </li>
                </ul>
                <div class="nav-right">
                    <select class="language-select me-3 langSel">
                        @foreach($language as $item)
                            <option value="{{$item->code}}" @if(session('lang') == $item->code) selected @endif>{{ __($item->name) }}</option>
                        @endforeach
                    </select>
                    <a href="{{ route('user.logout') }}" class="btn btn-sm py-2 custom--bg text-white"> <i class="fa fa-sign-out-alt" aria-hidden="true"></i> @lang('Logout')</a>
                </div>
            </div>
        </nav>
        </div>
    </div>
</header>


