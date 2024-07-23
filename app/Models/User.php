<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

use DB  ;

class User extends Authenticatable
{
	
	public  function __construct(){
		//alter table  
					try{
						
						
	DB::statement("ALTER TABLE users
ADD upgrade_paid int  DEFAULT 0");
					
										
	DB::statement("ALTER TABLE users
ADD resseller int  DEFAULT 0");
					
					
					
					}
			catch(\Exception $e){

	//	echo ($e->getMessage());

			}
		
		
	}
	
	
    use Notifiable, HasApiTokens;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $guarded = ['id'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */

    protected $casts = [
        'email_verified_at' => 'datetime',
        'address'           => 'object',
        'kyc_data'          => 'object',
        'ver_code_send_at'  => 'datetime',
    ];

    protected $data = [
        'data'=>1
    ];


    public function referrer()
    {
        return $this->belongsTo(User::class, 'ref_by', 'id');
    }

    public function login_logs()
    {
        return $this->hasMany(UserLogin::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class)->orderBy('id','desc');
    }

    public function deposits()
    {
        return $this->hasMany(Deposit::class)->where('status','!=',0);
    }

    public function withdrawals()
    {
        return $this->hasMany(Withdrawal::class)->where('status','!=',0);
    }


    // SCOPES

    public function getFullnameAttribute()
    {
        return $this->firstname . ' ' . $this->lastname;
    }

    public function scopeActive()
    {
        return $this->where('status', 1);
    }
	
	
	
    public function scopeManager()
    {
        return $this->where('role', 'Manager');
    }
	
		
    public function scopeSuper()
    {
        return $this->where('role', 'Super Manager');
    }
	
	    public function scopeNormal()
    {
        return $this->where('role', 'Referral');
    }
	
		    public function scopeDeveloper()
    {
        return $this->where('role', 'Developer');
    }
	
	
	

    public function scopeBanned()
    {
        return $this->where('status', 0);
    }

    public function scopeEmailUnverified()
    {
        return $this->where('ev', 0);
    }

    public function scopeSmsUnverified()
    {
        return $this->where('sv', 0);
    }
    public function scopeEmailVerified()
    {
        return $this->where('ev', 1);
    }

    public function scopeSmsVerified()
    {
        return $this->where('sv', 1);
    }

}
