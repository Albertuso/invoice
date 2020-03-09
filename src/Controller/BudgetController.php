<?php

namespace App\Controller;

use App\Entity\Budget;
use App\Entity\Invoice;
use App\Entity\ProductLine;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Client;
use App\Entity\Enterprise;
use App\Entity\Product;
use App\Entity\BudgetLine;
use App\Form\BudgetType;
use Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("/budget")
 */
class BudgetController extends AbstractController
{
    /**
     * @Route("/{identerprise}/client/{idclient}", name="budget_new")
     * @IsGranted("ROLE_USER")
     */
    public function new(Request $request, $identerprise, $idclient): Response
    {
        // Cargo el cliente (por route)
        $repositoryClient = $this->getDoctrine()->getRepository(Client::class);
        $client = $repositoryClient->findOneById($idclient);

        // Cargo la empresa a la que pertecene el cliente
        $repositoryEnterprise = $this->getDoctrine()->getRepository(Enterprise::class);
        $enterprise = $repositoryEnterprise->findOneById($identerprise);

        //Cargo los productos de esa empresa
        $repositoryProduct = $this->getDoctrine()->getRepository(Product::class);
        $products = $repositoryProduct->findByEnterpriseId($enterprise);

        //Genero un presupuesto
        $budget = new Budget();

        // Pongo los datos por defecto        
        $hoy = new \DateTime();
        $budget->setDate($hoy);
        $budget->setVisible(true);
        $budget->setFooter($enterprise->getFooter());
        $budget->setSold('N');

        // Creo un array de Productlines
        $this->BudgetLine = new ArrayCollection();

        // Se genera el formulario con los datos
        $form = $this->createForm(BudgetType::class, $budget);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager = $this->getDoctrine()->getManager();
            $budget->setClient($client);
            $budget->setEnterprise($enterprise);
            $names = $_REQUEST['productName'];
            $quantities = $_REQUEST['quantity'];
            $prices = $_REQUEST['price'];
            $vats = $_REQUEST['VAT'];

            //Aqui toca comprobar que la nueva factura es válida

            //Guardo las lineas 
            for ($i = 0; $i < count($names); $i++) {
                $newLine = new BudgetLine();
                $newLine->setName($names[$i]);
                $newLine->setQuantity($quantities[$i]);
                $newLine->setPrice($prices[$i]);
                $newLine->setVat($vats[$i]);

                $budget->addLine($newLine);
            }
            $entityManager->persist($budget);
            $entityManager->flush();


            // envio el email (no probado al no irme mercury)
            $to = $client->getEmail();
            $id = $budget->getId();
            $passwd =  $budget->getPasswd();
            $subject = "Factura facil nuevo presupuesto " . $id;
            $headers = "MIME-Version: 1.0" . "\r\n";
            $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
            $message = "<h1>Presupuesto Factura-Facil</h1>";
            $message .= "<p>Tienes un presupuesto pendiente</p>";
            $message .= "<p><a href='http://www.invoice.tld/budget/" . $id . "/paid/" . $passwd . "'>Pulsa aqui para aprobarlo</a></p>
";
            $message .= "<p><a href='http://www.invoice.tld/budget/" . $id . "/reject/" . $passwd . "'>Pulsa aqui para rechazarlo</a></p>
";

            echo ($to . $subject . $message . $headers);
            // mail($to, $subject, $message, $headers);

            return $this->redirectToRoute('budget_view', ['identerprise' => $identerprise]);
        }

        return $this->render('budget/new.html.twig', [
            'budget' => $budget,
            'form' => $form->createView(),
            'enterprise' => $enterprise,
            'enterprises' => $repositoryEnterprise->findAll(),
            'client' => $client,
            'products' => $products,
        ]);
    }

    /**
     * @Route("/{id}/paid/{passwd?}", name="budget_paid", methods={"GET","POST"})
     */
    public function paid(Request $request, $id, $passwd)
    {
        $repositoryBudget = $this->getDoctrine()->getRepository(Budget::class);
        $budget = $repositoryBudget->findByIdPasswd($id, $passwd);
        if ($budget) {
            if ($budget->getSold() == 'N') {
                $entityManager = $this->getDoctrine()->getManager();
                $budget->getId($id);
                $budget->setSold('S');

                $invoice = new Invoice();
                // Copio sus datos
                $invoice->setEnterprise($budget->getEnterprise());
                $invoice->setDate($budget->getDate());
                $invoice->setDescription($budget->getDescription());
                $invoice->setFooter($budget->getFooter());

                //Guardo las lineas 
                foreach ($budget->getLine() as $line) {
                    $newLine = new ProductLine();
                    $newLine->setName($line->getName());
                    $newLine->setQuantity($line->getQuantity());
                    $newLine->setPrice($line->getPrice());
                    $newLine->setVat($line->getVat());

                    $invoice->addLine($newLine);
                }

                // Guardo el resto de datos
                $invoice->setInvoicenumber($budget->getEnterprise()->getNextInvoiceNumber());
                $budget->getEnterprise()->setNextInvoiceNumber($budget->getEnterprise()->getNextInvoiceNumber() + 1);

                $invoice->setSubtotal($budget->getSubtotal());
                $invoice->setTotal($budget->getTotal());
                $invoice->setClient($budget->getClient());
                $invoice->setVisible(true);


                $entityManager->persist($budget);
                $entityManager->persist($invoice);
                // $entityManager->persist($budget->getEnterprise());

                $entityManager->flush();

                return $this->redirectToRoute('budget_view', ['identerprise' => $budget->getEnterprise()->getId()]);
            } else throw new Exception("El presupuesto ya fue pagado", 1);
        }

        if ($passwd == null) {

            // throw new Exception("Presupuesto sin contraseña");

            $form = $this->createFormBuilder()
                ->add('passwd', PasswordType::class, array(
                    'attr' => ['class' => 'form-control'],
                ))
                ->add('aceptar', SubmitType::class, array(
                    'attr' => ['class' => 'form-control']
                ))
                ->getForm();
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {

                return $this->redirectToRoute('budget_paid', ['id' => $id, 'passwd' => $form['passwd']->getData()]);
            } else {
                return $this->render('budget/confirmpasswd.html.twig', [
                    'form' => $form->createView(),
                ]);
            }
        } else {
            throw new Exception("Presupuesto no válido y/o contraseña incorrecta");
        }
    }

    /**
     * @Route("/{id}/reject/{passwd?}", name="budget_reject", methods={"GET","POST"})
     */
    public function reject(Request $request, $id, $passwd)
    {
        $repositoryBudget = $this->getDoctrine()->getRepository(Budget::class);
        $budget = $repositoryBudget->findByIdPasswd($id, $passwd);
        if ($budget) {
            if ($budget->getSold() == 'N') {
                $entityManager = $this->getDoctrine()->getManager();
                $budget->getId($id);
                $budget->setSold('R');
                $entityManager->persist($budget);
                $entityManager->flush();

                return $this->redirectToRoute('budget_view', ['identerprise' => $budget->getEnterprise()->getId()]);
            } else throw new Exception("El presupuesto ya fue pagado", 1);
        }

        if ($passwd == null) {

            // throw new Exception("Presupuesto sin contraseña");

            $form = $this->createFormBuilder()
                ->add('passwd', PasswordType::class, array(
                    'attr' => ['class' => 'form-control'],
                ))
                ->add('aceptar', SubmitType::class, array(
                    'attr' => ['class' => 'form-control']
                ))
                ->getForm();
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {

                return $this->redirectToRoute('budget_reject', ['id' => $id, 'passwd' => $form['passwd']->getData()]);
            } else {
                return $this->render('budget/confirmpasswd.html.twig', [
                    'form' => $form->createView(),
                ]);
            }
        } else {
            throw new Exception("Presupuesto no válido y/o contraseña incorrecta");
        }
    }

    /**
     * @Route("/view/{identerprise}", name="budget_view")
     * @IsGranted("ROLE_USER")
     */
    public function index($identerprise): Response
    {
        $enterpriseRepository = $this->getDoctrine()->getRepository(Enterprise::class);
        $enterpriseBudgets = $this->getDoctrine()->getRepository(Budget::class);

        return $this->render('budget/index.html.twig', [
            'budgets' => $enterpriseBudgets->findByEnterprise($identerprise),
            'identerprise' => $identerprise,
            'enterprise' => $enterpriseRepository->findOneByid($identerprise),
        ]);
    }

    /**
     * @Route("/{id}/edit", name="budget_edit", methods={"GET","POST"})
     * @IsGranted("ROLE_USER")
     */
    public function edit(Request $request, Budget $budget): Response
    {
        $repositoryEnterprises = $this->getDoctrine()->getRepository(Enterprise::class);
        // Cargo el cliente (por budget)
        $client = $budget->getClient();
        // Cargo la empresa a la que pertecene el cliente
        $enterprise = $client->getEnterprise();

        //Cargo los productos de esa empresa
        $repositoryProduct = $this->getDoctrine()->getRepository(Product::class);
        $products = $repositoryProduct->findByEnterpriseId($enterprise);

        $form = $this->createForm(BudgetType::class, $budget);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            //Cargo las lineas de productos de esa empresa
            $repositoryBudgetLine = $this->getDoctrine()->getRepository(BudgetLine::class);
            $budgetLines = $repositoryBudgetLine->findByBudget($budget);

            // Eliminar todas las lineas
            for ($i = 0; $i < count($budget->getLine()); $i++) {
                // $invoice->removeLine($invoice->getLine($i)[0]);
                $this->getDoctrine()->getManager()->remove($budgetLines[$i]);
            }

            $this->getDoctrine()->getManager()->flush();

            $names = $_REQUEST['productName'];
            $quantities = $_REQUEST['quantity'];
            $prices = $_REQUEST['price'];
            $vats = $_REQUEST['VAT'];

            //Aqui toca comprobar que la nueva factura es válida

            //Guardo las lineas 
            for ($i = 0; $i < count($names); $i++) {
                $newLine = new BudgetLine();
                $newLine->setName($names[$i]);
                $newLine->setQuantity($quantities[$i]);
                $newLine->setPrice($prices[$i]);
                $newLine->setVat($vats[$i]);
                $budget->addLine($newLine);
            }

            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('budget_view', ['identerprise' => $budget->getEnterprise()->getId()]);
        }

        return $this->render('budget/edit.html.twig', [
            'budget' => $budget,
            'form' => $form->createView(),
            'enterprise' => $budget->getEnterprise(),
            'enterprises' => $repositoryEnterprises->findByUser($this->getUser()),
            'client' => $budget->getClient(),
            'lines' => $budget->getLine(),
            'products' => $products,
        ]);
    }

    /**
     * @Route("/{id}/changeclient}", name="budget_changeclient", methods={"GET","POST"})
     * @IsGranted("ROLE_USER")
     */
    public function changeclient($id, Request $request)
    {

        $repositoryEnterprises = $this->getDoctrine()->getRepository(Enterprise::class);
        $repositoryClients = $this->getDoctrine()->getRepository(Client::class);

        $repositoryBudget = $this->getDoctrine()->getRepository(Budget::class);
        $budget = $repositoryBudget->findOneById($id);

        $idclients = $repositoryClients->findByEnterprise($budget->getEnterprise());

        $form = $this->createFormBuilder()
            ->add('client', ChoiceType::class, array(
                'attr' => ['class' => 'form-control'],
                'choices' => array(
                    'cliente' => $idclients,
                ),
                'choice_label' => function ($value, $key, $index) {
                    if ($value == true) {
                        return $value;
                    }
                    return strtoupper($key);
                },
            ))
            ->add('cambiar', SubmitType::class, array(
                'attr' => ['class' => 'form-control']
            ))
            ->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager = $this->getDoctrine()->getManager();
            $budget->setClient($form['client']->getData());
            $entityManager->persist($budget);
            $entityManager->flush();


            return $this->redirectToRoute('budget_view', ['identerprise' => $budget->getEnterprise()->getId()]);
            // return $this->render('debug.html.twig', [
            //     'debug' => $form['client']->getData(),
            // ]);
        }

        return $this->render('budget/changeclient.html.twig', [
            'budget' => $budget,
            'form' => $form->createView(),
            'enterprise' => $budget->getEnterprise(),
            'enterprises' => $repositoryEnterprises->findByUser($this->getUser()),
            'client' => $budget->getClient(),
            'clients' => $repositoryClients->findByEnterprise($budget->getEnterprise()),
        ]);
    }
    /**
     * @Route("/{id}/changestate}", name="budget_state", methods={"GET","POST"})
     * @IsGranted("ROLE_USER")
     */
    public function changestate($id, Request $request)
    {
        $repositoryEnterprises = $this->getDoctrine()->getRepository(Enterprise::class);
        $repositoryClients = $this->getDoctrine()->getRepository(Client::class);

        $repositoryBudget = $this->getDoctrine()->getRepository(Budget::class);
        $budget = $repositoryBudget->findOneById($id);

        $idclients = $repositoryClients->findByEnterprise($budget->getEnterprise());

        $form = $this->createFormBuilder()
            ->add('state', ChoiceType::class, array(
                'attr' => ['class' => 'form-control'],
                'choices'  => [
                    'Pagado' => 'S',
                    'Pendiente' => 'N',
                    'Rechazado' => 'R',
                ],
            ))
            ->add('cambiar', SubmitType::class, array(
                'attr' => ['class' => 'form-control']
            ))
            ->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            if ($form['state']->getData() == "S") {
                return $this->redirectToRoute('budget_paid', ['id' => $id, 'passwd' => $budget->getPasswd()]);
            } else
                return $this->redirectToRoute('budget_view', ['identerprise' => $budget->getEnterprise()->getId()]);
            // return $this->render('debug.html.twig', [
            //     'debug' => $form['client']->getData(),
            // ]);
        }

        return $this->render('budget/changestate.html.twig', [
            'budget' => $budget,
            'form' => $form->createView(),
            'enterprise' => $budget->getEnterprise(),
            'enterprises' => $repositoryEnterprises->findByUser($this->getUser()),
            'client' => $budget->getClient(),
            'clients' => $repositoryClients->findByEnterprise($budget->getEnterprise()),
        ]);
    }
}
