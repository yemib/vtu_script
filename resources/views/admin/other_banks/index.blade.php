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
                                <th>@lang('Bank')</th>
                                <th class="text-left">@lang('Transfer Limit')</th>
                                <th class="text-left">@lang('Transfer Charge')</th>
                                <th>@lang('Status')</th>
                                <th>@lang('Action')</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($banks as $index => $data)
                                <tr>
                                    <td data-label="@lang('Bank')">
                                        <div class="text--primary font-weight-bold">
                                            {{ __($data->name) }}
                                        </div>
                                        @lang('Processing Time') : {{ $data->processing_time }}
                                    </td>
                                    <td data-label="@lang('Transfer Limit')" class="text-left">
                                        <div>
                                            @lang('Min') :
                                            <strong class="text--primary">
                                                {{ $general->cur_sym.showAmount($data->minimum_limit) }}
                                            </strong>
                                        </div>
                                        <div>
                                            @lang('Max') :
                                            <strong class="text--primary">
                                                {{ $general->cur_sym.showAmount($data->maximum_limit) }}
                                            </strong>
                                        </div>

                                    </td>

                                    <td data-label="@lang('Transfer Charge')" class="text-left">
                                        <div>
                                            @lang('Fixed') :
                                            <strong class="text--primary">
                                                {{ $general->cur_sym.showAmount($data->fixed_charge) }}
                                            </strong>
                                        </div>
                                        <div>
                                            @lang('Percent') :
                                            <strong class="text--primary">
                                                {{ getAmount($data->percent_charge) }}%
                                            </strong>
                                        </div>
                                    </td>


                                    <td data-label="@lang('Status')">
                                        @if($data->status == 1)
                                            <span class="text--small badge font-weight-normal badge--success">@lang('Enabled')</span>
                                        @else
                                            <span class="text--small badge font-weight-normal badge--danger">@lang('Disabled')</span>
                                        @endif
                                    </td>
                                    <td data-label="@lang('Action')">

                                        <a href="{{ route('admin.bank.edit', $data->id) }}"
                                           class="icon-btn ml-1 editBtn" data-toggle="tooltip" title=""
                                           data-original-title="@lang('Edit')">
                                            <i class="la la-pencil"></i>
                                        </a>

                                        <a href="javascript:void(0)"
                                           data-id="{{ $data->id }}"
                                           data-status="{{ $data->status }}"
                                           class="icon-btn statusBtn {{ $data->status == 1 ? 'bg--danger' : 'bg--success' }} ml-1" data-toggle="tooltip" title="@if($data->status == 1) @lang('Disable') @else @lang('Enable') @endif">
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
                @if($banks->hasPages())
                <div class="card-footer py-4">
                    {{ paginateLinks($banks) }}
                </div>
                @endif
            </div><!-- card end -->
        </div>
    </div>


{{-- STATUS METHOD MODAL --}}
<div id="statusModal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">@lang('Confirmation Alert')</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('admin.bank.status') }}" method="POST">
                @csrf
                <input type="hidden" name="id">
                <div class="modal-body">
                    <p>@lang('Are you sure to') <span class="font-weight-bold method-name"></span> @lang(' this item')?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn--dark" data-dismiss="modal">@lang('No')</button>
                    <button type="submit" class="btn btn--primary">Yes</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('breadcrumb-plugins')

    <div class="d-flex justify-content-end align-items-center">
        <form action="" method="GET" class="form-inline float-sm-right bg--white mr-2">
            <div class="input-group has_append">
                <input type="text" name="search" class="form-control" placeholder="@lang('Bank Name')" value="{{ request()->search ?? '' }}">
                <div class="input-group-append">
                    <button class="btn btn--primary" type="submit"><i class="fa fa-search"></i></button>
                </div>
            </div>
        </form>

        <a href="{{ route('admin.bank.create') }}" class="btn btn--primary box--shadow1 text-white text--small"><i class="fa fa-fw fa-plus"></i>@lang('Add New')</a>

    </div>
@endpush

@push('script')
<script>
    (function ($) {

        "use strict";

        $('.statusBtn').on('click', (e)=> {
            var btn     = $(e.currentTarget);
            var modal   = $('#statusModal');
            var status  = btn.data('status');
            var message = status == 1 ? '@lang("Are you sure to disable this bank?")':'@lang("Are you sure to enable this bank?")';
            modal.find('.modal-body').text(message);
            modal.find('input[name=id]').val(btn.data('id'));
            modal.modal('show');
        });

    })(jQuery);
</script>
@endpush
