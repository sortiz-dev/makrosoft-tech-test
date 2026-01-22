<?php

namespace App\Application\Contract;

use App\Entity\Contract;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectRepository;

final class CreateContractHandler
{

    private ObjectRepository $repo;

    public function __construct(private readonly EntityManagerInterface $entityManager)
    {
        $this->repo = $entityManager->getRepository(Contract::class);
    }

    public function handle(CreateContractRequest $request): Contract
    {
        $existing = $this->repo->findOneBy(['contractNumber' => $request->contractNumber]);
        if ($existing !== null) {
            throw new \RuntimeException('Contract already exists');
        }

        $contract = new Contract(
            contractNumber: $request->contractNumber,
            contractDate: $request->contractDate,
            totalAmount: $request->totalAmount,
            months: $request->months,
            paymentProvider: $request->paymentProvider
        );

        $this->entityManager->persist($contract);
        $this->entityManager->flush();

        return $contract;
    }
}
