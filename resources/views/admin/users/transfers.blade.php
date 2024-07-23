@extends('admin.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card b-radius--10 ">
                <div class="card-body p-0">
                    <div class="table-responsive--md  table-responsive">
                        <table class="table table--light style--two">
                            <thead>
                                <tr>
                                    <th>@lang('TRX')</th>
                                    <th>@lang('Account Name')</th>
                                    <th>@lang('Account Number')</th>
                                    <th>@lang('Bank')</th>
                                    <th>@lang('Amount')</th>
                                    <th>@lang('Status')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($transfers as $transfer)
                                <tr>
                                    <td data-label="@lang('TRX')">
                                        {{ $transfer->trx }}
                                    </td>

                                    <td data-label="@lang('Account Name')">
                                        {{ $transfer->beneficiary->account_name }}
                                    </td>

                                    <td data-label="@lang('Account Number')">
                                        {{ $transfer->beneficiary->account_number }}
                                    </td>

                                    <td data-label="@lang('Bank')">
                                        {{ $transfer->bank->name??$general->sitename }}
                                    </td>


                                    <td data-label="@lang('Amount')">
                                        {{ $general->cur_sym.showAmount($transfer->amount) }}
                                    </td>

                                    <td data-label="@lang('Status')">
                                        @if($transfer->status == 1)
                                        <span class="badge badge--success">
                                            @lang('Completed')
                                        </span>
                                        @elseif($transfer->status == 0)
                                        <span class="badge badge--warning">
                                            @lang('Pending')
                                        </span>
                                        @elseif($transfer->status == 2)
                                            <span class="badge badge--danger">
                                                @lang('Rejected')
                                            </span>
                                        @endif
                                    </td>


                                </tr>
                                @empty
                                    <tr>
                                        <td colspan="100%" class="text-center">@lang($emptyMessage)</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table><!-- table end -->
                    </div>
                </div>
                @if($transfers->hasPages())
                    <div class="card-footer py-4">
                        {{ paginateLinks($transfers) }}
                    </div>
                @endif
            </div>
        </div>


    </div>

    <div class="modal fade" id="detailsModal">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Benficiary Details')</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <ul class="list-group list-group-flush">
                    </ul>
                </div>

            </div>
        </div>
    </div>
@endsection



@push('breadcrumb-plugins')
    <form action="" method="GET" class="form-inline float-sm-right bg--white">
        <div class="input-group has_append">
            <input type="text" name="search" class="form-control" placeholder="@lang('TRX')" value="{{ request()->search ?? '' }}">
            <div class="input-group-append">
                <button class="btn btn--primary" type="submit"><i class="fa fa-search"></i></button>
            </div>
        </div>
    </form>
@endpush

@push('script')
    <script>
        'use strict';
        (function($){
            $('.seeDetails').on('click', function(){
                let modal       = $('#detailsModal');
                let details     = $(this).data('details');
                let imagePath   = "{{asset(imagePath()['transfer']['beneficiary_data']['path'])}}/";
                let html    = `
                    <li class="list-group-item d-flex flex-wrap justify-content-between">
                        <span>@lang('Bank Name')</span>
                        ${$(this).data('bank_name')}
                    </li>
                    <li class="list-group-item d-flex flex-wrap justify-content-between">
                        <span>@lang('Short Name')</span>
                        ${$(this).data('short_name')}
                    </li>
                `;


                $.each(details, function (i, value) {
                    if(value.type == 'file'){
                        html +=
                        `
                            <li class="list-group-item d-flex flex-wrap justify-content-between">
                                <span>@lang('${titleCase(i)}')</span>
                                <img class="w-75" src="${imagePath}${value.value}">
                            </li>
                        `;
                    }else {
                        html +=
                        `
                            <li class="list-group-item d-flex flex-wrap justify-content-between">
                                <span>@lang('${titleCase(i)}')</span>
                                ${value.value}
                            </li>
                        `
                    }
                });
                modal.find('.modal-body .list-group').html(html);
                modal.modal('show');
            });
        })(jQuery)
    </script>
@endpush
