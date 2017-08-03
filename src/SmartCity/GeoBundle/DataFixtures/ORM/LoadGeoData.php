<?php

namespace SmartCity\GeoBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadGeoData implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $sql = file_get_contents('GeoData.sql');  // Read file contents
        $stmt = $manager->getConnection()->prepare($sql);
        $stmt->execute();
    }
}
