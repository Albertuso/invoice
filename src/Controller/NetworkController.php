<?php

namespace App\Controller;

use App\Entity\Network;
use App\Form\NetworkType;
use App\Entity\Enterprise;
use App\Repository\NetworkRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("/network")
 * @IsGranted("ROLE_USER")
 */
class NetworkController extends AbstractController
{
    /**
     * @Route("/{identerprise}", name="network_index", methods={"GET"})
     */
    public function index(NetworkRepository $networkRepository, $identerprise): Response
    {
        $repositoryEnterprise = $this->getDoctrine()->getRepository(Enterprise::class);
        $enterprise = $repositoryEnterprise->findOneById($identerprise);

        // return $this->render('debug.html.twig', [
        //     'debug' => $enterprise,
        // ]);

        return $this->render('network/index.html.twig', [
            'networks' => $networkRepository->findAll(),
            'idinterprise' => $identerprise,
            'enterprise' => $enterprise,
            'enterprises' => $this->getUser()->getEnterprises(),
        ]);
    }

    /**
     * @Route("/new/{identerprise}", name="network_new", methods={"GET","POST"})
     */
    public function new(Request $request, $identerprise): Response
    {
        $network = new Network();
        $form = $this->createForm(NetworkType::class, $network);
        $form->handleRequest($request);
        $repositoryEnterprise = $this->getDoctrine()->getRepository(Enterprise::class);
        $enterprise = $repositoryEnterprise->findOneById($identerprise);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $network->setVisible(true);
            $entityManager->persist($network);
            $entityManager->flush();

            return $this->redirectToRoute('network_index', ['identerprise' => $identerprise]);
        }

        return $this->render('network/new.html.twig', [
            'network' => $network,
            'form' => $form->createView(),
            'identerprise' => $identerprise,
            'enterprise' => $enterprise,
            'enterprises' => $this->getUser()->getEnterprises(),
        ]);
    }

    /**
     * @Route("/{id}", name="network_show", methods={"GET"})
     */
    public function show(Network $network): Response
    {
        return $this->render('network/show.html.twig', [
            'network' => $network,
        ]);
    }

    /**
     * @Route("/{id}/edit/{identerprise}", name="network_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Network $network,$identerprise): Response
    {
        $repositoryEnterprise = $this->getDoctrine()->getRepository(Enterprise::class);
        $enterprise = $repositoryEnterprise->findOneById($identerprise);

        $form = $this->createForm(NetworkType::class, $network);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('network_index', ['identerprise' => $identerprise]);
        }

        return $this->render('network/edit.html.twig', [
            'network' => $network,
            'form' => $form->createView(),
            'enterprise' => $enterprise,
            'idinterprise' => $identerprise,
        ]);
    }

    /**
     * @Route("/{id}", name="network_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Network $network): Response
    {
        if ($this->isCsrfTokenValid('delete' . $network->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $network->setVisible(false);
            // $entityManager->remove($network);
            $entityManager->flush();
        }

        return $this->redirectToRoute('network_index');
    }
}
