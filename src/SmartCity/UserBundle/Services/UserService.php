<?php

namespace SmartCity\UserBundle\Services;

use SmartCity\UserBundle\Entity\Constants\UserConstants;
use SmartCity\UserBundle\Entity\User;
use SmartCity\UserBundle\Entity\UserForgotPassword;
use SmartCity\UserBundle\Entity\UserVerificationToken;
use Doctrine\ORM\EntityManager;
use Monolog\Logger;
use Symfony\Component\DependencyInjection\ContainerInterface;

class UserService
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var Logger
     */
    private $logger;

    /**
     * @var EntityManager
     */
    private $em;


    /**
     * UserVerificationService constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->logger = $container->get('logger');
        $this->em = $container->get('doctrine.orm.default_entity_manager');
    }


    public function checkExistence($email, $cellphone, $nationalCode, $username = null){
        $userModel = $this->em->getRepository('SmartCityUserBundle:User');

        // if ($userModel->isUsernameExists($username)) {
            // return array(
            //     'status' => false,
            //     'message' => $this->container->get('translator')->trans('label.username_exist', array(), 'labels')
            // );
        // }

        if ($userModel->isEmailExists($email)) {
            return array(
                'status' => false,
                'message' => $this->container->get('translator')->trans('label.email_exist', array(), 'labels')
            );
        }

        $phoneService = $this->container->get('SmartCity.phone.service');
        $standard_phone = $phoneService->normalize($cellphone->getNationalNumber());
        if ($userModel->isCellphoneExist($standard_phone)) {
            return array(
                'status' => false,
                'message' => $this->container->get('translator')->trans('label.cellphone_exist', array(), 'labels') 
            );
        }

        if ($userModel->isNationalCodeExist($nationalCode)) {
            return array(
                'status' => false,
                'message' => $this->container->get('translator')->trans('label.nationalCode_exist', array(), 'labels') 
            );
        }

        return array(
            'status' => true,
        );
    }

}
