<?php

namespace App\Entity;

use App\Domain\Payment\PaymentProvider;
use App\Repository\ContractRepository;
use DateTimeImmutable;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ContractRepository::class)]
class Contract
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $contractNumber = null;

    #[ORM\Column(type: Types::DATE_IMMUTABLE)]
    private ?DateTimeImmutable $contractDate = null;

    #[ORM\Column(type: Types::BIGINT)]
    private ?string $totalAmount = null;

    #[ORM\Column]
    private ?int $months = null;

    #[ORM\Column(length: 20)]
    private ?string $paymentProvider = null;

    #[ORM\Column(type: Types::DATE_IMMUTABLE)]
    private ?DateTimeImmutable $createdAt = null;

    #[ORM\Column(type: Types::DATE_IMMUTABLE)]
    private ?DateTimeImmutable $updatedAt = null;

    public function __construct(
        string            $contractNumber,
        DateTimeImmutable $contractDate,
        string            $totalAmount,
        int               $months,
        PaymentProvider   $paymentProvider
    )
    {
        $this->contractNumber = $contractNumber;
        $this->contractDate = $contractDate;
        $this->totalAmount = $totalAmount;
        $this->months = $months;
        $this->paymentProvider = $paymentProvider->value;
        $this->createdAt = new DateTimeImmutable();
        $this->updatedAt = new DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContractNumber(): ?string
    {
        return $this->contractNumber;
    }

    public function setContractNumber(string $contractNumber): static
    {
        $this->contractNumber = $contractNumber;

        return $this;
    }

    public function getContractDate(): ?DateTimeImmutable
    {
        return $this->contractDate;
    }

    public function setContractDate(DateTimeImmutable $contractDate): static
    {
        $this->contractDate = $contractDate;

        return $this;
    }

    public function getTotalAmount(): ?string
    {
        return $this->totalAmount;
    }

    public function setTotalAmount(string $totalAmount): static
    {
        $this->totalAmount = $totalAmount;

        return $this;
    }

    public function getMonths(): ?int
    {
        return $this->months;
    }

    public function setMonths(int $months): static
    {
        $this->months = $months;

        return $this;
    }

    public function getPaymentProvider(): PaymentProvider
    {
        return PaymentProvider::from($this->paymentProvider);
    }

    public function getPaymentProviderLabel(): string
    {
        return match ($this->getPaymentProvider()) {
            PaymentProvider::PAYPAL => 'PayPal',
            PaymentProvider::PAY_ONLINE => 'Pay Online',
        };
    }

    public function setPaymentProvider(string $paymentProvider): static
    {
        $this->paymentProvider = $paymentProvider;

        return $this;
    }

    public function getCreatedAt(): ?DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?DateTimeImmutable $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function touch(): void
    {
        $this->updatedAt = new DateTimeImmutable();
    }
}
