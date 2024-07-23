@extends($activeTemplate.'layouts.master')
@section('content')
<div class="container">
    <div class="row justify-content-center mb-none-30">
        <div class="col-lg-12">
            <div class="custom--card">
                <div class="card-body p-0">
                    <div class="table-responsive--md">
                        <table class="table custom--table">
                            <thead>
                                <tr>
                                    <th>Plan</th>
                                    <th>@lang('Amount')</th>
                                    <th>@lang('Profit')</th>
                                    <th>@lang('Next Profit')</th>
                                    <th>@lang('Lock In Period')</th>
                                    <th>@lang('Status')</th>
                                    <th>@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody>

                                @forelse($fdrs as $fdr)
                                <tr>
                                    <td data-label="@lang('FDR No. | Plan')">
                                        <strong>{{ __($fdr->trx) }}</strong>
                                        <small class="d-block text--base">{{ __($fdr->plan->name) }}</small>
                                    </td>

                                    <td data-label="@lang('Amount')">
                                        <strong>{{ $general->cur_sym.showAmount($fdr->amount) }}</strong>
                                        <small class="d-block text--base">@lang('With') {{ getAmount($fdr->plan->interest_rate) }}% @lang('Profit Rate')</small>
                                    </td>

                                    <td data-label="@lang('Profit')">
                                        {{ $general->cur_sym.showAmount($fdr->interest) }}
                                        <small class="text--base d-block">@lang('Per') {{ $fdr->interest_interval }} @lang('Days')</small>
                                    </td>

                                    <td data-label="@lang('Next Profit')">
                                        {{ showDateTime($fdr->next_return_date, 'd M, Y') }}
                                        <small class="d-block text--base">{{ diffForHumans($fdr->next_return_date, 'd M, Y') }}</small>
                                    </td>

                                    <td data-label="@lang('Lock In Period')">
                                        {{ showDateTime($fdr->locked_date, 'd M, Y') }}
                                        <small class="d-block text--base">{{ diffForHumans($fdr->locked_date, 'd M, Y') }}</small>

                                    </td>

                                    <td data-label="@lang('Status')">
                                        @if($fdr->status == 1)
                                            <span class="badge badge--success">@lang('Running')</span>
                                        @elseif($fdr->status == 2)
                                            <span class="badge badge--danger">@lang('Completed')</span>
                                        @endif
                                    </td>

                                    <td data-label="@lang('Action')">
                                        @if($fdr->locked_date <= Carbon\Carbon::now() && $fdr->status == 1)
                                            <button type="button" data-bs-toggle="tooltip" title="@lang('Close')" data-token="{{ encrypt($fdr->id) }}" class="btn btn-primary btn-sm closeBtn">
                                                <i class="la la-wallet"></i>
                                            </button>
                                        @else
                                            <button type="button" data-bs-toggle="tooltip" title="@lang('Close')" class="btn btn-primary btn-sm disabled">
                                                <i class="la la-wallet"></i>
                                            </button>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="100%" class="text-center">{{ __($emptyMessage) }}</td>
                                </tr>
                                @endforelse

                            </tbody>
                        </table>
                        {{ $fdrs->links() }}
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- dashboard section end -->
    @push('modal')
    <div class="modal fade" id="closeFdr" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <strong class="modal-title">@lang('Close FDR')</strong>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </a>
                </div>
                <form action="{{ route('user.fdr.close') }}" method="post">
                    @csrf
                    <input type="hidden" name="user_token" required>
                    <div class="modal-body">
                        <div class="form-group">
                            <input type="hidden" name="id" class="transferId" required>
                        </div>
                        <div class="content">
                          <p>@lang('Are you sure to close this FDR?')</p>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-md btn-danger text-white" data-bs-dismiss="modal">@lang('No')</button>
                        <button type="submit" class="btn btn-md custom--bg text-white">@lang('Yes')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @endpush

@endsection

@push('script')
    <script>
        (function($){
            "use strict";
            $('.closeBtn').on('click', function() {
                var modal = $('#closeFdr');
                modal.find('input[name=user_token]').val($(this).data('token'));
                modal.modal('show');
            });
        })(jQuery);
    </script>
@endpush


@push('bottom-menu')
    <li>
        <a href="{{ route('user.fdr.plans') }}">@lang('Investment Plans')</a>
    </li>
    <li>
        <a href="{{ route('user.fdr.list') }}" class="active">@lang('My Investment List')</a>
    </li>
@endpush
