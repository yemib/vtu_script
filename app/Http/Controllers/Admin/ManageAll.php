<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\network;
use App\Models\data;
use App\Models\airtime;
use App\Models\cable;
use App\Models\cable_plan;
use App\Models\bill;
use App\Models\role;


use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;


//add the details  to the databas here


class ManageAll extends Controller
{
    //add network


    public function default_prices()
    {

  $seller  = (isset($_GET['seller']))   ? $_GET['seller']  :  50  ;
  $selling  =  (isset($_GET['selling']))   ? $_GET['selling']  :  40  ; ;

        //add data to the table here fast iokaty
         //delete all and re-input  ;

         $delete_all  =  data::get() ;

         foreach(  $delete_all as $delete){

            data::find($delete->id)->delete();

         }

        $data  =  data::count();
        if ($data  ==  0) {

            $url = 'https://www.smartspeedtelecom.com/api/user/';
            $token = '31a4814f99b1e22e939639f056948ba2c63f487c';

            $headers = array(
                'Authorization: Token ' . $token,
                'Content-Type: application/json',
            );

            $ch = curl_init($url);

            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Disabling SSL verification (not recommended for production)

            $response = curl_exec($ch);

            if (curl_errno($ch)) {
                echo 'Curl error: ' . curl_error($ch);
            }
            curl_close($ch);
            $outoput  = json_decode($response,  true);
            //print_r($outoput['Dataplans']['MTN_PLAN']);
            //['Dataplans']['MTN_PLAN']['CORPORATE']

            $count = 0;
            foreach ($outoput['Dataplans']  as  $plans) {

                foreach ($plans as  $key => $plan) {
                    //create  all the packages here .

                 if($key  ==  'ALL'   && $plan[$count ]['network']  != 1){

                    foreach ($plan as  $key => $details) {
                        $data  =  new data();
                        foreach ($details  as $key => $detail) {

                                $table  =  "data" ;
                               if(!Schema::hasColumn($table  ,  $key )) {
                                DB::statement("ALTER TABLE $table
                                ADD $key varchar(600)  DEFAULT NULL");
                               }
                                 if( $key !=  'id'){
                                $data->{$key}  =   $detail ;

                                //check the key type to get the default price and  resseller price.
                                    if($key  ==  'plan_amount'){
                                        $data->default_price  =   $detail  +  $selling  ;
                                        $data->reseller_price  =   $detail  +  $seller  ;

                                    }


                                 }


                        }
                        $data->save();
                    }

                }

                if($key  !=  'ALL' ){

                    foreach ($plan as  $key => $details) {
                        $data  =  new data();
                        foreach ($details  as $key => $detail) {

                                $table  =  "data" ;
                               if(!Schema::hasColumn($table  ,  $key )) {
                                DB::statement("ALTER TABLE $table
                                ADD $key varchar(600)  DEFAULT NULL");
                               }
                                 if( $key !=  'id'){
                                $data->{$key}  =   $detail ;

                                if($key  ==  'plan_amount'){
                                    $data->default_price  =   $detail  +  $selling  ;
                                    $data->reseller_price  =   $detail  +  $seller  ;

                                }

                                 }


                        }
                        $data->save();
                    }


                }

                }

                // print_r($value['plan_amount']);

            }
        }
    }





    public function defaultprice(){

      $this->default_prices();
        //fast about this


        return back();
    }

    public function addtable(Request $request, $table, $model)
    {
        //add all the networks  okay .

        //get the table  name from the table   okay .


        try {
            DB::statement("CREATE TABLE $table(
    id int AUTO_INCREMENT  PRIMARY KEY,
    created_at varchar(600)  DEFAULT NULL,
    updated_at varchar(600)  DEFAULT NULL

  )");
        } catch (\Exception $e) {

            //return($e->getMessage());
        }


        foreach ($_POST as $key => $value) {

            try {
                DB::statement("ALTER TABLE $table
ADD $key varchar(600)  DEFAULT NULL");
            } catch (\Exception $e) {

                //return($e->getMessage());
                continue;
            }
        }
//now insert okay
        if (isset($request->id)) {

            $save   =   $model::find($request->id);
            //get active here
            //$this->validator($_POST, 'update')->validate();

        } else {
            //$this->validator($_POST, 'create')->validate();
            $save   =  new $model();
        }
        //now insert here  okay
        $columns = Schema::getColumnListing($table);
        //print($columns[3]);
        //$this->VariableArray();
        $count  =  1;
        foreach ($columns as $column) {
            if ($count >  3) {

                if (isset($request[$column])) {
                    if ($request[$column] != '') {
                        $save[$column]  = $request[$column];
                    }
                }
            }

            $count++;
        }


        $save->save();


        return ("done");
    }




    public function addnetworks(Request $request, network $network)
    {

        //send table name  and model
        $this->addtable($request,  'networks', $network);


        return  redirect('iamadmin@6019/network_list');
    }


    public function  networks(Request $request)
    {

        $pageTitle  =  "Network  List";
        $data  =  network::paginate(10);
        $emptyMessage  =  "No Data";
        $all = array('pageTitle' => $pageTitle,  'data' => $data,  'emptyMessage' => $emptyMessage);

        return  view('admin.network.list')->with($all);
    }
    public function editnetwork($id)
    {
        $pageTitle  = "Edit Network";
        //add it .
        $all  =  array('id' => $id,  'pageTitle'  =>  $pageTitle);
        return view('admin.network.add')->with($all);
    }

    public function deletenetworks($id)
    {
        //now delete it
        $del  = network::find($id);
        $del->delete();

        return back()->with('danger', "Deleted");
    }


    //data


    public function adddata(Request $request, data $data)
    {

        //send table name  and model
        $this->addtable($request,  'data', $data);


        return  redirect('iamadmin@6019/data_list');
    }


    public function  data(Request $request)
    {

        $pageTitle  =  "Data  List";
        $data  =  data::paginate(10);
        $emptyMessage  =  "No Data";
        $all = array('pageTitle' => $pageTitle,  'data' => $data,  'emptyMessage' => $emptyMessage);

        return  view('admin.data.list')->with($all);
    }
    public function editdata($id)
    {
        $pageTitle  = "Edit Data";
        //add it .
        $all  =  array('id' => $id,  'pageTitle'  =>  $pageTitle);
        return view('admin.data.add')->with($all);
    }

    public function deletedata($id)
    {
        //now delete it
        $del  = data::find($id);
        $del->delete();

        return back()->with('danger', "Deleted");
    }




    //Airtime


    public function addairtime(Request $request, airtime $airtime)
    {

        //send table name  and model
        $this->addtable($request,  'airtimes', $airtime);


        return  redirect('iamadmin@6019/airtime_list');
    }


    public function  airtime(Request $request)
    {

        $pageTitle  =  "Airtime  List";
        $data  =  airtime::paginate(10);
        $emptyMessage  =  "No Data";
        $all = array('pageTitle' => $pageTitle,  'data' => $data,  'emptyMessage' => $emptyMessage);

        return  view('admin.airtime.list')->with($all);
    }
    public function editairtime($id)
    {
        $pageTitle  = "Edit Airtime";
        //add it .
        $all  =  array('id' => $id,  'pageTitle'  =>  $pageTitle);
        return view('admin.airtime.add')->with($all);
    }

    public function deleteairtime($id)
    {
        //now delete it
        $del  = airtime::find($id);
        $del->delete();

        return back()->with('danger', "Deleted");
    }




    //Cable


    public function addcable(Request $request, cable $airtime)
    {
        //validate the  name.




        //send table name  and model
        $this->addtable($request,  'cables', $airtime);


        return  redirect('iamadmin@6019/cable_list');
    }


    public function  cable(Request $request)
    {

        $pageTitle  =  "Cable  List";
        $data  =  cable::paginate(10);
        $emptyMessage  =  "No Data";
        $all = array('pageTitle' => $pageTitle,  'data' => $data,  'emptyMessage' => $emptyMessage);

        return  view('admin.cable.list')->with($all);
    }
    public function editcable($id)
    {
        $pageTitle  = "Edit Cable";
        //add it .
        $all  =  array('id' => $id,  'pageTitle'  =>  $pageTitle);
        return view('admin.cable.add')->with($all);
    }

    public function deletecable($id)
    {
        //now delete it
        $del  = cable::find($id);
        $del->delete();

        return back()->with('danger', "Deleted");
    }





    //Cable  Plan


    public function addcable_plan(Request $request, cable_plan $data)
    {

        //send table name  and model
        $this->addtable($request,  'cable_plans', $data);


        return  redirect('iamadmin@6019/cable_plan_list');
    }


    public function  cable_plan(Request $request)
    {

        $pageTitle  =  "Cable Plan  List";
        $data  =  cable_plan::paginate(10);
        $emptyMessage  =  "No Data";
        $all = array('pageTitle' => $pageTitle,  'data' => $data,  'emptyMessage' => $emptyMessage);

        return  view('admin.cable_plan.list')->with($all);
    }
    public function editcable_plan($id)
    {
        $pageTitle  = "Edit Cable Plan";
        //add it .
        $all  =  array('id' => $id,  'pageTitle'  =>  $pageTitle);
        return view('admin.cable_plan.add')->with($all);
    }

    public function deletecable_plan($id)
    {
        //now delete it
        $del  = cable_plan::find($id);
        $del->delete();

        return back()->with('danger', "Deleted");
    }







    //Bill
    public function addbill(Request $request, bill $data)
    {

        //send table name  and model
        $this->addtable($request,  'bills', $data);


        return  redirect('iamadmin@6019/bill_list');
    }
    public function  bill(Request $request)
    {

        $pageTitle  =  "Bills  List";
        $data  =  bill::paginate(10);
        $emptyMessage  =  "No Data";
        $all = array('pageTitle' => $pageTitle,  'data' => $data,  'emptyMessage' => $emptyMessage);

        return  view('admin.bill.list')->with($all);
    }
    public function editbill($id)
    {
        $pageTitle  = "Edit Bill";
        //add it .
        $all  =  array('id' => $id,  'pageTitle'  =>  $pageTitle);
        return view('admin.bill.add')->with($all);
    }
    public function deletebill($id)
    {
        //now delete it
        $del  = bill::find($id);
        $del->delete();

        return back()->with('danger', "Deleted");
    }




    //Role
    public function addrole(Request $request, role $data)
    {

        $validated = $request->validate([

            'name' => isset($request->id)  ?  'required|string|max:255|unique:roles,name,' . $request->id  :   'required|unique:roles|max:255',


            'profit' => 'required',
        ]);




        //send table name  and model
        $this->addtable($request,  'roles', $data);


        return  redirect('iamadmin@6019/role_list');
    }
    public function  role(Request $request)
    {

        $pageTitle  =  "Role  List";
        $data  =  role::paginate(10);
        $emptyMessage  =  "No Data";
        $all = array('pageTitle' => $pageTitle,  'data' => $data,  'emptyMessage' => $emptyMessage);

        return  view('admin.role.list')->with($all);
    }
    public function editrole($id)
    {
        $pageTitle  = "Edit Role";
        //add it .
        $all  =  array('id' => $id,  'pageTitle'  =>  $pageTitle);
        return view('admin.role.add')->with($all);
    }
    public function deleterole($id)
    {
        //now delete it
        $del  = role::find($id);
        $del->delete();

        return back()->with('danger', "Deleted");
    }
}
