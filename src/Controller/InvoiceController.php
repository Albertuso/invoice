<?php

namespace App\Controller;

use App\Entity\Invoice;
use App\Entity\Client;
use App\Entity\Enterprise;
use App\Entity\Product;
use App\Entity\ProductLine;
use App\Form\InvoiceType;
use App\Repository\EnterpriseRepository;
use App\Repository\InvoiceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @Route("/invoice")
 */
class InvoiceController extends AbstractController
{
    /**
     * @Route("/client/{idclient}", name="invoice_index", methods={"GET"})
     */
    public function index(InvoiceRepository $invoiceRepository, $idclient): Response
    {
        $repositoryEnterprises = $this->getDoctrine()->getRepository(Enterprise::class);
        // Cargo el cliente (por route)
        $repositoryClient = $this->getDoctrine()->getRepository(Client::class);

        $client = $repositoryClient->findOneById($idclient);

        // Cargo la empresa a la que pertecene el cliente
        $enterprise = $client->getEnterprise();

        // $repositoryClient = $this->getDoctrine()->getRepository(Client::class);
        // $client = $repositoryClient->findOneById($idclient);
        return $this->render('invoice/index.html.twig', [
            'invoices' => $invoiceRepository->findbyClient($idclient),
            'idclient' => $idclient,
            'enterprise' => $enterprise,
            'enterprises' => $repositoryEnterprises->findAll(),
        ]);
    }

    /**
     * @Route("/new/client/{idclient}", name="invoice_new", methods={"GET","POST"})
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
        $invoice = new Invoice();

        // Pongo los datos por defecto        
        $hoy = new \DateTime();
        $invoice->setInvoicenumber($enterprise->getNextinvoicenumber());
        $invoice->setDate($hoy);
        $invoice->setVisible(true);
        $invoice->setFooter($enterprise->getFooter());

        // Creo un array de Productlines
        $this->ProductLine = new ArrayCollection();

        // Se genera el formulario con los datos
        $form = $this->createForm(InvoiceType::class, $invoice);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $invoice->setClient($client);
            $invoice->setEnterprise($enterprise);
            $invoice->setVisible(true);
            $names = $_REQUEST['productName'];
            $quantities = $_REQUEST['quantity'];
            $prices = $_REQUEST['price'];
            $vats = $_REQUEST['VAT'];

            //Aqui toca comprobar que la nueva factura es válida

            //Guardo las lineas 
            for ($i = 0; $i < count($names); $i++) {

                $newLine = new ProductLine();

                $newLine->setName($names[$i]);
                $newLine->setQuantity($quantities[$i]);
                $newLine->setPrice($prices[$i]);
                $newLine->setVat($vats[$i]);
                $invoice->addLine($newLine);
            }

            //Subo id de invoicenext
            $enterprise->setNextinvoicenumber($enterprise->getNextinvoicenumber() + 1);

            $entityManager->persist($invoice);
            $entityManager->flush();

            return $this->redirectToRoute('invoice_index', ['idclient' => $idclient]);
        }

        return $this->render('invoice/new.html.twig', [
            'invoice' => $invoice,
            'invoicenumber' => $enterprise->getNextinvoicenumber(),
            'form' => $form->createView(),
            'enterprise' => $enterprise,
            'client' => $client,
            'products' => $products,
        ]);
    }

    /**
     * @Route("/show/{id}", name="invoice_show", methods={"GET"})
     */
    public function show(Invoice $invoice): Response
    {
        return $this->render('invoice/show.html.twig', [
            'invoice' => $invoice,
            'enterprise' => $invoice->getEnterprise(),
        ]);
    }

    /**
     * @Route("/{id}/edit", name="invoice_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Invoice $invoice): Response
    {
        $repositoryEnterprises = $this->getDoctrine()->getRepository(Enterprise::class);
        // Cargo el cliente (por invoice)
        $client = $invoice->getClient();
        // Cargo la empresa a la que pertecene el cliente
        $enterprise = $client->getEnterprise();

        //Cargo los productos de esa empresa
        $repositoryProduct = $this->getDoctrine()->getRepository(Product::class);
        $products = $repositoryProduct->findByEnterpriseId($enterprise);

        $form = $this->createForm(InvoiceType::class, $invoice);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            //Cargo las lineas de productos de esa empresa
            $repositoryProductLine = $this->getDoctrine()->getRepository(ProductLine::class);
            $productLines = $repositoryProductLine->findByInvoice($invoice);

            // Eliminar todas las lineas
            for ($i = 0; $i < count($invoice->getLine()); $i++) {
                // $invoice->removeLine($invoice->getLine($i)[0]);
                $this->getDoctrine()->getManager()->remove($productLines[$i]);
            }

            $this->getDoctrine()->getManager()->flush();

            $names = $_REQUEST['productName'];
            $quantities = $_REQUEST['quantity'];
            $prices = $_REQUEST['price'];
            $vats = $_REQUEST['VAT'];

            //Aqui toca comprobar que la nueva factura es válida

            for ($i = 0; $i < count($names); $i++) {

                $newLine = new ProductLine();

                $newLine->setName($names[$i]);
                $newLine->setQuantity($quantities[$i]);
                $newLine->setPrice($prices[$i]);
                $newLine->setVat($vats[$i]);

                $invoice->addLine($newLine);
            }

            //Guardo las lineas 
            for ($i = 0; $i < count($names); $i++) {

                $newLine = new ProductLine();

                $newLine->setName($names[$i]);
                $newLine->setQuantity($quantities[$i]);
                $newLine->setPrice($prices[$i]);
                $newLine->setVat($vats[$i]);

                $invoice->addLine($newLine);
            }

            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('invoice_index', ['idclient' => $client->getId()]);
        }

        return $this->render('invoice/edit.html.twig', [
            'invoice' => $invoice,
            'form' => $form->createView(),
            'enterprise' => $invoice->getEnterprise(),
            'enterprises' => $repositoryEnterprises->findAll(),
            'client' => $invoice->getClient(),
            'lines' => $invoice->getLine(),
            'products' => $products,
        ]);
    }

    /**
     * @Route("/{id}", name="invoice_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Invoice $invoice): Response
    {
        $client = $invoice->getClient();
        if ($this->isCsrfTokenValid('delete' . $invoice->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $invoice->setVisible(false);
            // $entityManager->remove($invoice);
            $entityManager->flush();
        }

        return $this->redirectToRoute('invoice_index', ['idclient' => $client->getId()]);
    }
    /**
     * @Route("/search/enterprise/{identerprise}/{txtbusca}", defaults={"txtbusca"=""}, name="invoice_search", methods={"GET","POST"})
     */
    public function search($identerprise, $txtbusca): Response
    {
        $array = [];
        if ($txtbusca != "") {
            $repositoryProduct = $this->getDoctrine()->getRepository(Product::class);
            $products = $repositoryProduct->findByNameAjax($txtbusca, $identerprise);
            foreach ($products as $product) {
                $line =
                    [
                        'id' => $product->getId(),
                        'name' => $product->getName(),
                        'price' => $product->getPrice(),
                        'vat' => $product->getVat(),
                        'description' => $product->getDescription(),
                        'enterprise' => $product->getEnterprise()->getName()
                    ];

                array_push($array, $line);
            }

            $enviar = json_encode($array);

            return new Response($enviar);
        } else return new Response(null);
    }

    /**
     * @Route("/{id}/print", name="invoice_print", methods={"GET","POST"})
     */
    public function print(Request $request, Invoice $invoice): Response
    {

        // Cargo el cliente (por invoice)
        $client = $invoice->getClient();
        // Cargo la empresa a la que pertecene el cliente
        $enterprise = $client->getEnterprise();

        //Cargo los productos de esa empresa
        $repositoryProduct = $this->getDoctrine()->getRepository(Product::class);
        $products = $repositoryProduct->findByEnterpriseId($enterprise);

        $form = $this->createForm(InvoiceType::class, $invoice);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            //Cargo las lineas de productos de esa empresa
            $repositoryProductLine = $this->getDoctrine()->getRepository(ProductLine::class);
            $productLines = $repositoryProductLine->findByInvoice($invoice);

            // Eliminar todas las lineas
            for ($i = 0; $i < count($invoice->getLine()); $i++) {
                // $invoice->removeLine($invoice->getLine($i)[0]);
                $this->getDoctrine()->getManager()->remove($productLines[$i]);
            }

            $this->getDoctrine()->getManager()->flush();

            $names = $_REQUEST['productName'];
            $quantities = $_REQUEST['quantity'];
            $prices = $_REQUEST['price'];
            $vats = $_REQUEST['VAT'];

            //Aqui toca comprobar que la nueva factura es válida
            for ($i = 0; $i < count($names); $i++) {

                $newLine = new ProductLine();

                $newLine->setName($names[$i]);
                $newLine->setQuantity($quantities[$i]);
                $newLine->setPrice($prices[$i]);
                $newLine->setVat($vats[$i]);

                $invoice->addLine($newLine);
            }


            //Guardo las lineas 
            for ($i = 0; $i < count($names); $i++) {

                $newLine = new ProductLine();

                $newLine->setName($names[$i]);
                $newLine->setQuantity($quantities[$i]);
                $newLine->setPrice($prices[$i]);
                $newLine->setVat($vats[$i]);

                $invoice->addLine($newLine);
            }

            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('invoice_index', ['idclient' => $client->getId()]);
        }

        return $this->render('invoice/toPrint.html.twig', [
            'invoice' => $invoice,
            'form' => $form->createView(),
            'enterprise' => $invoice->getEnterprise(),
            'client' => $invoice->getClient(),
            'lines' => $invoice->getLine(),
            'products' => $products,
        ]);
    }

    /**
     * @Route("/{id}/duplicate", name="invoice_duplicate", methods={"GET","POST"})
     */
    public function duplicate(Request $request, Invoice $invoice): Response
    {

        // Cargo el cliente (por invoice)
        $client = $invoice->getClient();
        // Cargo la empresa a la que pertecene el cliente
        $enterprise = $client->getEnterprise();

        $entityManager = $this->getDoctrine()->getManager();

        $newinvoice = new Invoice();
        $hoy = new \DateTime();
        $newid = $enterprise->getNextinvoicenumber();
        $newinvoice->setInvoicenumber($newid);
        $newinvoice->setDate($hoy);
        $newinvoice->setVisible(true);
        $newinvoice->setFooter($enterprise->getFooter());

        $lines = $invoice->getLine();
        foreach ($lines as $line) {
            $newinvoice->addLine($line);
        }
        $newinvoice->setDescription($invoice->getDescription());
        $newinvoice->setSubtotal($invoice->getSubTotal());
        $newinvoice->setTotal($invoice->getTotal());
        $newinvoice->setClient($invoice->getClient());
        $newinvoice->setEnterprise($invoice->getEnterprise());
    
        //Subo id de invoicenext
        $enterprise->setNextinvoicenumber($enterprise->getNextinvoicenumber() + 1);

        $entityManager->persist($invoice);
        $entityManager->flush();

        return $this->redirectToRoute('invoice_index', ['idclient' => $client->getId()]);
    }
}
