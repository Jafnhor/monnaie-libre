<?php

namespace Ml\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Ml\UserBundle\Entity\User;
use Ml\UserBundle\Form\UserType;

class UserController extends Controller
{
	public function indexAction()
	{
		/* Test connexion */
		$req = $this->get('request');
		
        try {		
		    $login = $this->container->get('ml.session')->sessionExist($req);
		}
		catch (\Exception $e) {
		    return $this->redirect($this->generateUrl('ml_user_add'));		    
		}

        $user = $this->getDoctrine()
			->getRepository('MlUserBundle:User')
			->findOneBy(array("login" => $login, "visible" => true));
		
		if ($user != NULL) {
			return $this->render('MlUserBundle:User:see.html.twig', array('user' => $user));
		}
		else {
			return $this->redirect($this->generateUrl('ml_user_add'));	
		}
	}	

	public function seeAction()
	{
		/* Test connexion */
		$req = $this->get('request');
		
        try {		
		    $login = $this->container->get('ml.session')->sessionExist($req);
		}
		catch (\Exception $e) {
		    return $this->redirect($this->generateUrl('ml_user_add'));		    
		}

        $user = $this->getDoctrine()
			->getRepository('MlUserBundle:User')
			->findOneByLogin($login);
	
		/** S'il existe, il est envoyé à la vue **/
		return $this->render('MlUserBundle:User:see.html.twig', array('user' => $user));

	}
	
	public function addAction()
	{
		/* Test connexion */
		$req = $this->get('request');	
		try {	
		    $login = $this->container->get('ml.session')->sessionExist($req);
		}
		catch (\Exception $e) {
		    /* Création d'un nouvel utilisateur */	
		    $user = new User;

		    $form = $this->createForm(new UserType(),$user);

		    /* Vérification de non doublon du login */
		    if($req->getMethod() == 'POST'){
			    $form->bind($req);
				
			    $userExisteDeja = $this->getDoctrine()
			    ->getRepository('MlUserBundle:User')
			    ->findOneByLogin($this->getRequest()->request->get('login'));

			    /* Doublon -> inscription impossible */
			    if($userExisteDeja != NULL) {
				    return $this->render('MlUserBundle:User:add.html.twig', array(
					      'form' => $form->createView(),
					      'erreur' => "Le login saisi est déjà pris, veuillez en choisir un autre"));
			    }
				
				$user->setLastName($this->getRequest()->request->get('lastName'));
				$user->setFirstName($this->getRequest()->request->get('firstName'));
				$user->setLogin($this->getRequest()->request->get('login'));
				
				$pass = $this->getRequest()->request->get('password');
				$crypted_pass = md5($pass);
				
				$user->setPassword($crypted_pass);

			    /* Aucun doublon -> inscription possible. Génération du formulaire d'inscription */

				$em = $this->getDoctrine()->getManager();
				$em->persist($user);
				$em->flush();

				$this->get('session')->getFlashBag()->add('inscription','Bienvenue dans notre communauté');
				$this->get('session')->set('login', $form->getData()->getLogin()); 

				return $this->redirect($this->generateUrl('ml_user_see'));
		    }
		    /* Formulaire non valide -> rechargement de la page */
		    return $this->render('MlUserBundle:User:add.html.twig', array('form' => $form->createView()));
		}
		return $this->redirect($this->generateUrl('ml_user_see'));		
	}

	public function deleteAction() {
		/* Test connexion*/
		$req = $this->get('request');
		
		try {		
		    $login = $this->container->get('ml.session')->sessionExist($req);
		}
		catch (\Exception $e) {
		    return $this->redirect($this->generateUrl('ml_user_add'));		    
		}
 
        $user = $this->getDoctrine()
			->getRepository('MlUserBundle:User')
			->findOneByLogin($login);

		$form = $this->createFormBuilder()->getForm();

		if ($req->getMethod() == 'POST') {
			$form->bind($req);
			
			$em = $this->getDoctrine()->getManager();
			
			$couchsurfing_evals = $this->getDoctrine()
				->getRepository('MlTransactionBundle:CouchsurfingEval')
				->findBySubscriber($user);
			
			$carpooling_evals = $this->getDoctrine()
				->getRepository('MlTransactionBundle:CarpoolingEval')
				->findBySubscriber($user);
				
			$sale_evals = $this->getDoctrine()
				->getRepository('MlTransactionBundle:SaleEval')
				->findBySubscriber($user);
				
			$basic_evals = $this->getDoctrine()
				->getRepository('MlTransactionBundle:BasicEval')
				->findBySubscriber($user);
				
			$couchsurfing_users = $this->getDoctrine()
				->getRepository('MlServiceBundle:CouchSurfingUser')
				->findByApplicant($user);
			
			$carpooling_users = $this->getDoctrine()
				->getRepository('MlServiceBundle:CarpoolingUser')
				->findByApplicant($user);
				
			$sale_users = $this->getDoctrine()
				->getRepository('MlServiceBundle:SaleUser')
				->findByApplicant($user);
				
			$basic_users = $this->getDoctrine()
				->getRepository('MlServiceBundle:BasicUser')
				->findByApplicant($user);
			
			if (($couchsurfing_evals != NULL) || ($carpooling_evals != NULL) || ($sale_evals != NULL) || ($basic_evals != NULL) ||
			($couchsurfing_users != NULL) || ($carpooling_users != NULL) || ($sale_users != NULL) || ($basic_users != NULL)) {
				return $this->render('MlUserBundle:User:delete.html.twig', array(
					'message' => ("Vous devez accomplir votre devoir avant de pouvoir quitter Poavre (vous avez des services à réaliser ou des évaluations en attente), merci."),
					'user' => $user,
					'form' => $form->createView()));
			}
			
			$user->setVisible(false);
			$user->setMaster(false);
			$user->setModerator(false);
			
			$carpoolings = $this->getDoctrine()
				->getRepository('MlServiceBundle:Carpooling')
				->findByUser($user);
				
			foreach ($carpoolings as $key => $value) {
				$value->setVisibility(false);
				$em->persist($value);
				$em->flush();
			}	
			
			$basics = $this->getDoctrine()
				->getRepository('MlServiceBundle:Basic')
				->findByUser($user);
				
			foreach ($basics as $key => $value) {
				$value->setVisibility(false);
				$em->persist($value);
				$em->flush();
			}	
			
			$sales = $this->getDoctrine()
				->getRepository('MlServiceBundle:Sale')
				->findByUser($user);
				
			foreach ($sales as $key => $value) {
				$value->setVisibility(false);
				$em->persist($value);
				$em->flush();
			}	
			
			$couchsurfings = $this->getDoctrine()
				->getRepository('MlServiceBundle:CouchSurfing')
				->findByUser($user);
				
			foreach ($couchsurfings as $key => $value) {
				$value->setVisibility(false);
				$em->persist($value);
				$em->flush();
			}	
			
			$em->persist($user);
			$em->flush();

			/* Redirection vers l'accueil du site */
			return $this->redirect($this->generateUrl('ml_user_deconnexion'));
		}
		/* Formulaire non valide -> rechargement de la page */
		return $this->render('MlUserBundle:User:delete.html.twig', array(
			'user' => $user,
			'form' => $form->createView()));
	}
	
	public function connexionAction() {
		// On récupère la requête
		$request = $this->get('request');

		/* Demande de connexion */
		if ($request->getMethod() == 'POST') {
			$user = $this->getDoctrine()
						->getRepository('MlUserBundle:User')
						->findOneBy(array('login' => $request->request->get('login'),
										'password' => md5($request->request->get('mot_de_passe')),
										'visible' => true));
			/* login+password OK -> redirection vers notre page */
			if ($user != NULL) {
				$session = new Session();
				$session->start();
			
				$session->set('login', $request->request->get('login')); 
				
				return $this->render('MlUserBundle:User:see.html.twig', array(
					'user' => $user));
			}
			else { /* login+password FAIL -> redirection inscription */
				return $this->redirect($this->generateUrl('ml_user_add'));
			}
		}
	
		/* Premier accès à la page de connexion */
		return $this->redirect($this->generateUrl('ml_user_add'));
	}
	
	public function deconnexionAction() {
		// On récupère la requête
		$request = $this->get('request');
		$session = $request->getSession();		

		/* Deconnexion -> redirection vers la page d'accueil */
		$session->invalidate();
		return $this->redirect($this->generateUrl('ml_user_add'));
	}
	
    public function editAction(){
	    /* Test connexion*/
		$req = $this->get('request');
		
		try {		
		    $login = $this->container->get('ml.session')->sessionExist($req);
		}
		catch (\Exception $e) {
		    return $this->redirect($this->generateUrl('ml_user_add'));		    
		}
 
        $user = $this->getDoctrine()
			->getRepository('MlUserBundle:User')
			->findOneByLogin($login);
		
		$real_pass = $user->getPassword();

		// On crée le FormBuilder grâce à la méthode du contrôleur
		$formBuilder = $this->createFormBuilder($user);

		// On ajoute les champs de l'entité que l'on veut à notre formulaire
		$formBuilder
			->add('lastName', 'text', array(
											"label" => "Nom"))
			->add('firstName', 'text', array(
											"label" => "Prénom"))
			->add('password', 'password', array(
											"label" => "Mot de passe"));

	    // À partir du formBuilder, on génère le formulaire
	    $form = $formBuilder->getForm();
		$request = $this->getRequest();

		if ($request->getMethod() == 'POST') {
			$form->bind($request);

			$em = $this->getDoctrine()->getManager();
			
			if ($request->request->get("form")["password"] != NULL) {
				$pass = md5($request->request->get("form")["password"]);
				
				if ($pass != NULL) {
					$user->setPassword($pass);
				}
				else {
					$user->setPassword($real_pass);
				}
			}
			else {
				$user->setPassword($real_pass);
			}
			
			$em->persist($user);
			$em->flush();

			// On définit un message flash
			$this->get('session')->getFlashBag()->add('info', 'Votre profil a bien été modifié');

			return $this->redirect($this->generateUrl('ml_user_see'));
		}
		else {
			return $this->render('MlUserBundle:User:edit.html.twig', array(
				'form' => $form->createView(), 
				'id' => $user->getId(),
				'user' => $user));

			// On passe la méthode createView() du formulaire à la vue afin qu'elle puisse afficher le formulaire toute seule
		}
	}
}