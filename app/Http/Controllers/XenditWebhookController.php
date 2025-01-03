<?php

namespace App\Http\Controllers;

use App\Models\XenditCallbackPaymentRequest;
use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Http\Controllers\UtilityController;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use App\Events\Payment\ProcessPaymentXenditSuccessEvent;
use App\Http\Controllers\Callbacks\Payments\Enum\EventCaptureEnum;
use App\Http\Controllers\Callbacks\Payments\Enum\EventPaymentEnum;
use App\Http\Controllers\Callbacks\Payments\Enum\EventPaymentMethodEnum;
use App\Http\Controllers\Callbacks\Payments\Enum\PaymentStatusEnum;
use App\Models\businessAmount;
use App\Models\JobApplication;
use App\Models\MessageNotification;
use App\Models\PaymentTransaction;
use App\Models\XenCallback;
use App\Models\XenditCreatePayment;
use App\Models\XenditDisbursement;
use App\Models\XenditPaymentMethod;
use App\Models\XenditPaymentRequest;
use App\Models\XenditVirtualAccountRequest;
use App\Services\PaymentSubscribes\PaymentSubscribeService;
use App\Services\PaymentTransactions\Enums\PaymentTransactionStatusEnum;
use App\Services\Subscribes\SubscribeService\SubscribeService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
class XenditWebhookController extends Controller
{
    public function callbackPaymentMethod(Request $request)
    {
        $data = $request->all();
        try {
            $status =  $data['data']['status'] ?? 'Erorr';

            XenditCallbackPaymentRequest::create([
                'id' => Str::orderedUuid()->toString(),
                'callback_id' => $data['id'],
                'reference_id' => $data['data']['reference_id'],
                'data' => json_encode($data['data']),
                'event' => $data['event'],
                'status' => $status ,
                'failure_code' => $data['data']['failure_code'],
                'xen_business_id' =>  $data['business_id'] ?? null,
            ]);

            $xenditCreatePayments = XenditCreatePayment::query()
                ->where('reference_id', $data['data']['reference_id'])
                ->first();

                if ($xenditCreatePayments){
                    if ($data['event'] == 'payment_method.expired') {
                        if ($xenditCreatePayments->status != 'Paid') {
                            $xenditCreatePayments->status = $status;
                        }
                    }else{
                        $xenditCreatePayments->status = $status;
                    }

                     //update business_amount
                    $businessAmount = null;
                    $businessAmount = JobApplication::query()
                    ->where('reference_id', $data['data']['reference_id'])
                    ->first();

                    if ($businessAmount){
                        if ($data['event'] == 'payment_method.expired') {
                            if ($businessAmount->status != 'Paid') {
                                $businessAmount->status = $status;
                            }
                        }else{
                            $businessAmount->status = $status;
                        }

                    }else{
                        return response()->json("No Business Found....", 422);
                    }
                    //end of update business_amount

                    //update xendit_Payment_requests
                    $paymentRequest = null;
                    $paymentRequest = XenditPaymentRequest::query()
                    ->where('reference_id', $data['data']['reference_id'])
                    ->first();

                    if ($paymentRequest){
                        if ($data['event'] == 'payment_method.expired') {
                            if ($paymentRequest->status != 'Paid') {
                                $paymentRequest->status = $status;
                            }
                        }else{
                            $paymentRequest->status = $status;
                        }
                    }else{
                        return response()->json("No Payment Request Found....", 422);
                    }
                    //end of update xendit_Payment_requests

                    //update xendit_Payment_method

                    $paymentMethod = null;
                    $paymentMethod = XenditPaymentMethod::query()
                    ->where('reference_id', $data['data']['reference_id'])
                    ->first();

                    if ($paymentMethod){
                        if ($data['event'] == 'payment_method.expired') {
                            if ($paymentMethod->status != 'Paid') {
                                $paymentMethod->status = $status;
                            }
                        }else{
                            $paymentMethod->status = $status;
                        }
                    }else{
                        return response()->json("No Payment Method Found....", 422);
                    }


                    $xenditCreatePayments->save();
                    $businessAmount->save();
                    $paymentRequest->save();
                    $paymentMethod->save();
                }
                else{
                    //payment-methods-callback
                    return response()->json("No Record Found....", 422);
                }


            return response()->json([], 200);

        } catch (\Exception $exception) {
            // Log::driver('sentry');
            return response()->json([$exception->getMessage()], 422);
        }
    }
    public function callbackPaidVirtualAccount(Request $request)
    {
        $data=[];
        $data = $request->all();
        $sourceType = null;
        $sourceId = null;

        try {

            $xenditVirtualAccountRequest = XenditVirtualAccountRequest::query()
                ->where('external_id', $data['external_id'])
                ->first();

                if ($xenditVirtualAccountRequest){
                    $xenditVirtualAccountRequest->status = "Paid";
                    $xenditVirtualAccountRequest->paid_information = json_encode($data);
                    $xenditVirtualAccountRequest->transaction_timestamp = Carbon::parse($data['transaction_timestamp'])->setTimezone(config('app.timezone'))->format('Y-m-d H:i:s');

                    $xenditCreatePayments = XenditCreatePayment::query()
                    ->where('reference_id', $data['external_id'])
                    ->first();

                    if ($xenditCreatePayments){
                            $xenditCreatePayments->status = "Paid";

                            //update business_amount
                            $businessAmount = null;
                            $businessAmount = JobApplication::query()
                            ->where('reference_id', $data['external_id'])
                            ->first();

                            if ($businessAmount){
                                $businessAmount->status = 'Paid';
                            }else{
                                return response()->json("No Business Found....", 422);
                            }
                            //end of update business_amount

                    }
                    else{
                        return response()->json("No Record Found....", 422);
                    }

                    $xenditVirtualAccountRequest->save();
                    $xenditCreatePayments->save();
                    $businessAmount->save();

                }else{
                    return response()->json("No Virtual Record Found....", 422);
                }


                return response()->json([], 200);

        } catch (\Exception $exception) {
            // Log::driver('sentry');
            return response()->json([$exception->getMessage()], 422);
        }
    }
    public function disbursments(Request $request)
    {
        $data = $request->all();
        $status = null;
        $statusMsg = null;
        // $failureCode = null;
        try {
            if ($data['event'] == 'payout.succeeded') {
                $status = "Success";
                $statusMsg = "Diajukan";
            }else{
                $status = $data['data']['status'];
                $statusMsg = $data['data']['status'];
            }

            $xenditDisbursement = XenditDisbursement::query()
                ->where('reference_id', $data['data']['reference_id'])
                ->first();

                if ($xenditDisbursement){
                    $xenditDisbursement->status = $status;
                    $xenditDisbursement->estimated_arrival_time = Carbon::parse($data['data']['estimated_arrival_time'])->setTimezone(config('app.timezone'))->format('Y-m-d H:i:s');

                    $businessAmount = JobApplication::query()
                    ->where('reference_id', $data['data']['reference_id'])
                    ->first();

                    if ($businessAmount){
                        $businessAmount->status = $status;
                        $businessAmount->save();
                    }

                    $xenditDisbursement->save();
                }else{
                    return response()->json("No Disbursement Record Found....", 422);
                }

                return response()->json([], 200);

        } catch (\Exception $exception) {
            // Log::driver('sentry');
            return response()->json([$exception->getMessage()], 422);
        }
    }
    public function callbackCreateVirtualAccount(Request $request)
    {
        $data = $request->all();
        try {
            $status =  $data['status'] ?? 'Erorr';

            $xenditCreatePayments = XenditVirtualAccountRequest::query()
                ->where('external_id', $data['external_id'])
                ->first();

                if ($xenditCreatePayments){
                    $xenditCreatePayments->status = $status;
                    $xenditCreatePayments->created = Carbon::parse($data['created'])->setTimezone(config('app.timezone'))->format('Y-m-d H:i:s');
                    $xenditCreatePayments->updated = Carbon::parse($data['updated'])->setTimezone(config('app.timezone'))->format('Y-m-d H:i:s');
                    $xenditCreatePayments->expected_amount = $data['expected_amount'];
                    $xenditCreatePayments->save();

                }else{
                    //create-va-callback
                    return response()->json("No Virtual Record Found....", 422);
                }

                return response()->json([], 200);

        } catch (\Exception $exception) {
            // Log::driver('sentry');
            return response()->json([$exception->getMessage()], 422);
        }
    }
    public function callbackPaymentSucceeded(Request $request)
    {
        $data = [];
        $data = $request->all();
        $sourceType = null;
        $sourceId = null;

        try {

            if ($data['event'] == 'payment.succeeded') {
                $referenceId = $data['data']['payment_method']['reference_id'] ?? null;

                if (!$referenceId){
                    return response()->json("Invalid reference_id....", 422);
                }

                $xenditCreatePaymentRequest = XenditPaymentRequest::query()
                ->where('reference_id', $referenceId)
                ->first();

                if ($xenditCreatePaymentRequest){
                    $xenditCreatePaymentRequest->status = "Paid";
                    $xenditCreatePaymentRequest->paid_information = json_encode($data);
                    $xenditCreatePaymentRequest->transaction_timestamp = Carbon::parse($data['created'])->setTimezone(config('app.timezone'))->format('Y-m-d H:i:s');

                        $xenditCallbackPaymentRequest = XenditCallbackPaymentRequest::query()
                        ->where('reference_id', $referenceId)
                        ->first();

                        if ($xenditCallbackPaymentRequest){
                            $xenditCallbackPaymentRequest->status = "Paid";
                        }else{
                            return response()->json("Callback Payment Request Record not Found....", 422);
                        }

                        $xenditPaymentMethod = XenditPaymentMethod::query()
                            ->where('reference_id', $referenceId)
                            ->first();

                        if ($xenditPaymentMethod){
                            $xenditPaymentMethod->status = "Paid";
                        }else{
                            return response()->json("Payment Method Record not Found....", 422);
                        }

                        $xenditCreatePayments = XenditCreatePayment::query()
                            ->where('reference_id', $referenceId)
                            ->first();

                        if ($xenditCreatePayments){
                            $xenditCreatePayments->status = "Paid";
                        }else{
                            return response()->json("Create Payment Record not Found....", 422);
                        }

                         //update business_amount
                            $businessAmount = null;
                            $businessAmount = JobApplication::query()
                            ->where('reference_id', $referenceId)
                            ->first();

                            if ($businessAmount){
                                $businessAmount->status = 'Paid';
                            }else{
                                return response()->json("No Business Found....", 422);
                            }


                    $xenditCreatePaymentRequest->save();
                    $xenditCallbackPaymentRequest->save();
                    $xenditPaymentMethod->save();
                    $xenditCreatePayments->save();
                    $businessAmount->save();

                }else{
                         // Kirim ke Project B jika data tidak ditemukan
                    return response()->json("Payment Request Record not Found....", 422);
                }
            }else{
                return response()->json("Event status invalid....", 422);
            }

            return response()->json([], 200);


        } catch (\Exception $exception) {
            // Log::driver('sentry');
            return response()->json([$exception->getMessage()], 422);
        }
    }

    private function forwardToProjectB($data, $method)
    {
        try {
            $url = "https://www.donasikita.com/payment-gateways/{$method}";

            $response = Http::post($url, $data);

            if ($response->successful()) {
                return response()->json([], 200);
            }

            return response()->json("Payment Request Record not Found....", 422);
        } catch (\Exception $e) {
            return response()->json("Error process", 422);
        }
    }

}
