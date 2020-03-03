<?php

namespace App\Controller;

use App\Entity\Enterprise;
use App\Entity\Invoice;
use App\Form\EnterpriseType;
use App\Repository\EnterpriseRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("/enterprise")
 * @IsGranted("ROLE_USER")

 */
class EnterpriseController extends AbstractController
{
    /**
     * @Route("/", name="enterprise_index", methods={"GET"})
     */
    public function index(EnterpriseRepository $enterpriseRepository): Response
    {
        return $this->redirectToRoute('main');
    }

    /**
     * @Route("/new", name="enterprise_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {

        $user = $this->getUser();

        $visibles = 0;
        for ($i = 0; $i < count($user->getEnterprises()); $i++) {
            if ($user->getEnterprises()[$i]->getVisible() == true) {
                $visibles++;
            }
        }


        if ($user->getMax() > $visibles) {
            $enterprise = new Enterprise();
            $form = $this->createForm(EnterpriseType::class, $enterprise);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {

                $entityManager = $this->getDoctrine()->getManager();

                $file = $form['logo']->getData();
                $ext = $file->guessExtension();
                $file_name = time() . "." . $ext;
                $file->move("./img/logos", $file_name);

                $enterprise->setLogo($file_name);
                $enterprise->setVisible(true);

                $enterprise->setNextinvoicenumber(0);
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
            'enterprises' => $this->getUser()->getEnterprises(),
            'supervisors' => $enterprise->getSupervisors(),
        ]);
    }

    /**
     * @Route("/{id}/edit", name="enterprise_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Enterprise $enterprise): Response
    {
        $repositoryEnterprise = $this->getDoctrine()->getRepository(Enterprise::class);
        $form = $this->createForm(EnterpriseType::class, $enterprise);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $file = $form['logo']->getData();
            $ext = $file->guessExtension();
            $file_name = time() . "." . $ext;
            $file->move("./img/logos", $file_name);

            $enterprise->setLogo($file_name);

            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('enterprise_index');
        }

        return $this->render('enterprise/edit.html.twig', [
            'enterprise' => $enterprise,
            'enterprises' => $repositoryEnterprise->findAll(),
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

            $enterprise->setVisible(false);
            // $entityManager->remove($enterprise);
            $entityManager->flush();
        }

        return $this->redirectToRoute('main');
    }

    /**
     * @Route("/{idempresa}/listinvoices", name="enterprise_listinvoices", methods={"GET","POST"})
     */
    public function invoices(Request $request, $idempresa)
    {
        $repositoryInvoice = $this->getDoctrine()->getRepository(Invoice::class);
        $invoicesenterprise = $repositoryInvoice->findByEnterprise($idempresa);



        $repositoryEnterprise = $this->getDoctrine()->getRepository(Enterprise::class);
        $enterprise = $repositoryEnterprise->findOneById($idempresa);

        // $form = $this->createForm(EnterpriseType::class, $enterprise);
        $form = $this->createFormBuilder()
            ->add('buscar')
            ->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // data is an array with "name", "email", and "message" keys
            $data = $form->getData();

            $invoicesenterprise = $repositoryInvoice->findByLoQueSea($data['buscar']);

            return $this->render('debug.html.twig', [
                'debug' => $invoicesenterprise,
            ]);
        }

        return $this->render('enterprise/show_invoices.html.twig', [
            'invoiceseterprises' => $invoicesenterprise,
            'form' => $form->createView(),
            'enterprise' => $enterprise,
            'enterprises' => $repositoryEnterprise->findAll(),
        ]);
    }
}
