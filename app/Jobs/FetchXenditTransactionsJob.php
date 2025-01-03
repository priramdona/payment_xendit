<?php

namespace App\Jobs;

use App\Models\JobApplication;
use App\Models\XenditCreatePayment;
use App\Models\XenditDisbursement;
use App\Models\XenditTransaction;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
class FetchXenditTransactionsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $baseUrl = 'https://api.xendit.co';

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        $allTransactions = [];
    $hasMore = true;
    $afterId = null;
    $retryAttempts = 0;
    $maxRetries = 5;

    $apiKey = config('services.xendit.key');
    while ($hasMore) {
        $url = "{$this->baseUrl}/transactions?limit=50";

        if ($afterId) {
            $url .= "&after_id={$afterId}";
        }

        $response = Http::withBasicAuth($apiKey, '')
        ->timeout(30)
        ->retry(3, 5000) // Retry up to 3 times with a 5-second delay between each attempt
        ->get($url);

        if ($response->successful()) {
            $data = $response->json();
            $allTransactions = array_merge($allTransactions, $data['data']);
            $hasMore = $data['has_more'] ?? false;

            if ($hasMore) {
                $afterId = end($data['data'])['id'];
            }

            // Reset retry count on success
            $retryAttempts = 0;
        } elseif ($response->status() === 429) { // Rate limit exceeded
            if ($retryAttempts < $maxRetries) {
                $waitTime = pow(2, $retryAttempts); // Exponential backoff
                Log::warning("Rate limit exceeded, retrying in {$waitTime} seconds...");

                // Wait for the specified time before retrying
                sleep($waitTime);

                $retryAttempts++;
                continue; // Skip the rest of this loop iteration and retry
            } else {
                Log::error("Max retries reached. Failed to fetch Xendit transactions due to rate limit.");
                return;
            }
        } else {
            Log::error('Failed to fetch Xendit transactions', [
                'status' => $response->status(),
                'error' => $response->body(),
            ]);
            return;
        }
    }
            //command bash di server
            //* * * * * php /path-to-your-project/artisan schedule:run >> /dev/null 2>&1
            //php artisan queue:work

    $sourceType = null;

    foreach ($allTransactions as $transaction) {
        $paymentStatus = 'Paid';
        $xenditCreatePayments = XenditCreatePayment::where('reference_id', $transaction['reference_id'])->update([
            'status' => 'Paid',
            'settlement_status' => $transaction['settlement_status'],
            'estimated_settlement_time' => Carbon::parse($transaction['estimated_settlement_time'])->setTimezone(config('app.timezone')),
            'xendit_fee' => $transaction['fee']['xendit_fee'],
            'value_added_tax' => $transaction['fee']['value_added_tax'],
            'xendit_withholding_tax' => $transaction['fee']['xendit_withholding_tax'],
            'third_party_withholding_tax' => $transaction['fee']['third_party_withholding_tax'],
            'fee_status' => $transaction['fee']['status'],
            'net_amount' => $transaction['net_amount'],
        ]);

        if ( $transaction['settlement_status'] == 'SETTLED'){
            $paymentStatus = "Settled";
        }else{
            $paymentStatus = $transaction['settlement_status'];
        }

        if ($transaction['type'] === 'DISBURSEMENT'){
            JobApplication::where('reference_id', $transaction['reference_id'])->update([
                'status' => $transaction['settlement_status'],
             ]);

        }else{
            JobApplication::where('reference_id', $transaction['reference_id'])->update([
                'status' => $paymentStatus,
                'received_amount' => $transaction['net_amount'],
                'deduction_amount' => $transaction['fee']['xendit_fee'] + $transaction['fee']['value_added_tax'] + $transaction['fee']['xendit_withholding_tax'] + $transaction['fee']['third_party_withholding_tax'] ,
            ]);

        }

        XenditDisbursement::where('reference_id', $transaction['reference_id'])->update([
            'status' => $paymentStatus,
        ]);

        XenditTransaction::updateOrCreate(
            ['transaction_id' => $transaction['id']],
            [
                'product_id' => $transaction['product_id'],
                'type' => $transaction['type'],
                'status' => $transaction['status'],
                'channel_category' => $transaction['channel_category'],
                'channel_code' => $transaction['channel_code'],
                'reference_id' => $transaction['reference_id'],
                'account_identifier' => $transaction['account_identifier'],
                'currency' => $transaction['currency'],
                'amount' => $transaction['amount'],
                'net_amount' => $transaction['net_amount'],
                'cashflow' => $transaction['cashflow'],
                'settlement_status' => $transaction['settlement_status'],
                'estimated_settlement_time' => Carbon::parse($transaction['estimated_settlement_time'])->setTimezone(config('app.timezone')),
                'business_id' => $transaction['business_id'],
                'created' => Carbon::parse($transaction['created'])->setTimezone(config('app.timezone')),
                'updated' => Carbon::parse($transaction['updated'])->setTimezone(config('app.timezone')),
                'xendit_fee' => $transaction['fee']['xendit_fee'],
                'value_added_tax' => $transaction['fee']['value_added_tax'],
                'xendit_withholding_tax' => $transaction['fee']['xendit_withholding_tax'],
                'third_party_withholding_tax' => $transaction['fee']['third_party_withholding_tax'],
                'fee_status' => $transaction['fee']['status'],
                'fee' => json_encode($transaction['fee']),
            ]
        );
    }

        // Simpan transaksi (ini bisa disimpan ke database atau diproses lebih lanjut)
        Log::info('Fetched Xendit transactions', ['transactions' => $allTransactions]);
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        //
    }
}
