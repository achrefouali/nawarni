<?php

namespace ClaimBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Claim
 *
 * @ORM\Table(name="claim")
 * @ORM\Entity(repositoryClass="ClaimBundle\Repository\ClaimRepository")
 */
class Claim
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text")
     */
    private $description;

    /**
     * @var date
     *
     * @ORM\Column(name="date", type="datetime")
     */
    private $date;

    /**
     * @var string
     *
     * @ORM\Column(name="address", type="text")
     */
    private $address;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set title
     *
     * @param string $title
     *
     * @return Claim
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Claim
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set date
     *
     * @param string $date
     *
     * @return Claim
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return string
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set address
     *
     * @param string $address
     *
     * @return Claim
     */
    public function setAddress($address)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Get address
     *
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @var bool
     *
     * @ORM\Column(name="public", type="boolean")
     */
    private $public;



    /**
     * Set public
     *
     * @param boolean $public
     *
     * @return Claim
     */
    public function setPublic($public)
    {
        $this->public = $public;

        return $this;
    }

    /**
     * Get public
     *
     * @return bool
     */
    public function getPublic()
    {
        return $this->public;
    }
    public function __construct()
    {
        $this->public = false ;
    }


    /**
     * @var \SettingBundle\Entity\ClaimCategory
     *
     * @ORM\ManyToOne(targetEntity="SettingBundle\Entity\ClaimCategory")
     */
    private $claimCategory;

    /**
     * @return \SettingBundle\Entity\ClaimCategory
     */
    public function getClaimCategory()
    {
        return $this->claimCategory;
    }

    /**
     * @param \SettingBundle\Entity\ClaimCategory $claimCategory
     */
    public function setClaimCategory($claimCategory)
    {
        $this->claimCategory = $claimCategory;
    }


    /**
     * @var string
     *
     * @ORM\Column(name="claimFile", type="string", length=255, nullable=true)
     */
    private $claimFile ;

    private $fileClaimFile ;

    private $tempClaimFile;

    /**
     * @return string
     */
    public function getClaimFile()
    {
        return $this->claimFile;
    }

    /**
     * @param string $claimFile
     */
    public function setClaimFile($claimFile)
    {
        $this->claimFile = $claimFile;
    }

    /**
     * @return mixed
     */
    public function getFileClaimFile()
    {
        return $this->fileClaimFile;
    }

    /**
     * @param mixed $fileAnnonceFile
     */
    public function setFileClaimFile($fileClaimFile)
    {

        $this->fileClaimFile = $fileClaimFile;
        if (null !== $this->claimFile && $fileClaimFile !== null ) {
            $this->tempClaimFile = $this->claimFile;
            $this->claimFile = null;
        }

    }

    /**
     * @return mixed
     */
    public function getTempClaimFile()
    {
        return $this->tempClaimFile;
    }

    /**
     * @param mixed $tempClaimFile
     */
    public function setTempClaimFile($tempClaimFile)
    {
        $this->tempClaimFile = $tempClaimFile;
    }

    public function upload()
    {
        // Si jamais il n'y a pas de fichier (champ facultatif)

        // Si on avait un ancien fichier, on le supprime
        if (null !== $this->tempClaimFile) {

            $oldFile = $this->getUploadRootDir() . '/' . $this->id . '.' . $this->tempClaimFile;
            if (file_exists($oldFile)) {
                unlink($oldFile);
            }
        }

        // On déplace le fichier envoyé dans le répertoire de notre choix
        $this->fileClaimFile->move(
            $this->getUploadRootDir(), // Le répertoire de destination
            $this->id . '.' . $this->tempClaimFile  // Le nom du fichier à créer, ici « id.extension »
        );
        $this->claimFile =$this->id . '.' . $this->tempClaimFile;
        $this->fileClaimFile = null;

    }



    public function getUploadRootDir( )
    {
        return __DIR__ . '/../../../web/' . $this->getUploadDir();
    }
    public function getUploadDir()
    {
        return 'uploads/claim';
    }
    public function __toString()
    {
        return $this->getTitle();
    }




}

