<?php

namespace Application\Sonata\ClientOperationsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Application\Sonata\ClientOperationsBundle\Entity\V01TVA
 *
 * @ORM\Table("et_operations_V01TVA")
 * @ORM\Entity
 */
class V01TVA
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
     * @var string $tiers
     *
     * @ORM\Column(name="tiers", type="string", length=255)
     */
    private $tiers;

    /**
     * @var string $no_TVA_tiers
     *
     * @ORM\Column(name="no_TVA_tiers", type="string", length=255)
     */
    private $no_TVA_tiers;

    /**
     * @var \DateTime $date_piece
     *
     * @ORM\Column(name="date_piece", type="date")
     */
    private $date_piece;

    /**
     * @var string $numero_piece
     *
     * @ORM\Column(name="numero_piece", type="string", length=255)
     */
    private $numero_piece;

    /**
     * @var integer $devise_id
     *
     * @ORM\Column(name="devise_id", type="integer")
     */
    private $devise_id;

    /**
     * @var string $montant_HT_en_devise
     *
     * @ORM\Column(name="montant_HT_en_devise", type="float")
     */
    private $montant_HT_en_devise;

    /**
     * @var float $taux_de_TVA
     *
     * @ORM\Column(name="taux_de_TVA", type="float")
     */
    private $taux_de_TVA;

    /**
     * @var float $montant_TVA_francaise
     *
     * @ORM\Column(name="montant_TVA_francaise", type="float")
     */
    private $montant_TVA_francaise;

    /**
     * @var float $montant_TTC
     *
     * @ORM\Column(name="montant_TTC", type="float")
     */
    private $montant_TTC;

    /**
     * @var float $paiement_montant
     *
     * @ORM\Column(name="paiement_montant", type="float")
     */
    private $paiement_montant;

    /**
     * @var integer $paiement_devise_id
     *
     * @ORM\Column(name="paiement_devise_id", type="integer")
     */
    private $paiement_devise_id;

    /**
     * @var \DateTime $paiement_date
     *
     * @ORM\Column(name="paiement_date", type="date")
     */
    private $paiement_date;

    /**
     * @var string $mois
     *
     * @ORM\Column(name="mois", type="float")
     */
    private $mois;

    /**
     * @var string $taux_de_change
     *
     * @ORM\Column(name="taux_de_change", type="string", length=255)
     */
    private $taux_de_change;

    /**
     * @var float $HT
     *
     * @ORM\Column(name="HT", type="float")
     */
    private $HT;

    /**
     * @var float $TVA
     *
     * @ORM\Column(name="TVA", type="float")
     */
    private $TVA;

    /**
     * @var string $commentaires
     *
     * @ORM\Column(name="commentaires", type="text")
     */
    private $commentaires;

    /**
     *
     * @ORM\ManyToOne(targetEntity="Imports", inversedBy="ao2tva")
     * @ORM\JoinColumn(name="import_id", referencedColumnName="id")
     */
    private $imports;

    /**
     * @return string
     */
    public function __toString(){

        return $this->getTiers();
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
     * Set tiers
     *
     * @param string $tiers
     * @return V01_TVA
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
     * Set no_TVA_tiers
     *
     * @param string $noTVATiers
     * @return V01_TVA
     */
    public function setNoTVATiers($noTVATiers)
    {
        $this->no_TVA_tiers = $noTVATiers;
    
        return $this;
    }

    /**
     * Get no_TVA_tiers
     *
     * @return string 
     */
    public function getNoTVATiers()
    {
        return $this->no_TVA_tiers;
    }

    /**
     * Set date_piece
     *
     * @param \DateTime $datePiece
     * @return V01_TVA
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
     * Set numero_piece
     *
     * @param string $numeroPiece
     * @return V01_TVA
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
     * Set devise_id
     *
     * @param integer $deviseId
     * @return V01_TVA
     */
    public function setDeviseId($deviseId)
    {
        $this->devise_id = $deviseId;
    
        return $this;
    }

    /**
     * Get devise_id
     *
     * @return integer 
     */
    public function getDeviseId()
    {
        return $this->devise_id;
    }

    /**
     * Set montant_HT_en_devise
     *
     * @param string $montantHTEnDevise
     * @return V01_TVA
     */
    public function setMontantHTEnDevise($montantHTEnDevise)
    {
        $this->montant_HT_en_devise = $montantHTEnDevise;
    
        return $this;
    }

    /**
     * Get montant_HT_en_devise
     *
     * @return string 
     */
    public function getMontantHTEnDevise()
    {
        return $this->montant_HT_en_devise;
    }

    /**
     * Set taux_de_TVA
     *
     * @param float $tauxDeTVA
     * @return V01_TVA
     */
    public function setTauxDeTVA($tauxDeTVA)
    {
        $this->taux_de_TVA = $tauxDeTVA;
    
        return $this;
    }

    /**
     * Get taux_de_TVA
     *
     * @return float 
     */
    public function getTauxDeTVA()
    {
        return $this->taux_de_TVA;
    }

    /**
     * Set montant_TVA_francaise
     *
     * @param float $montantTVAFrancaise
     * @return V01_TVA
     */
    public function setMontantTVAFrancaise($montantTVAFrancaise)
    {
        $this->montant_TVA_francaise = $montantTVAFrancaise;
    
        return $this;
    }

    /**
     * Get montant_TVA_francaise
     *
     * @return float 
     */
    public function getMontantTVAFrancaise()
    {
        return $this->montant_TVA_francaise;
    }

    /**
     * Set montant_TTC
     *
     * @param float $montantTTC
     * @return V01_TVA
     */
    public function setMontantTTC($montantTTC)
    {
        $this->montant_TTC = $montantTTC;
    
        return $this;
    }

    /**
     * Get montant_TTC
     *
     * @return float 
     */
    public function getMontantTTC()
    {
        return $this->montant_TTC;
    }

    /**
     * Set paiement_montant
     *
     * @param float $paiementMontant
     * @return V01_TVA
     */
    public function setPaiementMontant($paiementMontant)
    {
        $this->paiement_montant = $paiementMontant;
    
        return $this;
    }

    /**
     * Get paiement_montant
     *
     * @return float 
     */
    public function getPaiementMontant()
    {
        return $this->paiement_montant;
    }

    /**
     * Set paiement_devise_id
     *
     * @param integer $paiementDeviseId
     * @return V01_TVA
     */
    public function setPaiementDeviseId($paiementDeviseId)
    {
        $this->paiement_devise_id = $paiementDeviseId;
    
        return $this;
    }

    /**
     * Get paiement_devise_id
     *
     * @return integer 
     */
    public function getPaiementDeviseId()
    {
        return $this->paiement_devise_id;
    }

    /**
     * Set paiement_date
     *
     * @param \DateTime $paiementDate
     * @return V01_TVA
     */
    public function setPaiementDate($paiementDate)
    {
        $this->paiement_date = $paiementDate;
    
        return $this;
    }

    /**
     * Get paiement_date
     *
     * @return \DateTime 
     */
    public function getPaiementDate()
    {
        return $this->paiement_date;
    }

    /**
     * Set mois
     *
     * @param string $mois
     * @return V01_TVA
     */
    public function setMois($mois)
    {
        $this->mois = $mois;
    
        return $this;
    }

    /**
     * Get mois
     *
     * @return string 
     */
    public function getMois()
    {
        return $this->mois;
    }

    /**
     * Set taux_de_change
     *
     * @param string $tauxDeChange
     * @return V01_TVA
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

    /**
     * Set HT
     *
     * @param float $HT
     * @return V01_TVA
     */
    public function setHT($HT)
    {
        $this->HT = $HT;
    
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
     * Set TVA
     *
     * @param float $TVA
     * @return V01_TVA
     */
    public function setTVA($TVA)
    {
        $this->TVA = $TVA;
    
        return $this;
    }

    /**
     * Get TVA
     *
     * @return float 
     */
    public function getTVA()
    {
        return $this->TVA;
    }

    /**
     * Set commentaires
     *
     * @param string $commentaires
     * @return V01_TVA
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
     * Set client_id
     *
     * @param integer $clientId
     * @return V01TVA
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
     * Set imports
     *
     * @param Application\Sonata\ClientOperationsBundle\Entity\Imports $imports
     * @return V01TVA
     */
    public function setImports(\Application\Sonata\ClientOperationsBundle\Entity\Imports $imports = null)
    {
        $this->imports = $imports;
    
        return $this;
    }

    /**
     * Get imports
     *
     * @return Application\Sonata\ClientOperationsBundle\Entity\Imports 
     */
    public function getImports()
    {
        return $this->imports;
    }
}