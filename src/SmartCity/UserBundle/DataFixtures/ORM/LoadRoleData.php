<?php

namespace SmartCity\UserBundle\DataFixtures\ORM;

use SmartCity\UserBundle\Entity\Role;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadRoleData extends AbstractFixture implements OrderedFixtureInterface
{
    /** @var  ObjectManager $manager */
    private $manager;

    public function load(ObjectManager $manager)
    {
        $this->manager = $manager;
        $this->addReference('super-admin-role', $this->createSuperAdminRole());
        $this->addReference('admin-role', $this->createAdminRole());
    }

    /**
     * Create Super Admin Role (Super Admin)
     * @return Role
     */
    private function createSuperAdminRole()
    {
        $role = $this->manager->getRepository('SmartCityUserBundle:Role')->findByRoleName('ROLE_SUPER_ADMIN');
        if (! $role) {
            $role = new Role();
            $role->setTitle("Super Admin");
            $role->setRole("ROLE_SUPER_ADMIN");
            $role->setVisible(true);

            $this->manager->persist($role);
            $this->manager->flush();
        }
        return $role;
    }

    /**
     * Create Admin Role (admin)
     * @return Role
     */
    private function createAdminRole()
    {
        $role = $this->manager->getRepository('SmartCityUserBundle:Role')->findByRoleName('ROLE_ADMIN');
        if (! $role) {
            $role = new Role();
            $role->setTitle("Admin");
            $role->setRole("ROLE_ADMIN");
            $role->setVisible(true);

            $this->manager->persist($role);
            $this->manager->flush();
        }
        return $role;
    }

    /**
     * Get the order of this fixture
     *
     * @return integer
     */
    function getOrder()
    {
        return 1;
    }
}
