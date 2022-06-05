<?php

namespace ShoppyGo\MarketplaceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="ShoppyGo\MarketplaceBundle\Entity\MarketplaceSellerShippingRepository")
 * @UniqueEntity(fields={"id_shipping", "id_shop"})
 * @package ShoppyGo\MarketplaceBundle\Entity
 */
class MarketplaceSellerShipping
{

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(name="id_shipping", type="integer")
     * @var int
     */
    private $id_shipping;

    /**
     * @ORM\Column(name="id_supplier", type="integer")
     * @var int
     */
    private $id_seller;

    /**
     * @ORM\Column(name="from_total", type="decimal", precision=2)
     * @var float
     */
    private $from = 0;
    /**
     * @ORM\Column(name="to_total", type="decimal", precision=2)
     * @var float
     */
    private $to = 0;
    /**
     * @ORM\Column(name="shipping_cost", type="decimal", precision=2)
     * @var float
     */
    private $cost = 0;
    /**
     * @ORM\Column(name="vat", type="decimal", precision=2)
     * @var float
     */
    private $vat = 0;

    public function getCost()
    {
        return $this->cost;
    }

    public function setCost($cost): self
    {
        $this->cost = $cost;

        return $this;
    }

    public function getFrom()
    {
        return $this->from;
    }

    public function setFrom($from): self
    {
        $this->from = $from;

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

    public function getIdShipping(): int
    {
        return $this->id_shipping;
    }

    public function setIdShipping(int $id_shipping): self
    {
        $this->id_shipping = $id_shipping;

        return $this;
    }

    public function getTo()
    {
        return $this->to;
    }

    public function setTo($to): self
    {
        $this->to = $to;

        return $this;
    }

    public function getVat()
    {
        return $this->vat;
    }

    public function setVat($vat): self
    {
        $this->vat = $vat;

        return $this;
    }

}
