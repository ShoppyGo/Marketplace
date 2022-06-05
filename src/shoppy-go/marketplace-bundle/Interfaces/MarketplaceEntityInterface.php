<?php

namespace ShoppyGo\MarketplaceBundle\Interfaces;


/**
 * Interface MarketplaceEntityInterface
 * @package Bwlab\Marketplace\Interfaces
 */
interface MarketplaceEntityInterface
{
    /**
     * @return bool
     */
    public function isSeller();

    /**
     * @param bool $seller
     */
    public function setSeller(bool $seller);

    /**
     * @return $this
     */
    public function toggleSeller();

    /**
     * @return mixed
     */
    public function getId();

    /**
     * @return mixed
     */
    public function setId($id);
}
