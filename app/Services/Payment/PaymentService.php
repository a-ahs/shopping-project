<?php

    namespace app\Services\Payment;

    use app\Services\Payment\Contracts\requestInterface;
    use app\Services\Payment\Exceptions\classNotFoundException;

    class PaymentServices
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

        private function findProvider()
        {
            $className = 'app\Services\Payment\Providers\\' . $this->providerName;

            if(!class_exists($className))
            {
                throw new classNotFoundException('درگاه پرداخت مورد نظر پیدا نشد');
            }

            return $className($this->request);
        }
    }
?>