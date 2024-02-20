<?php

    namespace App\Services\Payment\Requests;

    use App\Services\Payment\Contracts\requestInterface;

    class IdpayRequest implements requestInterface
    {
        private $user;
        private $amount;
        private $orderId;
        private $apiKey;
        
        public function __construct(array $data)
        {
            $this->user = $data['user'];
            $this->amount = $data['amount'];
            $this->orderId = $data['order_id'];
            $this->apiKey = $data['apiKey'];

        }

        public function getAmount()
        {
            return $this->amount * 10;
        }

        public function getUser()
        {
            return $this->user;
        }

        public function getOrderId()
        {
            return $this->orderId;
        }
        
        public function getAPIKey()
        {
            return $this->apiKey;
        }
    }

?>