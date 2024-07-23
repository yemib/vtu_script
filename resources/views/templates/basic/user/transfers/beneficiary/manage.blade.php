@extends($activeTemplate.'layouts.master')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card border border--base p-lg-4 border--base beneficiary-card">
                <div class="card-body">
                    <ul class="nav nav-tabs mb-3">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" data-name="own" data-bs-toggle="pill" data-bs-target="#own-bank" type="button" role="tab">@lang($general->sitename)</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" data-name="other" data-bs-toggle="pill" data-bs-target="#other-bank" type="button" role="tab">@lang('Other Banks')</button>
                        </li>
                    </ul>
                    <div class="tab-content">

                        {{-- Own Bank Body --}}
                        <div class="tab-pane fade show active" id="own-bank" role="tabpanel">

                            <div class="my-3 d-flex justify-content-end">
                              <button type="button" class="btn btn-sm btn--base add-btn"> <i class="fas fa-plus-circle"></i> @lang('Add Beneficiary')</button>
                            </div>

                            <div class="card border mb-3 d-none" id="addForm">
                                <div class="card-body p-4">
                                    <form action="{{ route('user.transfer.beneficiary.own.add') }}" method="POST">
                                        @csrf
                                        <div class="d-flex justify-content-between align-items-center">
                                            <h4>@lang('Add Beneficiary to') @lang($general->sitename)</h4>
                                            <button type="button" class="btn btn-sm btn--danger close-form-btn"><i class="fas fa-times-circle"></i></button>
                                        </div>
                                        <div class="form-group">
                                            <label>@lang('Account Number') <span class="text--danger">*</span></label>
                                            <input type="text" name="account_number" class="form--control">
                                            <small class="text--danger error-message"></small>
                                        </div>
                                        <div class="form-group">
                                          <label>@lang('Account Name') <span class="text--danger">*</span></label>
                                          <input type="text" name="account_name" class="form--control">
                                        </div>
                                        <div class="form-group">
                                            <label>@lang('Short Name') <span class="text--danger">*</span></label>
                                            <input type="text" name="short_name" class="form--control">
                                        </div>
                                        <button type="submit" class="btn w-100 btn--base">@lang('Submit')</button>
                                    </form>
                                </div>
                            </div>

                            <div class="custom--card">
                                <div class="table-responsive--md">
                                    <table class="table custom--table">
                                        <thead>
                                            <tr>
                                                <th>@lang('Account Number')</th>
                                                <th>@lang('Account Name')</th>
                                                <th>@lang('Short Name')</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($ownBeneficiaries as $beneficiary)
                                                <tr>
                                                    <td data-label="@lang('Account Number')">
                                                        {{ $beneficiary->account_number }}
                                                    </td>
                                                    <td data-label="@lang('Account Number')">
                                                        {{ $beneficiary->account_name }}
                                                    </td>
                                                    <td data-label="@lang('Short Name')">
                                                        {{ $beneficiary->short_name }}
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr><td colspan="100%" class="text-center">@lang($emptyMessage)</td></tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                        </div>

                        {{-- Other Bank Body --}}
                        <div class="tab-pane fade" id="other-bank" role="tabpanel">

                              <div class="my-3 d-flex justify-content-end">
                                <button type="button" class="btn btn-sm btn--base add-other-btn"> <i class="fas fa-plus-circle"></i> @lang('Add Beneficiary')</button>
                              </div>


                            <div class="card border mb-3 d-none" id="addOtherForm">
                                <div class="card-body p-4">
                                    <form action="{{ route('user.transfer.beneficiary.other.add') }}" method="POST" enctype="multipart/form-data">
                                        @csrf

                                        <div class="d-flex justify-content-between align-items-center">
                                            <h4>@lang('Add Beneficiary to Other Banks')</h4>
                                            <button type="button" class="btn btn-sm btn--danger close-other-form"><i class="fas fa-times-circle"></i></button>
                                        </div>

                                        <div class="form-group">
                                            <label>@lang('Select Bank')</label>
                                            <select class="form--control" name="bank">
                                              <option disabled selected value="">@lang('Select One')</option>
                                              @foreach ($otherBanks as $bank)
                                                  <option data-fields='@json($bank->user_data)' value="{{ $bank->id }}">{{ $bank->name }}</option>
                                              @endforeach
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label>@lang('Account Name') <span class="text--danger">*</span></label>
                                            <input type="text" name="account_name" class="form--control">
                                        </div>

                                        <div class="form-group">
                                            <label>@lang('Account Number') <span class="text--danger">*</span></label>
                                            <input type="text" name="account_number" class="form--control">
                                        </div>

                                        <div class="form-group">
                                            <label>@lang('Short Name') <span class="text--danger">*</span></label>
                                            <input type="text" name="short_name" class="form--control">
                                        </div>


                                        <div id="user-fields">

                                        </div>

                                        <div class="form-group">
                                            <button type="submit" class="btn w-100 btn--base">@lang('Submit')</button>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            <div class="custom--card">
                                <div class="table-responsive--md">
                                    <table class="table custom--table">
                                        <thead>
                                            <tr>
                                                <th>@lang('Bank')</th>
                                                <th>@lang('Account No.')</th>
                                                <th>@lang('Account Name')</th>
                                                <th>@lang('Short Name')</th>
                                                <th>@lang('Details')</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($otherBeneficiaries as $beneficiary)
                                            <tr>
                                                <td data-label="@lang('Bank')">
                                                    {{ $beneficiary->bank->name }}
                                                </td>

                                                <td data-label="@lang('Account No.')">
                                                    {{ $beneficiary->account_number }}
                                                </td>
                                                <td data-label="@lang('Account Name.')">
                                                    {{ $beneficiary->account_name }}
                                                </td>

                                                <td data-label="@lang('Short Name')">
                                                    {{ $beneficiary->short_name }}
                                                </td>
                                                <td data-label="@lang('Details')">
                                                    <button class="btn btn-sm btn--base seeDetails"
                                                        data-bank_name="{{ $beneficiary->bank->name }}"
                                                        data-short_name="{{ $beneficiary->short_name }}"
                                                        data-details='@json($beneficiary->details)'
                                                    >
                                                        <i class="la la-desktop"></i>
                                                    </button>
                                                </td>

                                            </tr>
                                            @empty
                                                <tr><td colspan="100%" class="text-center">@lang($emptyMessage)</td></tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('modal')
    <!-- Modal -->
    <div class="modal fade" id="detailsModal">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Benficiary Details')</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <ul class="list-group list-group-flush">
                    </ul>
                </div>

            </div>
        </div>
    </div>
@endpush


@push('bottom-menu')
    @if($general->modules->own_bank || $general->modules->other_bank)
        <li><a href="{{ route('user.transfer.beneficiary.manage') }}" class="active">@lang('Beneficiary Management')</a></li>
        @if($general->modules->own_bank)
            <li><a href="{{ route('user.transfer.own') }}">@lang('Transfer Within') @lang($general->sitename)</a></li>
        @endif
        @if($general->modules->other_bank)
            <li><a href="{{ route('user.transfer.other') }}">@lang('Transfer To Other Bank')</a></li>
        @endif

        <li>
            <a href="{{ route('user.transfer.history') }}">@lang('Transfer History')</a>
        </li>
    @endif
@endpush


@push('script')
    <script>
        'use strict';
        (function($){
            const addForm       = $('#addForm');
            const addOtherForm  = $('#addOtherForm');

            $('.add-btn').on('click', function(){
                addForm.removeClass('d-none').hide().fadeIn(500);
            });

            $('.add-other-btn').on('click', function(){
                addOtherForm.removeClass('d-none').hide().fadeIn(500);
            });

            $('.close-form-btn').on('click', function(){
                addForm.fadeOut(500);
            });

            $('.close-other-form').on('click', function(){
                addOtherForm.fadeOut(500);
            });


            addForm.find('input[name=account_number]').on('focusout', function(){
                let $this = $(this);
                let route   = `{{ route('user.accountnumber.check', '') }}/${$this.val()}`
                $.get(route, function(response) {
                    if(response.error){
                        $this.parent('.form-group').find('.error-message').text(response.message);

                        addForm.find('input:not(input[name=account_number])').attr('disabled', 'disabled')
                        addForm.find('input[name=account_name]').val('')
                    }else{
                        addForm.find('input[name=account_name]').val(response.data.name).attr('readonly', 'readonly')
                        addForm.find('input').removeAttr('disabled')

                        $this.parent('.form-group').find('.error-message').text('');
                    }

                });
            });

            addOtherForm.find('select[name=bank]').on('change', function(){
                let fields = $(this).find('option:selected').data('fields');
                let output = ``;
                fields.forEach(element => {
                    if(element.type == 'text') {
                        output +=
                        `
                            <div class="form-group">
                                <label>@lang('${element.field_name}') <span class="text--danger">*</span></label>
                                <input type="text" name="${snakeCase(element.field_name)}" class="form--control" ${element.validation}>
                            </div>
                        `;
                    }else if(element.type == 'textarea'){
                        output +=
                        `
                            <div class="form-group">
                                <label>@lang('${element.field_name}') <span class="text--danger">*</span></label>
                                <textarea type="text" name="${snakeCase(element.field_name)}" class="form--control" ${element.validation}></textarea>
                            </div>
                        `;
                    }else if(element.type == 'file'){
                        output +=
                        `
                            <div class="form-group">
                                <label>@lang('${element.field_name}') <span class="text--danger">*</span></label>
                                <input type="file" name="${snakeCase(element.field_name)}" class="form--control" accept=".jpg,.jpeg,.png" ${element.validation}>
                            </div>
                        `
                    }
                });
                $('#user-fields').html(output).hide().fadeIn(500);
            });

            $('.nav-link').on('click', function(){
                let name = $(this).data('name');
                localStorage.setItem('tabType', name);
            });

            function selectTab(){
                let tab = localStorage.getItem('tabType');
                if(tab){
                    $('.nav-link').removeClass('active')
                    $('.tab-pane').removeClass('active')
                    if(tab == 'own'){
                        $('.nav-link[data-name=own]').addClass('active');
                        $('#own-bank').addClass('active show')
                    }else if(tab == 'other'){
                        $('.nav-link[data-name=other]').addClass('active')
                        $('#other-bank').addClass('active show')
                    }
                }
            }

            selectTab();

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

