@extends('admin.layouts.app')

@section('panel')

                   <h6  class="alert alert-info alert-dismissible">Info! Here you are to add the airtime discount
                    people will be buying e.g 7. If a user purchase ₦100 airtime, only ₦93 will be deducted from his wallet.</h6>
                   <br/>
    <div class="card">
        <form action="/iamadmin@6019/add_airtime" method="POST">
            @csrf

            <div class="card-body">


                <div class="row">

                   <?php
					//place the  link
					if(isset($id)){
						$editval  = App\Models\airtime::find($id);

					}


					?>

                  @if(isset($id))  <input  name="id"  value="{{$id}}"  type="hidden" /> @endif

                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="font-weight-bold" for="name">
                               Network <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">

                            <select  required  class="form-control"  name="network">

                            <option  value=""> Select Network</option>

                            <?php   $networks  =  App\Models\network::get() ;

								?>

                            @foreach( $networks  as  $network )


                            <option  @if(isset($editval))    @if($editval->network  == $network->network_code)  selected  @endif  @endif
                                  value="{{  $network->network_code }}">{{  $network->network_name }}</option>


                            @endforeach
                            </select>




                            </div>
                        </div>
                    </div>



                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="font-weight-bold" for="plan">
                             Type <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">

                            <select class="form-control" name="type" required>

                                        <option value="">Please select...</option>

                                             <option   @if(isset($editval))    @if($editval->type  == "VTU" )  selected  @endif  @endif  value="VTU"> VTU </option>
												  <option @if(isset($editval))    @if($editval->type  == "SNS" )  selected  @endif  @endif value="SNS"> SNS </option>

                                                </select>

                            </div>
                        </div>
                    </div>








                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="font-weight-bold" for="discount">
                             Discount<span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
        <input type="number" name="discount" id="discount" class="form-control" placeholder="" value="@if(isset($editval)){{ $editval->discount }}@else{{ old('discount') }}@endif"  />
                            </div>
                        </div>
                    </div>



                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="font-weight-bold" for="ressller_discount">
                          Reseller   Discount<span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
        <input type="number" name="ressller_discount" id="ressller_discount" class="form-control" placeholder="" value="@if(isset($editval)){{ $editval->ressller_discount }}@else{{ old('ressller_discount') }}@endif"  />
                            </div>
                        </div>
                    </div>





                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="font-weight-bold" for="ressller_discount">
                         Actual   Discount<span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
        <input type="number" name="actual_discount" id="actual_discount" class="form-control" placeholder="" value="@if(isset($editval)){{ $editval->actual_discount }}@else{{ old('actual_discount') }}@endif"  />
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
    <a href="{{ route('admin.airtimelist') }}" class="btn btn-sm btn--primary box--shadow1 text--small">
        <i class="la la-fw la-backward"></i>  Airtime  List
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

