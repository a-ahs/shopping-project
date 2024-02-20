<?php

namespace App\Http\Controllers;

use App\Events\SendImages;
use App\Http\Requests\Payment\PayRequest;
use App\Mail\SendOrderedImages;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Product;
use App\Models\User;
use App\Services\Payment\PaymentService;
use App\Services\Payment\Requests\IdpayRequest;
use App\Services\Payment\Requests\IdpayVerifyRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class paymentController extends Controller
{
    public function pay(PayRequest $request)
    {
        $validateData = $request->validated();

        $user = User::firstOrCreate([
            'email' => $validateData['email']
        ], [
            'name' => $validateData['name'],
            'mobile' => $validateData['mobile'],
        ]);

        
        try {
            $orderItems = json_decode(Cookie::get('basket'), true);

            if(count($orderItems) <= 0)
            {
                throw new \InvalidArgumentException('سبد خرید نمیتواند خالی باشد');
            }
            $products = Product::findMany(array_keys($orderItems));
           
            $totalPrice = $products->sum('price');
            $refCode = Str::random(30);

            $createdOrder = Order::create([
                'amount' => $totalPrice,
                'ref_code' => $refCode,
                'status' => 'unpaid',
                'user_id' => $user->id
            ]);

            $orderItemsForCreatedOrder = $products->map(function ($product)
            {
                $cuurentProduct = $product->only('id', 'price');
                $cuurentProduct['product_id'] = $cuurentProduct['id'];
                unset($cuurentProduct['id']);

                return $cuurentProduct;
            });

            $createdOrder->orderItems()->createMany($orderItemsForCreatedOrder->toArray());

            $refId = rand(11111,99999);

            $createdPayment = Payment::create([
                'gateway' => 'idpay',
                'ref_code' => $refCode,
                'status' => 'unpaid',
                'order_id' => $createdOrder->id,
            ]);

            $idpayRequest = new IdpayRequest([
                'user' => $user,
                'amount' => $totalPrice,
                'order_id' => $refCode,
                'apiKey' => config('services.gateways.id_pay.api_key')
            ]);
            
            $paymenService = new PaymentService(PaymentService::IDPAY, $idpayRequest);
            
            return $paymenService->pay();

        } catch (\Exception $e) {
            return back()->with('failed', $e->getMessage());
        }

    }

    public function callback(Request $request)
    {
        $paymentInfo = $request->all();
        $verifyRequest = new IdpayVerifyRequest([
            'id' => $paymentInfo['id'],
            'orderId' => $paymentInfo['order_id'],
            'apiKey' => config('services.gateways.id_pay.api_key')
        ]);
        
        $paymenService = new PaymentService(PaymentService::IDPAY, $verifyRequest);
            
        $result = $paymenService->verify();


        if(!$result['status'])
        {
            return redirect()->route('home.checkout.show')->with('failed', 'پرداخت شما انجام نشد');
        }

        if($request['status'] == '101')
        {
            return redirect()->route('home.checkout.show')->with('failed', 'پرداخت شما قبلا انجام شده است');
        }

        $currentPayment = Payment::where('ref_code', $result['data']['order_id'])->first();

        $currentPayment->update([
            'status' => 'paid',
            'res_id' => $result['data']['track_id'],
        ]);

        $currentPayment->order->update([
            'status' => 'paid',
        ]);

        $reservedImages = $currentPayment->order->orderItems->map(function ($orderItem){
            return $orderItem->product->source_url;
        });
        
        $currentUser = $currentPayment->order->user;
        $reservedImages = $reservedImages->toArray();

        event(new SendImages($reservedImages, $currentUser));

        Cookie::queue('basket', null);

        return redirect()->route('home.products.index')->with('success', 'پرداخت شما با موفقیت انجام شد');
    }
}
