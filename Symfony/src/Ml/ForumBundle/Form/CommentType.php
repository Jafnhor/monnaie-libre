<?php

namespace Ml\ForumBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Comment form
 */
class CommentType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('message', 'textarea', array(
											'label' => "Commentaire"))
        ;
    }
    
    /**
	 * Set Default options
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Ml\ForumBundle\Entity\Comment'
        ));
    }

    /**
	 * Get name
     * @return string
     */
    public function getName()
    {
        return 'ml_forumbundle_comment';
    }
}
