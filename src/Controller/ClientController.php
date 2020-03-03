<?php

namespace App\Controller;

use App\Entity\Client;
use App\Entity\Enterprise;
use App\Entity\Supervisor;
use App\Form\ClientType;
use App\Repository\ClientRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;


/**
 * @Route("/client")
 * @IsGranted("ROLE_USER")

 */
class ClientController extends AbstractController
{
    /**
     * @Route("/enterprise/{idempresa}", name="client_index", methods={"GET"})
     */
    public function index(ClientRepository $clientRepository, $idempresa): Response
    {
        $enterpriseRepository = $this->getDoctrine()->getRepository(Enterprise::class);

        return $this->render('client/index.html.twig', [
            'clients' => $clientRepository->findByEnterpriseId($idempresa),
            'id_empresa' => $idempresa,
            'enterprise' => $enterpriseRepository->findOneByid($idempresa),
            'enterprises' => $this->getUser()->getEnterprises(),
        ]);
    }

    /**
     * @Route("/new/enterprise/{idempresa}", name="client_new", methods={"GET","POST"})
     */
    public function new(Request $request, $idempresa): Response
    {
        $client = new Client();
        $form = $this->createForm(ClientType::class, $client);
        $form->handleRequest($request);

        $repositoryEnterprise = $this->getDoctrine()->getRepository(Enterprise::class);
        $enterprise = $repositoryEnterprise->findOneById($idempresa);

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager = $this->getDoctrine()->getManager();
            $client->setVisible(true);
            $entityManager->persist($client);
            $client->setEnterprise($enterprise);
            $entityManager->flush();

            // return $this->redirectToRoute('client_index');  
            return $this->redirectToRoute('client_index', ['idempresa' => $idempresa]);
        }

        return $this->render('client/new.html.twig', [
            'client' => $client,
            'form' => $form->createView(),
            'id_empresa' => $idempresa,
            'supervisors' => $enterprise->getSupervisors(),
            'enterprise' => $enterprise,
            'enterprises' => $this->getUser()->getEnterprises(),
        ]);
    }

    /**
     * @Route("/show/{client}/enterprise/{idempresa}", name="client_show", methods={"GET"})
     */
    public function show(Client $client, $idempresa): Response
    {
        $repositoryEnterprise = $this->getDoctrine()->getRepository(Enterprise::class);
        $enterprise = $repositoryEnterprise->findOneById($idempresa);
        return $this->render('client/show.html.twig', [
            'client' => $client,
            'enterprise' => $enterprise,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="client_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Client $client): Response
    {
        $form = $this->createForm(ClientType::class, $client);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {

            $supervisorid = $_REQUEST['supervisor'][0];
            $supervisor = $this->getDoctrine()->getRepository(Supervisor::class)->findOneById($supervisorid);

            $client->setSupervisor($supervisor);
            $this->getDoctrine()->getManager()->flush();

            // return $this->render('debug.html.twig', [
            //     'debug' => $_REQUEST['supervisor'][0],
            // ]);

            return $this->redirectToRoute('client_index', ['idempresa' => $client->getEnterprise()->getId()]);
        }

        return $this->render('client/edit.html.twig', [
            'client' => $client,
            'form' => $form->createView(),
            'enterprise' => $client->getEnterprise(),
            'supervisors' => $client->getEnterprise()->getSupervisors(),
        ]);
    }

    /**
     * @Route("/{id}", name="client_delete", methods={"DELETE"})
     * @IsGranted("ROLE_USER")
     */
    public function delete(Request $request, Client $client): Response
    {
        $enterprise = $client->getEnterprise();
        if ($this->isCsrfTokenValid('delete' . $client->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();

            $client->setVisible(false);
            // $entityManager->remove($client);
            $entityManager->flush();
        }

        return $this->redirectToRoute('client_index', ['idempresa' => $enterprise->getId()]);
    }
}
