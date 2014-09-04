<?php
namespace Training\BatteryBundle\Entity;

use Doctrine\ORM\EntityRepository;
/**
 * Battery Repository
  */
class BatteryRepository extends EntityRepository
{
    /**
     * @return array
     */
    public function findAllGroupedByType()
    {
        return $this->createQueryBuilder('b')
            ->select(array(
                'b.type',
                'SUM(b.count) as totalCount',
            ))
            ->groupBy('b.type')
            ->getQuery()
            ->getArrayResult();
    }
}