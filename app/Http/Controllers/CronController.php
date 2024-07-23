<?php

namespace App\Http\Controllers;

use App\Models\AdminNotification;
use App\Models\GeneralSetting;
use App\Models\InstallmentLog;
use App\Models\Transaction;
use App\Models\UserDps;
use App\Models\UserFdr;
use App\Models\UserLoan;
use Carbon\Carbon;

class CronController extends Controller
{
    public function dps()
    {
        $general = GeneralSetting::first();
        $general->last_dps_cron = Carbon::now();
        $general->save();

        $now    = Carbon::now()->format('y-m-d');

        $allDps = UserDps::where('status', 1)
                ->whereDate('next_installment_date', '<=', $now)
                ->whereColumn('total_installment', '>', 'given_installment')
                ->with('user', 'plan')
                ->get();

        foreach ($allDps as $dps) {
            $installmentAmount  = $dps->per_installment;
            $user               = $dps->user;

            if($user->balance < $installmentAmount){
                $adminNotification = new AdminNotification();
                $adminNotification->user_id = $user->id;
                $adminNotification->title = 'DPS Installment Due';
                $adminNotification->click_url = urlPath('admin.dps.unpaid');
                $adminNotification->save();

                notify($user, 'DPS_INSTALLMENT_DUE', [
                    "currency"              => $general->site_currency,
                    "plan_name"             => $dps->plan->name,
                    "plan_trx"              => $dps->plan->trx,
                    "due_installment"       => $dps->due_installment,
                    "per_installment"       => $dps->per_installment,
                    "given_installment"     => $dps->per_installment,
                    "installment_interval"  => $dps->isntallment_interval,
                    "total_installment"     => $dps->total_installment,
                ]);

            }else{

                $dps->given_installment   += 1;

                $dps->next_installment_date	= $dps->next_installment_date->addDays($dps->installment_interval+1);
                $dps->save();

                $user->balance -= $installmentAmount;
                $user->save();
                $this->createLog($dps->id, 'DPS', $installmentAmount);
                $transaction = new Transaction();
                $transaction->user_id       = $user->id;
                $transaction->amount        = $installmentAmount;
                $transaction->post_balance  = $user->balance;
                $transaction->charge        = 0;
                $transaction->trx_type      = '-';
                $transaction->details       = 'DPS Installment Given';
                $transaction->trx           =  $dps->trx;
                $transaction->save();
            }

            if($dps->given_installment >= $dps->total_installment){
                $dps->status = 2;
                $dps->save();

                notify($user, 'DPS_MATURED', [
                    "currency"              => $general->site_currency,
                    "plan_name"             => $dps->plan->name,
                    "plan_trx"              => $dps->plan->trx,
                    "due_installment"       => $dps->due_installment,
                    "per_installment"       => $dps->per_installment,
                    "given_installment"     => $dps->per_installment,
                    "installment_interval"  => $dps->isntallment_interval,
                    "total_installment"     => $dps->total_installment,
                ]);
            }
        }
    }

    public function loan()
    {
        $general = GeneralSetting::first();
        $general->last_loan_cron = Carbon::now();
        $general->save();

        $now    = Carbon::now()->format('y-m-d');
        $loans  = UserLoan::whereDate('next_installment_date', '<=', $now)
                    ->whereColumn('total_installment', '>', 'given_installment')
                    ->where('status', 1)
                    ->with('user', 'plan')
                    ->get();

        foreach ($loans as $loan) {
            $installmentAmount  = $loan->per_installment;
            $user               = $loan->user;
            if($user->balance < $installmentAmount){

                if(!$loan->due_count_date || $loan->due_count_date != $loan->next_installment_date){

                    $adminNotification = new AdminNotification();
                    $adminNotification->user_id = $user->id;
                    $adminNotification->title = 'Loan Installment Due';
                    $adminNotification->click_url = urlPath('admin.loan.unpaid');
                    $adminNotification->save();

                    notify($user, 'LOAN_INSTALLMENT_DUE', [
                        "currency"              => $general->site_currency,
                        "plan_name"             => $loan->plan->name,
                        "plan_trx"              => $loan->plan->trx,
                        "due_installment"       => $loan->due_installment,
                        "per_installment"       => $loan->per_installment,
                        "given_installment"     => $loan->per_installment,
                        "installment_interval"  => $loan->isntallment_interval,
                        "total_installment"     => $loan->total_installment,
                    ]);
                }

            }else{
                if($loan->due_installment){
                    $loan->due_installment     -= 1;
                }else{
                    $loan->given_installment   += 1;
                }
                $loan->next_installment_date	= $loan->next_installment_date->addDays($loan->installment_interval);
                $loan->paid_amount += $installmentAmount;
                $loan->save();
                $user->balance -= $installmentAmount;
                $user->save();
                $this->createLog($loan->id, 'LOAN', $installmentAmount);
                $transaction = new Transaction();
                $transaction->user_id       = $user->id;
                $transaction->amount        = $installmentAmount;
                $transaction->post_balance  = $user->balance;
                $transaction->charge        = 0;
                $transaction->trx_type      = '-';
                $transaction->details       = 'Loan Installment Given';
                $transaction->trx           =  $loan->trx;
                $transaction->save();
            }


            if( $loan->given_installment >= $loan->total_installment){
                $loan->status = 2;
                $loan->save();

                notify($user, 'LOAN_COMPLETED', [
                    "currency"              => $general->site_currency,
                    "plan_name"             => $loan->plan->name,
                    "plan_trx"              => $loan->plan->trx,
                    "due_installment"       => $loan->due_installment,
                    "per_installment"       => $loan->per_installment,
                    "given_installment"     => $loan->per_installment,
                    "installment_interval"  => $loan->isntallment_interval,
                    "total_installment"     => $loan->total_installment,
                ]);
            }
        }
    }

    public function fdr()
    {
        $general = GeneralSetting::first();
        $general->last_fdr_cron = Carbon::now();
        $general->save();

        $now    = Carbon::now()->format('y-m-d');

        $allFdr = UserFdr::whereDate('next_return_date', '<=', $now)
                            ->where('status', 1)
                            ->with('user', 'plan')
                            ->get();

        foreach ($allFdr as $fdr) {
            $interest = $fdr->interest;
            $user = $fdr->user;
            $fdr->next_return_date	= $fdr->next_return_date->addDays($fdr->interest_interval+1);
            $fdr->profit	        += $interest;
            $fdr->save();

            $user->balance += $interest;
            $user->save();
            $this->createLog($fdr->id, 'FDR', $interest);

            $transaction = new Transaction();
            $transaction->user_id       = $user->id;
            $transaction->amount        = $interest;
            $transaction->post_balance  = $user->balance;
            $transaction->charge        = 0;
            $transaction->trx_type      = '+';
            $transaction->details       = 'Received FDR Interest';
            $transaction->trx           =  $fdr->trx;
            $transaction->save();
        }
    }

    public function createLog($id, $type, $amount)
    {
        $log            = new InstallmentLog();
        $log->f_id      = $id;
        $log->type      = $type;
        $log->amount    = $amount;
        $log->save();
    }

}



