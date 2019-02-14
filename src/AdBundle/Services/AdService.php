<?php
/**
 * Created by PhpStorm.
 * User: acf
 * Date: 14/02/2019
 * Time: 16:26
 */

namespace AdBundle\Services;

use AdBundle\Entity\Ad;
use Doctrine\ORM\EntityManager;
use SettingBundle\Entity\AdCategory;

class AdService
{
    private $em;
    /**
     * Construct
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;

    }

    public function createAd($variable){
        extract($variable);
        $ad = new Ad();
        $ad->setTitle($title);
        $ad->setDescription($description);
        $date = new \DateTime($date);
        $ad->setDate($date);
        $ad->setAddress($address);
        $category = $this->em->getRepository(AdCategory::class)->find($adCategory);
        $ad->setAdCategory($category);
        $this->em->persist($ad);
        $this->em->flush();
        return true ;
    }


    public function getAd($filters,$paginator=false){

        return $this->em->getRepository('AdBundle:Ad')
            ->findRecordsByFilter(
                $filters,
                $paginator
            );
    }


}