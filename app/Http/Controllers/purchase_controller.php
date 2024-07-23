<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;

use App\Models\GeneralSetting;

use App\Models\User;
use App\Models\data;
use App\Models\network;
use App\Models\referral_bonus;
use App\Models\role;
use App\Models\airtime;
use App\Models\CommissionLog;
use App\Models\AdminNotification;



use DB;

class purchase_controller extends Controller
{
    //
    //upgrade function


    public function  payupgrade($type, Request $request)
    {

        switch ($type) {
            case 'resseller':
                $title = "Resseller";
                break;
            case 'manager':
                $title = "Manager";
                break;
            case 'supermanager':
                $title = "Super Manager";
                break;
            default:
                $title = $type;
        }


        $user   =  auth()->user();

        //direct payment for resseller  while  manager and super can pay based on  money  maked.



        $role  =  role::where('name',  $title)->first();
        if ($title  ==  "Resseller") {

            //check the balance
            if (isset($role->id)) {


                if ($role->levy  <=  $user->balance) {
                    //do the deduction and transfer  it.
                    $deduct  =  User::find($user->id);

                    $remain  =  $user->balance  -  $role->levy;
                    $deduct->balance  = $remain;

                    $deduct->resseller  =  1;
                    $deduct->save();
                    //save transaction.

                    //now redirect...


                    $trx = "Success";
                    $detail = "Account Ugraded to Resseller";

                    $request['amount']  = $role->levy;

                    $this->transaction($request,  "-", $trx, $detail);



                    $notify[] = ['success', 'Successful'];
                    return redirect('/user/transactions')->withNotify($notify);
                }


                $notify[] = ['error', 'Insufficient Fund. Fund Your Wallet'];
                return back()->withNotify($notify);
            }
        }

        //now manager and  super manager


        if ($title  ==  "Manager") {
            //check some certain things before  sending  mail  or request  for approval.
            $check_ref  =  User::where('ref_by',  $user->id)->count();

            if ($check_ref  <  20) {
                //please  reject it .

                $notify[] = ['error', 'You need to have  20  referrals  before  you can request for  upgrade.  Thanks.'];
                return back()->withNotify($notify);
            }

            //send notification to Admin  to decide  .
            $general  = GeneralSetting::first();


            $email  =  $general->noti_email;
            $path  =  "https://" . $_SERVER['HTTP_HOST'] . urlPath('admin.users.detail', $user->id);
            $name  =  $user->getFullnameAttribute();
            $subject  =  "$name Request to Become A  manager";
            $message  =  "$name Request to Become A  manager  <br>   Email  : $user->email  <br/>   Phone  :  $user->mobile  <br/>  Requested to become a manager  .  <br/>  $path  <br/> <a   href='$path'> Upgrade </a>  ";
            $receiver_name  =  "Admin";

            sendGeneralEmail($email, $subject, $message, $receiver_name);


            $adminNotification = new AdminNotification();
            $adminNotification->user_id = $user->id;
            $adminNotification->title = 'Manager Upgrade Requested';
            $adminNotification->click_url = urlPath('admin.users.detail', $user->id);
            $adminNotification->save();
            //send the  notification...

            $notify[] = ['success', 'Upgrade Request Submitted'];
            return back()->withNotify($notify);

            /*
if(isset($role->id)){



				//do the deduction and transfer  it.
$deduct  =  User::find($user->id);

	$remain  =  $user->balance  -  $role->levy   ;
	$deduct->balance  =$remain   ;

$deduct->role  =  "Manager"  ;
$deduct->save();
								//save transaction.

								//now redirect...


	$trx = "Success";
	$detail = "Account Ugraded to  Manager";

	$request['amount']  =$role->levy ;

$this->transaction($request    ,  "-"   , $trx   , $detail    ) ;



	$notify[] = ['success', 'Successful'];
        return redirect('/user/transactions')->withNotify($notify);

}

*/
        }

        if ($title  ==  "Super Manager") {
            //do the super manager


            $check_ref  =  User::where('ref_by',  $user->id)->count();

            if ($check_ref  <  20) {
                //please  reject it .

                //$notify[] = ['error', 'You need to have  20  referrals  before  you can request for  upgrade.  Thanks.'];
                //  return back()->withNotify($notify);

            }

            //send notification to Admin  to decide  .
            $general  = GeneralSetting::first();


            $email  =  $general->noti_email;
            $path  =  "https://" . $_SERVER['HTTP_HOST'] . urlPath('admin.users.detail', $user->id);
            $name  =  $user->getFullnameAttribute();
            $subject  =  "$name Request to Become A Super manager";
            $message  =  "$name  Request to Become A Super manager <br>   Email  : $user->email   <br/> Phone  :  $user->mobile   <br/>  .<br/>   <a   href='	$path'> Upgrade </a>  ";
            $receiver_name  =  "Admin";

            sendGeneralEmail($email, $subject, $message, $receiver_name);


            $adminNotification = new AdminNotification();
            $adminNotification->user_id = $user->id;
            $adminNotification->title = 'Super Manager Upgrade Requested';
            $adminNotification->click_url = urlPath('admin.users.detail', $user->id);
            $adminNotification->save();
            //send the  notification...

            $notify[] = ['success', 'Upgrade Request Submitted'];
            return back()->withNotify($notify);

            /*

if(isset($role->id)){

				//do the deduction and transfer  it.
$deduct  =  User::find($user->id);

	$remain  =  $user->balance  -  $role->levy   ;
	$deduct->balance  =$remain   ;

$deduct->role  =  "Super Manager"  ;
$deduct->save();
								//save transaction.

								//now redirect...


	$trx = "Success";
	$detail = "Account Ugraded to Super Manager";

	$request['amount']  =$role->levy ;

$this->transaction($request    ,  "-"   , $trx   , $detail    ) ;



	$notify[] = ['success', 'Successful'];
        return redirect('/user/transactions')->withNotify($notify);

}
	*/
        }
    }




    //make necessary  profit and add to individual wallet and  comission area .



    public function  bonus_transaction($amount,  $trx_type, $trx, $details, $user, $trxamount,  $percent, $title)
    {
        //the list of the transaction .
        $from  = auth()->user();

        $balance  =  User::find($user->id);
        $save  = new  CommissionLog();
        $save->to_id  =  $user->id;
        $save->from_id  =  $from->id;
        $save->level =  0;
        $save->commission_amount  =  $amount;
        $save->main_amo =  $balance->bonus_balance;
        $save->trx_amo =  $trxamount;
        $save->percent  =  $percent;

        $save->title   =  $details;
        $save->type   =  $title;
        $save->trx   =  $trx;

        //	$save->charge  =  0  ;
        //$save->post_balance  =    $balance->bonus_balance   ;


        $save->save();
    }

    public function  profit_share($total_profit, $type)
    {
        //first get the user detail

        //referral  bonus   ...  one time bonus



        try {
            //alter  user table.
            DB::statement("ALTER TABLE users
ADD bonus_balance decimal(28,8)  DEFAULT 0.00000000");
            //create c


            DB::statement("CREATE TABLE referral_bonuses(
    id int AUTO_INCREMENT  PRIMARY KEY,
    created_at varchar(600)  DEFAULT NULL,
    updated_at varchar(600)  DEFAULT NULL,
	ref_by int DEFAULT NULL  ,
	who_id  int  DEFAULT NULL  ,
	amount decimal(28,8) DEFAULT 0.00000000

  )");
        } catch (\Exception $e) {

            //echo($e->getMessage());
        }
        //now  add  the referral .
        $who  = auth()->user();

        //referral  bonus
        $referral_bonus  = role::where('name',  'Referral')->first();
        //check  if  it exist before in  referral_bonus .

        $check  =  referral_bonus::where([['ref_by',   $who->ref_by], ['who_id',  $who->id]])->count();

        //if($check  ==  0){
        //add profit and save referral  benefit .
        $referral_amount  =  $total_profit  * ($referral_bonus->profit  /  100);
        $add_to_bal  =  User::find($who->ref_by);

        if (isset($add_to_bal->id)) {
            $total  =  $add_to_bal->bonus_balance  + $referral_amount;
            $add_to_bal->bonus_balance  =  $total;
            $add_to_bal->save();

            //add to referral_bonus table
            /*
			$add_ref_tab  =  new   referral_bonus()  ;
			$add_ref_tab->ref_by  = $who->ref_by  ;
			$add_ref_tab->who_id  =$who->id ;
			$add_ref_tab->amount  =  $referral_amount  ;
			$add_ref_tab->save();  */

            //now save transaction
            $details  = "$type Bonus From : $who->email ( " . $who->getFullnameAttribute() . " )  ";

            $ref_del   =  User::find($who->ref_by);

            $this->bonus_transaction($referral_amount,  "+", "$type Bonus", $details, $ref_del,   $total_profit, $referral_bonus->profit, $type);
        }
        //}
        //end  of referral bonus .

        //manager bonus
        if ($who->manager !=  0) {
            //now check for manager and add the  necessary  bonus  okay .
            $manager  =  User::find($who->manager);

            if (isset($manager->id)) {
                //now add the benefit  okay
                $manager_bonus  = role::where('name',  'Manager')->first();

                if (isset($manager_bonus->id)) {
                    $manager_amount  =  $total_profit  * ($manager_bonus->profit / 100);

                    $totalm =  $manager->bonus_balance  + $manager_amount;
                    $manager->bonus_balance  =   $totalm;

                    $manager->save();
                    //save transaction
                    $details  = "$type Bonus From : $who->email ( " . $who->getFullnameAttribute() . " )  ";
                    $this->bonus_transaction($manager_amount,  "+", "Manager $type Bonus", $details, $manager, $total_profit, $manager_bonus->profit, $type);
                }
            }
        } //end manager  bonus




        //super manager bonus
        //This is the first condition of super manager
        if ($who->manager ==  0  &&  $who->super_manager  !=  0) {
            //now check for manager and add the  necessary  bonus  okay .



            //now check if  this manager is  under any super manager
            if ($who->super_manager !=  0) {
                //now add the benefit  okay
                $super_manager_bonus  = role::where('name',  'Super Manager')->first();
                $super_manager  =  User::find($who->super_manager);

                if (isset($super_manager_bonus->id)  && isset($super_manager->id)) {

                    $super_manager_amount  =  $total_profit  * ($super_manager_bonus->profit / 100);



                    $totals = $super_manager->bonus_balance  + $super_manager_amount;
                    $super_manager->bonus_balance  =   $totals;

                    $super_manager->save();
                    //save transaction
                    $details  = "From  : $who->email ( " . $who->getFullnameAttribute() . " ) ";

                    $this->bonus_transaction($super_manager_amount,  "+", "Referral  $type  Bonus", $details, $super_manager, $total_profit, $super_manager_bonus->profit, $type);
                }
            }
        }

        //the second condition of super manager   ..

        if ($who->manager !=  0) {
            //now check for manager and add the  necessary  bonus  okay .
            $manager  =  User::find($who->manager);

            if (isset($manager->id)) {
                //now check if  this manager is  under any super manager
                if ($manager->super_manager !=  0) {
                    //now add the benefit  okay
                    $super_manager_bonus  = role::where('name',  'Super Manager')->first();
                    $super_manager  =  User::find($manager->super_manager);

                    if (isset($super_manager_bonus->id)  && isset($super_manager->id)) {

                        $super_manager_amount  =  $total_profit  * ($super_manager_bonus->profit / 100);



                        $totals = $super_manager->bonus_balance  + $super_manager_amount;
                        $super_manager->bonus_balance  =   $totals;

                        $super_manager->save();
                        //save transaction
                        $details  = "From  : $who->email ( " . $who->getFullnameAttribute() . " )  Under  " . $manager->getFullnameAttribute() . "  ($manager->email)  Manager  ";
                        $this->bonus_transaction($super_manager_amount,  "+", "Manager  $type  Bonus", $details, $super_manager, $total_profit, $super_manager_bonus->profit, $type);
                    }
                }
            }
        }




        //developer  bonus .

        //just get  the first developer

        $dev  =  User::where('role',  'Developer')->first();
        if (isset($dev->id)) {

            //now the bonus %

            $dev_bonus  =  role::where('name',  'Developer')->first();
            if (isset($dev_bonus->profit)) {

                //now get it
                $dev_amount  =  $total_profit  * ($dev_bonus->profit / 100);

                $totald  =  $dev->bonus_balance +  $dev_amount;
                $dev->bonus_balance  =       $totald;

                $dev->save();

                //now save transaction .

                $details  = "From  : $who->email ( " . $who->getFullnameAttribute() . " ) ";
                $this->bonus_transaction($dev_amount,  "+", "Developer $type Bonus", $details, $dev, $total_profit,  $dev_bonus->profit,  $type);
            }
        }



        //company profit
        $dev  =  User::where('role',  'Company')->first();
        $admin  =  User::where('role',  'Company')->first();
        if (isset($admin->id)) {
            //now the bonus %
            $dev_bonus  =  role::where('name',  'Company')->first();
            if (isset($dev_bonus->profit)) {
                //now get it
                $dev_amount  =  $total_profit  * ($dev_bonus->profit / 100);

                $totald  =  $dev->bonus_balance +  $dev_amount;
                $dev->bonus_balance  =       $totald;

                $dev->save();

                //now save transaction .

                $details  = "From  : $who->email ( " . $who->getFullnameAttribute() . " ) ";
                $this->bonus_transaction($dev_amount,  "+", "Company $type Bonus", $details, $dev, $total_profit,  $dev_bonus->profit,  $type);
            }
        }




        ///add admin  profit
        $dev  =  User::where('role',  'Admin')->first();
        $admin  =  User::where('role',  'Admin')->first();
        if (isset($admin->id)) {
            //now the bonus %
            $dev_bonus  =  role::where('name',  'Admin')->first();
            if (isset($dev_bonus->profit)) {
                //now get it
                $dev_amount  =  $total_profit  * ($dev_bonus->profit / 100);

                $totald  =  $dev->bonus_balance +  $dev_amount;
                $dev->bonus_balance  =       $totald;

                $dev->save();

                //now save transaction .

                $details  = "From  : $who->email ( " . $who->getFullnameAttribute() . " ) ";
                $this->bonus_transaction($dev_amount,  "+", "Admin $type Bonus", $details, $dev, $total_profit,  $dev_bonus->profit,  $type);
            }
        }
    }


    public function check_balance(Request $request,  $user)
    {
        //   check  balance before continuity


        //check the ba




    }


    public function  transaction(Request $request,  $trx_type, $trx, $details)
    {
        //the list of the transaction .
        $user =  auth()->user();

        $balance  =  User::find($user->id);
        $save  = new  Transaction();
        $save->user_id  =  $user->id;
        $save->amount  =  $request->amount;
        $save->charge  =  0;
        $save->post_balance  =    $balance->balance;
        $save->trx_type  =  $trx_type;
        $save->trx =  $trx;
        $save->details  =  $details;
        $save->save();
    }


    public function  bonus_transfer(Request $request)
    {

        //sned the amount

        $user  = auth()->user();

        //get the total balance of bonus

        if ($request->amount  <=  $user->bonus_balance    &&  $request->amount  !=  0) {

            $save  = User::find($user->id);
            $total  =  $user->balance +  $request->amount;
            $save->balance  =   $total;

            $remain  =   $user->bonus_balance  -  $request->amount;

            $save->bonus_balance =     $remain;


            $save->save();
            //save the transaction

            //$request['amount']  = $deduct_amount ;

            $trx = "Success";
            $detail = "Bonus Deposited To Wallet";


            $this->transaction($request,  "+", $trx, $detail);





            $notify[] = ['success', 'Successful'];
            return redirect('/user/transactions')->withNotify($notify);
        }

        $notify[] = ['error', 'Amount Greater than the Balance'];
        return back()->withNotify($notify);
    }

    //test monify  payment  here.

    public  function airtime(Request $request)
    {
        //purchase  the airtime  code  now ..
        //check balance  okay
        $user  = auth()->user();
        if ($request->amount  > $user->balance) {
            //	$all = array('error'  =>  'Insulfficient balance');
            $notify[] = ['error', 'Insulfficient balance'];
            return back()->withNotify($notify);
        }
        $gen  = GeneralSetting::first();
        $key = $gen->vtukey;
        $url = 'https://www.smartspeedtelecom.com/api/topup/';
        $data = array(
            'network' => $request->network,
            'amount' => $request->amount,
            'mobile_number' => $request->phone,
            'Ported_number' => false,
            'airtime_type' => 'VTU'
        );

        $headers = array(
            "Authorization: Token  $key",
            'Content-Type: application/json',
        );

        $ch = curl_init($url);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);  // Disable SSL verification

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            echo 'Error: ' . curl_error($ch);
        }

        curl_close($ch);

        // Handle the response as needed
        echo $response;

        $respx = json_decode($response);



        /*    {"id":645385,"airtime_type":"VTU","network":1,
            "ident":"20240122225621301957961678a666ce7a-f1e0-4f25-84ba-0b3d123093f2",
            "paid_amount":"9.8","mobile_number":"08160492362","amount":"10",
            "plan_amount":"10","plan_network":"MTN","balance_before":"74.0",
            "balance_after":"64.2","Status":"successful",
            "create_date":"2024-01-22T22:56:23.970581","Ported_number":true} */

        if (isset($respx->error[0])) {

            $notify[] = ['error', 'Error Occur Please Try Again'];
            return back()->withNotify($notify);

            //return(curl_error($curl))  ;

            //return back()->with($all);
            } else {

                if (isset($respx->Status)) {

            if ($respx->Status == "failed") {
                //please return the error .
                $notify[] = ['error', 'Mimimum airtime you can purchase is 100 naira'];
                return back()->withNotify($notify);
            } else if ($respx->Status == "successful") {

      //now deduct from  current balance before transaction  updatae  .

                //now check  the  code okay  before deduction  and saving  okay  .

                //$output['code'] ==  200
                $deduct  = User::find($user->id);
                $network  = network::where('network_code', $request->network)->first();
                $airtime  = airtime::where('network',  $network->network_code)->first();
                //get the airtime  discount  okay.
                //for resseller and for normal user
                if ($user->resseller ==  1) {
                    $discount_per  = 100  - $airtime->ressller_discount;
                } else {
                    $discount_per  = 100  - $airtime->discount;
                }

                $actual_per  = 100 -  $airtime->actual_discount;

                $actual_amount  =  $request->amount  *  ($actual_per / 100);
                $profit  =  $request->amount   -   $actual_amount;

                $deduct_amount  =   $request->amount  * ($discount_per / 100);

                $deduct->balance  =  $user->balance -  $deduct_amount;
                //now you need to get things done here.

                $deduct->save();

                $trx = "Success";
                $detail = "Airtime (NGN$request->amount) |$network->network_name|$request->phone";

                $request['amount']  = $deduct_amount;

                $this->transaction($request,  "-", $trx, $detail);

                //add bonus for diff role  .
                /*  $total_profit  = $profit;
                $this->profit_share($total_profit, "Airtime"); */

                $notify[] = ['success', 'Successful'];
                return redirect('/user/transactions')->withNotify($notify);
            } else {

                $deduct  = User::find($user->id);
                $network  = network::where('network_code', $request->network)->first();
                $airtime  = airtime::where('network',  $network->network_code)->first();
                //get the airtime  discount  okay.

                //for resseller and for normal user
                if ($user->resseller ==  1) {
                    $discount_per  = 100  - $airtime->ressller_discount;
                } else {
                    $discount_per  = 100  - $airtime->discount;
                }

                $actual_per  = 100 -  $airtime->actual_discount;
                $actual_amount  =  $request->amount  *  ($actual_per / 100);
                $profit  =  $request->amount   -   $actual_amount;
                $deduct_amount  =   $request->amount  * ($discount_per / 100);
                $deduct->balance  =  $user->balance -  $deduct_amount;
                //now you need to get things done here.
                $deduct->save();
                $trx = "Pending";
                $detail = "Airtime (NGN$request->amount) | $network->network_name | $request->phone | Pending";
                $request['amount']  = $deduct_amount;

                $this->transaction($request,  "-", $trx, $detail);

                //add bonus for diff role  .
                /*     $total_profit  = $profit;
                $this->profit_share($total_profit, "Airtime"); */

                $notify[] = ['error', 'Pending Oops an error occured'];
                return redirect('/user/transactions')->withNotify($notify);
            }

        }
            //return $response;


        }


        $notify[] = ['error', 'Error Occur Please Try Again'];
        return back()->withNotify($notify);
    }



    public  function data(Request $request)
    {

        //purchase  the airtime  code  now ..
        //check balance  okay
        $user  = auth()->user();
        //get network  code
        $network_code  =  network::find($request->network);
        //get the amount of  data  okay.
        //return($request->order);
        $data_d  =  data::where([['dataplan_id',  $request->order],  ['network',  $request->network]])->first();

        //get the profit from here
        //$profit  = $data_d->default_price     -  $data_d->actual_amount  ;
        if ($user->resseller ==  1) {

            $profit  = $data_d->reseller_price         -  $data_d->actual_price;
            $deduct_price  =      $data_d->reseller_price;
        } else {

            $profit  = $data_d->default_price     -  $data_d->actual_price;
            $deduct_price  =      $data_d->default_price;
        }
        if ($deduct_price  > $user->balance) {
            //	$all = array('error'  =>  'Insulfficient balance');
            $notify[] = ['error', 'Insulfficient balance'];
            return back()->withNotify($notify);
        }
        $gen  = GeneralSetting::first();
        $curl = curl_init();
        // $key  =  	base64_encode("$gen->vtukey:$gen->vtuuserid") ;
        $key = $gen->vtukey;
        $url = 'https://www.smartspeedtelecom.com/api/data/';
        $data = array(
            'network' => $network_code->network_code,
            'mobile_number' => $request->phone,
            'plan' =>  $request->order,
            'Ported_number' => true
        );

        $headers = array(
            "Authorization: Token $key",
            'Content-Type: application/json',
        );

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);  // Disable SSL verification
        $response = curl_exec($ch);
        if (curl_errno($ch)) {
            echo 'Error: ' . curl_error($ch);
        }
        curl_close($ch);
        // Handle the response as needed
        /*   echo $response;
        echo("<br/> <br/>"); */

        $respx = json_decode($response);
        /*      {"id":5876264,"network":1,"ident":"Data5e0fc71dc-ce7","balance_before":"334.0","balance_after":"204.0",
            "mobile_number":"08160492362","plan":6,
            "Status":"successful",
            "api_response":"Dear Customer, You have successfully shared 500MB Data to 2348160492362. Your SME data balance is 45417.52GB expires 27/02/2024. Thankyou",
            "plan_network":"MTN","plan_name":"500.0MB","plan_amount":"130.0","create_date":"2024-01-22T16:43:24.911084","Ported_number":true} */
        //die();

        //print_r ($respx->error[0] )   ;



        if (isset($respx->error[0])) {

            $notify[] = ['error', 'Error Occur Please Try Again'];
            return back()->withNotify($notify);
            //return(curl_error($curl))  ;
            //return back()->with($all);
        } else {

            if (isset($respx->Status)) {

                if ($respx->Status == "successful") {

                    $deduct  = User::find($user->id);
                    //return  ($data_d->reseller_price  );
                    if ($user->resseller  ==  1) {
                        $deduct->balance  =  $user->balance -  $deduct_price;
                    } else {
                        $deduct->balance  =  $user->balance - $deduct_price;
                    }
                    $deduct->save();
                    $network  = network::find($request->network);
                    $trx = "Success";
                    $detail = "$data_d->plan |$data_d->plan_network |  Data  |   $deduct_price NGN   | $request->phone | Successful";

                    $request->amount     =    $deduct_price;   // $data_d->default_price ;
                    $this->transaction($request,  "-", $trx, $detail);
                    /* $total_profit  = $profit;
                $this->profit_share($total_profit, "Data");
                        */
                    $notify[] = ['success', 'Successful'];
                    return redirect('/user/transactions')->withNotify($notify);
                } else if ($respx->Status == "failed") {
                    $network  = network::find($request->network);
                    $trx = "Failed";
                    $detail = "Data | $network->network_name | $data_d->plan | $request->phone | Failed";
                    $request->amount = $deduct_price;   // $data_d->default_price ;
                    $this->transaction($request,  "-", $trx, $detail);
                    $total_profit  = $profit;
                    $this->profit_share($total_profit, "Data");
                    $notify[] = ['error', 'Failed, Transaction not successful'];
                    return redirect('/user/transactions')->withNotify($notify);
                    // 		if($respx->status ==  "failed"){
                    // 			//please return the error .
                    // 		$notify[] = ['error', 'Failed Please Try Again'];
                    //         return back()->withNotify($notify);

                    // 			}


                } else {

                    $deduct  = User::find($user->id);

                    //return  ($data_d->reseller_price  );

                    if ($user->resseller  ==  1) {
                        $deduct->balance  =  $user->balance -  $deduct_price;
                    } else {
                        $deduct->balance  =  $user->balance - $deduct_price;
                    }
                    $deduct->save();
                    $network  = network::find($request->network);
                    $trx = "Pending";
                    $detail = "Data | $network->network_name | $data_d->plan | $request->phone | Pending";
                    $request->amount     =    $deduct_price;   // $data_d->default_price ;
                    $this->transaction($request,  "-", $trx, $detail);
                    $total_profit  = $profit;
                    // $this->profit_share($total_profit, "Data");
                    $notify[] = ['error', 'Pending Oops something went awry'];
                    return redirect('/user/transactions')->withNotify($notify);
                }
            } else {

                $notify[] = ['error', 'Transaction Failed Try Again'];
                return back()->withNotify($notify);
            }
        }



        $notify[] = ['error', 'Error Occur Please Try Again'];
        return back()->withNotify($notify);
    }



    //data code


}
