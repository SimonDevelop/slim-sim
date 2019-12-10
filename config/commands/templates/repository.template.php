<?php

namespace App\Entity;

use Doctrine\ORM\EntityRepository;

class PregReplaceRepository extends EntityRepository
{
    public function queryGetPregReplaces()
    {
        return $this->createQueryBuilder()
            ->getQuery()
            ->getResult();
    }
}
