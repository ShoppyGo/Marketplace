<?php

namespace ShoppyGo\MarketplaceBundle\Classes;

//use Bwlab\Marketplace\Domain\Seller\Command\ReplaceSellerCommand;
//use Bwlab\Marketplace\Domain\Seller\Command\ReplaceShippingProductCommand;
//use Bwlab\Marketplace\Entity\MarketplaceSeller;
//use Bwlab\Marketplace\Provider\SupplierChoiceProvider;
use Context;
use Doctrine\ORM\EntityManager;
use PrestaShop\PrestaShop\Adapter\LegacyContext;
use PrestaShop\PrestaShop\Core\CommandBus\CommandBusInterface;
use PrestaShopBundle\Form\Admin\Type\SwitchType;
use ShoppyGo\MarketplaceBundle\Provider\SellerChoiceProvider;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class MarketplaceCore
{
    protected static bool $execProductUpdateAfter = false;
    protected EntityManager $entityManager;
//    /**
//     * @var MarketplaceSeller
//     */
    protected $marketplaceSeller;
    /**
     * @var CommandBusInterface
     */
    private CommandBusInterface $bus;
    private LegacyContext $legacyContext;

//
    public function __construct(RegistryInterface $registry, CommandBusInterface $bus, LegacyContext $context)
    {
        $this->entityManager = $registry->getEntityManager();
        $this->bus = $bus;
        $this->legacyContext = $context;

//        $this->setMarketplaceSeller();
    }
//
//    /**
//     * build lista prodotti
//     *
//     * @param $params
//     * @param $employee_id
//     */
//    public function actionAdminProductsListingFieldsModifier($params)
//    {
//        #------
//        # lo staff del marketpalce deve vedere tutto
//        #
//        if ($this->isEmployStaff()) {
//            return;
//        }
//        #
//        #---------------
//        #
//        $sql_table = &$params['sql_table'];
//        $sql_where = &$params['sql_where'];
//
//        if (array_key_exists('bwmkp', $sql_table) === true) {
//            return;
//        }
//
//
//        $supplier = new Supplier($this->marketplaceSeller->getIdSupplier());
//
//        if ($supplier->active === false) {
//            throw new PrestaShopException('List of products are not available. You are not  a seller.');
//        }
//        $sql_table['bwmkp'] = array(
//            'join' => 'INNER JOIN',
//            'on' => 'bwmkp.`id_product` = p.`id_product`',
//            'table' => 'product_supplier',
//        );
//        $sql_where[] = 'bwmkp.`id_supplier` = '.$supplier->id;
//    }
//
//    /**
//     * @param $field_name
//     * @param $entity_name
//     * @param $params
//     */
//    public function actionFormHandler($field_name, $entity_name, $params)
//    {
//        $id = $params['id'];
////        $is_seller = $params['form_data']['seller'];
//        $params['supplier'] = $params['form_data']['supplier'];
//        #---- CannotReplaceMarketplaceSellerException
//        $this->bus->handle(
//            new ReplaceSellerCommand($id, $field_name,  $entity_name, $params)
//        );
//    }
//
//    /**
//     * controlla se il prodotto è del seller
//     *
//     * @param $id_product
//     * @param int $id_attribute
//     * @return bool
//     */
//    public function isOwner($id_product, $id_attribute = 0)
//    {
//      return (boolean)  ProductSupplier::getIdByProductAndSupplier($id_product, $id_attribute, $this->marketplaceSeller->getIdSupplier());
//    }
//    /**
//     * @param $params
//     * @param $field_name
//     * @param $primary_field
//     * @param $route
//     * @param $route_parama_name
//     */
//    public function actionGridDefinitionModifier($params, $field_name, $primary_field, $route, $route_parama_name)
//    {
////        /** @var GridDefinitionInterface $definition */
////        $definition = $params['definition'];
////        /** @var ColumnCollection $columns */
////        $columns = $definition->getColumns();
////        #-------creo la colonna e la nomino is_seller
////        $toggle_seller = new ToggleColumn($field_name);
////
////        $toggle_seller
////            ->setName($this->trans('Seller'))
////            ->setOptions(
////                [
////                    'field' => $field_name,
////                    #------associo il campo di riferimento per la route
////                    'primary_field' => $primary_field,
////                    #------controller richiamato al click dell'utente
////                    'route' => $route,
////                    #-----parametro della route che sarà modificato con il primary_field
////                    'route_param_name' => $route_parama_name,     //passa sempre il primary_field
////                ]
////            );
////
////        #----aggiungo la colonna alla grid
////        $columns->addAfter('active', $toggle_seller);
////
////        #----alla colonna aggiungo anche il filtro
////        $definition->getFilters()->add(
////            (new Filter(
////                $field_name,
////                YesAndNoChoiceType::class
////            ))->setAssociatedColumn($field_name)
////        );
//    }
//
//    /**
//     * @param $params
//     * @param $table
//     * @param $field_name
//     * @param $fromAlias
//     *
//     */
//    public function actionGridQueryBuilderModifier($params, $table, $field_name, $fromAlias)
//    {
//        #-------recupero l'oggetto query builder di doctrine
//        /** @var QueryBuilder $search_query_builder */
//        $search_query_builder = $params['search_query_builder'];
//
////        #-------creo la join con la mia tabella custom
////        $search_query_builder
////            #------!ATTENZIONE: qui rinomino il campo in is_seller, che è il nome della colonna
////            ->addSelect('mp.seller as is_seller')
////            #-----per recuperare l'alias della tabella principale, andare in dub.
////            ->leftJoin($fromAlias, _DB_PREFIX_.$table, 'mp', 'mp.'.$field_name.' = '.$fromAlias.'.'.$field_name);
////
//
////        #------se l'utente a cliccato sull'ordinamento, aggiungo anche l'ordinamento
////        /** @var SearchCriteriaInterface $search_criteria */
////        $search_criteria = $params['search_criteria'];
////        if ($search_criteria->getOrderBy() === 'is_seller') {
////            $search_query_builder->orderBy('mp.seller', $search_criteria->getOrderWay());
////        }
////
////        #-------se l'utente ha selezionato un filtro ggiungo anche il filtro
////        foreach ($search_criteria->getFilters() as $filternae => $filter) {
////            if ($filternae === 'is_seller') {
////                $search_query_builder->andWhere('mp.active = :active')
////                    ->setParameter('seller', (bool)$filter);
////            }
////        }
//    }
//
//    /**
//     * aggiornamento prodotto
//     * @param Product $product
//     * @param $id_shop
//     */
//    public function actionObjectProductUpdateAfter($product, $id_shop)
//    {
//        if ($this->isEmployStaff() === true) {
//            return;
//        }
//        #-----
//        # se è stato richiamato l'aggiornamento, non si deve più richiamare altrimenti va in loop
//        #
//        if (self::$execProductUpdateAfter === true) {
//            return;
//        }
//        self::$execProductUpdateAfter = true;
//        #
//        #-------
//        # il prodotto è del seller?
//        #
//        $ps = ProductSupplier::getIdByProductAndSupplier($product->id, 0, $this->getSellerId());
//        #
//        # se non trovo il seller product esco
//        #
//        if (!$ps) {
//            return;
//        }
//
//    }
//
//    /**
//     * query lista ordini
//     *
//     * @param $params
//     * @param $employee_id
//     */
//    public function actionOrderGridQueryBuilderModifier($params, $id_employee, $id_shop)
//    {
//        # lo staff del marketplace deve vedere tutto
//        #
//        if ($this->isEmployStaff()) {
//            return;
//        }
//        if ($this->isEmployeeSeller() === false) {
//            return;
//        }
//        /** @var QueryBuilder $qb */
//        $qb = $params['search_query_builder'];
//
//        $qb->innerJoin('o', _DB_PREFIX_.'marketplace_seller_order', 'mp', 'o.id_order = mp.id_order');
//        $qb->andWhere('mp.id_supplier = :seller');
//        $qb->setParameter('seller', $this->getSellerId());
//
//        return;
//    }
//
    public function addSelectSellerFormBuilderModifier($seller, SellerChoiceProvider $choices, $params)
    {

        /**
         * @var FormBuilder $form
         */
        $form = $params['form_builder'];
        $form->add(
            'supplier',
            ChoiceType::class,
            array(
                'label' => 'Seller',
                'choices' => $choices->getChoices(),
                'data' => $seller ? $seller->getIdSupplier() : '',
            )
        );
    }
//
//    /**
//     * @param $object
//     * @param $params
//     */
    public function addSwitchSellerFormBuilderModifier($object, $params)
    {
        /**
         * @var FormBuilder $form
         */
        $form = $params['form_builder'];
        $form->add(
            'seller',
            SwitchType::class,
            array(
                'label' => 'Seller',
                'choices' => [
                    'OFF' => false,
                    'ON' => true,
                ],
                'data' => $object ? $object->isSeller() : false,

            )
        );
    }
//
    public function getEmployee()
    {
        return $this->legacyContext->getContext()->employee;
    }
//
//    public function getEmployeeId()
//    {
//        return Context::getContext()->employee->id;
//    }
//
//    /**
//     * @return Supplier
//     */
//    public function getSeller()
//    {
//        return new Supplier($this->marketplaceSeller->getIdSupplier());
//
//    }
//
//    /**
//     * @return int
//     */
//    public function getSellerId()
//    {
//        return $this->marketplaceSeller->getIdSupplier();
//
//    }
//
//    /**
//     * @return \Shop
//     */
//    public function getShop()
//    {
//        return $this->context->getContext()->shop;
//
//    }
//
//    /**
//     * controlla se il dipendente è dello staff del marketplace
//     * @return bool
//     * @todo implementare il controllo in base alla presenza o meno di profili specifici
//     */
    public function isEmployStaff()
    {

        return $this->legacyContext->getContext()->employee->isSuperAdmin();

    }
//
    public function isEmployeeSeller()
    {
        return (bool)$this->marketplaceSeller;

    }
//
//
//    /**
//     * replica il funzionamento del modulo
//     * @param $id
//     * @param array $parameters
//     * @param null $domain
//     * @param null $locale
//     * @return string
//     * @todo da aggiornare con symfony translator????
//     */
//    protected function trans($id, array $parameters = [], $domain = null, $locale = null)
//    {
//        $parameters['legacy'] = 'htmlspecialchars';
//
//        return $this->getTranslator()->trans($id, $parameters, $domain, $locale);
//    }
//
//    /**
//     * replica il funzionamento del modulo
//     * @return TranslatorComponent|null
//     * @todo da aggiornare con symfony translator????
//     */
//    private function getTranslator()
//    {
//        return Context::getContext()->getTranslator();
//
//    }
//
//    private function setMarketplaceSeller()
//    {
//        $this->marketplaceSeller = $this->entityManager->getRepository(MarketplaceSeller::class)
//            ->findOneBy(['id_employee' => $this->getEmployeeId(), 'id_shop' => $this->getShop()->id]);
//    }
//
//
}
