<?php

namespace Ml\AdministrationBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

/**
 * \class AdministrationController
 * Administration Controller extending Controller
 * This one is used to manage exchanges between users (services, groups, topics, comments, ...) and users themselves
 */
class AdministrationController extends Controller {

	/**
	 * Display all data (services, groups, topics, comments and users) and allow administrators to manage them
	 * @return Twig template MlAdministrationBundle:Administration:index.html.twig
	 */
    public function indexAction() {
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
		
		if ($user == NULL) {
			return $this->redirect($this->generateUrl('ml_user_add'));
		}
		
		if ($user->getModerator() == false) {
			return $this->redirect($this->generateUrl('ml_user_add'));
		}
		
		$em = $this->getDoctrine()->getManager();
		
		$members = $this->getDoctrine()
				->getManager()
				->getRepository('MlUserBundle:User')
				->findAll();
		
		if ($members == NULL) {
			$members = NULL;
		}
		
		$groups = $this->getDoctrine()
				->getManager()
				->getRepository('MlGroupBundle:Groupp')
				->findAll();
				
		if ($groups == NULL) {
			$groups = NULL;
		}
		
		$services = NULL;
		
		$carpoolings = $this->getDoctrine()
			->getRepository('MlServiceBundle:Carpooling')
			->findAll();
			
		if ($carpoolings != NULL) {
			foreach ($carpoolings as $key => $value) {
				$services[] = $value;
			}
		}
		
		$couchsurfings = $this->getDoctrine()
			->getRepository('MlServiceBundle:CouchSurfing')
			->findAll();
			
		if ($couchsurfings != NULL) {
			foreach ($couchsurfings as $key => $value) {
				$services[] = $value;
			}
		}
		
		$sales = $this->getDoctrine()
			->getRepository('MlServiceBundle:Sale')
			->findAll();
			
		if ($sales != NULL) {
			foreach ($sales as $key => $value) {
				$services[] = $value;
			}
		}
		
		$comments = $this->getDoctrine()
			->getRepository('MlForumBundle:TopicComment')
			->findAll();
			
		if ($comments == NULL) {
			$comments = NULL;
		}
		
		$topics = $this->getDoctrine()
			->getRepository('MlForumBundle:Topic')
			->findAll();
			
		$topics_final = NULL;
		$ratios = NULL;
		
		if ($topics == NULL) {
			$topics = NULL;
		}
		else {
			foreach ($topics as $key => $value) {
				$likes = $em->createQuery(
							"SELECT COUNT(tu.avis) as nb_likes
							FROM MlForumBundle:TopicUser tu
							WHERE tu.avis = 1
							AND tu.topic = :value")
						  ->setParameter('value', $value);
												
				$count_likes_initial = $likes->getResult();
			
				$count_likes = (int)$count_likes_initial[0]['nb_likes'];
				
				$dislikes = $em->createQuery(
							"SELECT COUNT(tu.avis) as nb_dislikes
							FROM MlForumBundle:TopicUser tu
							WHERE tu.avis != 1
							AND tu.topic = :value")
						    ->setParameter('value', $value);
							
												
				$count_dislikes_initial = $dislikes->getResult();
				
				$count_dislikes = (int)$count_dislikes_initial[0]['nb_dislikes'];
				
				$l_d = $count_likes - $count_dislikes;
					
				$ratios[$value->getId()] = $l_d;
			}
		
			arsort($ratios);
			
			foreach ($ratios as $key => $value) {
				$ratio_topic = $em->createQuery(
							"SELECT t
							FROM MlForumBundle:Topic t
							WHERE t.id = :value")
						    ->setParameter('value', ($key));
				
				if (is_array($ratio_topic->getResult())) {
					$topic_initial = $ratio_topic->getResult();
					$topic = $topic_initial[0];
				}
				else {
					$topic = $ratio_topic->getResult();
				}
			
				$topics_final[] = $topic;
			}
			
			rsort($ratios);
		}
	
        return $this->render('MlAdministrationBundle:Administration:index.html.twig', array(
						'user' => $user,
						'members' => $members,
						'groups' => $groups,
						'services' => $services,
						'comments' => $comments,
						'topics' => $topics_final,
						'ratios' => $ratios,));
    }
	
	/**
	 * Ban an user (set visible attribute to false)
	 * @return Redirection to ml_administration_homepage
	 */
	public function banAction() {
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
		
		if ($user == NULL) {
			return $this->redirect($this->generateUrl('ml_user_add'));
		}
		
		$em = $this->getDoctrine()->getManager();
		
		$user_id = $req->request->get("member_id");
		
		if ($user_id == NULL) {
			return $this->redirect($this->generateUrl('ml_user_add'));
		}
			
		$user_to_ban = $this->getDoctrine()
			->getRepository('MlUserBundle:User')
			->findOneById($user_id);
		
		if ($user_to_ban == NULL) {
			return $this->redirect($this->generateUrl(ml_user_add));
		}
		
		if ($user_to_ban->getVisible() == true) {
			if ($user_to_ban->getModerator() == true) {
				$user_to_ban->setModerator(false);
			}
			
			$user_to_ban->setVisible(false);
		}
		else {
			$user_to_ban->setVisible(true);
		}
		
		$em->persist($user_to_ban);
		$em->flush();
		
		return $this->redirect($this->generateUrl('ml_administration_homepage'));
	}
	
	/**
	 * Grant an user to moderator
	 * @return Redirection to ml_administration_homepage
	 */
	public function grantAction() {
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
		
		if ($user == NULL) {
			return $this->redirect($this->generateUrl('ml_user_add'));
		}
		
		$em = $this->getDoctrine()->getManager();
		
		$user_id = $req->request->get("member_id");
		
		if ($user_id == NULL) {
			return $this->redirect($this->generateUrl('ml_user_add'));
		}
			
		$user_to_grant = $this->getDoctrine()
			->getRepository('MlUserBundle:User')
			->findOneById($user_id);
		
		if ($user_to_grant == NULL) {
			return $this->redirect($this->generateUrl('ml_user_add'));
		}
		
		if ($user_to_grant->getModerator() == false) {
			$user_to_grant->setModerator(true);
		}
		else {
			$user_to_grant->setModerator(false);
		}
		
		$em->persist($user_to_grant);
		$em->flush();
		
		return $this->redirect($this->generateUrl('ml_administration_homepage'));
	}
	
	/**
	 * Grant an user to Master
	 * @return Redirection to ml_administration_homepage
	 */
	public function grantMasterAction() {
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
		
		if ($user == NULL) {
			return $this->redirect($this->generateUrl('ml_user_add'));
		}
		
		$em = $this->getDoctrine()->getManager();
		
		$user_id = $req->request->get("member_id");
		
		if ($user_id == NULL) {
			return $this->redirect($this->generateUrl('ml_user_add'));
		}
			
		$user_to_grant_master = $this->getDoctrine()
			->getRepository('MlUserBundle:User')
			->findOneById($user_id);
		
		if ($user_to_grant_master == NULL) {
			return $this->redirect($this->generateUrl(ml_user_add));
		}
					
		$user_to_grant_master->setMaster(true);
		
		if ($user_to_grant_master->getModerator() == false) {
			$user_to_grant_master->setModerator(true);
		}
		
		$em->persist($user_to_grant_master);
		$em->flush();
		
		return $this->redirect($this->generateUrl('ml_administration_homepage'));
	}
	
	/**
	 * Delete a group from database
	 * Set associatedGroup attribute from Services to NULL
	 * @return Redirection to ml_administration_homepage
	 */
	public function deleteGroupAction() {
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
		
		if ($user == NULL) {
			return $this->redirect($this->generateUrl('ml_user_add'));
		}
		
		$group_id = $req->request->get("group_id");
		
		if ($group_id == NULL) {
			return $this->redirect($this->generateUrl('ml_user_add'));
		}
		
		$group = $this->getDoctrine()
			->getRepository('MlGroupBundle:Groupp')
			->findOneById($group_id);
			
		if ($group == NULL) {
			return $this->redirect($this->generateUrl('ml_user_add'));
		}
		
		$em = $this->getDoctrine()->getManager();
	
		// Members and moderators of the group
		$group_user = $this->getDoctrine()
			->getRepository('MlGroupBundle:GroupUser')
			->findByGroupp($group);
		
		if ($group_user != NULL) {
			foreach ($group_user as $key => $value) {
				$em->remove($value);
				$em->flush();
			}
		}
		
		 // Set NULL associatedGroup to created services
		$associated_services = NULL;
		
		$associated_carpoolings = $this->getDoctrine()
			->getRepository('MlServiceBundle:Carpooling')
			->findByAssociatedGroup($group);
			
		if ($associated_carpoolings != NULL) {
			foreach ($associated_carpoolings as $key => $value) {
				$associated_services[] = $value;
			}
		}
		
		$associated_couchsurfings = $this->getDoctrine()
			->getRepository('MlServiceBundle:CouchSurfing')
			->findByAssociatedGroup($group);
			
		if ($associated_couchsurfings != NULL) {
			foreach ($associated_couchsurfings as $key => $value) {
				$associated_services[] = $value;
			}
		}
		
		$associated_sales = $this->getDoctrine()
			->getRepository('MlServiceBundle:Sale')
			->findByAssociatedGroup($group);
			
		if ($associated_sales != NULL) {
			foreach ($associated_sales as $key => $value) {
				$associated_services[] = $value;
			}
		}
		
		if ($associated_services != NULL) {
			foreach ($associated_services as $key => $value) {
				$value->setAssociatedGroup(NULL);
				
				$em->persist($value);
				$em->flush();
			}
		}

		// Group itself
		$em->remove($group);
		$em->flush();
		
		return $this->redirect($this->generateUrl('ml_administration_homepage'));
	}
	
	/**
	 * Delete a service from database
	 * @return Redirection to ml_administration_homepage
	 */
	public function deleteServiceAction() {
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
		
		if ($user == NULL) {
			return $this->redirect($this->generateUrl('ml_user_add'));
		}
		
		$em = $this->getDoctrine()->getManager();
		
		$service_id = $req->request->get("service_id");
		$service_type = $req->request->get("service_type");
		
		if ($service_id == NULL) {
			return $this->redirect($this->generateUrl('ml_user_add'));
		}
		
		if ($service_type == NULL) {
			return $this->redirect($this->generateUrl('ml_user_add'));
		}
			
		if ($service_type == "Carpooling") {
			$service = $this->getDoctrine()
				->getRepository('MlServiceBundle:Carpooling')
				->findOneById($service_id);
		}
		else if ($service_type == "Couchsurfing") {
			$service = $this->getDoctrine()
				->getRepository('MlServiceBundle:Couchsurfing')
				->findOneById($service_id);
		}
		else {
			$service = $this->getDoctrine()
				->getRepository('MlServiceBundle:Sale')
				->findOneById($service_id);
		}
			
		if ($service == NULL) {
			return $this->redirect($this->generateUrl(ml_user_add));
		}
		
		if ($service->getVisibility() == true) {
			$service->setVisibility(false);
		}
		else {
			$service->setVisibility(true);
		}

		$em->persist($service);
		$em->flush();
		
		return $this->redirect($this->generateUrl('ml_administration_homepage'));
	}
	
	/**
	 * Delete a comment from database
	 * @return Redirection to ml_administration_homepage
	 */
	public function deleteCommentAction() {
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
		
		if ($user == NULL) {
			return $this->redirect($this->generateUrl('ml_user_add'));
		}
		
		$topic_id = $req->request->get("topic_id");
		$comment_id = $req->request->get("comment_id");
		
		if ($topic_id == NULL) {
			return $this->redirect($this->generateUrl('ml_user_add'));
		}
		if ($comment_id == NULL) {
			return $this->redirect($this->generateUrl('ml_user_add'));
		}
		
		$topic = $this->getDoctrine()
			->getRepository('MlForumBundle:Topic')
			->findOneById($topic_id);
			
		if ($topic == NULL) {
			return $this->redirect($this->generateUrl('ml_user_add'));
		}
			
		$comment = $this->getDoctrine()
			->getRepository('MlForumBundle:Comment')
			->findOneById($comment_id);
		
		if ($comment == NULL) {
			return $this->redirect($this->generateUrl('ml_user_add'));
		}
			
		$topic_comment = $this->getDoctrine()
			->getRepository('MlForumBundle:TopicComment')
			->findOneBy(array('comment' => $comment, 'topic' => $topic));
		
		if ($topic_comment == NULL) {
			return $this->redirect($this->generateUrl(ml_user_add));
		}
		
		$em = $this->getDoctrine()->getManager();
		$em->remove($topic_comment);
		$em->flush();
		
		$em->remove($comment);
		$em->flush();
		
		return $this->redirect($this->generateUrl('ml_administration_homepage'));
	}
	
	/**
	 * Delete a topic from database
	 * @return Redirection to ml_administration_homepage
	 */
	public function deleteTopicAction() {
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
		
		if ($user == NULL) {
			return $this->redirect($this->generateUrl('ml_user_add'));
		}
		
		$topic_id = $req->request->get("topic_id");
		
		if ($topic_id == NULL) {
			return $this->redirect($this->generateUrl('ml_user_add'));
		}
		
		$topic = $this->getDoctrine()
			->getRepository('MlForumBundle:Topic')
			->findOneById($topic_id);
			
		if ($topic == NULL) {
			return $this->redirect($this->generateUrl('ml_user_add'));
		}
			
		$topic_comment = $this->getDoctrine()
			->getRepository('MlForumBundle:TopicComment')
			->findByTopic($topic);
		
		$em = $this->getDoctrine()->getManager();
		
		if ($topic_comment != NULL) {
			foreach ($topic_comment as $key => $value) {	
				$comments = $this->getDoctrine()
					->getRepository('MlForumBundle:Comment')
					->findById($value->getComment()->getId());
			
				$em->remove($value);
				$em->flush();
				
				foreach ($comments as $keyy => $valuee) {
					$em->remove($valuee);
					$em->flush();
				}
			}
		}
		
		$topic_user = $this->getDoctrine()
			->getRepository('MlForumBundle:TopicUser')
			->findByTopic($topic);
		
		if ($topic_user != NULL) {
			foreach ($topic_user as $key => $value) {				
				$em->remove($value);
				$em->flush();
			}
		}
		
		$em->remove($topic);
		$em->flush();
		
		return $this->redirect($this->generateUrl('ml_administration_homepage'));
	}
}
