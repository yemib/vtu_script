@extends('admin.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-md-12">
            <div class="card border--dark">
                <h5 class="card-header bg--dark p-2">@lang('User\'s Required Information')</h5>

                <form action="" method="POST">
                    @csrf

                    <div class="card-body">
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
                            <i class="la la-fw la-plus"></i>@lang('Add More')
                        </button>
                    </div>

                    <div class="card-footer">
                        <button type="submit" class="btn btn-block btn--primary"> @lang('Save') </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

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
                $('.addUserData').html(`<i class="la la-fw la-plus"></i>@lang('Add More')`)
            } else {
                $('.addUserData').html(`<i class="la la-fw la-plus"></i>@lang('Add Fields')`)
            }
        }

        $(document).on('click', '.removeBtn', function () {
            $(this).closest('.user-data').remove();
            changeButtonText();
        });

        changeButtonText();
    })(jQuery);

</script>
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
