<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GeneralSetting extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'mail_config'   => 'object',
        'sms_config'    => 'object',
        'modules'       => 'object',
        'kyc_form'      => 'object'
    ];

	
	    protected $table = "vtu_general_settings";
	
	
	
    public function scopeSitename($query, $pageTitle)
    {
        $pageTitle = empty($pageTitle) ? '' : ' - ' . $pageTitle;
        return $this->sitename . $pageTitle;
    }
}
