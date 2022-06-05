<?php
/**
 * Created by Luigi Massa <lmassa@bwlab.it>
 */

namespace ShoppyGo\MarketplaceBundle\HookListener;

use ShoppyGo\MarketplaceBundle\Classes\MarketplaceCore;
use ShoppyGo\MarketplaceBundle\Entity\MarketplaceEmployeeSeller;
use ShoppyGo\MarketplaceBundle\Form\Widget\SellerSelectWidget;
use ShoppyGo\MarketplaceBundle\Form\Widget\SellerSwitchWidget;
use ShoppyGo\MarketplaceBundle\Provider\SellerChoiceProvider;
use ShoppyGo\MarketplaceBundle\Repository\MarketplaceEmployeeSellerRepository;

class EmployeeActionFormBuilderModifierListener extends AbstractHookListenerImplementation
{
    protected SellerChoiceProvider $sellerChoiceProvider;
    protected MarketplaceCore $core;
    protected MarketplaceEmployeeSellerRepository $employeeSellerRepository;
    protected SellerSelectWidget $sellerSelectWidget;
    protected SellerSwitchWidget $sellerSwitchWidget;

    public function __construct(
        SellerChoiceProvider $sellerChoiceProvider,
        MarketplaceCore $core,
        MarketplaceEmployeeSellerRepository $employeeSellerRepository
    ) {
        $this->core = $core;
        $this->employeeSellerRepository = $employeeSellerRepository;
        $this->sellerChoiceProvider = $sellerChoiceProvider;
    }

    public function exec(array $params)
    {
        $id_employee = $params['id'];
        $form = $params['form_builder'];

        if ($id_employee === $this->core->getEmployee()->id && $this->core->isEmployStaff() === true) {
            return;
        }

        /** @var MarketplaceEmployeeSeller $seller */
        $seller = $this->employeeSellerRepository->findSellerByEmployee($id_employee);

        $this->sellerSelectWidget->setSeller($seller);

        $this->sellerSelectWidget->addField($form);
    }

    public function setSellerSelectWidget(SellerSelectWidget $sellerSelectWidget): void
    {
        $this->sellerSelectWidget = $sellerSelectWidget;
    }

}
