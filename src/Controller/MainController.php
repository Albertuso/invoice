<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

use App\Entity\Client;
use App\Entity\Enterprise;
use App\Entity\Invoice;
use App\Entity\Network;
use App\Entity\Product;
use App\Entity\ProductLine;
use App\Entity\SocialNetwork;
use App\Entity\Supervisor;
use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Security\LoginAuthenticator;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\HttpFoundation\Request as HttpFoundationRequest;
use Symfony\Component\HttpFoundation\Response;

class MainController extends AbstractController{
    /**
     * @Route("/", name="main")
     */
    public function index(HttpFoundationRequest $request, UserPasswordEncoderInterface $passwordEncoder): Response{

        //Vista de Bienvenida
            //Si pulsamos registrarnos
            $user = new User();
            $registrationForm=$this->createForm(RegistrationFormType::class, $user);
            $registrationForm->handleRequest($request);
            if($registrationForm->isSubmitted() && $registrationForm->isValid()){
                $em= $this->getDoctrine()->getManager();
                $user->setRoles(['ROLE_USER']);
                $user->setPassword(
                    $passwordEncoder->encodePassword(
                        $user,
                        $registrationForm->get('plainPassword')->getData()
                    )
                );
                $em->persist($registrationForm);
                $em->flush();
                $this->addFlash('exito','Usuario registrado');
                return $this->redirectToRoute('main');
            }
    
            //Si pulsamos login
            
            $loginForm=$this->createForm(LoginAuthenticator::class, $request );
            $loginForm->handleRequest($request);
            if($loginForm->isSubmitted() && $loginForm->isValid()){
            
                return $this->redirectToRoute('main');
            }
    
            return $this->render('main/index.html.twig', [
                'controller_name' => 'PrincipalController',
                'registrationForm'=>$registrationForm->createView(),
                'loginForm'=>$loginForm->createView()
            ]);

        //Vista fin de bienvenida

        $repositoryUsers = $this->getDoctrine()->getRepository(User::class);
        $users = $repositoryUsers->findAll();

        return $this->render('main/index.html.twig', [
            'controller_name' => 'MainController',
            'user' => $this->getUser(),
            'users' => $users,
        ]);
        //vista principal donde apareceran las empresas del usuario
        
        }
    /**
     * @Route("/edit", name="edit")
     */
        public function edit(){
        return $this->render('main/index.html.twig', [
            'controller_name' => 'MainController',
        ]);
    }
    /**
     * @Route("/newenterprise", name="newenterprise")
     */
    public function newenterprise(){
        //vista de creacion de empresa    	
        return $this->render('main/index.html.twig', [
            'controller_name' => 'MainController',
        ]);
    }
    /**
     * @Route("/newclients", name="newclients")
     */
    public function newclients(){
        //vista de creacion de los clientes de la empresa    	
        return $this->render('main/index.html.twig', [
            'controller_name' => 'MainController',
        ]);
    }
    /**
     * @Route("/newproducts", name="newproducts")
     */
    public function newproducts(){
        //vista de creacion de los productos de la empresa    	
        return $this->render('main/index.html.twig', [
            'controller_name' => 'MainController',
        ]);
    }
    /**
     * @Route("/newinvoices", name="newinvoices")
     */
    public function newinvoices(){
        //vista de creacion de las facturas de la empresa    	
        return $this->render('main/index.html.twig', [
            'controller_name' => 'MainController',
        ]);
    }
    /**
     * @Route("/enterprise", name="enterprise")
     */
    public function enterprise(){
        //vista de la empresa seleccionada    	
        return $this->render('main/index.html.twig', [
            'controller_name' => 'MainController',
        ]);
    }
    /**
     * @Route("/clients", name="clients")
     */
    public function clients(){
        //vista de los clientes de la empresa   	
        return $this->render('main/index.html.twig', [
            'controller_name' => 'MainController',
        ]);
    }
    /**
     * @Route("/products", name="products")
     */
    public function products(){
    	//vista de los productos de la empresa
        return $this->render('main/index.html.twig', [
            'controller_name' => 'MainController',
        ]);
    }
    /**
     * @Route("/invoices", name="invoices")
     */
    public function invoices(){
    	//vista de las facturas de la empresa
        return $this->render('main/index.html.twig', [
            'controller_name' => 'MainController',
        ]);
    }  
}