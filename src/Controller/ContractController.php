<?php

namespace App\Controller;

use App\Application\Contract\CreateContractHandler;
use App\Application\Contract\CreateContractRequest;
use App\Form\ContractType;
use App\Repository\ContractRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ContractController extends AbstractController
{

    public function __construct(private readonly CreateContractHandler $handler)
    {
    }

    #[Route('/', name: 'contract_index', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('contract/index.html.twig', [
            'controller_name' => 'ContractController',
        ]);
    }

    #[Route('/contracts/new', name: 'contract_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $dto = new CreateContractRequest();
        $form = $this->createForm(ContractType::class, $dto);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $contract = $this->handler->handle($dto);
                $this->addFlash('success', 'Contract created successfully');

                return $this->redirectToRoute('contract_show', ['id' => $contract->getId()]);
            } catch (\RuntimeException $e) {
                $form->addError(new FormError($e->getMessage()));
            }
        }

        return $this->render('contract/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/contracts/{id}', name: 'contract_show', methods: ['GET'])]
    public function show(int $id, ContractRepository $repository): Response
    {
        $contract = $repository->find($id);
        if ($contract === null) {
            throw $this->createNotFoundException('Contract not found');
        }

        return $this->render('contract/show.html.twig', [
            'contract' => $contract,
        ]);
    }
}
