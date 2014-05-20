<?php

namespace Ml\UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * User form
 */
class UserType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            //->add('premium','boolean')
            ->add('lastName','text', array(
											'label' => "Nom"))
            ->add('firstName','text', array(
											'label' => "Prénom"))
            ->add('login','text', array(
											'label' => "Login"))
            ->add('password','password', array(
											'label' => "Mot de passe"))
        ;
    }
    
    /**
	 * Set Default options
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Ml\UserBundle\Entity\User'
        ));
    }

    /**
	 * Get name
     * @return string
     */
    public function getName()
    {
        return 'ml_userbundle_user';
    }
}