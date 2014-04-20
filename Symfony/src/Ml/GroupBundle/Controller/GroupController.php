<?php
namespace Ml\GroupBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Ml\GroupBundle\Entity\Groupp;
use Ml\GroupBundle\Entity\GroupUser;

class GroupController extends Controller {	

	public function indexAction() {
		$request = $this->get('request');
		
        try {		
		    $login = $this->container->get('ml.session')->sessionExist($request);
		}
		catch (\Exception $e) {
		    return $this->redirect($this->generateUrl('ml_user_add'));		    
		}
		
		if ($login == NULL) {
			return $this->redirect($this->generateUrl('ml_user_add'));
		}
		
		$current_user = $this->getDoctrine()
			->getRepository('MlUserBundle:User')
			->findOneByLogin($login);
		
		$groups = $this->getDoctrine()
			->getRepository('MlGroupBundle:Groupp')
			->findAll();
		
		$message = NULL;
		
		if ($groups == NULL) {
			$groups = NULL;
		}
		
		if ($groups == NULL) {
			$message = "No group in database";
		}
		
		return $this->render('MlGroupBundle:Group:groups.html.twig', array(
			'user' => $current_user,
			'groups' => $groups,
			'message' => $message));
	}
	
	public function creationGroupAction() {
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
		
		$current_user = $this->getDoctrine()
				->getRepository('MlUserBundle:User')
				->findOneByLogin($login);
		
		$group = new Groupp;
		
		// Get all users from database in order that the group's creator can add members to the group
		$users = $this->getDoctrine()
				->getRepository('MlUserBundle:User')
				->findAll();
		
		if ($users != NULL) {
			for ($i = 0; $i<sizeof($users); $i++) {
				// Add all users except current user
				if ($users[$i]->getLogin() != $login) {
					$users_login[$users[$i]->getLogin()] = $users[$i]->getLogin();
				}
			}
			
			// If there are users in database we display them
			if(isset($users_login)) {
				$form = $this->createFormBuilder($group)
							 ->add('name', 'text')
							 ->add('description', 'text')
							 ->add('users', 'choice', array(
														'choices' => $users_login,
														'multiple' => true,
														'required' => false,
														'mapped' => false))
							 ->getForm();
							 
				$group_already_exist = $this->getDoctrine()
					->getRepository('MlGroupBundle:Groupp')
					->findOneByName($req->request->get("form")['name']);
				
				if($group_already_exist != NULL) {
						return $this->render('MlGroupBundle:Group:creation_group.html.twig', array(
						  'form' => $form->createView(),
						  'user' => $current_user,
						  'error' => "A group with the specified name already exist, please choose another one."));
				}

				if ($req->getMethod() == 'POST') {	
				    // Link Request <-> Form
				    $form->bind($req);
			  
					$group->setAdministrator($current_user);

					// Save object $group in database
					$em = $this->getDoctrine()->getManager();
					$em->persist($group);
					$em->flush();
					
					// If creator add members
					if(isset($req->request->get("form")['users'])) {		
						$group_id = $this->getDoctrine()
							->getRepository('MlGroupBundle:Groupp')
							->findOneByName($req->request->get("form")['name']);
							
						// Add members to the group
						for($i = 0; $i < sizeof($req->request->get("form")['users']); $i++) {
							  $groupUser[$i] = new GroupUser;
							  
							  $user_id = $this->getDoctrine()
								->getRepository('MlUserBundle:User')
								->findOneByLogin($req->request->get("form")['users'][$i]);

							  // Link to the group which is here always the same
							  $groupUser[$i]->setGroupp($group_id);
							  // Link to the user which change at each loop
							  $groupUser[$i]->setUser($user_id);
							  // By default users are accepted
							  $groupUser[$i]->setAccepted(true);

							  $em->persist($groupUser[$i]);
							}
					
							$em->flush();
						}

					return $this->redirect($this->generateUrl('ml_home_homepage'));
				}
			}
			// No users in database
			else {
				$form = $this->createFormBuilder($group)
						 ->add('name', 'text')
						 ->add('description', 'text')
						 ->getForm();

				if ($req->getMethod() == 'POST') {	
					$form->bind($req);
					
					$group_already_exist = $this->getDoctrine()
					->getRepository('MlGroupBundle:Groupp')
					->findOneByName($req->request->get("form")['name']);
					
					if($group_already_exist != NULL) {
							return $this->render('MlGroupBundle:Group:creation_group.html.twig', array(
							  'form' => $form->createView(),
							  'user' => $current_user,
							  'error' => "A group with the specified name already exist, please choose another one."));
					}
			  
					$group->setAdministrator($current_user);

					$em = $this->getDoctrine()->getManager();
					$em->persist($group);
					$em->flush();

					return $this->redirect($this->generateUrl('ml_home_homepage'));

				}
			}	
		}
		else {
			$form = $this->createFormBuilder($group)
					 ->add('name', 'text')
					 ->add('description', 'text')
					 ->getForm();

			if ($req->getMethod() == 'POST') {
				$form->bind($req);
				
				$group_already_exist = $this->getDoctrine()
					->getRepository('MlGroupBundle:Groupp')
					->findOneByName($req->request->get("form")['name']);
				
				if($group_already_exist != NULL) {
						return $this->render('MlGroupBundle:Group:creation_group.html.twig', array(
						  'form' => $form->createView(),
						  'user' => $current_user,
						  'error' => "A group with the specified name already exist, please choose another one."));
				}
		  
				$group->setAdministrator($current_user);

				$em = $this->getDoctrine()->getManager();
				$em->persist($group);
				$em->flush();

				return $this->redirect($this->generateUrl('ml_home_homepage'));

			}
		}

		return $this->render('MlGroupBundle:Group:creation_group.html.twig', array(
		  'form' => $form->createView(),
		  'user' => $current_user));
	}
	
	public function displayGroupAction($group_id = null) {
		$request = $this->get('request');
		
        try {		
		    $login = $this->container->get('ml.session')->sessionExist($request);
		}
		catch (\Exception $e) {
		    return $this->redirect($this->generateUrl('ml_user_add'));		    
		}
		
		if ($login == NULL) {
			return $this->redirect($this->generateUrl('ml_user_add'));
		}
		
		$current_user = $this->getDoctrine()
			->getRepository('MlUserBundle:User')
			->findOneByLogin($login);
		
		$groups_users = NULL;
		
		$group_data = $this->getDoctrine()
			->getRepository('MlGroupBundle:Groupp')
			->findOneById($group_id);
			
		if ($group_data != NULL) {
			$req_groups_users = $this->getDoctrine()
				->getRepository('MlGroupBundle:GroupUser')
				->findByGroupp($group_data);
			
			foreach ($req_groups_users as $key => $value) {
				$group_users[] = $value;
			}
			
			$administrator_group_data = $this->getDoctrine()
					->getRepository('MlUserBundle:User')
					->findOneByLogin($group_data->getAdministrator()->getLogin());
					
			$is_member = false;
					
			if (isset($administrator_group_data)) {
				if ($administrator_group_data == $current_user) {
					$is_member = true;
				}
			}
			
			if ($is_member == false) {
				if (isset($groups_users)) {
					foreach ($groups_users as $key => $value) {
						if ($value->getUser()->getLogin() == $login) {
							$is_member = true;
						}
					}
				}
			}
		}
		
		if (!isset($group_users)) {
			$group_users = NULL;
		}
			
		if ($group_data == NULL) {
				return $this->render('MlGroupBundle:Group:display_group.html.twig', array(
						'user' => $current_user,
						'message' => $group_id, 
						'group_id' => $group_id));		
		} 
		else {
			return $this->render('MlGroupBundle:Group:display_group.html.twig', array(
						'user' => $current_user,
						'members' => $group_users,
						'group' => $group_data, 
						'group_id' => $group_id,
						'administrator_group_data' => $administrator_group_data,
						'is_member' => $is_member));	
		}
	}
}