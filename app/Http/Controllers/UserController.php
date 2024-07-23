<?php

namespace App\Http\Controllers;

use App\Lib\GoogleAuthenticator;
use App\Models\AdminNotification;
use App\Models\CommissionLog;
use App\Models\bank_account;
use App\Models\Deposit;
use App\Models\GatewayCurrency;
use App\Models\GeneralSetting;
use App\Models\Transaction;
use App\Models\User;
use App\Models\UserDps;
use App\Models\UserFdr;
use App\Models\UserLoan;
use App\Models\WithdrawMethod;
use App\Models\Withdrawal;
use App\Rules\FileTypeValidate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    public function __construct()
    {
        $this->activeTemplate = activeTemplate();
    }

    public function home(Request $request)
    {
        $pageTitle = 'Dashboard';
        $user = auth()->user();
        $widget['total_deposit']    = Deposit::where('user_id', $user->id)
                                        ->where('status', 1)
                                        ->sum('amount');
        $widget['total_fdr']        = UserFdr::where('user_id', $user->id)->count();
        $widget['total_withdraw']   = Withdrawal::approved()->where('user_id', $user->id)->sum('amount');
        $widget['total_loan']       = UserLoan::approved()->where('user_id', $user->id)->count();
        $widget['total_dps']        = UserDps::where('user_id', $user->id)->count();
        $widget['total_trx']        = Transaction::where('user_id', $user->id)->count();

        $credits                  = Transaction::where('user_id', $user->id)
                                    ->where('trx_type', '+')
                                    ->latest()
                                    ->limit(5)
                                    ->get();

        $debits                  = Transaction::where('user_id', $user->id)
                                    ->where('trx_type', '-')
                                    ->latest()
                                    ->limit(5)
                                    ->get();

		//save the account  here .

		//check existence  of bank account...
		$bank  =  bank_account::where('user_id'  , $user->id  )->first()  ;
			$new_account  =  new  monify_controller()  ;

		if(!isset($bank->id)){

		$accout_output  =  $new_account->getaccount();
		}else{
			//check transaction
	$output  =	$new_account->transaction_trace( $request, $bank->accountReference	);
			//save the transaction into the database.
			//return($output);
		}
		//check transaction   here  okay !!
		//return($accout_output);
    $emptyMessage = 'No Data Found';
        return view($this->activeTemplate . 'user.dashboard', compact('pageTitle', 'user', 'widget', 'credits', 'debits','emptyMessage'));
    }

    public function profile()
    {
        $pageTitle = "Profile";
        $user = Auth::user();
        return view($this->activeTemplate . 'user.profile_setting', compact('pageTitle', 'user'));
    }

    public function submitProfile(Request $request)
    {
        $request->validate([
            'firstname' => 'required|string|max:50',
            'lastname' => 'required|string|max:50',

            'image' => ['image', new FileTypeValidate(['jpg', 'jpeg', 'png'])]
        ], [
            'firstname.required' => 'First name field is required',
            'lastname.required' => 'Last name field is required'
        ]);

        $user = Auth::user();

        $in['firstname'] = $request->firstname;
        $in['lastname'] = $request->lastname;

        if ($request->hasFile('image')) {
            $location = imagePath()['profile']['user']['path'];
            $size = imagePath()['profile']['user']['size'];
            $filename = uploadImage($request->image, $location, $size, $user->image);
            $in['image'] = $filename;
        }
        $user->fill($in)->save();
        $notify[] = ['success', 'Profile updated successfully.'];
        return back()->withNotify($notify);
    }

    public function changePassword()
    {
        $pageTitle = 'Change password';
        return view($this->activeTemplate . 'user.password', compact('pageTitle'));
    }

    public function submitPassword(Request $request)
    {

        $password_validation = Password::min(6);
        $general = GeneralSetting::first();
        if ($general->secure_password) {
            $password_validation = $password_validation->mixedCase()->numbers()->symbols()->uncompromised();
        }

        $this->validate($request, [
            'current_password' => 'required',
            'password' => ['required', 'confirmed', $password_validation]
        ]);


        try {
            $user = auth()->user();
            if (Hash::check($request->current_password, $user->password)) {
                $password = Hash::make($request->password);
                $user->password = $password;
                $user->save();
                $notify[] = ['success', 'Password changes successfully.'];
                return back()->withNotify($notify);
            } else {
                $notify[] = ['error', 'The password doesn\'t match!'];
                return back()->withNotify($notify);
            }
        } catch (\PDOException $e) {
            $notify[] = ['error', $e->getMessage()];
            return back()->withNotify($notify);
        }
    }

    /*
     * Deposit History
     */
    public function depositHistory(Request $request)
    {
        $pageTitle = 'My Deposits';
        $emptyMessage = 'No deposit yet';

        $gatewayCurrency = GatewayCurrency::whereHas('method', function ($gate) {
            $gate->where('status', 1);
        })->with('method')->orderby('method_code')->get();

        $logs = auth()->user()->deposits()->with(['gateway'])->orderBy('id', 'desc')->paginate(getPaginate());
		//update transaction  detail okay .
		$user  =  auth()->user()  ;
			$bank  =  bank_account::where('user_id'  , $user->id  )->first()  ;
			$new_account  =  new  monify_controller()  ;

		if(!isset($bank->id)){

		$accout_output  =  $new_account->getaccount();
		}else{
			//check transaction
	$output  =	$new_account->transaction_trace( $request, $bank->accountReference	);
			//save the transaction into the database.
			//return($output);
		}


        return view($this->activeTemplate . 'user.deposit.log', compact('pageTitle', 'emptyMessage', 'logs', 'gatewayCurrency'));
    }

    /*
     * Withdraw Operation
     */

    public function withdrawStore(Request $request)
    {
        $this->validate($request, [
            'method_code' => 'required',
            'amount' => 'required|numeric'
        ]);

        $method = WithdrawMethod::where('id', $request->method_code)->where('status', 1)->firstOrFail();
        $user = auth()->user();
        if ($request->amount < $method->min_limit) {
            $notify[] = ['error', 'Your requested amount is smaller than minimum amount.'];
            return back()->withNotify($notify);
        }
        if ($request->amount > $method->max_limit) {
            $notify[] = ['error', 'Your requested amount is larger than maximum amount.'];
            return back()->withNotify($notify);
        }

        if ($request->amount > $user->balance) {
            $notify[] = ['error', 'You do not have sufficient balance for withdraw.'];
            return back()->withNotify($notify);
        }


        $charge = $method->fixed_charge + ($request->amount * $method->percent_charge / 100);
        $afterCharge = $request->amount - $charge;
        $finalAmount = $afterCharge * $method->rate;

        $withdraw = new Withdrawal();
        $withdraw->method_id = $method->id; // wallet method ID
        $withdraw->user_id = $user->id;
        $withdraw->amount = $request->amount;
        $withdraw->currency = $method->currency;
        $withdraw->rate = $method->rate;
        $withdraw->charge = $charge;
        $withdraw->final_amount = $finalAmount;
        $withdraw->after_charge = $afterCharge;
        $withdraw->trx = getTrx();
        $withdraw->save();
        session()->put('wtrx', $withdraw->trx);
        return redirect()->route('user.withdraw.preview');
    }

    public function withdrawPreview()
    {
        $withdraw = Withdrawal::with('method', 'user')->where('trx', session()->get('wtrx'))->where('status', 0)->orderBy('id', 'desc')->first();


        $pageTitle = 'Withdraw Preview';
        return view($this->activeTemplate . 'user.withdraw.preview', compact('pageTitle', 'withdraw'));
    }


    public function withdrawSubmit(Request $request)
    {
        $general    = GeneralSetting::first();
        $withdraw   = Withdrawal::with('method', 'user')->where('trx', session()->get('wtrx'))->where('status', 0)->orderBy('id', 'desc')->firstOrFail();

        $rules = [];
        $inputField = [];
        if ($withdraw->method->user_data != null) {
            foreach ($withdraw->method->user_data as $key => $cus) {
                $rules[$key] = [$cus->validation];
                if ($cus->type == 'file') {
                    array_push($rules[$key], 'image');
                    array_push($rules[$key], new FileTypeValidate(['jpg', 'jpeg', 'png']));
                    array_push($rules[$key], 'max:2048');
                }
                if ($cus->type == 'text') {
                    array_push($rules[$key], 'max:191');
                }
                if ($cus->type == 'textarea') {
                    array_push($rules[$key], 'max:300');
                }
                $inputField[] = $key;
            }
        }

        $this->validate($request, $rules);

        $user = auth()->user();

        if ($withdraw->amount > $user->balance) {
            $notify[] = ['error', 'Your request amount is larger then your current balance.'];
            return back()->withNotify($notify);
        }

        $directory = date("Y") . "/" . date("m") . "/" . date("d");
        $path = imagePath()['verify']['withdraw']['path'] . '/' . $directory;
        $collection = collect($request);
        $reqField = [];
        if ($withdraw->method->user_data != null) {
            foreach ($collection as $k => $v) {
                foreach ($withdraw->method->user_data as $inKey => $inVal) {
                    if ($k != $inKey) {
                        continue;
                    } else {
                        if ($inVal->type == 'file') {
                            if ($request->hasFile($inKey)) {
                                try {
                                    $reqField[$inKey] = [
                                        'field_name' => $directory . '/' . uploadImage($request[$inKey], $path),
                                        'type' => $inVal->type,
                                    ];
                                } catch (\Exception $exp) {
                                    $notify[] = ['error', 'Could not upload your ' . $request[$inKey]];
                                    return back()->withNotify($notify)->withInput();
                                }
                            }
                        } else {
                            $reqField[$inKey] = $v;
                            $reqField[$inKey] = [
                                'field_name' => $v,
                                'type' => $inVal->type,
                            ];
                        }
                    }
                }
            }
            $withdraw['withdraw_information'] = $reqField;
        } else {
            $withdraw['withdraw_information'] = null;
        }


        $withdraw->status = 2;
        $withdraw->save();
        $user->balance  -=  $withdraw->amount;
        $user->save();



        $transaction = new Transaction();
        $transaction->user_id = $withdraw->user_id;
        $transaction->amount = $withdraw->amount;
        $transaction->post_balance = $user->balance;
        $transaction->charge = $withdraw->charge;
        $transaction->trx_type = '-';
        $transaction->details = 'Withdrawn Via ' . $withdraw->method->name;
        $transaction->trx =  $withdraw->trx;
        $transaction->save();

        $adminNotification = new AdminNotification();
        $adminNotification->user_id = $user->id;
        $adminNotification->title = 'New withdraw request from ' . $user->username;
        $adminNotification->click_url = urlPath('admin.withdraw.details', $withdraw->id);
        $adminNotification->save();

        session()->forget('wtrx');

        notify($user, 'WITHDRAW_REQUEST', [
            'method_name' => $withdraw->method->name,
            'method_currency' => $withdraw->currency,
            'method_amount' => showAmount($withdraw->final_amount),
            'amount' => showAmount($withdraw->amount),
            'charge' => showAmount($withdraw->charge),
            'currency' => $general->cur_text,
            'rate' => showAmount($withdraw->rate),
            'trx' => $withdraw->trx,
            'post_balance' => showAmount($user->balance),
            'delay' => $withdraw->method->delay
        ]);



        $notify[] = ['success', 'Withdraw request sent successfully'];
        return redirect()->route('user.withdraw.history')->withNotify($notify);
    }

    public function withdrawLog()
    {
        $pageTitle = "My Withdrawals";
        $withdraws = Withdrawal::where('user_id', Auth::id())->where('status', '!=', 0)->with('method')->orderBy('id', 'desc')->paginate(getPaginate());
        $emptyMessage = "No Withdrawals Yet";
        $withdrawMethod = WithdrawMethod::where('status', 1)->get();
        return view($this->activeTemplate . 'user.withdraw.log', compact('pageTitle', 'withdraws', 'emptyMessage', 'withdrawMethod'));
    }



    public function show2faForm()
    {
        $general = GeneralSetting::first();
        $ga = new GoogleAuthenticator();
        $user = auth()->user();
        $secret = $ga->createSecret();
        $qrCodeUrl = $ga->getQRCodeGoogleUrl($user->username . '@' . $general->sitename, $secret);
        $pageTitle = '2FA Security';
        return view($this->activeTemplate . 'user.twofactor', compact('pageTitle', 'secret', 'qrCodeUrl'));
    }

    public function create2fa(Request $request)
    {
        $user = auth()->user();
        $this->validate($request, [
            'key' => 'required',
            'code' => 'required',
        ]);
        $response = verifyG2fa($user, $request->code, $request->key);
        if ($response) {
            $user->tsc = $request->key;
            $user->ts = 1;
            $user->save();
            $userAgent = getIpInfo();
            $osBrowser = osBrowser();
            notify($user, '2FA_ENABLE', [
                'operating_system' => @$osBrowser['os_platform'],
                'browser' => @$osBrowser['browser'],
                'ip' => @$userAgent['ip'],
                'time' => @$userAgent['time']
            ]);
            $notify[] = ['success', 'Google authenticator enabled successfully'];
            return back()->withNotify($notify);
        } else {
            $notify[] = ['error', 'Wrong verification code'];
            return back()->withNotify($notify);
        }
    }


    public function disable2fa(Request $request)
    {
        $this->validate($request, [
            'code' => 'required',
        ]);

        $user = auth()->user();
        $response = verifyG2fa($user, $request->code);
        if ($response) {
            $user->tsc = null;
            $user->ts = 0;
            $user->save();
            $userAgent = getIpInfo();
            $osBrowser = osBrowser();
            notify($user, '2FA_DISABLE', [
                'operating_system' => @$osBrowser['os_platform'],
                'browser' => @$osBrowser['browser'],
                'ip' => @$userAgent['ip'],
                'time' => @$userAgent['time']
            ]);
            $notify[] = ['success', 'Two factor authenticator disable successfully'];
        } else {
            $notify[] = ['error', 'Wrong verification code'];
        }
        return back()->withNotify($notify);
    }



    public function transactions( $var = null)
    {
        $histories      = Transaction::where('user_id', Auth::user()->id)->latest()->paginate(getPaginate());
        $pageTitle      = 'My Transactions';
        $emptyMessage  = 'No Transaction Yet';
        return view($this->activeTemplate . 'user.transactions', compact('pageTitle', 'histories', 'emptyMessage'));
    }




    public function checkAccountNumber($account)
    {
        $user = User::active()->where('account_number', $account)->first();

        if(!$user){
            return response()->json(['error'=> true, 'message'=>'No such account found']);
        }
        $data = [
            'name'  => $user->username,
        ];

        return response()->json(['error'=> false, 'data'=>$data]);
    }



    public function referredUsers()
    {
        $pageTitle      = "My referred Users";
        $emptyMessage   = "No referee found";
        $referees       = User::where('ref_by', auth()->id())->paginate(getPaginate());

        return view($this->activeTemplate. 'user.referral.index', compact('pageTitle','emptyMessage','referees'));
    }

    public function my_downlines()
    {
        $pageTitle      = "My Downlines";
        $emptyMessage   = "No Downlines found";


		//check if  is manager  or super manager.
		try{
$user  = auth()->user()  ;

		if($user->role == 'Manager'  ||  $user->role == 'Super Manager' ){

		     $referees       = User::where('manager', auth()->id())->orwhere('super_manager', auth()->id())->paginate(getPaginate());
		}

			/**
		if($user->role == 'Super Manager'){

		     $referees       = User::where('super_manager', auth()->id())->paginate(getPaginate());
		}   ***/




        return view($this->activeTemplate. 'user.referral.downline', compact('pageTitle','emptyMessage','referees'));
		}catch(\Exception $e){



		}
    }




	    public function downline_transactions($id)
    {

$user  = auth()->user()  ;

		if($user->role == 'Manager'  ||  $user->role == 'Super Manager' ){





		$downline  = User::find($id);

        $histories      = Transaction::where('user_id', $id)->latest()->paginate(getPaginate());
        $pageTitle      = " $downline->fullname  Transactions ";
        $emptyMessage  = 'No Transaction  Yet';
        return view($this->activeTemplate . 'user.transactions', compact('pageTitle', 'histories', 'emptyMessage'));


				}
    }




	public function  downline_deposit(Request  $request){


		//now deposit for the user from the manager wallet
		$user  = auth()->user()  ;

		if($user->role == 'Manager'  ||  $user->role == 'Super Manager' ){

if($user->balance  <  $request->amount   &&  $request->amount != 0   ){

					    $notify[] = ['error', 'Balance Not Sufficient' ];
                   return back()->withNotify($notify);


			}

if($request->amount  <  0 ){

					    $notify[] = ['error', 'Amount Less than Zero' ];
                   return back()->withNotify($notify);


			}





	$downline  =	User::where(  [  [ 'id' , $request->userid]]  )->first();

			$downline_balance  =  $downline->balance ;
$downline->balance  =  $request->amount  +  $downline_balance ;

			$downline->save()  ;


			//deduct from manager   ..

			$manager  =  User::find($user->id);
			$manager_balance  =  $manager->balance  ;
			$manager->balance  =  $manager_balance -   $request->amount  ;

			$manager->save();

			//manager transaction  history

						$trx = "Success";
	$detail = "Deposit To $downline->fullname";
 	$this->transaction($user->id   ,  $request->amount     ,  "-"   , $trx   , $detail    ) ;




			//user transaction  history .
			$trx = "Success";
	$detail = "Deposit From Manager";
$this->transaction($request->userid   ,  $request->amount     ,  "+"   , $trx   , $detail    ) ;

			//deposit history for user



        $data = new Deposit();
        $data->user_id = $request->userid ;
       // $data->method_code = $gate->method_code;
        $data->method_currency = "NGN";
        $data->amount = $request->amount;
        $data->charge = 0;

        $data->final_amo = $downline->balance;
        $data->btc_amo = 0;
        $data->btc_wallet = "";
        $data->trx =$detail;
        //$data->try = 0;
        $data->status = 1;
        $data->save();



					    $notify[] = ['success', 'Successful' ];
                   return back()->withNotify($notify);

		}


		return back()->with('error'  ,"Not Allowed");



	}

		public function  transaction($id    ,  $amount , $trx_type   , $trx   , $details   ){
		//the list of the transaction .


		$balance  =  User::find($id)  ;
		$save  = new  Transaction();
		$save->user_id  =  $id  ;
		$save->amount  =  $amount  ;
		$save->charge  =  0  ;
		$save->post_balance  =    $balance->balance   ;
		$save->trx_type  =  $trx_type ;
		$save->trx =  $trx ;
		$save->details  =  $details  ;
		$save->save()  ;


	}


    public function commissionLogs()
    {
        $pageTitle  = "Deposit Referral Commissions";
        $logs       = CommissionLog::where('to_id', Auth::id())->with('user', 'bywho')->latest()->paginate(getPaginate());
        $emptyMessage = "No commission yet";
        return view($this->activeTemplate. 'user.referral.logs', compact('pageTitle', 'logs','emptyMessage'));
    }

    public function kycVerification()
    {
        $pageTitle  = 'KYC Form';
        return view($this->activeTemplate. 'user.kyc.form', compact('pageTitle'));
    }

    public function kycData()
    {
        $pageTitle  = 'KYC Data';
        if(!auth()->user()->kyc_data){
            return redirect()->route('user.kyc.verify');
        }
        return view($this->activeTemplate. 'user.kyc.data', compact('pageTitle'));
    }

    public function kycFormSubmit (Request $request)
    {
        $general = GeneralSetting::first();
        $validation_rule    = [];

        if($general->kyc_form){
            $userDetails        = [];

            foreach ($general->kyc_form as $item) {
                $field = snakeCase($item->field_name);
                if($item->type == 'textarea'){
                    $validation_rule[$field]  = [$item->validation, 'max:600'];
                }elseif($item->type == 'file'){
                    $validation_rule[$field]  = [$item->validation, new FileTypeValidate(['jpg','jpeg','png'])];
                }else{
                    $validation_rule[$field]  = [$item->validation, 'max:255'];
                }
                $userDetails[$field]['type']    = $item->type;
                $userDetails[$field]['value']   = $request->$field;
            }

            $request->validate($validation_rule);

            $directory = date("Y")."/".date("m")."/".date("d");
            $path = imagePath()['verify']['user_kyc']['path'].'/'.$directory;

            foreach ($userDetails as $key => $item) {

                if($item['type'] == 'file'){
                    try {
                        $userDetails[$key]['value'] =  $directory.'/'.uploadImage($userDetails[$key]['value'], $path);
                    } catch (\Exception $exp) {
                        $notify[] = ['error', 'Could not upload your ' . $key];
                        return back()->withNotify($notify)->withInput();
                    }
                }
            }
        }



        $user           = auth()->user();
        $user->kyc_data = $userDetails;
        $user->save();

        $adminNotification = new AdminNotification();
        $adminNotification->user_id = $user->id;
        $adminNotification->title = 'KYC Form Submitted';
        $adminNotification->click_url = urlPath('admin.users.detail.kyc',$user->id);
        $adminNotification->save();

        $notify[]=['success','KYC form submitted successfully. Please wait for admin approval'];
        return redirect()->route('user.kyc.view')->withNotify($notify);

    }
}
