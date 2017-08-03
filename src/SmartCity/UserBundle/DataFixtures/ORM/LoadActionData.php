<?php

namespace SmartCity\UserBundle\DataFixtures\ORM;

use SmartCity\UserBundle\Entity\Action;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadActionData extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $actions = [
            'ACTION_USER_INDEX' => 'Index',
            'ACTION_USER_NEW'   => 'Create',
            'ACTION_USER_SHOW'  => 'Show',
            'ACTION_USER_DELETE'=> 'Delete',
            'ACTION_USER_EDIT'  => 'Edit',
            'ACTION_USER_UPDATE'=> 'Update',
        ];

        foreach ($actions as $code => $title) {
            $action = new Action();
            $action->setCode($code);
            $action->setTitle("User -> $title");
            $action->setVisible(true);
            $manager->persist($action);
            $this->addReference('user-' . strtolower($title) . '-action', $action);
        }

        $manager->flush();
    }

    /**
     * Get the order of this fixture
     *
     * @return integer
     */
    function getOrder()
    {
        return 3;
    }
}
