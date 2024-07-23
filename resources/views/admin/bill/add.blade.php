@extends('admin.layouts.app')

@section('panel')
                      
                   <h6  class="alert alert-info alert-dismissible"></h6>
                   <br/>
    <div class="card">
        <form action="/iamadmin@6019/add_bill" method="POST">
            @csrf
         
            <div class="card-body">
               
               
                <div class="row">   

                   <?php 
					//place the  link  
					if(isset($id)){
						$editval  = App\Models\bill::find($id);
						
					}
					
					
					?>
                  
                  @if(isset($id))  <input  name="id"  value="{{$id}}"  type="hidden" /> @endif
                   
                   
                            
           
            
             
                    <div class="col-md-12">
                    
                    <div class="form-group">
                        <label class="form-control-label" for="input-service">Bill</label>

                                      <div class="input-icon text-default">
                                                    <span class="input-icon-addon">
                                                        <i class="fas fa-tachometer-alt"></i>
                                                    </span>
                       
<input   value="@if(isset($editval)){{ $editval->bill_name }}@else{{ old('bill_name') }}@endif" type="text" name="bill_name" maxlength="40" placeholder="E.g Ikeja Electricity distribution - IKEDC" autocomplete="off" class="form-control" required="">
                       
                                                </div>
                      </div>
         
                                </div>
                                 
                                 
                                  
             
         
                    
       
                                
                    <div class="col-md-12">
               
                   <div class="form-group">
                        <label class="form-control-label" for="input-service">Bills Code</label>

                                      <div class="input-icon text-default">
                                                    <span class="input-icon-addon">
                                                        <i class="fas fa-code"></i>
                                                    </span>
                       
    <input value="@if(isset($editval)){{ $editval->plan_code }}@else{{ old('plan_code') }}@endif" type="text" class="form-control" maxlength="40" name="plan_code" placeholder="e.g 1,2,3 etc" autocomplete="off" required="">
                          
                                                </div>



  

                      </div>
                   
                    </div>
           
                    
                               
                                
                    <div class="col-md-12">

                   <div class="form-group">
                        <label class="form-control-label" for="input-service">Convinient Charge</label>

                                      <div class="input-icon text-default">
                                                    <span class="input-icon-addon">
                                                        <i class="fas fa-code"></i>
                                                    </span>
                       
               <input value="@if(isset($editval)){{ $editval->charge }}@else{{ old('charge') }}@endif" type="number" class="form-control" maxlength="40" name="charge" placeholder="e.g 20" autocomplete="off" required="">
                          
                                                </div>



  

                      </div>
                                 
                    </div>
           
            
                  
                               
                               
                                
                    <div class="col-md-12">
<div class="form-group">
                        <label class="form-control-label" for="input-service">Reseller Convinient Fee</label>

                                      <div class="input-icon text-default">
                                                    <span class="input-icon-addon">
                                                        <i class="fas fa-code"></i>
                                                    </span>
                       
 <input value="@if(isset($editval)){{ $editval->reseller_fee }}@else{{ old('reseller_fee') }}@endif" type="number" class="form-control" maxlength="40" name="reseller_fee" placeholder="e.g 10" required="">
                          
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
    <a href="{{ route('admin.billlist') }}" class="btn btn-sm btn--primary box--shadow1 text--small">
        <i class="la la-fw la-backward"></i>  Bill  List
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

