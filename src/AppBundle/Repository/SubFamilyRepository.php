<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * SubFamilyRepository
 *
 * This class was generated by the PhpStorm "Php Annotations" Plugin. Add your own custom
 * repository methods below.
 */
class SubFamilyRepository extends EntityRepository
{
    public function createAlphabteticalQueryBuilder()
    {
        return $this->createQueryBuilder('sub_family')
            ->orderBy('sub_family.name', 'ASC')
            ;

    }
}
