<?php
/**
 * Created by Luigi Massa <lmassa@bwlab.it>
 */

namespace ShoppyGo\MarketplaceBundle\HookListener;

abstract class AbstractHookListenerImplementation implements HookListenerInterface
{

    public array $hooks = [];


    public function setHooks(array $hooks)
    {
        $this->hooks = $hooks;
        array_walk($this->hooks, static function (&$hook) {
            $hook = strtolower($hook);
        });
    }

    public function supports(string $hook)
    {
        return in_array($hook, $this->hooks);
    }
}
