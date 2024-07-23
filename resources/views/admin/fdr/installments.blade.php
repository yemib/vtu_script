@extends('admin.layouts.app')

@section('panel')
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body p-0">
                <div class="table-responsive--sm table-responsive">
                    <table class="table table--light style--two">
                        <thead>
                            <tr>
                                <th>@lang('S.N')</th>
                                <th>@lang('Date')</th>
                                <th>@lang('Amount')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($installments as $installment)
                            <tr>
                                <td data-label="@lang('DPS No. | Plan')">
                                    {{ $loop->iteration }}
                                </td>
                                <td data-label="@lang('DPS No. | Plan')">
                                    {{ showDateTime($installment->created_at, 'd M, Y, h:i A') }}
                                </td>
                                <td data-label="@lang('User')">
                                    {{ showAmount($installment->amount) }} {{ __($general->cur_text) }}
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td class="text-muted text-center" colspan="100%">{{ __($emptyMessage) }}</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table><!-- table end -->
                </div>
            </div>
        </div><!-- card end -->
    </div>
</div>
@endsection



