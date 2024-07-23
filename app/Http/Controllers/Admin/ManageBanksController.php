<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OtherBank;
use Illuminate\Http\Request;

class ManageBanksController extends Controller
{
    public function index(){
        $pageTitle = 'Other Banks';
        if(request()->search){
            $query = OtherBank::where('name', 'LIKE', '%'.request()->search.'%')->latest();
            $emptyMessage = 'No Data Found';
        }else{
            $query = OtherBank::latest();
            $emptyMessage = 'No Bank Yet';
        }

        $banks = $query->paginate(getPaginate());
        return view('admin.other_banks.index', compact('pageTitle', 'banks', 'emptyMessage'));
    }

    public function createOtherBank()
    {
        $pageTitle = 'Add Other Bank';
        return view('admin.other_banks.create', compact('pageTitle'));
    }

    public function editOtherBank($id)
    {
        $bank       = OtherBank::findOrFail($id);
        $pageTitle  = "Edit Bank";
        $fieldsCount    = 0;
        $fields         = null;
        if($bank->user_data){
            $fields         = $bank->user_data;
            $fieldsCount    = count($fields);
        }
        return view('admin.other_banks.edit', compact('pageTitle', 'bank', 'fields', 'fieldsCount'));
    }

    public function storeOtherBank (Request $request)
    {
        $request->validate([
            'name'                      => 'required|string|max:100',
            'processing_time'           => 'required|string|max:100',
            'minimum_amount'            => 'required|numeric|gt:0',
            'maximum_amount'            => 'required|numeric|gt:minimum_amount',
            'daily_maximum_amount'      => 'required|numeric|gte:maximum_amount',
            'monthly_maximum_amount'    => 'required|numeric|gte:maximum_amount',
            'daily_transaction_count'   => 'required|integer|gt:0',
            'monthly_transaction_count' => 'required|integer|gt:0',
            'fixed_charge'              => 'required|numeric|gte:0',
            'percent_charge'            => 'required|numeric|gte:0',
            'instruction'               => 'nullable|max:64000',
            'field_name.*'              => 'sometimes|required',
            'type.*'                    => 'sometimes|required|in:text,textarea,file',
            'validation.*'              => 'sometimes|required|in:required,nullable',
        ],[
            'field_name.*.required'=>'All field is required'
        ]);

        $bank                            = new OtherBank();
        $bank->name                      = $request->name;
        $bank->minimum_limit             = $request->minimum_amount;
        $bank->maximum_limit             = $request->maximum_amount;
        $bank->daily_maximum_limit       = $request->daily_maximum_amount;
        $bank->monthly_maximum_limit     = $request->monthly_maximum_amount;
        $bank->daily_total_transaction   = $request->daily_transaction_count;
        $bank->monthly_total_transaction = $request->monthly_transaction_count;
        $bank->fixed_charge              = $request->fixed_charge;
        $bank->percent_charge            = $request->percent_charge;
        $bank->processing_time           = $request->processing_time;
        $bank->user_data                 = $request->input_form ? array_values($request->input_form) : null;
        $bank->instruction               = $request->instruction;
        $bank->save();

        $notify[] = ['success', 'New Bank Added Successfully'];
        return redirect()->route('admin.bank.index')->withNotify($notify);
    }

    public function updateOtherBank (Request $request, $id)
    {
        $request->validate([
            'name'                      => 'required|string|max:100',
            'processing_time'           => 'required|string|max:100',
            'minimum_amount'            => 'required|numeric|gt:0',
            'maximum_amount'            => 'required|numeric|gt:minimum_amount',
            'daily_maximum_amount'      => 'required|numeric|gte:maximum_amount',
            'monthly_maximum_amount'    => 'required|numeric|gte:maximum_amount',
            'daily_transaction_count'   => 'required|integer|gt:0',
            'monthly_transaction_count' => 'required|integer|gt:0',
            'fixed_charge'              => 'required|numeric|gte:0',
            'percent_charge'            => 'required|numeric|gte:0',
            'instruction'               => 'nullable|max:64000',
            'input_form'                => 'nullable|array',
            'field_name.*'              => 'sometimes|required',
            'type.*'                    => 'sometimes|required|in:text,textarea,file',
            'validation.*'              => 'sometimes|required|in:required,nullable',
        ],[
            'field_name.*.required'     =>'All field is required'
        ]);


        $bank                            = OtherBank::findOrFail($id);
        $bank->name                      = $request->name;
        $bank->minimum_limit             = $request->minimum_amount;
        $bank->maximum_limit             = $request->maximum_amount;
        $bank->daily_maximum_limit       = $request->daily_maximum_amount;
        $bank->monthly_maximum_limit     = $request->monthly_maximum_amount;
        $bank->daily_total_transaction   = $request->daily_transaction_count;
        $bank->monthly_total_transaction = $request->monthly_transaction_count;
        $bank->fixed_charge              = $request->fixed_charge;
        $bank->percent_charge            = $request->percent_charge;
        $bank->processing_time           = $request->processing_time;
        $bank->user_data                 = $request->input_form ? array_values($request->input_form) : null;
        $bank->instruction               = $request->instruction;
        $bank->save();

        $notify[] = ['success', 'Bank Updated Successfully'];
        return back()->withNotify($notify);
    }

    public function changeStatus(Request $request)
    {
        $request->validate([
            'id' => 'required|integer',
        ]);

        $bank = OtherBank::findOrFail($request->id);

        if($bank->status == 1){
            $bank->status = 0;
            $notify[] = ['success', $bank->name.' has been disabled successfully'];
        }else{
            $bank->status = 1;
            $notify[] = ['success', $bank->name.' has been enabled successfully'];
        }

        $bank->save();
        return redirect()->back()->withNotify($notify);
    }
}
