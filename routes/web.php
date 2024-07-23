<?php

use App\Http\Controllers\Admin\ManageAll;
use App\Http\Controllers\purchase_controller;
use Illuminate\Support\Facades\Route;

use  App\Models\data;
use  App\Models\airtime;
use  App\Models\network;
use Illuminate\Http\Request;


Route::get('/clear', function () {
    \Illuminate\Support\Facades\Artisan::call('optimize:clear');
});

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::any('dataorder',  function (Request $request) {
    //now send neccessary info okay
    $result  =  '<option></option>';
    //just serach by network code

    $datas  = data::where('network', $request->network)->get();


    $sme    =  '';
    foreach ($datas  as   $resultt) {
        //$result  .= '<option value="'.$resultt->plan_code.'">' .$resultt->plan   .  '</option>';

        //check authentification
        $user  =  auth()->user();

        if ($user->resseller  ==  1) {
            $price  =   $resultt->reseller_price;
        } else {

            $price  =   $resultt->default_price;
        }
        $output  =  '<option value="' . $resultt->dataplan_id . '">' . $resultt->plan_type .  "  $resultt->plan     $resultt->month_validate   = ₦" . $price . " </option>";

        if ($resultt->plan_type  != 'SME') {
            $result  .=        $output;
        } else {
            $sme .=  $output;
        }
    }

    if ($sme  !=  '') {

        $result =   $sme  . $result;
    }

    return ($result );
});



//getaccount
Route::get('testmoni',  'monify_controller@getaccount');


// Cron Route
Route::get('cron/dps', 'CronController@dps')->name('cron.dps');
Route::get('cron/fdr', 'CronController@fdr')->name('cron.fdr');
Route::get('cron/loan', 'CronController@loan')->name('cron.loan');


Route::namespace('Gateway')->prefix('ipn')->name('ipn.')->group(function () {
    Route::post('paypal', 'Paypal\ProcessController@ipn')->name('Paypal');
    Route::get('paypal-sdk', 'PaypalSdk\ProcessController@ipn')->name('PaypalSdk');
    Route::post('perfect-money', 'PerfectMoney\ProcessController@ipn')->name('PerfectMoney');
    Route::post('stripe', 'Stripe\ProcessController@ipn')->name('Stripe');
    Route::post('stripe-js', 'StripeJs\ProcessController@ipn')->name('StripeJs');
    Route::post('stripe-v3', 'StripeV3\ProcessController@ipn')->name('StripeV3');
    Route::post('skrill', 'Skrill\ProcessController@ipn')->name('Skrill');
    Route::post('paytm', 'Paytm\ProcessController@ipn')->name('Paytm');
    Route::post('payeer', 'Payeer\ProcessController@ipn')->name('Payeer');
    Route::post('paystack', 'Paystack\ProcessController@ipn')->name('Paystack');
    Route::post('voguepay', 'Voguepay\ProcessController@ipn')->name('Voguepay');
    Route::get('flutterwave/{trx}/{type}', 'Flutterwave\ProcessController@ipn')->name('Flutterwave');
    Route::post('razorpay', 'Razorpay\ProcessController@ipn')->name('Razorpay');
    Route::post('instamojo', 'Instamojo\ProcessController@ipn')->name('Instamojo');
    Route::get('blockchain', 'Blockchain\ProcessController@ipn')->name('Blockchain');
    Route::get('blockio', 'Blockio\ProcessController@ipn')->name('Blockio');
    Route::post('coinpayments', 'Coinpayments\ProcessController@ipn')->name('Coinpayments');
    Route::post('coinpayments-fiat', 'Coinpayments_fiat\ProcessController@ipn')->name('CoinpaymentsFiat');
    Route::post('coingate', 'Coingate\ProcessController@ipn')->name('Coingate');
    Route::post('coinbase-commerce', 'CoinbaseCommerce\ProcessController@ipn')->name('CoinbaseCommerce');
    Route::get('mollie', 'Mollie\ProcessController@ipn')->name('Mollie');
    Route::post('cashmaal', 'Cashmaal\ProcessController@ipn')->name('Cashmaal');
    Route::post('authorize-net', 'AuthorizeNet\ProcessController@ipn')->name('AuthorizeNet');
    Route::post('2check-out', 'TwoCheckOut\ProcessController@ipn')->name('TwoCheckOut');
    Route::post('mercado-pago', 'MercadoPago\ProcessController@ipn')->name('MercadoPago');
});

// User Support Ticket
Route::prefix('support')->group(function () {
    Route::get('/', 'TicketController@supportTicket')->name('ticket');
    Route::get('/new', 'TicketController@openSupportTicket')->name('ticket.open');
    Route::post('/create', 'TicketController@storeSupportTicket')->name('ticket.store');
    Route::get('/view/{ticket}', 'TicketController@viewTicket')->name('ticket.view');
    Route::post('/reply/{ticket}', 'TicketController@replyTicket')->name('ticket.reply');
    Route::get('/download/{ticket}', 'TicketController@ticketDownload')->name('ticket.download');
});


/*
|--------------------------------------------------------------------------
| Start Admin Area
|--------------------------------------------------------------------------
*/

Route::namespace('Admin')->prefix('iamadmin@6019')->name('admin.')->group(function () {


    Route::namespace('Auth')->group(function () {
        Route::get('/', 'LoginController@showLoginForm')->name('login');
        Route::post('/', 'LoginController@login')->name('login');
        Route::get('logout', 'LoginController@logout')->name('logout');
        // Admin Password Reset
        Route::get('password/reset', 'ForgotPasswordController@showLinkRequestForm')->name('password.reset');
        Route::post('password/reset', 'ForgotPasswordController@sendResetCodeEmail');
        Route::post('password/verify-code', 'ForgotPasswordController@verifyCode')->name('password.verify.code');
        Route::get('password/reset/{token}', 'ResetPasswordController@showResetForm')->name('password.reset.form');
        Route::post('password/reset/change', 'ResetPasswordController@reset')->name('password.change');
    });



    Route::middleware('admin')->group(function () {

        Route::get('default_price',     [ManageAll::class,  'defaultprice'])->name('default_price');
        //place all the links .
        Route::get('add_network',  function () {
            $pageTitle =  "Add  Network";
            return  view('admin.network.add')->with('pageTitle', $pageTitle);
        })->name('add.network');

        Route::post('add_network',  'ManageAll@addnetworks');
        Route::get('network_list',   'ManageAll@networks')->name('network.list');

        Route::get('edit_network/{id}',    [ManageAll::class,  'editnetwork'])->name('network.edit');

        Route::get('delete_network/{id}',   'ManageAll@deletenetworks')->name('network.delete');

        //Data Links
        global $title;
        global  $link_name;
        $link_name  =  'data';

        $title  =  "Data";
        Route::get('add_' . $link_name,  function () {
            $link_name  =  'data';

            $title  =  "Data";

            $pageTitle =  "Add $title";

            return  view("admin.data.add")->with('pageTitle', $pageTitle);
        })->name("add.$link_name");

        Route::post("add_$link_name",  "ManageAll@add$link_name");
        Route::get($link_name . "_list",   "ManageAll@$link_name")->name("$link_name" . "list");

        Route::get("edit_$link_name/{id}",   "ManageAll@edit$link_name")->name("$link_name.edit");

        Route::get("delete_$link_name/{id}",   "ManageAll@delete$link_name")->name("$link_name.delete");




        //Airtime Links

        $link_name  =  'airtime';
        $title  =  "Airtime";
        Route::get('add_airtime',  function () {
            $link_name  =  'airtime';
            $title  =  "Airtime";
            $pageTitle =  "Add Airtime";
            return  view("admin.airtime.add")->with('pageTitle', $pageTitle);
        })->name("add.airtime");

        Route::post("add_$link_name",  "ManageAll@add$link_name");
        Route::get($link_name . "_list",   "ManageAll@$link_name")->name("$link_name" . "list");

        Route::get("edit_$link_name/{id}",   "ManageAll@edit$link_name")->name("$link_name.edit");

        Route::get("delete_$link_name/{id}",   "ManageAll@delete$link_name")->name("$link_name.delete");



        //Cable Links

        $link_name  =  'cable';

        $title  =  "Cable";
        Route::get('add_' . $link_name,  function () {
            $link_name  =  'cable';

            $title  =  "Cable";

            $pageTitle =  "Add $title";

            return  view("admin.$link_name.add")->with('pageTitle', $pageTitle);
        })->name("add.$link_name");

        Route::post("add_$link_name",  "ManageAll@add$link_name");
        Route::get($link_name . "_list",   "ManageAll@$link_name")->name("$link_name" . "list");

        Route::get("edit_$link_name/{id}",   "ManageAll@edit$link_name")->name("$link_name.edit");

        Route::get("delete_$link_name/{id}",   "ManageAll@delete$link_name")->name("$link_name.delete");


        //Cable Plans Links

        $link_name  =  'cable_plan';

        $title  =  "Cable Plan";
        Route::get('add_' . $link_name,  function () {

            $link_name  =  'cable_plan';

            $title  =  "Cable Plan";

            $pageTitle =  "Add $title";

            return  view("admin.$link_name.add")->with('pageTitle', $pageTitle);
        })->name("add.$link_name");

        Route::post("add_$link_name",  "ManageAll@add$link_name");
        Route::get($link_name . "_list",   "ManageAll@$link_name")->name($link_name . "list");

        Route::get("edit_$link_name/{id}",   "ManageAll@edit$link_name")->name("$link_name.edit");

        Route::get("delete_$link_name/{id}",   "ManageAll@delete$link_name")->name("$link_name.delete");



        //Bills Links

        $link_name  =  'bill';

        $title  =  "Bills";
        Route::get('add_' . $link_name,  function () {

            $link_name  =  'bill';

            $title  =  "Bills";


            $pageTitle =  "Add $title";

            return  view("admin.$link_name.add")->with('pageTitle', $pageTitle);
        })->name("add.$link_name");

        Route::post("add_$link_name",  "ManageAll@add$link_name");
        Route::get($link_name . "_list",   "ManageAll@$link_name")->name("$link_name" . "list");

        Route::get("edit_$link_name/{id}",   "ManageAll@edit$link_name")->name("$link_name.edit");

        Route::get("delete_$link_name/{id}",   "ManageAll@delete$link_name")->name("$link_name.delete");






        //Role Links
        $link_name  =  'role';
        $title  =  "Roles";
        Route::get('add_' . $link_name,  function () {
            $link_name  =  'role';
            $title  =  "Roles";
            $pageTitle =  "Add $title";
            return  view("admin.$link_name.add")->with('pageTitle', $pageTitle);
        })->name("add.$link_name");
        Route::post("add_$link_name",  "ManageAll@add$link_name");
        Route::get($link_name . "_list",   "ManageAll@$link_name")->name("$link_name" . "list");
        Route::get("edit_$link_name/{id}",   "ManageAll@edit$link_name")->name("$link_name.edit");
        Route::get("delete_$link_name/{id}",   "ManageAll@delete$link_name")->name("$link_name.delete");











        Route::get('dashboard', 'AdminController@dashboard')->name('dashboard');
        Route::get('profile', 'AdminController@profile')->name('profile');
        Route::post('profile', 'AdminController@profileUpdate')->name('profile.update');
        Route::get('password', 'AdminController@password')->name('password');
        Route::post('password', 'AdminController@passwordUpdate')->name('password.update');

        //Notification
        Route::get('notifications', 'AdminController@notifications')->name('notifications');
        Route::get('notification/read/{id}', 'AdminController@notificationRead')->name('notification.read');
        Route::get('notifications/read-all', 'AdminController@readAll')->name('notifications.readAll');

        //Report Bugs
        Route::get('request-report', 'AdminController@requestReport')->name('request.report');
        Route::post('request-report', 'AdminController@reportSubmit');

        Route::get('system-info', 'AdminController@systemInfo')->name('system.info');


        // Users Manager
        Route::get('users', 'ManageUsersController@allUsers')->name('users.all');

        Route::get('users/active', 'ManageUsersController@activeUsers')->name('users.active');

        Route::get('users/manager', 'ManageUsersController@managers')->name('users.manager');

        Route::get('users/super_manager', 'ManageUsersController@supermanagers')->name('users.super_manager');

        Route::get('users/normaluser', 'ManageUsersController@normals')->name('users.normals');

        Route::get('users/developer', 'ManageUsersController@developers')->name('users.developers');


        Route::get('users/banned', 'ManageUsersController@bannedUsers')->name('users.banned');

        Route::get('users/email-verified', 'ManageUsersController@emailVerifiedUsers')->name('users.email.verified');

        Route::get('users/email-unverified', 'ManageUsersController@emailUnverifiedUsers')->name('users.email.unverified');
        Route::get('users/sms-unverified', 'ManageUsersController@smsUnverifiedUsers')->name('users.sms.unverified');
        Route::get('users/sms-verified', 'ManageUsersController@smsVerifiedUsers')->name('users.sms.verified');
        Route::get('users/with-balance', 'ManageUsersController@usersWithBalance')->name('users.with.balance');

        Route::get('users/awaiting-for-kyc-verification', 'ManageUsersController@kycSubmitted')->name('users.kyc.awaiting');

        Route::get('users/{scope}/search', 'ManageUsersController@search')->name('users.search');
        Route::get('user/detail/{id}', 'ManageUsersController@detail')->name('users.detail');
        Route::post('user/update/{id}', 'ManageUsersController@update')->name('users.update');
        Route::post('user/add-sub-balance/{id}', 'ManageUsersController@addSubBalance')->name('users.add.sub.balance');
        Route::get('user/send-email/{id}', 'ManageUsersController@showEmailSingleForm')->name('users.email.single');
        Route::post('user/send-email/{id}', 'ManageUsersController@sendEmailSingle')->name('users.email.single');
        Route::get('user/login/{id}', 'ManageUsersController@login')->name('users.login');

        Route::get('user/kyc-data/{id}', 'ManageUsersController@kycData')->name('users.detail.kyc');

        Route::get('user/kyc-data-approve/{id}', 'ManageUsersController@kycApprove')->name('users.detail.kyc.approve');
        Route::get('user/kyc-data-reject/{id}', 'ManageUsersController@kycReject')->name('users.detail.kyc.reject');


        Route::get('user/transactions/{id}', 'ManageUsersController@transactions')->name('users.transactions');
        Route::get('user/deposits/{id}', 'ManageUsersController@deposits')->name('users.deposits');
        Route::get('user/deposits/via/{method}/{type?}/{userId}', 'ManageUsersController@depositViaMethod')->name('users.deposits.method');
        Route::get('user/withdrawals/{id}', 'ManageUsersController@withdrawals')->name('users.withdrawals');
        Route::get('user/withdrawals/via/{method}/{type?}/{userId}', 'ManageUsersController@withdrawalsViaMethod')->name('users.withdrawals.method');

        Route::get('user/loans/{id}', 'ManageUsersController@loans')->name('users.loans');
        Route::get('user/fdr/{id}', 'ManageUsersController@fdr')->name('users.fdr');
        Route::get('user/dps/{id}', 'ManageUsersController@dps')->name('users.dps');

        Route::get('user/beneficiaries/{id}', 'ManageUsersController@beneficiaries')->name('users.beneficiaries');

        Route::get('user/transfers/{id}', 'ManageUsersController@transfers')->name('users.transfers');

        // Login History
        Route::get('users/login/history/{id}', 'ManageUsersController@userLoginHistory')->name('users.login.history.single');

        Route::get('users/send-email', 'ManageUsersController@showEmailAllForm')->name('users.email.all');
        Route::post('users/send-email', 'ManageUsersController@sendEmailAll')->name('users.email.send');
        Route::get('users/email-log/{id}', 'ManageUsersController@emailLog')->name('users.email.log');
        Route::get('users/email-details/{id}', 'ManageUsersController@emailDetails')->name('users.email.details');

        // Referral
        Route::get('/referral', 'ManageReferralController@index')->name('referral.index');
        Route::post('/referral', 'ManageReferralController@store')->name('store.refer');
        Route::get('/referral-status/{type}', 'ManageReferralController@referralStatusUpdate')->name('referral.status');

        // Subscriber
        Route::get('subscriber', 'SubscriberController@index')->name('subscriber.index');
        Route::get('subscriber/send-email', 'SubscriberController@sendEmailForm')->name('subscriber.sendEmail');
        Route::post('subscriber/remove', 'SubscriberController@remove')->name('subscriber.remove');
        Route::post('subscriber/send-email', 'SubscriberController@sendEmail')->name('subscriber.sendEmail');



        // Loan Management
        Route::name('loan.')->prefix('loan')->group(function () {
            Route::get('plans', 'ManageLoanController@plans')->name('plan.index');
            Route::get('plan/create', 'ManageLoanController@createPlan')->name('plan.create');
            Route::get('plan/edit/{id}', 'ManageLoanController@editPlan')->name('plan.edit');
            Route::post('plan/save', 'ManageLoanController@savePlan')->name('plan.save');
            Route::post('update/{id}', 'ManageLoanController@updatePlan')->name('plan.update');
            Route::post('plan/status', 'ManageLoanController@changeStatus')->name('plan.status');

            Route::get('details/{id}', 'ManageLoanController@details')->name('details');
            Route::get('all', 'ManageLoanController@allLoans')->name('index');
            Route::get('running', 'ManageLoanController@runningLoans')->name('running');
            Route::get('pending', 'ManageLoanController@pendingLoans')->name('pending');
            Route::get('rejected', 'ManageLoanController@rejectedLoans')->name('rejected');
            Route::get('paid', 'ManageLoanController@paidLoans')->name('paid');
            Route::get('due', 'ManageLoanController@dueInstallment')->name('due');

            Route::post('approve', 'ManageLoanController@approve')->name('approve');
            Route::post('reject', 'ManageLoanController@reject')->name('reject');
        });


        // DPS Management
        Route::name('dps.')->prefix('dps')->group(function () {
            Route::get('plans', 'ManageDpsController@plans')->name('plan.index');
            Route::post('plan/save', 'ManageDpsController@savePlan')->name('plan.save');
            Route::post('update/{id}', 'ManageDpsController@updatePlan')->name('plan.update');
            Route::post('plan/status', 'ManageDpsController@changeStatus')->name('plan.status');

            Route::get('all', 'ManageDpsController@index')->name('index');
            Route::get('running', 'ManageDpsController@runningDps')->name('running');
            Route::get('matured', 'ManageDpsController@maturedDps')->name('matured');
            Route::get('due', 'ManageDpsController@dueInstallment')->name('due');
            Route::get('installments/{id}', 'ManageDpsController@installments')->name('isntallments');
        });


        // FDR Management
        Route::name('fdr.')->prefix('fdr')->group(function () {
            Route::get('plans', 'ManageFdrController@plans')->name('plan.index');
            Route::post('plan/save', 'ManageFdrController@savePlan')->name('plan.save');
            Route::post('update/{id}', 'ManageFdrController@updatePlan')->name('plan.update');
            Route::post('plan/status', 'ManageFdrController@changeStatus')->name('plan.status');

            Route::get('all', 'ManageFdrController@index')->name('index');
            Route::get('running', 'ManageFdrController@runningFdr')->name('running');
            Route::get('closed', 'ManageFdrController@closedFdr')->name('closed');
            Route::get('due', 'ManageFdrController@dueInstallment')->name('due');
            Route::get('installments/{id}', 'ManageFdrController@installments')->name('isntallments');
        });


        // Deposit Gateway
        Route::name('gateway.')->prefix('gateway')->group(function () {
            // Automatic Gateway
            Route::get('automatic', 'GatewayController@index')->name('automatic.index');
            Route::get('automatic/edit/{alias}', 'GatewayController@edit')->name('automatic.edit');
            Route::post('automatic/update/{code}', 'GatewayController@update')->name('automatic.update');
            Route::post('automatic/remove/{code}', 'GatewayController@remove')->name('automatic.remove');
            Route::post('automatic/activate', 'GatewayController@activate')->name('automatic.activate');
            Route::post('automatic/deactivate', 'GatewayController@deactivate')->name('automatic.deactivate');


            // Manual Methods
            Route::get('manual', 'ManualGatewayController@index')->name('manual.index');
            Route::get('manual/new', 'ManualGatewayController@create')->name('manual.create');
            Route::post('manual/new', 'ManualGatewayController@store')->name('manual.store');
            Route::get('manual/edit/{alias}', 'ManualGatewayController@edit')->name('manual.edit');
            Route::post('manual/update/{id}', 'ManualGatewayController@update')->name('manual.update');
            Route::post('manual/activate', 'ManualGatewayController@activate')->name('manual.activate');
            Route::post('manual/deactivate', 'ManualGatewayController@deactivate')->name('manual.deactivate');
        });


        // DEPOSIT SYSTEM
        Route::name('deposit.')->prefix('deposit')->group(function () {
            Route::get('/', 'DepositController@deposit')->name('list');
            Route::get('pending', 'DepositController@pending')->name('pending');
            Route::get('rejected', 'DepositController@rejected')->name('rejected');
            Route::get('approved', 'DepositController@approved')->name('approved');
            Route::get('successful', 'DepositController@successful')->name('successful');
            Route::get('details/{id}', 'DepositController@details')->name('details');

            Route::post('reject', 'DepositController@reject')->name('reject');
            Route::post('approve', 'DepositController@approve')->name('approve');
            Route::get('via/{method}/{type?}', 'DepositController@depositViaMethod')->name('method');
            Route::get('/{scope}/search', 'DepositController@search')->name('search');
            Route::get('date-search/{scope}', 'DepositController@dateSearch')->name('dateSearch');
        });


        // WITHDRAW SYSTEM
        Route::name('withdraw.')->prefix('withdraw')->group(function () {
            Route::get('pending', 'WithdrawalController@pending')->name('pending');
            Route::get('approved', 'WithdrawalController@approved')->name('approved');
            Route::get('rejected', 'WithdrawalController@rejected')->name('rejected');
            Route::get('log', 'WithdrawalController@log')->name('log');
            Route::get('via/{method_id}/{type?}', 'WithdrawalController@logViaMethod')->name('method');
            Route::get('{scope}/search', 'WithdrawalController@search')->name('search');
            Route::get('date-search/{scope}', 'WithdrawalController@dateSearch')->name('dateSearch');
            Route::get('details/{id}', 'WithdrawalController@details')->name('details');
            Route::post('approve', 'WithdrawalController@approve')->name('approve');
            Route::post('reject', 'WithdrawalController@reject')->name('reject');


            // Withdraw Method
            Route::get('method/', 'WithdrawMethodController@methods')->name('method.index');
            Route::get('method/create', 'WithdrawMethodController@create')->name('method.create');
            Route::post('method/create', 'WithdrawMethodController@store')->name('method.store');
            Route::get('method/edit/{id}', 'WithdrawMethodController@edit')->name('method.edit');
            Route::post('method/edit/{id}', 'WithdrawMethodController@update')->name('method.update');
            Route::post('method/activate', 'WithdrawMethodController@activate')->name('method.activate');
            Route::post('method/deactivate', 'WithdrawMethodController@deactivate')->name('method.deactivate');
        });

        // Others Bank
        Route::name('bank.')->prefix('other-banks')->group(function () {
            Route::get('create', 'ManageBanksController@createOtherBank')->name('create');
            Route::post('create', 'ManageBanksController@storeOtherBank')->name('store');
            Route::get('edit/{id}', 'ManageBanksController@editOtherBank')->name('edit');
            Route::post('edit/{id}', 'ManageBanksController@updateOtherBank')->name('update');

            Route::get('all', 'ManageBanksController@index')->name('index');
            Route::post('change-status', 'ManageBanksController@changeStatus')->name('status');
        });

        // Transfers
        Route::name('transfers.')->prefix('transfers')->group(function () {
            Route::get('pending', 'ManageTransfersController@pending')->name('pending');
            Route::get('rejected', 'ManageTransfersController@rejected')->name('rejected');
            Route::get('own', 'ManageTransfersController@own')->name('own');
            Route::get('others', 'ManageTransfersController@others')->name('other');
            Route::get('details/{id}', 'ManageTransfersController@details')->name('details');
            Route::post('reject/{id}', 'ManageTransfersController@reject')->name('reject');
            Route::post('accept/{id}', 'ManageTransfersController@accept')->name('accept');
        });


        // Report
        Route::get('report/transaction', 'ReportController@transaction')->name('report.transaction');
        Route::get('report/transaction/search', 'ReportController@transactionSearch')->name('report.transaction.search');

        Route::get('report/commission-log', 'ReportController@commissions')->name('report.commission');
        Route::get('report/commission-log/search', 'ReportController@commissionSearch')->name('report.commission.search');

        Route::get('report/login/history', 'ReportController@loginHistory')->name('report.login.history');
        Route::get('report/login/ipHistory/{ip}', 'ReportController@loginIpHistory')->name('report.login.ipHistory');
        Route::get('report/email/history', 'ReportController@emailHistory')->name('report.email.history');


        // Admin Support
        Route::get('tickets', 'SupportTicketController@tickets')->name('ticket');
        Route::get('tickets/pending', 'SupportTicketController@pendingTicket')->name('ticket.pending');
        Route::get('tickets/closed', 'SupportTicketController@closedTicket')->name('ticket.closed');
        Route::get('tickets/answered', 'SupportTicketController@answeredTicket')->name('ticket.answered');
        Route::get('tickets/view/{id}', 'SupportTicketController@ticketReply')->name('ticket.view');
        Route::post('ticket/reply/{id}', 'SupportTicketController@ticketReplySend')->name('ticket.reply');
        Route::get('ticket/download/{ticket}', 'SupportTicketController@ticketDownload')->name('ticket.download');
        Route::post('ticket/delete', 'SupportTicketController@ticketDelete')->name('ticket.delete');


        // Language Manager
        Route::get('/language', 'LanguageController@langManage')->name('language.manage');
        Route::post('/language', 'LanguageController@langStore')->name('language.manage.store');
        Route::post('/language/delete/{id}', 'LanguageController@langDel')->name('language.manage.del');
        Route::post('/language/update/{id}', 'LanguageController@langUpdate')->name('language.manage.update');
        Route::get('/language/edit/{id}', 'LanguageController@langEdit')->name('language.key');
        Route::post('/language/import', 'LanguageController@langImport')->name('language.importLang');


        Route::post('language/store/key/{id}', 'LanguageController@storeLanguageJson')->name('language.store.key');
        Route::post('language/delete/key/{id}', 'LanguageController@deleteLanguageJson')->name('language.delete.key');
        Route::post('language/update/key/{id}', 'LanguageController@updateLanguageJson')->name('language.update.key');



        // General Setting
        Route::get('setting/general', 'GeneralSettingController@general')->name('setting.general');
        Route::get('setting/system', 'GeneralSettingController@system')->name('setting.system');
        Route::post('setting/general', 'GeneralSettingController@updateGeneral');
        Route::post('setting/system', 'GeneralSettingController@updateSystem');
        Route::get('setting/kyc-form', 'GeneralSettingController@kycForm')->name('setting.kyc');
        Route::post('setting/kyc-form', 'GeneralSettingController@saveKycForm');


        Route::get('optimize', 'GeneralSettingController@optimize')->name('setting.optimize');

        // Logo-Icon
        Route::get('setting/logo-icon', 'GeneralSettingController@logoIcon')->name('setting.logo.icon');
        Route::post('setting/logo-icon', 'GeneralSettingController@logoIconUpdate')->name('setting.logo.icon');

        //Custom CSS
        Route::get('custom-css', 'GeneralSettingController@customCss')->name('setting.custom.css');
        Route::post('custom-css', 'GeneralSettingController@customCssSubmit');


        //Cookie
        Route::get('cookie', 'GeneralSettingController@cookie')->name('setting.cookie');
        Route::post('cookie', 'GeneralSettingController@cookieSubmit');


        // Plugin
        Route::get('extensions', 'ExtensionController@index')->name('extensions.index');
        Route::post('extensions/update/{id}', 'ExtensionController@update')->name('extensions.update');
        Route::post('extensions/activate', 'ExtensionController@activate')->name('extensions.activate');
        Route::post('extensions/deactivate', 'ExtensionController@deactivate')->name('extensions.deactivate');



        // Email Setting
        Route::get('email-template/global', 'EmailTemplateController@emailTemplate')->name('email.template.global');
        Route::post('savetemplateher', 'EmailTemplateController@emailTemplateUpdate')->name('email.template.globalpost');
        Route::get('email-template/setting', 'EmailTemplateController@emailSetting')->name('email.template.setting');
        Route::post('email-template/setting', 'EmailTemplateController@emailSettingUpdate')->name('email.template.setting');
        Route::get('email-template/index', 'EmailTemplateController@index')->name('email.template.index');
        Route::get('email-template/{id}/edit', 'EmailTemplateController@edit')->name('email.template.edit');
        Route::post('email-template/{id}/update', 'EmailTemplateController@update')->name('email.template.update');
        Route::post('email-template/send-test-mail', 'EmailTemplateController@sendTestMail')->name('email.template.test.mail');


        // SMS Setting
        Route::get('sms-template/global', 'SmsTemplateController@smsTemplate')->name('sms.template.global');
        Route::post('sms-template/global', 'SmsTemplateController@smsTemplateUpdate')->name('sms.template.global');
        Route::get('sms-template/setting', 'SmsTemplateController@smsSetting')->name('sms.templates.setting');
        Route::post('sms-template/setting', 'SmsTemplateController@smsSettingUpdate')->name('sms.template.setting');
        Route::get('sms-template/index', 'SmsTemplateController@index')->name('sms.template.index');
        Route::get('sms-template/edit/{id}', 'SmsTemplateController@edit')->name('sms.template.edit');
        Route::post('sms-template/update/{id}', 'SmsTemplateController@update')->name('sms.template.update');
        Route::post('email-template/send-test-sms', 'SmsTemplateController@sendTestSMS')->name('sms.template.test.sms');

        // SEO
        Route::get('seo', 'FrontendController@seoEdit')->name('seo');


        // Frontend
        Route::name('frontend.')->prefix('frontend')->group(function () {


            Route::get('templates', 'FrontendController@templates')->name('templates');
            Route::post('templates', 'FrontendController@templatesActive')->name('templates.active');


            Route::get('frontend-sections/{key}', 'FrontendController@frontendSections')->name('sections');
            Route::post('frontend-content/{key}', 'FrontendController@frontendContent')->name('sections.content');
            Route::get('frontend-element/{key}/{id?}', 'FrontendController@frontendElement')->name('sections.element');
            Route::post('remove', 'FrontendController@remove')->name('remove');

            // Page Builder
            Route::get('manage-pages', 'PageBuilderController@managePages')->name('manage.pages');
            Route::post('manage-pages', 'PageBuilderController@managePagesSave')->name('manage.pages.save');
            Route::post('manage-pages/update', 'PageBuilderController@managePagesUpdate')->name('manage.pages.update');
            Route::post('manage-pages/delete', 'PageBuilderController@managePagesDelete')->name('manage.pages.delete');
            Route::get('manage-section/{id}', 'PageBuilderController@manageSection')->name('manage.section');
            Route::post('manage-section/{id}', 'PageBuilderController@manageSectionUpdate')->name('manage.section.update');
        });
    });
});




/*
|--------------------------------------------------------------------------
| Start User Area
|--------------------------------------------------------------------------
*/


Route::name('user.')->group(function () {
    Route::get('/login', 'Auth\LoginController@showLoginForm')->name('login');
    Route::post('/login', 'Auth\LoginController@login');
    Route::get('logout', 'Auth\LoginController@logout')->name('logout');

    Route::get('register', 'Auth\RegisterController@showRegistrationForm')->name('register');

    Route::post('register', 'Auth\RegisterController@register')->middleware('regStatus');


    Route::post('check-mail', 'Auth\RegisterController@checkUser')->name('checkUser');

    Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
    Route::post('password/email', 'Auth\ForgotPasswordController@sendResetCodeEmail')->name('password.email');
    Route::get('password/code-verify', 'Auth\ForgotPasswordController@codeVerify')->name('password.code.verify');
    Route::post('password/reset', 'Auth\ResetPasswordController@reset')->name('password.update');
    Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
    Route::post('password/verify-code', 'Auth\ForgotPasswordController@verifyCode')->name('password.verify.code');
});

Route::name('user.')->prefix('user')->group(function () {
    Route::middleware('auth')->group(function () {


        //upgrade user
        Route::get('upgrade/{type}', function ($type) {

            $to =      strtoupper($type);
            $pageTitle = "Upgrade To $to";

            return view('templates.basic.user.upgrade', compact('pageTitle', 'type'));
        })->name('user_upgrade');


        //place all the details  here okay .

        Route::get('pay_upgrade/{type}',  'purchase_controller@payupgrade');


        //place the registration for user  here.
        Route::get('register', function () {


            $pageTitle = "Sign Up";
           $info = json_decode(json_encode(getIpInfo()), true);
            $mobile_code = "+234";
            $countries = json_decode(file_get_contents(resource_path('views/partials/country.json')));
            //at this junction  create the user referee section

            $user  =  auth()->user();

            session()->put('reference', $user->username);
            session()->put('signupuser', $user->username);

            return view('templates.basic.user.auth.register', compact('pageTitle', 'mobile_code', 'countries'));
        })->name('user_register');

        Route::post('userregister', 'Auth\UserResgisterController@userregister')->name('user_register_post');


        Route::post('pay_airtime',  'purchase_controller@airtime');
        Route::post('data',    [purchase_controller::class  ,  'data'] );

        Route::get('authorization', 'AuthorizationController@authorizeForm')->name('authorization');
        Route::get('resend-verify', 'AuthorizationController@sendVerifyCode')->name('send.verify.code');
        Route::post('verify-email', 'AuthorizationController@emailVerification')->name('verify.email');
        Route::post('verify-sms', 'AuthorizationController@smsVerification')->name('verify.sms');
        Route::post('verify-g2fa', 'AuthorizationController@g2faVerification')->name('go2fa.verify');

        Route::middleware(['checkStatus'])->group(function () {

            Route::get('kyc-verification', 'UserController@kycVerification')->name('kyc.verify');
            Route::post('kyc-verification', 'UserController@kycFormSubmit');
            Route::get('kyc-data', 'UserController@kycData')->name('kyc.view');

            Route::get('dashboard', 'UserController@home')->name('home');

            Route::get('profile-setting', 'UserController@profile')->name('profile.setting');


            Route::post('profile-setting', 'UserController@submitProfile');

            Route::get('change-password', 'UserController@changePassword')->name('change.password');
            Route::post('change-password', 'UserController@submitPassword');

            //2FA
            Route::get('twofactor', 'UserController@show2faForm')->name('twofactor');
            Route::post('twofactor/enable', 'UserController@create2fa')->name('twofactor.enable');
            Route::post('twofactor/disable', 'UserController@disable2fa')->name('twofactor.disable');


            // Actions
            Route::post('action', 'UserActionController@userAction')->name('action');
            Route::get('verify/otp', 'UserActionController@verifyOtp')->name('otp.verify');
            Route::post('check/otp', 'UserActionController@checkOtp')->name('otp.check');
            Route::post('resend/otp', 'UserActionController@resendOtp')->name('otp.resend');
            Route::get('action/cancel', 'UserActionController@cancelAction')->name('action.cancel');

            //deal with the data airtime  e.t.c

            Route::get(
                'data',
                function () {
                    $pageTitle = "Data";
                    return  view(activeTemplate() . "user.data")->with('pageTitle', $pageTitle);
                }
            )->name('data');
            Route::get('airtime',  function () {
                $pageTitle = "Airtime";
                return  view(activeTemplate() . "user.airtime")->with('pageTitle', $pageTitle);
            })->name('airtime');


            Route::get('bills',  function () {
                $pageTitle = "Bills";
                return  view(activeTemplate() . "user.bill")->with('pageTitle', $pageTitle);
            })->name('bills');


            Route::get('cables',  function () {
                $pageTitle = "Cables";
                return  view(activeTemplate() . "user.cables")->with('pageTitle', $pageTitle);
            })->name('cables');

            //Route::get('cables'  ,  function(){});


            // Deposit
            Route::middleware('checkModule:deposit')->group(function () {
                Route::post('deposit/insert', 'Gateway\PaymentController@depositInsert')->name('deposit.insert');
                Route::get('deposit/preview', 'Gateway\PaymentController@depositPreview')->name('deposit.preview');
                Route::get('deposit/confirm', 'Gateway\PaymentController@depositConfirm')->name('deposit.confirm');
                Route::get('deposit/manual', 'Gateway\PaymentController@manualDepositConfirm')->name('deposit.manual.confirm');
                Route::post('deposit/manual', 'Gateway\PaymentController@manualDepositUpdate')->name('deposit.manual.update');
                Route::any('deposits', 'UserController@depositHistory')->name('deposit.history');
            });


            // LOAN
            Route::middleware(['checkModule:loan', 'checkKyc'])->group(function () {
                Route::get('loan/plans', 'UserActionController@loanPlans')->name('loan.plans');
                Route::post('loan/apply', 'UserActionController@applyLoan')->name('loan.apply');
                Route::get('loan/apply/form', 'UserActionController@loanForm')->name('loan.apply.form');
                Route::post('loan/apply/form', 'UserActionController@loanFormSubmit');
                Route::get('loan/list', 'UserActionController@loanList')->name('loan.list');
            });

            Route::get('account-number/check/{account}', 'UserController@checkAccountNumber')->name('accountnumber.check');

            // Beneficiary Management
            Route::get('transfer/beneficiary', 'UserActionController@manageBeneficiary')->name('transfer.beneficiary.manage');

            // Transfers
            Route::middleware(['checkModule:own_bank', 'checkKyc'])->group(function () {
                Route::get('transfer/own', 'UserActionController@transferOwn')->name('transfer.own');
                Route::get('transfer/own/send', 'UserActionController@transferOwnSend')->name('transfer.own.send');
                Route::post('transfer/beneficiary/own/add', 'UserActionController@addOwnBeneficiary')->name('transfer.beneficiary.own.add');
                Route::post('transfer/beneficiary/other/add', 'UserActionController@addOtherBeneficiary')->name('transfer.beneficiary.other.add');
            });

            Route::middleware(['checkModule:other_bank', 'checkKyc'])->group(function () {
                Route::get('transfer/other', 'UserActionController@transferOther')->name('transfer.other');
                Route::get('transfer/other/send', 'UserActionController@transferOtherSend')->name('transfer.other.send');
            });

            Route::middleware('checkModule:referral_system')->group(function () {


                Route::get('referees', 'UserController@referredUsers')->name('referral.users');


                //just add the downlines here

                Route::get('my_downlines', 'UserController@my_downlines')->name('my_downlines.users');

                Route::get('downline_transactions/{id}', 'UserController@downline_transactions')->name('downline_transactions.users');


                Route::post('downline_deposit', 'UserController@downline_deposit')->name('downline_deposit.users');


                Route::post('bonus_transfer', 'purchase_controller@bonus_transfer')->name('transfer.bonus');


                Route::get('referral/commissions', 'UserController@commissionLogs')->name('referral.commissions.logs');
            });

            Route::get('transfer/log', 'UserActionController@transferHistory')->name('transfer.history');

            // Transaction
            Route::get('transactions', 'UserController@transactions')->name('transaction.history');
        });
    });
});

Route::post('/subscribe', 'SiteController@addSubscriber')->name('subscribe');
Route::get('/contact', 'SiteController@contact')->name('contact');
Route::post('/contact', 'SiteController@contactSubmit');

Route::get('/change/{lang?}', 'SiteController@changeLanguage')->name('lang');

Route::get('/cookie/accept', 'SiteController@cookieAccept')->name('cookie.accept');

Route::get('blog/{id}/{slug}', 'SiteController@blogDetails')->name('blog.details');

Route::get('placeholder-image/{size}', 'SiteController@placeholderImage')->name('placeholder.image');
Route::get('page/{id}-{slug}', 'SiteController@page')->name('page');

Route::get('/{slug}', 'SiteController@pages')->name('pages');

Route::get('/', 'SiteController@index')->name('home');
