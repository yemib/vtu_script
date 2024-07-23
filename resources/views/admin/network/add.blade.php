@extends('admin.layouts.app')

@section('panel')
    <div class="card">
        <form action="{{  route('admin.add.network') }}" method="POST">
            @csrf

            <div class="card-body">


                <div class="row">

                   <h6  class="info   info-danger">Info! Here you are to add each network one after the other in UPPERCASE. MTN, GLO, 9MOBILE, AIRTEL. The network code should be added as given on your simhosting server.</h6>
                   <br/>
                   <?php
					//place the  link
					if(isset($id)){
						$editval  = App\Models\network::find($id);

					}


					?>

                  @if(isset($id))  <input  name="id"  value="{{$id}}"  type="hidden" /> @endif

                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="font-weight-bold" for="name">
                               Network <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
<input  type="text" name="network_name" id="name" class="form-control" placeholder="Enter Network Name" value="@if(isset($editval)){{ $editval->network_name }}@else{{ old('network_name') }}@endif" required />
                            </div>
                        </div>
                    </div>



                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="font-weight-bold" for="network_code">
                             Network Code <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
        <input type="text" name="network_code" id="network-code" class="form-control" placeholder="Enter Network Name" value="@if(isset($editval)){{ $editval->network_code }}@else{{ old('network_code') }}@endif" required />
                            </div>
                        </div>
                    </div>





                    <div class="col-md-12 text-center mb-3">
                        <h3 id="displayProfit"></h3>
                    </div>



                </div>
            </div>
            <div class="card-footer">
                  <div class="col-md-3">
                  @if(isset($id)) <?php  $button = "Update" ; ?>  @else   <?php  $button = "Add" ; ?>  @endif
                <button type="submit" class="btn btn--primary btn-block">{{$button }}</button>

				</div>
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
        <i class="la la-fw la-backward"></i> Network List
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

