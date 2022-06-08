<?php
/**
 * Created by Luigi Massa <lmassa@bwlab.it>
 */

namespace ShoppyGo\MarketplaceBundle\Adapter\Entity;

use PrestaShop\PrestaShop\Adapter\Entity\Supplier;

class Seller extends Supplier
{
    public function getSellerId(): int
    {
        return (int) $this->id;
    }
}
