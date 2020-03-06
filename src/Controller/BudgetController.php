<?php

namespace App\Controller;

use App\Entity\Budget;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Client;
use App\Entity\Enterprise;
use App\Entity\Product;
use App\Entity\BudgetLine;
use App\Form\BudgetType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\Common\Collections\ArrayCollection;

class BudgetController extends AbstractController
{
    /**
     * @Route("{identerprise}/budget/{idclient}", name="budget_new")
     */
    public function new(Request $request, $identerprise, $idclient): Response
    {

$mensaje = "Esto es una prueba 1\r\nA ver si te llega correctamente 2\r\nUn saludo 3\r\n\n\n\nwww.ejemplocodigo.com";

// Si cualquier línea es más larga de 70 caracteres, se debería usar wordwrap()
$mensaje = wordwrap($mensaje, 70, "\r\n");

// Enviamos el email
mail('ruben.segura@outlook.es', 'Probando la funcion MAIL desde PHP', $mensaje);


echo "EMAIL ENVIADO...";
        // Cargo el cliente (por route)
        $repositoryClient = $this->getDoctrine()->getRepository(Client::class);
        $client = $repositoryClient->findOneById($idclient);

        // Cargo la empresa a la que pertecene el cliente
        $repositoryEnterprise = $this->getDoctrine()->getRepository(Enterprise::class);
        $enterprise = $repositoryEnterprise->findOneById($identerprise);

        //Cargo los productos de esa empresa
        $repositoryProduct = $this->getDoctrine()->getRepository(Product::class);
        $products = $repositoryProduct->findByEnterpriseId($enterprise);

        //Genero un presupuesto
        $budget = new Budget();

        // Pongo los datos por defecto        
        $hoy = new \DateTime();
        $budget->setBudgetnumber(0);
        $budget->setDate($hoy);
        $budget->setVisible(true);
        $budget->setFooter($enterprise->getFooter());
        $budget->setSold(false);

        // Creo un array de Productlines
        $this->BudgetLine = new ArrayCollection();

        // Se genera el formulario con los datos
        $form = $this->createForm(BudgetType::class, $budget);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager = $this->getDoctrine()->getManager();
            $budget->setClient($client);
            $budget->setEnterprise($enterprise);
            $names = $_REQUEST['productName'];
            $quantities = $_REQUEST['quantity'];
            $prices = $_REQUEST['price'];
            $vats = $_REQUEST['VAT'];

            //Aqui toca comprobar que la nueva factura es válida

            //Guardo las lineas 
            for ($i = 0; $i < count($names); $i++) {
                $newLine = new BudgetLine();
                $newLine->setName($names[$i]);
                $newLine->setQuantity($quantities[$i]);
                $newLine->setPrice($prices[$i]);
                $newLine->setVat($vats[$i]);

                $budget->addLine($newLine);
            }
            $entityManager->persist($budget);
            $entityManager->flush();
            return $this->redirectToRoute('invoice_index', ['idclient' => $idclient]);
        }

        return $this->render('budget/index.html.twig', [
            'budget' => $budget,
            'form' => $form->createView(),
            'enterprise' => $enterprise,
            'client' => $client,
            'products' => $products,
        ]);
    }
}
