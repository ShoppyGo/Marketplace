<?php
/**
 * Created by Luigi Massa <lmassa@bwlab.it>
 */

namespace ShoppyGo\MarketplaceBundle\HookListener;

use PrestaShop\PrestaShop\Core\Form\IdentifiableObject\Builder\FormBuilderInterface;
use ShoppyGo\MarketplaceBundle\Classes\MarketplaceCore;
use ShoppyGo\MarketplaceBundle\Provider\SellerChoiceProvider;
use ShoppyGo\MarketplaceBundle\Repository\MarketplaceEmployeeSellerRepository;

class EmployeeHookAfterUpdateListener extends AbstractHookListenerImplementation
{
    protected SellerChoiceProvider $sellerChoiceProvider;
    protected MarketplaceCore $core;
    protected MarketplaceEmployeeSellerRepository $employeeSellerRepository;
    protected FormBuilderInterface $formBuilder;

    public function __construct(
        SellerChoiceProvider $sellerChoiceProvider,
        MarketplaceCore $core,
        MarketplaceEmployeeSellerRepository $employeeSellerRepository,
        FormBuilderInterface $formBuilder
    ) {
        $this->core = $core;
        $this->employeeSellerRepository = $employeeSellerRepository;
        $this->sellerChoiceProvider = $sellerChoiceProvider;
        $this->formBuilder = $formBuilder;
    }

    public function exec(array $params)
    {
        $employeeForm = $this->formBuilder->getForm();
        $employeeForm->handleRequest($params['request']);
        $data = $employeeForm->getData();
        $id_seller = $data['supplier'];
        $id_employee = $params['id'];
        $this->employeeSellerRepository->update($id_employee, $id_seller);
    }

}
