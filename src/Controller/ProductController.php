<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\Enterprise;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("/product")
 * @IsGranted("ROLE_USER")
 */
class ProductController extends AbstractController
{
    /**
     * @Route("/enterprise/{idempresa}", name="product_index", methods={"GET"})
     */
    public function index(ProductRepository $productRepository, $idempresa): Response
    {
        $enterpriseRepository = $this->getDoctrine()->getRepository(Enterprise::class);

        return $this->render('product/index.html.twig', [
            'products' => $productRepository->findByEnterpriseId($idempresa),
            'id_empresa' => $idempresa,
            'enterprise' => $enterpriseRepository->findOneByid($idempresa),
            'enterprises' => $this->getUser()->getEnterprises(),
        ]);
    }

    /**
     * @Route("/new/enterprise/{idempresa}", name="product_new", methods={"GET","POST"})
     */
    public function new(Request $request, $idempresa): Response
    {
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {


            $repositoryEnterprise = $this->getDoctrine()->getRepository(Enterprise::class);
            $enterprise = $repositoryEnterprise->findOneById($idempresa);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($product);
            $product->setEnterprise($enterprise);
            $entityManager->flush();

            // return $this->redirectToRoute('product_index');
            return $this->redirectToRoute('product_index', ['idempresa' => $idempresa]);
        }

        return $this->render('product/new.html.twig', [
            'product' => $product,
            'form' => $form->createView(),
            'id_empresa' => $idempresa,
        ]);
    }

    /**
     * @Route("/{id}", name="product_show", methods={"GET"})
     */
    public function show(Product $product): Response
    {
        return $this->render('product/show.html.twig', [
            'product' => $product,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="product_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Product $product): Response
    {
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('product_index');
        }

        return $this->render('product/edit.html.twig', [
            'product' => $product,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="product_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Product $product): Response
    {
        if ($this->isCsrfTokenValid('delete' . $product->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($product);
            $entityManager->flush();
        }

        return $this->redirectToRoute('product_index');
    }
}
