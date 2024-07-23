@extends($activeTemplate.'layouts.master')
@section('content')
<!-- dashboard section start -->
<div class="container">
    <div class="row justify-content-center">
        <h5>Data  </h5><br>

        <div class="col-lg-8">
            <div class="card border--base">
                <div class="card-body">
                    <form onSubmit="$('#submit_area').html('<strong>Loading...</strong>')" id="form_id"
                        action="/user/data" method="post" enctype="multipart/form-data">
                        @csrf

                        <h3 class="text-center mb-3"></h3>

                        <div class="form-group">
                            <label for="">
                                <span class="text--danger"> Select Network

                                </span>
                            </label>

                            <select name="network" onChange=" submit_form('/dataorder'  , 'form_id'  , 'order')"
                                required class="form--control">
                                <option value=""> Please Select </option>

                                <?php  $networks  =  App\Models\network::get()  ;  ?>


                                @foreach( $networks as $network)
                                <option value="{{ $network->id }}">{{ $network->network_name }} </option>


                                @endforeach
                            </select>

                        </div>


                        <div id="loading_area"> </div>
                        <div class="form-group">
                            <label for="">
                                <span class="text--danger">Order

                                </span>
                            </label>

                            <select name="order" id="order" class="form--control">
                                <option> Select Network First </option>
                            </select>

                        </div>




                        <div class="form-group">
                            <label for="">
                                <span class="text--danger">Phone Number

                                </span>
                            </label>

                            <input required name="phone" class="form-control" />
                        </div>

                        <div id="submit_area"> <button type="submit" class="btn btn--base w-100">Submit</button>

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
    .d-widget:hover {
        transform: translateY(0px) !important;
    }

    @media(max-width: 420px) {
        .d-widget__content {
            text-align: start !important;
        }
    }

    .withdraw-thumbnail {
        max-width: 220px;
        max-height: 220px
    }

    .fileinput .thumbnail>img {
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
