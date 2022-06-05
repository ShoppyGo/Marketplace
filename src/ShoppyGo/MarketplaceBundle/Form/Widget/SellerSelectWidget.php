<?php
/**
 * Created by Luigi Massa <lmassa@bwlab.it>
 */

namespace ShoppyGo\MarketplaceBundle\Form\Widget;

use ShoppyGo\MarketplaceBundle\Entity\MarketplaceEmployeeSeller;
use ShoppyGo\MarketplaceBundle\Provider\SellerChoiceProvider;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilder;

class SellerSelectWidget
{
    protected SellerChoiceProvider $sellerChoiceProvider;
    protected ?MarketplaceEmployeeSeller $seller;

    public function __construct(SellerChoiceProvider $sellerChoiceProvider)
    {
        $this->sellerChoiceProvider = $sellerChoiceProvider;
    }

    public function addField(FormBuilder $form)
    {
        $form->add(
            'supplier', ChoiceType::class, array(
                'label' => 'Seller',
                'choices' => $this->sellerChoiceProvider->getChoices(),
                'data' => $this->seller ? $this->seller->getIdSeller() : '',
            )
        );
    }

    public function setSeller(?MarketplaceEmployeeSeller $seller)
    {
        $this->seller = $seller;
    }
}
