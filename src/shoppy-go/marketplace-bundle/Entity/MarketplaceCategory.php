<?php

namespace ShoppyGo\MarketplaceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use ShoppyGo\MarketplaceBundle\Interfaces\MarketplaceEntityInterface;

/**
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="ShoppyGo\MarketplaceBundle\Repository\MarketplaceCategoryRepository")
 *
 * Class MarketplaceCategory
 * @package ShoppyGo\MarketplaceBundle\Entity
 */
class MarketplaceCategory implements MarketplaceEntityInterface
{
    /**
     * @Orm\Id
     * @ORM\Column(name="id_category", type="integer")
     * @var int
     */
    private $id_category;
    /**
     * @ORM\Column(name="seller", type="boolean")
     * @var bool
     */
    private $seller = false;


    public function getId()
    {
        return $this->getIdCategory();
    }

    /**
     * @return int
     */
    public function getIdCategory(): int
    {
        return $this->id_category;
    }

    /**
     * @param int $id_category
     */
    public function setIdCategory(int $id_category): self
    {
        $this->id_category = $id_category;

        return $this;
    }

    /**
     * @return bool
     */
    public function isSeller(): bool
    {
        return $this->seller;
    }

    /**
     * @param bool $seller
     */
    public function setSeller(bool $seller): self
    {
        $this->seller = $seller;

        return $this;
    }

    public function setId($id)
    {
        $this->setIdCategory(id);
    }

    public function toggleSeller()
    {
        $this->setSeller(!$this->isSeller());
    }

}
