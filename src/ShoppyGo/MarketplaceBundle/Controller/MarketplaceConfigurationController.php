<?php

namespace ShoppyGo\MarketplaceBundle\Controller;

use Bwlab\Marketplace\Classes\MarketplaceConfiguration;
use Bwlab\Marketplace\Domain\Seller\Command\ToggleSellerCommand;
use Bwlab\Marketplace\Entity\MarketplaceCategory;
use Bwlab\Marketplace\Entity\MarketplaceSeller;
use Bwlab\Marketplace\Entity\MarketplaceSellerShipping;
use Bwlab\Marketplace\Fixtures\DemoFixtures;
use Bwlab\Marketplace\Form\Type\ConfigurationType;
use PrestaShopBundle\Controller\Admin\FrameworkBundleAdminController;
use Symfony\Component\HttpFoundation\Request;

class MarketplaceConfigurationController extends FrameworkBundleAdminController
{

    public function configuration(Request $request)
    {
        #--------vedi   https://devdocs.prestashop.com/1.7/development/components/grid/#rendering-grid

        $core = $this->get('bwlab_core_marketplace');
        #
        # l'accesso alla configurazione del marketplace è solo per l'admin
        #
        if ($core->isEmployeeSeller()) {
            return $this->redirectToRoute('admin_suppliers_index');
        }

        $conf = $this->get('prestashop.adapter.legacy.configuration');


        $data = [];
        foreach (MarketplaceConfiguration::CONFIGURATION_KEYS as $key) {
            $data[$key] = $conf->get($key);
        }

        $form = $this->createForm(ConfigurationType::class, $data);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            foreach ($data as $conf_name => $conf_value) {
                $conf->set($conf_name, $conf_value);
            }
            $this->addFlash('success', 'Configurazione salvata');
        }

        return $this->render(
            '@Modules/bwmarketplace/views/templates/admin/controller/marketplace_configuration_configuration.html.twig',
            array(
                'form' => $form->createView(),
            )
        );
    }

    public function installDemo(Request $request)
    {
        $fixture = new DemoFixtures($this->get('bwmarketplace.module'), $this->getDoctrine());
        $fixture->resetMarketplaceDb();
        $fixture->resetData();
        $fixture->installSellerData();

        $this->addFlash('success', $this->trans('Demo data installd', 'Amind.Marketplace.Configuration'));

        return $this->redirectToRoute('admin_marketplace_configuration');
    }

    public function reinstallHooks(Request $request)
    {
//        if ($this->get('bwmarketplace.module')->installHooks()) {
//            $this->addFlash('success', $this->trans('Hooks reinstalled.', 'Amind.Marketplace.Configuration'));
//
//        } else {
//            $this->addFlash('error', $this->trans('Error in hooks reinstallation', 'Amind.Marketplace.Configuration'));
//
//        }

//        return $this->redirectToRoute('admin_marketplace_configuration');
    }

    public function toggleSellerCategory($id)
    {
        $this->getCommandBus()->handle(
            new ToggleSellerCommand((int)$id, 'id_category', null, MarketplaceCategory::class)
        );
        $this->addFlash('success', $this->trans('Toggle success!', ''));

        return $this->redirectToRoute('admin_categories_index');
    }

    public function toggleSellerEmployee($id)
    {
        $this->getCommandBus()->handle(
            new ToggleSellerCommand((int)$id, 'id_employee', null, MarketplaceSeller::class)
        );
        $this->addFlash('success', $this->trans('Toggle success!', ''));

        return $this->redirectToRoute('admin_employees_index');
    }
}
