<?php
/**
 * Created by PhpStorm.
 * User: acf
 * Date: 20/02/2019
 * Time: 11:33
 */

namespace ClaimBundle\Controller;


use ClaimBundle\Entity\Claim;
use ClaimBundle\Form\ClaimType;
use ClaimBundle\Form\FilterType;
use SettingBundle\Entity\ClaimCategory;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ClaimController extends  Controller
{

    private $pageLimit = 10;
    private $total_entities = 0;

    /**
     * @return object
     */
    private function getClaimService(){
        return $this->get('application_claim_service');
    }
    /**
     * Store in the session service the current sort
     *
     * @param string $column The column
     * @param string $order_by The order sorting (ASC,DESC)
     */
    private function setSort($column, $order_by)
    {
        $this->get('session')->set('application_claim_sort', $column);
        $this->get('session')->set('application_claim_orderBy', strtoupper($order_by));

    }
    /**
     * This function create form type
     * @return \Symfony\Component\Form\Form
     */
    public function getFilterForm()
    {
        $filters = $this->getFilters();
        $category=$this->getDoctrine()->getRepository(ClaimCategory::class)->findCategory();
        return $this->createForm(FilterType::class, $filters,
            ['category'=>$category]);
    }
    /**
     * This function  get session filter
     * @return mixed
     */
    protected function getFilters()
    {
        return $this->get('session')->get('application_claim_filter_type', []);
    }
    /**
     * Store in the session service the current filters
     *
     * @param array the filters
     */
    protected function setFilters($filters)
    {
        $this->get('session')->set('application_claim_filter_type', $filters);
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function filterAction(Request $request)
    {
        if ($request->get('reset')) {
            $this->setFilters(array());
            return $this->redirect($this->generateUrl("application_claim_list"));
        }
        if ($request->getMethod() == "POST") {
            $form = $this->getFilterForm();
            $form->handleRequest($request);
            if ($form->isValid()) {
                $filters = $form->getViewData();
            }
        }
        if ($request->getMethod() == "GET") {
            $filters = $request->query->all();
        }

        if (isset($filters)) {
            $this->setFilters($filters);
        }
        return $this->redirect($this->generateUrl("application_claim_list"));
    }
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Symfony\Component\Security\Core\Exception\AccessDeniedException
     */
    public function listAction(Request $request){

        if ($request->query->get('sort'))
        {
            $this->setSort($this->get('request_stack')->getCurrentRequest()->query->get('sort'), $this->get('request_stack')->getCurrentRequest()->query->get('order_by','DESC'));
        }
        $filterForm = $this->getFilterForm();
        $filters = $this->getFilters();
        $ad = $this->getClaimService()->getClaim($filters);

        $this->total_entities = $ad['total_result'];
        $paginatedClaim = $this->get('knp_paginator')
            ->paginate($ad['result'], $request->query->get('page', 1) ,$this->pageLimit);

        return $this->render('ClaimBundle:claim:list.html.twig',array(
            'claim' => $paginatedClaim,
            'total_claim' => $this->total_entities,
            "filterForm" => $filterForm->createView()
        ));

    }

    /**
     *
     * @param Request $request
     *
     * @throws \HttpException
     */


    public function editAction(Request $request ,$id){
        $claim = $this->getDoctrine()->getRepository(Claim::class)->find($id);
        if(empty($claim)){
            throw new \Exception('Claim  not found');
        }

        $form = $this->createForm(ClaimType::class, $claim);
        //dump(true);exit;
        $form->handleRequest($request);
        if ($request->getMethod() == "POST") {
            $fileClaim = $request->files->get('claimbundle_claim')['claimFile'];
            if (!is_null($fileClaim)) {
                $claim->setClaimFile($fileClaim->getClientOriginalName());
                $claim->setFileClaimFile($fileClaim);
                $claim->upload();
            }

            $this->getClaimService()->editClaim($claim);
//            $this->addFlash('success_action',
//                $this->get('translator')
//                    ->trans('setting.trainingType.message.flash.success.create',array('%type%' => $ad),'ApplicationSettingBundle'));

            return $this->redirectToRoute('application_claim_list');
        }
        return $this->render('ClaimBundle:claim:create.html.twig',
            array('form' => $form->createView(),
                'object' => $claim));
    }


    /**
     * @param $id
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function disableAction($id, Request $request){

        $claim = $this->getDoctrine()->getRepository(Claim::class)->find($id);

        if(!is_object($claim)){
            throw new NotFoundHttpException( 'Claim  not Found');
        }
        try{

            $this->getClaimService()->disable($claim);
        }catch(\Exception $e){
            return $this->redirectToRoute('application_claim_list');
        }
        return $this->redirectToRoute('application_claim_list');

    }

    /**
     * @param $id
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function enableAction($id, Request $request){

        $claim = $this->getDoctrine()->getRepository(Claim::class)->find($id);
        if(!is_object($claim)){
            throw new NotFoundHttpException( 'Claimy Not found ');
        }
        try{
            $this->getClaimService()->enable($claim);
        }catch(\Exception $e){
            return $this->redirectToRoute('application_claim_list');
        }
        return $this->redirectToRoute('application_claim_list');

    }

    public function deleteAction($id,Request $request){
        $ad = $this->getDoctrine()->getRepository(Claim::class)->find($id);
        if(!is_object($ad)){
            throw new NotFoundHttpException( 'Claim  Not found ');
        }
        try{
            $this->getClaimService()->delete($ad);
        }catch(\Exception $e){
            return $this->redirectToRoute('application_claim_list');
        }
        return $this->redirectToRoute('application_claim_list');
    }


}