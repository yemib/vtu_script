<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class bonus_deposit extends Model
{
    use HasFactory;
	
	
	//create database  contructor .....  
	public  function  __contructor(){
						try{
			DB::statement("CREATE TABLE bonus_deposits(
    id int AUTO_INCREMENT  PRIMARY KEY,
    created_at varchar(600)  DEFAULT NULL,
    updated_at varchar(600)  DEFAULT NULL,
	
	ref_by int DEFAULT NULL  ,  
	who_id  int  DEFAULT NULL  ,
	amount decimal(28,8) DEFAULT 0.00000000  

  )");
	}catch(\Exception $e){

			//echo($e->getMessage());
		}
		
	}
	
}
