<?php

namespace Ml\ServiceBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Ml\ServiceBundle\Entity\Service;
use Ml\ServiceBundle\Entity\Carpooling;
use Ml\ServiceBundle\Entity\CarpoolingUser;
use Ml\ServiceBundle\Form\CarpoolingType;
use Ml\ServiceBundle\Entity\CouchSurfing;
use Ml\ServiceBundle\Entity\CouchSurfingUser;
use Ml\ServiceBundle\Form\CouchSurfingType;
use Ml\ServiceBundle\Entity\Sale;
use Ml\ServiceBundle\Entity\SaleUser;
use Ml\ServiceBundle\Form\SaleType;
use Ml\UserBundle\Entity\User;

class ServiceController extends Controller
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
		
		if ($login == NULL) {
			return $this->redirect($this->generateUrl('ml_user_add'));
		}
		
		$user = $this->getDoctrine()
				->getManager()
				->getRepository('MlUserBundle:User')
				->findOneByLogin($login);

		if ($req->getMethod() == 'POST') {
			$carpooling = false;
			$couchsurfing = false;
			$sale = false;
			
			if ($req->request->get('type') != null) {
				foreach ($req->request->get('type') as $key => $value) {
					if ($value == 'carpooling') {
						$carpooling = true;
					}
					else if ($value =='couchsurfing'){
						$couchsurfing = true;
					}
					else if ($value == 'sale') {
						$sale = true;
					}

				}
				
				if ($carpooling == true) {
					$carpoolings = $this->getDoctrine()
								   ->getManager()
								   ->getRepository('MlServiceBundle:Carpooling')
								   ->findByVisibility(true);
								   
					$services[] = $carpoolings;
				}
				if ($couchsurfing == true) {
					$couchsurfings = $this->getDoctrine()
									 ->getManager()
									 ->getRepository('MlServiceBundle:CouchSurfing')
									 ->findByVisibility(true);
									 
					$services[] = $couchsurfings;
				}
				if ($sale == true) {
					$sales = $this->getDoctrine()
									 ->getManager()
									 ->getRepository('MlServiceBundle:Sale')
									 ->findByVisibility(true);
									 
					$services[] = $sales;
				}
			}
			else {
				/* Récupération de tous les Services du site */

				$carpoolings = $this->getDoctrine()
							   ->getManager()
							   ->getRepository('MlServiceBundle:Carpooling')
							   ->findByVisibility(true);
							   
				$couchsurfings = $this->getDoctrine()
								 ->getManager()
								 ->getRepository('MlServiceBundle:CouchSurfing')
								 ->findByVisibility(true);		

				$sales = $this->getDoctrine()
						 ->getManager()
						 ->getRepository('MlServiceBundle:Sale')
						 ->findByVisibility(true);						
								 
				if ($couchsurfings != NULL) {
					$services[] = $couchsurfings;
				}
				if ($carpoolings != NULL) {
					$services[] = $carpoolings;
				}
				if ($sales != NULL) {
					$services[] = $sales;
				}	
			}
		}
		else {
			/* Récupération de tous les Services du site */
			
			$carpoolings = $this->getDoctrine()
						   ->getManager()
						   ->getRepository('MlServiceBundle:Carpooling')
						   ->findByVisibility(true);
							   
			$couchsurfings = $this->getDoctrine()
							 ->getManager()
							 ->getRepository('MlServiceBundle:CouchSurfing')
							 ->findByVisibility(true);		

			$sales = $this->getDoctrine()
					 ->getManager()
					 ->getRepository('MlServiceBundle:Sale')
					 ->findByVisibility(true);						
							 
			if ($couchsurfings != NULL) {
				$services[] = $couchsurfings;
			}
			if ($carpoolings != NULL) {
				$services[] = $carpoolings;
			}
			if ($sales != NULL) {
				$services[] = $sales;
			}	
		}

		if ($services == NULL || $services[0] == NULL) {
			$services = NULL;
		}
		
		return $this->render('MlServiceBundle:Service:index.html.twig', array(
		  'servicess'=>$services,
		  'user' => $user));
	}
	
	public function addServiceAction() {
		$req = $this->get('request');		
		try {		
		    $login = $this->container->get('ml.session')->sessionExist($req);
		}
		catch (\Exception $e) {
		    return $this->redirect($this->generateUrl('ml_user_add'));		    
		}
		
		if ($login == NULL) {
			return $this->redirect($this->generateUrl('ml_user_add'));
		}
		
		$user = $this->getDoctrine()
				->getManager()
				->getRepository('MlUserBundle:User')
				->findOneByLogin($login);

		if($req->getMethod() == 'POST') {
			if ($req->request->get("type") == "carpooling") {
				return $this->redirect($this->generateUrl('ml_service_add_carpooling'));
			}
			else if ($req->request->get("type") == "couchsurfing") {
				return $this->redirect($this->generateUrl('ml_service_add_couchsurfing'));
			}
			else if ($req->request->get("type") == "sale") {
				return $this->redirect($this->generateUrl('ml_service_add_sale'));
			}			
		}
		
		return $this->render('MlServiceBundle:Service:add_service.html.twig', array(
		    'user' => $user));
	}

	public function seeCarpoolingAction($carpooling = null) {
		/* Test connexion */
		$req = $this->get('request');		
		try {		
		    $login = $this->container->get('ml.session')->sessionExist($req);
		}
		catch (\Exception $e) {
		    return $this->redirect($this->generateUrl('ml_user_add'));		    
		}
		
		if ($login == NULL) {
			return $this->redirect($this->generateUrl('ml_user_add'));
		}
		
		$user = $this->getDoctrine()
				->getManager()
				->getRepository('MlUserBundle:User')
				->findOneByLogin($login);
				
		$em = $this->getDoctrine()->getManager();
		$data_carpooling = $em->getRepository('MlServiceBundle:Carpooling')->findOneById($carpooling);
		
		/* Si le Service demandé n'existe pas */
		if ($data_carpooling == null){
			return $this->redirect($this->generateUrl('ml_service_homepage'));
		}
		
		if ($data_carpooling->getVisibility() == false) {
			return $this->redirect($this->generateUrl('ml_service_homepage'));
		}
		
		if($req->getMethod() != 'POST'){			
			return $this->render('MlServiceBundle:Service:see_carpooling.html.twig', array('user' => $user,'carpool' => $data_carpooling));
		}
		else {				
			if ($user == $data_carpooling->getUser()) {
				return $this->redirect($this->generateUrl('ml_service_homepage'));
			}
			
			$carpoolingUser = new CarpoolingUser;
			
			$carpoolingUser->setApplicant($user);
			$carpoolingUser->setCarpooling($data_carpooling);
			
			$em->persist($carpoolingUser);
			$em->flush();
			
			$data_carpooling->setVisibility(false);
			
			$em->persist($data_carpooling);
			$em->flush();

			return $this->redirect($this->generateUrl('ml_service_homepage'));
		}
	}
	
	public function addCarpoolingAction(){
		/* Test connexion */
		$req = $this->get('request');		
		try {		
		    $login = $this->container->get('ml.session')->sessionExist($req);
		}
		catch (\Exception $e) {
		    return $this->redirect($this->generateUrl('ml_user_add'));		    
		}
		
		if ($login == NULL) {
			return $this->redirect($this->generateUrl('ml_user_add'));
		}
		
		$user = $this->getDoctrine()
				->getManager()
				->getRepository('MlUserBundle:User')
				->findOneByLogin($login);
	
		$carpooling = new Carpooling;
		
		$form = $this->createForm(new CarpoolingType(),$carpooling);


		if($req->getMethod() == 'POST'){
			//lien requête<->form
			$form->bind($req);
		
			$em = $this->getDoctrine()->getManager();

			$carpooling->setUser($user);
			
			if ($req->request->get("form")["associatedGroup"] != NULL) {
				$carpooling->setAssociatedGroup($req->request->get("form")["associatedGroup"]);
			}
			
			$em->persist($carpooling);
			$em->flush();

			//$this->get('session')->getFlashBag->add('ajouter', 'Votre service est ajoutée');
			
			$carpooling_id = $carpooling->getId();

			return $this->redirect($this->generateUrl('ml_service_see_carpooling', array('user'=>$user,'carpooling' => $carpooling_id)));
		}
		
		return $this->render('MlServiceBundle:Service:add_carpooling.html.twig', array(
			'form' => $form->createView(),
		    'user' => $user));
	}

	public function deleteCarpoolingAction(/*Service $service*/) {
		/* Test connexion */
		$req = $this->get('request');		
		try {		
		    $login = $this->container->get('ml.session')->sessionExist($req);
		}
		catch (\Exception $e) {
		    return $this->redirect($this->generateUrl('ml_user_add'));		    
		}
		
		if ($login == NULL) {
			return $this->redirect($this->generateUrl('ml_user_add'));
		}
		
		$user = $this->getDoctrine()
				->getManager()
				->getRepository('MlUserBundle:User')
				->findOneByLogin($login);

	
		$em=$this->getDoctrine()->getManager();
		$service=$em->getRepository('MlServiceBundle:Carpooling')->findById('3');
		
		$em->remove($service[0]);
		$em->flush();

		//$this->get('session')->getFlashBag->add('supprimer','Votre service a été supprimé');
		return $this->redirect($this->generateUrl('ml_service_homepage'));
	}

	public function addCouchSurfingAction(){
		/* Test connexion */
		$req = $this->get('request');		
		try {		
		    $login = $this->container->get('ml.session')->sessionExist($req);
		}
		catch (\Exception $e) {
		    return $this->redirect($this->generateUrl('ml_user_add'));		    
		}
		
		if ($login == NULL) {
			return $this->redirect($this->generateUrl('ml_user_add'));
		}
		
		$user = $this->getDoctrine()
				->getManager()
				->getRepository('MlUserBundle:User')
				->findOneByLogin($login);
	
		$couchSurfing = new CouchSurfing;
		
		$form = $this->createForm(new CouchSurfingType(),$couchSurfing);


		if($req->getMethod() == 'POST'){
			//lien requête<->form
			$form->bind($req);
		
			$em = $this->getDoctrine()->getManager();

			$couchSurfing->setUser($user);
			
			if ($req->request->get("form")["associatedGroup"] != NULL) {
				$couchSurfing->setAssociatedGroup($req->request->get("form")["associatedGroup"]);
			}
			
			$em->persist($couchSurfing);
			$em->flush();

			//$this->get('session')->getFlashBag->add('ajouter', 'Votre service est ajoutée');
			
			$couchSurfing_id = $couchSurfing->getId();

			return $this->redirect($this->generateUrl('ml_service_see_carpooling', array('user'=>$user,'couchsurfing' => $couchSurfing_id)));
		}
		
		return $this->render('MlServiceBundle:Service:add_couchsurfing.html.twig', array(
			'form' => $form->createView(),
		    'user' => $user));
	}

	public function seeCouchSurfingAction($couchsurfing = null) {
		/* Test connexion */
		$req = $this->get('request');		
		try {		
		    $login = $this->container->get('ml.session')->sessionExist($req);
		}
		catch (\Exception $e) {
		    return $this->redirect($this->generateUrl('ml_user_add'));		    
		}
		
		if ($login == NULL) {
			return $this->redirect($this->generateUrl('ml_user_add'));
		}
		
		$user = $this->getDoctrine()
				->getManager()
				->getRepository('MlUserBundle:User')
				->findOneByLogin($login);
				
		$em = $this->getDoctrine()->getManager();
		$data_couchsurfing = $em->getRepository('MlServiceBundle:CouchSurfing')->findOneById($couchsurfing);
		
		/* Si le Service demandé n'existe pas */
		if ($data_couchsurfing == null){
			return $this->redirect($this->generateUrl('ml_service_homepage'));
		}
		
		if ($data_couchsurfing->getVisibility() == false) {
			return $this->redirect($this->generateUrl('ml_service_homepage'));
		}
		
		if($req->getMethod() != 'POST'){			
			return $this->render('MlServiceBundle:Service:see_couchsurfing.html.twig', array('user' => $user,'couchsurfing' => $data_couchsurfing));
		}
		else {				
			if ($user == $data_couchsurfing->getUser()) {
				return $this->redirect($this->generateUrl('ml_service_homepage'));
			}
			
			$couchSurfingUser = new CouchSurfingUser;
			
			$couchSurfingUser->setApplicant($user);
			$couchSurfingUser->setCouchsurfing($data_couchsurfing);
			
			$em->persist($couchSurfingUser);
			$em->flush();
			
			$data_couchsurfing->setVisibility(false);
			
			$em->persist($data_couchsurfing);
			$em->flush();

			return $this->redirect($this->generateUrl('ml_service_homepage'));
		}
	}
	
	public function deleteCouchsurfingAction(/*Service $service*/) {
		/* Test connexion */
		$req = $this->get('request');		
		try {		
		    $login = $this->container->get('ml.session')->sessionExist($req);
		}
		catch (\Exception $e) {
		    return $this->redirect($this->generateUrl('ml_user_add'));		    
		}
		
		if ($login == NULL) {
			return $this->redirect($this->generateUrl('ml_user_add'));
		}
		
		$user = $this->getDoctrine()
				->getManager()
				->getRepository('MlUserBundle:User')
				->findOneByLogin($login);

	
		$em=$this->getDoctrine()->getManager();
		$service=$em->getRepository('MlServiceBundle:CouchSurfing')->findById('3');
		
		$em->remove($service[0]);
		$em->flush();

		//$this->get('session')->getFlashBag->add('supprimer','Votre service a été supprimé');
		return $this->redirect($this->generateUrl('ml_service_homepage'));
	}

	public function addSaleAction(){
		/* Test connexion */
		$req = $this->get('request');		
		try {		
		    $login = $this->container->get('ml.session')->sessionExist($req);
		}
		catch (\Exception $e) {
		    return $this->redirect($this->generateUrl('ml_user_add'));		    
		}
		
		if ($login == NULL) {
			return $this->redirect($this->generateUrl('ml_user_add'));
		}
		
		$user = $this->getDoctrine()
				->getManager()
				->getRepository('MlUserBundle:User')
				->findOneByLogin($login);
	
		$sale = new Sale;
		
		$form = $this->createForm(new SaleType(),$sale);


		if($req->getMethod() == 'POST'){
			//lien requête<->form
			$form->bind($req);
		
			$em = $this->getDoctrine()->getManager();

			$sale->setUser($user);
			
			if ($req->request->get("form")["associatedGroup"] != NULL) {
				$sale->setAssociatedGroup($req->request->get("form")["associatedGroup"]);
			}
			
			$em->persist($sale);
			$em->flush();

			//$this->get('session')->getFlashBag->add('ajouter', 'Votre service est ajoutée');
			
			$sale_id = $sale->getId();

			return $this->redirect($this->generateUrl('ml_service_see_carpooling', array('user'=>$user,'sale' => $sale_id)));
		}
		
		return $this->render('MlServiceBundle:Service:add_sale.html.twig', array(
			'form' => $form->createView(),
		    'user' => $user));
	}

	public function seeSaleAction($sale = null) {
		/* Test connexion */
		$req = $this->get('request');		
		try {		
		    $login = $this->container->get('ml.session')->sessionExist($req);
		}
		catch (\Exception $e) {
		    return $this->redirect($this->generateUrl('ml_user_add'));		    
		}
		
		if ($login == NULL) {
			return $this->redirect($this->generateUrl('ml_user_add'));
		}
		
		$user = $this->getDoctrine()
				->getManager()
				->getRepository('MlUserBundle:User')
				->findOneByLogin($login);
				
		$em = $this->getDoctrine()->getManager();
		$data_sale = $em->getRepository('MlServiceBundle:Sale')->findOneById($sale);
		
		/* Si le Service demandé n'existe pas */
		if ($data_sale == null){
			return $this->redirect($this->generateUrl('ml_service_homepage'));
		}
		
		if ($data_sale->getVisibility() == false) {
			return $this->redirect($this->generateUrl('ml_service_homepage'));
		}
		
		if($req->getMethod() != 'POST'){			
			return $this->render('MlServiceBundle:Service:see_sale.html.twig', array('user' => $user,'sale' => $data_sale));
		}
		else {				
			if ($user == $data_sale->getUser()) {
				return $this->redirect($this->generateUrl('ml_service_homepage'));
			}
			
			$saleUser = new SaleUser;
			
			$saleUser->setApplicant($user);
			$saleUser->setCouchsurfing($data_sale);
			
			$em->persist($saleUser);
			$em->flush();
			
			$data_sale->setVisibility(false);
			
			$em->persist($data_sale);
			$em->flush();

			return $this->redirect($this->generateUrl('ml_service_homepage'));
		}
	}
	
	public function deleteSaleAction(/*Service $service*/) {
		/* Test connexion */
		$req = $this->get('request');		
		try {		
		    $login = $this->container->get('ml.session')->sessionExist($req);
		}
		catch (\Exception $e) {
		    return $this->redirect($this->generateUrl('ml_user_add'));		    
		}
		
		if ($login == NULL) {
			return $this->redirect($this->generateUrl('ml_user_add'));
		}
		
		$user = $this->getDoctrine()
				->getManager()
				->getRepository('MlUserBundle:User')
				->findOneByLogin($login);

	
		$em=$this->getDoctrine()->getManager();
		$service=$em->getRepository('MlServiceBundle:Sale')->findById('3');
		
		$em->remove($service[0]);
		$em->flush();

		//$this->get('session')->getFlashBag->add('supprimer','Votre service a été supprimé');
		return $this->redirect($this->generateUrl('ml_service_homepage'));
	}

}

