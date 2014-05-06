<?php

namespace Ml\HomeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class HomeController extends Controller
{
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
	
        return $this->redirect($this->generateUrl('ml_user_see'));
    }
}
