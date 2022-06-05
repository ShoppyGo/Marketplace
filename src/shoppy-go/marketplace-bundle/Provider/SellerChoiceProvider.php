<?php

namespace ShoppyGo\MarketplaceBundle\Provider;

use PrestaShop\PrestaShop\Adapter\Supplier\SupplierDataProvider;
use PrestaShop\PrestaShop\Core\Form\FormChoiceAttributeProviderInterface;
use PrestaShop\PrestaShop\Core\Form\FormChoiceProviderInterface;

/**
 * Created by Luigi Massa <lmassa@bwlab.it>
 */
class SellerChoiceProvider implements FormChoiceProviderInterface, FormChoiceAttributeProviderInterface
{
    /**
     * @var SupplierDataProvider
     */
    private SupplierDataProvider  $sellerDataProvider;

    public function __construct(SupplierDataProvider $supplierDataProvider)
    {
        $this->sellerDataProvider = $supplierDataProvider;
    }

    public function getChoices(): array
    {
        $choices = [];
        foreach ($this->sellerDataProvider->getSuppliers() as $supplier) {

            $choices[$supplier['name']] = $supplier['id_supplier'];
        }

        return $choices;
    }

    public function getChoicesAttributes()
    {
        // TODO: Implement getChoicesAttributes() method.
    }
}
