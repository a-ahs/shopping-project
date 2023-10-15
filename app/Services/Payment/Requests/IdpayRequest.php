<?php

    namespace app\Services\Payment\Requests;

    use app\Services\Payment\Contracts\requestInterface;

    class IdpayRequest implements requestInterface
    {
        private $user;
        private $amount;

        public function __construct(array $data)
        {
            $this->user = $data['user'];
            $this->amount = $data['amount'];
        }

        public function getAmount()
        {
            return $this->amount;
        }

        public function getUser()
        {
            return $this->user;
        }
    }

?>