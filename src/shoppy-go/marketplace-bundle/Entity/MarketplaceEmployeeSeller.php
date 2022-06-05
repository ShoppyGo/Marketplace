<?php

namespace ShoppyGo\MarketplaceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="ShoppyGo\MarketplaceBundle\Repository\MarketplaceEmployeeSellerRepository")
 *
 * Class MarketPlaceSeller
 * @package ShoppyGo\MarketplaceBundle\Entity
 */
class MarketplaceEmployeeSeller
{

    /**
     * @ORM\Id
     * @ORM\Column(name="id_employee", type="integer")
     * @var int
     */
    private $id_employee;

    /**
     * @ORM\Column(name="id_supplier", type="integer")
     * @var int
     */
    private $id_seller;

    /**
     * @return int
     */
    public function getIdEmployee(): int
    {
        return $this->id_employee;
    }

    public function setIdEmployee(int $id_employee): self
    {
        $this->id_employee = $id_employee;

        return $this;
    }

    /**
     * @return int
     */
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
