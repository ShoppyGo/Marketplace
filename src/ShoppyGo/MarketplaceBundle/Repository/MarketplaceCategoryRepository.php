<?php
/**
 * Created by Luigi Massa <lmassa@bwlab.it>
 */

namespace ShoppyGo\MarketplaceBundle\Repository;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use ShoppyGo\MarketplaceBundle\Entity\MarketplaceCategory;
use ShoppyGo\MarketplaceBundle\Interfaces\MarketplaceSellerRepositoryInterface;

/**
 * @method get(string $string)
 */
class MarketplaceCategoryRepository extends EntityRepository implements MarketplaceSellerRepositoryInterface
{

    public function create(int $id, bool $is_seller = false)
    {
        $category = new MarketplaceCategory();
        $category->setIdCategory($id);
        $category->setSeller($is_seller);

        /** @var EntityManagerInterface $em */
        $this->getEntityManager()
            ->persist($category)
        ;
        $this->getEntityManager()
            ->flush()
        ;
    }

    public function findOneByShop(int $id_category): ?MarketplaceCategory
    {
        return $this->findOneBy(['id_category' => $id_category]);
    }

    public function toggle(int $id): bool
    {
        $record = $this->findOneBy(['id_category' => $id]);

        $record->setSeller(!$record->isSeller());
        $this->getEntityManager()
            ->persist($record)
        ;
        $this->getEntityManager()
            ->flush()
        ;
    }
}
