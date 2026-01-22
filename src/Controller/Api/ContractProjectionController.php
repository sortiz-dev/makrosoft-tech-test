<?php

namespace App\Controller\Api;

use App\Application\Projection\InstallmentProjectionService;
use App\Entity\Contract;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class ContractProjectionController extends AbstractController
{

    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly InstallmentProjectionService $installmentProjectionService
    )
    {}

    #[Route('/api/contracts/{id}/installments', name: 'contract_installments', methods: ['GET'])]
    public function installments(int $id): JsonResponse
    {
        $contract = $this->entityManager->find(Contract::class, $id);

        if ($contract === null) {
            return $this->json([
                'message' => 'Contract not found'
            ], 404);
        }

        $items = $this->installmentProjectionService->projectForContract(contract: $contract);

        $payload = array_map(static fn($x) => [
            'installmentNumber' => $x->installmentNumber,
            'dueDate' => $x->dueDate->format('Y-m-d'),
            'principal' => $x->principal,
            'interest' => $x->interest,
            'fee' => $x->fee,
            'totalPayment' => $x->totalPayment,
            'remainingBalanceAfter' => $x->remainingBalanceAfter,
        ], $items);

        return $this->json([
            'contractId' => $contract->getId(),
            'installments' => $payload,
        ]);
    }

}
