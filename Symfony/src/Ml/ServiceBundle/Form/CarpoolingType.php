<?php

namespace Ml\ServiceBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\HttpFoundation\Response;

class CarpoolingType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
		global $kernel;
	
		/* Test connexion */
		$req = $kernel
			    ->getContainer()
				->get('request');		
		try {		
		    $login = $kernel
			    ->getContainer()
				->get('ml.session')
				->sessionExist($req);
		}
		catch (\Exception $e) {
		    return $kernel
			    ->getContainer()
				->redirect($kernel
			    ->getContainer()->generateUrl('ml_user_add'));		    
		}
		
		if ($login == NULL) {
			return $kernel
			    ->getContainer()
				->redirect($kernel
			    ->getContainer()->generateUrl('ml_user_add'));
		}
		
		$user = $kernel
			    ->getContainer()
			    ->get('doctrine.orm.entity_manager')
				->getRepository('MlUserBundle:User')
				->findOneByLogin($login);
	
		$groups_user = $kernel
				  ->getContainer()
				  ->get('doctrine.orm.entity_manager')
				  ->getRepository('MlGroupBundle:GroupUser')
			      ->findByUser($user);
		
		$groups_administrator = $kernel
				  ->getContainer()
				  ->get('doctrine.orm.entity_manager')
				  ->getRepository('MlGroupBundle:Groupp')
			      ->findByAdministrator($user);
		
		if ($groups_user == NULL) {
			$groups_user = NULL;
		}
		
		if ($groups_administrator == NULL) {
			$groups_administrator = NULL;
		}
		
		$groups_name = NULL;
		
		if ($groups_user != NULL) {
			foreach ($groups_user as $key => $value) {
				$groups[] = $value->getGroupp();
			}
			
			foreach ($groups as $key => $value) {
				$groups_name[$value->getName()] = $value->getName();
			}
		}
		
		if ($groups_administrator != NULL) {
			foreach ($groups_administrator as $key => $value) {
				$groups_a[] = $value;
			}
			
			foreach ($groups_a as $key => $value) {
				$groups_name[$value->getName()] = $value->getName();
			}
		}
	
        $builder
            ->add('title', ' ', array(
										'label' => "Titre"))
            ->add('comment', ' ', array(
										'label' => "Commentaire"))
            ->add('price', ' ', array(
										'label' => "Prix"))
            ->add('departure', ' ', array(
											'label' => "Départ de"))
            ->add('arrival', ' ', array(
										'label' => "Arrivée "))
            ->add('meetingPoint', ' ', array(
												'label' => "Lieu de rendez-vous"))
            ->add('arrivalPoint', ' ', array(
												'label' => "Lieu de dépose"))
            ->add('bends', ' ', array(
										'label' => "Détours"))
            ->add('departureDate', 'date', array(
													'label' => "Date de départ"))
            ->add('estimatedDuration', 'time', array(
														'label' => "Durée estimée"))
            ->add('estimatedDistance', 'integer', array(
														'label' => "Distance estimée (km)"))
            ->add('packageTransport', ' ', array(
													'label' => "Transport de colis"))
            ->add('packageSize', 'integer', array(
													'label' => "Taille des bagages",
													'required' => false))
            ->add('car')
            ->add('smoker', 'choice', array(
										'label' => "Fumeur",
										'choices' => array(true => "Oui", false => "Oui")))
            ->add('pets', 'choice', array( 
										'label' => "Animaux",
										'choices' => array(true => "Oui", false => "Non")))
            ->add('music', 'choice', array( 
										'label' => "Musique",
										'choices' => array(true => "Oui", false => "Non")))
			->add('associatedGroup', 'choice', array( 
													'label' => "Groupe associé",
													'choices' => $groups_name,
													'required' => false,
													'mapped' => false))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Ml\ServiceBundle\Entity\Carpooling'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'ml_servicebundle_carpooling';
    }
}
