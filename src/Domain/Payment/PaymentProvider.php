<?php

namespace App\Domain\Payment;

enum PaymentProvider: string
{
    case PAYPAL = 'PAYPAL';
    case PAY_ONLINE = 'PAY_ONLINE';
}
