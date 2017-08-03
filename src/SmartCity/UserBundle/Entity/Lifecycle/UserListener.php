<?php

namespace SmartCity\UserBundle\Entity\Lifecycle;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Events;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use libphonenumber\PhoneNumberFormat;
use libphonenumber\PhoneNumber;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use SmartCity\UserBundle\Entity\User;
use Hashids\Hashids;

/**
 * Class UserListener
 * @package SmartCity\UserBundle\Entity\Lifecycle
 */
class UserListener implements EventSubscriber
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * Constructor
     *
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * @return array
     */
    public function getSubscribedEvents()
    {
        return array(
            Events::postLoad,
            Events::postPersist,
            Events::prePersist,
            Events::preUpdate,
        );
    }

    /**
     * This must be called for calling the prepareImgSrc() function
     *
     * @param User $object
     */
    public function postLoad(User $object)
    {
        $phoneNumberUtil = $this->container->get('libphonenumber.phone_number_util');
        $cellphone = $object->getCellphone();
        $plainCellphone = '';
        if ($cellphone) {
            $plainCellphone = $phoneNumberUtil->format($cellphone, PhoneNumberFormat::NATIONAL);
        }
        $object->setPlainCellphone($plainCellphone);;
    }

    public function postPersist($object, LifecycleEventArgs $args)
    {
        
    }

    public function preUpdate(User $object, PreUpdateEventArgs $args)
    {

        $this->prepareCellphone($object);
        $this->updatePassword($object);

        if ($object->getLastSeen() == null || $object->getLastname() == '') {
            $defaultLastSeen = new \DateTime('1997-01-01 00:00:00');
            $object->setLastSeen($defaultLastSeen);
        }
    }

    /**
     * @param $object
     * @param LifecycleEventArgs $args
     */
    public function prePersist($object, LifecycleEventArgs $args)
    {
        $this->prepareCellphone($object);
        $this->updatePassword($object);

    }

    /**
     * @param User $object
     */
    public function updatePassword($object)
    {
        if (0 !== strlen($password = $object->getPlainPassword())) {
            $encoder = $this->getEncoder($object);
            $object->setPassword($encoder->encodePassword($object, $password));
            $object->eraseCredentials();
        }
    }

    /**
     * @param UserInterface $user
     * @return \Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface
     */
    protected function getEncoder(UserInterface $user)
    {
        return $this->container->get('security.password_encoder');
    }

    public function prepareCellphone(User &$object)
    {

        $current_route = '';
        if ($this->container->isScopeActive('request')) {
            $current_route = $this->container->get('request')->get('_route');
        }

        if ($current_route != 'panel_user_edit' && $current_route != 'panel_user_new') {
            if ($object->getPlainCellphone()) {
                $phoneService = $this->container->get('SmartCity.phone.service');
                $standard_phone = $phoneService->normalize($object->getPlainCellphone());

                $cellphone = new PhoneNumber();
                $cellphone->setRawInput($standard_phone);
                $object->setCellphone($cellphone);
            }
        }
    }

}