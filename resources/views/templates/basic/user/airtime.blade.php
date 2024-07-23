@extends($activeTemplate.'layouts.master')
@section('content')
<!-- dashboard section start -->
<div class="container">
    <div class="row justify-content-center">
       <h5>Airtime</h5><br>

        <div class="col-lg-8">
            <div class="card border--base">
                <div class="card-body">
                    <form   onSubmit="$('#submit_area').html('<strong>Loading...</strong>')"    id="form_id" action="/user/pay_airtime" method="post" enctype="multipart/form-data">

                        @csrf

                        <h3 class="text-center mb-3"></h3>




        <div class="form-group">
        <label for="">
 <span class="text--danger">Network

       </span>
               </label>
                         <select  name="network"     required  class="form--control">
                	<option  value=""> Please Select   </option>

                	 <?php  $airtimes  =  App\Models\airtime::get()  ;  ?>


                	 @foreach( $airtimes as  $airtime)

                	<?php    $network  =  App\Models\network::where("network_code"  ,$airtime->network )->first() ;  ?>
                	 	<option  value="{{ $network->network_code }}">{{ $network->network_name }} </option>


                	 @endforeach
                </select>


               </div>



        <div class="form-group">
        <label for="">
 <span class="text--danger">Type

       </span>
               </label>

                <select  name="type" id="type"  class="form--control">
                	<option> Select Network  First </option>
                	<option value="VTU">VTU</option>
                </select>

                                    </div>



        <div class="form-group">
        <label for="">
 <span class="text--danger">Amount

       </span>
               </label>
               <input  class="form--control"   name="amount"   />


                                    </div>




        <div class="form-group">
        <label for="">
 <span class="text--danger">Phone Number

       </span>
               </label>

              <input  required   value="{{old('phone')}}"   name="phone"   class="form-control"     />
                                    </div>
                     <div  id="submit_area">

                            <button type="submit" class="btn btn--base w-100">Submit</button>

						</div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- dashboard section end -->
@endsection




@push('style')
<style>
    .d-widget:hover{
        transform: translateY(0px) !important;
    }
    @media(max-width: 420px){
        .d-widget__content{
            text-align: start !important;
        }
    }
    .withdraw-thumbnail{
        max-width: 220px;
        max-height: 220px
    }
    .fileinput .thumbnail > img{
        max-width: 100%;
        max-height: 250px;
    }
</style>
@endpush

@push('script-lib')
<script src="{{asset($activeTemplateTrue.'/js/bootstrap-fileinput.js')}}"></script>
@endpush
@push('style-lib')
<link rel="stylesheet" href="{{asset($activeTemplateTrue.'/css/bootstrap-fileinput.css')}}">
@endpush

@push('script')
<script>

    (function($){

        "use strict";

            $('.withdraw-thumbnail').hide();

            $('.clickBtn').on('click', function() {

                var classNmae = $('.fileinput').attr('class');

                if(classNmae != 'fileinput fileinput-exists'){
                    $('.withdraw-thumbnail').hide();
                }else{

                    $('.fileinput-preview img').css({"width":"100%", "height":"300px", "object-fit":"contain"});

                    $('.withdraw-thumbnail').show();

                }

            });

    })(jQuery);

</script>
@endpush
