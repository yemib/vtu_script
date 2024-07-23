<?php

namespace App\Http\Controllers;

use App\Models\AdminNotification;
use App\Models\BalanceTransfer;
use App\Models\FdrPlan;
use App\Models\DpsPlan;
use App\Models\GeneralSetting;
use App\Models\InstallmentLog;
use App\Models\LoanPlan;
use App\Models\OtherBank;
use App\Models\Transaction;
use App\Models\User;
use App\Models\UserAction;
use App\Models\UserBeneficiary;
use App\Models\UserDps;
use App\Models\UserFdr;
use App\Models\UserLoan;
use App\Models\Withdrawal;
use App\Models\WithdrawMethod;
use App\Rules\FileTypeValidate;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class UserActionController extends Controller
{
    public function __construct()
    {
        $this->activeTemplate = activeTemplate();
    }

    public function manageBeneficiary()
    {
        $pageTitle          = 'Beneficiary Management';
        $emptyMessage       = 'No Registered Beneficiary Yet';

        $ownBeneficiaries   = UserBeneficiary::where('user_id', auth()->id())->where('bank_id', 0)->paginate(getPaginate());
        $otherBeneficiaries = UserBeneficiary::where('user_id', auth()->id())->where('bank_id', '!=', 0)->with('bank')->paginate(getPaginate());

        $otherBanks         = OtherBank::active()->get();


        $emptyMessage       = 'No registered beneficiary yet';
        return view($this->activeTemplate . 'user.transfers.beneficiary.manage', compact('pageTitle', 'emptyMessage', 'ownBeneficiaries', 'otherBeneficiaries', 'emptyMessage', 'otherBanks'));
    }

    public function addOwnBeneficiary(Request $request)
    {
        $request->validate([
            'account_number'    => 'required|string:255',
            'account_name'      => 'required|string:255',
            'short_name'        => 'required|string:max:100'
        ]);

        $beneficiaryUser = User::active()->where('account_number', $request->account_number)->where('username', $request->account_name)->first();

        if(!$beneficiaryUser){
            $notify[]=['error','Selected beneficiary is invalid'];
            return back()->withNotify($notify);
        }

        //Check if already exist
        $beneficiaryExist  = UserBeneficiary::where('user_id', auth()->id())->where('beneficiary_id', $beneficiaryUser->id)->first();

        if($beneficiaryExist){
            $notify[]=['error','This user already added'];
            return back()->withNotify($notify);
        }

        $beneficiary                  = new UserBeneficiary();
        $beneficiary->user_id         = auth()->id();
        $beneficiary->beneficiary_id  = $beneficiaryUser->id;
        $beneficiary->account_number  = $request->account_number;
        $beneficiary->account_name    = $request->account_name;
        $beneficiary->short_name      = $request->short_name;
        $beneficiary->save();

        $notify[]=['success','Beneficiary added successfully'];
        return back()->withNotify($notify);
    }

    public function addOtherBeneficiary(Request $request)
    {
        $request->validate([
            'account_number'    => 'required|string:255',
            'account_name'      => 'required|string:255',
            'bank'              => 'required|integer',
            'short_name'        => 'required|string:max:100'
        ]);

        $bank = OtherBank::findOrFail($request->bank);

        $validation_rule    = [];

        if($bank->user_data){
            $userDetails        = [];

            foreach ($bank->user_data as $item) {
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
            $path = imagePath()['transfer']['beneficiary_data']['path'].'/'.$directory;

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

        //Check if exist
        $beneficiary                    = new UserBeneficiary();
        $beneficiary->user_id           = auth()->id();
        $beneficiary->bank_id           = $request->bank;
        $beneficiary->account_number    = $request->account_number;
        $beneficiary->account_name      = $request->account_name;
        $beneficiary->short_name        = $request->short_name;
        $beneficiary->details           = $userDetails;
        $beneficiary->save();

        $notify[]=['success','Beneficiary added successfully'];
        return back()->withNotify($notify);

    }

    public function transferOwn()
    {
        $general            = GeneralSetting::first(['sitename']);
        $ownBeneficiaries   = UserBeneficiary::where('user_id', auth()->id())->where('bank_id', 0)->paginate(getPaginate());
        $emptyMessage       = 'No registered beneficiary yet';
        $pageTitle          = 'Transfer Money Within '.$general->sitename;
        return view($this->activeTemplate . 'user.transfers.own', compact('pageTitle', 'ownBeneficiaries', 'emptyMessage'));
    }
    public function transferOther()
    {
        $general            = GeneralSetting::first(['sitename']);

        $beneficiaries      = UserBeneficiary::where('user_id', auth()->id())
        ->where('bank_id', '!=', 0)
        ->whereHas('bank', function($q){
            return $q->where('status', 1);
        })
        ->with('bank')
        ->paginate(getPaginate());

        $pageTitle          = 'Transfer Money to Other Bank';
        $emptyMessage       = 'No registered beneficiary yet';
        return view($this->activeTemplate . 'user.transfers.other', compact('pageTitle', 'beneficiaries', 'emptyMessage'));
    }

    public function transferHistory()
    {
        $transfers = BalanceTransfer::where('user_id', auth()->id())->with('beneficiary', 'bank')->paginate(getPaginate());
        $pageTitle = 'Transfer History';
        $emptyMessage = 'No transfer yet';
        return view($this->activeTemplate . 'user.transfers.history', compact('pageTitle', 'transfers', 'emptyMessage'));
    }

    public function transferOwnSend()
    {
        $action         = $this->getAction();
        if (!$action) return redirect()->route('user.home');

        if(!$action->used_at){
            abort(403, 'Unauthorized action.');
        }

        $user           = auth()->user();
        $beneficiary    = UserBeneficiary::where('id', $action->beneficiary_id)
                        ->where('user_id', $user->id)
                        ->with('beneficiary')->first();
        if(!$beneficiary){
            abort(403);
        }
        $general    = GeneralSetting::first();
        $amount     = $action->amount;
        $result     = $this->checkOwnTransferAvailability($amount, $general);

        if(!$result['success']){
            $notify[] = ['error', $result['message']];
            return back()->withNotify($notify);
        }

        $transfer                   = new BalanceTransfer();
        $transfer->user_id          = $user->id;
        $transfer->trx              = getTrx();
        $transfer->beneficiary_id   = $beneficiary->id;
        $transfer->bank_id          = $beneficiary->bank_id;
        $transfer->amount           = $amount;
        $transfer->charge           = $result['charge'];
        $transfer->final_amount     = $result['final_amount'];
        $transfer->save();

        $user->balance      -= $transfer->final_amount;
        $user->save();

        $transaction                = new Transaction();
        $transaction->user_id       = $user->id;
        $transaction->amount        = $transfer->final_amount;
        $transaction->post_balance  = $user->balance;
        $transaction->charge        = $transfer->charge;
        $transaction->trx_type      = '-';
        $transaction->details       = 'Money transferred within '.$general->sitename;
        $transaction->trx           = $transfer->trx;
        $transaction->save();

        $recipient          = $beneficiary->beneficiary;

        $recipient->balance += $transfer->amount;
        $recipient->save();

        $transaction                = new Transaction();
        $transaction->user_id       = $recipient->id;
        $transaction->amount        = $transfer->amount;
        $transaction->post_balance  = $recipient->balance;
        $transaction->charge        = 0;
        $transaction->trx_type      = '+';
        $transaction->details       = 'Received transferred money';
        $transaction->trx           = $transfer->trx;
        $transaction->save();

        session()->forget('otp');

        $shortCodes = [
            'sender'        => $user->username,
            'recipient'     => $recipient->username,
            'amount'        => showAmount($transfer->amount),
            'charge'        => showAmount($transfer->charge),
            'final_amount'  => showAmount($transfer->final_amount),
            'trx'           => $transfer->trx,
            'post_balance'  => showAmount($user->balance)
        ];

        notify($user, 'OWN_TRANSFER_MONEY_SEND', $shortCodes);

        $shortCodes = [
            'sender'        => $user->username,
            'recipient'     => $recipient->username,
            'amount'        => showAmount($transfer->amount),
            'charge'        => showAmount($transfer->charge),
            'final_amount'  => showAmount($transfer->final_amount),
            'trx'           => $transfer->trx,
            'post_balance'  => showAmount($recipient->balance)
        ];

        notify($recipient, 'OWN_TRANSFER_MONEY_RECEIVED', $shortCodes);

        $notify[]=['success', "$transfer->amount $general->cur_text transferred successfully"];
        return redirect()->route('user.transfer.history')->withNotify($notify);
    }

    public function transferOtherSend()
    {
        $action         = $this->getAction();
        if (!$action) return redirect()->route('user.home');

        if(!$action->used_at){
            abort(403, 'Unauthorized action.');
        }
        $user           = auth()->user();
        $beneficiary    = UserBeneficiary::where('id', $action->beneficiary_id)
                        ->where('user_id', $user->id)
                        ->with('beneficiary')->first();
        if(!$beneficiary){
            abort(403);
        }
        $general    = GeneralSetting::first();
        $amount     = $action->amount;
        $result     = $this->checkOtherTransferAvailability($amount,  $beneficiary);

        if(!$result['success']){
            $notify[] = ['error', $result['message']];
            return back()->withNotify($notify);
        }


        $transfer                   = new BalanceTransfer();
        $transfer->user_id          = $user->id;
        $transfer->trx              = getTrx();
        $transfer->beneficiary_id   = $beneficiary->id;
        $transfer->bank_id          = $beneficiary->bank_id;
        $transfer->amount           = $amount;
        $transfer->charge           = $result['charge'];
        $transfer->final_amount     = $result['final_amount'];
        $transfer->status           = 0;
        $transfer->save();

        $user->balance      -= $transfer->final_amount;
        $user->save();

        $transaction                = new Transaction();
        $transaction->user_id       = $user->id;
        $transaction->amount        = $transfer->final_amount;
        $transaction->post_balance  = $user->balance;
        $transaction->charge        = $transfer->charge;
        $transaction->trx_type      = '-';
        $transaction->details       = 'Money transferred to other bank';
        $transaction->trx           = $transfer->trx;
        $transaction->save();

        $adminNotification              = new AdminNotification();
        $adminNotification->user_id     = $user->id;
        $adminNotification->title       = 'A new money transfer request by '.$user->username;
        $adminNotification->click_url   = urlPath('admin.transfers.details', $transfer->id);
        $adminNotification->save();

        session()->forget('otp');

        notify($transfer->user, 'OTHER_TRANSFER_REQUEST_SEND', [
            "sender_account_number"     => $transfer->user->account_number,
            "sender_account_name"       => $transfer->user->username,
            "recipient_account_number"  => $transfer->beneficiary->account_number,
            "recipient_account_name"    => $transfer->beneficiary->account_name,
            "sending_amount"            => showAmount($transfer->amount),
            "charge"                    => showAmount($transfer->charge),
            "final_amount"              => showAmount($transfer->charge),
            "currency"                  => $general->cur_text,
            "bank_name"                 => $transfer->bank->name,
        ]);

        $notify[]=['success', "$transfer->amount $general->cur_text transfer request sent successfully"];
        return redirect()->route('user.transfer.history')->withNotify($notify);
    }

    public function fdrPlans()
    {
        $pageTitle      = 'Fixed Deposit Receipt Plans';
        $emptyMessage   = 'No Plan Yet';
        $plans          = FdrPlan::active()->latest()->paginate(getPaginate());
        return view($this->activeTemplate . 'user.fdr.plans', compact('pageTitle', 'emptyMessage', 'plans'));
    }

    public function fdrList()
    {
        $fdrs           = UserFdr::where('user_id', auth()->id())->with('plan')->latest()->paginate(getPaginate());
        $pageTitle      = 'My FDR List';
        $emptyMessage   = 'You have no fdr yet';
        return view($this->activeTemplate . 'user.fdr.list', compact('pageTitle', 'fdrs', 'emptyMessage'));
    }

    public function fdrBuy()
    {
        $action         = $this->getAction();
        if (!$action) return redirect()->route('user.home');

        $plan           = FdrPlan::active()->where('id', $action->plan_id)->firstOrFail();
        $amount         = $action->amount;
        $pageTitle      = 'Invest in FDR';
        return view($this->activeTemplate . 'user.fdr.preview', compact('pageTitle', 'plan', 'amount'));
    }

    function fdrBuyConfirm(){
        $action  = $this->getAction();
        if (!$action) return redirect()->route('user.home');
        //Check if OTP Verified
        if(!$action->used_at){
            abort(403, 'Unauthorized action.');
        }

        $amount  = $action->amount;
        $user    = auth()->user();

        //Check user balance
        if($user->balance < $amount){
            $notify[]=['error','Sorry! You don\'t have sufficient balance'];
            return redirect()->route('user.fdr.plans')->withNotify($notify);
        }

        $plan    = FdrPlan::active()->where('id', $action->plan_id)->firstOrFail();
        $userFdr = new UserFdr();

        $userFdr->user_id            = $user->id;
        $userFdr->plan_id            = $plan->id;
        $userFdr->trx                = getTrx();
        $userFdr->amount             = $amount;
        $userFdr->interest           = getAmount($amount*$plan->interest_rate/100);
        $userFdr->interest_interval  = $plan->interest_interval;
        $userFdr->next_return_date   = Carbon::now()->addDays($plan->interest_interval+1);
        $userFdr->locked_date        = Carbon::now()->addDays($plan->locked_days);
        $userFdr->save();

        $user->balance -= $amount;
        $user->save();

        $transaction = new Transaction();
        $transaction->user_id       = $user->id;
        $transaction->amount        = $amount;
        $transaction->post_balance  = $user->balance;
        $transaction->charge        = 0;
        $transaction->trx_type      = '-';
        $transaction->details       = 'New FDR Opened';
        $transaction->trx           =  $userFdr->trx;
        $transaction->save();
        $transaction->save();

        $adminNotification = new AdminNotification();
        $adminNotification->user_id = $user->id;
        $adminNotification->title = 'A new FDR has been opened by '.$user->username;
        $adminNotification->click_url = urlPath('admin.users.fdr', $user->id);
        $adminNotification->save();

        $shortCodes = [
            'plan_name'         => $plan->name,
            'trx'               => $userFdr->trx,
            'amount'            => showAmount($amount),
            'locked_date'       => $userFdr->locked_date,
            'interest'          => $userFdr->interest,
            'interest_interval' => $userFdr->interest_interval,
            'next_return_date'  => $userFdr->next_return_date,
        ];

        notify($user, 'OPEN_FDR', $shortCodes);
        session()->forget('otp');
        return redirect()->route('user.fdr.list');
    }


    public function fdrClose(Request $request)
    {
        $request->validate([
            'user_token' => 'required'
        ]);
        $fdr = UserFdr::where('id',decrypt($request->user_token))->where('status',1)->where('user_id', auth()->id())->firstOrFail();

        if($fdr->locked_date > Carbon::now()){
            $notify[]=['error','Sorry! You cant close this FDR before '. showDateTime($fdr->locked_date, 'd M, Y')];
            return back()->withNotify($notify);
        }

        $general = GeneralSetting::first();
        $user = auth()->user();
        $user->balance += $fdr->amount;
        $user->save();

        $fdr->status = 2;
        $fdr->save();

        $transaction                = new Transaction();
        $transaction->user_id       = $user->id;
        $transaction->amount        = $fdr->amount;
        $transaction->post_balance  = $user->balance;
        $transaction->charge        = 0;
        $transaction->trx_type      = '+';
        $transaction->details       = 'Received main amount of FDR';
        $transaction->trx           = getTrx();
        $transaction->save();


        $adminNotification = new AdminNotification();
        $adminNotification->user_id = $user->id;
        $adminNotification->title = 'An FDR has been closed by '.$user->username;
        $adminNotification->click_url = urlPath('admin.users.fdr', $user->id);
        $adminNotification->save();

        notify($user, 'FDR_CLOSED',[
            "fdr_number"    => $fdr->trx,
            "amount"        => showAmount($fdr->amount),
            "profit"        => showAmount($fdr->profit),
            "interest"      => showAmount($fdr->interest),
            "trx"           => $transaction->trx,
            "currency"      => $general->cur_text,
            "plan_name"     => $fdr->plan->name,
            "post_balance"  => showAmount($user->balance),
        ]);

        $notify[]=['success','FDR Closed successfully'];
        return back()->withNotify($notify);

    }


    public function dpsPlans()
    {
        $pageTitle      = 'Deposit Pension Scheme Plans';
        $emptyMessage   = 'No Plan Yet';
        $plans          = DpsPlan::active()->latest()->paginate(getPaginate());
        return view($this->activeTemplate . 'user.dps.plans', compact('pageTitle', 'emptyMessage', 'plans'));
    }

    public function dpsList()
    {
        $allDps         = UserDps::where('user_id', auth()->id())->with('plan')->paginate(getPaginate());
        $pageTitle      = 'My DPS List';
        $emptyMessage   = 'You have no dps yet';
        return view($this->activeTemplate . 'user.dps.list', compact('pageTitle', 'allDps', 'emptyMessage'));
    }

    public function dpsBuy()
    {
        $action         = $this->getAction();
        $plan           = DpsPlan::active()->where('id', $action->plan_id)->firstOrFail();
        $amount         = $action->amount;
        $pageTitle      = 'Invest in DPS';
        return view($this->activeTemplate . 'user.dps.preview', compact('pageTitle', 'plan', 'amount'));
    }

    function dpsBuyConfirm(){

        $action  = $this->getAction();
        if (!$action) return redirect()->route('user.home');

        //Check if OTP Verified
        if(!$action->used_at){
            abort(403, 'Unauthorized action.');
        }

        $plan    = DpsPlan::active()->where('id', $action->plan_id)->firstOrFail();
        $amount  = $plan->per_installment + 0;
        $user    = auth()->user();

        //Check user balance
        if($user->balance < $amount){
            $notify[]=['error','Sorry! You don\'t have sufficient balance'];
            return redirect()->route('user.fdr.plans')->withNotify($notify);
        }

        $userDps = new UserDps();

        $userDps->user_id               = $user->id;
        $userDps->plan_id               = $plan->id;
        $userDps->trx                   = getTrx();
        $userDps->interest_rate         = $plan->interest_rate;
        $userDps->per_installment       = $plan->per_installment;
        $userDps->total_installment     = $plan->total_installment;
        $userDps->given_installment     = 1;
        $userDps->installment_interval  = $plan->installment_interval;
        $userDps->next_installment_date = Carbon::now()->addDays($plan->installment_interval+1);
        $userDps->save();

        $user->balance -= $plan->per_installment;
        $user->save();

        $log            = new InstallmentLog();
        $log->f_id      = $userDps->id;
        $log->type      = 'DPS';
        $log->amount    = $userDps->per_installment;
        $log->save();

        $transaction = new Transaction();
        $transaction->user_id       = $user->id;
        $transaction->amount        = $plan->per_installment;
        $transaction->post_balance  = $user->balance;
        $transaction->charge        = 0;
        $transaction->trx_type      = '-';
        $transaction->details       = 'DPS Installment Given';
        $transaction->trx           =  $userDps->trx;
        $transaction->save();

        $general = GeneralSetting::first();

        $adminNotification              = new AdminNotification();
        $adminNotification->user_id     = $user->id;
        $adminNotification->title       = 'A new DPS has been opened by '.$user->username;
        $adminNotification->click_url   = urlPath('admin.users.dps', $user->id);
        $adminNotification->save();

        $shortCodes = [
            'plan_name'             => $plan->name,
            'trx'                   => $userDps->trx,
            'per_installment'       => showAmount($plan->per_installment),
            'interest_interval'     => showAmount($plan->installment_interval),
            'interest_rate'         => $plan->interest_rate,
            'given_installment'     => 1,
            'total_installment'     => $plan->total_installment,
            'next_installment_date' => $userDps->next_installment_date,
        ];

        notify($user, 'OPEN_DPS', $shortCodes);
        session()->forget('otp');

        return redirect()->route('user.dps.list');

    }

    public function dpsClose(Request $request)
    {
        $request->validate([
            'user_token' => 'required'
        ]);

        $dps = UserDps::where('id', decrypt($request->user_token))->where('user_id', auth()->id())->with('plan')->firstOrFail();

        if($dps->status == 1 ){
            $notify[]=['error','Sorry! you can\'t close a DPS before mature'];
            return back()->withNotify($notify);
        }

        if(!$dps->status){
            $notify[]=['error','You have already closed this DPS'];
            return back()->withNotify($notify);
        }

        $general        = GeneralSetting::first();
        $user           = auth()->user();

        $dps->status    = 0;
        $dps->save();

        $totalGiven     = $dps->per_installment * $dps->given_installment;
        $interest       = $totalGiven * $dps->interest_rate /100;
        $totalAmount    = $totalGiven + $interest;
        $user->balance += $user->$totalAmount;

        $transaction                = new Transaction();
        $transaction->user_id       = $user->id;
        $transaction->amount        = $totalAmount;
        $transaction->post_balance  = $user->balance;
        $transaction->charge        = 0;
        $transaction->trx_type      = '+';
        $transaction->details       = 'DPS Mature Amount Received';
        $transaction->trx           = getTrx();
        $transaction->save();

        $adminNotification              = new AdminNotification();
        $adminNotification->user_id     = $user->id;
        $adminNotification->title       = 'A DPS has been closed by '.$user->username;
        $adminNotification->click_url   = urlPath('admin.users.dps', $user->id);
        $adminNotification->save();

        notify($user, 'DPS_CLOSED',[
            "received_amount"   => showAmount($totalAmount),
            "given_amount"      => showAmount($totalGiven),
            "interest"          => showAmount($interest),
            "trx"               => $transaction->trx,
            "currency"          => $general->cur_text,
            "plan_name"         => $dps->plan->name,
            "dps_number"        => $dps->trx,
            "post_balance"      => showAmount($user->balance),
        ]);

        $notify[]=['success','DPS closed successfully'];
        return back()->withNotify($notify);

    }


    public function loanPlans()
    {
        $pageTitle      = 'Loan Plans';
        $emptyMessage   = 'No Plan Yet';
        $plans          = LoanPlan::active()->latest()->paginate(getPaginate());
        return view($this->activeTemplate . 'user.loan.plans', compact('pageTitle', 'emptyMessage', 'plans'));
    }

    public function loanList()
    {
        $loans           = UserLoan::where('user_id', auth()->id())->with('plan')->paginate(getPaginate());
        $pageTitle      = 'My Loan List';
        $emptyMessage   = 'You have no loan yet';
        return view($this->activeTemplate . 'user.loan.list', compact('pageTitle', 'loans', 'emptyMessage'));
    }


    public function applyLoan(Request $request)
    {
        $request->validate([
            'id'    => 'required|integer',
            'amount'=> 'required|numeric',
        ]);
        $plan = LoanPlan::active()->where('id', $request->id)->firstOrFail();

        // Check Limit
        if($plan->minimum_amount > $request->amount || $request->amount > $plan->maximum_amount ){
            $notify[]=['error','Please follow the minium & maximum limit for this plan'];
            return back()->withNotify($notify);
        }

        session()->put('loan', ['plan'=>$plan, 'amount'=> $request->amount]);

        return redirect()->route('user.loan.apply.form');
    }

    public function loanForm()
    {
        $loan = session()->get('loan');
        if(!$loan) return redirect()->route('user.loan.plans');

        $plan   = $loan['plan'];
        $amount = $loan['amount'];
        $requiredInfo = json_decode($plan->required_information);
        $pageTitle = 'Apply For Loan';
        return view($this->activeTemplate . 'user.loan.form', compact('pageTitle', 'plan', 'amount', 'requiredInfo'));
    }


    public function loanFormSubmit(Request $request)
    {
        $loan   = session()->get('loan');
        if(!$loan) return redirect()->route('user.loan.plans');
        $plan   = $loan['plan'];
        $amount = $loan['amount'];
        $plan = LoanPlan::active()->where('id', $plan->id)->firstOrFail();

        //Check Limit
        if($plan->minimum_amount > $amount || $amount > $plan->maximum_amount ){
            $notify[]=['error','Please follow the minium & maximum limit for this plan'];
            return redirect()->route('user.loan.plans')->withNotify($notify);
        }

        $requiredInfo = json_decode($plan->required_information);

        if($requiredInfo){
            $validation_rule    = [];
            $userDetails        = [];

            foreach ($requiredInfo as $item) {
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
            $path = imagePath()['verify']['loan']['path'].'/'.$directory;

            foreach ($userDetails as $key=>$item) {
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

        $user               = auth()->user();
        $userLoan           = new UserLoan();
        $per_installment    = $amount * $plan->per_installment / 100;

        $userLoan->user_id              = $user->id;
        $userLoan->plan_id              = $plan->id;
        $userLoan->trx                  = getTrx();
        $userLoan->amount               = $amount;
        $userLoan->per_installment      = $per_installment;
        $userLoan->user_details         = json_encode($userDetails);
        $userLoan->installment_interval = $plan->installment_interval;
        $userLoan->total_installment    = $plan->total_installment;
        $userLoan->final_amount         = getAmount($per_installment * $plan->total_installment);
        $userLoan->save();

        $shortCodes = [
            'trx'                   => $userLoan->trx,
            'amount'                => showAmount($amount),
            'plan_name'             => $plan->name,
            'per_installment'       => showAmount($per_installment),
            'installment_interval'  => $plan->installment_interval,
            'total_installment'     => $plan->total_installment,
            'final_amount'          => showAmount($per_installment * $plan->total_installment)
        ];

        $adminNotification = new AdminNotification();
        $adminNotification->user_id = $user->id;
        $adminNotification->title = 'A new loan request has been submitted by '.$user->username;
        $adminNotification->click_url = urlPath('admin.loan.details',$userLoan->id);
        $adminNotification->save();

        notify($user, 'LOAN_REQUEST', $shortCodes);

        session()->forget('loan');
        $notify[]=['success',' successfully'];
        return redirect()->route('user.loan.list')->withNotify($notify);

    }


    /*
     * Withdraw Operation
     */

    public function withdrawMoney()
    {
        $withdrawMethod = WithdrawMethod::where('status', 1)->get();
        $pageTitle = 'Withdraw Money';
        return view($this->activeTemplate . 'user.withdraw.methods', compact('pageTitle', 'withdrawMethod'));
    }

    public function withdrawRequest()
    {
        $action  = $this->getAction();
        if (!$action) return redirect()->route('user.home');

        //Check if OTP Verified
        if(!$action->used_at){
            abort(403, 'Unauthorized action.');
        }

        $amount  = $action->amount;
        $user    = auth()->user();

        //Check user balance
        if($user->balance < $amount){
            $notify[]=['error','Sorry! You don\'t have sufficient balance'];
            return redirect()->route('user.wihtdraw')->withNotify($notify);
        }

        $method = WithdrawMethod::where('id', $action->method_id)->where('status', 1)->firstOrFail();
        $user = auth()->user();
        if ($amount < $method->min_limit) {
            $notify[] = ['error', 'Your requested amount is smaller than minimum amount.'];
            return back()->withNotify($notify);
        }
        if ($amount > $method->max_limit) {
            $notify[] = ['error', 'Your requested amount is larger than maximum amount.'];
            return back()->withNotify($notify);
        }

        if ($amount > $user->balance) {
            $notify[] = ['error', 'You do not have sufficient balance for withdraw.'];
            return back()->withNotify($notify);
        }

        $charge = $method->fixed_charge + ($amount * $method->percent_charge / 100);
        $afterCharge = $amount - $charge;
        $finalAmount = $afterCharge * $method->rate;

        $withdraw = new Withdrawal();
        $withdraw->method_id = $method->id; // wallet method ID
        $withdraw->user_id = $user->id;
        $withdraw->amount = $amount;
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



    public function userAction(Request $request)
    {
        $request->validate([
            'id'            =>'required|integer',
            'type'          => 'required|in:fdr,dps,withdraw,own_transfer,other_transfer',
            'verification'  => 'sometimes|required|in:1,2,3',
            'amount'        => 'sometimes|gt:0|required|numeric'
        ]);

        $user       = auth()->user();
        $general    = GeneralSetting::first();
        $amount     = $request->amount;

        //Check user balance
        if($amount && $user->balance < $amount){
            $notify[]=['error','Sorry! You don\'t have sufficient balance'];
            return back()->withNotify($notify);
        }


        $plan = $method = $act = null;

        if($request->type == 'fdr') {
            $plan   = FdrPlan::findOrFail($request->id);
            $act    = 'FDR_OTP';
            if (($amount < $plan->minimum_amount) || ($amount > $plan->maximum_amount)) {
                $notify[] = ['error', 'Please follow the minimum and maximum deposit limit'];
                return back()->withNotify($notify);
            }

        }elseif ($request->type == 'dps') {
            $plan = DpsPlan::findOrFail($request->id);

            if($user->balance < $plan->per_installment){
                $notify[]=['error','Sorry! You don\'t have sufficient balance'];
                return back()->withNotify($notify);
            }

            $act = 'DPS_OTP';

        }elseif($request->type == 'withdraw') {
            $method = WithdrawMethod::where('id', $request->id)->where('status', 1)->firstOrFail();
            $act    = 'WITHDRAW_OTP';
            if (($amount < $method->min_limit) || ($amount > $method->max_limit)) {
                $notify[] = ['error', 'Please follow the minimum and maximum withdrawal limit'];
                return back()->withNotify($notify);
            }

        }elseif($request->type == 'own_transfer') {
            $result = $this->checkOwnTransferAvailability($request->amount, $general);
            $act    = 'OWN_TRANSFER_OTP';
            if(!$result['success']){

                $notify[] = ['error', $result['message']];
                return back()->withNotify($notify);
            }
            $beneficiaryId = $request->id;

        }elseif($request->type == 'other_transfer') {
            $act            = 'OTHER_BANK_TRANSFER_OTP';
            $beneficiary    = UserBeneficiary::where('user_id', $user->id)
            ->where('id', $request->id)
            ->whereHas('bank', function($q){
                return $q->where('status', 1);
            })
            ->with('bank')
            ->first();

            if(!$beneficiary){
                $notify[] = ['error', 'Selected beneficiary is invalid'];
                return back()->withNotify($notify);
            }

            $result = $this->checkOtherTransferAvailability($request->amount, $beneficiary);
            if(!$result['success']){
                $notify[] = ['error', $result['message']];
                return back()->withNotify($notify);
            }
            $beneficiaryId = $request->id;
        }

        $action = new UserAction();

        if ($plan) {
            $action->plan_id        = $plan->id;
        }elseif($method) {
            $action->method_id      = $method->id;
        }elseif($beneficiaryId){
            $action->beneficiary_id  = $beneficiaryId;
        }

        $action->user_id    = $user->id;
        $action->type       = $request->type;
        $action->amount     = $amount;
        $action->otp_type   = $request->verification;
        $action->save();
        session()->put('otp', $action->id);

        if(!checkIsOtpEnable($general)){
            $action->used_at   = now();
            $action->save();

            if($action->type == 'fdr'){
                return redirect()->route('user.fdr.buy');
            }elseif($action->type == 'dps'){
                return redirect()->route('user.dps.buy');
            }elseif($action->type == 'withdraw'){
                return redirect()->route('user.withdraw.request');
            }elseif($action->type == 'own_transfer'){
                return redirect()->route('user.transfer.own.send');
            }elseif($action->type == 'other_transfer'){
                return redirect()->route('user.transfer.other.send');
            }
        }

        if($request->verification != 1){
            $this->sendOtp($action, $act);
        }

        return redirect()->route('user.otp.verify');
    }

    function checkOwnTransferAvailability($amount, $general=null){
        $fixedCharge    = $general->fixed_transfer_charge;
        $percentCharge  = $amount * $general->percent_transfer_charge / 100;
        $charge         = $fixedCharge + $percentCharge;
        $finalAmount    = $amount + $charge;
        $user           = auth()->user();

        //Check Per Minimum Transfer limit
        if($amount < $general->minimum_transfer_limit){
            $result['message']  = 'Sorry minimum transfer limit is '. showAmount($general->minimum_transfer_limit);
            $result['success']  = false;
            return $result;
        }

        //Check if Balance is sufficient
        if($user->balance < $finalAmount){
            $result['message']  = 'Sorry! You don\'t have sufficient balance';
            $result['success']  = false;
            return $result;
        }

        $now = Carbon::now();

        //Check Transfer limit
        $todaysTotal = BalanceTransfer::where('user_id', $user->id)
                                ->where('status', 1)
                                ->where('bank_id', 0)
                                ->whereDate('created_at', $now)
                                ->sum('amount')
                                ;

        if($todaysTotal +  $amount > $general->daily_transfer_limit){
            $result['message']  = 'Sorry you are exceeding the daily transfer limit';
            $result['success']  = false;
            return $result;
        }

        $thisMonthTotal = BalanceTransfer::where('user_id', $user->id)
                                ->where('status', 1)
                                ->where('bank_id', 0)
                                ->whereMonth('created_at', $now->month)
                                ->whereYear('created_at', $now->year)
                                ->sum('amount')
                                ;
        if($thisMonthTotal +  $amount > $general->monthly_transfer_limit){
            $result['message']  = 'Sorry you are exceeding the monthly transfer limit';
            $result['success']  = false;
            return $result;
        }


        $result['success']      = true;
        $result['charge']       = $charge;
        $result['final_amount'] = $finalAmount;
        return $result;
    }

    function checkOtherTransferAvailability($amount, $beneficiary){
        $bank           = $beneficiary->bank;
        $fixedCharge    = $bank->fixed_charge;
        $percentCharge  = $amount * $bank->percent_charge / 100;
        $charge         = $fixedCharge + $percentCharge;
        $finalAmount    = $amount + $charge;
        $user           = auth()->user();


        //Check Per Minimum Transfer limit
        if($amount < $bank->minimum_limit){
            $result['message']  = 'Sorry minimum transfer limit is '. showAmount($bank->minimum_limit);
            $result['success']  = false;
            return $result;
        }

        //Check Per Transfer Maximum limit
        if($amount > $bank->maximum_limit){
            $result['message']  = 'Sorry maximum transfer limit is '. showAmount($bank->maximum_limit);
            $result['success']  = false;
            return $result;
        }

        //Check if Balance is sufficient
        if($user->balance < $finalAmount){
            $result['message']  = 'Sorry! You don\'t have sufficient balance';
            $result['success']  = false;
            return $result;
        }



        $now = Carbon::yesterday();
        //Check Daily Transfer limit
        $todaysData = BalanceTransfer::where('user_id', $user->id)
                                ->whereIn('status', [0,1])
                                ->where('bank_id', $bank->id)
                                ->whereDate('created_at', $now)
                                ->selectRaw('count(id) as total_transfer, sum(amount) as total_amount')
                                ->first()
                                ;
        $todaysTotalAmount  = $todaysData['total_amount']??0;
        $todaysTotalCount   = $todaysData['total_transfer'];

        if($todaysTotalAmount +  $amount > $bank->daily_maximum_limit){
            $result['message']  = 'Sorry you are exceeding the daily transfer limit';
            $result['success']  = false;
            return $result;
        }


        if($todaysTotalCount > $bank->daily_total_transaction){
            $result['message']  = 'Sorry you have already reached the daily transfer limit of '. $bank->daily_total_transaction . 'times';
            $result['success']  = false;
            return $result;
        }


        //Check Monthly Transfer limit

        $thisMonthData = BalanceTransfer::where('user_id', $user->id)
                                ->whereIn('status', [0,1])
                                ->where('bank_id', $bank->id)
                                ->whereMonth('created_at', $now->month)
                                ->whereYear('created_at', $now->year)
                                ->selectRaw('count(id) as total_transfer, sum(amount) as total_amount')
                                ->first()
                                ;

        $thisMonthTotalAmount  = $thisMonthData['total_amount']??0;
        $thisMonthTotalCount   = $thisMonthData['total_transfer'];

        if($thisMonthTotalAmount +  $amount > $bank->monthly_maximum_limit){
            $result['message']  = 'Sorry you are exceeding the monthly transfer limit';
            $result['success']  = false;
            return $result;
        }

        if($thisMonthTotalCount > $bank->monthly_total_transaction){
            $result['message']  = 'Sorry you have already reached the monthly transfer limit of '. $bank->monthly_total_transaction . 'times';
            $result['success']  = false;
            return $result;
        }

        $result['success']      = true;
        $result['charge']       = $charge;
        $result['final_amount'] = $finalAmount;
        return $result;
    }


    public function verifyOtp()
    {
        $pageTitle      = 'OTP Verification';
        $action         = $this->getAction();
        if (!$action) return redirect()->route('user.home');

        if($action->used_at){
            $notify[]=['error','Invalid Token'];
            return redirect()->route('user.home')->withNotify($notify);
        }

        return view($this->activeTemplate . 'user.verify_otp', compact('pageTitle', 'action'));
    }


    public function sendOtp($action, $act)
    {
        $general            = GeneralSetting::first();

        $action->otp        = verificationCode(6);
        $action->send_at    = Carbon::now();
        $action->expired_at = Carbon::now()->addSeconds($general->otp_time);
        $action->save();

        $shortCodes = [
            'sitename' => $general->sitename,
            'otp'      => $action->otp
        ];


        if($action->otp_type == 2){
            sendEmail(auth()->user(), $act, $shortCodes);
        }elseif($action->otp_type == 3){
            sendSms(auth()->user(), $act, $shortCodes);
        }
    }

    public function resendOtp()
    {
        $action = $this->getAction();
        if (!$action) return redirect()->route('user.home');

        if($action->type == 'fdr') {
            $act = 'FDR_OTP';
        }elseif ($action->type == 'dps') {
            $act = 'DPS_OTP';
        }elseif ($action->type == 'withdraw') {
            $act = 'WITHDRAW_OTP';
        }elseif ($action->type == 'own_transfer') {
            $act = 'OWN_TRANSFER_OTP';
        }elseif ($action->type == 'other_transfer') {
            $act = 'OTHER_BANK_TRANSFER_OTP';
        }
        $general = GeneralSetting::first();

        if($this->checkValidCode($action, $action->otp, $general->otp_time)) {
            $target_time = $action->send_at->addSeconds($general->otp_time)->timestamp;
            $delay = $target_time - time();
            throw ValidationException::withMessages(['resend' => 'Please Try after ' . $delay . ' Seconds']);
        }

        $this->sendOtp($action, $act);

        return redirect()->route('user.otp.verify');
    }

    public function checkValidCode($action, $code, $add_min = 10000){
        if (!$code) return false;
        if (!$action->send_at) return false;
        if ($action->send_at->addSeconds($add_min) < Carbon::now()) return false;
        if ($action->otp !== $code) return false;
        return true;
    }

    public function checkOtp(Request $request)
    {
        $now  = Carbon::now();
        $request->validate([
            'otp'=>'required|digits:6'
        ]);

        $user = auth()->user();
        $action = $this->getAction();

        if (!$action) return redirect()->route('user.home');
        //If 2Fa


        if($action->otp_type == 1){
            $verified = verifyG2fa($user,trim($request->otp));
            if(!$verified) {
                $notify[]=['error','Sorry! Invalid OTP Provided'];
                return back()->withNotify($notify);
            }
        }
        //If SMS or Email
        else{
            // Check if valid otp
            if($action->otp != $request->otp){
                $notify[]=['error','Invalid OTP'];
                return back()->withNotify($notify);
            }
            //Check If OTP is expired
            $finishTime = Carbon::parse($action->expired_at);
            if($now > $finishTime) {
                $notify[]=['error','Sorry! This OTP is expired'];
                return back()->withNotify($notify);
            }

            $verified = 1;
        }

        if($verified){
            $action->used_at = now();
            $action->save();

            if($action->type == 'fdr'){
                return redirect()->route('user.fdr.buy');
            }elseif($action->type == 'dps'){
                return redirect()->route('user.dps.buy');
            }elseif($action->type == 'withdraw'){
                return redirect()->route('user.withdraw.request');
            }elseif($action->type == 'own_transfer'){
                return redirect()->route('user.transfer.own.send');
            }elseif($action->type == 'other_transfer'){
                return redirect()->route('user.transfer.other.send');
            }
        }
    }


    public function getAction()
    {
        $id  = session()->get('otp');
        if (!$id) return false;

        $action = UserAction::where('id', $id)->where('user_id', auth()->id())->first();
        if (!$action) return false;

        return $action;
    }

    public function cancelAction()
    {
        $action = $this->getAction();
        if (!$action) return redirect()->route('user.home');
        session()->forget('otp');
        if($action->type == 'fdr'){
            return redirect()->route('user.fdr.plans');
        }elseif($action->type == 'dps'){
            return redirect()->route('user.dps.plans');
        }elseif($action->type == 'withdraw'){
            return redirect()->route('user.withdraw.history');
        }
    }

}
