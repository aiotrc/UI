<?php

namespace SmartCity\UserBundle\Form\Type;

use Doctrine\ORM\EntityRepository;
use libphonenumber\PhoneNumberFormat;
use SmartCity\UserBundle\Entity\Constants\UserConstants;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Tetranz\Select2EntityBundle\Form\Type\Select2EntityType;


class UserType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $user = $builder->getData();

        $builder

        // ------------------------------------------------------------------- personal data
            ->add('firstname', TextType::class, array(
                'label' => 'label.user.firstname',
                'attr' => array(
                    'placeholder' => 'نام به فارسی'
                )
            ))
            ->add('lastname', TextType::class, array(
                'label' => 'label.user.lastname',
                'attr' => array(
                    'placeholder' => 'نام خانوادگی به فارسی'
                )
            ))
            ->add('birthday', 'datetime', array(
                'widget' => 'single_text',
                'choice_translation_domain' => false,
                'label' => 'label.user.birthday',

                'attr' => array(
                    'class' => 'birthday_alt'
                )
            ))
            ->add('jalaliBirthday', TextType::class, array(
                'attr' => array(
                    'id' => 'birthday_alt'
                )
            ))
            ->add('nationalCode', TextType::class, array(
                'label' => 'label.user.national_code',
                'max_length' => 10,
                'required' => true,
                'attr' => array(
                    'placeholder' => 'کد ملی ۱۰ رقمی'
                )
            ))
            ->add('sex', 'choice', array(
                'choices' => UserConstants::$user_sexes,
                'label' => 'label.user.sex.label',
            ))

            // ->add('provinces', 'entity', array(
            //     'class' => 'SmartCityGeoBundle:Province',
            //     'label' => 'label.province.title',
            //     'required' => true,
            //     'multiple' => true,
            //     'expanded' => true,
            //     'attr' => array(
            //         'size' => 6,
            //     ),
            //     'choice_attr' => function($val, $key, $index) {
            //         return ['class' => 'icheck'];
            //     },
            // ))


        // ------------------------------------------------------------------- address and contact data
            ->add('email', 'email', array(
                'label' => 'label.email',
                'required' => true,
                'attr' => array(
                    'placeholder' => 'ایمیل'
                )
            ))
            ->add('cellphone', 'tel', array(
                'required' => true,
                'label' => 'label.cellphone',
                'default_region' => 'IR',
                'format' => PhoneNumberFormat::NATIONAL,
                'attr' => array(
                    'placeholder' => 'مثال : 09128875789'
                )
            ))

        // ------------------------------------------------------------------- system data
            // ->add('username', TextType::class, array(
            //     'label' => 'label.user.username',
            // ))
            ->add('plainPassword', 'repeated', array(
                'type' => 'password',
                'first_options' => array(
                    'label' => 'label.password',
                    'attr' => array(
                        'class' => 'form-control',
                        'value',
                        'placeholder' => 'label.password'
                    )
                ),
                'second_options' => array(
                    'label' => 'label.password_confirmed',
                    'attr' => array(
                        'class' => 'form-control',
                        'placeholder' => 'label.password_confirmed'
                    )
                ),
                'invalid_message' => 'label.password_mismatch',
                'required' => false
            ))

            ->add('status', 'choice', array(
                'choices' => UserConstants::$user_statuses,
                'label' => 'label.user.status.label'
            ))

            ->add('locale', 'choice', array(
                'choices' => UserConstants::$user_locales,
                'label' => 'label.locale.label'
            ))

            // ->add('deleted', 'checkbox', array(
            //     'label' => 'label.user.deleted',
            //     'required' => false
            // ))

            ->add('submit', 'submit', array(
                'label' => 'label.submit', 
                'attr' => array(
                    'class' => 'btn blue',
                )
            ))
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'SmartCity\UserBundle\Entity\User',
            'translation_domain' => 'labels'
        ));
    }
}
