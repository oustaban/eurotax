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
     * @var UploadedFile $file
     * @Assert\File(maxSize="6000000")
     */
    public $file;

    /**
     * @var string $document
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $document;


    /**
     * @var string $file_alias
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $file_alias;

    /**
     * @var integer $type_document
     *
     * @ORM\ManyToOne(targetEntity="ListTypeDocuments", inversedBy="document")
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
     * @ORM\Column(name="preavis", type="string", length=100, nullable=true )
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


    public $replace_extension = 'txt';

    public $allowed_extensions = null;
    public $denied_extensions = array(
        'php',
    );

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
    public function getDateDocument()
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
     * @return null|string
     */
    public function getAbsolutePath()
    {
        return null === $this->file_alias ? null : $this->getUploadDir() . '/' . $this->file_alias;
    }

    /**
     * @return null|string
     */
    public function getWebPath()
    {
        return null === $this->file_alias ? null : UPLOAD_DOCUMENTS_WEB_PATH . '/' . $this->file_alias;
    }


    /**
     * @return string
     */
    protected function getUploadDir()
    {
        return UPLOAD_DOCUMENTS_PATH;
    }

    /**
     * @return mixed
     */
    public function upload()
    {
        if (null === $this->file) {
            return;
        }

        $pathinfo = pathinfo($this->file->getClientOriginalName());

        $extension = $this->getAllowedExtension($pathinfo['extension']);

        $this->file_alias = md5($this->document) . time() . '.' . $extension;

        $this->file->move($this->getUploadDir(), $this->file_alias);

        $this->document = $pathinfo['filename']; #substr($this->file->getClientOriginalName(), 0, strpos($this->file->getClientOriginalName(), '.'));

        unset($this->file);
    }

    /**
     * @param $extension
     * @return string
     */
    private function getAllowedExtension($extension)
    {
        if ($this->allowed_extensions && is_array($this->allowed_extensions)) {

            if (!in_array($extension, $this->allowed_extensions)) {
                $extension = $this->replace_extension;
            }
        }

        if ($this->denied_extensions && is_array($this->denied_extensions)) {

            if (in_array($extension, $this->denied_extensions)) {
                $extension = $this->replace_extension;
            }
        }

        return $extension;
    }


    /**
     *
     */
    public function removeUpload()
    {
        $file = $this->getAbsolutePath();

        if ($file) {
            @unlink($file);
        }
    }

    /**
     * Set file_alias
     *
     * @param string $fileAlias
     * @return Document
     */
    public function setFileAlias($fileAlias)
    {
        $this->file_alias = $fileAlias;

        return $this;
    }

    /**
     * Get file_alias
     *
     * @return string
     */
    public function getFileAlias()
    {
        return $this->file_alias;
    }
}