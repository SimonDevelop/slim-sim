<?php

namespace App\Entity;

use Doctrine\ORM\EntityRepository;

class RankRepository extends EntityRepository
{
    public function queryGetRanks()
    {
        return $this->createQueryBuilder("r")
            ->getQuery()
            ->getResult();
    }
}
