<?php

namespace App\Http\Controllers;

use App\Models\User;
use app\Services\Payment\PaymentServices;
use app\Services\Payment\Requests\IdpayRequest;
use Illuminate\Http\Request;

class paymentController extends Controller
{
    public function pay()
    {
        $user = User::first();
        $idpayRequest = new IdpayRequest([
            'amount' => 1000,
            'user' => $user
        ]);

        $paymenService = new PaymentServices(PaymentServices::IDPAY, $idpayRequest);
        dd($paymenService->pay());
    }
}
