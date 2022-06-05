<?php
/**
 * Created by Luigi Massa <lmassa@bwlab.it>
 */

namespace ShoppyGo\MarketplaceBundle\Form\Widget;

use PrestaShopBundle\Form\Admin\Type\SwitchType;
use ShoppyGo\MarketplaceBundle\Entity\MarketplaceEmployeeSeller;
use ShoppyGo\MarketplaceBundle\Provider\SellerChoiceProvider;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilder;

class SellerSwitchWidget
{
    protected ?MarketplaceEmployeeSeller $seller;


    public function addField(FormBuilder $form)
    {
        $form->add(
            'seller',
            SwitchType::class,
            array(
                'label' => 'Seller',
                'choices' => [
                    'OFF' => false,
                    'ON' => true,
                ],
                'data' =>false,
            )
        );
    }

    public function setSeller(?MarketplaceEmployeeSeller $seller)
    {
        $this->seller = $seller;
    }
}
