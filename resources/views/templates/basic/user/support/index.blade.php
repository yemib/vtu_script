@extends($activeTemplate.'layouts.master')
@section('content')
    <div class="container">
        <div class="row align-items-center mb-3">
            <div class="col-lg-6">
                <h6>@lang('Support Ticket History')</h6>
            </div>
            <div class="col-lg-6 text-lg-end">
                <a href="{{ route('ticket.open') }}" class="btn btn-sm btn--base"><i class="las la-plus-circle"></i> @lang('Open New Ticket')</a>
            </div>
        </div>
        <div class="row justify-content-center mb-none-30">
            <div class="col-lg-12">
                <div class="custom--card">
                    <div class="table-responsive--md">
                        <table class="table custom--table mb-0">
                            <thead>
                                <tr>
                                    <th>@lang('Subject')</th>
                                    <th>@lang('Status')</th>
                                    <th>@lang('Priority')</th>
                                    <th>@lang('Last Reply')</th>
                                    <th>@lang('Action')</th>
                                </tr>
                            </thead>

                            <tbody>
                                @forelse($supports as $key => $support)
                                    <tr>
                                        <td data-label="@lang('Subject')">
                                            <a href="{{ route('ticket.view', $support->ticket) }}"
                                                class="text--base">
                                                [@lang('Ticket')#{{ $support->ticket }}]
                                                {{ __($support->subject) }}
                                            </a>
                                        </td>
                                        <td data-label="@lang('Status')">
                                            @if($support->status == 0)
                                                <span class="badge badge--success">@lang('Open')</span>
                                            @elseif($support->status == 1)
                                                <span class="badge badge--primary">@lang('Answered')</span>
                                            @elseif($support->status == 2)
                                                <span class="badge badge--warning">@lang('Customer Reply')</span>
                                            @elseif($support->status == 3)
                                                <span class="badge badge--danger">@lang('Closed')</span>
                                            @endif
                                        </td>
                                        <td data-label="@lang('Priority')">
                                            @if($support->priority == 1)
                                                <span class="badge badge--dark">@lang('Low')</span>
                                            @elseif($support->priority == 2)
                                                <span class="badge badge--success">@lang('Medium')</span>
                                            @elseif($support->priority == 3)
                                                <span class="badge badge--primary">@lang('High')</span>
                                            @endif
                                        </td>
                                        <td data-label="@lang('Last Reply')">
                                            {{ \Carbon\Carbon::parse($support->last_reply)->diffForHumans() }}
                                        </td>

                                        <td data-label="@lang('Action')">
                                            <a href="{{ route('ticket.view', $support->ticket) }}" data-bs-toggle="tooltip" title="@lang('View Ticket')"
                                                class="btn btn-primary btn-sm">
                                                <i class="fa fa-desktop"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="100%">@lang('Data Not Found')</td>
                                    </tr>
                                @endforelse
                            </tbody>

                        </table>
                        @if($supports->hasPages())
                        <div class="mt-3">
                            {{ $supports->links() }}
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection



@push('bottom-menu')
    <li><a href="{{ route('user.profile.setting') }}">@lang('Profile')</a></li>
    @if($general->modules->referral_system)
        <li><a href="{{ route('user.referral.users') }}">@lang('Referral')</a></li>
    @endif
    <li><a href="{{ route('user.twofactor') }}">@lang('2FA Security')</a></li>
    <li><a href="{{ route('user.change.password') }}">@lang('Change Password')</a>
    </li>
    <li><a href="{{ route('user.transaction.history') }}">@lang('Transactions')</a></li>
    <li><a class="active" href="{{ route('ticket') }}">@lang('Support Tickets')</a></li>
@endpush
