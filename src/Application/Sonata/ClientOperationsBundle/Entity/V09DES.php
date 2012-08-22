<?php

namespace Application\Sonata\ClientOperationsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Application\Sonata\ClientOperationsBundle\Entity\V09DES
 *
 * @ORM\Table("et_operations_V09DES")
 * @ORM\Entity
 */
class V09DES
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
     * @var float $montant_HT_en_devise
     *
     * @ORM\Column(name="montant_HT_en_devise", type="float")
     */
    private $montant_HT_en_devise;

    /**
     * @var float $mois
     *
     * @ORM\Column(name="mois", type="float")
     */
    private $mois;

    /**
     * @var string $mois_complementaire
     *
     * @ORM\Column(name="mois_complementaire", type="text")
     */
    private $mois_complementaire;

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
     * @var string $commentaires
     *
     * @ORM\Column(name="commentaires", type="text")
     */
    private $commentaires;


    /**
     * @return string
     */
    public function __toString()
    {
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
     * Set client_id
     *
     * @param integer $clientId
     * @return V09DES
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
     * Set tiers
     *
     * @param string $tiers
     * @return V09DES
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
     * @return V09DES
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
     * @return V09DES
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
     * @return V09DES
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
     * @return V09DES
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
     * @param float $montantHTEnDevise
     * @return V09DES
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
     * Set mois
     *
     * @param float $mois
     * @return V09DES
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
     * Set mois_complementaire
     *
     * @param string $moisComplementaire
     * @return V09DES
     */
    public function setMoisComplementaire($moisComplementaire)
    {
        $this->mois_complementaire = $moisComplementaire;

        return $this;
    }

    /**
     * Get mois_complementaire
     *
     * @return string
     */
    public function getMoisComplementaire()
    {
        return $this->mois_complementaire;
    }

    /**
     * Set taux_de_change
     *
     * @param string $tauxDeChange
     * @return V09DES
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
     * @param float $hT
     * @return V09DES
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
     * Set commentaires
     *
     * @param string $commentaires
     * @return V09DES
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
}
