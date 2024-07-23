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
                                <th>@lang('Users Profit')</th>
                                <th>@lang('Deposit Amount')</th>
                                <th>@lang('Status')</th>
                                <th>@lang('Action')</th>
                            </tr>
                            </thead>
                            <tbody>
                           @forelse($plans as $plan)
                               <tr>
                                    <td data-label="@lang('Plan')">
                                        <span class="font-weight-bold">{{ __($plan->name) }}</span>
                                    </td>

                                    <td data-label="@lang('Users Profit')">
                                        <span class="text--primary">
                                            {{ getAmount($plan->interest_rate) }}%
                                        </span>
                                        @lang('of total deposited amount for every')
                                        <br>
                                        <span class="text--primary">
                                            {{ $plan->interest_interval }}
                                        </span> @lang('Days')
                                    </td>

                                    <td data-label="@lang('Deposit Amount')">
                                        @lang('Min'): {{ showAmount($plan->minimum_amount) }}
                                        <br>
                                        @lang('Max'): {{ showAmount($plan->maximum_amount) }}
                                    </td>

                                    <td data-label="@lang('Status')">
                                        @if($plan->status == 1)
                                            <span class="text--small badge font-weight-normal badge--success">@lang('Enabled')</span>
                                        @else
                                            <span class="text--small badge font-weight-normal badge--danger">@lang('Disabled')</span>
                                        @endif
                                    </td>
                                    <td data-label="@lang('Action')">

                                        <button type="button" class="icon-btn ml-1 edit-btn" data-toggle="tooltip" title="@lang('Edit')" data-plan="{{ $plan }}">
                                            <i class="la la-pencil"></i>
                                        </button>

                                        <button
                                            data-id="{{ $plan->id }}"
                                            data-status="{{ $plan->status }}"
                                            class="icon-btn statusBtn {{ $plan->status == 1 ? 'bg--danger' : 'bg--success' }}"
                                            data-toggle="tooltip"
                                            title="@if($plan->status == 1) @lang('Disable') @else @lang('Active') @endif">
                                           <i class="la la-{{ $plan->status == 1 ? 'la la-eye-slash' : 'la la-eye' }}"></i>
                                       </button>

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
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">@lang('Confirmation Alert')!</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('admin.fdr.plan.status') }}" method="POST">
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

{{-- Add Modal --}}

<!-- Modal -->
<div class="modal fade" id="addModal">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <form action="" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body row">

                    <div class="form-group col-lg-6">
                        <label class="font-weight-bold" for="plan-name">@lang('Name')</label>
                        <input type="text" name="name" id="plan-name" class="form-control" placeholder="@lang('Enter Plan Name')">
                    </div>

                    <div class="form-group col-lg-6">
                        <label class="font-weight-bold" for="interest-rate">@lang('Interest Rate on Total Deposit')</label>
                        <div class="input-group">
                            <input type="text" name="interest_rate" id="interest-rate" class="form-control numeric-validation" placeholder="@lang('Enter Interest Rate')">
                            <div class="input-group-append">
                                <span class="input-group-text">@lang('%')</span>
                            </div>
                        </div>
                    </div>

                    <div class="form-group col-lg-6">
                        <label class="font-weight-bold" for="interest-interval">@lang('Interest Interval')</label>
                        <div class="input-group">
                            <input type="text" name="interest_interval" id="interest-interval" class="form-control integer-validation" placeholder="@lang('Enter Interest Interval')">
                            <div class="input-group-append">
                                <span class="input-group-text">@lang('Days')</span>
                            </div>
                        </div>
                    </div>

                    <div class="form-group col-lg-6">
                        <label class="font-weight-bold" for="locked-days">@lang('Locked Days')</label>
                        <div class="input-group">
                            <input type="text" name="locked_days" id="locked-days" class="form-control integer-validation" placeholder="@lang('Enter Locked Days')">
                            <div class="input-group-append">
                                <span class="input-group-text">@lang('Days')</span>
                            </div>
                        </div>
                    </div>

                    <div class="form-group col-lg-6">
                        <label class="font-weight-bold" for="minimum-amount">@lang('Minimum Amount') <span class="text-danger">*</span></label>
                        <div class="input-group has_append mb-3">
                            <input type="text" class="form-control numeric-validation" id="minimum-amount" name="minimum_amount" placeholder="Enter Minimum Amount"  required />
                            <div class="input-group-append">
                                <div class="input-group-text"> {{ __($general->cur_text) }} </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group col-lg-6">
                        <label class="font-weight-bold" for="maximum-amount">@lang('Maximum Amount') <span class="text-danger">*</span></label>
                        <div class="input-group has_append mb-3">
                            <input type="text" class="form-control numeric-validation" id="maximum-amount" name="maximum_amount" placeholder="Enter Maximum Amount" required />
                            <div class="input-group-append">
                                <div class="input-group-text"> {{ __($general->cur_text) }} </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group final-amount col-lg-12 text-center d-none">
                        <h3 class="text--success">
                            @lang('User will get a minimum of')
                            <span class="text--success" id="minAmount"></span> @lang($general->cur_text)
                            @lang('to a maximum of')
                            <span class="text--success" id="maxAmount"></span> @lang($general->cur_text)
                            @lang('per')  <span class="text--success" id="perInterval"></span> @lang('days')
                        </h3>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn--primary btn-block"></button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('breadcrumb-plugins')
    <button class="btn btn--primary box--shadow1 text-white text--small add-btn"><i class="la la-plus"></i>@lang('Add New')</button>
@endpush

@push('script')
<script>
    (function ($) {
        "use strict";
        const addModal      = $('#addModal');
        const statusModal   = $('#statusModal');

        $('.statusBtn').on('click', (e)=> {
            var btn     = $(e.currentTarget);
            var status  = btn.data('status');
            var message = status == 1 ? '@lang("Are you sure to disable this plan?")':'@lang("Are you sure to enable this plan?")';
            statusModal.find('.modal-body').text(message);
            statusModal.find('input[name=id]').val(btn.data('id'));
            statusModal.modal('show');
        });

        addModal.on('hidden.bs.modal', function () {
            addModal.find('.modal-body input').val('');
            addModal.find('.final-amount').addClass( 'd-none' );
        })

        $('.add-btn').on('click', () => {
            addModal.find(':submit').text(`@lang('Add')`);
            addModal.find('.modal-title').text(`@lang('Add Plan')`);
            addModal.find('form').attr('action', `{{ route('admin.fdr.plan.save', '')}}`);
            addModal.modal('show');
        });

        $('.edit-btn').on('click', (e) => {
            let plan = $(e.currentTarget).data('plan');
            addModal.find(':submit').text(`@lang('Save Changes')`);
            addModal.find('.modal-title').text(`@lang('Edit Plan'): @lang('${plan.name}')`);
            addModal.find('form').attr('action', `{{ route('admin.fdr.plan.update', '')}}/${plan.id}`);
            $('#plan-name').val(plan.name);
            $('#interest-interval').val(plan.interest_interval);
            $('#minimum-amount').val(parseFloat(plan.minimum_amount));
            $('#maximum-amount').val(parseFloat(plan.maximum_amount));
            $('#interest-rate').val(parseFloat(plan.interest_rate));
            $('#locked-days').val(parseFloat(plan.locked_days));
            calculateProfit(addModal);
            addModal.modal('show');
        });

        $('#interest-rate, #minimum-amount, #maximum-amount').on( 'input', () => calculateProfit() );

        function calculateProfit(){
            let minAmount       = Number($('#minimum-amount').val());
            let maxAmount       = Number($('#maximum-amount').val());
            let interest        = Number($('#interest-rate').val())/100;
            let interval        = $('#interest-interval').val();

            let totalMinAmount  = minAmount * interest;
            let totalMaxAmount  = maxAmount * interest;

            if(minAmount && maxAmount && interest){
                addModal.find('#minAmount').text(showAmount(totalMinAmount) );
                addModal.find('#maxAmount').text(showAmount(totalMaxAmount) );
                addModal.find('#perInterval').text(interval);
                addModal.find('.final-amount').removeClass( 'd-none' );
            }
        }
    })(jQuery);
</script>
@endpush
