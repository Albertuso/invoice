<?php

namespace App\Controller;

use App\Entity\Budget;
use App\Entity\Invoice;
use App\Entity\Client;
use App\Entity\Enterprise;
use App\Entity\Product;
use App\Entity\ProductLine;
use App\Form\BudgetType;
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
            'budgets' => $budgetRepository->findbyClient($idclient),
            'idclient' => $idclient,
            'enterprise' => $enterprise,
            'enterprises' => $repositoryEnterprises->findAll(),
        ]);
    }
  /**
     * @Route("/new/client/{idclient}", name="budget_new", methods={"GET","POST"})
     */
    public function new(Request $request, $idclient): Response
    {

        // Cargo el cliente (por route)
        $repositoryClient = $this->getDoctrine()->getRepository(Client::class);
        $client = $repositoryClient->findOneById($idclient);

        // Cargo la empresa a la que pertecene el cliente
        $enterprise = $client->getEnterprise();

        //Cargo los productos de esa empresa
        $repositoryProduct = $this->getDoctrine()->getRepository(Product::class);
        $products = $repositoryProduct->findByEnterpriseId($enterprise);

        //Genero una nueva factura
        $budget = new Budget();

        // Pongo los datos por defecto        
        $hoy = new \DateTime();
        // $budget->setInvoicenumber($enterprise->getNextinvoicenumber());
        $budget->setDate($hoy);
        // $invoice->setVisible(true);
        $budget->setFooter($enterprise->getFooter());

        // Creo un array de Productlines
        $this->ProductLine = new ArrayCollection();

        // Se genera el formulario con los datos
        $form = $this->createForm(BudgetType::class, $budget);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $budget->setClient($client);
            $budget->setEnterprise($enterprise);
            // $budget->setVisible(true);
            $names = $_REQUEST['productName'];
            $quantities = $_REQUEST['quantity'];
            $prices = $_REQUEST['price'];
            $vats = $_REQUEST['VAT'];

            //Aqui toca comprobar que el presupuesto es valido

            //Guardo las lineas 
            for ($i = 0; $i < count($names); $i++) {

                $newLine = new ProductLine();

                $newLine->setName($names[$i]);
                $newLine->setQuantity($quantities[$i]);
                $newLine->setPrice($prices[$i]);
                $newLine->setVat($vats[$i]);

                $budget->addLine($newLine);
            }

            $entityManager->persist($budget);
            $entityManager->flush();

            return $this->redirectToRoute('budget_index', ['idclient' => $idclient]);
        }

        return $this->render('budget/new.html.twig', [
            'budget' => $budget,
            'form' => $form->createView(),
            'enterprise' => $enterprise,
            'client' => $client,
            'products' => $products,
        ]);

        
    }

}
