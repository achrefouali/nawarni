<?php
/**
 * Created by PhpStorm.
 * User: acf
 * Date: 20/02/2019
 * Time: 11:34
 */

namespace ClaimBundle\Services;


use ClaimBundle\Entity\Claim;
use Doctrine\ORM\EntityManager;
use SettingBundle\Entity\ClaimCategory;
use Symfony\Component\Filesystem\Filesystem;

class ClaimService
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
        $claim = new Claim();
        $claim->setTitle($title);
        $claim->setDescription($description);

        $date = new \DateTime($date);

        $claim->setDate($date);
        $claim->setAddress($address);
        $category = $this->em->getRepository(ClaimCategory::class)->find($claimCategory);
        $claim->setClaimCategory($category);
        $this->em->persist($claim);
        $this->em->flush();
        $claim->setClaimFile($this->base64_to_jpeg($affiche, $claim));
        $this->em->persist($claim);
        $this->em->flush();
        return true ;
    }


    public function editClaim($ad){
        $this->em->persist($ad);
        $this->em->flush();
        return $ad ;
    }
    public function getClaim($filters,$paginator=false){

        return $this->em->getRepository('ClaimBundle:Claim')
            ->findRecordsByFilter(
                $filters,
                $paginator
            );
    }

    function base64_to_jpeg($base64_string, $claim)
    {
        $data = explode(',', $base64_string);
        $dataExtension = explode(';', $data[0]);
        $dataExtension2 = explode(':', $dataExtension[0]);
        $dataExtension3 = explode('/', $dataExtension2[1]);
        $extension = $dataExtension3[1];
        // open the output file for writing
        $fileSystem = new Filesystem();
        $filename = $claim->getId() . "." ."picture." . $extension;
        $fileSystem->touch($claim->getUploadRootDir() . "/" .$filename);
        $file = $claim->getUploadRootDir() . "/".$filename;
        $ifp = fopen($file, 'wb');
        fwrite($ifp, base64_decode($data[1]));
        // clean up the file resource
        fclose($ifp);

        return $filename;

    }


    public function disable($claim){
        $claim->setPublic(false);
        $this->em->flush();
        return $claim ;
    }
    public function enable($claim){
        $claim->setPublic(true);
        $this->em->flush();
        return $claim ;
    }
    public function delete($claim){
        $this->em->remove($claim);
        $this->em->flush();
        return true;



    }


    public function getClaimApi($variable){
        return $this->em->getRepository('ClaimBundle:Claim')
            ->findRecords(
                $variable
            );
    }

}