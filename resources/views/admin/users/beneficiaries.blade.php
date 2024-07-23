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
                                    <th>@lang('Account Name')</th>
                                    <th>@lang('Short Name')</th>
                                    <th>@lang('Account No.')</th>
                                    <th>@lang('Bank')</th>
                                    <th>@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody>
                            @forelse($beneficiaries as $beneficiary)
                                <tr>

                                    <td data-label="@lang('Account Name.')">
                                        {{ $beneficiary->account_name }}
                                    </td>

                                    <td data-label="@lang('Short Name')">
                                        {{ $beneficiary->short_name }}
                                    </td>

                                    <td data-label="@lang('Account No.')">
                                        {{ $beneficiary->account_number }}
                                    </td>

                                    <td data-label="@lang('Bank')">
                                        {{ $beneficiary->bank->name??$general->sitename }}
                                    </td>

                                    <td data-label="@lang('Action')">
                                        <span data-toggle="tooltip" title="@lang('Details')">
                                            <button class="icon-btn btn--primary seeDetails"
                                                data-bank_name="{{ $beneficiary->bank->name??$general->sitename }}"
                                                data-short_name="{{ $beneficiary->short_name }}"
                                                data-details='@json($beneficiary->details)'

                                            >
                                                <i class="la la-desktop"></i>
                                            </button>
                                        </span>
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
                @if($beneficiaries->hasPages())
                    <div class="card-footer py-4">
                        {{ paginateLinks($beneficiaries) }}
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
            <input type="text" name="search" class="form-control" placeholder="@lang('Search')..." value="{{ request()->search ?? '' }}">
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
