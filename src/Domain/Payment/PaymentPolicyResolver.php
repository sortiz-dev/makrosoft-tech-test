<?php

namespace App\Domain\Payment;

use Symfony\Component\DependencyInjection\Attribute\AutowireIterator;

final class PaymentPolicyResolver
{
    private array $policiesByProvider = [];

    public function __construct(#[AutowireIterator('app.payment_policy')] iterable $policies)
    {
        foreach ($policies as $policy) {
            $this->policiesByProvider[$policy->provider()->value] = $policy;
        }
    }

    public function resolve(PaymentProvider $provider): PaymentPolicy
    {
        $key = $provider->value;

        if (!isset($this->policiesByProvider[$key])) {
            throw new \RuntimeException("Unsupported payment provider $key");
        }

        return $this->policiesByProvider[$key];
    }

}
