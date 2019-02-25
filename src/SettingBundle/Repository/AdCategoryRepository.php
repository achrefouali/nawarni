<?php

namespace SettingBundle\Repository;

/**
 * AdCategoryRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class AdCategoryRepository extends \Doctrine\ORM\EntityRepository
{



    /**
     * findRecordsByFilter
     * This function allow to find filter
     * @param $filter
     * @param string $sort
     * @param string $orderBy
     * @param bool $paginator
     * @return array|\Doctrine\ORM\Query
     */
    public function findRecordsByFilter($filter, $paginator = false, $sort = 'id', $orderBy = 'DESC')
    {

        $queryBuilder = $this->createQueryBuilder('c');
        $queryBuilder->select('c');



        if ((isset($filter['title']) && !empty($filter['title']))) {
            $queryBuilder->andWhere('c.title LIKE  :title')->setParameter('title', '%' . $filter['title'] . '%');
        }

        $queryBuilder->orderBy('c.'.$sort, $orderBy);

        if($paginator == false){
            $result =  $queryBuilder->getQuery()->getResult();
        }else{
            $result =  $queryBuilder->getQuery();
        }


        return ['total_result' => count($queryBuilder->getQuery()->getResult()) , 'result' => $result];
    }


    public function findRecords()
    {

        $queryBuilder = $this->createQueryBuilder('c');
        $queryBuilder->select('c');
        $queryBuilder->andWhere('c.enable = :enabled');
        $queryBuilder->setParameter('enabled',1);
        $result=[];
        foreach($queryBuilder->getQuery()->getResult() as $item){
            $result[$item->getId()]=['title'=>$item->getTitle(),
                                     'id'=>$item->getId()];
        }


        return ['total_result' => count($queryBuilder->getQuery()->getResult()) , 'result' => $result];
    }
    public function findCategory(){
        $queryBuilder = $this->createQueryBuilder('c');
        $queryBuilder->select('c');
        $result=[];
        foreach($queryBuilder->getQuery()->getResult() as $item){
            $result[$item->getId()]=$item->getTitle();
        }
        return $result;
    }

    public function getCoutClaim(){
        $queryBuilder = $this->createQueryBuilder('c');
        $queryBuilder->select('c');
        return sizeof($queryBuilder->getQuery()->getResult());
    }

}