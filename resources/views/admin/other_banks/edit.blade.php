@extends('admin.layouts.app')

@section('panel')
    <div class="card">
        <form action="{{ route('admin.bank.edit', $bank->id) }}" method="POST">
            @csrf
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="name">@lang('Bank Name') <span class="text--danger">*</span></label>
                            <div class="input-group">
                                <input type="text" name="name" id="name" class="form-control border-radius-5" placeholder="Bank Name" value="{{ $bank->name }}" required />
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="processing_time">@lang('Processing Time')
                                <span class="text--danger">*</span>
                            </label>
                            <div class="form-group">
                                <div class="input-group">
                                    <input type="text" name="processing_time" id="processing_time" class="form-control border-radius-5" placeholder="Processing Time" value="{{ $bank->processing_time }}" required/>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">

                    <div class="col-lg-12">
                        <div class="card border--dark">
                            <h5 class="card-header bg--dark">@lang('Transfer Limit')</h5>
                            <div class="card-body">
                                <div class="row">
                                    <div class="form-group col-md-6">
                                        <label for="minimum_amount">
                                            @lang('Minimum Amount') <span class="text--danger">*</span>
                                        </label>
                                        <div class="input-group">
                                            <input type="text" class="form-control numeric-validation"
                                                id="minimum_amount" placeholder="0" name="minimum_amount"
                                                value="{{ getAmount($bank->minimum_limit) }}" required />
                                            <div class="input-group-append">
                                                <div class="input-group-text"> {{ __($general->cur_text) }} </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label for="maximum_amount">
                                            @lang('Maximum Amount')<span class="text--danger">*</span>
                                        </label>
                                        <div class="input-group">
                                            <input type="text" class="form-control numeric-validation" id="maximum_amount"
                                                placeholder="0" name="maximum_amount" value="{{ getAmount($bank->maximum_limit) }}" required />
                                            <div class="input-group-append">
                                                <div class="input-group-text"> {{ __($general->cur_text) }} </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <h4 class="text-muted my-3 border-bottom pb-2">@lang('Daily Limit')</h4>
                                <div class="row">
                                    <div class="form-group col-md-6">
                                        <label for="daily_maximum_amount">
                                            @lang('Maximum Transaction Amount')<span class="text--danger">*</span>
                                        </label>
                                        <div class="input-group">
                                            <input type="text" class="form-control numeric-validation" id="daily_maximum_amount"
                                                placeholder="0" name="daily_maximum_amount" value="{{ getAmount($bank->daily_maximum_limit) }}" required />
                                            <div class="input-group-append">
                                                <div class="input-group-text"> {{ __($general->cur_text) }} </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="daily_transaction_count">
                                            @lang('Maximum Transaction Count')<span class="text--danger">*</span>
                                        </label>
                                        <input type="text" class="form-control integer-validation" id="daily_transaction_count" placeholder="0" name="daily_transaction_count" value="{{ getAmount($bank->daily_total_transaction) }}" required />
                                    </div>
                                </div>


                                <h4 class="text-muted my-3 border-bottom pb-2">@lang('Monthly Limit')</h4>
                                <div class="row">
                                    <div class="form-group col-md-6">
                                        <label for="monthly_maximum_amount">
                                            @lang('Maximum Transaction Amount')<span class="text--danger">*</span>
                                        </label>
                                        <div class="input-group">
                                            <input type="text" class="form-control numeric-validation" id="monthly_maximum_amount"
                                                placeholder="0" name="monthly_maximum_amount" value="{{ getAmount($bank->monthly_maximum_limit) }}" required />
                                            <div class="input-group-append">
                                                <div class="input-group-text"> {{ __($general->cur_text) }} </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="monthly_transaction_count">
                                            @lang('Maximum Transaction Count')<span class="text--danger">*</span>
                                        </label>
                                        <input type="text" class="form-control integer-validation" id="monthly_transaction_count" placeholder="0" name="monthly_transaction_count" value="{{ getAmount($bank->monthly_total_transaction) }}" required />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="col-lg-12 mt-3">
                        <div class="card border--dark">
                            <h5 class="card-header bg--dark">@lang('Transfer Charge')</h5>
                            <div class="card-body">
                                <div class="row">
                                    <div class="form-group col-md-6">
                                        <label for="fixed_charge">
                                            @lang('Fixed Charge')
                                            <span class="text--danger">*</span>
                                        </label>
                                        <div class="input-group">
                                            <input type="text" class="form-control numeric-validation"
                                                placeholder="0" name="fixed_charge" id="fixed_charge"
                                                value="{{ getAmount($bank->fixed_charge) }}" required />
                                            <div class="input-group-append">
                                                <div class="input-group-text"> {{ __($general->cur_text) }} </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="percent_charge">
                                            @lang('Percent Charge')
                                            <span class="text--danger">*</span>
                                        </label>
                                        <div class="input-group">
                                            <input type="text" class="form-control numeric-validation"
                                                placeholder="0" name="percent_charge" id="percent_charge"
                                                value="{{ getAmount($bank->percent_charge) }}" required />
                                            <div class="input-group-append">
                                                <div class="input-group-text">%</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12 mt-3">
                        <div class="card border--dark">
                            <h5 class="card-header bg--dark p-2">@lang('Instruction') </h5>
                            <div class="card-body">
                                <textarea rows="5" class="form-control nicEdit" name="instruction">@php echo $bank->instruction @endphp</textarea>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12 mt-4">
                        <div class="card border--dark">
                            <h5 class="card-header bg--dark p-2">@lang('User\'s Required Information')</h5>
                            <div class="card-body">

                                <div class="d-flex flex-wrap  mb-3">
                                    <div class="w-50">
                                        <input name="account_name" class="form-control rounded-0 bg-white text--black" type="text" value="Account Name" required placeholder="@lang('Field Name')" readonly>
                                    </div>

                                    <div class="w-25">
                                        <select  class="form-control rounded-0">
                                            <option value="text"> @lang('Input') </option>
                                        </select>
                                    </div>
                                    <div class="w-25">
                                        <select class="form-control rounded-0">
                                            <option value="required"> @lang('Required') </option>
                                        </select>
                                    </div>
                                </div>

                                <div class="d-flex flex-wrap  mb-3">
                                    <div class="w-50">
                                        <input name="account_name" class="form-control rounded-0 bg-white text--black" type="text" value="Account Number" required placeholder="@lang('Field Name')" readonly>
                                    </div>

                                    <div class="w-25">
                                        <select  class="form-control rounded-0">
                                            <option value="text"> @lang('Input') </option>
                                        </select>
                                    </div>
                                    <div class="w-25">
                                        <select class="form-control rounded-0">
                                            <option value="required"> @lang('Required') </option>
                                        </select>
                                    </div>
                                </div>

                                <div class="addedField">
                                    @if($fieldsCount)
                                        @foreach($fields as $v)
                                            <div class="user-data border mb-3">
                                                <button class="btn--danger removeBtn mb-1" type="button">
                                                    <i class="fa fa-times"></i>
                                                </button>
                                                <div class="d-flex flex-wrap">
                                                    <div class="w-50">
                                                        <input name="input_form[{{ $loop->index }}][field_name]" class="form-control rounded-0" type="text" value="{{$v->field_name}}" required placeholder="@lang('Field Name')">
                                                    </div>
                                                    <div class="w-25">
                                                        <select name="input_form[{{ $loop->index }}][type]" class="form-control rounded-0">
                                                            <option value="text" @if($v->type == 'text') selected @endif>
                                                                @lang('Input Text')
                                                            </option>
                                                            <option value="textarea" @if($v->type == 'textarea') selected @endif>
                                                                @lang('Textarea')
                                                            </option>
                                                            <option value="file" @if($v->type == 'file') selected @endif>
                                                                @lang('File upload')
                                                            </option>
                                                        </select>
                                                    </div>
                                                    <div class="w-25">
                                                        <select name="input_form[{{ $loop->index }}][validation]" class="form-control rounded-0">
                                                            <option value="required" @if($v->validation == 'required') selected @endif> @lang('Required') </option>
                                                            <option value="nullable" @if($v->validation == 'nullable') selected @endif>  @lang('Optional') </option>
                                                        </select>
                                                    </div>

                                                </div>
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                                <button type="button" class="btn btn-sm btn--success addUserData">
                                    <i class="la la-fw la-plus"></i>@if($fieldsCount) @lang('Add More') @else @lang('Add Fields') @endif
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn--primary btn-block">@lang('Save Changes')</button>
            </div>
        </form>
    </div><!-- card end -->
@endsection


@push('breadcrumb-plugins')
<a href="{{ route('admin.bank.index') }}" class="btn btn-sm btn--primary box--shadow1 text--small">
    <i class="la la-fw la-backward"></i> @lang('Go Back')
</a>
@endpush

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

@push('script')
<script>
    (function ($) {
        "use strict";
        let addCount = {{ $fieldsCount }};

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

        function changeButtonText() {
            let count = $(document).find('.user-data').length
            if (count > 0) {
                $('.addUserData').html(`<i class="la la-fw la-plus"></i> @lang('Add More')`)
            } else {
                $('.addUserData').html(`<i class="la la-fw la-plus"></i> @lang('Add Fields')`)
            }
        }

        $(document).on('click', '.removeBtn', function () {
            $(this).closest('.user-data').remove();
            changeButtonText();
        });
    })(jQuery);

</script>
@endpush
