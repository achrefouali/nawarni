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
use Symfony\Component\Filesystem\Filesystem;

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
        $ad->setAnnonceFile($this->base64_to_jpeg($affiche, $ad));
        $this->em->persist($ad);
        $this->em->flush();
        return true ;
    }


    public function editAd($ad){
        $this->em->persist($ad);
        $this->em->flush();
        return $ad ;
     }
    public function getAd($filters,$paginator=false){

        return $this->em->getRepository('AdBundle:Ad')
            ->findRecordsByFilter(
                $filters,
                $paginator
            );
    }

    function base64_to_jpeg($base64_string, $ad)
    {
        $data = explode(',', $base64_string);
        $dataExtension = explode(';', $data[0]);
        $dataExtension2 = explode(':', $dataExtension[0]);
        $dataExtension3 = explode('/', $dataExtension2[1]);
        $extension = $dataExtension3[1];
        // open the output file for writing
        $fileSystem = new Filesystem();
        $filename = $ad->getId() . "." ."picture." . $extension;
        $fileSystem->touch($ad->getUploadRootDir() . "/" .$filename);
        $file = $ad->getUploadRootDir() . "/".$filename;
        $ifp = fopen($file, 'wb');
        fwrite($ifp, base64_decode($data[1]));
        // clean up the file resource
        fclose($ifp);

        return $filename;

    }


    public function disable($ad){
        $ad->setPublic(false);
        $this->em->flush();
        return $ad ;
    }
    public function enable($ad){
        $ad->setPublic(true);
        $this->em->flush();
        return $ad ;
    }
    public function delete($ad){
        $this->em->remove($ad);
        $this->em->flush();
        return true;



    }
}