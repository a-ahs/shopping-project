<?php

    namespace app\Services\Payment\Contracts;

    abstract class abstractProviderInterface
    {
        public function __construct(protected requestInterface $request)
        {

        }
    }

?>