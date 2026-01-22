<?php

namespace App\Domain\Payment;

class PayPalPolicy implements PaymentPolicy
{

    public function provider(): PaymentProvider
    {
        return PaymentProvider::PAYPAL;
    }

    public function monthlyInterestRate(): float
    {
        return 0.01;
    }

    public function paymentFeeRate(): float
    {
        return 0.02;
    }
}
