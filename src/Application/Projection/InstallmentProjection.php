<?php

namespace App\Application\Projection;

use DateTimeImmutable;

final readonly class InstallmentProjection
{
    public function __construct(
        public int               $installmentNumber,
        public DateTimeImmutable $dueDate,
        public int               $principal,
        public int               $interest,
        public int               $fee,
        public int               $totalPayment,
        public int               $remainingBalanceAfter,
    )
    {
    }
}
