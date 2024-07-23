<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GeneralSetting;
use App\Models\InstallmentLog;
use App\Models\LoanPlan;
use App\Models\Transaction;
use App\Models\UserLoan;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ManageLoanController extends Controller
{
    public function allLoans()
    {
        $pageTitle      = 'All Loans';

        if(request()->search){
            $query          = UserLoan::where('trx', request()->search);
            $emptyMessage   = 'No Data Found';
        }else{
            $query          = UserLoan::latest();
            $emptyMessage   = 'No Loan Yet';
        }

        $loans = $query->with('user', 'plan')->paginate(getPaginate());

        return view('admin.loan.index', compact('pageTitle', 'emptyMessage', 'loans'));
    }

    public function runningLoans()
    {
        $pageTitle      = 'Running Loans';

        if(request()->search){
            $query          = UserLoan::running()->where('trx', request()->search);
            $emptyMessage   = 'No Data Found';
        }else{
            $query          = UserLoan::running()->latest();
            $emptyMessage   = 'No Running Loan Yet';
        }

        $loans = $query->with('user', 'plan')->paginate(getPaginate());

        return view('admin.loan.index', compact('pageTitle', 'emptyMessage', 'loans'));
    }


    public function pendingLoans()
    {
        $pageTitle      = 'Pending Loans';

        if(request()->search){
            $query          = UserLoan::pending()->where('trx', request()->search);
            $emptyMessage   = 'No Data Found';
        }else{
            $query          = UserLoan::pending()->latest();
            $emptyMessage   = 'No Pending Loan Yet';
        }

        $loans = $query->with('user', 'plan')->paginate(getPaginate());


        return view('admin.loan.index', compact('pageTitle', 'emptyMessage', 'loans'));
    }

    public function paidLoans()
    {
        $pageTitle      = 'Paid Loans';


        if(request()->search){
            $query          = UserLoan::paid()->where('trx', request()->search);
            $emptyMessage   = 'No Data Found';
        }else{
            $query          = UserLoan::paid()->latest();
            $emptyMessage   = 'No Paid Loan Yet';
        }

        $loans = $query->with('user', 'plan')->paginate(getPaginate());

        return view('admin.loan.index', compact('pageTitle', 'emptyMessage', 'loans'));
    }

    public function rejectedLoans()
    {
        $pageTitle      = 'Rejected Loans';

        if(request()->search){
            $query          = UserLoan::rejected()->where('trx', request()->search);
            $emptyMessage   = 'No Data Found';
        }else{
            $query          = UserLoan::rejected()->latest();
            $emptyMessage   = 'No Rejected Loan Yet';
        }

        $loans = $query->with('user', 'plan')->paginate(getPaginate());

        return view('admin.loan.index', compact('pageTitle', 'emptyMessage', 'loans'));
    }



    public function dueInstallment()
    {
        $pageTitle      = 'Due Installment Loans';

        if(request()->search){
            $query          = UserLoan::due()->where('trx', request()->search);
            $emptyMessage   = 'No Data Found';
        }else{
            $query          = UserLoan::due()->latest();
            $emptyMessage   = 'No Due Installment DPS Yet';
        }

        $loans = $query->with('user', 'plan')->paginate(getPaginate());

        return view('admin.loan.index', compact('pageTitle', 'emptyMessage', 'loans'));
    }

    public function details($id)
    {
        $loan           = UserLoan::where('id', $id)->with('plan', 'user')->firstOrFail();
        $installments   = InstallmentLog::loanInstallments($id);
        $pageTitle      = 'All Loans';
        $emptyMessage   = 'No Loan Yet';
        return view('admin.loan.details', compact('pageTitle', 'emptyMessage', 'loan', 'installments'));
    }

    public function plans()
    {
        $pageTitle      = 'All Plans for Loan';
        $emptyMessage   = 'No Plan Yet';
        $plans          = LoanPlan::latest()->paginate(getPaginate());
        return view('admin.loan.plan.index', compact('pageTitle', 'emptyMessage', 'plans'));
    }

    public function createPlan()
    {
        $pageTitle = 'Create New Plan';
        return view('admin.loan.plan.create', compact('pageTitle'));
    }
    public function editPlan($id)
    {
        $pageTitle      = 'Edit Plan';
        $plan           = LoanPlan::findOrFail($id);
        $fieldsCount    = 0;
        $fields         = null;

        if($plan->required_information){
            $fields = json_decode($plan->required_information);
            $fieldsCount = count($fields);
        }

        return view('admin.loan.plan.edit', compact('pageTitle', 'plan', 'fields', 'fieldsCount'));
    }

    public function savePlan(Request $request)
    {
        $request->validate([
            'name'                  => 'required|max:100',
            'total_installment'     => 'required|integer|gt:0',
            'installment_interval'  => 'required|integer|gt:0',
            'per_installment'       => 'required|numeric|gt:0',
            'minimum_amount'        => 'required|numeric|gt:0',
            'maximum_amount'        => 'required|numeric|gt:minimum_amount',
            'instruction'           => 'nullable|max:64000',
            'field_name.*'          => 'sometimes|required',
            'type.*'                => 'sometimes|required|in:text,textarea,file',
            'validation.*'          => 'sometimes|required|in:required,nullable',
        ],[
            'field_name.*.required'=>'All field is required'
        ]);

        $plan                         = new LoanPlan();
        $plan->name                   = $request->name;
        $plan->total_installment      = $request->total_installment;
        $plan->installment_interval   = $request->installment_interval;
        $plan->per_installment        = $request->per_installment;
        $plan->minimum_amount         = $request->minimum_amount;
        $plan->maximum_amount         = $request->maximum_amount;
        $plan->required_information   = $request->input_form ? json_encode($request->input_form) : null;
        $plan->instruction            = $request->instruction??null;
        $plan->save();

        $notify[] = ['success', 'Plan Added Successfully'];
        return redirect()->route('admin.loan.plan.index')->withNotify($notify);
    }

    public function updatePlan(Request $request, $id)
    {
        $request->validate([
            'name'                      => 'required|max:100',
            'total_installment'         => 'required|integer|gt:0',
            'installment_interval'      => 'required|integer|gt:0',
            'per_installment'           => 'required|numeric|gt:0',
            'minimum_amount'            => 'required|numeric|gt:0',
            'maximum_amount'            => 'required|numeric|gt:minimum_amount',
            'instruction'               => 'nullable|max:64000',
            'input_form'                => 'sometimes|required|array',
            'input_form.*.field_name'   => 'sometimes|required|string',
            'input_form.*.type'         => 'sometimes|required|in:text,textarea,file',
            'input_form.*validation'    => 'sometimes|required|in:required,nullable',
        ],[
            'input_form.*.field_name'   =>'All Required Information field is required',
            'input_form.*.type'         =>'All Required Information field is required',
            'input_form.*.validation'   =>'All Required Information field is required'
        ]);

        $plan                         = LoanPlan::findOrFail($id);
        $plan->name                   = $request->name;
        $plan->total_installment      = $request->total_installment;
        $plan->installment_interval   = $request->installment_interval;
        $plan->per_installment        = $request->per_installment;
        $plan->minimum_amount         = $request->minimum_amount;
        $plan->maximum_amount         = $request->maximum_amount;
        $plan->required_information   = $request->input_form ? json_encode($request->input_form) : null;
        $plan->instruction            = $request->instruction??null;
        $plan->save();

        $notify[] = ['success', 'Plan Updated Successfully'];
        return back()->withNotify($notify);
    }

    public function changeStatus(Request $request)
    {
        $request->validate([
            'id' => 'required|integer',
        ]);

        $plan = LoanPlan::findOrFail($request->id);

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

    public function approve(Request $request)
    {
        $request->validate([
            'id'        => 'required|integer',
        ]);
        $loan = UserLoan::where('id', $request->id)->with('user', 'plan')->firstOrFail();
        $loan->status = 1;
        $loan->next_installment_date	= Carbon::now()->addDays($loan->installment_interval);
        $loan->save();
        $user = $loan->user;
        $user->balance += getAmount($loan->amount);
        $user->save();

        $transaction                = new Transaction();
        $transaction->user_id       = $user->id;
        $transaction->amount        = $loan->amount;
        $transaction->post_balance  = $user->balance;
        $transaction->charge        = 0;
        $transaction->trx_type      = '+';
        $transaction->details       = 'Loan Request Approved & Amount Added to The Balance';
        $transaction->trx           =  getTrx();
        $transaction->save();

        $general = GeneralSetting::first();

        notify($user, 'LOAN_APPROVED', [
            'trx'                   => $transaction->trx,
            'amount'                => showAmount($loan->amount),
            'currency'              => $general->cur_text,
            'post_balance'          => showAmount($user->balance),
            'plan_name'             => $loan->plan->name,
            'per_installment'       => $loan->plan->per_installment,
            'installment_interval'  => $loan->plan->installment_interval,
            'post_balance'          => $user->balance,
            'final_amount'          => $loan->final_amount
        ]);

        $notify[]=['success','Loan approved successfully'];
        return back()->withNotify($notify);
    }

    public function reject(Request $request)
    {
        $request->validate([
            'id'        => 'required|integer',
            'details'   => 'required|string'
        ]);

        $loan = UserLoan::where('id', $request->id)->with('user', 'plan')->firstOrFail();
        $loan->status           = 3;
        $loan->admin_feedback   = $request->details;
        $loan->save();

        $user                   = $loan->user;
        $general                = GeneralSetting::first();

        notify($user, 'LOAN_REJECTED', [
            'amount'                => showAmount($loan->amount),
            'currency'              => $general->cur_text,
            'post_balance'          => showAmount($user->balance),
            'plan_name'             => $loan->plan->name,
            'per_installment'       => $loan->plan->per_installment,
            'installment_interval'  => $loan->plan->installment_interval,
            'post_balance'          => $user->balance,
            'final_amount'          => $loan->final_amount
        ]);

        $notify[]=['success','Loan rejected successfully'];
        return back()->withNotify($notify);
    }

}
