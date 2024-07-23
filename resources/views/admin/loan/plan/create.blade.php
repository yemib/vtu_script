@extends('admin.layouts.app')

@section('panel')
    <div class="card">
        <form action="{{ route('admin.loan.plan.save') }}" method="POST">
            @csrf
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="font-weight-bold" for="name">
                                @lang('Plan Name') <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <input type="text" name="name" id="name" class="form-control" placeholder="Enter Plan's Name" value="{{ old('name') }}" required />
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="font-weight-bold" for="minimum_amount">@lang('Minimum Amount') <span class="text-danger">*</span></label>
                            <div class="input-group has_append mb-3">
                                <input type="text" class="form-control numeric-validation" id="minimum_amount" name="minimum_amount" placeholder="Enter Minimum Amount" value="{{ old('minimum_amount') }}" required />
                                <div class="input-group-append">
                                    <div class="input-group-text"> {{ __($general->cur_text) }} </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="font-weight-bold" for="maximum_amount">@lang('Maximum Amount') <span class="text-danger">*</span></label>
                            <div class="input-group has_append mb-3">
                                <input type="text" class="form-control numeric-validation" id="maximum_amount" name="maximum_amount" placeholder="Enter Maximum Amount" value="{{ old('maximum_amount') }}" required />
                                <div class="input-group-append">
                                    <div class="input-group-text"> {{ __($general->cur_text) }} </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="font-weight-bold" for="per_installment">
                                @lang('Per Installment')
                                <span class="text-danger">*</span>
                            </label>
                            <div class="form-group">
                                <div class="input-group">
                                    <input type="text" name="per_installment" id="per_installment" class="form-control numeric-validation" placeholder="Enter Per Intstallment's Value" value="{{ old('per_installment') }}" required />
                                    <div class="input-group-append">
                                        <span class="input-group-text">%</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="font-weight-bold" for="installment_interval">
                                @lang('Installment Interval')
                                <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">

                                <input type="text" name="installment_interval" id="installment_interval" class="form-control integer-validation" placeholder="Enter Intallment Interval" value="{{ old('installment_interval') }}" required />
                                <div class="input-group-append">
                                    <span class="input-group-text">@lang('Days')</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="font-weight-bold" for="total_installment">
                                @lang('Total Installments') <span class="text-danger">*</span>
                            </label>
                            <div class="form-group">
                                <div class="input-group">
                                    <input type="text" name="total_installment" id="total_installment" class="form-control numeric-validation" placeholder="Enter Total Installments Count" value="{{ old('total_installment') }}" required />
                                    <div class="input-group-append">
                                        <span class="input-group-text">@lang('Times')</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12 text-center mb-3">
                        <h3 id="displayProfit"></h3>
                    </div>

                    <div class="col-md-12">
                        <div class="card border--dark">
                            <h5 class="card-header bg--dark p-2">@lang('Instruction') </h5>
                            <div class="card-body">
                                <textarea rows="5" class="form-control nicEdit" name="instruction">{{ old('instruction') }}</textarea>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12 mt-4">
                        <div class="card border--dark">
                            <h5 class="card-header bg--dark p-2">@lang('User\'s Required Information')</h5>

                            <div class="card-body">
                                <div class="addedField">

                                </div>
                                <button type="button" class="btn btn-sm btn--success addUserData">
                                    <i class="la la-fw la-plus"></i>@lang('Add Fields')
                                </button>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn--primary btn-block">@lang('Save')</button>
            </div>
        </form>
    </div><!-- card end -->
@endsection

@push('style')
    <style>
        .user-data {
            position: relative !important;
            border-radius: 5px;
        }
        .removeBtn {
            position: absolute;
            left: -5px;
            top: -5px;
            width: 20px;
            height: 20px;
            font-size: 10px;
            border-radius: 50%;
        }
    </style>
@endpush


@push('breadcrumb-plugins')
    <a href="{{ route('admin.loan.plan.index') }}" class="btn btn-sm btn--primary box--shadow1 text--small">
        <i class="la la-fw la-backward"></i> @lang('Go Back')
    </a>
@endpush

@push('script')
    <script>
        (function ($) {
            "use strict";

            let addCount = 0;

            $('.addUserData').on('click', function () {
                var html = `
                    <div class="user-data border mb-3">
                        <button class="btn--danger removeBtn mb-1" type="button">
                            <i class="fa fa-times"></i>
                        </button>

                        <div class="d-flex flex-wrap">
                            <div class="w-50">
                                <input name="input_form[${addCount}][field_name]" class="form-control rounded-0" type="text" value="" required placeholder="@lang('Field Name')">
                            </div>

                            <div class="w-25">
                                <select name="input_form[${addCount}][type]" class="form-control rounded-0">
                                    <option value="text"> @lang('Input') </option>
                                    <option value="textarea" > @lang('Textarea') </option>
                                    <option value="file"> @lang('File upload') </option>
                                </select>
                            </div>
                            <div class="w-25">
                                <select name="input_form[${addCount}][validation]"
                                        class="form-control rounded-0">
                                    <option value="required"> @lang('Required') </option>
                                    <option value="nullable">  @lang('Optional') </option>
                                </select>
                            </div>
                        </div>
                    </div>`;

                $('.addedField').append(html);

                addCount++;

                changeButtonText();
            });

            function changeButtonText(){
                let count = $(document).find('.user-data').length
                if(count>0){
                    $('.addUserData').html(`<i class="la la-fw la-plus"></i> @lang('Add More')`)
                }else{
                    $('.addUserData').html(`<i class="la la-fw la-plus"></i> @lang('Add Fields')`)
                }
            }


            $(document).on('click', '.removeBtn', function () {
                $(this).closest('.user-data').remove();
                changeButtonText();
            });

            $('#per_installment').on('input', (e) => displayProfit());

            $('#total_installment').on('input', (e)=> displayProfit());

            function displayProfit(){
                let totalInstallment    = parseFloat($('#total_installment').val());

                let perInstallment      = parseFloat($('#per_installment').val());
                let profit              = (totalInstallment * perInstallment).toFixed(2);

                let gain                = profit >= 100 ? 'Profit' : 'Loss';
                let bgColor             = profit >= 100 ? 'text--success' : 'text-danger';
                profit                  -= 100;
                console.log(profit);
                if(profit){
                    let adminGain = `<span class='${bgColor}'>Admin's  ${gain} ${profit}%</span>`;
                    $('#displayProfit').html(adminGain);
                    $('#displayProfit').addClass(bgColor);
                }else{
                    $('#displayProfit').html('');
                }
            }
            displayProfit();
        })(jQuery);

    </script>
@endpush

