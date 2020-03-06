<?php

namespace App\Controller;

use App\Entity\Budget;
use App\Entity\Invoice;
use App\Entity\Client;
use App\Entity\Enterprise;
use App\Entity\Product;
use App\Entity\ProductLine;
use App\Form\InvoiceType;
use App\Repository\EnterpriseRepository;
use App\Repository\InvoiceRepository;
use App\Repository\BudgetRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @Route("/budget")
 */
class BudgetController extends AbstractController
{
    /**
     * @Route("/client/{idclient}", name="budget_index", methods={"GET"})
     */
    public function index(BudgetRepository $budgetRepository, $idclient): Response
    {
        $repositoryEnterprises = $this->getDoctrine()->getRepository(Enterprise::class);
        // Cargo el cliente (por route)
        $repositoryClient = $this->getDoctrine()->getRepository(Client::class);

        $client = $repositoryClient->findOneById($idclient);

        // Cargo la empresa a la que pertecene el cliente
        $enterprise = $client->getEnterprise();

        // $repositoryClient = $this->getDoctrine()->getRepository(Client::class);
        // $client = $repositoryClient->findOneById($idclient);
        return $this->render('budget/index.html.twig', [
            'invoices' => $budgetRepository->findbyClient($idclient),
            'idclient' => $idclient,
            'enterprise' => $enterprise,
            'enterprises' => $repositoryEnterprises->findAll(),
        ]);
    }
}
