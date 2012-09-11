<?php

namespace Application\Sonata\ClientOperationsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;


/**
 * Application\Sonata\ClientOperationsBundle\Entity\AbstractSellEntity
 *
 * @ORM\MappedSuperclass
 */
abstract class AbstractSellEntity extends AbstractBaseEntity
{

    /**
     * @var string $tiers
     *
     * @ORM\Column(name="tiers", type="string", length=255)
     */
    private $tiers;


    /**
     * @var string $numero_piece
     *
     * @ORM\Column(name="numero_piece", type="string", length=255)
     */
    private $numero_piece;

    /**
     * @var float $mois
     *
     * @ORM\Column(name="mois", type="float")
     */
    private $mois;

    /**
     * @var float $HT
     *
     * @ORM\Column(name="HT", type="float")
     */
    private $HT;

    /**
     * @var integer $devise
     *
     * @ORM\ManyToOne(targetEntity="\Application\Sonata\ClientBundle\Entity\ListDevises", inversedBy="BaseListDevises")
     * @ORM\JoinColumn(name="devise_id", referencedColumnName="id")
     */

    private $devise;

    /**
     * @var float $montant_HT_en_devise
     *
     * @ORM\Column(name="montant_HT_en_devise", type="float")
     */
    private $montant_HT_en_devise;

    /**
     * @var string $taux_de_change
     *
     * @ORM\Column(name="taux_de_change", type="string", length=255)
     */
    private $taux_de_change;


    /**
     * @var string $commentaires
     *
     * @ORM\Column(name="commentaires", type="text")
     */
    private $commentaires;


    /**
     * @return string
     */
    public function __toString(){

        return $this->getTiers();
    }


    /**
     * Set commentaires
     *
     * @param string $commentaires
     * @return VAbstractBaseEntity
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
     * @return VAbstractBaseEntity
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
     * Set mois
     *
     * @param float $mois
     * @return VAbstractBaseEntity
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

    /**
     * Set numero_piece
     *
     * @param string $numeroPiece
     * @return VAbstractBaseEntity
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
     * Set HT
     *
     * @param float $hT
     * @return VAbstractBaseEntity
     */
    public function setHT($hT)
    {
        $this->HT = $hT;
    
        return $this;
    }

    /**
     * Get HT
     *
     * @return float 
     */
    public function getHT()
    {
        return $this->HT;
    }

    /**
     * Set devise
     *
     * @param Application\Sonata\ClientBundle\Entity\ListDevises $devise
     * @return VAbstractBaseEntity
     */
    public function setDevise(\Application\Sonata\ClientBundle\Entity\ListDevises $devise = null)
    {
        $this->devise = $devise;
    
        return $this;
    }

    /**
     * Get devise
     *
     * @return Application\Sonata\ClientBundle\Entity\ListDevises 
     */
    public function getDevise()
    {
        return $this->devise;
    }

    /**
     * Set montant_HT_en_devise
     *
     * @param float $montantHTEnDevise
     * @return VAbstractBaseEntity
     */
    public function setMontantHTEnDevise($montantHTEnDevise)
    {
        $this->montant_HT_en_devise = $montantHTEnDevise;
    
        return $this;
    }

    /**
     * Get montant_HT_en_devise
     *
     * @return float 
     */
    public function getMontantHTEnDevise()
    {
        return $this->montant_HT_en_devise;
    }

    /**
     * Set taux_de_change
     *
     * @param string $tauxDeChange
     * @return VAbstractBaseEntity
     */
    public function setTauxDeChange($tauxDeChange)
    {
        $this->taux_de_change = $tauxDeChange;
    
        return $this;
    }

    /**
     * Get taux_de_change
     *
     * @return string 
     */
    public function getTauxDeChange()
    {
        return $this->taux_de_change;
    }
}