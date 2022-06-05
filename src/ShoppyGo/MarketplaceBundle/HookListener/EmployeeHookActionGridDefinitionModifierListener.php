<?php
/**
 * Created by Luigi Massa <lmassa@bwlab.it>
 */

namespace ShoppyGo\MarketplaceBundle\HookListener;

use PrestaShop\PrestaShop\Core\Form\IdentifiableObject\Builder\FormBuilderInterface;
use PrestaShop\PrestaShop\Core\Grid\Column\ColumnCollection;
use PrestaShop\PrestaShop\Core\Grid\Column\Type\DataColumn;
use PrestaShop\PrestaShop\Core\Grid\Definition\GridDefinitionInterface;
use PrestaShop\PrestaShop\Core\Grid\Filter\Filter;
use ShoppyGo\MarketplaceBundle\Classes\MarketplaceCore;
use ShoppyGo\MarketplaceBundle\Provider\SellerChoiceProvider;
use ShoppyGo\MarketplaceBundle\Repository\MarketplaceEmployeeSellerRepository;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Translation\TranslatorInterface;

class EmployeeHookActionGridDefinitionModifierListener extends AbstractHookListenerImplementation
{
    protected SellerChoiceProvider $sellerChoiceProvider;
    protected MarketplaceCore $core;
    protected MarketplaceEmployeeSellerRepository $employeeSellerRepository;
    protected FormBuilderInterface $formBuilder;
    protected TranslatorInterface $translator;

    public function __construct(
        SellerChoiceProvider $sellerChoiceProvider,
        MarketplaceCore $core,
        MarketplaceEmployeeSellerRepository $employeeSellerRepository,
        TranslatorInterface $translator
    ) {
        $this->core = $core;
        $this->employeeSellerRepository = $employeeSellerRepository;
        $this->sellerChoiceProvider = $sellerChoiceProvider;
        $this->translator = $translator;
    }

    public function exec(array $params)
    {
        if (true === $this->core->isEmployeeSeller()) {
            return;
        }
        /** @var GridDefinitionInterface $definition */
        $definition = $params['definition'];
        /** @var ColumnCollection $columns */
        $columns = $definition->getColumns();
        #-------creo la colonna e la nomino is_seller
        $toggle_seller = new DataColumn('seller_name');

        $toggle_seller->setName($this->translator->trans('Seller'))
            ->setOptions(
                [
                    'field'            => 'seller_name'
                ]
            )
        ;

        #----aggiungo la colonna alla grid
        $columns->addAfter('id_employee', $toggle_seller);

        #----alla colonna aggiungo anche il filtro
        $definition->getFilters()
            ->add(
                (new Filter(
                    'seller_name', TextType::class
                ))->setAssociatedColumn('seller_name')
            )
        ;
    }

}
