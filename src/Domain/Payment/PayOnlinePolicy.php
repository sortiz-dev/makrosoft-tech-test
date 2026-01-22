<?php

namespace App\Domain\Payment;

class PayOnlinePolicy implements PaymentPolicy
{

    public function provider(): PaymentProvider
    {
        return PaymentProvider::PAY_ONLINE;
    }

    public function monthlyInterestRate(): float
    {
        return 0.02;
    }

    public function paymentFeeRate(): float
    {
        return 0.01;
    }
}
