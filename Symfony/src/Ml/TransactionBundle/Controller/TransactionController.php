<?php

namespace Ml\TransactionBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Ml\TransactionBundle\Entity\Account;
use Ml\TransactionBundle\Entity\Transaction;
use Ml\TransactionBundle\Exception\TransactionException;
use Ml\UserBundle\Entity\User;

class TransactionController extends Controller
{
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

		// On récupère les transactions sortantes
		$outTransactions = $this->getDoctrine()
							->getManager()
							->getRepository('MlTransactionBundle:Transaction')
							->findBy(array("debitedAccount" => $user->getAccount()),array('date' => 'DESC'));
		
		// Puis les entrantes
		$inTransactions = $this->getDoctrine()
							->getManager()
							->getRepository('MlTransactionBundle:Transaction')
							->findBy(array("creditedAccount" => $user->getAccount()),array('date' => 'DESC'));
		
		if ($outTransactions == NULL) {
			$outTransactions = NULL;
		}
		
		if ($inTransactions == NULL) {
			$inTransactions = NULL;
		}
		
		return $this->render('MlTransactionBundle:Transaction:index.html.twig', array(
		  'outTransactions' => $outTransactions,
		  'inTransactions' => $inTransactions,
		  'user' => $user));
    }
    
    public function paymentAction() {
        /* Test connexion */
		$req = $this->get('request');
		
        try {		
		    $login = $this->container->get('ml.session')->sessionExist($req);
		}
		catch (\Exception $e) {
		    return $this->redirect($this->generateUrl('ml_user_add'));		    
		}
		
		$all_users = $this->getDoctrine()
			->getRepository('MlUserBundle:User')
			->findAll();
			
		if ($all_users != NULL) {
			for ($i = 0; $i<sizeof($all_users); $i++) {
				// Add all users except current user
				if ($all_users[$i]->getLogin() != $login) {
					$users[$all_users[$i]->getLogin()] = $all_users[$i]->getLogin();
				}
			}
		}
			
        $user = $this->getDoctrine()
			->getRepository('MlUserBundle:User')
			->findOneByLogin($login);
			
			// Si un paiement est effectué
			if($req->getMethod() == 'POST'){
			    $recipient = $this->getDoctrine()
						->getRepository('MlUserBundle:User')
						->findOneBy(array('login' => $req->request->get('recipient')));
												
				$amount = $req->request->get('amount');
				$flag = $req->request->get('flag');
						
			    if($this->container->get('ml.reservation')->canPay($user,$amount,$this->getDoctrine()->getManager())) {
				
				    if($recipient != null) {
					    try {
						    $account = &$recipient->getAccount();
						    $ret = $user->getAccount()->payment($account,$amount,$flag);
						    $this->getDoctrine()->getManager()->persist($ret);
						    $this->getDoctrine()->getManager()->persist($user->getAccount());
						    $this->getDoctrine()->getManager()->persist($account);
						    $this->getDoctrine()->getManager()->flush();
						
		       				return $this->redirect($this->generateUrl('ml_transaction_homepage'));		
					    }
					    catch(\Exception $e) {
						    return $this->render('MlTransactionBundle:Transaction:payment.html.twig', array('user'=>$user,'users'=>$users,'error'=>$e->getMessage()));
					    }
				    }
				    else {	
				        return $this->render('MlTransactionBundle:Transaction:payment.html.twig', array('user'=>$user,'users'=>$users,'error'=>'L\'utilisateur '.$req->request->get('recipient').' n\'existe pas.'));
				    }
			        
			    }
			    else {	
			        return $this->render('MlTransactionBundle:Transaction:payment.html.twig', array('user'=>$user,'users'=>$users,'error'=>'Vous n\'avez pas les moyens d\'effectuer cette transaction.'));
			    }
			}
		
		return $this->render('MlTransactionBundle:Transaction:payment.html.twig', array('user'=>$user,'users'=>$users));
    }
}
