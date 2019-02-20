<?php

namespace AppBundle\EventListener;

use Avanzu\AdminThemeBundle\Event\SidebarMenuEvent;
use Avanzu\AdminThemeBundle\Model\MenuItemModel;
use Symfony\Component\HttpFoundation\Request;

class SidebarListener
{
    public function onSetupMenu(SidebarMenuEvent $event)
    {
        $request = $event->getRequest();

        foreach ($this->getMenu($request) as $item) {
            $event->addItem($item);
        }

    }

    /**
     * Get the sidebar menu
     *
     * @param Request $request
     * @return mixed
     */
    protected function getMenu(Request $request)
    {
        $earg      = array();
        $rootItems = array(
            $dash = new MenuItemModel('dashboard', 'Tableau de bord', 'homepage', $earg, 'fa fa-dashboard'),
            $ad = new MenuItemModel('ad', 'Gestion des annonces', 'application_ad_list', $earg, 'fa fa-paper-plane'),
            $claim = new MenuItemModel('claim', 'Gestion des réclamation', 'application_ad_list', $earg, 'fa fa-bolt'),
            $settings = new MenuItemModel('setting', 'Paramétrage', '', $earg, 'fa  fa-cog'),
        );

        $settings->addChild(new MenuItemModel('adCategory', 'Catégories des annonces', 'application_setting_adCategory_list', $earg,'fa fa-bullhorn'));
        $settings->addChild(new MenuItemModel('adCategory', 'Catégories des réclamations', 'application_setting_claim_list', $earg,'fa  fa-exclamation-triangle'));
        return $this->activateByRoute($request->get('_route'), $rootItems);

    }

    /**
     * Set current menu item to be active
     *
     * @param $route
     * @param $items
     * @return mixed
     */
    protected function activateByRoute($route, $items) {

        foreach($items as $item) { /** @var $item MenuItemModel */
            if($item->hasChildren()) {
                $this->activateByRoute($route, $item->getChildren());
            }
            else {
                if($item->getRoute() == $route) {
                    $item->setIsActive(true);
                }
            }
        }

        return $items;
    }


}