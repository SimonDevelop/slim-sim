<?php

namespace DataFixtures;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

use App\Entity\Rank;

class RankFixture extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $user = new Rank();
        $user->setName('admin');
        $manager->persist($user);
        $manager->flush();
        $this->addReference('rank-admin', $user);
    }

    public function getOrder()
    {
        return 1;
    }
}
