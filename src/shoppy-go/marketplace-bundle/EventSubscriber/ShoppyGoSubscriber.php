<?php
/**
 * Created by Luigi Massa <lmassa@bwlab.it>
 */

namespace ShoppyGo\MarketplaceBundle\EventSubscriber;

use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class ShoppyGoSubscriber implements EventSubscriberInterface
{
    /**
     * @var Container
     */
    protected $container;
    /**
     * @var iterable
     */
    protected $hooks;

    public function __construct(iterable $hooks)
    {
        $this->hooks = $hooks;
    }

    public function __call($hookname, $event)
    {
        // vedi LegacyHookSubscriber __call
        foreach ($this->hooks as $key => $hook) {
            if (true === $hook->supports($hookname)) {
                $event_hook = $event[0];

                return $hook->exec($event_hook->getHookParameters());
            }
        }
    }

    public static function getSubscribedEvents(): array
    {
        $hooks = [
            'actionEmployeeFormBuilderModifier'    => 1,
            'actionAfterCreateEmployeeFormHandler' => 1,
            'actionAfterUpdateEmployeeFormHandler' => 1,
            'actionEmployeeGridDefinitionModifier' => 1,
            'actionEmployeeGridQueryBuilderModifier' => 1,
        ];

        $hook_list = array();
        foreach ($hooks as $hook => $params) {
            $hook_list[strtolower($hook)] = array(
                strtolower($hook),
                $params,
            );
        }

        return $hook_list;
    }
}
