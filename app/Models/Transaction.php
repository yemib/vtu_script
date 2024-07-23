<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;



class Transaction extends Model
{
	//create contructor  
	
	
	    public function __construct()
    {
      
						try{
			DB::statement("CREATE TABLE vtu_transactions(
    id int AUTO_INCREMENT  PRIMARY KEY,
    created_at varchar(600)  DEFAULT NULL,
    updated_at varchar(600)  DEFAULT NULL,
	user_id  int DEFAULT NULL  ,  
	amount  decimal(28,8) DEFAULT 0.00000000	  ,  
	charge  decimal(28,8) DEFAULT 0.00000000	  ,  
	post_balance  decimal(28,8) DEFAULT 0.00000000	  ,  
	trx_type  varchar(40) DEFAULT NULL	  ,  
		trx  varchar(40) DEFAULT NULL	  ,  
		details  varchar(255) DEFAULT NULL	   
	
	
  )");
	}catch(\Exception $e){

			//echo($e->getMessage());
		}
		
		
    }

	
	
    protected $table = "vtu_transactions";
	
	

    protected  $guarded = ['id'];
    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
