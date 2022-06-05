<?php
/**
 * Created by Luigi Massa <lmassa@bwlab.it>
 */

namespace  ShoppyGo\MarketplaceBundle\Repository;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\OptimisticLockException;
use ShoppyGo\MarketplaceBundle\Entity\MarketplaceEmployeeSeller;
use ShoppyGo\MarketplaceBundle\Interfaces\MarketplaceSellerRepositoryInterface;

/**
 * @method get(string $string)
 */
class MarketplaceEmployeeSellerRepository extends EntityRepository
{

    public function findSellersByEmployees(array $employees): array
    {
        return $this->createQueryBuilder('ms')
            ->where('ms.id_employee in (:employee)')
            ->setParameters([':employee' => $employees])
            ->getQuery()->execute();

    }

    public function create(int $id_employee, int $id_seller)
    {
        $seller = new MarketplaceEmployeeSeller();
        $seller->setIdEmployee($id_employee);
        $seller->setIdSeller($id_seller);

        /** @var EntityManagerInterface $em */
        $em = $this->getEntityManager();
        $em->persist($seller);
        $em->flush();
    }
    public function update(int $id_employee, int $id_seller)
    {
        $employee_seller = $this->find($id_employee);
        $employee_seller->setIdSeller($id_seller);
        /** @var EntityManagerInterface $em */
        $em = $this->getEntityManager();
        $em->persist($employee_seller);
        $em->flush();
    }

    public function findSellerByEmployee(?int $id_employee): ?MarketplaceEmployeeSeller
    {
        return $this->findOneBy(['id_employee' => $id_employee]);
    }
}
