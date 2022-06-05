<?php

namespace ShoppyGo\MarketplaceBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;

class ShoppyGoMarketplaceExtension extends Extension implements PrependExtensionInterface
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

//         $container->setParameter('shoppy_go_maketplace.bar', $config['bar']);
//         $container->setParameter('shoppy_go_maketplace.integer_foo', $config['integer_foo']);
//         $container->setParameter('shoppy_go_maketplace.integer_bar', $config['integer_bar']);
    }

    public function prepend(ContainerBuilder $container)
    {
//        $configs = $container->getExtensionConfig($this->getAlias());
//        $config = $this->processConfiguration(new Configuration(), $configs);
        // TODO: Set custom doctrine config
        $doctrineConfig = [];
//        $doctrineConfig['orm']['resolve_target_entities']['ShoppyGo\MaketplaceBundle\Entity\UserInterface'] = $config['user_provider'];
//        $doctrineConfig['orm']['mappings'][] = array(
//            'name' => 'ShoppyGoMaketplaceBundle',
//            'is_bundle' => true,
//            'type' => 'xml',
//            'prefix' => 'ShoppyGo\MaketplaceBundle\Entity'
//        );
//        $container->prependExtensionConfig('doctrine', $doctrineConfig);
        // TODO: Set custom twig config
        $twigConfig = [];
//        $twigConfig['globals']['shoppy_go_maketplace_bar_service'] = "@shoppy_go_maketplace.service";
//        $twigConfig['paths'][__DIR__.'/../Resources/views'] = "shoppy_go_maketplace";
//        $twigConfig['paths'][__DIR__.'/../Resources/public'] = "shoppy_go_maketplace.public";
//        $container->prependExtensionConfig('twig', $twigConfig);
    }

    public function getAlias()
    {
        return 'shoppy_go_marketplace';
    }
}
