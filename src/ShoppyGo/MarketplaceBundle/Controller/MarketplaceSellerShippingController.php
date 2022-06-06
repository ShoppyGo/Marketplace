<?php

namespace Bwlab\Marketplace\Controller;

use Bwlab\Marketplace\Classes\MarketplaceCore;
use Bwlab\Marketplace\Entity\MarketplaceSellerShipping;
use Bwlab\Marketplace\Form\Type\SellerChoiceType;
use PrestaShop\PrestaShop\Core\Grid\Search\SearchCriteria;
use PrestaShopBundle\Controller\Admin\FrameworkBundleAdminController;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\HttpFoundation\Request;

class MarketplaceSellerShippingController extends FrameworkBundleAdminController
{
    
    public function delete(Request $request)
    {
        /** @var MarketplaceCore $core */
        $core = $this->get('bwlab_core_marketplace');
        
        $criteria = $this->getCriteria($request, $core);
        $objectManager = $this->getDoctrine()->getManager();
        
        $record = $objectManager
            ->getRepository(MarketplaceSellerShipping::class)
            ->findOneBy($criteria);
        
        if ($record) {
            $objectManager->remove($record);
            $objectManager->flush();
        }
        
        return $this->redirectToRoute('admin_marketplace_seller_shipping');
    }
    
    public function edit(Request $request)
    {
        #
        /** @var MarketplaceCore $core */
        $core = $this->get('bwlab_core_marketplace');
        
        #
        # preparo nuovo record
        #
        $shipping_cost = new MarketplaceSellerShipping();
        #
        # vedo se viene richiesto un id
        #
        if ($request->get('id_shipping')) {
            
            $criteria = $this->getCriteria($request, $core);
            
            $shipping_cost = $this->getDoctrine()->getManager()
                ->getRepository(MarketplaceSellerShipping::class)->findOneBy(
                    $criteria
                );
            
            if (!$shipping_cost) {
                return $this->redirectToRoute('admin_marketplace_seller_shipping');
                
            }
        }
        #
        $builder = $this->createFormBuilder($shipping_cost);
        #
        $builder
            ->add(
                'from',
                MoneyType::class,
                [
                    'scale' => 2,
                    'label' => $this->trans('from', 'Admin.Marketplace.Shipping'),
                ]
            )
            ->add(
                'to',
                MoneyType::class,
                [
                    'scale' => 2,
                    'label' => $this->trans('to', 'Admin.Marketplace.Shipping'),
                ]
            )
            ->add(
                'cost',
                MoneyType::class,
                [
                    'scale' => 2,
                    'label' => $this->trans('cost', 'Admin.Marketplace.Shipping'),
                ]
            )
            ->add(
                'vat',
                MoneyType::class,
                [
                    'scale' => 2,
                    'label' => $this->trans('vat', 'Admin.Marketplace.Shipping'),
                ]
            );
        if ($core->isEmployStaff()) {
            $builder->add('id_employee', SellerChoiceType::class);
        }
        #
        $form = $builder->getForm();
        #
        $form->handleRequest($request);
        #
        # se i dati sono stati inviati allora procedo al salvataggio
        #
        if ($form->isSubmitted()) {
            #
            if ($form->isValid()) {
                #
                /** @var MarketplaceSellerShipping $data */
                $data = $form->getData();
                
                
                if ($core->isEmployeeSeller()) {
                    $data->setIdEmployee($core->getEmployee()->id);
                }
                $data->setIdShop($core->getShop()->id);
                
                $ranges = [];
                $m = $this->getDoctrine()->getManager();
                #
                # solo se non sono in modifica devo controllare l'accavallamento
                #
                if (!$request->get('id_shipping')) {
                    $ranges = $m->getRepository(MarketplaceSellerShipping::class)
                        ->getRanges(
                            $shipping_cost->getFrom(),
                            $shipping_cost->getTo(),
                            $data->getIdEmployee(),
                            $data->getIdShop()
                        );
                }
                if (count($ranges) === 0) {
                    try {
                        
                        $m->persist($data);
                        $m->flush();
                        
                        $this->addFlash('success', $this->trans('Shipping cost saved', 'Admin.Marketplace.Shipping'));
                        
                        #
                        return $this->redirectToRoute('admin_marketplace_seller_shipping');
                    } catch (\Exception $exception) {
                        $this->addFlash('error', $this->trans($exception->getMessage(), 'Admin.Marketplace.Shipping'));
                    }
                } else {
                    $this->addFlash('error', $this->trans('the cost overlaps', 'Admin.Marketplace.Shipping'));
                    
                }
                
                #
            } else {
                #
                $this->addFlash('error', 'Error save shipping', 'Admin.Marketplace.Shipping');
                #
                foreach ($form->getErrors() as $err) {
                    $this->addFlash('error', $err->getMessage());
                }
            }
        }
        
        return $this->render(
            '@Modules/bwmarketplace/views/templates/admin/controller/marketplace_seller_shipping_edit.html.twig',
            array(
                'form' => $form->createView(),
            )
        );
    }
    
    public function index(Request $request)
    {
        #--------vedi   https://devdocs.prestashop.com/1.7/development/components/grid/#rendering-grid
        # posso impostare la grid per la lista dello shipping
        # posso impostare la grid per la lista dello shipping
        
        $gridFactory = $this->get('bwlab_marketplace_grid_seller_shipping_factory');
        $productGrid = $gridFactory->getGrid(new SearchCriteria());
        
        return $this->render(
            '@Modules/bwmarketplace/views/templates/admin/controller/marketplace_seller_shipping_index.html.twig',
            array(
                'shipping_grid' => $this->presentGrid($productGrid),
            )
        );
    }
    
    /**
     * @param Request $request
     * @param MarketplaceCore $core
     * @return array
     */
    private function getCriteria(Request $request, MarketplaceCore $core): array
    {
        $criteria = [
            'id_shipping' => $request->get('id_shipping'),
            'id_shop' => $core->getShop()->id,
        ];
        #
        # controllo se l'utente prova ad editare un altro record da url
        #
        if ($core->isEmployStaff() === false) {
            $criteria['id_employee'] = $core->getSellerId();
        }
        
        return $criteria;
    }
    
    
}                                     