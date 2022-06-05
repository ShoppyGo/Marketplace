<?php

namespace ShoppyGo\MarketplaceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="ShoppyGo\MarketplaceBundle\Repository\MarketplaceSellerProductRepository")
 *
 * Class MarketPlaceSeller
 * @package ShoppyGo\MarketplaceBundle\Entity
 */
class MarketplaceSellerProduct
{

    /**
     * @ORM\Id
     * @ORM\Column(name="id_product", type="integer")
     * @var int
     */
    private $id_product;

    /**
     * @ORM\Column(name="id_supplier", type="integer")
     * @var int
     */
    private $id_seller;

    /**
     * @return int
     */
    public function getIdProduct(): int
    {
        return $this->id_product;
    }

    /**
     * @param int $id_product
     * @return $this
     */
    public function setIdProduct(int $id_product): self
    {
        $this->id_product = $id_product;

        return $this;
    }

    public function getIdSeller(): int
    {
        return $this->id_seller;
    }

    public function setIdSeller(int $id_seller): self
    {
        $this->id_seller = $id_seller;

        return $this;
    }



}
