<?php

namespace Ml\HomeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Home Controller extending Controller
 * This one is used for redirections and to access homepage
 */
class HomeController extends Controller
{
	/**
	 * Redirect to ml_user_see if connected, else, display MlHomeBundle:Home:index.html.twig
	 * @return Twig template MlHomeBundle:Home:index.html.twig
	 */
    public function indexAction()
    {
		$req = $this->get('request');
		
		$login = NULL;
		
        try {		
		    $login = $this->container->get('ml.session')->sessionExist($req);
		}
		catch (\Exception $e) {
			/*
				return $this->redirect($this->generateUrl('ml_user_add'));		
			*/
			$this->render('MlHomeBundle:Home:index.html.twig');
		}
		
		if ($login == NULL) {
			/*
				return $this->redirect($this->generateUrl('ml_user_add'));
			*/
			return $this->render('MlHomeBundle:Home:index.html.twig');
		}
		else {
			$user = $this->getDoctrine()
				->getRepository('MlUserBundle:User')
				->findOneBy(array("login" => $login, "visible" => true));
			
			if ($user != NULL) {
				return $this->redirect($this->generateUrl('ml_user_see'));
			} else {
				return $this->render('MlHomeBundle:Home:index.html.twig');
			}
		}
    }
	
	/**
	 * Display MlHomeBundle:Home:developers.html.twig
	 * @return Twig template MlHomeBundle:Home:developers.html.twig
	 */
	public function developersAction()
    {	
		return $this->render('MlHomeBundle:Home:developers.html.twig');
    }
}
