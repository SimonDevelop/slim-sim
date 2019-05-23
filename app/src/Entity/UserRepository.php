<?php

namespace App\Entity;

use Doctrine\ORM\EntityRepository;

class UserRepository extends EntityRepository
{
    public function queryGetUsers()
    {
        return $this->createQueryBuilder("u")
            ->getQuery()
            ->getResult();
    }
}
