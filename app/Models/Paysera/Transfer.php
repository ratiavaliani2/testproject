<?php

namespace App\Models\Paysera;

use App\Models\ClientFactory;
use App\Models\EntitiesTransferInput;
use App\Models\EntitiesTransferRegistrationParameters;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use PayseraClientTransfersClientClientFactory;
use PayseraClientTransfersClientEntity as Entities;

class Transfer extends Model
{
    use HasFactory;

    protected $table = 'Transfer';
    protected $ClientFactory;
    protected $UTId;

    private function init () {
        $this->ClientFactory = new ClientFactory([
            'base_url' => 'https://wallet.paysera.com/transfer/rest/v1/',
            'mac' => [
                'mac_id' => env('PAYSERA.MAC.ID'),
                'mac_secret' => env('PAYSERA.SECRET'),
            ],
        ]);
    }

    public function create ($amount) {
        $this->init();

        $transfersClient = $this->ClientFactory->getTransfersClient();
        $transferInput = (new EntitiesTransferInput())
            ->setAmount($amount)
            ->setBeneficiary($beneficiary)
            ->setPayer($payer)
            ->setFinalBeneficiary($finalBeneficiary)
            ->setPerformAt($performAt)
            ->setChargeType($chargeType)
            ->setUrgency($urgency)
            ->setNotifications($notifications)
            ->setPurpose($purpose)
            ->setPassword($password)
            ->setCancelable($cancelable)
            ->setAutoCurrencyConvert($autoCurrencyConvert)
            ->setAutoChargeRelatedCard($autoChargeRelatedCard)
            ->setAutoProcessToDone($autoProcessToDone)
            ->setReserveUntil($reserveUntil)

            ->setCallback('https://testproject.com/api/paysera/transfer/callback');
            // adding a callback for Paysera transfer

        $response = $transfersClient->createTransfer($transferInput);

        // We will probably get the response which is described in the documentation
        $response = '{
            "id": "123",
            "status": "new",
            "amount": {
                "amount": "100.00",
                "currency": "EUR"
            }
        }';//...
        $response = json_decode($response);

        $this->UTId = $response->id;

        $transfer = new Transfer();
        $transfer->ut_id = $response->id;
        $transfer->status = $response->status;
        $transfer->amount = $response->amount->amount;
        $transfer->currency = $response->amount->currency;
        $transfer->userId = auth()->id();

        $transfer->json_blob = $response;
        // I will save the incoming json blob just in case I need it...
        $transfer->save();
    }

    public function signTransfer (Request $request) {
        $this->init();

        $transferRegistrationParameters = (new EntitiesTransferRegistrationParameters())
            ->setConvertCurrency($convertCurrency)
            ->setUserIp($request->ip());

        $result = $this->ClientFactory->signTransfer($this->UTId, $transferRegistrationParameters);
        // We will probably get the response which is described in the documentation
        $response = '{
            "id": "123",
            "status": "new",
            "amount": {
                "amount": "100.00",
                "currency": "EUR"
            }
        }';//...
        $response = json_decode($response);

        $this->UTId = $response->id;

        $transfer = Transfer::where('ut_id', $this->UTId);
        $transfer->sign_json_blob = $response;
        // I will save incoming json blob as I don't know what I will need and it will mark the transfer as signed in the database
        $transfer->save();
    }

    public static function callback (Request $request) {
        $validated = $request->validate([
            'trasder_id' => 'required',
            'status' => 'required',
            'date' => 'required',
        ]);

        // The callback will tell us if the transfer was done and successful or not

        $transfer = Transfer::where('ut_id', $request->trasder_id);
        $transfer->status = $request->status;
        $transfer->call_back_reserved_at = $request->date;

        $transfer->save();
    }
}
