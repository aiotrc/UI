<?php

namespace SmartCity\UserBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ActionGroupType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('code', 'text', array(
                    'label'=>'label.code', 
                )
            )
            ->add('title', 'text', array(
                    'label'=>'label.title', 
                    'translation_domain' => 'labels'
                )
            )
            ->add('visible', 'checkbox', array(
                    'label'=>'label.visible', 
                    'required' => false,
                    'attr' => array(
                        'class' => 'icheck'
                    )
                )
            )
            ->add('actions', null, array(
                    'label' => 'label.actions',
                    'property' => 'title',
                    'multiple' => true,
                    'expanded' => true,
                    'attr' => array(
                        'class' => 'icheck'
                    )
                )
            )
            ->add('submit', 'submit', array(
                'label' => 'label.submit',
                'attr' => array(
                    'class' => 'btn blue',
                )
            ))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'SmartCity\UserBundle\Entity\ActionGroup',
            'translation_domain' => 'labels',
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'smartCity_userbundle_actiongroup';
    }
}
