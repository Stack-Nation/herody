<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('privacy-policy',function(){
    return view('privacy-policy');
})->name('privacy-policy');
Route::get('user/email-verified/{id}','User\DashboardController@email_verified');
// Admin
Route::get('admin','Admin\HomeController@index')->name('admin');
Route::post('admin','Admin\HomeController@login')->name('admin');

Route::middleware(['Admin.Auth'])->prefix('admin')->namespace('Admin')->name('admin.')->group(function () {
    // Managers
    Route::get('managers','ManagerController@index')->name('managers');
    Route::post('create','ManagerController@create')->name('manager.create');
    Route::post('storeForm','ManagerController@storeForm')->name('manager.storeForm');
    Route::post('delete','ManagerController@delete')->name('manager.delete');

    // Dashboard
    Route::get('dashboard','DashboardController@dashboard')->name('dashboard');
    Route::get('change-password','HomeController@changePassword')->name('changePassword');
    Route::post('change-password','HomeController@PasswordUpdate')->name('changePassword');
    Route::post('logout','HomeController@logout')->name('logout');

    //Projects
    Route::get('pending-works', 'WorkController@pending')->name('work.pending');
    Route::get('all-works', 'WorkController@all')->name('work.all');
    Route::post('approve-project', 'WorkController@approve')->name('work.approve');
    Route::post('delete-project', 'WorkController@delete')->name('work.delete');

    //member manage
    Route::get('member', 'MemberManageController@ShowAllMember')->name('member.all');
    Route::get('pending-regs', 'MemberManageController@pending')->name('member.pending');
    Route::get('pending-regs/approve/{id}', 'MemberManageController@approve')->name('member.approve');
    Route::get('pending-regs/reject/{id}', 'MemberManageController@reject')->name('member.reject');
    Route::get('member/{id}', 'MemberManageController@ShowMemberDetails')->name('member.details');
    Route::post('member-update', 'MemberManageController@MemberUpdate')->name('member.update');

    Route::get('withdraw-report/{id}', 'MemberManageController@WithdrawReport')->name('member.withdraw_report');
    Route::get('campaign-report/{id}', 'MemberManageController@CampaignReport')->name('member.campaign_report');
    Route::get('project-report/{id}', 'MemberManageController@projectReport')->name('member.project_report');
    Route::get('gig-report/{id}', 'MemberManageController@gigReport')->name('member.gig_report');
    Route::get('member/export/excel', 'MemberManageController@excel_export')->name('member.export');
    Route::get('member/export/referrals', 'MemberManageController@excel_referrals')->name('member.export.referrals');

    //Withdraw methods
    Route::get('withdraw', 'WithdrawController@index')->name('withdraw.index');
    Route::post('withdraw/store', 'WithdrawController@store')->name('withdraw.store');
    Route::put('withdraw/update/{id}', 'WithdrawController@update')->name('withdraw.update');
    Route::delete('withdraw/destroy', 'WithdrawController@destroy')->name('withdraw.destroy');

    Route::get('withdraw-request', 'DashboardController@ShowWithdrawRequest')->name('show.withdraw.request');
    Route::get('withdraw-log', 'DashboardController@ShowWithdrawLog')->name('show.withdraw.log');
    Route::post('withdraw-approve', 'DashboardController@WithdrawApproved')->name('withdraw.approve');
    Route::post('withdraw-reject', 'DashboardController@WithdrawReject')->name('withdraw.reject');

    // Excel Exports
    Route::get('work/export','WorkController@export_excel')->name('work.export');
    Route::get('withdraw/export','WithdrawController@export_excel')->name('withdraw.export');

    // Employers
    Route::get('employers','EmployerController@index')->name('employers');
    Route::post('employer/login','EmployerController@login')->name('employer.login');
});


Route::get('/', function () {
    return view('welcome');
})->name("index");
Auth::routes(['verify' => true]);
Route::get('/home', 'HomeController@index')->name('home');

// Non-authenticated Employer
Route::get('for-businesses','Employer\HomeController@to_register')->name('employer.for-businesses');
Route::post('for-businesses','Employer\HomeController@register')->name('employer.register');
Route::get('business/login','Employer\HomeController@to_login')->name('employer.login');
Route::get('company/verify-email','Employer\HomeController@resendemail')->name('employer.verify.email')->middleware(['employerAuth']);
Route::post('company/verify-email','Employer\HomeController@brand')->name('employer.verify.emailr')->middleware(['employerAuth']);
Route::get('company/brand','Employer\DashboardController@brand')->name('employer.brand')->middleware(['employerAuth','empEmail']);
Route::post('company/brand','Employer\DashboardController@savecompany')->name('employer.save.company')->middleware(['employerAuth','empEmail']);
Route::post('business/login','Employer\HomeController@login')->name('employer.login');
Route::get('company/logout','Employer\HomeController@logout')->name('employer.logout')->middleware(['employerAuth']);

// Truecaller
Route::post('truecaller','TrueCallerController@login')->name('truecaller.login');

//World Controller
Route::post('/getstates','WorldController@states')->name('world.states');
Route::post('getcities','WorldController@cities')->name('world.cities');

// Employer
Route::middleware(['employerAuth','empEmail','BrandCheck'])->namespace('Employer')->prefix('company')->name('employer.')->group(function(){
    Route::get('dashboard','DashboardController@dashboard')->name('dashboard');
    Route::get('profile','DashboardController@profile')->name('profile');
    Route::post('profile','DashboardController@update_profile')->name('profile');
    Route::post('profile-image','DashboardController@upload_profile_image')->name('profile_image.update');
    Route::get('change-pass','DashboardController@change_passr')->name('changepass');
    Route::post('change-pass','DashboardController@change_pass')->name('changepass');
    
    Route::get('works','WorkController@index')->name('work.manage');
    Route::get('works/post','WorkController@create')->name('work.post');
    Route::post('works/post','WorkController@store')->name('work.post');
    Route::post('works/delete','WorkController@delete')->name('work.delete');
    Route::get('works/edit/{id}','WorkController@edit')->name('work.edit');
    Route::post('works/edit/{id}','WorkController@update')->name('work.edit');
    Route::get('works/applications/{id}','WorkController@applications')->name('work.applications');
    Route::post('works/shortlist/{id}','WorkController@shortlist')->name('work.shortlist');
    Route::post('works/select/{id}','WorkController@select')->name('work.select');
    Route::post('works/reject/{id}','WorkController@reject')->name('work.reject');
    Route::get('works/files/{id}','WorkController@files')->name('work.files');
    Route::post('works/accept','WorkController@accept')->name('work.accept');
    
    Route::get('wallet','WalletController@index')->name('wallet');
    Route::post('wallet/add','WalletController@addMoney')->name('wallet.add');
    Route::post('wallet/add/done/{amount}','WalletController@doneMoney')->name('wallet.add.done');
});
Route::get('employer/email-not-verified',function(){
    return view('employer.pages.text_email_verify');
})->name('employer.email.verify');

// Project
Route::get('works','WorkController@list')->name('works');
Route::get('work/{id}/{name}','WorkController@details')->name('work.details');
Route::post('work/category','WorkController@cat')->name('work.cat');
Route::middleware(["auth"])->group(function(){
    Route::post('work/apply','WorkController@apply')->name('work.apply');
});

// Applicant View
Route::get('users/{id}','JobController@selecteds')->name('applicant.view');
Route::get('users/{id}/print','JobController@selecteds')->name('print.view');


// Users
Route::get('user/mobile-not-verified','User\DashboardController@mobileNotVerified')->name('user.mobile-not-ver')->middleware(['auth','verified']);
Route::get('user/verify-mobile','User\DashboardController@verifyMobiler')->name('user.verify-mobile')->middleware(['auth','verified']);
Route::post('user/verify-mobile','User\DashboardController@verifyMobile')->name('user.verify-mobilep')->middleware(['auth','verified']);
Route::middleware(['auth','verified'])->namespace('User')->prefix('user')->name('user.')->group(function(){
    Route::get('app-download','DashboardController@download')->name('download_app');
    Route::get('dashboard','DashboardController@dashboard')->name('dashboard');
    Route::get('resume','DashboardController@resume')->name('resume');
    Route::post('edu-update','DashboardController@eduUpdate')->name('edu-update');
    Route::post('exp-update','DashboardController@expUpdate')->name('exp-update');
    Route::post('proj-update','DashboardController@projUpdate')->name('proj-update');
    Route::post('skill-update','DashboardController@skillUpdate')->name('skill-update');
    Route::post('hobby-update','DashboardController@hobbyUpdate')->name('hobby-update');
    Route::post('ach-update','DashboardController@achUpdate')->name('ach-update');
    Route::post('social-update','DashboardController@socialUpdate')->name('social-update');
    Route::get('edu-delete/{id}','DashboardController@eduDelete')->name('edu-delete');
    Route::get('exp-delete/{id}','DashboardController@expDelete')->name('exp-delete');
    Route::get('proj-delete/{id}','DashboardController@projDelete')->name('proj-delete');
    Route::get('skill-delete/{id}','DashboardController@skillDelete')->name('skill-delete');
    Route::get('profile','DashboardController@profile')->name('profile');
    Route::post('profile','DashboardController@updateProfile')->name('profile');
    Route::get('change-pass','DashboardController@changepwr')->name('changePassword');
    Route::post('change-pass','DashboardController@updatePassword')->name('changePassword');

    // Wallet
    Route::get('wallet','WalletController@index')->name('wallet');
    Route::get('logout','DashboardController@logout')->name('logout');

    // Works
    Route::get('works','WorkController@works')->name('works.show');
    Route::get('works/work/{id}','WorkController@work')->name('works.work');
    Route::post('works/work/{id}/whole','WorkController@whole')->name('works.work.whole');
    Route::post('works/work/{id}/objective','WorkController@objective')->name('works.work.objective');
});

//User View
Route::post('acc_details',"RazorpayController@create_contact");
Route::get('users/{id}','ApplicantController@index')->name('applicant.view');
Route::get('users/{id}/print-resume','ApplicantController@printv')->name('print.view');
Route::get('users/{id}/print-pdf','ApplicantController@print')->name('print.pdf');

//Certificate Controller
Route::get('certificate/{jid}/user/{uid}/view','ApplicantController@printc')->name('certificate.print');


//Manager Routes
Route::get('manager/users/export/excel','Admin\MemberManageController@excel_export')->name('manager.member.export');
Route::get('manager/users/export/excel/ref','Admin\MemberManageController@excel_referrals')->name('manager.member.export.referrals');
Route::get('manager/login','Manager\HomeController@loginr')->name('manager.loginr');
Route::post('manager/login','Manager\HomeController@login')->name('manager.login');
Route::middleware(['Manager'])->prefix('manager')->name('manager.')->namespace('Manager')->group(function(){
    Route::get('dashboard','MainController@dashboard')->name('dashboard');
    Route::get('pending-projects','MainController@pendingjobs')->name('pendingjobs');
    Route::get('all-projects','MainController@jobAll')->name('jobs.all');
    Route::post('pending-projects/approve','MainController@jobApprove')->name('job.approve');
    Route::post('pending-projects/reject','MainController@jobReject')->name('job.delete');
    Route::get('pending-gigs','MainController@pendingGigs')->name('gigs.pending');
    Route::get('all-gigs','MainController@allGigs')->name('gigs.all');
    Route::get('create-gigs','MainController@createGig')->name('gigs.create');
    Route::post('create-gigs','MainController@storeGig')->name('gig.create');
    Route::get('approve-campaign/{id}','MainController@approveCampaign')->name('campaign.approve');
    Route::get('reject-campaign/{id}','MainController@rejectCampaign')->name('campaign.reject');
    // Campaigns
    Route::get('campaigns','CampaignController@index')->name('missions');
    Route::get('campaign/create','CampaignController@creater')->name('mission.create');
    Route::post('campaign/create','CampaignController@create')->name('mission.create');
    Route::post('storeForm','CampaignController@storeForm')->name('mission.storeForm');
    Route::post('campaign/delete','CampaignController@delete')->name('mission.delete');
    Route::get('campaign/applications/{id}','CampaignController@applications')->name('mission.applications');
    Route::get('campaign/applications/accept/{id}','CampaignController@accept')->name('mission.accept');
    Route::get('campaign/applications/reject/{id}','CampaignController@reject')->name('mission.reject');
    Route::get('campaign/response/{id}','CampaignController@response')->name('mission.response');
    Route::post('campaign/response/accept','CampaignController@acceptResp')->name('mission.acceptResp');
    Route::post('campaign/response/reject','CampaignController@rejectResp')->name('mission.rejectResp');

    // Employers
    Route::get('employers','EmployerController@index')->name('employers');
    Route::post('employer/login','EmployerController@login')->name('employer.login');

    // Telecallings
    Route::get('telecallings','TelecallingController@index')->name('telecallings');
    Route::get('telecallings/create','TelecallingController@create')->name('telecalling.create');
    Route::post('telecallings/create','TelecallingController@createPost')->name('telecalling.create');
    Route::post('telecallings/delete','TelecallingController@delete')->name('telecalling.delete');
    Route::get('telecalling/applications/{id}','TelecallingController@applications')->name('telecalling.applications');
    Route::post('telecalling/distribute','TelecallingController@distribute')->name('telecalling.distribute');
    Route::post('telecalling/application/select','TelecallingController@select')->name('telecalling.select');
    Route::post('telecalling/application/reject','TelecallingController@reject')->name('telecalling.reject');
    Route::get('telecalling/application/view-data/{tid}/{uid}','TelecallingController@viewData')->name('telecalling.viewdata');
    Route::get('telecalling/application/view-feedback/{id}','TelecallingController@feedback')->name('telecalling.feedback');

    
    Route::get('logout','HomeController@logout')->name('logout');
});

// TEST
// Route::get('test','TestController@test');
Route::get('/acc_details',"RazorpayController@create_contact");
Route::post('/acc_details',"RazorpayController@add_contact");
Route::get('/givereward/{id}/{amt}',"RazorpayController@givereward");

