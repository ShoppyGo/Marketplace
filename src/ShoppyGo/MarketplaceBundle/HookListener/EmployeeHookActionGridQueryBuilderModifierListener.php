<?php
/**
 * Created by Luigi Massa <lmassa@bwlab.it>
 */

namespace ShoppyGo\MarketplaceBundle\HookListener;

use Doctrine\ORM\QueryBuilder;
use PrestaShop\PrestaShop\Core\Form\IdentifiableObject\Builder\FormBuilderInterface;
use PrestaShop\PrestaShop\Core\Grid\Search\SearchCriteriaInterface;
use ShoppyGo\MarketplaceBundle\Classes\MarketplaceCore;
use ShoppyGo\MarketplaceBundle\Provider\SellerChoiceProvider;
use ShoppyGo\MarketplaceBundle\Repository\MarketplaceEmployeeSellerRepository;
use Symfony\Component\Translation\TranslatorInterface;

class EmployeeHookActionGridQueryBuilderModifierListener extends AbstractHookListenerImplementation
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
        /** @var QueryBuilder $search_query_builder */
        $search_query_builder = $params['search_query_builder'];

        $search_query_builder
            ->addSelect('s.name as seller_name')
            ->leftJoin('e', _DB_PREFIX_.'marketplace_employee_seller', 'mes', 'mes.id_employee = e.id_employee')
            ->leftJoin('mes', _DB_PREFIX_.'supplier', 's', 's.id_supplier = mes.id_supplier')
        ;
        /** @var SearchCriteriaInterface $search_criteria */
        $search_criteria = $params['search_criteria'];
        if ($search_criteria->getOrderBy() === 'seller_name') {
            $search_query_builder->orderBy('mes.seller_name', $search_criteria->getOrderWay());
        }
        foreach ($search_criteria->getFilters() as $filtername => $filter) {
            if ($filtername === 'seller_name') {
                $search_query_builder->andWhere('s.name like :name')
                    ->setParameter('name', '%'.$filter.'%')
                ;
            }
        }
    }

}
