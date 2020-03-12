<?php

namespace App\Controller;

use App\Entity\Supervisor;
use App\Entity\Enterprise;
use App\Form\SupervisorType;
use App\Repository\SupervisorRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("/supervisor")
 * @IsGranted("ROLE_USER")
 */
class SupervisorController extends AbstractController
{
    /**
     * @Route("/{identerprise}", name="supervisor_index", methods={"GET"})
     */
    public function index(SupervisorRepository $supervisorRepository, $identerprise): Response
    {

        $repositoryEnterprise = $this->getDoctrine()->getRepository(Enterprise::class);
        $enterprise = $repositoryEnterprise->findOneById($identerprise);



        return $this->render('supervisor/index.html.twig', [
            'supervisors' => $supervisorRepository->findByEnterprise($identerprise),
            'identerprise' => $enterprise->getId(),
            'enterprise' => $enterprise,
            'enterprises' => $repositoryEnterprise->findByUser($this->getUser()),
        ]);
    }

    /**
     * @Route("/new/{identerprise}", name="supervisor_new", methods={"GET","POST"})
     */
    public function new(Request $request, $identerprise): Response
    {
        $supervisor = new Supervisor();
        $repositoryEnterprise = $this->getDoctrine()->getRepository(Enterprise::class);
        $enterprise = $repositoryEnterprise->findOneById($identerprise);
        $form = $this->createForm(SupervisorType::class, $supervisor);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();

            $supervisor->setEnterprise($enterprise);
            $supervisor->setVisible(true);
            $entityManager->persist($supervisor);
            $entityManager->flush();

            return $this->redirectToRoute('supervisor_index', ['identerprise' => $identerprise]);
        }

        return $this->render('supervisor/new.html.twig', [
            'supervisor' => $supervisor,
            'form' => $form->createView(),
            'enterprise' => $enterprise,
            'enterprises' => $repositoryEnterprise->findByUser($this->getUser()),
        ]);
    }

    /**
     * @Route("/{id}", name="supervisor_show", methods={"GET"})
     */
    public function show(Supervisor $supervisor): Response
    {
        $repositoryEnterprise = $this->getDoctrine()->getRepository(Enterprise::class);

        return $this->render('supervisor/show.html.twig', [
            'supervisor' => $supervisor,
            'enterprises' => $repositoryEnterprise->findByUser($this->getUser()),
        ]);
    }

    /**
     * @Route("/{id}/edit", name="supervisor_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Supervisor $supervisor): Response
    {
        $form = $this->createForm(SupervisorType::class, $supervisor);
        $form->handleRequest($request);
        $repositoryEnterprise = $this->getDoctrine()->getRepository(Enterprise::class);


        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('supervisor_index', ['identerprise' => $supervisor->getEnterprise()->getId()]);
        }

        return $this->render('supervisor/edit.html.twig', [
            'supervisor' => $supervisor,
            'form' => $form->createView(),
            'identerprise' => $supervisor->getEnterprise()->getId(),
            'enterprise' => $supervisor->getEnterprise(),
            'enterprises' => $repositoryEnterprise->findByUser($this->getUser()),
        ]);
    }

    /**
     * @Route("/{id}", name="supervisor_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Supervisor $supervisor): Response
    {
        $enterprise = $supervisor->getEnterprise();
        if ($this->isCsrfTokenValid('delete' . $supervisor->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $supervisor->setVisible(false);
            // $entityManager->remove($supervisor);
            $entityManager->flush();
        }

        return $this->redirectToRoute('enterprise_show', ['id' => $enterprise->getId()]);
    }
}
