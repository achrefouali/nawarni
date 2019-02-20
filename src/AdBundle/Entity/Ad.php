<?php

namespace AdBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Ad
 *
 * @ORM\Table(name="ad")
 * @ORM\Entity(repositoryClass="AdBundle\Repository\AdRepository")
 */
class Ad
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
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="date")
     */
    private $date;

    /**
     * @var string
     *
     * @ORM\Column(name="address", type="string", length=255)
     */
    private $address;

    /**
     * @var bool
     *
     * @ORM\Column(name="public", type="boolean")
     */
    private $public;


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
     * @return Ad
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
     * @return Ad
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
     * @param \DateTime $date
     *
     * @return Ad
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
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
     * @return Ad
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
     * Set public
     *
     * @param boolean $public
     *
     * @return Ad
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
     * @var \SettingBundle\Entity\AdCategory
     *
     * @ORM\ManyToOne(targetEntity="SettingBundle\Entity\AdCategory")
     */
    private $adCategory;

    /**
     * @return \SettingBundle\Entity\AdCategory
     */
    public function getAdCategory()
    {
        return $this->adCategory;
    }

    /**
     * @param \SettingBundle\Entity\AdCategory $adCategory
     */
    public function setAdCategory($adCategory)
    {
        $this->adCategory = $adCategory;
    }

    /**
     * @var string
     *
     * @ORM\Column(name="annonceFile", type="string", length=255, nullable=true)
     */
    private $annonceFile ;

    private $fileAnnonceFile ;

    private $tempAnnonceFile;

    /**
     * @return string
     */
    public function getAnnonceFile()
    {
        return $this->annonceFile;
    }

    /**
     * @param string $annonceFile
     */
    public function setAnnonceFile($annonceFile)
    {
        $this->annonceFile = $annonceFile;
    }

    /**
     * @return mixed
     */
    public function getFileAnnonceFile()
    {
        return $this->fileAnnonceFile;
    }

    /**
     * @param mixed $fileAnnonceFile
     */
    public function setFileAnnonceFile($fileAnnonceFile)
    {

        $this->fileAnnonceFile = $fileAnnonceFile;
        if (null !== $this->annonceFile && $fileAnnonceFile !== null ) {
            $this->tempAnnonceFile = $this->annonceFile;
            $this->annonceFile = null;
        }

    }

    /**
     * @return mixed
     */
    public function getTempAnnonceFile()
    {
        return $this->tempAnnonceFile;
    }

    /**
     * @param mixed $tempAnnonceFile
     */
    public function setTempAnnonceFile($tempAnnonceFile)
    {
        $this->tempAnnonceFile = $tempAnnonceFile;
    }

    public function upload()
    {
        // Si jamais il n'y a pas de fichier (champ facultatif)

            // Si on avait un ancien fichier, on le supprime
            if (null !== $this->tempAnnonceFile) {

                $oldFile = $this->getUploadRootDir() . '/' . $this->id . '.' . $this->tempAnnonceFile;
                if (file_exists($oldFile)) {
                    unlink($oldFile);
                }
            }

            // On déplace le fichier envoyé dans le répertoire de notre choix
            $this->fileAnnonceFile->move(
                $this->getUploadRootDir(), // Le répertoire de destination
                $this->id . '.' . $this->tempAnnonceFile  // Le nom du fichier à créer, ici « id.extension »
            );
            $this->annonceFile =$this->id . '.' . $this->tempAnnonceFile;
            $this->fileAnnonceFile = null;

    }



    public function getUploadRootDir( )
    {
        return __DIR__ . '/../../../web/' . $this->getUploadDir();
    }
    public function getUploadDir()
    {
        return 'uploads/ad';
    }
    public function __toString()
    {
        return $this->getTitle();
    }


}

