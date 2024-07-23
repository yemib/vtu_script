<?php

use  App\Models\data  ;
use  App\Models\airtime  ;
use  App\Models\network ;
use Illuminate\Http\Request;



//get the list of  type  ..


Route::any('airtimeorder'  ,  function(Request $request){
	//now send neccessary info okay
	$result  =  '<option>Select Type...</option>';
	$datas  = airtime::where('network'  , $request->network)->get();
	foreach($datas  as   $resultt){
//$result  .= '<option value="'.$resultt->plan_code.'">' .$resultt->plan   .  '</option>';
$result  .=		'<option value="'.$resultt->type.'">'.$resultt->type.' </option>'  ;
			}
				
		return  ($result);
	
}  );




