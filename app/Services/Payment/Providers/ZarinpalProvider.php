<?php

    namespace App\Services\Payment\Providers;

    use App\Services\Payment\Contracts\abstractProviderInterface;
    use App\Services\Payment\Contracts\payableInterface;
    use App\Services\Payment\Contracts\verifyableInterface;

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