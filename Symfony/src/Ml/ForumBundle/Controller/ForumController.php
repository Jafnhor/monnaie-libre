<?php

namespace Ml\ForumBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Ml\ForumBundle\Entity\Topic;
use Ml\ForumBundle\Form\TopicType;
use Ml\ForumBundle\Entity\Comment;
use Ml\ForumBundle\Form\CommentType;
use Ml\ForumBundle\Entity\TopicComment;
use Ml\ForumBundle\Entity\TopicUser;

class ForumController extends Controller
{
    public function indexAction() {
		$request = $this->get('request');
		
		$em = $this->getDoctrine()->getManager();
		
		$message = NULL;
		
        try {		
		    $login = $this->container->get('ml.session')->sessionExist($request);
		}
		catch (\Exception $e) {
		    return $this->redirect($this->generateUrl('ml_user_add'));		    
		}
		
		if ($login == NULL) {
			return $this->redirect($this->generateUrl('ml_user_add'));
		}
		
		$user = $this->getDoctrine()
			->getRepository('MlUserBundle:User')
			->findOneByLogin($login);
		
		if ($user == NULL) {
			return $this->redirect($this->generateUrl('ml_user_add'));
		}
		
		$topics = $this->getDoctrine()
			->getRepository('MlForumBundle:Topic')
			->findAll();
			
		$topics_final = NULL;
		$ratios = NULL;
		$last_message = NULL;
		
		if ($topics == NULL) {
			$message = "No topics already created, be first, create one.";
		}
		else {
			foreach ($topics as $key => $value) {
				$likes = $em->createQuery(
							"SELECT COUNT(tu.avis) as nb_likes
							FROM MlForumBundle:TopicUser tu
							WHERE tu.avis = 1
							AND tu.topic = :value")
						  ->setParameter('value', $value);
												
				$count_likes = (int)$likes->getResult()[0]['nb_likes'];
				
				$dislikes = $em->createQuery(
							"SELECT COUNT(tu.avis) as nb_dislikes
							FROM MlForumBundle:TopicUser tu
							WHERE tu.avis != 1
							AND tu.topic = :value")
						    ->setParameter('value', $value);
							
												
				$count_dislikes = (int)$dislikes->getResult()[0]['nb_dislikes'];
				
				$l_d = $count_likes - $count_dislikes;
				
				$name = $value->getAuthor()->getLogin();
				$date = $value->getCreationDate();
					
				$ratios[$value->getId()] = $l_d;
				$last_message[$value->getId()][0] = $name;
				$last_message[$value->getId()][1] = $date;
			}
		
			arsort($ratios);
			
			foreach ($ratios as $key => $value) {
				$ratio_topic = $em->createQuery(
							"SELECT t
							FROM MlForumBundle:Topic t
							WHERE t.id = :value")
						    ->setParameter('value', ($key));
				
				if (is_array($ratio_topic->getResult())) {
					$topic = $ratio_topic->getResult()[0];
				}
				else {
					$topic = $ratio_topic->getResult();
				}
			
				$topics_final[] = $topic;
			}
			
			rsort($ratios);
			rsort($last_message);
		}
	
        return $this->render('MlForumBundle:Forum:index.html.twig', array(
			'user' => $user,
			'message' => $message,
			'topics' => $topics_final,
			'ratios' => $ratios,
			'last_message' => $last_message));
    }
	
	public function newTopicAction() {
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
		
		$user = $this->getDoctrine()
			->getRepository('MlUserBundle:User')
			->findOneByLogin($login);
		
		if ($user == NULL) {
			return $this->redirect($this->generateUrl('ml_user_add'));
		}
		
		$topic = new Topic;
		
		$form = $this->createForm(new TopicType(),$topic);
		
		if ($request->getMethod() == 'POST') {
			//lien requête<->form
			$form->bind($request);
		
			$em = $this->getDoctrine()->getManager();

			$topic->setAuthor($user);
			
			$em->persist($topic);
			$em->flush();
			
			$topic_id = $topic->getId();

			return $this->redirect($this->generateUrl('ml_forum_see_topic', array('topic' => $topic_id)));
		}
		
		return $this->render('MlForumBundle:Forum:new_topic.html.twig', array(
			'user' => $user,
			'form' => $form->createView()));
	}
	
	public function seeTopicAction($topic = NULL) {
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
		
		$user = $this->getDoctrine()
			->getRepository('MlUserBundle:User')
			->findOneByLogin($login);
		
		if ($user == NULL) {
			return $this->redirect($this->generateUrl('ml_user_add'));
		}
		
		if ($topic == NULL) {
			return $this->redirect($this->generateUrl('ml_user_add'));
		}
		
		$topic_data = $this->getDoctrine()
			->getRepository('MlForumBundle:Topic')
			->findOneById($topic);
			
		if ($topic_data == NULL) {
			return $this->redirect($this->generateUrl('ml_user_add'));
		}
		
		$comment = new Comment;
		
		$form = $this->createForm(new CommentType(),$comment);
		
		$em = $this->getDoctrine()->getManager();
		
		if ($request->getMethod() == 'POST') {
			$new_topic = $request->request->get("createNewTopic");
			$like = $request->request->get("liker");
			$dislike = $request->request->get("disliker");
			
			if ($new_topic != NULL) {
				//lien requête<->form
				$form->bind($request);

				$comment->setAuthor($user);
				
				$em->persist($comment);
				$em->flush();
				
				$topiccomment = new TopicComment;
				
				$topiccomment->setTopic($topic_data);
				$topiccomment->setComment($comment);
				
				$em->persist($topiccomment);
				$em->flush();
			}
			else if ($like != NULL) {
				$topic_user_data = $this->getDoctrine()
					->getRepository('MlForumBundle:TopicUser')
					->findOneBy(array("topic" => $topic_data, "user" => $user));
				
				if ($topic_user_data == NULL) {
					$topic_user = new TopicUser;
				
					$topic_user->setTopic($topic_data);
					$topic_user->setUser($user);
					$topic_user->setAvis(true);
					
					$em->persist($topic_user);
					$em->flush();
				}
				else {
					$topic_user_data->setAvis(true);
					
					$em->persist($topic_user_data);
					$em->flush();
				}
			}
			else {
				$topic_user_data = $this->getDoctrine()
					->getRepository('MlForumBundle:TopicUser')
					->findOneBy(array("topic" => $topic_data, "user" => $user));
				
				if ($topic_user_data == NULL) {
					$topic_user = new TopicUser;
				
					$topic_user->setTopic($topic_data);
					$topic_user->setUser($user);
					$topic_user->setAvis(false);
					
					$em->persist($topic_user);
					$em->flush();
				}
				else {
					$topic_user_data->setAvis(false);
					
					$em->persist($topic_user_data);
					$em->flush();
				}
			}
				
			return $this->redirect($this->generateUrl('ml_forum_see_topic', array('topic' => $topic)));
		}
		else {
			if ($topic_data->getAuthor() != $user) {
				$topic_data->setNbViews(($topic_data->getNbViews() + 1));
					
				$em->persist($topic_data);
				$em->flush();
			}
		}
		
		$topiccomments = $this->getDoctrine()
			->getRepository('MlForumBundle:TopicComment')
			->findByTopic($topic_data);
	
		return $this->render('MlForumBundle:Forum:see_topic.html.twig', array(
			'user' => $user,
			'topic' => $topic_data,
			'form' => $form->createView(),
			'topiccomments' => $topiccomments));
	}
}