<?php
/**
 * Created by PhpStorm.
 * User: acf
 * Date: 14/02/2019
 * Time: 16:11
 */

namespace AdBundle\Controller\Api;


use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Request\ParamFetcherInterface;
use FOS\RestBundle\Controller\Annotations;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class AdController extends  FOSRestController
{


    /**
     * @return object
     */
    private function getAdService(){
        return $this->get('application_ad_service');
    }
    /**
     *
     * Lister les utilisateurs.

     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     200 = "Renvoyé en cas de succès",
     *     500 = "Renvoyé en cas d'erreur"
     *   },parameters={
    {"name"="title","dataType"="string","required"=true,"description"="titre"},
    {"name"="description","dataType"="text","required"=true,"description"="description"},
    {"name"="date","dataType"="date","required"=true,"description"="date"},
    {"name"="address","dataType"="string","required"=true,"description"="address"},
    {"name"="adCategory","dataType"="integer","required"=true,"description"="adCategory"},
*         }
     * )
     *
     *
     * @param \FOS\RestBundle\Request\ParamFetcherInterface $paramFetcher
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     */
    public function createAction(Request $request){


        $variable=[
            'title'=>$request->query->get('title'),
            'description'=>$request->query->get('description'),
            'date'=>$request->query->get('date'),
            'address'=>$request->query->get('address'),
            'adCategory'=>$request->query->get('adCategory'),
        ];
        foreach($variable as $item=>$value){

            if($value == ""){
                return new JsonResponse(
                    [
                        'success' => false,
                        'data' => 'There is an empty parameter ',
                    ]
                );
            }
        }
        $this->getAdService()->createAd($variable);

        return new JsonResponse(
            [
                'success' => true,
                'data' => 'Ad created',
            ]
        );


    }

}