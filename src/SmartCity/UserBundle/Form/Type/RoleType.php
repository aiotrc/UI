<?php

namespace SmartCity\UserBundle\Form\Type;

use SmartCity\UserBundle\Entity\Role;
use libphonenumber\PhoneNumberFormat;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;


class RoleType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', 'text', array(
                'label'=>'label.title', 
            ))

            ->add('role', 'text', array(
                'label' => 'label.role.code',
                'required' => true
            ))

            ->add('visible', 'checkbox', array(
                'label' => 'label.visible',
                'required' => false,
                'attr' => array(
                    'class' => 'icheck'
                )
            ))

            ->add('actionGroups', null, array(
                'label' => 'label.actiongroup.title',
                'property' => 'title',
                'expanded' => true,
                'multiple' => true,
                'attr' => array(
                    'class' => 'icheck'
                )
            ))

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
            'data_class' => 'SmartCity\UserBundle\Entity\Role',
            'translation_domain' => 'labels'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'Role';
    }
}
