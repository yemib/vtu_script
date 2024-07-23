@extends($activeTemplate.'layouts.master')
@section('content')

<!-- dashboard section start -->
    <div class="container">



        <?php   $user = auth()->user(); ?>




                               <div>
                                @if($user->role  == "Referral")
                          <?php   $manager  = App\Models\User::find($user->manager);  ?>

                             @if(isset( $manager->id ))
                             <p>

                               <strong>  Your Manager : </strong>
                                 <br/>

                                 Name  :     {{  $manager->getFullnameAttribute() }}
                                      <br/>
                                    Email :   {{  $manager->email  }}

                                  <br/>
                                   Phone  :     {{  $manager->mobile }}
                                   </p>



                                @endif

                              @endif


                                      @if($user->role  == "Manager")

                                      <?php   $manager  = App\Models\User::find($user->super_manager);  ?>
                                              @if(isset( $manager->id ))
                                            <p>

                               <strong>  Your Manager : </strong>
                                 <br/>

                                 Name  :     {{  $manager->getFullnameAttribute() }}
                                      <br/>
                                    Email :   {{  $manager->email  }}

                                  <br/>
                                   Phone  :     {{  $manager->mobile }}
                                   </p>




                                      @endif
                                      @endif



                                              @if($user->role  == "Super Manager")


                                      @endif


                               </div>







                              @if( $user->role  !=  'Company'  && $user->role  !=  'Admin'  )


                                   <div>


                           @if( $user->resseller  ==  0  )
       	<a  href="/user/upgrade/resseller"  class="btn btn-success">  Become a Resseller</a>

       	@endif

       	@if($user->role  ==  "Referral")
       	<a  href="/user/upgrade/manager"  class="btn btn-success">  Upgrade to Manager</a>

       	@endif

       		@if($user->role  ==  "Manager")
       	<a  href="/user/upgrade/supermanager"  class="btn btn-success">  Upgrade to Super-Manager   </a>

       	@endif
       </div>

       <br/>


                               @endif


                        <div class="avatar-upload">
                           <a  href="{{ route('user.profile.setting') }}">
                            <div class="avatar-edit">

                                <label for="imageUpload"></label>
                            </div>
                            </a>
                            <div class="avatar-preview">
                                <div id="imagePreview" style="background-image: url({{ getImage(imagePath()['profile']['user']['path'].'/'. $user->image,imagePath()['profile']['user']['size']) }});">
                                </div>
                            </div>


                        </div>
                        @if($user->resseller  ==  1)
                            <p  style="color: green"><strong>Reseller Account</strong></p>

                            @endif


        <div class="row justify-content-center gy-4">


            <div class="col-lg-6">
               <a  href="/user/deposits"   class="w-100 h-100 " >
                <div class="card-widget section--bg2 text-center bg_img  " style="background-image: url(' {{ asset($activeTemplateTrue.'images/elements/card-bg.png') }} ');">
                    <span class="caption text-white mb-3">@lang('Available Balance')</span>
                    <h3 class="d-number text-white">{{ $general->cur_sym }}{{ showAmount($user->balance) }}</h3>
                </div><!-- d-widget end -->

				</a>
            </div>


                 <div class="col-lg-6">
               <a  href="{{route('user.referral.commissions.logs')}}"   class="w-100 h-100" >
                <div class="card-widget section--bg2 text-center bg_img" style="background-image: url(' {{ asset($activeTemplateTrue.'images/elements/card-bg.png') }} ');">
                    <span class="caption text-white mb-3">@lang('Referral Bonus Balance')</span>
                    <h3 class="d-number text-white">{{ $general->cur_sym }}{{ showAmount($user->bonus_balance) }}</h3>
                </div><!-- d-widget end -->

				</a>
            </div>


        </div>


        <!-- row end -->

        <div class="row justify-content-center gy-4 mt-4">




            <div class="col-lg-4 col-md-6">
                <a href="{{ route('user.data') }}" class="w-100 h-100">
                    <div class="d-widget section--bg2 d-flex flex-wrap align-items-center rounded-3 bg_img h-100" style="background-image: url(' {{ asset($activeTemplateTrue.'images/elements/card-bg.png') }} ');">
                        <div class="d-widget__content">
                        <h6 class="d-number text-white textsize">Data Topup</h6>
                        <span class="caption text-white"></span>
                        </div>
                        <div class="d-widget__icon border-radius--100">
                            <i class="las la-money-check"></i>
                        </div>
                    </div><!-- d-widget end -->
                </a>
            </div>


            <div class="col-lg-4 col-md-6">
                <a href="{{ route('user.airtime') }}" class="w-100 h-100">
                    <div class="d-widget section--bg2 d-flex flex-wrap align-items-center rounded-3 bg_img h-100" style="background-image: url(' {{ asset($activeTemplateTrue.'images/elements/card-bg.png') }} ');">
                        <div class="d-widget__content">
                          <!--
                           <div  class="caption text-white mb-3">Airtime  Topup  </div>  --->

                            <h3 class="d-number text-white">Airtime  Topup</h3>
                            <span class="caption text-white"></span>
                        </div>
                        <div class="d-widget__icon border-radius--100">
                        <i class="las la-money-check"></i>
                        </div>
                    </div><!-- d-widget end -->
                </a>
            </div>


            <div class="col-lg-4 col-md-6">
                <a href="{{ route('user.transaction.history') }}" class="w-100 h-100">
                    <div class="d-widget section--bg2 d-flex flex-wrap align-items-center rounded-3 bg_img h-100" style="background-image: url(' {{ asset($activeTemplateTrue.'images/elements/card-bg.png') }} ');">
                        <div class="d-widget__content">
                            <h3 class="d-number text-white">{{ @$widget['total_trx'] }}</h3>
                            <span class="caption text-white">@lang('Transactions')</span>
                        </div>
                        <div class="d-widget__icon border-radius--100">
                        <i class="las la-exchange-alt"></i>
                        </div>
                    </div><!-- d-widget end -->
                </a>
            </div>


                <div class="col-lg-4 col-md-6">
                    <a href="{{ route('user.cables') }}" class="w-100 h-100">
                        <div class="d-widget section--bg2 d-flex flex-wrap align-items-center rounded-3 bg_img h-100" style="background-image: url(' {{ asset($activeTemplateTrue.'images/elements/card-bg.png') }} ');">
                            <div class="d-widget__content">
                                <h3 class="d-number text-white">Cables</h3>
                            <span class="caption text-white"></span>
                            </div>
                            <div class="d-widget__icon border-radius--100">
                            <i class="las la-money-bill"></i>
                            </div>
                        </div><!-- d-widget end -->
                    </a>
                </div>





                <div class="col-lg-4 col-md-6">
                    <a href="{{ route('user.bills') }}" class="w-100 h-100">
                        <div class="d-widget section--bg2 d-flex flex-wrap align-items-center rounded-3 bg_img h-100" style="background-image: url(' {{ asset($activeTemplateTrue.'images/elements/card-bg.png') }} ');">
                            <div class="d-widget__content">
                                <h3 class="d-number text-white">Bills</h3>
                            <span class="caption text-white"></span>
                            </div>
                            <div class="d-widget__icon border-radius--100">
                                <i class="las la-box-open"></i>
                            </div>
                        </div><!-- d-widget end -->
                    </a>
                </div>


@if(auth()->user()->role  ==  'Manager'   ||   auth()->user()->role  == 'Super Manager')

            <div class="col-lg-4 col-md-6">
                <a href="/user/my_downlines" class="w-100 h-100">
                    <div class="d-widget section--bg2 d-flex flex-wrap align-items-center rounded-3 bg_img h-100" style="background-image: url(' {{ asset($activeTemplateTrue.'images/elements/card-bg.png') }} ');">
                        <div class="d-widget__content">
                            <h3 class="d-number text-white">My Downlines</h3>
                        <span class="caption text-white"></span>
                        </div>
                        <div class="d-widget__icon border-radius--100">
                        <i class="las la-hand-holding-usd"></i>
                        </div>
                    </div><!-- d-widget end -->
                </a>
            </div>
          @endif

            <div class="col-lg-4 col-md-6">
                <a href="/user/referees" class="w-100 h-100">
                    <div class="d-widget section--bg2 d-flex flex-wrap align-items-center rounded-3 bg_img h-100" style="background-image: url(' {{ asset($activeTemplateTrue.'images/elements/card-bg.png') }} ');">
                        <div class="d-widget__content">
                            <h3 class="d-number text-white">My Referral</h3>
                        <span class="caption text-white"></span>
                        </div>
                        <div class="d-widget__icon border-radius--100">
                        <i class="las la-hand-holding-usd"></i>
                        </div>
                    </div><!-- d-widget end -->
                </a>
            </div>




        </div><!-- row end -->

        @if($general->modules->referral_system)
        <div class="col-12 mt-5">
            <div class="d-widget section--bg2 d-flex flex-wrap align-items-center rounded-3 bg_img h-100" style="background-image: url(' {{ asset($activeTemplateTrue.'images/elements/card-bg.png') }} ');">
                <label for="lastname" class="col-form-label text-white">@lang('My Referral Link'):</label>
                <div class="input-group">
                    <input type="url" id="ref" value="{{ route('home').'?reference='.auth()->user()->username }}" class="form-control form-control-lg bg-transparent text-white" readonly>
                    <button  type="button"  data-copytarget="#ref" class="input-group-text bg--base text-white copybtn border-0"><i class="fa fa-copy"></i> &nbsp; @lang('Copy')</button>
                </div>
            </div>
        </div>
        @endif

    </div>
  <!-- dashboard section end -->



        		<style>

					.backcolor1{

						/*background-color: greenyellow*/
					}

					.backcolor2{
						/*background-color: blue;*/
					}




.col-lg-6  {
   flex: 0 0 auto;
    width: 50%;
}
.col-lg-4{
		flex: 0 0 auto;
   width: 50%;

							   }

			.d-widget__content	.d-number{

    font-size: 13px ;

				}

					.d-widget{
						 padding: 2px !important  ;


					}


					.d-widget__icon i {
    font-size: 13px;
}

		  @media only screen and (min-width: 481px) {

			  .col-lg-4{
		flex: 0 0 auto;
   width: 33.3333333333%;

							   }


		  }

		  @media only screen and (min-width: 769px) {

			  .col-lg-4{
		flex: 0 0 auto;
   width: 33.3333333333%;

							   }

			  .d-widget{
						 padding: 30px !important

					}


				.d-number{

    font-size: 26px ;

				}

			.d-widget__content	.d-number{

    font-size: 26px ;

				}

			  	.col-lg-4{
								     flex: 0 0 auto;
								   width: 33.3333333333%;

							   }

			  .d-widget__icon i {
    font-size: 42px;
}


					}


			</style>



@endsection



@if($general->modules->referral_system)
    @push('script')
        <script>
            'use strict';
            document.querySelectorAll('.copybtn').forEach((element)=>{
                element.addEventListener('click', copy, true);
            })

            function copy(e) {
                var
                    t = e.target,
                    c = t.dataset.copytarget,
                    inp = (c ? document.querySelector(c) : null);
                if (inp && inp.select) {
                    inp.select();
                    try {
                        document.execCommand('copy');
                        inp.blur();
                        t.classList.add('copied');
                        setTimeout(function() { t.classList.remove('copied'); }, 1500);
                    }catch (err) {
                        alert(`@lang('Please press Ctrl/Cmd+C to copy')`);
                    }
                }
            }
        </script>
    @endpush


    @push('style')
        <style>
            .copyInput {
                display: inline-block;
                line-height: 50px;
                position: absolute;
                top: 0;
                right: 0;
                width: 40px;
                text-align: center;
                font-size: 14px;
                cursor: pointer;
                -webkit-transition: all .3s;
                -o-transition: all .3s;
                transition: all .3s;
            }

            .copied::after {
                position: absolute;
                top: 10px;
                right: 12%;
                width: 100px;
                display: block;
                content: "COPIED";
                font-size: 1em;
                padding: 5px 5px;
                color: #fff;
                background-color: #{{ $general->base_color }};
                border-radius: 3px;
                opacity: 0;
                will-change: opacity, transform;
                animation: showcopied 1.5s ease;
            }

            @keyframes showcopied {
                0% {
                    opacity: 0;
                    transform: translateX(100%);
                }
                50% {
                    opacity: 0.7;
                    transform: translateX(40%);
                }
                70% {
                    opacity: 1;
                    transform: translateX(0);
                }
                100% {
                    opacity: 0;
                }
            }

        </style>

    @endpush
@endif
