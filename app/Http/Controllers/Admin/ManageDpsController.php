<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DpsPlan;
use App\Models\InstallmentLog;
use App\Models\UserDps;
use Illuminate\Http\Request;

class ManageDpsController extends Controller
{
    public function index()
    {
        $pageTitle      = 'All DPS (Deposit Pension Scheme)';

        if(request()->search){
            $query          = UserDps::where('trx', request()->search);
            $emptyMessage   = 'No Data Found';
        }else{
            $query         = UserDps::latest();
            $emptyMessage   = 'No DPS Yet';
        }

        $data = $query->with('user','plan')->paginate(getPaginate());
        return view('admin.dps.index', compact('pageTitle', 'emptyMessage', 'data'));
    }

    public function runningDps()
    {
        $pageTitle      = 'Running DPS (Deposit Pension Scheme)';
        $emptyMessage   = 'No Running DPS Yet';

        if(request()->search){
            $query           = UserDps::running()->where('trx', request()->search);
            $emptyMessage   = 'No Data Found';
        }else{
            $query           = UserDps::running()->latest();
        }

        $data = $query->with('user','plan')->paginate(getPaginate());
        return view('admin.dps.index', compact('pageTitle', 'emptyMessage', 'data'));
    }


    public function maturedDps()
    {
        $pageTitle      = 'Matured DPS (Deposit Pension Scheme)';
        $emptyMessage   = 'No Matured DPS Yet';

        if(request()->search){
            $query           = UserDps::matured()->where('trx', request()->search);
            $emptyMessage   = 'No Data Found';
        }else{
            $query           = UserDps::matured()->latest();
        }

        $data = $query->with('user','plan')->paginate(getPaginate());
        return view('admin.dps.index', compact('pageTitle', 'emptyMessage', 'data'));
    }

    public function dueInstallment()
    {
        $pageTitle      = 'Due Installment DPS (Deposit Pension Scheme)';
        $emptyMessage   = 'No Due Installment DPS Found';

        if(request()->search){
            $query           = UserDps::due()->where('trx', request()->search);
            $emptyMessage   = 'No Data Found';
        }else{
            $query           = UserDps::due()->latest();
        }

        $data = $query->with('user','plan')->paginate(getPaginate());

        return view('admin.dps.index', compact('pageTitle', 'emptyMessage', 'data'));
    }

    public function plans()
    {
        $pageTitle      = 'All Plans for DPS (Deposit Pension Scheme)';
        $emptyMessage   = 'No plan yet';
        $plans          = DpsPlan::latest()->paginate(getPaginate());
        return view('admin.dps.plan.index', compact('pageTitle', 'emptyMessage', 'plans'));
    }

    public function installments($id)
    {
        $dps            = UserDps::findOrFail($id);
        $installments   = InstallmentLog::dpsInstallments($id);
        $pageTitle      = "Installmetnt Logs of DPS: $dps->trx";
        $emptyMessage   = 'No installment given yet';
        return view('admin.dps.installments', compact('pageTitle', 'emptyMessage', 'installments'));
    }

    public function savePlan(Request $request)
    {
        $request->validate([
            'name'                  => 'required|max:100',
            'installment_interval'  => 'required|integer|gt:0',
            'total_installment'     => 'required|integer|gt:0',
            'per_installment'       => 'required|numeric|gt:0',
            'interest_rate'         => 'required|numeric|gte:0',
        ]);

        $totalDeposit   = $request->total_installment * $request->per_installment;
        $finalAmount    = $totalDeposit + ($totalDeposit * $request->interest_rate / 100);

        $plan                        = new DpsPlan();
        $plan->name                  = $request->name;
        $plan->total_installment     = $request->total_installment;
        $plan->installment_interval  = $request->installment_interval;
        $plan->per_installment       = $request->per_installment;
        $plan->interest_rate         = $request->interest_rate;
        $plan->final_amount          = getAmount($finalAmount);
        $plan->save();

        $notify[] = ['success', 'Plan Added Successfully'];
        return redirect()->route('admin.dps.plan.index')->withNotify($notify);
    }

    public function updatePlan(Request $request, $id)
    {
        $request->validate([
            'name'                  => 'required|max:100',
            'installment_interval'  => 'required|integer|gt:0',
            'total_installment'     => 'required|integer|gt:0',
            'per_installment'       => 'required|numeric|gt:0',
            'interest_rate'         => 'required|numeric|gte:0',
        ]);

        $plan                        = DpsPlan::findOrFail($id);
        $totalDeposit   = $request->total_installment * $request->per_installment;
        $finalAmount    = $totalDeposit + ($totalDeposit * $request->interest_rate / 100);

        $plan->name                  = $request->name;
        $plan->total_installment     = $request->total_installment;
        $plan->installment_interval  = $request->installment_interval;
        $plan->per_installment       = $request->per_installment;
        $plan->interest_rate         = $request->interest_rate;
        $plan->final_amount          = getAmount($finalAmount);
        $plan->save();

        $notify[] = ['success', 'Plan Updated Successfully'];
        return redirect()->route('admin.dps.plan.index')->withNotify($notify);
    }

    public function changeStatus(Request $request)
    {
        $request->validate([
            'id' => 'required|integer',
        ]);

        $plan = DpsPlan::findOrFail($request->id);

        if($plan->status == 1){
            $plan->status = 0;
            $notify[] = ['success', $plan->name.' has been disabled successfully'];
        }else{
            $plan->status = 1;
            $notify[] = ['success', $plan->name.' has been enabled successfully'];
        }

        $plan->save();
        return redirect()->back()->withNotify($notify);
    }
}
