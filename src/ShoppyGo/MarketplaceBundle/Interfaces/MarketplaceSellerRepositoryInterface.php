<?php

namespace ShoppyGo\MarketplaceBundle\Interfaces;

interface MarketplaceSellerRepositoryInterface
{
    public function toggle(int $id);

    public function create(int $id, bool $is_seller = false);
}
