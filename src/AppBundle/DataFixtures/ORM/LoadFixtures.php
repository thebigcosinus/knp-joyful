<?php
namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Nelmio\Alice\Fixtures;

class LoadFixtures implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        Fixtures::load(__DIR__.'/fixtures.yml', $manager, [
            'providers' => [$this]
        ]);
    }

    public function genus() {
        $genera = ['genus1', 'genus2'];
        $key = array_rand($genera);

        return $genera[$key];
    }
}

?>
