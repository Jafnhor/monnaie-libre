<?php

namespace Ml\TransactionBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class EvaluationController extends Controller {
    public function indexAction() {
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

		$basic = $this->getDoctrine()
			->getRepository('MlTransactionBundle:BasicEval')
			->findBy(array('subscriber'=>$user,'payed'=>false));
			
		$carpooling = $this->getDoctrine()
			->getRepository('MlTransactionBundle:CarpoolingEval')
			->findBy(array('subscriber'=>$user,'payed'=>false));	
			
		$couchsurfing = $this->getDoctrine()
			->getRepository('MlTransactionBundle:CouchsurfingEval')
			->findBy(array('subscriber'=>$user,'payed'=>false));
			
		$sale = $this->getDoctrine()
			->getRepository('MlTransactionBundle:SaleEval')
			->findBy(array('subscriber'=>$user,'payed'=>false));
		
		if ($basic == NULL) {
			$basic = NULL;
		}
		
		if ($carpooling == NULL) {
			$carpooling = NULL;
		}
		
		if ($couchsurfing == NULL) {
			$couchsurfing = NULL;
		}
		
		if ($sale == NULL) {
			$sale = NULL;
		}
	    
		return $this->render('MlTransactionBundle:Transaction:index_eval.html.twig',array('user'=>$user,'basic'=>$basic,'carpooling'=>$carpooling,'couchsurfing'=>$couchsurfing,'sale'=>$sale));
    }

    public function evaluationAction($serviceType,$id) {
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

		switch($serviceType) {
		    case 'basic':
		        $eval = $this->getDoctrine()
			        ->getRepository('MlTransactionBundle:BasicEval')
			        ->findOneById($id);		    
		        break;
		    case 'carpooling':
		        $eval = $this->getDoctrine()
			        ->getRepository('MlTransactionBundle:CarpoolingEval')
			        ->findOneById($id);		    
		        break;
		    case 'couchsurfing':
		        $eval = $this->getDoctrine()
			        ->getRepository('MlTransactionBundle:CouchsurfingEval')
			        ->findOneById($id);		    
		        break;
		    case 'sale':
		        $eval = $this->getDoctrine()
			        ->getRepository('MlTransactionBundle:SaleEval')
			        ->findOneById($id);
		        break;
		    default:
		        $eval = null;
		        break;
		}
		
		if($eval == null) { // Si le service n'existe pas
		    return $this->redirect($this->generateUrl('ml_transaction_eval_index'));
		}
		
		if($req->getMethod() == 'POST') { // Si on a noté
		    $ret = $eval->getSubscriber()->getAccount()->payment($eval->getOwner()->getAccount(),
		                                                  $eval->getService()->getPrice(),
		                                                  'Paiement du service '.$eval->getService()->getTitle());
		    $eval->setPayed(true);
		    $eval->setEval($req->request->get('note'));
		    
		    $this->getDoctrine()->getManager()->persist($eval);
		    $this->getDoctrine()->getManager()->persist($ret);
		    $this->getDoctrine()->getManager()->persist($eval->getOwner()->getAccount());
		    $this->getDoctrine()->getManager()->persist($eval->getSubscriber()->getAccount());
		    $this->getDoctrine()->getManager()->flush();
		    
		    $this->evalKarma($eval->getOwner());
		    
		    return $this->redirect($this->generateUrl('ml_transaction_eval_index'));
		}
		
	    return $this->render('MlTransactionBundle:Transaction:evaluation.html.twig',array('user'=>$user,'evaluation'=>$eval,'serviceType'=>$serviceType));
    }
    
    public function evalKarma($user) {
        $basic = $this->getDoctrine()
	            ->getRepository('MlTransactionBundle:BasicEval')
	            ->findBy(array('owner'=>$user,'payed'=>true));		
        $carpooling = $this->getDoctrine()
	            ->getRepository('MlTransactionBundle:CarpoolingEval')
	            ->findBy(array('owner'=>$user,'payed'=>true));		
        $couchsurfing = $this->getDoctrine()
	            ->getRepository('MlTransactionBundle:CouchsurfingEval')
	            ->findBy(array('owner'=>$user,'payed'=>true));
        $sale = $this->getDoctrine()
	            ->getRepository('MlTransactionBundle:SaleEval')
	            ->findBy(array('owner'=>$user,'payed'=>true));
	            
	    $evaluations = array_merge($basic,$carpooling,$couchsurfing,$sale);
	    
	    $karma = 0;
	    
	    foreach($evaluations as $eval) {
	        $karma += $eval->getEval()*10;
	    }
	    
	    $karma /= count($evaluations);
	    
	    $user->setKarma($karma);
	    
	    $this->getDoctrine()->getManager()->persist($user);
	    $this->getDoctrine()->getManager()->flush();
    }
}
