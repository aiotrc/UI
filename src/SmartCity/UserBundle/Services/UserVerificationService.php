<?php

namespace SmartCity\UserBundle\Services;

use SmartCity\UserBundle\Entity\Constants\UserConstants;
use SmartCity\UserBundle\Entity\User;
use SmartCity\UserBundle\Entity\UserForgotPassword;
use SmartCity\UserBundle\Entity\UserVerificationToken;
use Doctrine\ORM\EntityManager;
use Monolog\Logger;
use Symfony\Component\DependencyInjection\ContainerInterface;

class UserVerificationService
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

    /**
     * @param User $user
     * @return UserVerificationToken
     */
    public function generateVerificationToken(User $user)
    {

        $verificationTokenModel = $this->em->getRepository('SmartCityUserBundle:UserVerificationToken');
        $verificationToken =  $verificationTokenModel->getActiveToken($user);

        if (!$verificationToken) {
            $emailToken = $this->generateEmailToken();
            $smsToken = $this->generateSMSToken();

            $expiresAt = new \DateTime('+10 min');

            $verificationToken = new UserVerificationToken();
            $verificationToken->setUser($user);
            $verificationToken->setExpiresAt($expiresAt);
            $verificationToken->setEmailToken($emailToken);
            $verificationToken->setSMSToken($smsToken);

            $this->em->persist($verificationToken);
            $this->em->flush();
        }

        return $verificationToken;
    }

    /**
     * @param User $user
     * @return UserVerificationToken
     */
    public function verifyVerificationToken(User $user, $token)
    {

        $verificationTokenModel = $this->em->getRepository('SmartCityUserBundle:UserVerificationToken');
        $verificationToken =  $verificationTokenModel->getActiveToken($user);

        $responseCode = 0;

        if ($verificationToken) {

            if($verificationToken->getEmailToken() == $token){
                $responseCode = 1;

                $now = new \DateTime();
                $verificationToken->setUsed(true);
                $verificationToken->setExpired(true);
                $verificationToken->setUpdatedAt($now);
                $verificationToken->setUsedAt($now);
            }
        }

        return $responseCode;
    }

    public function generateForgetPasswordToken(User $user, $type = UserConstants::FORGET_PASSWORD_TYPE_EMAIL)
    {
        if($type == UserConstants::FORGET_PASSWORD_TYPE_EMAIL){
            $token = $this->generateEmailToken();
        }
        else if($type == UserConstants::FORGET_PASSWORD_TYPE_SMS){
            $token = $this->generateSMSToken();
        }

        $expiresAt = new \DateTime(UserConstants::FORGET_PASSWORD_LIFECYCLE);

        $UserForgotPassword = new UserForgotPassword();
        $UserForgotPassword->setUser($user);
        $UserForgotPassword->setType($type);
        $UserForgotPassword->setToken($token);
        $UserForgotPassword->setExpiresAt($expiresAt);

        $this->em->persist($UserForgotPassword);
        $this->em->flush();

        return $UserForgotPassword;
    }

    /**
     * @param User $user
     * @param $code
     * @return int
     */
    public function verifyForgetPasswordToken(User $user, $code)
    {
        $responseCode = 0; // Code not found

        $userPasswordTokenRepository = $this->em->getRepository('SmartCityUserBundle:UserForgotPassword');
        $token = $userPasswordTokenRepository->getActiveToken($user);
        if ($token) {
            if ($token->getToken() == $code) {
                $token->setUsed(true);
                $responseCode = 1; // Found, Verified
            } 
            else if ($token->getGenerateTries() > 5) {
                $responseCode = 3; // Exceeded tries
                $token->setExpired(true);
            } 
            else {
                $token->increaseGenerateTries();
                $responseCode = 2; // Wrong
            }
        }

        $this->em->flush();

        return $responseCode;
    }

    /**
     * @param $user
     * @return mixed
     */
    public function hasAlreadyForgetPasswordToken($user)
    {
        $userPasswordToken = $this->em->getRepository('SmartCityUserBundle:UserForgotPassword');
        return $userPasswordToken->hasAlreadyForgetPasswordToken($user);
    }

    private function generateEmailToken()
    {

        return base_convert(sha1(uniqid(mt_rand(), true)), 16, 36);
    }

    private function generateSMSToken()
    {
        $min = 10000;
        $max = 99999;

        return mt_rand($min, $max);
    }
}
