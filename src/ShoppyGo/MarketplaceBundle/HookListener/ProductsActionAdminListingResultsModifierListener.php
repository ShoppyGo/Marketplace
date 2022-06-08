<?php
/**
 * Created by Luigi Massa <lmassa@bwlab.it>
 */

namespace ShoppyGo\MarketplaceBundle\HookListener;

use PrestaShop\PrestaShop\Adapter\Entity\PrestaShopException;
use PrestaShop\PrestaShop\Adapter\Entity\Supplier;
use ShoppyGo\MarketplaceBundle\Adapter\Entity\Seller;
use ShoppyGo\MarketplaceBundle\Classes\MarketplaceCore;
use ShoppyGo\MarketplaceBundle\Repository\MarketplaceEmployeeSellerRepository;
use Symfony\Component\Translation\TranslatorInterface;

class ProductsActionAdminListingResultsModifierListener extends AbstractHookListenerImplementation
{
    protected MarketplaceCore $core;
    protected MarketplaceEmployeeSellerRepository $employeeSellerRepository;
    protected TranslatorInterface $translator;

    public function __construct(
        MarketplaceCore $core,
        MarketplaceEmployeeSellerRepository $employeeSellerRepository,
        TranslatorInterface $translator
    ) {
        $this->core = $core;
        $this->employeeSellerRepository = $employeeSellerRepository;
        $this->translator = $translator;
    }

    public function exec(array $params)
    {
        if (false == $this->core->isEmployeeSeller()) {
            return;
        }
        $sql_table = &$params['sql_table'];
        $sql_where = &$params['sql_where'];

        if (array_key_exists('msp', $sql_table) === true) {
            return;
        }

        $seller = new Seller($this->core->getSellerId());
        if ($seller->active === false) {
            throw new PrestaShopException('List of products are not available. You are not  a seller.');
        }
        $sql_table['mksp'] = array(
            'table' => 'product_supplier',
            'join'  => 'INNER JOIN',
            'on'    => 'mksp.`id_product` = p.`id_product`',
        );
        $sql_where[] = 'mksp.`id_supplier` = '.$seller->getSellerId();
    }
}
