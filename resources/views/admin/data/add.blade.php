@extends('admin.layouts.app')

@section('panel')
    <div class="card">
        <form action="/iamadmin@6019/add_data" method="POST">
            @csrf

            <div class="card-body">


                <div class="row">

                   <h6  class="alert alert-info alert-dismissible">Info! Here you are to add the DATA details as provided by your retailer host.</h6>
                   <br/>
                   <?php
					//place the  link
					if(isset($id)){
						$editval  = App\Models\data::find($id);

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


                            <option  @if(isset($editval))    @if($editval->network  == $network->id )  selected  @endif  @endif  value="{{  $network->id }}">{{  $network->network_name }}</option>


                            @endforeach
                            </select>




                            </div>
                        </div>
                    </div>



                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="font-weight-bold" for="plan">
                             Plan <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
        <input type="text" name="plan" id="plan" class="form-control" placeholder="Enter Plan Details" value="@if(isset($editval)){{ $editval->plan }}@else{{ old('plan') }}@endif" required />
                            </div>
                        </div>
                    </div>


                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="font-weight-bold" for="plan_code">
                             Plan  Type<span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
        <input type="text" name="plan_type" id="plan_type" class="form-control" placeholder="Enter Plan Code" value="@if(isset($editval)){{ $editval->plan_type }}@else{{ old('plan_type') }}@endif" required />
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="font-weight-bold" for="default_price">
                             Selling Price<span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
        <input type="number" name="default_price" id="default_price" class="form-control" placeholder="Enter Plan Code" value="@if(isset($editval)){{ $editval->default_price }}@else{{ old('default_price') }}@endif" required />
                            </div>
                        </div>
                    </div>

                                <div class="col-md-12">
                        <div class="form-group">
                            <label class="font-weight-bold" for="reseller_price">
                             Reseller Price<span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
        <input type="number" name="reseller_price" id="reseller_price" class="form-control" placeholder="" value="@if(isset($editval)){{ $editval->reseller_price }}@else{{ old('reseller_price') }}@endif" required />
                            </div>
                        </div>
                    </div>


                                   <div class="col-md-12">
                        <div class="form-group">
                            <label class="font-weight-bold" for=">plan_price">
                             plan Price<span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
        <input type="number" name="plan_price" id=">plan_price" class="form-control" placeholder="" value="@if(isset($editval)){{ $editval->plan_amount }}@else{{ old('plan_amount') }}@endif" required />
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
        <i class="la la-fw la-backward"></i>  Data  List
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

