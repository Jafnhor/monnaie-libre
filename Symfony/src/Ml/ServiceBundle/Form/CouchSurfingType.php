<?php

namespace Ml\ServiceBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CouchSurfingType extends AbstractType
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
            ->add('location', ' ', array(
										'label' => "Lieu"))
            ->add('dateStart', ' ', array(
										'label' => "Date d'arrivée prévue"))
            ->add('dateEnd', ' ', array(
										'label' => "Date de départ prévue"))
            ->add('hourStart', ' ', array(
										'label' => "Heure d'arrivée"))
            ->add('hourEnd', ' ', array(
										'label' => "Heure de départ"))
            ->add('limitGuest', 'choice', array( 
										'label' => "Invités ?",
										'choices' => array(true => "Oui", false => "Non")))
            ->add('limitNumberOfGuest', 'integer', array(
													'label' => "Nombre d'invités (maximum)",
													'required' => false))
			->add('associatedGroup', 'choice', array( 
													'label' => "Groupes associé",
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
            'data_class' => 'Ml\ServiceBundle\Entity\CouchSurfing'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'ml_servicebundle_couchsurfing';
    }
}
