<?php

namespace Ml\ForumBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Topic form
 */
class TopicType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', 'text', array(
									'label' => 'Nom'))
            ->add('description')
        ;
    }
    
    /**
	 * Set Default options
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Ml\ForumBundle\Entity\Topic'
        ));
    }

    /**
	 * Get name
     * @return string
     */
    public function getName()
    {
        return 'ml_forumbundle_topic';
    }
}
