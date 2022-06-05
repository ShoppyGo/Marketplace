<?php
/**
 * Created by Luigi Massa <lmassa@bwlab.it>
 */

namespace ShoppyGo\MarketplaceBundle\Repository;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use ShoppyGo\MarketplaceBundle\Entity\MarketplaceSellerProduct;

class MarketplaceSellerProductRepository extends EntityRepository
{

    public function createProduct(int $id_seller, int $id_product)
    {
        $seller_product = new MarketplaceSellerProduct();
        $seller_product->setIdSeller($id_seller);
        $seller_product->setIdProduct($id_product);

        /** @var EntityManagerInterface $em */
        $em = $this->get('doctrine')
            ->getEntityManager()
        ;
        $em->persist($seller_product);
        $em->flush();
    }

    public function findProduct($id_product): ?MarketplaceSellerProduct
    {
        return $this->findOneBy(['id_product' => $id_product]);
    }

    # recupero lista prodotti per seller
    public function findSellersByProducts(array $id_products): array
    {
        $sellers = $this->createQueryBuilder('ps')
            ->select('distinct(ps.id_seller) as seller')
            ->andWhere('ps.id_product in (:products)')
            ->setParameter('products', $id_products)
            ->getQuery()
            ->execute()
        ;

        $id_seller = array();

        if (!$sellers) {
            return $id_seller;
        }

        foreach ($sellers as $seller) {
            $id_seller[] = $seller['seller'];
        }

        return $id_seller;
    }

    # controlla se l'abbinamento prodotto seller esiste
    public function isProductSeller(int $id_product, int $id_seller): bool
    {
        return (bool)$this->findOneBy(['id_seller' => $id_seller, 'id_product' => $id_product]);
    }
}
