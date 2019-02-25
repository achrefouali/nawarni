<?php

namespace AppBundle\Controller;

use AdBundle\Entity\Ad;
use ClaimBundle\Entity\Claim;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;

class DashboardController extends Controller
{
    /**
     * @Route("/", name="homepage")
     * @return RedirectResponse
     */
    public function indexAction()
    {
        return new RedirectResponse($this->generateUrl('admin_dashboard'));
    }

    /**
     * @Route("/dashboard", name="admin_dashboard")
     * @return \Symfony\Component\HttpFoundation\Response
     */

    public function dashboardAction()
    {
         $widget =$this->getSettingService()->getAllWidget();
         $areaChart=$this->getSettingService()->getDataAreaChart();


        return $this->render('default/dashboard.html.twig',[
            'adCount'=>$widget['adNumber'],
            'claimNumber'=>$widget['claimNumber'],
            'claimCategoryNumber'=>$widget['claimCategoryNumber'],
            'adCategoryNumber'=>$widget['adCategoryNumber'],
            'areaChart'=>$areaChart,
        ]);
    }
    public function getSettingService(){
        return $this->get('application_setting_service');
    }
}
