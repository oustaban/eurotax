<?php

namespace Application\Sonata\ClientOperationsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;


/**
 * Application\Sonata\ClientOperationsBundle\Entity\AbstractBaseEntity
 *
 * @ORM\MappedSuperclass
 */
abstract class AbstractBaseEntity
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
     * @var \DateTime $date_piece
     *
     * @ORM\Column(name="date_piece", type="date")
     */
    private $date_piece;

    /**
     *
     * @ORM\ManyToOne(targetEntity="Imports", inversedBy="AbstractBaseEntity")
     * @ORM\JoinColumn(name="import_id", referencedColumnName="id")
     */
    private $imports;

    /**
     *
     * @ORM\ManyToOne(targetEntity="ListStatuses", inversedBy="AbstractBaseEntity")
     * @ORM\JoinColumn(name="status_id", referencedColumnName="id")
     */
    private $status;


    public function __clone()
    {
        $this->id = null;
    }

    /**
     * Set client_id
     *
     * @param integer $clientId
     * @return AbstractBaseEntity
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
     * Set date_piece
     *
     * @param \DateTime $datePiece
     * @return AbstractBaseEntity
     */
    public function setDatePiece($datePiece)
    {
        $this->date_piece = $datePiece;

        return $this;
    }

    /**
     * Get date_piece
     *
     * @return \DateTime
     */
    public function getDatePiece()
    {
        return $this->date_piece;
    }

    /**
     * @return \DateTime
     */
    public function getDatePieceFormat()
    {
        $date_piece = new \DateTime($this->date_piece->format('Y-m-01'));
        return $date_piece;
    }

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
     * Set imports
     *
     * @param \Application\Sonata\ClientOperationsBundle\Entity\Imports $imports
     * @return AbstractBaseEntity
     */
    public function setImports(\Application\Sonata\ClientOperationsBundle\Entity\Imports $imports = null)
    {
        $this->imports = $imports;

        return $this;
    }

    /**
     * Get imports
     *
     * @return \Application\Sonata\ClientOperationsBundle\Entity\Imports
     */
    public function getImports()
    {
        return $this->imports;
    }

    /**
     * Set status
     *
     * @param ListStatuses $status
     * @return AbstractBaseEntity
     */
    public function setStatus(ListStatuses $status = null)
    {
        $this->status= $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return ListStatuses
     */
    public function getStatus()
    {
        return $this->status;
    }
}