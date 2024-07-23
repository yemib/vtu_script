@extends('admin.layouts.app')

@section('panel')
    <div class="row">

        <div class="col-lg-12">
            <div class="card">
                <div class="card-body p-0">
                    <div class="table-responsive--md table-responsive">
                        <table class="table table--light style--two">
                            <thead>
                            <tr>
                                <th>@lang('Plan')</th>
                                <th>@lang('Installment')</th>
                                <th>@lang('After Matured')</th>
                                <th>@lang('Status')</th>
                                <th>@lang('Action')</th>
                            </tr>
                            </thead>
                            <tbody>
                           @forelse($plans as $plan)
                               <tr>
                                    <td data-label="@lang('Plan')">
                                        <span class="font-weight-bold">{{ __($plan->name) }}</span>

                                        @php
                                            $profit = ($plan->total_installment * $plan->per_installment) * $plan->interest_rate / 100;
                                        @endphp

                                            <span class="d-block text--info">
                                                {{ getAmount($plan->interest_rate) }}%

                                            @lang('of') {{ $general->cur_sym.showAmount($profit) }}</span>

                                    </td>

                                   <td data-label="@lang('Installment')">
                                        <span class="text--primary">
                                            {{ $general->cur_sym.showAmount($plan->per_installment) }} /

                                            {{ $plan->installment_interval }}
                                        </span> @lang('Days')
                                        <br>
                                        @lang('for') <span class="text--primary">{{ $plan->total_installment }}</span> @lang('Times')
                                    </td>

                                    <td data-label="@lang('After Matured')">
                                        @php
                                            $profit = ($plan->total_installment*$plan->per_installment) -100;
                                        @endphp

                                        <span class="font-weight-bold text--danger">
                                        {{ $general->cur_sym.showAmount($plan->final_amount) }} </span>  <br>
                                        @lang('after') <span class="text--primary">{{ $plan->total_installment*$plan->installment_interval }}</span> @lang('days')
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
            <form action="{{ route('admin.dps.plan.status') }}" method="POST">
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

                    <div class="form-group col-lg-12">
                        <label class="font-weight-bold" for="plan-name">@lang('Name')</label>
                        <input type="text" name="name" id="plan-name" class="form-control" placeholder="@lang('Enter Plan Name')">
                    </div>

                    <div class="form-group col-lg-6">
                        <label class="font-weight-bold" for="installment-interval">@lang('Installment Interval')</label>
                        <div class="input-group">
                            <input type="text" name="installment_interval" id="installment-interval" class="form-control integer-validation" placeholder="@lang('Enter Installment Interval')">
                            <div class="input-group-append">
                                <span class="input-group-text">@lang('Days')</span>
                            </div>
                        </div>
                    </div>

                    <div class="form-group col-lg-6">
                        <label class="font-weight-bold" for="total-installment">@lang('Total Installment')</label>
                        <input type="text" name="total_installment" id="total-installment" class="form-control integer-validation" placeholder="@lang('Enter Total Installment')">
                    </div>

                    <div class="form-group col-lg-6">
                        <label class="font-weight-bold" for="per-installment">@lang('Per Installment')</label>
                        <div class="input-group">
                            <input type="text" name="per_installment" id="per-installment" class="form-control numeric-validation" placeholder="@lang('Enter an Amount')">
                            <div class="input-group-append">
                                <span class="input-group-text">@lang($general->cur_text)</span>
                            </div>
                        </div>
                    </div>

                    <div class="form-group col-lg-6">
                        <label class="font-weight-bold" for="interest-rate">@lang('Interest Rate of Total Deposit')</label>
                        <div class="input-group">
                            <input type="text" name="interest_rate" id="interest-rate" class="form-control numeric-validation" placeholder="@lang('Enter Profit Rate')">
                            <div class="input-group-append">
                                <span class="input-group-text">@lang('%')</span>
                            </div>
                        </div>
                    </div>

                    <div class="form-group col-lg-6">
                        <label>@lang('Total Deposit')</label>
                        <div class="input-group">
                            <input type="text" class="form-control total_deposit" readonly>
                            <div class="input-group-append">
                                <span class="input-group-text">@lang($general->cur_text)</span>
                            </div>
                        </div>
                    </div>

                    <div class="form-group col-lg-6">
                        <label>@lang('User\'s Profit')</label>
                        <div class="input-group">
                            <input type="text" class="form-control profit-amount" readonly>
                            <div class="input-group-append">
                                <span class="input-group-text">@lang($general->cur_text)</span>
                            </div>
                        </div>
                    </div>
                    <h3 class="col-lg-12 d-none mature-amount text--success text-center">
                        @lang('After mature, the user will get') : <span class="text--success"></span> @lang($general->cur_text)
                    </h3>
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
            addModal.find('.mature-amount').addClass( 'd-none' );
        })

        $('.add-btn').on('click', () => {
            addModal.find(':submit').text(`@lang('Add')`);
            addModal.find('.modal-title').text(`@lang('Add Plan')`);
            addModal.find('form').attr('action', `{{ route('admin.dps.plan.save', '')}}`);
            addModal.modal('show');
        });

        $('.edit-btn').on('click', (e) => {
            let plan = $(e.currentTarget).data('plan');
            addModal.find(':submit').text(`@lang('Save Changes')`);
            addModal.find('.modal-title').text(`@lang('Edit Plan'): @lang('${plan.name}')`);
            addModal.find('form').attr('action', `{{ route('admin.dps.plan.update', '')}}/${plan.id}`);
            addModal.find('input[name=name]').val(plan.name);

            addModal.find('input[name=installment_interval]').val(plan.installment_interval);
            addModal.find('input[name=total_installment]').val(plan.total_installment);
            addModal.find('input[name=per_installment]').val(parseFloat(plan.per_installment));
            addModal.find('input[name=interest_rate]').val(parseFloat(plan.interest_rate));
            calculateProfit(addModal);
            addModal.modal('show');
        });

        $('#per-installment, #total-installment, #interest-rate').on( 'input', () => calculateProfit(addModal) );

        function calculateProfit(modal){
            let perInstallment      = Number(modal.find('input[name=per_installment]').val());
            let totalInstallment    = Number(modal.find('input[name=total_installment]').val());
            let interestRate        = Number(modal.find('input[name=interest_rate]').val());
            let totalAmount         = perInstallment * totalInstallment;
            let interest            = totalAmount * interestRate / 100;

            if(perInstallment && totalInstallment && interestRate){
                modal.find('.total_deposit').val( showAmount(totalAmount) );
                modal.find('.profit-amount').val( showAmount(interest) );
                modal.find('.mature-amount').removeClass( 'd-none' );
                modal.find('.mature-amount span').text( showAmount(totalAmount + interest) );
            }
        }
    })(jQuery);
</script>
@endpush
