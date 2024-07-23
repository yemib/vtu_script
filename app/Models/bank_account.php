<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;



class bank_account extends Model
{
	//create contructor  
	
	
	    public function __construct()
    {
      
						try{
			DB::statement("CREATE TABLE bank_accounts(
    id int AUTO_INCREMENT  PRIMARY KEY,
    created_at varchar(600)  DEFAULT NULL,
    updated_at varchar(600)  DEFAULT NULL,
	user_id  int DEFAULT NULL  ,  
	bankCode  varchar(400) DEFAULT NULL	  ,  
	bankName  varchar(400) DEFAULT NULL	  ,  
	accountNumber varchar(400) DEFAULT NULL	 , 
	accountName varchar(400) DEFAULT NULL	,  
	accountReference varchar(400) DEFAULT NULL	,  
	customerName varchar(400) DEFAULT NULL	  
	
	
  )");
	}catch(\Exception $e){

			//echo($e->getMessage());
		}
		
		
    }

	
	
    protected $table = "bank_accounts";
	
	

 

}
