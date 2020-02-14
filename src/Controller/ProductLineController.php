<?php

namespace App\Controller;

use App\Entity\ProductLine;
use App\Form\ProductLineType;
use App\Repository\ProductLineRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("/product/line")
 * @IsGranted("ROLE_USER")
 */
class ProductLineController extends AbstractController
{
    /**
     * @Route("/", name="product_line_index", methods={"GET"})
     */
    public function index(ProductLineRepository $productLineRepository): Response
    {
        return $this->render('product_line/index.html.twig', [
            'product_lines' => $productLineRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="product_line_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $productLine = new ProductLine();
        $form = $this->createForm(ProductLineType::class, $productLine);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($productLine);
            $entityManager->flush();

            return $this->redirectToRoute('product_line_index');
        }

        return $this->render('product_line/new.html.twig', [
            'product_line' => $productLine,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="product_line_show", methods={"GET"})
     */
    public function show(ProductLine $productLine): Response
    {
        return $this->render('product_line/show.html.twig', [
            'product_line' => $productLine,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="product_line_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, ProductLine $productLine): Response
    {
        $form = $this->createForm(ProductLineType::class, $productLine);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('product_line_index');
        }

        return $this->render('product_line/edit.html.twig', [
            'product_line' => $productLine,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="product_line_delete", methods={"DELETE"})
     */
    public function delete(Request $request, ProductLine $productLine): Response
    {
        if ($this->isCsrfTokenValid('delete'.$productLine->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($productLine);
            $entityManager->flush();
        }

        return $this->redirectToRoute('product_line_index');
    }
}
