<?php

namespace App\Controller;

use App\Entity\Invoice;
use App\Entity\Client;
use App\Form\InvoiceType;
use App\Repository\InvoiceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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
        $repositoryClient = $this->getDoctrine()->getRepository(Client::class);
        $client = $repositoryClient->findOneById($idclient);
        return $this->render('invoice/index.html.twig', [
            'invoices' => $invoiceRepository->findbyClient($idclient),
            'idclient' => $idclient,
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

        //Genero una nueva factura
        $invoice = new Invoice();

        // Pongo los datos por defecto        
        $hoy = new \DateTime();
        $invoice->setInvoicenumber($enterprise->getId() . $hoy->format('YmdHis'));
        $invoice->setDate($hoy);
        $invoice->setFooter($enterprise->getFooter());

        // Se genera el formulario con los datos
        $form = $this->createForm(InvoiceType::class, $invoice);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $invoice->setClient($client);


            //Aqui toca comprobar que la nueva factura es válida


            
            $entityManager->persist($invoice);
            $entityManager->flush();

            return $this->redirectToRoute('invoice_index', ['idclient' => $idclient]);
        }

        return $this->render('invoice/new.html.twig', [
            'invoice' => $invoice,
            'invoicenumber' => "1",
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="invoice_show", methods={"GET"})
     */
    public function show(Invoice $invoice): Response
    {
        return $this->render('invoice/show.html.twig', [
            'invoice' => $invoice,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="invoice_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Invoice $invoice): Response
    {
        $form = $this->createForm(InvoiceType::class, $invoice);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('invoice_index');
        }

        return $this->render('invoice/edit.html.twig', [
            'invoice' => $invoice,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="invoice_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Invoice $invoice): Response
    {
        if ($this->isCsrfTokenValid('delete'.$invoice->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($invoice);
            $entityManager->flush();
        }

        return $this->redirectToRoute('invoice_index');
    }
}
