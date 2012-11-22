<?php

namespace Application\Sonata\ClientOperationsBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;


/**
 * Application\Sonata\ClientOperationsBundle\Entity\AbstractBuyEntity
 *
 * @ORM\MappedSuperclass
 */
abstract class AbstractBuyEntity extends AbstractBaseEntity
{

    /**
     * @var string $tiers
     *
     * @Assert\NotBlank()
     * @ORM\Column(name="tiers", type="string", length=255)
     */
    private $tiers;


    /**
     * @var string $numero_piece
     *
     * @Assert\NotBlank()
     * @ORM\Column(name="numero_piece", type="string", length=255)
     */
    private $numero_piece;


    /**
     * @var float $mois
     *
     * @ORM\Column(name="mois", type="date", nullable=true)
     */
    private $mois;


    /**
     * @var string $commentaires
     *
     * @ORM\Column(name="commentaires", type="text", nullable=true)
     */
    private $commentaires;


    /**
     * @return string
     */
    public function __toString()
    {
        return (string)$this->getTiers() ? : '-';
    }


    /**
     * Set commentaires
     *
     * @param string $commentaires
     * @return AbstractBuyEntity
     */
    public function setCommentaires($commentaires)
    {
        $this->commentaires = $commentaires;

        return $this;
    }

    /**
     * Get commentaires
     *
     * @return string
     */
    public function getCommentaires()
    {
        return $this->commentaires;
    }

    /**
     * Set tiers
     *
     * @param string $tiers
     * @return AbstractBuyEntity
     */
    public function setTiers($tiers)
    {
        $this->tiers = $tiers;

        return $this;
    }

    /**
     * Get tiers
     *
     * @return string
     */
    public function getTiers()
    {
        return $this->tiers;
    }

    /**
     * Set numero_piece
     *
     * @param string $numeroPiece
     * @return AbstractBuyEntity
     */
    public function setNumeroPiece($numeroPiece)
    {
        $this->numero_piece = $numeroPiece;

        return $this;
    }

    /**
     * Get numero_piece
     *
     * @return string
     */
    public function getNumeroPiece()
    {
        return $this->numero_piece;
    }

    /**
     * Set mois
     *
     * @param float $mois
     * @return AbstractBuyEntity
     */
    public function setMois($mois)
    {
        $this->mois = $mois;

        return $this;
    }

    /**
     * Get mois
     *
     * @return float
     */
    public function getMois()
    {
        return $this->mois;
    }


}