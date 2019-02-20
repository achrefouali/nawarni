<?php
/**
 * Created by PhpStorm.
 * User: acf
 * Date: 14/02/2019
 * Time: 16:43
 */

namespace AdBundle\Controller;


use AdBundle\Entity\Ad;
use AdBundle\Form\AdType;
use AdBundle\Form\FilterType;
use SettingBundle\Entity\AdCategory;
use SettingBundle\Entity\ClaimCategory;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class AdController extends Controller
{

    private $pageLimit = 10;
    private $total_entities = 0;

    /**
     * @return object
     */
    private function getAdService(){
        return $this->get('application_ad_service');
    }
    /**
     * Store in the session service the current sort
     *
     * @param string $column The column
     * @param string $order_by The order sorting (ASC,DESC)
     */
    private function setSort($column, $order_by)
    {
        $this->get('session')->set('application_ad_sort', $column);
        $this->get('session')->set('application_ad_orderBy', strtoupper($order_by));

    }
    /**
     * This function create form type
     * @return \Symfony\Component\Form\Form
     */
    public function getFilterForm()
    {
        $filters = $this->getFilters();
        $category=$this->getDoctrine()->getRepository(AdCategory::class)->findCategory();
        return $this->createForm(FilterType::class, $filters, [
            'category'=>$category,
        ]);
    }
    /**
     * This function  get session filter
     * @return mixed
     */
    protected function getFilters()
    {
        return $this->get('session')->get('application_ad_filter_type', []);
    }
    /**
     * Store in the session service the current filters
     *
     * @param array the filters
     */
    protected function setFilters($filters)
    {
        $this->get('session')->set('application_ad_filter_type', $filters);
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function filterAction(Request $request)
    {
        if ($request->get('reset')) {
            $this->setFilters(array());
            return $this->redirect($this->generateUrl("application_ad_list"));
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
        return $this->redirect($this->generateUrl("application_ad_list"));
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
        $ad = $this->getAdService()->getAd($filters);

        $this->total_entities = $ad['total_result'];
        $paginatedAd = $this->get('knp_paginator')
            ->paginate($ad['result'], $request->query->get('page', 1) ,$this->pageLimit);

        return $this->render('AdBundle:ad:list.html.twig',array(
            'ad' => $paginatedAd,
            'total_ad' => $this->total_entities,
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
        $ad = $this->getDoctrine()->getRepository(Ad::class)->find($id);
        if(empty($ad)){
            throw new \Exception('Ad Category not found');
        }

        $form = $this->createForm(AdType::class, $ad);
        //dump(true);exit;
        $form->handleRequest($request);
        if ($request->getMethod() == "POST") {
            $fileAnnonce = $request->files->get('adbundle_ad')['annonceFile'];
            if (!is_null($fileAnnonce)) {
                $ad->setAnnonceFile($fileAnnonce->getClientOriginalName());
                $ad->setFileAnnonceFile($fileAnnonce);
                $ad->upload();
            }

            $this->getAdService()->editAd($ad);
//            $this->addFlash('success_action',
//                $this->get('translator')
//                    ->trans('setting.trainingType.message.flash.success.create',array('%type%' => $ad),'ApplicationSettingBundle'));

            return $this->redirectToRoute('application_ad_list');
        }
        return $this->render('AdBundle:ad:create.html.twig',
            array('form' => $form->createView(),
                'object' => $ad));
    }


    /**
     * @param $id
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function disableAction($id, Request $request){

        $ad = $this->getDoctrine()->getRepository(Ad::class)->find($id);

        if(!is_object($ad)){
            throw new NotFoundHttpException( 'Ad  not Found');
        }
        try{

            $this->getAdService()->disable($ad);
        }catch(\Exception $e){
            return $this->redirectToRoute('application_ad_list');
        }
        return $this->redirectToRoute('application_ad_list');

    }

    /**
     * @param $id
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function enableAction($id, Request $request){

        $ad = $this->getDoctrine()->getRepository(Ad::class)->find($id);
        if(!is_object($ad)){
            throw new NotFoundHttpException( 'Ad Category Not found ');
        }
        try{
            $this->getAdService()->enable($ad);
        }catch(\Exception $e){
            return $this->redirectToRoute('application_ad_list');
        }
        return $this->redirectToRoute('application_ad_list');

    }

    public function deleteAction($id,Request $request){
        $ad = $this->getDoctrine()->getRepository(Ad::class)->find($id);
        if(!is_object($ad)){
            throw new NotFoundHttpException( 'Ad  Not found ');
        }
        try{
            $this->getAdService()->delete($ad);
        }catch(\Exception $e){
            return $this->redirectToRoute('application_ad_list');
        }
        return $this->redirectToRoute('application_ad_list');
    }

}