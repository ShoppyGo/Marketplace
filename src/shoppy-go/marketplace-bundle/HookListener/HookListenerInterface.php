<?php

namespace ShoppyGo\MarketplaceBundle\HookListener;

interface HookListenerInterface
{
    public function supports(string $hook);

    public function exec(array $params);
}
