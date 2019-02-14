<?php
/**
 * Created by PhpStorm.
 * User: acf
 * Date: 13/02/2019
 * Time: 14:28
 */

namespace SettingBundle\Services;


use Doctrine\ORM\EntityManager;

class SettingService
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

    /**
     * @param $ad
     * @return mixed
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function addAdCategory($ad){
        $this->em->persist($ad);
        $this->em->flush();
        return $ad;
    }

    public function getAdCategories($filters,$paginator=false){
        return $this->em->getRepository('SettingBundle:AdCategory')
            ->findRecordsByFilter(
                $filters,
                $paginator
            );

    }
    public function disable($ad){
        $ad->setEnable(false);
        $this->em->flush();
        return $ad ;
    }
    public function enable($ad){
        $ad->setEnable(true);
        $this->em->flush();
        return $ad ;
    }
    public function delete($ad){
        $this->em->remove($ad);
        $this->em->flush();
        return true;


}
     public function getClaim($filters,$paginator=false){

         return $this->em->getRepository('SettingBundle:claimCategory')
             ->findRecordsByFilter(
                 $filters,
                 $paginator
             );
     }
     public function addClaim($claim){
         $this->em->persist($claim);
         $this->em->flush();
         return $claim;
     }


    public function disableClaim($claim){
        $claim->setEnable(false);
        $this->em->flush();
        return $claim ;
    }
    public function enableClaim($claim){
        $claim->setEnable(true);
        $this->em->flush();
        return $claim ;
    }
    public function deleteClaim($claim){
        $this->em->remove($claim);
        $this->em->flush();
        return true;


    }


    public function getAdCategoriesApi(){
        return $this->em->getRepository('SettingBundle:AdCategory')
            ->findRecords(

            );

    }


    public function getClaimCategoriesApi(){
        return $this->em->getRepository('SettingBundle:claimCategory')
            ->findRecords(

            );

    }
}