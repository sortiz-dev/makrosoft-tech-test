<?php

namespace App\Domain\Payment;

interface PaymentPolicy
{

    public function provider(): PaymentProvider;

    public function monthlyInterestRate(): float;

    public function paymentFeeRate(): float;

}
