<?php

    namespace app\Services\Payment\Providers;

    use app\Services\Payment\Contracts\abstractProviderInterface;
    use app\Services\Payment\Contracts\payableInterface;
    use app\Services\Payment\Contracts\verifyableInterface;

    class ZarinpalProvider extends abstractProviderInterface implements payableInterface, verifyableInterface
    {
        public function pay()
        {

        }

        public function verify()
        {
            
        }
    }

?>