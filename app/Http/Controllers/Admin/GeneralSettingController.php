<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Frontend;
use App\Models\GeneralSetting;
use App\Rules\FileTypeValidate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Image;
use DB ;

class GeneralSettingController extends Controller
{
    public function general()
    {
        $general = GeneralSetting::first();
        $pageTitle = 'General Setting';
        $timezones = json_decode(file_get_contents(resource_path('views/admin/partials/timezone.json')));
        return view('admin.setting.general', compact('pageTitle', 'general','timezones'));
    }

    public function system()
    {
        $general = GeneralSetting::first();
        $modules = $general->modules;
        $pageTitle = 'System Setting';

        return view('admin.setting.system', compact('pageTitle', 'general', 'modules'));
    }

    public function updateGeneral(Request $request)
    {
        $request->validate([
            'base_color'         => 'nullable', 'regex:/^[a-f0-9]{6}$/i',
            'secondary_color'    => 'nullable', 'regex:/^[a-f0-9]{6}$/i',
            'timezone'           => 'nullable',
            'sitename'           => 'required|string|max:40',
            'cur_text'           => 'nullable|string|max:40',
            'cur_sym'            => 'nullable|string|max:40',
            'account_no_prefix'  => 'nullable|string|max:40',
            'account_no_length'  => 'nullable|integer|min:12|max:100',
        ]);


		//first alter the table and  insert .

				try{

					//monify


					DB::statement("ALTER TABLE vtu_general_settings
ADD monify_api_key varchar(600)  DEFAULT NULL");

					DB::statement("ALTER TABLE vtu_general_settings
ADD monify_contract_code varchar(600)  DEFAULT NULL");


					DB::statement("ALTER TABLE vtu_general_settings
ADD monify_user_key varchar(600)  DEFAULT NULL");



					DB::statement("ALTER TABLE vtu_general_settings
ADD noti_email varchar(600)  DEFAULT 'info@princeboye.site'");

							DB::statement("ALTER TABLE vtu_general_settings
ADD vtuuserid varchar(600)  DEFAULT NULL");


	DB::statement("ALTER TABLE vtu_general_settings
ADD vtukey varchar(600)  DEFAULT NULL");




				}
			catch(\Exception $e){

			//return($e->getMessage());

			}


        $general                            = GeneralSetting::first();

        $general->sitename                  = $request->sitename;

		$general->monify_api_key = $request->monify_api_key ;
		$general->monify_contract_code = $request->monify_contract_code ;
		$general->monify_user_key = $request->monify_user_key ;


		$general->vtukey = $request->vtukey ;
		$general->vtuuserid = $request->vtuuserid ;
		$general->noti_email = $request->noti_email ;

        $general->cur_text                  = $request->cur_text;
        $general->cur_sym                   = $request->cur_sym;
        $general->base_color                = $request->base_color;
        $general->secondary_color           = $request->secondary_color;
        $general->otp_time                  = $request->otp_time;
        $general->fixed_transfer_charge     = $request->fixed_transfer_charge;
        $general->percent_transfer_charge   = $request->percent_transfer_charge;
        $general->account_no_prefix         = $request->account_no_prefix;
        $general->account_no_length         = $request->account_no_length;
        $general->daily_transfer_limit      = $request->daily_transfer_limit;
        $general->monthly_transfer_limit    = $request->monthly_transfer_limit;
        $general->minimum_transfer_limit    = $request->minimum_transfer_limit;
        $general->save();

        $timezoneFile = config_path('timezone.php');
        $content = '<?php $timezone = '.$request->timezone.' ?>';
        file_put_contents($timezoneFile, $content);
        $notify[] = ['success', 'General setting has been updated.'];
        return back()->withNotify($notify);
    }

    public function updateSystem(Request $request)
    {
        $request->validate([
            'module'        => 'nullable|array',
            'module.*'      => 'in:on',
        ]);

        $modules['deposit']         = isset($request->module['deposit'])?1:0;
        $modules['withdraw']        = isset($request->module['withdraw'])?1:0;
        $modules['dps']             = isset($request->module['dps'])?1:0;
        $modules['fdr']             = isset($request->module['fdr'])?1:0;
        $modules['loan']            = isset($request->module['loan'])?1:0;
        $modules['own_bank']        = isset($request->module['own_bank'])?1:0;
        $modules['other_bank']      = isset($request->module['other_bank'])?1:0;

        $modules['otp_email']       = isset($request->module['otp_email'])?1:0;
        $modules['otp_sms']         = isset($request->module['otp_sms'])?1:0;
        $modules['referral_system'] = isset($request->module['referral_system'])?1:0;
        $modules['kyc']             = isset($request->module['kyc'])?1:0;

        $general                    = GeneralSetting::first();
        $general->ev                = $request->ev ? 1 : 0;
        $general->en                = $request->en ? 1 : 0;
        $general->sv                = $request->sv ? 1 : 0;
        $general->sn                = $request->sn ? 1 : 0;
        $general->force_ssl         = $request->force_ssl ? 1 : 0;
        $general->secure_password   = $request->secure_password ? 1 : 0;
        $general->registration      = $request->registration ? 1 : 0;
        $general->agree             = $request->agree ? 1 : 0;
        $general->modules           = $modules;
        $general->save();

        $notify[] = ['success', 'Module setting has been updated.'];
        return back()->withNotify($notify);
    }


    public function logoIcon()
    {
        $pageTitle = 'Logo & Favicon';
        return view('admin.setting.logo_icon', compact('pageTitle'));
    }

    public function logoIconUpdate(Request $request)
    {

    /*    $request->validate([
            'logo' => ['image',new FileTypeValidate(['jpg','jpeg','png'])],
            'favicon' => ['image',new FileTypeValidate(['png'])],
        ]);  */


        if ($request->hasFile('logo')) {
            try {
                $path = imagePath()['logoIcon']['path'];
                if (!file_exists($path)) {
                    mkdir($path, 0755, true);
                }
                Image::make($request->logo)->save($path . '/logo.png');
            } catch (\Exception $exp) {
                $notify[] = ['error', 'Logo could not be uploaded.'];
                return back()->withNotify($notify);
            }
        }

        if ($request->hasFile('favicon')) {
            try {
                $path = imagePath()['logoIcon']['path'];
                if (!file_exists($path)) {
                    mkdir($path, 0755, true);
                }
                $size = explode('x', imagePath()['favicon']['size']);
                Image::make($request->favicon)->resize($size[0], $size[1])->save($path . '/favicon.png');
            } catch (\Exception $exp) {
                $notify[] = ['error', 'Favicon could not be uploaded.'];
                return back()->withNotify($notify);
            }
        }
        $notify[] = ['success', 'Logo & favicon has been updated.'];
        return back()->withNotify($notify);
    }

    public function customCss(){
        $pageTitle = 'Custom CSS';
        $file = activeTemplate(true).'css/custom.css';
        $file_content = @file_get_contents($file);
        return view('admin.setting.custom_css',compact('pageTitle','file_content'));
    }


    public function customCssSubmit(Request $request){
        $file = activeTemplate(true).'css/custom.css';
        if (!file_exists($file)) {
            fopen($file, "w");
        }
        file_put_contents($file,$request->css);
        $notify[] = ['success','CSS updated successfully'];
        return back()->withNotify($notify);
    }

    public function optimize(){
        Artisan::call('optimize:clear');
        $notify[] = ['success','Cache cleared successfully'];
        return back()->withNotify($notify);
    }


    public function cookie(){
        $pageTitle = 'GDPR Cookie';
        $cookie = Frontend::where('data_keys','cookie.data')->firstOrFail();

        return view('admin.setting.cookie',compact('pageTitle','cookie'));
    }

    public function cookieSubmit(Request $request){

        $request->validate([
            'link'=>'required',
            'description'=>'required',
        ]);

        $cookie = Frontend::where('data_keys','cookie.data')->firstOrFail();

        $cookie->data_values = [
            'link' => $request->link,
            'description' => $request->description,
            'status' => $request->status ? 1 : 0,
        ];

        $cookie->save();

        $notify[] = ['success','Cookie policy updated successfully'];
        return back()->withNotify($notify);
    }


    // Kyc Setting
    public function kycForm(){
        $pageTitle = 'KYC (Know Your User) Form';
        $general = GeneralSetting::first();

        $fieldsCount    = 0;
        $fields         = null;

        if($general->kyc_form){
            $fields         = $general->kyc_form;
            $fieldsCount    = count($fields);
        }


        return view('admin.setting.kyc',compact('pageTitle', 'fieldsCount', 'fields'));
    }

    public function saveKycForm(Request $request)
    {

        $request->validate([
            'field_name.*'              => 'sometimes|required',
            'type.*'                    => 'sometimes|required|in:text,textarea,file',
            'validation.*'              => 'sometimes|required|in:required,nullable',
        ]);

        $general            = GeneralSetting::first();
        $general->kyc_form  = $request->input_form ? array_values($request->input_form) : null;
        $general->save();

        $notify[]=['success','Kyc form saved successfully'];
        return back()->withNotify($notify);
    }
}
