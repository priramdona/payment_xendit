<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\admin\DashboardController;
use App\Http\Controllers\admin\JobApplicationController;
use App\Http\Controllers\admin\JobController;
use App\Http\Controllers\admin\UserController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\JobsController;
use App\Http\Controllers\PaymentGatewayController;
use App\Http\Controllers\XenditWebhookController;
use App\Models\PaymentMethod;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });
Route::group([
    'prefix' => 'payment-gateways',
], function () {
    Route::any('/payment-methods-callback', [XenditWebhookController::class, 'callbackPaymentMethod']);
    Route::any('/payment-methods-succeeded', [XenditWebhookController::class, 'callbackPaymentSucceeded']);
    Route::any('/create-va-callback', [XenditWebhookController::class, 'callbackCreateVirtualAccount']);
    Route::any('/va-paid', [XenditWebhookController::class, 'callbackPaidVirtualAccount']);
    Route::any('/invoice-paid', [XenditWebhookController::class, 'callbackPaidInvoice']);
    Route::any('/paylater-paid', [XenditWebhookController::class, 'callbackPaidPaylater']);
    Route::any('/disbursements', [XenditWebhookController::class, 'disbursments']);

});
Route::get('/check-payment', [PaymentGatewayController::class, 'checkPayment']);
Route::get('/',[HomeController::class,'index'])->name('home');
Route::get('/jobs',[JobsController::class,'index'])->name('jobs');
Route::get('/jobs/fetch', [JobController::class, 'fetchJobs'])->name('jobs.fetch');
Route::get('/jobs/detail/{id}',[JobsController::class,'detail'])->name('jobDetail');
Route::post('/apply-job',[JobsController::class,'applyJob'])->name('applyJob');
Route::post('/save-job',[JobsController::class,'saveJob'])->name('saveJob');

Route::get('/contacts', [AccountController::class, 'contactus'])->name('contacts');
Route::post('/contacts', [AccountController::class, 'sendcontactus'])->name('contact.store');

Route::get('/forgot-password',[AccountController::class,'forgotPassword'])->name('account.forgotPassword');
Route::post('/process-forgot-password',[AccountController::class,'processForgotPassword'])->name('account.processForgotPassword');
Route::get('/reset-password/{token}',[AccountController::class,'resetPassword'])->name('account.resetPassword');
Route::post('/process-reset-password',[AccountController::class,'processResetPassword'])->name('account.processResetPassword');

// Route::group(['prefix' => 'admin','middleware' => 'checkRole'], function(){
//     Route::get('/dashboard',[DashboardController::class,'index'])->name('admin.dashboard');
//     Route::get('/users',[UserController::class,'index'])->name('admin.users');
//     Route::get('/users/{id}',[UserController::class,'edit'])->name('admin.users.edit');
//     Route::put('/users/{id}',[UserController::class,'update'])->name('admin.users.update');
//     Route::delete('/users',[UserController::class,'destroy'])->name('admin.users.destroy');
//     Route::get('/jobs',[JobController::class,'index'])->name('admin.jobs');
//     Route::get('/jobs/edit/{id}',[JobController::class,'edit'])->name('admin.jobs.edit');
//     Route::put('/jobs/{id}',[JobController::class,'update'])->name('admin.jobs.update');
//     Route::delete('/jobs',[JobController::class,'destroy'])->name('admin.jobs.destroy');
//     Route::get('/job-applications',[JobApplicationController::class,'index'])->name('admin.jobApplications');
//     Route::delete('/job-applications',[JobApplicationController::class,'destroy'])->name('admin.jobApplications.destroy');
// });

Route::group(['prefix' => 'account'], function(){

    // Guest Route
    Route::group(['middleware' => 'guest'], function(){
        Route::get('/register',[AccountController::class,'registration'])->name('account.registration');
        Route::post('/process-register',[AccountController::class,'processRegistration'])->name('account.processRegistration');
        Route::get('/login',[AccountController::class,'login'])->name('account.login');
        Route::post('/authenticate',[AccountController::class,'authenticate'])->name('account.authenticate');
    });

    // Authenticated Routes
    Route::group(['middleware' => 'auth'], function(){
        Route::get('/profile',[AccountController::class,'profile'])->name('account.profile');
        Route::get('/update-profile',[AccountController::class,'updateProfile'])->name('account.updateProfile');
        Route::get('/logout',[AccountController::class,'logout'])->name('account.logout');
        Route::post('/update-profile-pic',[AccountController::class,'updateProfilePic'])->name('account.updateProfilePic');
        // Route::get('/create-job',[AccountController::class,'createJob'])->name('account.createJob');
        // Route::post('/save-job',[AccountController::class,'saveJob'])->name('account.saveJob');
        // Route::get('/my-jobs',[AccountController::class,'myJobs'])->name('account.myJobs');
        // Route::get('/my-jobs/edit/{jobId}',[AccountController::class,'editJob'])->name('account.editJob');
        // Route::post('/update-job/{jobId}',[AccountController::class,'updateJob'])->name('account.updateJob');
        // Route::post('/delete-job',[AccountController::class,'deleteJob'])->name('account.deleteJob');
        // Route::get('/my-job-applications',[AccountController::class,'myJobApplications'])->name('account.myJobApplications');

        // Route::post('/remove-job-application',[AccountController::class,'removeJobs'])->name('account.removeJobs');
        // Route::get('/saved-jobs',[AccountController::class,'savedJobs'])->name('account.savedJobs');
        // Route::post('/remove-saved-job',[AccountController::class,'removeSavedJob'])->name('account.removeSavedJob');
        Route::post('/update-password',[AccountController::class,'updatePassword'])->name('account.updatePassword');
    });


    Route::get('/payment-method/{type}',[AccountController::class,'paymentMethod'])->name('payment.method');
    Route::get('/payment-job/{id}',[AccountController::class,'paymentJob'])->name('payment.job');

    Route::get('/payment-method-va',[AccountController::class,'paymentMethodVa'])->name('payment.method.va');
    Route::get('/payment-summary', function (Illuminate\Http\Request $request) {
        $selectedId = $request->methodId; // Ambil metode pembayaran dari query string
        $jobId = $request->jobId ?? 'DONASI'; // Ambil metode pembayaran dari query string
        $paymentMethods = PaymentMethod::find($selectedId);

        return view('front.paymentsummary', ['paymentMethods' => $paymentMethods, 'jobId' => $jobId]);
    })->name('account.payment-summary');
    Route::post('/payment-process/{id}', [AccountController::class, 'processPayment'])->name('payment-process');

    Route::get('/sucsesspayment', [PaymentGatewayController::class, 'succesPayment'])->name('succespayment');
    Route::get('/failedpayment', [PaymentGatewayController::class, 'failedPayment'])->name('failedpayment');
    Route::get('/underprocess', [PaymentGatewayController::class, 'underprocess'])->name('underprocess');
    Route::get('/success-payment-process', [AccountController::class, 'successPaymentProcess'])->name('success-payment-process');

});
