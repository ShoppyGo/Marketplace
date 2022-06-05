<?php
/**
 * Copyright since 2007 PrestaShop SA and Contributors
 * PrestaShop is an International Registered Trademark & Property of PrestaShop SA
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.md.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/OSL-3.0
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to https://devdocs.prestashop.com/ for more information.
 *
 * @author    PrestaShop SA and Contributors <contact@prestashop.com>
 * @copyright Since 2007 PrestaShop SA and Contributors
 * @license   https://opensource.org/licenses/OSL-3.0 Open Software License (OSL 3.0)
 */

namespace ShoppyGo\MarketplaceBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class SetupMarketplaceCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this->setName('shoppygo:setup')
            ->setDescription('Marletplace setup')
            ->addOption('create', null,InputOption::VALUE_NONE,'Create marketplace tables' )
            ->addOption('drop', null,InputOption::VALUE_NONE,'Drop marketplace tables' )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

//        $repository = $this->getContainer()->get('prestashop.core.addon.theme.repository');
//        $theme = $repository->getInstanceByName($input->getArgument('theme'));
//
//        $themeExporter = $this->getContainer()->get('prestashop.core.addon.theme.exporter');
//        $path = $themeExporter->export($theme);
//
//        $formatter = $this->getHelper('formatter');
//        $translator = $this->getContainer()->get('translator');
//        $successMsg = $translator->trans(
//            'Your theme has been correctly exported: %path%',
//            ['%path%' => $path],
//            'Admin.Design.Notification'
//        );
//        $formattedBlock = $formatter->formatBlock($successMsg, 'info', true);
//        $output->writeln($formattedBlock);
//
        $options = $input->getOptions();

        $sqls = array();
        $type = '';
        if(true === $options['create']) {
            $type = 'create. Marketplace is installed';
            $sqls = $this->createSql();
        }
        if(true === $options['drop']) {
            $type = 'drop. Marketplace is uninstalled';
            $sqls = $this->dropSql();
        }

        $conn = $this->getContainer()
                    ->get('doctrine')
                    ->getManager()
                    ->getConnection();

        foreach ($sqls as $sql) {
            $conn->executeQuery($sql);
        }

        $output->writeln('Success: '.$type);

        return 0;
    }

    private function dropSql(): array
    {
        return array(
            'DROP TABLE IF EXISTS '._DB_PREFIX_.'marketplace_employee_seller',
            'DROP TABLE IF EXISTS '._DB_PREFIX_.'marketplace_seller_shipping',
            'DROP TABLE IF EXISTS '._DB_PREFIX_.'marketplace_category',
            'DROP TABLE IF EXISTS '._DB_PREFIX_.'marketplace_seller_order',
        );
    }
    private function createSql(): array
    {
        $sql[] = 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'marketplace_employee_seller` (
                    `id_employee` int(11) NOT NULL ,
                    `id_supplier` int(11) NOT NULL,
                    PRIMARY KEY  (`id_employee`),
                    KEY (`id_supplier`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8;';

#----- tabella spedizioni
        $sql[] = 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'marketplace_seller_shipping` (
                    `id_shipping` int(11) NOT NULL AUTO_INCREMENT,
                    `id_supplier` int(11) NOT NULL ,
                    `from_total` decimal(8,2),
                    `to_total` decimal(8,2),
                    `type` char(1),
                    `shipping_cost` decimal(4,2),
                    `vat` decimal(4,2),
                    PRIMARY KEY  (`id_shipping`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8;';

#------- categorie abilitate
        $sql[] = 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'marketplace_category` (
                    `id_category` int(11) NOT NULL ,
                    `seller` boolean,
                    PRIMARY KEY ( `id_category`),
                    KEY (`id_category`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8;';

#------- ordine
        $sql[] = 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'marketplace_seller_order` (
                    `id_supplier` int(11) NOT NULL ,
                    `id_order` int(11) NOT NULL,
                    `id_order_main` int(11) NOT NULL,
                    KEY (`id_supplier`, `id_order`),
                    KEY (`id_order`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8;';
        return $sql;
    }
}
