<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Business;
use App\Models\DataConfig;
use App\Models\masterConfig;
use App\Models\MasterConfig as ModelsMasterConfig;
use App\Models\PaymentMethod as ModelsPaymentMethod;
use Carbon\Carbon;
use Exception;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Sale\Http\Requests\StorePosSaleRequest;
use Illuminate\Support\Str;
use Milon\Barcode\DNS1D;
use Milon\Barcode\DNS2D;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Milon\Barcode\Facades\DNS1DFacade;
use Milon\Barcode\Facades\DNS2DFacade;
use Xendit\PaymentMethod\PaymentMethod as PaymentMethodPaymentMethod;
use Xendit\PaymentRequest\PaymentMethod as PaymentRequestPaymentMethod;

use App\Models\Category;
use App\Models\Job;
use App\Models\JobApplication;
use App\Models\JobType;
use App\Models\PaymentMethod;
use App\Models\SavedJob;
use App\Models\User;
use App\Models\XenditCreatePayment;
use App\Models\XenditPaymentMethod;
use App\Models\XenditPaymentRequest;
use App\Models\XenditVirtualAccountRequest;
use Illuminate\Support\Facades\Http;
use Xendit\Configuration;
use Xendit\PaymentRequest\PaymentRequestApi;
use Xendit\PaymentRequest\PaymentRequestParameters;

class PaymentGatewayController extends Controller
{
    public function createPaymentGatewayRequest(
        string $paymentMethodId,
        float $totalAmount,
        string $sourceId,
        string $forUserId,
        string $keyPrivate,
        ?string $nameVA = null,
        ?string $numberPhone = null,
       )
        {
        $valueResponse = null;
        $responseType = null;
        $nameResponse = null;
        $expireResponse = null;
        $paymentChannelData = PaymentMethod::find($paymentMethodId);
        $reffPayment =  Str::orderedUuid()->toString() . '-' . Carbon::now()->format('Ymdss');
        $phoneNumber = $numberPhone ?? null;
        $dataResult = null;
        $paymentRequestId = null;
        $paymentReferenceId = NULL;

        if ($paymentChannelData){

            if($paymentChannelData->type == 'VIRTUAL_ACCOUNT'){
                $createVirtualAccount = new PaymentGatewayController();

                $virtualAccountInfo = $createVirtualAccount->createVirtualAccount(
                    $reffPayment,
                    $paymentChannelData->code,
                    $totalAmount,
                    $sourceId,
                    $forUserId,
                    $keyPrivate,
                    $nameVA,
                );

                $paymentRequestId = $virtualAccountInfo['id'];
                $paymentReferenceId = $virtualAccountInfo['reference_id'];
                $virtualAccountInfo = $virtualAccountInfo['virtual_account'];
                $nameResponse = $virtualAccountInfo['name'];
                $valueResponse = $virtualAccountInfo['account_number'];
                $expResponseDate = $virtualAccountInfo['expiration_date'];
                $expireResponse = carbon::parse($expResponseDate)->setTimezone(config('app.timezone'))->format('d-m-Y H:m') ?? null;
                $responseType = 'account';

            }
            else{
                //start refer to xendit_payment_request
                if ($paymentChannelData->code == 'OVO'){
                    if(Str::length($phoneNumber) <= 7){
                        throw new \Exception("Payment failed, Phone Number Error! " . "Check again ". $paymentChannelData->code . ' Numbers');
                    }
                }

                $paymentGatewayController = new PaymentGatewayController();

                $paymentResponse = $paymentGatewayController->createPaymentRequest(
                    keyPrivate: $keyPrivate,
                    refId: $reffPayment,
                    forUserId:null,
                    withSplitRule:null,
                    amount: $totalAmount,
                    saleAmount: $totalAmount,
                    type:$paymentChannelData->type,
                    channelCode:$paymentChannelData->code,
                    sourceId:$sourceId,
                    reusability:'ONE_TIME_USE',
                    phoneNumber: $phoneNumber,
                );

                $responseArray = $paymentResponse->getData(true);
                $dataResult = $responseArray['data'];

                $paymentRequestId = $dataResult['id'];
                $paymentReferenceId = $dataResult['reference_id'];
                $paymentRequests = $dataResult['payment_requests'];


                $responseActions = json_decode($paymentRequests['actions'], true);

                if ($responseActions){
                    foreach ($responseActions as $item) {
                        if($paymentChannelData->type =='EWALLET'){
                            if (($paymentChannelData->code == 'ASTRAPAY') || ($paymentChannelData->code == 'LINKAJA') || ($paymentChannelData->code == 'DANA')){
                                if ($item['url_type']== 'MOBILE'){
                                    if (isset($item['qr_code']) && !is_null($item['qr_code'])) {
                                        $valueResponse = DNS2DFacade::getBarcodeHTML($item['qr_code'], 'QRCODE', 8,8 );
                                        $responseType = 'qrcode';
                                    }
                                    else{
                                        if (isset($item['url']) && !is_null($item['url'])){
                                            $valueResponse = $item['url'];
                                            $responseType = 'url';
                                        }else{
                                            throw new \Exception('Payment failed., Please try again');
                                        }
                                    }
                                }

                            }elseif($paymentChannelData->code == 'SHOPEEPAY'){
                                if ($item['action']== 'PRESENT_TO_CUSTOMER'){
                                    if (isset($item['qr_code']) && !is_null($item['qr_code'])) {
                                        $valueResponse = DNS2DFacade::getBarcodeHTML($item['qr_code'], 'QRCODE', 8,8 );
                                        $responseType = 'qrcode';
                                    }
                                    else{
                                        throw new \Exception('Payment failed., Please try again');
                                    }
                                }
                            }elseif($paymentChannelData->code == 'OVO'){
                                $valueResponse = "Please payment on Customer OVO's Account";
                                $responseType = 'info';
                            }else{
                                throw new \Exception("Payment failed, Payment Channel doesn't exist" . $paymentChannelData->code);
                            }
                        }else{
                            throw new \Exception("Payment failed, Payment Type doesn't exist for ". $paymentChannelData->type);
                        }
                    }

                }else{
                    $responseActions = json_decode($paymentRequests['payment_method'], true);
                    if ($responseActions){
                        if($paymentChannelData->type =='VIRTUAL_ACCOUNT'){
                            $nameResponse = $responseActions['virtual_account']['channel_properties']['customer_name'];
                            $valueResponse = $responseActions['virtual_account']['channel_properties']['virtual_account_number'];
                            $expResponseDate = $responseActions['virtual_account']['channel_properties']['expires_at'];
                            $expireResponse = carbon::parse($expResponseDate)->setTimezone(config('app.timezone'))->format('d-m-Y H:m') ?? null;
                            $responseType = 'account';
                        }elseif(($paymentChannelData->type =='EWALLET') && ($paymentChannelData->code =='OVO')){
                            $valueResponse = $responseActions['ewallet']['channel_properties']['mobile_number'];
                            $responseType = 'info';
                        }elseif($paymentChannelData->type =='QR_CODE'){
                            // $valueResponse = DNS2DFacade::getBarcodeHTML($responseActions['qr_code']['channel_properties']['qr_string'], 'QRCODE', 8,8 );
                            // $valueResponse = DNS2DFacade::getBarcodePNG($responseActions['qr_code']['channel_properties']['qr_string'], 'QRCODE', 8,8 );
                                $valueResponse = $responseActions['qr_code']['channel_properties']['qr_string'];
                            $responseType = 'qrcode';
                        }else{
                            throw new \Exception('Payment failed., Please try again');
                        }
                    }
                }
                //end refer to xendit_payment_request
            }
        }

        return response()->json(data: [
            'payment_request_id' => $paymentRequestId ?? null,
            'reference_id' => $paymentReferenceId ?? null,
            'name_response' => $nameResponse ?? null,
            'value_response' => $valueResponse,
            'expired_response' => $expireResponse,
            'response_type' => $responseType,
            'nominal_information' => $totalAmount,
        ]);
    }

     public function createPaymentRequest(string $refId,
        string $keyPrivate,
        ?string $forUserId = null,
        ?string $withSplitRule = null,
        int $amount,
        int $saleAmount,
        string $type,
        string $channelCode,
        string $sourceId,
        ?string $reusability = 'ONE_TIME_USE',
        ?string $phoneNumber = null,
        ?array $basket = null,
        ?array $metadata = null){

        if ($keyPrivate === 'default'){
            Configuration::setXenditKey(config('services.xendit.key'));
        }else{
            Configuration::setXenditKey($keyPrivate);
        }

        if ($forUserId === 'default' || $forUserId === config('services.xendit.user_id')){
            $forUserId = null;
        }

        $apiInstance = new PaymentRequestApi();
        $idempotency_key = rand(1,10000) . Carbon::now()->format('Ymmddss');
        $paymentMethod = null;
        $channelProperties = null;

        $payloadType = null;

        $createPaymentTransactionalType = XenditPaymentRequest::class;
        $createPaymentTransactionalId = null;


        if ($type === 'EWALLET'){
            if ($channelCode == 'OVO'){
                $channelProperties = [
                    'mobile_number' => '62'.$phoneNumber,
                    'expires_at' => Carbon::now()->addMinutes(60)->toIso8601String(),
                ];
            }
            else if ($channelCode == 'DANA' || $channelCode == 'LINKAJA' || $channelCode == 'SHOPEEPAY'  ){
                $channelProperties = [
                'success_return_url' => route('succespayment'),
                ];
            }
            else{
                $channelProperties = [
                    'success_return_url' => route('succespayment'),
                    'failure_return_url' =>route('failedpayment'),
                ];
            }

            $payloadType = [
                'channel_code' => $channelCode,
                'channel_properties' => $channelProperties,
            ];

            $paymentMethod = [
                'type' => $type,
                'reusability' => $reusability,
                'ewallet' => $payloadType,
            ];
        }else if ($type === 'QR_CODE'){
            $paymentMethod =  [
                'type' => $type,
                'reusability' => $reusability,
                'qr_code' => ['channel_code' => $channelCode]
            ];
        }else{
            throw new \Exception('Payment Channel not Exist in Payment Gateway');
        }

        $payloadRequest = [
            'reference_id' => $refId,
            'amount' => $amount,
            'currency' => 'IDR',
            'country' => 'ID',
            'basket' => $basket,
            'metadata' => $metadata,
            'payment_method' => $paymentMethod
        ];

        $paymentRequestParameters = new PaymentRequestParameters($payloadRequest);

    try {
        $dataResult = $apiInstance->createPaymentRequest($idempotency_key, $forUserId, $withSplitRule, $paymentRequestParameters);

        $resultDetails = null;
        $dataPaymentMethods = json_decode($dataResult['payment_method'],true);
        $referenceId = $dataPaymentMethods['reference_id'] ?? null;
        $xenditPaymentRequestResponsePayload = [
            'payment_request_id'=> $dataResult['id'],
            'created'=> Carbon::parse($dataResult['created'])->setTimezone(config('app.timezone'))->format('Y-m-d H:i:s'),  // Konversi ke format MySQL
            'updated'=> Carbon::parse($dataResult['updated'])->setTimezone(config('app.timezone'))->format('Y-m-d H:i:s'),
            'reference_id'=> $referenceId,
            'business_id'=> $dataResult['business_id'],
            'customer_id'=> $dataResult['customer_id'],
            'customer'=> ($dataResult['customer']),
            'amount'=> $dataResult['amount'],
            'min_amount'=> $dataResult['min_amount'] ?? 0,
            'max_amount'=> $dataResult['max_amount'] ?? 0,
            'country'=> $dataResult['country'],
            'currency'=> $dataResult['currency'],
            'payment_method'=> json_encode($dataResult['payment_method']),
            'description'=> $dataResult['description'],
            'failure_code'=> $dataResult['failure_code'],
            'capture_method'=> $dataResult['capture_method'],
            'initiator'=> $dataResult['initiator'],
            'card_verification_results'=> $dataResult['card_verification_results'],
            'status'=> $dataResult['status'],
            'actions'=> json_encode($dataResult['actions']),
            'metadata'=> json_encode($dataResult['metadata']),
            'shipping_information'=> json_encode($dataResult['shipping_information']),
            'items'=> json_encode($dataResult['items']),
        ];

        $xenditPaymentRequest = XenditPaymentRequest::create($xenditPaymentRequestResponsePayload);
        $createPaymentTransactionalId= $xenditPaymentRequest->id;

        $payloadPaymentMethod =[
            'pm_id' => $dataPaymentMethods['id'] ?? null,
            'business_id' => $dataPaymentMethods['business_id'] ?? null,
            'customer_id' => $dataPaymentMethods['customer_id'] ?? null,
            'xendit_payment_request_id' => $xenditPaymentRequest['id'] ?? null,
            'type' => $dataPaymentMethods['type'] ?? null,
            'country' => $dataPaymentMethods['country'] ?? null,
            'amount' => $amount ?? null,
            'transaction_amount' => $saleAmount ?? null,
            'transactional_type' => null,
            'created' => Carbon::parse($dataPaymentMethods['created'])->setTimezone(config('app.timezone'))->format('Y-m-d H:i:s') ?? null,
            'updated' => Carbon::parse($dataPaymentMethods['updated'])->setTimezone(config('app.timezone'))->format('Y-m-d H:i:s') ?? null,
            'description' => $dataPaymentMethods['description'] ?? null,
            'reference_id' => $referenceId,
            'failure_code' => $dataPaymentMethods['failure_code'] ?? null,
            'actions' => json_encode($dataPaymentMethods['actions'] ?? null) ?? null,
            'card' => json_encode($dataPaymentMethods['card'] ?? null) ?? null,
            'direct_debit' => json_encode($dataPaymentMethods['direct_debit'] ?? null) ?? null,
            'ewallet' => json_encode($dataPaymentMethods['ewallet'] ?? null) ?? null,
            'over_the_counter' => json_encode($dataPaymentMethods['over_the_counter'] ?? null) ?? null,
            'virtual_account' => json_encode($dataPaymentMethods['virtual_account'] ?? null) ?? null,
            'qr_code' => json_encode($dataPaymentMethods['qr_code'] ?? null) ?? null,
            'billing_information' => json_encode($dataPaymentMethods['billing_information'] ?? null) ?? null,
            'reusability' => $dataPaymentMethods['reusability'] ?? null,
            'direct_bank_transfer' => $dataPaymentMethods['direct_bank_transfer'] ?? null,
            'status' => $dataPaymentMethods['status'] ?? null,
            'metadata' => json_encode($dataPaymentMethods['metadata'] ?? null) ?? null,
        ];

        $xenditPaymentMethodData = XenditPaymentMethod::create($payloadPaymentMethod);


        $xenditCreatePayments = XenditCreatePayment::create([
            'reference_id' => $referenceId,
            'source_type' => JobApplication::class,
            'source_id' => $sourceId,
            'transactional_type' => $createPaymentTransactionalType,
            'transactional_id' => $createPaymentTransactionalId,
            'amount' => $amount ?? null,
            'transaction_amount' => $saleAmount ?? null,
            'payment_type' => $type,
            'channel_code' => $channelCode,
            'status' => 'PENDING',
        ]);

        $application = JobApplication::find( $sourceId);
        $application->reference_id = $referenceId;
        $application->save();

        $result = [
            'id' => $xenditCreatePayments->id,
            'reference_id' => $xenditCreatePayments->reference_id,
            'payment_requests' => $xenditPaymentRequest,
        ];

// $result = $xenditPaymentRequest;

    } catch (\Xendit\XenditSdkException $e) {
        throw new \Exception(json_encode($e->getMessage()));
    }

    return response()->json([
        'message' => __('Success'),
        'data' => $result,
    ], 200);

}
    public function createVirtualAccount(
        string $refId,
        string $channelCode,
        float $totalAmount,
        ?string $sourceId = null,
        string $forUserId,
        string $keyPrivate,
        ?string $nameVA = 'Pembayaran Virtual Account',
        ){

            if ($keyPrivate === 'default'){
                $base64 = base64_encode(config('services.xendit.key').':');
            }else{
                $base64 = base64_encode($keyPrivate.':');
            }

            if ($forUserId === 'default' || $forUserId === config('services.xendit.user_id')){
                $forUserId = null;
            }

            $secret_key = 'Basic ' . $base64;
            $url = 'https://api.xendit.co/callback_virtual_accounts';

        // try {


            $timestamp = Carbon::now(config('app.timezone'))->addDay()->toIso8601String();

            $payloadRequest = [
                "external_id" => $refId,
                "bank_code" => $channelCode,
                "name" => $nameVA,
                "is_closed" => true,
                "is_single_use" => true,
                "expected_amount" => $totalAmount,
                "expiration_date" => $timestamp
            ];

            if ($forUserId === '6723980923dd7c0b5281a64d'){
                $dataRequest = Http::withHeaders([
                    'Authorization' => $secret_key,
                    'for-user-id' => null
                ])->post($url, $payloadRequest);
            }else{
                $dataRequest = Http::withHeaders([
                    'Authorization' => $secret_key,
                    'for-user-id' => $forUserId
                ])->post($url, $payloadRequest);
            }

            $dataRequest = Http::withHeaders([
                'Authorization' => $secret_key,
                'for-user-id' => $forUserId
            ])->post($url, $payloadRequest);

            if ($dataRequest->failed()) {

                throw new Exception('Error Request Create Virtual Account');
            }

            $apiResult = $dataRequest->object();

            $xenditCreateVirtualAccount = XenditVirtualAccountRequest::create([
                'xen_virtual_account_id' => $apiResult->id,
                'external_id' => $apiResult->external_id,
                'owner_id' => $apiResult->owner_id,
                'bank_code' => $apiResult->bank_code,
                'merchant_code' => $apiResult->merchant_code,
                'account_number' => $apiResult->account_number,
                'name' => $apiResult->name,
                'expected_amount' => 0,
                'is_single_use' => $apiResult->is_single_use,
                'is_closed' => $apiResult->is_closed,
                'expiration_date' => Carbon::parse($apiResult->expiration_date)->setTimezone(config('app.timezone'))->format('Y-m-d H:i:s'),
                'status' => $apiResult->status,
                'currency' => $apiResult->currency,
                'country' => $apiResult->country,
                'created' => null,
                'updated' => null,
            ]);
            // $payloadBusinessAmount = [
            //     'business_id' => $businessId,
            //     'status_credit' => 1,
            //     'transactional_type' => XenditVirtualAccountRequest::class ?? null,
            //     'transactional_id' => $xenditCreateVirtualAccount->id ?? null,
            //     'reference_id' => $apiResult->external_id,
            //     'amount' => $totalAmount ?? null,
            //     'transaction_amount' =>  $transactionAmount ?? null,
            //     'received_amount' => 0,
            //     'deduction_amount' => 0,
            //     'status' => 'PENDING',
            // ];

            // businessAmount::create($payloadBusinessAmount);

            $xenditCreatePayments = XenditCreatePayment::create([
                'reference_id' => $apiResult->external_id,
                'source_type' => JobApplication::class,
                'source_id' => $sourceId,
                'transactional_type' => XenditVirtualAccountRequest::class,
                'transactional_id' => $xenditCreateVirtualAccount->id,
                'amount' => $totalAmount ?? null,
                'transaction_amount' => $totalAmount ?? null,
                'payment_type' => 'VIRTUAL_ACCOUNT',
                'channel_code' => $channelCode,
                'status' => 'PENDING',
            ]);
            $application = JobApplication::find( $sourceId);
            $application->reference_id = $apiResult->external_id;
            $application->save();

            $result = [
                'id' => $xenditCreatePayments->id,
                'reference_id' => $xenditCreatePayments->reference_id,
                'virtual_account' => $xenditCreateVirtualAccount,
            ];


        return $result;
    }
    public function succesPayment()
    {
        return view('callback.success_payment');
    }
    public function failedPayment()
    {
        return view('callback.failed_payment');
    }
    public function underprocess()
    {
        return view('callback.underprocess');
    }
    public function checkPayment(request $request)
    {
        $createPayment = XenditCreatePayment::find($request->payment_request_id);

        return response()->json($createPayment);

    }

}
