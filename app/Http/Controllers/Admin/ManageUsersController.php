<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BalanceTransfer;
use App\Models\Deposit;
use App\Models\EmailLog;
use App\Models\Gateway;
use App\Models\GeneralSetting;
use App\Models\Transaction;
use App\Models\User;
use App\Models\UserBeneficiary;
use App\Models\UserDps;
use App\Models\UserFdr;
use App\Models\UserLoan;
use App\Models\WithdrawMethod;
use App\Models\Withdrawal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use DB;


class ManageUsersController extends Controller
{
    public function allUsers()
    {
        $pageTitle = 'Manage Users';
        $emptyMessage = 'No user found';
        $users = User::orderBy('id','desc')->paginate(getPaginate());
        return view('admin.users.list', compact('pageTitle', 'emptyMessage', 'users'));
    }

    public function activeUsers()
    {
        $pageTitle = 'Manage Active Users';
        $emptyMessage = 'No active user found';
        $users = User::active()->orderBy('id','desc')->paginate(getPaginate());
        return view('admin.users.list', compact('pageTitle', 'emptyMessage', 'users'));
    }
	
	    public function managers()
    {
        $pageTitle = 'Manage Managers';
        $emptyMessage = 'No Manager found';
        $users = User::manager()->orderBy('id','desc')->paginate(getPaginate());
        return view('admin.users.list', compact('pageTitle', 'emptyMessage', 'users'));
    }
	
		    public function supermanagers()
    {
        $pageTitle = 'super Managers';
        $emptyMessage = 'No Manager found';
        $users = User::super()->orderBy('id','desc')->paginate(getPaginate());
        return view('admin.users.list', compact('pageTitle', 'emptyMessage', 'users'));
    }
	
	
			    public function normals()
    {
        $pageTitle = 'Normal Users';
        $emptyMessage = 'No User found';
        $users = User::normal()->orderBy('id','desc')->paginate(getPaginate());
        return view('admin.users.list', compact('pageTitle', 'emptyMessage', 'users'));
    }
	
	
			    public function developers()
    {
        $pageTitle = 'Developers';
        $emptyMessage = 'No Developer found';
        $users = User::developer()->orderBy('id','desc')->paginate(getPaginate());
        return view('admin.users.list', compact('pageTitle', 'emptyMessage', 'users'));
    }
	
	
	

    public function bannedUsers()
    {
        $pageTitle = 'Banned Users';
        $emptyMessage = 'No banned user found';
        $users = User::banned()->orderBy('id','desc')->paginate(getPaginate());
        return view('admin.users.list', compact('pageTitle', 'emptyMessage', 'users'));
    }



    public function emailUnverifiedUsers()
    {
        $pageTitle = 'Email Unverified Users';
        $emptyMessage = 'No email unverified user found';
        $users = User::emailUnverified()->orderBy('id','desc')->paginate(getPaginate());
        return view('admin.users.list', compact('pageTitle', 'emptyMessage', 'users'));
    }
    public function emailVerifiedUsers()
    {
        $pageTitle = 'Email Verified Users';
        $emptyMessage = 'No email verified user found';
        $users = User::emailVerified()->orderBy('id','desc')->paginate(getPaginate());
        return view('admin.users.list', compact('pageTitle', 'emptyMessage', 'users'));
    }


    public function smsUnverifiedUsers()
    {
        $pageTitle = 'SMS Unverified Users';
        $emptyMessage = 'No sms unverified user found';
        $users = User::smsUnverified()->orderBy('id','desc')->paginate(getPaginate());
        return view('admin.users.list', compact('pageTitle', 'emptyMessage', 'users'));
    }


    public function smsVerifiedUsers()
    {
        $pageTitle = 'SMS Verified Users';
        $emptyMessage = 'No sms verified user found';
        $users = User::smsVerified()->orderBy('id','desc')->paginate(getPaginate());
        return view('admin.users.list', compact('pageTitle', 'emptyMessage', 'users'));
    }


    public function usersWithBalance()
    {
        $pageTitle = 'Users with balance';
        $emptyMessage = 'No sms verified user found';
        $users = User::where('balance','!=',0)->orderBy('id','desc')->paginate(getPaginate());
        return view('admin.users.list', compact('pageTitle', 'emptyMessage', 'users'));
    }

    public function kycSubmitted()
    {
        $pageTitle      = 'Users with balance';
        $emptyMessage   = 'No sms verified user found';
        $users          = User::where('kyc_data','!=', null)->where('kycv', 0)->orderBy('id','desc')->paginate(getPaginate());
        $kyc            = true;
        return view('admin.users.list', compact('pageTitle', 'emptyMessage', 'users', 'kyc'));
    }


    public function search(Request $request, $scope)
    {
        $search = $request->search;

        $users = User::where(function ($user) use ($search) {
            $user->where('username', 'like', "%$search%")
                ->orWhere('email', 'like', "%$search%")
                ->orWhere('country_code', 'like', "%$search%")
                ->orWhere('account_number', 'like', "%$search%");
        });

        $pageTitle = '';
        if ($scope == 'active') {
            $pageTitle = 'Active ';
            $users = $users->where('status', 1);
        }elseif($scope == 'banned'){
            $pageTitle = 'Banned';
            $users = $users->where('status', 0);
        }elseif($scope == 'emailUnverified'){
            $pageTitle = 'Email Unverified ';
            $users = $users->where('ev', 0);
        }elseif($scope == 'smsUnverified'){
            $pageTitle = 'SMS Unverified ';
            $users = $users->where('sv', 0);
        }elseif($scope == 'withBalance'){
            $pageTitle = 'With Balance ';
            $users = $users->where('balance','!=',0);
        }

        $users = $users->paginate(getPaginate());
        $pageTitle .= 'User Search - ' . $search;
        $emptyMessage = 'No search result found';
        return view('admin.users.list', compact('pageTitle', 'search', 'scope', 'emptyMessage', 'users'));
    }


    public function detail($id)
    {
        $pageTitle          = 'User Detail';
        $user               = User::findOrFail($id);
        $totalDeposit       = Deposit::where('user_id',$user->id)->where('status',1)->sum('amount');
        $totalWithdraw      = Withdrawal::where('user_id',$user->id)->where('status',1)->sum('amount');
        $totalTransaction   = Transaction::where('user_id',$user->id)->count();
        $totalTransfers     = BalanceTransfer::completed()->where('user_id',$user->id)->sum('amount');
        $totalLoan          = UserLoan::approved()->where('user_id',$user->id)->count();
        $totalFdr           = UserFdr::where('user_id',$user->id)->count();
        $totalDps           = UserDps::where('user_id',$user->id)->count();
        $totalBeneficiaries = UserBeneficiary::where('user_id',$user->id)->count();

        $countries          = json_decode(file_get_contents(resource_path('views/partials/country.json')));

        return view('admin.users.detail', compact('pageTitle', 'user','totalDeposit','totalWithdraw','totalTransaction','countries', 'totalTransfers', 'totalLoan', 'totalFdr', 'totalDps', 'totalBeneficiaries'));
    }


    public function loans($id)
    {
        $user       = User::findOrFail($id);
        $pageTitle  = 'All Approved Loans of ' .$user->username;

        if(request()->search){
            $query          = UserLoan::approved()->where('user_id', $id)->where('trx', request()->search);
            $emptyMessage   = 'No Data Found';
        }else{
            $query          = UserLoan::approved()->where('user_id', $id)->latest();
            $emptyMessage   = 'No Loan Yet';
        }

        $loans = $query->with('user', 'plan')->paginate(getPaginate());

        return view('admin.loan.index', compact('pageTitle', 'emptyMessage', 'loans'));
    }

    public function fdr($id)
    {
        $user = User::findOrFail($id);
        $pageTitle      = 'FDR List of ' .$user->username;

        if(request()->search){
            $query          = UserFdr::where('user_id', $id)->where('trx', request()->search);
            $emptyMessage   = 'No Data Found';
        }else{
            $query          = UserFdr::where('user_id', $id)->latest();
            $emptyMessage   = 'No FDR Yet';
        }

        $data = $query->with('user', 'plan')->paginate(getPaginate());

        return view('admin.fdr.index', compact('pageTitle', 'emptyMessage', 'data'));
    }

    public function dps($id)
    {
        $user           = User::findOrFail($id);
        $pageTitle      = 'DPS List of ' .$user->username;

        if(request()->search){
            $query          = UserDps::where('user_id', $id)->where('trx', request()->search);
            $emptyMessage   = 'No Data Found';
        }else{
            $query          = UserDps::where('user_id', $id)->latest();
            $emptyMessage   = 'No DPS Yet';
        }

        $data = $query->with('user', 'plan')->paginate(getPaginate());

        return view('admin.dps.index', compact('pageTitle', 'emptyMessage', 'data'));
    }


    public function beneficiaries($id)
    {
        $user           = User::findOrFail($id);
        $pageTitle      = 'Beneficiaries of ' .$user->username;
        if(request()->search){
            $search     =  request()->search;
            $general    = GeneralSetting::first();

            if($search == $general->sitename){
                $query  = UserBeneficiary::where('user_id', $id)->where('bank_id', 0);
            }else{

                $query  = UserBeneficiary::where('user_id', $id)->where(function($q)use($search){
                    $q->where('account_name', $search)
                    ->orWhere('account_number', $search)
                    ->orWhere('short_name', $search)
                    ->orWhereHas('bank', function($q) use($search){
                        $q->where('name', $search);
                    });
                });
            }

            $emptyMessage   = 'No Data Found';
        }else{
            $query          = UserBeneficiary::where('user_id', $id)->latest();
            $emptyMessage   = 'No Beneficiary Added';
        }

        $beneficiaries = $query->with('user', 'bank')->paginate(getPaginate());

        return view('admin.users.beneficiaries', compact('pageTitle', 'emptyMessage', 'beneficiaries'));
    }


    public function transfers($id)
    {
        $user           = User::findOrFail($id);
        $pageTitle      = 'Money Transfer List of ' .$user->username;
        if(request()->search){
            $query  = BalanceTransfer::where('user_id', $id)->where('trx', request()->search);
            $emptyMessage   = 'No Data Found';
        }else{
            $query          = BalanceTransfer::where('user_id', $id)->latest();
            $emptyMessage   = 'No Money Transfer Yet';
        }

        $transfers = $query->with('user', 'bank')->paginate(getPaginate());

        return view('admin.users.transfers', compact('pageTitle', 'emptyMessage', 'transfers'));
    }


    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

		//add  role  id  okay  
		
		
					try{
						
			DB::statement("ALTER TABLE users
ADD role varchar(300)  DEFAULT 'Referral'");
								
						
	DB::statement("ALTER TABLE users
ADD manager int   DEFAULT 0");
												
	DB::statement("ALTER TABLE users
ADD super_manager int   DEFAULT 0");
						
						

					
					
					}
			catch(\Exception $e){

			//return($e->getMessage());

			}
		
		
		
		
        $countryData = json_decode(file_get_contents(resource_path('views/partials/country.json')));

        $request->validate([
            'firstname' => 'required|max:50',
            'lastname' => 'required|max:50',
            'email' => 'required|email|max:90|unique:users,email,' . $user->id,
           // 'mobile' => 'required|unique:users,mobile,'.$user->id,
            //'country' => 'required',
        ]);
        $countryCode = $request->country;
		
        $user->mobile = $request->mobile;
		
		$user->role  =  $request->role ;
        $user->country_code = $countryCode;
        $user->firstname = $request->firstname;
        $user->lastname = $request->lastname;
        $user->email = $request->email;
        $user->address = [
                            'address' => $request->address,
                            'city' => $request->city,
                            'state' => $request->state,
                            'zip' => $request->zip,
                            'country' => @$countryData->$countryCode->country,
                        ];
        $user->status = $request->status ? 1 : 0;
        $user->kycv = $request->kycv ? 1 : 0;
        $user->ev = $request->ev ? 1 : 0;
        $user->sv = $request->sv ? 1 : 0;
        $user->ts = $request->ts ? 1 : 0;
        $user->tv = $request->tv ? 1 : 0;
		
		$user->resseller  =  $request->resseller  ?  1 : 0 ;
        $user->save();
		//now  update each of the role  okay.  
		if(isset($request->add_user)){
			foreach($request->add_user as  $add){
				//get the  user id  okay  
				$normal_user = User::findOrFail($add);  
				$normal_user->manager   =   $id ;  
				$normal_user->save()  ;
				}
		}
				//now  update each of the role  okay.  
		if(isset($request->add_manager)){
			foreach($request->add_manager as  $add){
				//get the  user id  okay  
				$normal_user = User::findOrFail($add);  
				$normal_user->super_manager   =   $id ;  
				$normal_user->save()  ;
				}
		}
		
		

        $notify[] = ['success', 'User detail has been updated'];
        return redirect()->back()->withNotify($notify);
    }

    public function addSubBalance(Request $request, $id)
    {
        $request->validate(['amount' => 'required|numeric|gt:0']);

        $user = User::findOrFail($id);
        $amount = $request->amount;
        $general = GeneralSetting::first(['cur_text','cur_sym']);
        $trx = getTrx();

        if ($request->act) {
            $user->balance += $amount;
            $user->save();
            $notify[] = ['success', $general->cur_sym . $amount . ' has been added to ' . $user->username . '\'s balance'];

            $transaction = new Transaction();
            $transaction->user_id = $user->id;
            $transaction->amount = $amount;
            $transaction->post_balance = $user->balance;
            $transaction->charge = 0;
            $transaction->trx_type = '+';
            $transaction->details = $request->reason   ;//  'Added Balance Via Admin';
            $transaction->trx =  $trx;
            $transaction->save();

            notify($user, 'BAL_ADD', [
                'trx' => $trx,
                'amount' => showAmount($amount),
                'currency' => $general->cur_text,
                'post_balance' => showAmount($user->balance),
            ]);

        } else {
            
			
			if ($amount > $user->balance) {
               // $notify[] = ['error', $user->username . '\'s has insufficient balance.'];
               // return back()->withNotify($notify);
            }
            $user->balance -= $amount;
            $user->save();



            $transaction = new Transaction();
            $transaction->user_id = $user->id;
            $transaction->amount = $amount;
            $transaction->post_balance = $user->balance;
            $transaction->charge = 0;
            $transaction->trx_type = '-';
            $transaction->details =     $request->reason  ; //'Subtract Balance Via Admin';
            $transaction->trx =  $trx;
            $transaction->save();


            notify($user, 'BAL_SUB', [
                'trx' => $trx,
                'amount' => showAmount($amount),
                'currency' => $general->cur_text,
                'post_balance' => showAmount($user->balance)
            ]);
            $notify[] = ['success', $general->cur_sym . $amount . ' has been subtracted from ' . $user->username . '\'s balance'];
        }
        return back()->withNotify($notify);
    }


    public function userLoginHistory($id)
    {
        $user = User::findOrFail($id);
        $pageTitle = 'User Login History - ' . $user->username;
        $emptyMessage = 'No users login found.';
        $login_logs = $user->login_logs()->orderBy('id','desc')->with('user')->paginate(getPaginate());
        return view('admin.users.logins', compact('pageTitle', 'emptyMessage', 'login_logs'));
    }



    public function showEmailSingleForm($id)
    {
        $user = User::findOrFail($id);
        $pageTitle = 'Send Email To: ' . $user->username;
        return view('admin.users.email_single', compact('pageTitle', 'user'));
    }

    public function sendEmailSingle(Request $request, $id)
    {
        $request->validate([
            'message' => 'required|string|max:65000',
            'subject' => 'required|string|max:190',
        ]);

        $user = User::findOrFail($id);
        sendGeneralEmail($user->email, $request->subject, $request->message, $user->username);
        $notify[] = ['success', $user->username . ' will receive an email shortly.'];
        return back()->withNotify($notify);
    }

    public function transactions(Request $request, $id)
    {
        $user = User::findOrFail($id);
        if ($request->search) {
            $search = $request->search;
            $pageTitle = 'Search User Transactions : ' . $user->username;
            $transactions = $user->transactions()->where('trx', $search)->with('user')->orderBy('id','desc')->paginate(getPaginate());
            $emptyMessage = 'No transactions';
            return view('admin.reports.transactions', compact('pageTitle', 'search', 'user', 'transactions', 'emptyMessage'));
        }
        $pageTitle = 'User Transactions : ' . $user->username;
        $transactions = $user->transactions()->with('user')->orderBy('id','desc')->paginate(getPaginate());
        $emptyMessage = 'No transactions';
        return view('admin.reports.transactions', compact('pageTitle', 'user', 'transactions', 'emptyMessage'));
    }

    public function deposits(Request $request, $id)
    {
        $user   = User::findOrFail($id);
        $userId = $user->id;

        if ($request->search) {
            $search = $request->search;
            $pageTitle = 'Search User Deposits : ' . $user->username;
            $deposits = $user->deposits()->where('trx', $search)->orderBy('id','desc')->paginate(getPaginate());
            $emptyMessage = 'No deposits';
            return view('admin.deposit.log', compact('pageTitle', 'search', 'user', 'deposits', 'emptyMessage','userId'));
        }

        $pageTitle      = 'Deposits of : ' . $user->username;
        $deposits       = $user->deposits()
                            ->orderBy('id','desc')
                            ->with(['gateway','user'])
                            ->paginate(getPaginate());

        $successful     = $user->deposits()->orderBy('id','desc')->where('status',1)->sum('amount');
        $pending        = $user->deposits()->orderBy('id','desc')->where('status',2)->sum('amount');
        $rejected       = $user->deposits()->orderBy('id','desc')->where('status',3)->sum('amount');
        $emptyMessage   = 'No deposit yet';
        $scope          = 'all';

        return view('admin.deposit.log', compact('pageTitle', 'user', 'deposits', 'emptyMessage','userId','scope','successful','pending','rejected'));
    }

    public function withdrawals(Request $request, $id)
    {
        $user = User::findOrFail($id);
        if ($request->search) {
            $search = $request->search;
            $pageTitle = 'Search User Withdrawals : ' . $user->username;
            $withdrawals = $user->withdrawals()->where('trx', 'like',"%$search%")->orderBy('id','desc')->paginate(getPaginate());
            $emptyMessage = 'No withdrawals';
            return view('admin.withdraw.withdrawals', compact('pageTitle', 'user', 'search', 'withdrawals', 'emptyMessage'));
        }
        $pageTitle = 'User Withdrawals : ' . $user->username;
        $withdrawals = $user->withdrawals()->orderBy('id','desc')->paginate(getPaginate());
        $emptyMessage = 'No withdrawals';
        $userId = $user->id;
        return view('admin.withdraw.withdrawals', compact('pageTitle', 'user', 'withdrawals', 'emptyMessage','userId'));
    }

    public  function withdrawalsViaMethod($method,$type,$userId){
        $method = WithdrawMethod::findOrFail($method);
        $user = User::findOrFail($userId);
        if ($type == 'approved') {
            $pageTitle = 'Approved Withdrawal of '.$user->username.' Via '.$method->name;
            $withdrawals = Withdrawal::where('status', 1)->where('user_id',$user->id)->with(['user','method'])->orderBy('id','desc')->paginate(getPaginate());
        }elseif($type == 'rejected'){
            $pageTitle = 'Rejected Withdrawals of '.$user->username.' Via '.$method->name;
            $withdrawals = Withdrawal::where('status', 3)->where('user_id',$user->id)->with(['user','method'])->orderBy('id','desc')->paginate(getPaginate());

        }elseif($type == 'pending'){
            $pageTitle = 'Pending Withdrawals of '.$user->username.' Via '.$method->name;
            $withdrawals = Withdrawal::where('status', 2)->where('user_id',$user->id)->with(['user','method'])->orderBy('id','desc')->paginate(getPaginate());
        }else{
            $pageTitle = 'Withdrawals of '.$user->username.' Via '.$method->name;
            $withdrawals = Withdrawal::where('status', '!=', 0)->where('user_id',$user->id)->with(['user','method'])->orderBy('id','desc')->paginate(getPaginate());
        }
        $emptyMessage = 'Withdraw Log Not Found';
        return view('admin.withdraw.withdrawals', compact('pageTitle', 'withdrawals', 'emptyMessage','method'));
    }

    public function showEmailAllForm()
    {
        $pageTitle = 'Send Email To All Users';
        return view('admin.users.email_all', compact('pageTitle'));
    }

    public function sendEmailAll(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:65000',
            'subject' => 'required|string|max:190',
        ]);

        foreach (User::where('status', 1)->cursor() as $user) {
            sendGeneralEmail($user->email, $request->subject, $request->message, $user->username);
        }

        $notify[] = ['success', 'All users will receive an email shortly.'];
        return back()->withNotify($notify);
    }

    public function login($id){
        $user = User::findOrFail($id);
        Auth::login($user);
        return redirect()->route('user.home');
    }

    public function emailLog($id){
        $user = User::findOrFail($id);
        $pageTitle = 'Email log of '.$user->username;
        $logs = EmailLog::where('user_id',$id)->with('user')->orderBy('id','desc')->paginate(getPaginate());
        $emptyMessage = 'No data found';
        return view('admin.users.email_log', compact('pageTitle','logs','emptyMessage','user'));
    }

    public function emailDetails($id){
        $email = EmailLog::findOrFail($id);
        $pageTitle = 'Email details';
        return view('admin.users.email_details', compact('pageTitle','email'));
    }

    public function kycData($id)
    {
        $user = User::findOrFail($id);
        $pageTitle = 'KYC Data';
        return view('admin.users.kyc_data', compact('pageTitle','user'));
    }

    public function kycApprove($id)
    {
        $user           = User::findOrFail($id);
        $user->kycv     = 1;
        $user->save();

        $general = GeneralSetting::first();

        notify($user, 'KYC_APPROVE', [
            'sitename' => $general->sitename,
        ]);


        $notify[]=['success','Approved successfully'];
        return back()->withNotify($notify);
    }
    public function kycReject($id)
    {

        $user           = User::findOrFail($id);
        $files = collect($user->kyc_data)->where('type', 'file');

        $user->kycv     = 0;
        $user->kyc_data = null;
        $user->save();

        if($files->count()){
            foreach($files->pluck('value') as $image){
                removeFile(imagePath()['verify']['user_kyc']['path'].'/' . $image);
            }
        }

        $general = GeneralSetting::first();

        notify($user, 'KYC_REJECT', [
            'sitename' => $general->sitename,
        ]);


        $notify[]=['success','Rejected successfully'];
        return redirect()->route('admin.users.detail', $user->id)->withNotify($notify);
    }
}
