<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\GeneralSetting ;
use App\Models\bank_account ;
use App\Models\bank_transaction ;
use App\Models\Deposit ;
use App\Models\User ;

class monify_controller extends Controller
{

	public function  __construct(){




	}


public	$urlt2  =  "https://sandbox.monnify.com/";
public	$urlt  =  "https://api.monnify.com/";



	public function  monify_token(){

$keys  =  GeneralSetting::first()  ;
		$api_key  = $keys->monify_api_key  ;
		$secret_key  =  $keys->monify_user_key  ;
		$code  =  $keys->monify_contract_code  ;


		//return($code   );

	$curl = curl_init();
	$key  =  	base64_encode("$api_key:$secret_key") ;
	$url  = $this->urlt."api/v1/auth/login"    ;
		$array  =   array(

  CURLOPT_URL => "$url",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
CURLOPT_SSL_VERIFYPEER => false ,
	/*
  CURLOPT_POSTFIELDS =>'{
    "network":"'.$request->network.'",
    "amount":"'.$request->amount.'",
    "type": "VTU",
    "mobile_number":"'.$request->phone.'"
}',  */
  CURLOPT_HTTPHEADER => array(
    "Authorization: Basic $key",
    'Content-Type: application/json'
  ),
)  ;

	//return($array);
curl_setopt_array($curl, $array );

$response = curl_exec($curl);

	curl_close($curl);

$jsonArrayResponse = json_decode($response  , TRUE);

				if ($response == FALSE) {



	return(curl_error($curl))  ;

//return back()->with($all);


}else{
					//print_r($jsonArrayResponse);

					//return($jsonArrayResponse)  ;

					try{


					return($jsonArrayResponse['responseBody']['accessToken'] );


					}catch(\Exception $e){


						return('  ');

					}



				}



	}




	public function getaccount(){

		//get the details  of the user okay
		$user  =  auth()->user()  ;

		//decode reference here.


		$gettoken  =  $this->monify_token();
$keys  =  GeneralSetting::first()  ;
$api_key  = $keys->monify_api_key  ;
$secret_key  =  $keys->monify_secret_key  ;
$code  =  $keys->monify_contract_code  ;
$curl = curl_init();

	$url  = $this->urlt."api/v2/bank-transfer/reserved-accounts"    ;
		$array  =   array(

  CURLOPT_URL => "$url",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
CURLOPT_SSL_VERIFYPEER => false ,

  CURLOPT_POSTFIELDS =>'{


 	"accountReference": "'.$user->account_number.'",
	"accountName": "'.$user->firstname.' '. $user->	lastname. '",
	"currencyCode": "NGN",
	"contractCode": "'.$code.'",
	"customerEmail": "'.$user->email.'",
	"customerName": "'.$user->firstname.' '. $user->lastname. '",
	"getAllAvailableBanks": true

}',
  CURLOPT_HTTPHEADER => array(
    "Authorization: Bearer $gettoken",
    'Content-Type: application/json'
  ),
)  ;

	//return($array);
curl_setopt_array($curl, $array );

$response = curl_exec($curl);

	curl_close($curl);

$jsonArrayResponse = json_decode($response  , TRUE);

				if ($response == FALSE) {

	//return(curl_error($curl))  ;

//return back()->with($all);


}else{
		//save the bank details


		//	foreach($incoming_value as $key ) {

            try{
$save  = new bank_account()  ;

$save->customerName  = $jsonArrayResponse['responseBody']['customerName']  ;

$save->accountReference  = $jsonArrayResponse['responseBody']['accountReference']  ;

$save->accountName  = $jsonArrayResponse['responseBody']['accountName']  ;

$save->bankCode  = $jsonArrayResponse['responseBody']['accounts'][0]['bankCode']   ;

$save->bankName  = $jsonArrayResponse['responseBody']['accounts'][0]['bankName']   ;

$save->accountNumber  = $jsonArrayResponse['responseBody']['accounts'][0]['accountNumber']  ;

$save->user_id  =  $user->id  ;

$save->save()  ;
            }catch(\Exception $e){

            }


	//	}

		//add  the  user id  okay.

					return($jsonArrayResponse );
				}





	}



	public function  transaction_trace(Request $request , $bank_ref)  {

		//create a monnify transaction trace database  .

				//get the details  of the user okay
		$user  =  auth()->user()  ;

		//get user reference from bank account

		//$bank_ref  = bank_account::where('user_id'  ,  $user->id)->first()  ;
		//decode reference here.

		$gettoken  =  $this->monify_token();
$keys  =  GeneralSetting::first()  ;
$api_key  = $keys->monify_api_key  ;
$secret_key  =  $keys->monify_secret_key  ;
$code  =  $keys->monify_contract_code  ;
$curl = curl_init();

	$url  = $this->urlt."api/v1/bank-transfer/reserved-accounts/transactions?accountReference=$bank_ref&page=0&size=10"    ;
		$array  =   array(

  CURLOPT_URL => "$url",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'GET',
CURLOPT_SSL_VERIFYPEER => false ,


  CURLOPT_HTTPHEADER => array(
    "Authorization: Bearer $gettoken",
    'Content-Type: application/json'
  ),
)  ;

	//return($array);
curl_setopt_array($curl, $array );

$response = curl_exec($curl);

	curl_close($curl);

$jsonArrayResponse = json_decode($response  , TRUE);

				if ($response == FALSE) {

	//return(curl_error($curl))  ;

//return back()->with($all);


}else{

				try{

			$output  =	json_decode($response , TRUE);


		$all  =	 $output['responseBody']['content'] ;

	foreach($all as $detail){
	//first check

$check = bank_transaction::where('paymentReference'  ,  $detail['paymentReference'])->count();
	if($check  ==  0){

	if( $detail['paymentStatus']  ==  "PAID" ){

		$save  =  new  bank_transaction();
		$save->paymentReference   =  $detail['paymentReference']  ;
		$save->amount  =   $detail['amountPaid']  ;
		$save->user_id  = $user->id ;
		$save->save();

		//update user balance and deposit transaction history .

		$user_bal  =  User::find($user->id )  ;

		$total_amount = $user_bal->balance + $detail['amountPaid']  ;

		$user_bal->balance  =  $total_amount ;

		$user_bal->save() ;


		$data = new Deposit();
        $data->user_id = $user->id;
        $data->method_code = 200;
        $data->method_currency = "NGN";
        $data->amount = $detail['amountPaid'];
        $data->charge = 0;
        $data->rate = 1;
        $data->final_amo = $detail['amountPaid'];
        $data->btc_amo = 0;
        $data->btc_wallet = "";
        $data->trx = $detail['paymentReference'];
        $data->try = 0;
        $data->status = 1;
        $data->save();

		//add the transaction here....

		$tran = new purchase_controller();
		$request ['amount'] =  $detail['amountPaid'];
		$trx_type = "+";
			$trx = "Success";
		$details  = "Deposit Via MONNIFY";

		$tran->transaction($request    ,  $trx_type   , $trx   , $details   );

	}
		//update the available balance okay .




	}





	}


					return($output);
				}catch(\Exception $e){

					return(" ");

				}


				}





	}


}
