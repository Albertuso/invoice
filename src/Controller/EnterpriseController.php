<?php

namespace App\Controller;

use App\Entity\Enterprise;
use App\Form\EnterpriseType;
use App\Repository\EnterpriseRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("/enterprise")
 */
class EnterpriseController extends AbstractController
{
    /**
     * @Route("/", name="enterprise_index", methods={"GET"})
     */
    public function index(EnterpriseRepository $enterpriseRepository): Response
    {
        return $this->render('enterprise/index.html.twig', [
            'enterprises' => $enterpriseRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="enterprise_new", methods={"GET","POST"})
     * 
     * @IsGranted("ROLE_USER")
     */
    public function new(Request $request): Response
    {

        $user = $this->getUser();

        if ($user->getMax() > count($user->getEnterprises())) {
            $enterprise = new Enterprise();
            $form = $this->createForm(EnterpriseType::class, $enterprise);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $entityManager = $this->getDoctrine()->getManager();
                $this->getUser()->addEnterprise($enterprise);
                $entityManager->persist($user);
                $entityManager->flush();

                return $this->redirectToRoute('enterprise_index');
            }

            return $this->render('enterprise/new.html.twig', [
                'enterprise' => $enterprise,
                'form' => $form->createView(),
            ]);
        } else {
            return $this->render('enterprise/errormax.html.twig');

        }
    }

    /**
     * @Route("/{id}", name="enterprise_show", methods={"GET"})
     */
    public function show(Enterprise $enterprise): Response
    {
        return $this->render('enterprise/show.html.twig', [
            'enterprise' => $enterprise,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="enterprise_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Enterprise $enterprise): Response
    {
        $form = $this->createForm(EnterpriseType::class, $enterprise);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('enterprise_index');
        }

        return $this->render('enterprise/edit.html.twig', [
            'enterprise' => $enterprise,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="enterprise_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Enterprise $enterprise): Response
    {
        if ($this->isCsrfTokenValid('delete' . $enterprise->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($enterprise);
            $entityManager->flush();
        }

        return $this->redirectToRoute('enterprise_index');
    }
}
