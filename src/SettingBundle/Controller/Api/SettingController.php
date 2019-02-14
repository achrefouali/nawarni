<?php
/**
 * Created by PhpStorm.
 * User: acf
 * Date: 13/02/2019
 * Time: 14:26
 */

namespace SettingBundle\Controller\Api;

use FOS\RestBundle\Controller\FOSRestController;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class SettingController extends FOSRestController
{


    /**
     * @return object
     */
    private function getSettingService(){
        return $this->get('application_setting_service');
    }
    /**
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     200 = "Renvoyé en cas de succès",
     *     500 = "Renvoyé en cas d'erreur"
     *   }
     * )
     **
     * @return \Symfony\Component\HttpFoundation\Response
     *
     */
    public function getAdAllAction(Request $request   ){

        $adCategory                    = $this->getSettingService()->getAdCategoriesApi()
        ;
        return new Response(json_encode($adCategory));
    }
    /**
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     200 = "Renvoyé en cas de succès",
     *     500 = "Renvoyé en cas d'erreur"
     *   }
     * )
     **
     * @return \Symfony\Component\HttpFoundation\Response
     *
     */
    public function getClaimAllAction(Request $request){

        $claim                    = $this->getSettingService()->getClaimCategoriesApi()
        ;
        return new Response(json_encode($claim));
    }

}