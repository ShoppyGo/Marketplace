<?php
/**
 * Created by Luigi Massa <lmassa@bwlab.it>
 */

namespace ShoppyGo\MarketplaceBundle\Repository;

use Doctrine\ORM\EntityRepository;
use ShoppyGo\MarketplaceBundle\Entity\MarketplaceSellerShipping;

class MarketplaceSellerShippingRepository extends EntityRepository
{

    public function findRange(int $id_seller, $total): ?MarketplaceSellerShipping
    {
        $range = $this->createQueryBuilder('s')
            ->where('s.from between :from and :to')
            ->orWhere('s.from < :total and s.to > :total')
            ->andWhere('s.id_seller = :seller')
            ->setParameters(['total' => $total, 'seller' => $id_seller])
            ->orderBy('s.cost', 'DESC')
            ->getQuery()
            ->execute()
        ;
        if (count($range) > 0) {
            return $range[0];
        }
        #
        # non trovo nulla, quindi estraggo il record più grande
        #
        $range = $this->createQueryBuilder('s')
            ->where('s.from between :from and :to')
            ->orWhere('s.to < :total ')
            ->andWhere('s.id_seller = :seller')
            ->setParameters(['total' => $total, 'seller' => $id_seller])
            ->orderBy('s.cost', 'DESC')
            ->getQuery()
            ->execute()
        ;

        if (count($range) > 0) {
            return $range[0];
        }
        #
        # se non trovo nulla restutisco un insieme vuoto
        #
        return null;
    }

    /**
     * @param $from
     * @param $to
     * @return array<MarketplaceSellerShipping>
     */
    public function getRanges($from, $to, $id_seller): array
    {
        $range1 = $this->createQueryBuilder('s')
            ->where('s.from between :from and :to')
            ->orWhere('s.to between :from and :to')
            ->andWhere('s.id_seller = :seller')
            ->setParameters(['from' => $from, 'to' => $to, 'seller' => $id_seller])
            ->getQuery()
            ->execute()
        ;
        $range2 = $this->createQueryBuilder('s')
            ->where('s.from < :from and s.to > :from')
            ->orWhere('s.from < :to and s.to > :to')
            ->andWhere('s.id_seller = :seller')
            ->setParameters(['from' => $from, 'to' => $to, 'seller' => $id_seller])
            ->getQuery()
            ->execute()
        ;

        return array_merge($range1, $range2);
    }

}
