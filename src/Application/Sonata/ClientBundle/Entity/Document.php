<?php

namespace Application\Sonata\ClientBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Application\Sonata\ClientBundle\Entity\Document
 *
 * @ORM\Table("et_document")
 * @ORM\Entity
 */
class Document
{
    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var integer $client_id
     *
     * @ORM\Column(name="client_id", type="integer")
     */
    private $client_id;

    /**
     * @var string $document
     *
     * @ORM\Column(name="document", type="string", length=255)
     * @Assert\File(maxSize="6000000")
     */
    private $document;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    public $path;

    /**
     * @var integer $type_document
     *
     * @ORM\ManyToOne(targetEntity="ListTypeDocuments", inversedBy="client")
     * @ORM\JoinColumn(name="type_document_id", referencedColumnName="id")
     */
    private $type_document;


    /**
     * @var \DateTime $date_document
     *
     * @ORM\Column(name="date_document", type="date")
     */
    private $date_document;

    /**
     * @var string $preavis
     *
     * @ORM\Column(name="preavis", type="string", length=100 )
     */
    private $preavis;

    /**
     * @var string $particularite
     *
     * @ORM\Column(name="particularite", type="text")
     */
    private $particularite;

    /**
     * @var \DateTime $date_notaire
     *
     * @ORM\Column(name="date_notaire", type="date", nullable=true)
     */
    private $date_notaire;

    /**
     * @var \DateTime $date_apostille
     *
     * @ORM\Column(name="date_apostille", type="date", nullable=true)
     */
    private $date_apostille;


    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set client_id
     *
     * @param integer $clientId
     * @return Document
     */
    public function setClientId($clientId)
    {
        $this->client_id = $clientId;

        return $this;
    }

    /**
     * Get client_id
     *
     * @return integer
     */
    public function getClientId()
    {
        return $this->client_id;
    }

    /**
     * Set document
     *
     * @param string $document
     * @return Document
     */
    public function setDocument($document)
    {
        $this->document = $document;

        return $this;
    }

    /**
     * Get document
     *
     * @return string
     */
    public function getDocument()
    {
        return $this->document;
    }


    /**
     * Set date
     *
     * @param \DateTime $date_document
     * @return Document
     */
    public function setDateDocument($date_document)
    {
        $this->date_document = $date_document;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getsetDateDocument()
    {
        return $this->date_document;
    }

    /**
     * Set preavis
     *
     * @param string $preavis
     * @return Document
     */
    public function setPreavis($preavis)
    {
        $this->preavis = $preavis;

        return $this;
    }

    /**
     * Get preavis
     *
     * @return string
     */
    public function getPreavis()
    {
        return $this->preavis;
    }

    /**
     * Set particularite
     *
     * @param string $particularite
     * @return Document
     */
    public function setParticularite($particularite)
    {
        $this->particularite = $particularite;

        return $this;
    }

    /**
     * Get particularite
     *
     * @return string
     */
    public function getParticularite()
    {
        return $this->particularite;
    }

    /**
     * Set date_notaire
     *
     * @param \DateTime $dateNotaire
     * @return Document
     */
    public function setDateNotaire($dateNotaire)
    {
        $this->date_notaire = $dateNotaire;

        return $this;
    }

    /**
     * Get date_notaire
     *
     * @return \DateTime
     */
    public function getDateNotaire()
    {
        return $this->date_notaire;
    }

    /**
     * Set date_apostille
     *
     * @param \DateTime $dateApostille
     * @return Document
     */
    public function setDateApostille($dateApostille)
    {
        $this->date_apostille = $dateApostille;

        return $this;
    }

    /**
     * Get date_apostille
     *
     * @return \DateTime
     */
    public function getDateApostille()
    {
        return $this->date_apostille;
    }

    /**
     * @return string
     */

    public function __toString()
    {

        return $this->getDocument();
    }

    /**
     * Get date_document
     *
     * @return \DateTime
     */
    public function getDateDocument()
    {
        return $this->date_document;
    }


    /**
     * Set type_document
     *
     * @param Application\Sonata\ClientBundle\Entity\ListTypeDocuments $typeDocument
     * @return Document
     */
    public function setTypeDocument(\Application\Sonata\ClientBundle\Entity\ListTypeDocuments $typeDocument = null)
    {
        $this->type_document = $typeDocument;

        return $this;
    }

    /**
     * Get type_document
     *
     * @return Application\Sonata\ClientBundle\Entity\ListTypeDocuments
     */
    public function getTypeDocument()
    {
        return $this->type_document;
    }

    /**
     * Set path
     *
     * @param string $path
     * @return Document
     */
    public function setPath($path)
    {
        $this->path = $path;

        return $this;
    }

    /**
     * Get path
     *
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    public function getAbsolutePath()
    {
        return null === $this->path ? null : $this->getUploadRootDir() . '/' . $this->id . '.' . $this->path;
    }

    public function getWebPath()
    {
        return null === $this->path ? null : $this->getUploadDir() . '/' . $this->path;
    }

    protected function getUploadRootDir()
    {
        return __DIR__ . '/../../../../web/' . $this->getUploadDir();
    }

    protected function getUploadDir()
    {
        return 'uploads/documents';
    }

    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function preUpload()
    {
        if (null !== $this->document) {
            $this->path = uniqid() . '.' . $this->file->guessExtension();
        }
    }

    /**
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */
    public function upload()
    {
        // the file property can be empty if the field is not required
        if (null === $this->document) {
            return;
        }

        // we use the original file name here but you should
        // sanitize it at least to avoid any security issues

        // move takes the target directory and then the target filename to move to
        //$this->document->move($this->getUploadRootDir(), $this->document->getClientOriginalName());
        $this->document->move($this->getUploadRootDir(), $this->path);
        //$this->document->move($this->getUploadRootDir(), $this->id.'.'.$this->file->guessExtension());

        unset($this->document);
        // set the path property to the filename where you'ved saved the file
        #  $this->path = $this->document->getClientOriginalName();

        // clean up the document property as you won't need it anymore
        #   $this->document = null;
    }

    /**
     * @ORM\PreRemove()
     */
    public function storeFilenameForRemove()
    {
        $this->filenameForRemove = $this->getAbsolutePath();
    }

    /**
     * @ORM\PostRemove()
     */
    public function removeUpload()
    {
        if ($file = $this->getAbsolutePath()) {
            unlink($file);
        }
    }
}