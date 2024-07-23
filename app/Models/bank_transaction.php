<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class bank_transaction extends Model
{
	
	
	//create table and insert into payment gateway.
		
	
	    public function __construct()
    {
      
						try{
			DB::statement("CREATE TABLE bank_transactions(
    id int AUTO_INCREMENT  PRIMARY KEY,
    created_at varchar(600)  DEFAULT NULL,
    updated_at varchar(600)  DEFAULT NULL,
	user_id  int DEFAULT NULL  ,  
	amount  text DEFAULT NULL	,  
	paymentReference  text DEFAULT NULL	  
	  
	
	
  )");
							
	//insert  into payment gateway.  
							
			$save  =  new Gateway();
			$save->	code  =  200	;
			$save->name  =  "Monnify";
			$save->alias  =  "Monnify"; 
			$save->	save();				
							
							
							
							
							
							
	}catch(\Exception $e){

			//echo($e->getMessage());
		}
		
		
    }

	
	
	
	
    use HasFactory;
}
