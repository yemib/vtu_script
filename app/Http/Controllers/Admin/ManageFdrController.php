<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FdrPlan;
use App\Models\InstallmentLog;
use App\Models\UserFdr;
use Illuminate\Http\Request;

class ManageFdrController extends Controller
{

    public function index()
    {
        $pageTitle      = 'All FDR (Fixed Deposit Receipt)';
        $emptyMessage   = 'No FDR Yet';

        if(request()->search){
            $query           = UserFdr::where('trx', request()->search);
            $emptyMessage   = 'No Data Found';
        }else{
            $query           = UserFdr::latest()->with('plan');
        }

        $data = $query->with('user','plan')->paginate(getPaginate());

        return view('admin.fdr.index', compact('pageTitle', 'emptyMessage', 'data'));
    }

    public function runningFdr()
    {
        $pageTitle      = 'Running FDR (Fixed Deposit Receipt)';

        if(request()->search){
            $query           = UserFdr::running()->where('trx', request()->search);
            $emptyMessage   = 'No Data Found';
        }else{
            $query         = UserFdr::running()->latest();
            $emptyMessage   = 'No Running FDR Yet';
        }

        $data = $query->with('user','plan')->paginate(getPaginate());

        return view('admin.fdr.index', compact('pageTitle', 'emptyMessage', 'data'));
    }

    public function closedFdr()
    {
        $pageTitle      = 'Closed FDR (Fixed Deposit Receipt)';

        if(request()->search){
            $query           = UserFdr::closed()->where('trx', request()->search);
            $emptyMessage   = 'No Data Found';
        }else{
            $query         = UserFdr::closed()->latest();
            $emptyMessage   = 'No Closed FDR Yet';
        }

        $data = $query->with('user','plan')->paginate(getPaginate());
        return view('admin.fdr.index', compact('pageTitle', 'emptyMessage', 'data'));
    }

    public function dueInstallment()
    {
        $pageTitle      = 'Due Installment FDR (Fixed Deposit Receipt)';

        if(request()->search){
            $query           = UserFdr::due()->where('trx', request()->search);
            $emptyMessage   = 'No Data Found';
        }else{
            $query         = UserFdr::due()->latest();
            $emptyMessage   = 'No Due Installment FDR Yet';
        }

        $data = $query->with('user','plan')->paginate(getPaginate());
        return view('admin.fdr.index', compact('pageTitle', 'emptyMessage', 'data'));
    }

    public function installments($id)
    {
        $fdr            = UserFdr::findOrFail($id);
        $installments   = InstallmentLog::fdrInstallments($id);
        $pageTitle      = "Installment Logs of FDR: $fdr->trx";
        $emptyMessage   = 'No installment given yet';
        return view('admin.fdr.installments', compact('pageTitle', 'emptyMessage', 'installments'));
    }

    public function plans()
    {
        $pageTitle      = 'All Plans for FDR (Fixed Deposit Receipt)';
        $emptyMessage   = 'No Plan Yet';
        $plans          = FdrPlan::latest()->paginate(getPaginate());
        return view('admin.fdr.plan.index', compact('pageTitle', 'emptyMessage', 'plans'));
    }

    public function savePlan(Request $request)
    {
        $request->validate([
            'name'                => 'required|max:100',
            'interest_rate'       => 'required|integer|gt:0',
            'interest_interval'   => 'required|integer|gt:0',
            'locked_days'         => 'required|numeric|gt:0',
            'minimum_amount'      => 'required|numeric|gt:0',
            'maximum_amount'      => 'required|numeric|gt:minimum_amount',
        ]);

        $plan                     = new FdrPlan();
        $plan->name               = $request->name;
        $plan->interest_interval  = $request->interest_interval;
        $plan->interest_rate      = $request->interest_rate;
        $plan->locked_days        = $request->locked_days;
        $plan->minimum_amount     = $request->minimum_amount;
        $plan->maximum_amount     = $request->maximum_amount;
        $plan->save();

        $notify[] = ['success', 'Plan Added Successfully'];
        return redirect()->route('admin.fdr.plan.index')->withNotify($notify);
    }

    public function updatePlan(Request $request, $id)
    {
        $request->validate([
            'name'                => 'required|max:100',
            'interest_rate'       => 'required|integer|gt:0',
            'interest_interval'   => 'required|integer|gt:0',
            'locked_days'         => 'required|numeric|gt:0',
            'minimum_amount'      => 'required|numeric|gt:0',
            'maximum_amount'      => 'required|numeric|gt:minimum_amount',
        ]);

        $plan                     = FdrPlan::findOrFail($id);
        $plan->name               = $request->name;
        $plan->interest_interval  = $request->interest_interval;
        $plan->interest_rate      = $request->interest_rate;
        $plan->locked_days        = $request->locked_days;
        $plan->minimum_amount     = $request->minimum_amount;
        $plan->maximum_amount     = $request->maximum_amount;
        $plan->save();

        $notify[] = ['success', 'Plan Added Successfully'];
        return redirect()->route('admin.fdr.plan.index')->withNotify($notify);
    }


    public function changeStatus(Request $request)
    {
        $request->validate([
            'id' => 'required|integer',
        ]);

        $plan = FdrPlan::findOrFail($request->id);

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
