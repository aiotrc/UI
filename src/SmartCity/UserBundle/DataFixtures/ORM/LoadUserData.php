<?php

namespace SmartCity\UserBundle\DataFixtures\ORM;

use SmartCity\UserBundle\Entity\Constants\UserConstants;
use SmartCity\UserBundle\Entity\User;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use libphonenumber\PhoneNumber;

class LoadUserData extends AbstractFixture implements OrderedFixtureInterface
{
    /** @var  ObjectManager $manager */
    private $manager;

    public function load(ObjectManager $manager)
    {
        $this->manager = $manager;
        $this->addReference('root-user', $this->createRootUser());
    }

    /**
     * Create Super Admin User (root)
     * @return User
     */
    private function createRootUser()
    {
        $userModel = $this->manager->getRepository('SmartCityUserBundle:User');
        $super_admin_user = $userModel->findOneBy(array('username' => 'jahadPlatformAdmin'));

        if (! $super_admin_user) {
            $super_admin_user = new User();
            $super_admin_user->setFirstname("root");
            $super_admin_user->setLastname("smart");
            $super_admin_user->setEmail("root@SmartCity.ir");
            $super_admin_user->setUsername("smartAdmin");
            $super_admin_user->setNationalCode("1234567891");
            $super_admin_user->setPlainPassword('123456');
            $super_admin_user->setType(UserConstants::TYPE_BACKEND);
            $super_admin_user->setStatus(UserConstants::STATUS_ACTIVE);
            $setCellphone = new PhoneNumber();
            $setCellphone->setRawInput('+989369286019');
            $super_admin_user->setCellphone($setCellphone);
            $super_admin_user->addRole($this->getReference('super-admin-role'));

            $userModel->save($super_admin_user);
            $this->manager->flush();
        }

        return $super_admin_user;
    }
 

    /**
     * Get the order of this fixture
     *
     * @return integer
     */
    function getOrder()
    {
        return 2;
    }
}
