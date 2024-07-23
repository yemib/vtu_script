<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB  ;

class AdminNotification extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
	
	
	   protected $table = "vtu_admin_notifications";
	
	
		
	    public function __construct()
    {
      
						try{
			DB::statement("CREATE TABLE vtu_admin_notifications(
    id int AUTO_INCREMENT  PRIMARY KEY,
    created_at varchar(600)  DEFAULT NULL,
    updated_at varchar(600)  DEFAULT NULL,
	user_id  int DEFAULT NULL  ,  
		title  varchar(600) DEFAULT NULL	  ,  
		read_status	  tinyint(1) DEFAULT 0	  ,  
	
	click_url  text DEFAULT NULL	   
		
	
  )");
	}catch(\Exception $e){

			//echo($e->getMessage());
		}
		
		
    }

	
	
	
	

    public function user()
    {
    	return $this->belongsTo(User::class);
    }
}
