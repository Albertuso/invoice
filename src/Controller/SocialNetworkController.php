<?php

namespace App\Controller;

use App\Entity\SocialNetwork;
use App\Entity\Enterprise;
use App\Form\SocialNetworkType;
use App\Repository\SocialNetworkRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("/social/network")
 * @IsGranted("ROLE_USER")
 */
class SocialNetworkController extends AbstractController
{
    /**
     * @Route("/{idsocialnetwork}", name="social_network_index", methods={"GET"})
     */
    public function index(SocialNetworkRepository $socialNetworkRepository, $idsocialnetwork): Response
    {
        $socialnetwork = $socialNetworkRepository->findOneById($idsocialnetwork);
        // return $this->render('debug.html.twig', [
        //             'debug' => $socialNetworkRepository->findOneById($idsocialnetwork)->getEnterprise()->getId(),
        //         ]);
        // return $this->render('social_network/index.html.twig', [
        //     'social_networks' => $socialNetworkRepository->findAll(),
        //     'enterprise' => $socialNetworkRepository->findOneById($idsocialnetwork)->getEnterprise(),
        //     'enterprises' => $this->getUser()->getEnterprises(),

        // ]);

        return $this->redirectToRoute('enterprise_show', ['id' => $socialnetwork->getEnterprise()->getId()]);
    }

    /**
     * @Route("/new/{identerprise}", name="social_network_new", methods={"GET","POST"})
     */
    public function new(Request $request, $identerprise): Response
    {

        $enterpriseRepository = $this->getDoctrine()->getRepository(Enterprise::class);
        $socialNetwork = new SocialNetwork();
        $form = $this->createForm(SocialNetworkType::class, $socialNetwork);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $socialNetwork->setVisible(true);
            $socialNetwork->setEnterprise($enterpriseRepository->findOneByid($identerprise));
            $entityManager->persist($socialNetwork);
            $entityManager->flush();

            return $this->redirectToRoute('enterprise_show', ['id' => $identerprise]);
        }

        return $this->render('social_network/new.html.twig', [
            'social_network' => $socialNetwork,
            'form' => $form->createView(),
            'id_empresa' => $identerprise,
            'enterprise' => $enterpriseRepository->findOneByid($identerprise),
            'enterprises' => $this->getUser()->getEnterprises(),
        ]);
    }

    /**
     * @Route("/{id}", name="social_network_show", methods={"GET"})
     */
    public function show(SocialNetwork $socialNetwork): Response
    {
        return $this->render('social_network/show.html.twig', [
            'social_network' => $socialNetwork,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="social_network_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, SocialNetwork $socialNetwork): Response
    {
        $enterprise = $socialNetwork->getEnterprise();
        $form = $this->createForm(SocialNetworkType::class, $socialNetwork);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('social_network_show', ['id' => $socialNetwork->getId()]);
        }

        return $this->render('social_network/edit.html.twig', [
            'social_network' => $socialNetwork,
            'form' => $form->createView(),
            'enterprise' => $enterprise,
            'enterprises' => $this->getUser()->getEnterprises(),
        ]);
    }

    /**
     * @Route("/{id}", name="social_network_delete", methods={"DELETE"})
     */
    public function delete(Request $request, SocialNetwork $socialNetwork): Response
    {
        if ($this->isCsrfTokenValid('delete' . $socialNetwork->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $socialNetwork->setVisible(false);
            // $entityManager->remove($socialNetwork);
            $entityManager->flush();
        }

        return $this->redirectToRoute('enterprise_show', ['id' => $socialNetwork->getEnterprise()->getID()]);
    }
}
