<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BalanceTransfer;
use App\Models\GeneralSetting;
use App\Models\Transaction;
use Illuminate\Http\Request;

class ManageTransfersController extends Controller
{
    public function own()
    {
        $pageTitle      = 'Own Bank Transfers';

        if(request()->search){
            $query          = BalanceTransfer::where('trx', request()->search)
                                ->where('status', 1)
                                ->where('bank_id', 0);
            $emptyMessage   = 'No Data Found';
        }else{
            $query          = BalanceTransfer::where('status', 1)->where('bank_id', 0);

            $emptyMessage   = 'No own bank transfer yet';
        }

        $transfers = $query->with('user', 'beneficiary')->paginate(getPaginate());

        return view('admin.transfers.index', compact('pageTitle', 'emptyMessage', 'transfers'));
    }
    public function others()
    {
        $pageTitle      = 'Other Bank Transfers';


        if(request()->search){
            $query          = BalanceTransfer::where('trx', request()->search)
                                ->where('status', 1)
                                ->whereHas('bank');
            $emptyMessage   = 'No Data Found';
        }else{
            $query          = BalanceTransfer::where('status', 1)->whereHas('bank');
            $emptyMessage   = 'No other bank transfer yet';
        }

        $transfers = $query->with('user', 'beneficiary')->paginate(getPaginate());

        return view('admin.transfers.index', compact('pageTitle', 'emptyMessage', 'transfers'));
    }

    public function pending()
    {
        $pageTitle      = 'Other Banks Pending Transfers';

        if(request()->search){
            $query          = BalanceTransfer::where('trx', request()->search)
                                ->where('status', 0)
                                ->whereHas('bank');
            $emptyMessage   = 'No Data Found';
        }else{
            $query          = BalanceTransfer::where('status', 0)->whereHas('bank');
            $emptyMessage   = 'No pending transfer yet';
        }

        $transfers      = $query->with('bank', 'user')->paginate(getPaginate());

        return view('admin.transfers.index', compact('pageTitle', 'emptyMessage', 'transfers'));
    }
    public function rejected()
    {
        $pageTitle      = 'Other Banks Rejected Transfers';

        if(request()->search){
            $query          = BalanceTransfer::where('trx', request()->search)
                                ->where('status', 2)
                                ->whereHas('bank');
            $emptyMessage   = 'No Data Found';
        }else{
            $query          = BalanceTransfer::where('status', 2)->whereHas('bank');
            $emptyMessage   = 'No rejected transfer yet';
        }

        $transfers      = $query->with('bank', 'user')->paginate(getPaginate());

        return view('admin.transfers.index', compact('pageTitle', 'emptyMessage', 'transfers'));
    }

    public function details($id)
    {
        $transfer      = BalanceTransfer::where('id', $id)->with('bank', 'user', 'beneficiary')->firstOrFail();
        $pageTitle      = 'Transfer Details';

        return view('admin.transfers.details', compact('pageTitle', 'transfer'));
    }


    public function accept($id)
    {
        $transfer   = BalanceTransfer::where('id', $id)
                        ->with('user', 'bank', 'beneficiary')
                        ->firstOrFail();

        if($transfer->status == 1) {
            $notify[]=['error','This transfer has already been completed'];
            return back()->withNotify($notify);
        }
        $transfer->status   = 1;
        $transfer->save();

        $general = GeneralSetting::first();

        notify($transfer->user, 'OTHER_TRANSFER_COMPLETED', [
            "sender_account_number" => $transfer->user->account_number,
            "sender_account_name" => $transfer->user->username,
            "recipient_account_number" => $transfer->beneficiary->account_number,
            "recipient_account_name" => $transfer->beneficiary->account_name,
            "sending_amount" => $transfer->amount,
            "charge" =>  $transfer->charge,
            "final_amount" =>  $transfer->charge,
            "currency"=> $general->cur_text,
            "bank_name"=> $transfer->bank->name,
        ]);


        $notify[]=['success','Transfer completed successfully'];
        return back()->withNotify($notify);
    }

    public function reject($id)
    {
        $transfer   = BalanceTransfer::where('id', $id)
                        ->with('user', 'bank', 'beneficiary')
                        ->firstOrFail();

        if($transfer->status == 2) {
            $notify[]=['error','This transfer has already been rejectd'];
            return back()->withNotify($notify);
        }
        $transfer->status   = 2;
        $transfer->save();

        $general = GeneralSetting::first();

        // Refund User's Balance
        $user = $transfer->user;
        $user->balance += $transfer->final_amount;
        $user->save();


        $transaction                = new Transaction();
        $transaction->user_id       = $user->id;
        $transaction->amount        = $transfer->final_amount;
        $transaction->post_balance  = $user->balance;
        $transaction->charge        = 0;
        $transaction->trx_type      = '+';
        $transaction->details       = 'Refund trnsferred amount due to admin rejection';
        $transaction->trx           = $transfer->trx;
        $transaction->save();

        notify($user, 'OTHER_TRANSFER_REJECTED', [
            "sender_account_number" => $transfer->user->account_number,
            "sender_account_name" => $transfer->user->username,
            "recipient_account_number" => $transfer->beneficiary->account_number,
            "recipient_account_name" => $transfer->beneficiary->account_name,
            "sending_amount" => $transfer->amount,
            "charge" =>  $transfer->charge,
            "final_amount" =>  $transfer->charge,
            "currency"=> $general->cur_text,
            "bank_name"=> $transfer->bank->name,
        ]);

        $notify[]=['success','Transfer rejeced successfully'];
        return back()->withNotify($notify);
    }

}
