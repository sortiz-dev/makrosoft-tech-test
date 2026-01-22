<?php

namespace App\Application\Contract;

use App\Domain\Payment\PaymentProvider;
use DateTimeImmutable;
use Symfony\Component\Validator\Constraints as Assert;

final class CreateContractRequest
{
    #[Assert\NotBlank]
    #[Assert\Length(max: 50)]
    public string $contractNumber = '';

    #[Assert\NotBlank]
    public DateTimeImmutable $contractDate;

    #[Assert\NotBlank]
    #[Assert\Positive]
    public int $totalAmount = 0;

    #[Assert\NotBlank]
    #[Assert\Positive]
    #[Assert\LessThanOrEqual(360)]
    public int $months = 0;

    #[Assert\NotBlank]
    public PaymentProvider $paymentProvider;

    public function __construct()
    {
        $this->contractDate = new \DateTimeImmutable('today');
        $this->paymentProvider = PaymentProvider::PAYPAL;
    }

}
