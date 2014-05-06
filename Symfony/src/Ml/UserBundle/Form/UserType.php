<?php

namespace Ml\UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

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
											'label' => 'Last Name'))
            ->add('firstName','text', array(
											'label' => 'First Name'))
            ->add('login','text', array(
											'label' => 'Login'))
            ->add('password','password', array(
											'label' => 'Password'))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Ml\UserBundle\Entity\User'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'ml_userbundle_user';
    }
}
