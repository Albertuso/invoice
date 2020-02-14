<?php

namespace App\Controller;

use App\Entity\Supervisor;
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
     * @Route("/", name="supervisor_index", methods={"GET"})
     */
    public function index(SupervisorRepository $supervisorRepository): Response
    {
        return $this->render('supervisor/index.html.twig', [
            'supervisors' => $supervisorRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="supervisor_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $supervisor = new Supervisor();
        $form = $this->createForm(SupervisorType::class, $supervisor);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($supervisor);
            $entityManager->flush();

            return $this->redirectToRoute('supervisor_index');
        }

        return $this->render('supervisor/new.html.twig', [
            'supervisor' => $supervisor,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="supervisor_show", methods={"GET"})
     */
    public function show(Supervisor $supervisor): Response
    {
        return $this->render('supervisor/show.html.twig', [
            'supervisor' => $supervisor,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="supervisor_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Supervisor $supervisor): Response
    {
        $form = $this->createForm(SupervisorType::class, $supervisor);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('supervisor_index');
        }

        return $this->render('supervisor/edit.html.twig', [
            'supervisor' => $supervisor,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="supervisor_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Supervisor $supervisor): Response
    {
        if ($this->isCsrfTokenValid('delete'.$supervisor->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($supervisor);
            $entityManager->flush();
        }

        return $this->redirectToRoute('supervisor_index');
    }
}
