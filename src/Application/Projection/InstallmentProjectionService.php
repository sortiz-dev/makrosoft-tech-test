<?php

namespace App\Application\Projection;

use App\Domain\Payment\PaymentPolicyResolver;
use App\Entity\Contract;
use DateInterval;
use DateTimeImmutable;

final readonly class InstallmentProjectionService
{
    public function __construct(
        private PaymentPolicyResolver $paymentPolicyResolver
    )
    {
    }

    public function projectForContract(Contract $contract): array
    {
        $months = $contract->getMonths();
        if ($months <= 0) {
            throw new \InvalidArgumentException('Contract must have at least one month.');
        }

        $policy = $this->paymentPolicyResolver->resolve($contract->getPaymentProvider());

        $total = $contract->getTotalAmount();
        if ($total <= 0) {
            throw new \InvalidArgumentException('Contract must have a total amount greater than zero.');
        }

        //Dividimos en partes iguales
        $principalBase = intdiv($total, $months);
        $remainder = $total - ($principalBase * $months);

        $remaining = $total;
        $result = [];

        for ($i = 1; $i <= $months; $i++) {
            // Distribuir pesos sobrantes en las primeras cuotas
            $principal = $principalBase + ($i <= $remainder ? 1 : 0);

            $interest = $this->percentOf($remaining, $policy->monthlyInterestRate());
            $paymentBeforeFee = $principal + $interest;

            $fee = $this->percentOf($paymentBeforeFee, $policy->paymentFeeRate());
            $totalPayment = $paymentBeforeFee + $fee;

            $remainingAfter = $remaining - $principal;
            $dueDate = $this->addMonths($contract->getContractDate(), $i);

            $result[] = new InstallmentProjection(
                installmentNumber: $i,
                dueDate: $dueDate,
                principal: $principal,
                interest: $interest,
                fee: $fee,
                totalPayment: $totalPayment,
                remainingBalanceAfter: $remainingAfter
            );

            $remaining = $remainingAfter;
        }

        return $result;
    }

    private function addMonths(DateTimeImmutable $date, int $months): \DateTimeImmutable
    {
        return $date->add(new DateInterval('P' . $months . 'M'));
    }

    private function percentOf(int $amount, float $rate): int
    {
        return (int)round($amount * $rate, 0, PHP_ROUND_HALF_UP);
    }
}
