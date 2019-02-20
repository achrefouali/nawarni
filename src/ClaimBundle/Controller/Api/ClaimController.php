<?php
/**
 * Created by PhpStorm.
 * User: acf
 * Date: 20/02/2019
 * Time: 11:33
 */

namespace ClaimBundle\Controller\Api;


use FOS\RestBundle\Controller\FOSRestController;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ClaimController extends  FOSRestController
{
    /**
     * @return object
     */
    private function getClaimService(){
        return $this->get('application_claim_service');
    }
    /**
     *
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
    {"name"="claimCategory","dataType"="integer","required"=true,"description"="claimCategory"},
    {"name"="affiche","dataType"="integer","required"=true,"description"="affiche"},
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
            'claimCategory'=>$request->query->get('claimCategory'),
            'affiche'=>$request->query->get('affiche')
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
        $this->getClaimService()->createAd($variable);

        return new JsonResponse(
            [
                'success' => true,
                'data' => 'Claim created',
            ]
        );


    }



    /**
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     200 = "Renvoyé en cas de succès",
     *     500 = "Renvoyé en cas d'erreur"
     *   },parameters={
    {"name"="offset","dataType"="integer","required"=true,"description"="offset"},
    {"name"="limit","dataType"="integer","required"=true,"description"="limit"},
     *         }
     * )
     **
     * @return \Symfony\Component\HttpFoundation\Response
     *
     */
    public function getAllAction(Request $request   ){
        $variable=[
            'offset'=>$request->query->get('offset'),
            'limit'=>$request->query->get('limit'),
        ];

        $claim                    = $this->getClaimService()->getClaimApi($variable)
        ;
        return new Response(json_encode($claim));
    }
}