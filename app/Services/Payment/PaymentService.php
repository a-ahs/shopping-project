<?php

    namespace App\Services\Payment;

    use App\Services\Payment\Contracts\requestInterface;
    use App\Services\Payment\Exceptions\classNotFoundException;

    class PaymentService
    {
        public const IDPAY = 'IdPayProvider';
        public const ZARINPAL = 'ZarinpalProvider';

        public function __construct(private string $providerName, private requestInterface $request)
        {

        }

        public function pay()
        {
            return $this->findProvider()->pay();
        }

        public function verify()
        {
            return $this->findProvider()->verify();
        } 

        private function findProvider()
        {
            $className = 'App\Services\Payment\Providers\\' . $this->providerName;

            if(!class_exists($className))
            {
                throw new classNotFoundException('درگاه پرداخت مورد نظر پیدا نشد');
            }

            return new $className($this->request);
        }
    }
?>