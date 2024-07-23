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
                                    <th>@lang('Plan')</th>
                                    <th>@lang('Limit')</th>
                                    <th>@lang('Installment')</th>
                                    <th>@lang('Status')</th>
                                    <th>@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody>
                           @forelse($plans as $index => $data)
                               <tr>
                                    <td data-label="@lang('Plan')">
                                        <div class="font-weight-bold">{{ __($data->name) }}</div>
                                        @php
                                            $profit = ($data->total_installment*$data->per_installment) -100;
                                        @endphp

                                    <span class="font-weight-bold @if($profit > 0)text--success @else text--danger @endif">
                                    {{ getAmount($profit) }}% </span>
                                    @lang('in') <span class="text--primary">{{ $data->total_installment*$data->installment_interval }}</span> @lang('days')
                                    </td>

                                    <td data-label="@lang('Limit')">

                                            @lang('Min'): {{ $general->cur_sym.showAmount($data->minimum_amount) }}
                                            <div>
                                                @lang('Max'): {{ $general->cur_sym.showAmount($data->maximum_amount) }}
                                            </div>
                                    </td>

                                   <td data-label="@lang('Installment')">
                                        <span class="text--primary">
                                            {{ $data->per_installment+0 }}%
                                        </span>
                                        @lang('every')
                                        <span class="text--primary">
                                            {{ $data->installment_interval }}
                                        </span> @lang('Days')
                                    <br>@lang('for') <span class="text--primary">{{ $data->total_installment }}</span> @lang('Times')
                                    </td>


                                   <td data-label="@lang('Status')">
                                       @if($data->status == 1)
                                           <span class="text--small badge font-weight-normal badge--success">@lang('Enabled')</span>
                                       @else
                                           <span class="text--small badge font-weight-normal badge--danger">@lang('Disabled')</span>
                                       @endif
                                   </td>

                                   <td data-label="@lang('Action')">

                                       <a href="{{ route('admin.loan.plan.edit', $data->id) }}"
                                          class="icon-btn ml-1 editBtn" data-toggle="tooltip" title=""
                                          data-original-title="@lang('Edit')">
                                           <i class="la la-pencil"></i>
                                       </a>

                                       <a href="javascript:void(0)"
                                          data-id="{{ $data->id }}"
                                          data-status="{{ $data->status }}"
                                          class="icon-btn statusBtn {{ $data->status == 1 ? 'bg--danger' : 'bg--success' }} ml-1" data-toggle="tooltip" title=""
                                          data-original-title="@if($data->status == 1) @lang('Disable') @else @lang('Active') @endif">
                                           <i class="la la-{{ $data->status == 1 ? 'la la-eye-slash' : 'la la-eye' }}"></i>
                                       </a>

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
                @if($plans->hasPages())
                    <div class="card-footer py-4">
                    {{ paginateLinks($plans) }}
                    </div>
                @endif
            </div><!-- card end -->
        </div>
    </div>


{{-- STATUS METHOD MODAL --}}
<div id="statusModal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">@lang('Confirmation')!</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('admin.loan.plan.status') }}" method="POST">
                @csrf
                <input type="hidden" name="id">
                <div class="modal-body">

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn--dark" data-dismiss="modal">@lang('No')</button>
                    <button type="submit" class="btn btn--primary">@lang('Yes')</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('breadcrumb-plugins')
    <a href="{{ route('admin.loan.plan.create') }}" class="btn btn-sm btn--primary box--shadow1 text-white text--small addBtn"><i class="la la-plus"></i>@lang('Add New')</a>
@endpush

@push('script')
<script>
    (function ($) {

        "use strict";

        $('.statusBtn').on('click', (e)=> {
            var btn     = $(e.currentTarget);
            var modal   = $('#statusModal');
            var status  = btn.data('status');
            var message = status == 1 ? '@lang("Are you sure to disable this plan?")':'@lang("Are you sure to enable this plan?")';
            modal.find('.modal-body').text(message);
            modal.find('input[name=id]').val(btn.data('id'));
            modal.modal('show');
        });

    })(jQuery);
</script>
@endpush
