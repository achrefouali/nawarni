<?php
/**
 * Created by PhpStorm.
 * User: acf
 * Date: 14/02/2019
 * Time: 12:42
 */

namespace SettingBundle\Controller;


use SettingBundle\Entity\claimCategory;
use SettingBundle\Form\claimCategoryType;
use SettingBundle\Form\FilterClaimType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class ClaimCategoryController extends Controller
{
    private $pageLimit = 10;
    private $total_entities = 0;


    /**
     * Store in the session service the current sort
     *
     * @param string $column The column
     * @param string $order_by The order sorting (ASC,DESC)
     */
    private function setSort($column, $order_by)
    {
        $this->get('session')->set('application_setting_claim_sort', $column);
        $this->get('session')->set('application_setting_claim_orderBy', strtoupper($order_by));

    }
    /**
     * This function create form type
     * @return \Symfony\Component\Form\Form
     */
    public function getFilterForm()
    {
        $filters = $this->getFilters();
        return $this->createForm(FilterClaimType::class, $filters, array());
    }
    /**
     * This function  get session filter
     * @return mixed
     */
    protected function getFilters()
    {
        return $this->get('session')->get('application_setting_claim_filter_type', []);
    }
    /**
     * Store in the session service the current filters
     *
     * @param array the filters
     */
    protected function setFilters($filters)
    {
        $this->get('session')->set('application_setting_claim_filter_type', $filters);
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function filterAction(Request $request)
    {
        if ($request->get('reset')) {
            $this->setFilters(array());
            return $this->redirect($this->generateUrl("application_setting_claim_list"));
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
        return $this->redirect($this->generateUrl("application_setting_claim_list"));
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
        $claim = $this->getSettingService()->getClaim($filters);

        $this->total_entities = $claim['total_result'];
        $paginatedclaim = $this->get('knp_paginator')
            ->paginate($claim['result'], $request->query->get('page', 1) ,$this->pageLimit);

        return $this->render('SettingBundle:claim:list.html.twig',array(
            'claim' => $paginatedclaim,
            'total_claim' => $this->total_entities,
            "filterForm" => $filterForm->createView()
        ));

    }
    /**
     * @return object
     */
    private function getSettingService(){
        return $this->get('application_setting_service');
    }
    /**
     *
     * @param Request $request
     *
     * @throws \HttpException
     */

    public function createAction(Request $request)
    {

        $claim = new claimCategory();
        $form = $this->createForm(claimCategoryType::class, $claim);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $this->getSettingService()->addClaim($claim);
//            $this->addFlash('success_action',
//                $this->get('translator')
//                    ->trans('setting.trainingType.message.flash.success.create',array('%type%' => $claim),'ApplicationSettingBundle'));

            return $this->redirectToRoute('application_setting_claim_list');
        }
        return $this->render('SettingBundle:claim:create.html.twig',
            array('form' => $form->createView(),
                'object' => $claim));
    }
    public function editAction(Request $request ,$id){
        $ad = $this->getDoctrine()->getRepository(claimCategory::class)->find($id);
        if(empty($ad)){
            throw new \Exception('Ad Category not found');
        }
        $form = $this->createForm(claimCategoryType::class, $ad);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $this->getSettingService()->addClaim($ad);
//            $this->addFlash('success_action',
//                $this->get('translator')
//                    ->trans('setting.trainingType.message.flash.success.create',array('%type%' => $ad),'ApplicationSettingBundle'));

            return $this->redirectToRoute('application_setting_claim_list');
        }
        return $this->render('SettingBundle:claim:create.html.twig',
            array('form' => $form->createView(),
                'object' => $ad));
    }


    /**
     * @param $id
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function disableAction($id, Request $request){

        $ad = $this->getDoctrine()->getRepository(claimCategory::class)->find($id);

        if(!is_object($ad)){
            throw new NotFoundHttpException( 'Ad Category not Found');
        }
        try{

            $this->getSettingService()->disableClaim($ad);
        }catch(\Exception $e){
            return $this->redirectToRoute('application_setting_claim_list');
        }
        return $this->redirectToRoute('application_setting_claim_list');

    }

    /**
     * @param $id
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function enableAction($id, Request $request){

        $ad = $this->getDoctrine()->getRepository(claimCategory::class)->find($id);
        if(!is_object($ad)){
            throw new NotFoundHttpException( 'Ad Category Not found ');
        }
        try{
            $this->getSettingService()->enableClaim($ad);
        }catch(\Exception $e){
            return $this->redirectToRoute('application_setting_claim_list');
        }
        return $this->redirectToRoute('application_setting_claim_list');

    }

    public function deleteAction($id,Request $request){
        $ad = $this->getDoctrine()->getRepository(claimCategory::class)->find($id);
        if(!is_object($ad)){
            throw new NotFoundHttpException( 'Ad Category Not found ');
        }
        try{
            $this->getSettingService()->deleteClaim($ad);
        }catch(\Exception $e){
            return $this->redirectToRoute('application_setting_claim_list');
        }
        return $this->redirectToRoute('application_setting_claim_list');
    }

}