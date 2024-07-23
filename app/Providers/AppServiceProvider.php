<?php

namespace App\Providers;


use App\Models\AdminNotification;
use App\Models\BalanceTransfer;
use App\Models\Deposit;
use App\Models\Frontend;
use App\Models\GeneralSetting;
use App\Models\Language;
use App\Models\Page;
use App\Models\SupportTicket;
use App\Models\User;
use App\Models\UserDps;
use App\Models\UserFdr;
use App\Models\UserLoan;
use App\Models\Withdrawal;
use Illuminate\Pagination\Paginator;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //

        $activeTemplate                 = activeTemplate();
        $general                        = GeneralSetting::first();
        $viewShare['general']           = $general;
        $viewShare['activeTemplate']    = $activeTemplate;
        $viewShare['activeTemplateTrue']= activeTemplate(true);
        $viewShare['language']          = Language::all();
        $viewShare['pages']             = Page::where('tempname',$activeTemplate)->where('slug','!=','home')->where('slug', '!=', 'contact')->get();
        view()->share($viewShare);

        view()->composer('admin.partials.sidenav', function ($view) {
            $view->with([
                'banned_users_count'           => User::banned()->count(),
                'email_unverified_users_count' => User::emailUnverified()->count(),
                'sms_unverified_users_count'   => User::smsUnverified()->count(),
                'pending_ticket_count'         => SupportTicket::whereIN('status', [0,2])->count(),
                'pending_deposits_count'       => Deposit::pending()->count(),
                'pending_withdraw_count'       => Withdrawal::pending()->count(),
                'awaiting_kyc_verification'    => User::where('kyc_data','!=', null)->where('kycv', 0)->count(),
                'due_fdr_count'                => UserFdr::due()->count(),
                'due_dps_count'                => UserDps::due()->count(),
                'due_loan_count'               => UserLoan::due()->count(),
                'pending_loan_count'           => UserLoan::pending()->count(),
                'pending_transfer_count'       => BalanceTransfer::where('status', 0)->whereHas('bank')->count()
            ]);
        });

        view()->composer('partials.seo', function ($view) {
            $seo = Frontend::where('data_keys', 'seo.data')->first();
            $view->with([
                'seo' => $seo ? $seo->data_values : $seo,
            ]);
        });

        view()->composer('admin.partials.topnav', function ($view) {
            $view->with([
                'adminNotifications'=>AdminNotification::where('read_status',0)->with('user')->orderBy('id','desc')->get(),
            ]);
        });


        Paginator::useBootstrap();

    }
}
