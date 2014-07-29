<?php

namespace Application\Sonata\ClientBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use Application\Sonata\ClientOperationsBundle\Helpers\ClientDeclaration;
use Application\Sonata\ClientOperationsBundle\Helpers\ClientDeclarationComputation;


/**
 * Application\Sonata\ClientBundle\Entity\Client
 *
 * @ORM\Table("et_client")
 * @ORM\Entity(repositoryClass="Application\Sonata\ClientBundle\Entity\ClientRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Client
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
     * @var integer  $code_client
     *
     * @ORM\Column(name="code_client", type="integer")
     */
    protected $code_client;

    /**
     * @var integer $user_id
     *
     * @ORM\ManyToOne(targetEntity="\Application\Sonata\UserBundle\Entity\User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    protected $user;

    /**
     * @var string $nom
     *
     * @Assert\NotBlank()
     * @ORM\Column(name="nom", type="string", length=200)
     */
    private $nom;

    /**
     * @var integer $nature_du_client_id
     *
     * @ORM\ManyToOne(targetEntity="ListNatureDuClients")
     * @ORM\JoinColumn(name="nature_du_client_id" , referencedColumnName="id")
     */
    protected $nature_du_client;

    /**
     * @var string $raison_sociale
     *
     * @Assert\NotBlank()
     * @ORM\Column(name="raison_sociale", type="string", length=100)
     */
    private $raison_sociale = "";

    /**
     * @var string $adresse_1_postal
     *
     * @Assert\NotBlank()
     * @ORM\Column(name="adresse_1_postal", type="string", length=100)
     */
    private $adresse_1_postal = "";

    /**
     * @var string $adresse_2_postal
     *
     * @ORM\Column(name="adresse_2_postal", type="string", length=100, nullable=true)
     */
    private $adresse_2_postal = "";

    /**
     * @var string $code_postal_postal
     *
     * @Assert\NotBlank()
     * @ORM\Column(name="code_postal_postal", type="string", length=20)
     */
    private $code_postal_postal = "";

    /**
     * @var string $ville_postal
     *
     * @Assert\NotBlank()
     * @ORM\Column(name="ville_postal", type="string", length=50)
     */
    private $ville_postal = "";

    /**
     * @var string $pays_id_postal
     *
     * @Assert\NotBlank()
     * @ORM\ManyToOne(targetEntity="ListCountries")
     * @ORM\JoinColumn(name="pays_id_postal", referencedColumnName="code")
     */
    private $pays_postal;

    /**
     * @var string $activite
     *
     * @ORM\Column(name="activite", type="string", length=200, nullable=true)
     */
    private $activite = "";

    /**
     * @var \DateTime $date_debut_mission
     *
     * @Assert\NotBlank()
     * @ORM\Column(name="date_debut_mission", type="date")
     */
    private $date_debut_mission;

    /**
     * @var integer $mode_denregistrement_id
     *
     * @ORM\ManyToOne(targetEntity="ListModeDenregistrements")
     * @ORM\JoinColumn(name="mode_denregistrement_id", referencedColumnName="id")
     */

    private $mode_denregistrement;

    /**
     * @var string $siret
     *
     * @ORM\Column(name="siret", type="string", length=100, nullable=true)
     */
    private $siret = "";

    /**
     * @var integer $periodicite_facturation_id
     *
     * @ORM\ManyToOne(targetEntity="ListPeriodiciteFacturations")
     * @ORM\JoinColumn(name="periodicite_facturation_id", referencedColumnName="id")
     */
    private $periodicite_facturation;

    /**
     * @var string $num_dossier_fiscal
     *
     * @ORM\Column(name="num_dossier_fiscal", type="string", length=255, nullable=true)
     */
    private $num_dossier_fiscal = "";

    /**
     * @var boolean $taxe_additionnelle
     *
     * @ORM\Column(name="taxe_additionnelle", type="boolean", nullable=true)
     */
    private $taxe_additionnelle = false;

    /**
     * @var integer $periodicite_CA3_id
     *
     * @ORM\ManyToOne(targetEntity="ListPeriodiciteFacturations")
     * @ORM\JoinColumn(name="periodicite_CA3_id", referencedColumnName="id")
     */
    private $periodicite_CA3;

    /**
     * @var integer $center_des_impots
     *
     * @ORM\ManyToOne(targetEntity="\Application\Sonata\ImpotsBundle\Entity\Impots")
     * @ORM\JoinColumn(name="center_des_impots_id", referencedColumnName="id")
     */
    private $center_des_impots;

    /**
     * @var integer $language
     *
     * @ORM\ManyToOne(targetEntity="ListLanguages")
     * @ORM\JoinColumn(name="language_id", referencedColumnName="id")
     */
    private $language;


    /**
     * @var string $autre_destinataire_de_facturation
     *
     * @ORM\Column(name="autre_destinataire_de_facturation", type="boolean", nullable=true)
     */
    private $autre_destinataire_de_facturation;

    /**
     * @var string $contact
     *
     * @ORM\Column(name="contact", type="string", length=200, nullable=true)
     */
    private $contact = "";

    /**
     * @var string $raison_sociale_2
     *
     * @ORM\Column(name="raison_sociale_2", type="string", length=200, nullable=true)
     */
    private $raison_sociale_2 = "";

    /**
     * @var string $N_TVA_CEE_facture
     *
     * @ORM\Column(name="N_TVA_CEE_facture", type="string", length=100, nullable=true)
     */
    private $N_TVA_CEE_facture;

    /**
     * @var string $adresse_1_facturation
     *
     * @ORM\Column(name="adresse_1_facturation", type="string", length=100, nullable=true)
     */
    private $adresse_1_facturation = "";

    /**
     * @var string $adresse_2_facturation
     *
     * @ORM\Column(name="adresse_2_facturation", type="string", length=100, nullable=true)
     */
    private $adresse_2_facturation = "";

    /**
     * @var string $code_postal_facturation
     *
     * @ORM\Column(name="code_postal_facturation", type="string", length=20, nullable=true)
     */
    private $code_postal_facturation = "";

    /**
     * @var string $ville_facturation
     *
     * @ORM\Column(name="ville_facturation", type="string", length=50, nullable=true)
     */
    private $ville_facturation = "";

    /**
     * @var string $pays_id_facturation
     *
     * @ORM\ManyToOne(targetEntity="ListCountries")
     * @ORM\JoinColumn(name="pays_id_facturation", referencedColumnName="code")
     */
    private $pays_facturation;

    /**
     * @var \DateTime $date_fin_mission
     *
     * @ORM\Column(name="date_fin_mission", type="date", nullable=true)
     */
    private $date_fin_mission;


    /**
     * @var integer $date_de_depot_id
     *
     * @Assert\NotBlank()
     * @ORM\Column(name="date_de_depot_id", type="integer")
     */
    private $date_de_depot_id;

    /**
     * @var string $N_TVA_CEE
     *
     * @ORM\Column(name="N_TVA_CEE", type="string", length=100, nullable=true)
     */
    private $N_TVA_CEE;

    /**
     * Niveau Obligation INTRO
     * @var integer $niveau_dobligation_id
     *
     * @ORM\Column(name="niveau_dobligation_id", type="integer", nullable=true)
     */
    private $niveau_dobligation_id;

    
    /**
     * Niveau Obligation EXPED
     * @var integer $niveau_dobligation_exped_id
     *
     * @ORM\Column(name="niveau_dobligation_exped_id", type="integer", nullable=true)
     */
    private $niveau_dobligation_exped_id;
    
    
    /**
     * @var integer $alert_count
     *
     * @ORM\Column(name="alert_count", type="integer", nullable=true)
     */
    private $alert_count = 0;


    /**
     * @var integer $blocked_count
     *
     * @ORM\Column(name="blocked_count", type="integer", nullable=true)
     */
    private $blocked_count = 0;

    /**
     * @var string $teledeclaration
     *
     * @ORM\Column(name="teledeclaration", type="boolean", nullable=true)
     */
    private $teledeclaration;

    /**
     * @var string  $files
     *
     * @ORM\Column(name="files", type="text")
     */
    protected $files = '{".":[]}';


    /**
     * @ORM\OneToMany (targetEntity="Garantie", mappedBy="client")
     */
    protected $garantie;

    /**
     * @ORM\OneToMany (targetEntity="Compte", mappedBy="client")
     * @ORM\OrderBy({"date" = "ASC"})
     */
    protected $comptes;

    /**
     * @ORM\OneToMany (targetEntity="CompteDeDepot", mappedBy="client")
     */
    protected $comptes_de_depot;

    /**
     * @ORM\OneToMany (targetEntity="ClientAlert", mappedBy="client")
     */
    protected $alertes;

    /**
     * @ORM\OneToOne(targetEntity="ClientInvoicing", mappedBy="client")
     */
    protected $invoicing;

    /**
     * @ORM\OneToMany (targetEntity="Commentaire", mappedBy="client")
     */
    protected $commentaires;

    /**
     * @ORM\OneToMany (targetEntity="Contact", mappedBy="client")
     */
    protected $contacts;

    /**
     * @ORM\OneToMany (targetEntity="Coordonnees", mappedBy="client")
     */
    protected $coordonnees;

    /**
     * @ORM\OneToMany (targetEntity="Document", mappedBy="client")
     */
    protected $documents;

    /**
     * @ORM\OneToMany (targetEntity="Tarif", mappedBy="client")
     */
    protected $tarifs;

    /**
     * @var string $N_TVA_FR
     *
     * @ORM\Column(name="N_TVA_FR", type="string", length=100, nullable=true)
     */
    private $N_TVA_FR;

    
    /**
     * @var string $reference_client
     *
     * @ORM\Column(name="reference_client", type="string", length=200, nullable=true)
     */
    private $reference_client;
    
    
    
    private $_compte_reel_sum = 0, $_compte_previsionnel_sum = 0;
    
    
    /**
     * Montant crédit initial
     * 
     * @var float $montant_credit_initial
     *
     * @ORM\Column(name="montant_credit_initial", type="float", nullable=true)
     */
    private $montant_credit_initial;
    
    
    
    
    /**
     * Returns a string representation
     *
     * @return string
     */
    public function __toString()
    {
        return (string)$this->getNom() ? : '-';
    }


    public function __construct()
    {
        $this->garantie = new \Doctrine\Common\Collections\ArrayCollection();
        $this->pays_postal = $this->pays_facturation = ListCountries::getDefault();
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
     * Set nom
     *
     * @param string $nom
     * @return Client
     */
    public function setNom($nom)
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Get nom
     *
     * @return string
     */
    public function getNom()
    {
        return $this->nom;
    }


    /**
     * Set raison_sociale
     *
     * @param string $raisonSociale
     * @return Client
     */
    public function setRaisonSociale($raisonSociale)
    {
        $this->raison_sociale = $raisonSociale;

        return $this;
    }

    /**
     * Get raison_sociale
     *
     * @return string
     */
    public function getRaisonSociale()
    {
        return $this->raison_sociale;
    }

    /**
     * Set adresse_1_postal
     *
     * @param string $adressePostale1
     * @return Client
     */
    public function setAdresse1Postal($adressePostale1)
    {
        $this->adresse_1_postal = $adressePostale1;

        return $this;
    }

    /**
     * Get adresse_1_postal
     *
     * @return string
     */
    public function getAdresse1Postal()
    {
        return $this->adresse_1_postal;
    }

    /**
     * Set adresse_2_postal
     *
     * @param string $adressePostale2
     * @return Client
     */
    public function setAdresse2Postal($adressePostale2)
    {
        $this->adresse_2_postal = $adressePostale2;

        return $this;
    }

    /**
     * Get adresse_2_postal
     *
     * @return string
     */
    public function getAdresse2Postal()
    {
        return $this->adresse_2_postal;
    }

    /**
     * Set code_postal_postal
     *
     * @param string $codePostal
     * @return Client
     */
    public function setCodePostalPostal($codePostal)
    {
        $this->code_postal_postal = $codePostal;

        return $this;
    }

    /**
     * Get code_postal_postal
     *
     * @return string
     */
    public function getCodePostalPostal()
    {
        return $this->code_postal_postal;
    }

    /**
     * Set ville_postal
     *
     * @param string $villePostal
     * @return Client
     */
    public function setVillePostal($villePostal)
    {
        $this->ville_postal = $villePostal;

        return $this;
    }

    /**
     * Get ville_postal
     *
     * @return string
     */
    public function getVillePostal()
    {
        return $this->ville_postal;
    }

    /**
     * Set pays_id_postal
     *
     * @param integer $paysIdPostal
     * @return Client
     */
    public function setPaysIdPostal($paysIdPostal)
    {
        $this->pays_id_postal = $paysIdPostal;

        return $this;
    }

    /**
     * Get pays_id_postal
     *
     * @return integer
     */
    public function getPaysIdPostal()
    {
        return $this->pays_id_postal;
    }

    /**
     * Set activite
     *
     * @param string $activite
     * @return Client
     */
    public function setActivite($activite)
    {
        $this->activite = $activite;

        return $this;
    }

    /**
     * Get activite
     *
     * @return string
     */
    public function getActivite()
    {
        return $this->activite;
    }

    /**
     * Set date_debut_mission
     *
     * @param \DateTime $dateDebutMission
     * @return Client
     */
    public function setDateDebutMission($dateDebutMission)
    {
        $this->date_debut_mission = $dateDebutMission;

        return $this;
    }

    /**
     * Get date_debut_mission
     *
     * @return \DateTime
     */
    public function getDateDebutMission()
    {
        return $this->date_debut_mission;
    }


    /**
     * Set siret
     *
     * @param string $siret
     * @return Client
     */
    public function setSiret($siret)
    {
        $this->siret = $siret;

        return $this;
    }

    /**
     * Get siret
     *
     * @return string
     */
    public function getSiret()
    {
        return $this->siret;
    }


    /**
     * Set num_dossier_fiscal
     *
     * @param string $numDossierFiscal
     * @return Client
     */
    public function setNumDossierFiscal($numDossierFiscal)
    {
        $this->num_dossier_fiscal = $numDossierFiscal;

        return $this;
    }

    /**
     * Get num_dossier_fiscal
     *
     * @return string
     */
    public function getNumDossierFiscal()
    {
        return $this->num_dossier_fiscal;
    }

    /**
     * Set taxe_additionnelle
     *
     * @param boolean $taxeAdditionnelle
     * @return Client
     */
    public function setTaxeAdditionnelle($taxeAdditionnelle)
    {
        $this->taxe_additionnelle = $taxeAdditionnelle;

        return $this;
    }

    /**
     * Get taxe_additionnelle
     *
     * @return boolean
     */
    public function getTaxeAdditionnelle()
    {
        return $this->taxe_additionnelle;
    }


    /**
     * Set adresse_1_facturation
     *
     * @param string $adresse1Facturation
     * @return Client
     */
    public function setAdresse1Facturation($adresse1Facturation)
    {
        $this->adresse_1_facturation = $adresse1Facturation;

        return $this;
    }

    /**
     * Get adresse_1_facturation
     *
     * @return string
     */
    public function getAdresse1Facturation()
    {
        return $this->adresse_1_facturation;
    }

    /**
     * Set adresse_2_facturation
     *
     * @param string $adresse2Facturation
     * @return Client
     */
    public function setAdresse2Facturation($adresse2Facturation)
    {
        $this->adresse_2_facturation = $adresse2Facturation;

        return $this;
    }

    /**
     * Get adresse_2_facturation
     *
     * @return string
     */
    public function getAdresse2Facturation()
    {
        return $this->adresse_2_facturation;
    }

    /**
     * Set code_postal_facturation
     *
     * @param string $codePostalFacturation
     * @return Client
     */
    public function setCodePostalFacturation($codePostalFacturation)
    {
        $this->code_postal_facturation = $codePostalFacturation;

        return $this;
    }

    /**
     * Get code_postal_facturation
     *
     * @return string
     */
    public function getCodePostalFacturation()
    {
        return $this->code_postal_facturation;
    }

    /**
     * Set ville_facturation
     *
     * @param string $villeFacturation
     * @return Client
     */
    public function setVilleFacturation($villeFacturation)
    {
        $this->ville_facturation = $villeFacturation;

        return $this;
    }

    /**
     * Get ville_facturation
     *
     * @return string
     */
    public function getVilleFacturation()
    {
        return $this->ville_facturation;
    }

    /**
     * Set pays_id_facturation
     *
     * @param integer $paysIdFacturation
     * @return Client
     */
    public function setPaysIdFacturation($paysIdFacturation)
    {
        $this->pays_id_facturation = $paysIdFacturation;

        return $this;
    }

    /**
     * Get pays_id_facturation
     *
     * @return integer
     */
    public function getPaysIdFacturation()
    {
        return $this->pays_id_facturation;
    }

    /**
     * Set date_fin_mission
     *
     * @param \DateTime $dateFinMission
     * @return Client
     */
    public function setDateFinMission($dateFinMission)
    {
        $this->date_fin_mission = $dateFinMission;

        return $this;
    }

    /**
     * Get date_fin_mission
     *
     * @return \DateTime
     */
    public function getDateFinMission()
    {
        return $this->date_fin_mission;
    }

    /**
     * Set date_de_depot_id
     *
     * @param integer $dateDeDepot
     * @return Client
     */
    public function setDateDeDepot($dateDeDepot)
    {
        $this->date_de_depot_id = $dateDeDepot;

        return $this;
    }

    /**
     * Get date_de_depot
     *
     * @return integer
     */
    public function getDateDeDepot()
    {
        return $this->date_de_depot_id;
    }

    /**
     * Set N_TVA_CEE
     *
     * @param string $nTVACEE
     * @return Client
     */
    public function setNTVACEE($nTVACEE)
    {
        $this->N_TVA_CEE = $nTVACEE;

        return $this;
    }

    /**
     * Get N_TVA_CEE
     *
     * @return string
     */
    public function getNTVACEE()
    {
        return $this->N_TVA_CEE;
    }

    /**
     * Set niveau_dobligation_id
     *
     * @param integer $niveauDobligationId
     * @return Client
     */
    public function setNiveauDobligationId($niveauDobligationId)
    {
        $this->niveau_dobligation_id = $niveauDobligationId;

        return $this;
    }

    /**
     * Get niveau_dobligation_id
     *
     * @return integer
     */
    public function getNiveauDobligationId()
    {
        return $this->niveau_dobligation_id;
    }

    
    /**
     * Set niveau_dobligation_exped_id
     *
     * @param integer $niveauDobligationExpedId
     * @return Client
     */
    public function setNiveauDobligationExpedId($niveauDobligationExpedId)
    {
    	$this->niveau_dobligation_exped_id = $niveauDobligationExpedId;
    
    	return $this;
    }
    
    /**
     * Get niveau_dobligation_exped_id
     *
     * @return integer
     */
    public function getNiveauDobligationExpedId()
    {
    	return $this->niveau_dobligation_exped_id;
    }
    
    
    
    
    /**
     * Set date_de_depot_id
     *
     * @param integer $dateDeDepotId
     * @return Client
     */
    public function setDateDeDepotId($dateDeDepotId)
    {
        $this->date_de_depot_id = $dateDeDepotId;

        return $this;
    }

    /**
     * Get date_de_depot_id
     *
     * @return integer
     */
    public function getDateDeDepotId()
    {
        return $this->date_de_depot_id;
    }

    /**
     * Set nature_du_client
     *
     * @param \Application\Sonata\ClientBundle\Entity\ListNatureDuClients $natureDuClient
     * @return Client
     */
    public function setNatureDuClient(\Application\Sonata\ClientBundle\Entity\ListNatureDuClients $natureDuClient = null)
    {
        $this->nature_du_client = $natureDuClient;

        return $this;
    }

    /**
     * Get nature_du_client
     *
     * @return \Application\Sonata\ClientBundle\Entity\ListNatureDuClients
     */
    public function getNatureDuClient()
    {
        return $this->nature_du_client;
    }

    /**
     * Set user
     *
     * @param \Application\Sonata\UserBundle\Entity\User $user
     * @return Client
     */
    public function setUser(\Application\Sonata\UserBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \Application\Sonata\UserBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set mode_denregistrement
     *
     * @param \Application\Sonata\ClientBundle\Entity\ListModeDenregistrements $modeDenregistrement
     * @return Client
     */
    public function setModeDenregistrement(\Application\Sonata\ClientBundle\Entity\ListModeDenregistrements $modeDenregistrement = null)
    {
        $this->mode_denregistrement = $modeDenregistrement;

        return $this;
    }

    /**
     * Get mode_denregistrement
     *
     * @return \Application\Sonata\ClientBundle\Entity\ListModeDenregistrements
     */
    public function getModeDenregistrement()
    {
        return $this->mode_denregistrement;
    }

    /**
     * Set periodicite_facturation
     *
     * @param \Application\Sonata\ClientBundle\Entity\ListPeriodiciteFacturations $periodiciteFacturation
     * @return Client
     */
    public function setPeriodiciteFacturation(\Application\Sonata\ClientBundle\Entity\ListPeriodiciteFacturations $periodiciteFacturation = null)
    {
        $this->periodicite_facturation = $periodiciteFacturation;

        return $this;
    }

    /**
     * Get periodicite_facturation
     *
     * @return \Application\Sonata\ClientBundle\Entity\ListPeriodiciteFacturations
     */
    public function getPeriodiciteFacturation()
    {
        return $this->periodicite_facturation;
    }

    /**
     * Set periodicite_CA3
     *
     * @param \Application\Sonata\ClientBundle\Entity\ListPeriodiciteFacturations $periodiciteCA3
     * @return Client
     */
    public function setPeriodiciteCA3(\Application\Sonata\ClientBundle\Entity\ListPeriodiciteFacturations $periodiciteCA3 = null)
    {
        $this->periodicite_CA3 = $periodiciteCA3;

        return $this;
    }

    /**
     * Get periodicite_CA3
     *
     * @return \Application\Sonata\ClientBundle\Entity\ListPeriodiciteFacturations
     */
    public function getPeriodiciteCA3()
    {
        return $this->periodicite_CA3;
    }
    
    
    public function getPeriodiciteCA3Info($year, $month) {
    	
    	if($period = $this->getPeriodiciteCA3()) {
    		
    		switch($period->getId()) {
    			case 1: //Mensuelle
    				//$date = new \DateTime('now');
    				//return $date->format('Y-m');
					return date('Y-m', strtotime($year . '-' . $month . '-01'));			
    				break;
    			case 2: //Trimestrielle
    				return 'T'. floor(($month - 1) / 3) + 1;
    				break;
    			
    		}
    		
    		
    	}
    	
    }
    
    
    
    

    /**
     * Set center_des_impots
     *
     * @param \Application\Sonata\ImpotsBundle\Entity\Impots $centerDesImpots
     * @return Client
     */
    public function setCenterDesImpots(\Application\Sonata\ImpotsBundle\Entity\Impots $centerDesImpots = null)
    {
        $this->center_des_impots = $centerDesImpots;

        return $this;
    }

    /**
     * Get center_des_impots
     *
     * @return \Application\Sonata\ImpotsBundle\Entity\Impots
     */
    public function getCenterDesImpots()
    {
        return $this->center_des_impots;
    }


    /**
     * Set $teledeclaration
     *
     * @param boolean $autreDestinataireDeFacturation
     * @return Client
     */
    public function setAutreDestinataireDeFacturation($autreDestinataireDeFacturation)
    {
        $this->autre_destinataire_de_facturation = $autreDestinataireDeFacturation;

        return $this;
    }

    /**
     * Get autre_destinataire_de_facturation
     *
     * @return boolean
     */
    public function getAutreDestinataireDeFacturation()
    {
        return $this->autre_destinataire_de_facturation;
    }

    /**
     * Set contact
     *
     * @param string $contact
     * @return Client
     */
    public function setContact($contact)
    {
        $this->contact = $contact;

        return $this;
    }

    /**
     * Get contact
     *
     * @return string
     */
    public function getContact()
    {
        return $this->contact;
    }


    /**
     * Set N_TVA_CEE_facture
     *
     * @param string $nTVACEEFacture
     * @return Client
     */
    public function setNTVACEEFacture($nTVACEEFacture)
    {
        $this->N_TVA_CEE_facture = $nTVACEEFacture;

        return $this;
    }

    /**
     * Get N_TVA_CEE_facture
     *
     * @return string
     */
    public function getNTVACEEFacture()
    {
        return $this->N_TVA_CEE_facture;
    }


    /**
     * Set language
     *
     * @param \Application\Sonata\ClientBundle\Entity\ListLanguages $language
     * @return Client
     */
    public function setLanguage(\Application\Sonata\ClientBundle\Entity\ListLanguages $language = null)
    {
        $this->language = $language;

        return $this;
    }

    /**
     * Get language
     *
     * @return \Application\Sonata\ClientBundle\Entity\ListLanguages
     */
    public function getLanguage()
    {
        return $this->language;
    }

    /**
     * Set raison_sociale_2
     *
     * @param string $raisonSociale2
     * @return Client
     */
    public function setRaisonSociale2($raisonSociale2)
    {
        $this->raison_sociale_2 = $raisonSociale2;

        return $this;
    }

    /**
     * Get raison_sociale_2
     *
     * @return string
     */
    public function getRaisonSociale2()
    {
        return $this->raison_sociale_2;
    }

    /**
     * Set alert_count
     *
     * @param integer $alertCount
     * @return Client
     */
    public function setAlertCount($alertCount)
    {
        $this->alert_count = $alertCount;

        return $this;
    }

    /**
     * Get alert_count
     *
     * @return integer
     */
    public function getAlertCount()
    {
    	if($this->alert_count == 0) {    		
    		/* @var $doctrine \Doctrine\Bundle\DoctrineBundle\Registry */
    		$doctrine = \AppKernel::getStaticContainer()->get('doctrine');
    		/* @var $em \Doctrine\ORM\EntityManager */
    		$em = $doctrine->getManager();
    		
    		$alerts = $em->getRepository('ApplicationSonataClientBundle:ClientAlert')->findBy(array('client'=>$this));
    		$this->alert_count = count($alerts);
    	}
        return $this->alert_count;
    }

    /**
     * Set blocked_count
     *
     * @param integer $blockedCount
     * @return Client
     */
    public function setBlockedCount($blockedCount)
    {
        $this->blocked_count = $blockedCount;

        return $this;
    }

    /**
     * Get blocked_count
     *
     * @return integer
     */
    public function getBlockedCount()
    {
        return $this->blocked_count;
    }

    /**
     * Set teledeclaration
     *
     * @param boolean $teledeclaration
     * @return Client
     */
    public function setTeledeclaration($teledeclaration)
    {
        $this->teledeclaration = $teledeclaration;

        return $this;
    }

    /**
     * Get teledeclaration
     *
     * @return boolean
     */
    public function getTeledeclaration()
    {
        return $this->teledeclaration;
    }

    /**
     * Set code_client
     *
     * @param string $codeClient
     * @return Client
     */
    public function setCodeClient($codeClient)
    {
        $this->code_client = $codeClient;

        return $this;
    }

    /**
     * Get code_client
     *
     * @return string
     */
    public function getCodeClient()
    {
        return $this->code_client;
    }

    /**
     * @ORM\PrePersist()
     */
    public function prePersist()
    {

    	if($this->getCodeClient()) {
    		return;
    	}
    	
    	
        /* @var $doctrine \Doctrine\Bundle\DoctrineBundle\Registry */
        $doctrine = \AppKernel::getStaticContainer()->get('doctrine');

        /* @var $em \Doctrine\ORM\EntityManager */
        $em = $doctrine->getManager();

       
        
        $natureDuClientId = $this->getNatureDuClient()->getId();
        
        
        if($natureDuClientId == ListNatureDuClients::sixE) {
        	list($code_client) = $em->getRepository('ApplicationSonataClientBundle:Client')
        	->createQueryBuilder('c')
        	->select('c.code_client as code_client')
        	->addOrderBy('c.code_client', 'DESC')
        	->setMaxResults(1)
        	->getQuery()
        	->execute();
        	
        	$maxCodeClient = $code_client['code_client'];
        	
        	// 9000, 9001 .... 9100
        	if($maxCodeClient > 9000) {
        		
        	} else {
        		$maxCodeClient = 9000;
        	}
        	
        } elseif($natureDuClientId == ListNatureDuClients::DEB || $natureDuClientId == ListNatureDuClients::DES) {

        	list($code_client) = $em->getRepository('ApplicationSonataClientBundle:Client')
        	->createQueryBuilder('c')
        	->select('c.code_client as code_client')
        	->where('c.code_client < 9000')
        	->addOrderBy('c.code_client', 'DESC')
        	->setMaxResults(1)
        	->getQuery()
        	->execute();
        	 
        	$maxCodeClient = $code_client['code_client'];
        }
        
        
        /**
         * UPDATE et_client SET code_client = id
         */
        $this->setCodeClient($maxCodeClient + 1);
        
    }

    /**
     * Get files
     *
     * @return \stdClass|array
     */
    public function getFiles()
    {
        return json_decode($this->files ? : '{".":[]}', true);
    }

    /**
     * Set files
     *
     * @param \stdClass|array $files
     * @return Client
     */
    public function setFiles($files)
    {
        $this->files = json_encode($files, 256);

        return $this;
    }

    /**
     * Get files directory absolute path
     *
     * @param int|Client $client
     * @return string
     */
    public static function getFilesAbsoluteDir($client)
    {
        $dir = DOCUMENT_ROOT . self::getFilesWebDir($client);

        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }

        return $dir;
    }

    /**
     * Get files directory web path
     *
     * @param int|Client $client
     * @return string
     */
    public static function getFilesWebDir($client)
    {
        return UPLOAD_DOCUMENTS_WEB_PATH . '/' . self::clientSortDirectoryPath($client);
    }

    
    public static function clientSortDirectoryPath($client, $isWindowsOSPath = false) {
    	
    	/* @var $doctrine \Doctrine\Bundle\DoctrineBundle\Registry */
    	$doctrine = \AppKernel::getStaticContainer()->get('doctrine');
    	/* @var $em \Doctrine\ORM\EntityManager */
    	$em = $doctrine->getManager();
    	
    	if (!($client instanceof Client)) {
    		$client = $em->getRepository('ApplicationSonataClientBundle:Client')->find($client);
    	}
    	
    	$nom = $client->getNom();
    	$nomKey = strtoupper(substr($nom, 0,1)); // first letter
    	
    	
    	//var_dump($nom);
    	//exit;
    	
    	$alphas = range('A', 'Z');
    	static $newAlphas = array();
    	
    	if(empty($newAlphas)) {
    		foreach($alphas as $i => $alpha) {
    			if($i % 2 == 0) {
    				$newAlphas[$alpha] = $alpha . '-' . $alphas[$i+1];
    				$newAlphas[$alphas[$i+1]] = $alpha . '-' . $alphas[$i+1];
    			}
    		}
    	}
    	$sortDir = isset($newAlphas[$nomKey]) ? $newAlphas[$nomKey]
    		: $newAlphas['A']; //Nom that starts in number will reside at A-B directory
    	
    	$slash = '/';
    	
    	if($isWindowsOSPath) {
    		$slash = '\\';
    	}
    	
    	return $sortDir . $slash . $nom;
    	
    }
    
    public function localWindowsDirectoryPath() {
    	return LOCAL_CLIENTS_PATH . '\\' . self::clientSortDirectoryPath($this, true);
    }
    
    
    /**
     * Move file
     *
     * @param string $fromFile
     * @param string $toFile
     * @return Client
     */
    public function moveFile($fromFile, $toFile)
    {
        if (is_file($fromFile)) {
            rename($fromFile, str_replace(array('//', '/\\'), '/', self::getFilesAbsoluteDir($this) . '/' . $toFile));

            self::scanFilesTree($this);
        }

        return $this;
    }

    /**
     * @static
     * @param int|Client $client
     */
    public static function scanFilesTree($client)
    {
        /* @var $doctrine \Doctrine\Bundle\DoctrineBundle\Registry */
        $doctrine = \AppKernel::getStaticContainer()->get('doctrine');
        /* @var $em \Doctrine\ORM\EntityManager */
        $em = $doctrine->getManager();

        if (!($client instanceof Client)) {
            $client = $em->getRepository('ApplicationSonataClientBundle:Client')->find($client);
        }
        $client->setFiles(self::recursiveScanDir(self::getFilesAbsoluteDir($client)));

        $em->persist($client);
        $em->flush();
    }

    protected static function recursiveScanDir($dir)
    {
        $files = array('.' => array());
        foreach (new \DirectoryIterator($dir) as $fileInfo) {
            /** @var $fileInfo \DirectoryIterator */
            if (!$fileInfo->isDot()) {
                if ($fileInfo->isFile()) {
                    $files['.'][] = $fileInfo->getFilename();//mb_convert_encoding($fileInfo->getFilename(), 'UTF-8');
                } elseif ($fileInfo->isDir()) {
                    $files[$fileInfo->getFilename()] = self::recursiveScanDir($fileInfo->getPathname());
                }
            }
        }

        krsort($files);

        return $files;
    }

    /**
     * Set pays_postal
     *
     * @param \Application\Sonata\ClientBundle\Entity\ListCountries $paysPostal
     * @return Client
     */
    public function setPaysPostal(\Application\Sonata\ClientBundle\Entity\ListCountries $paysPostal = null)
    {
        $this->pays_postal = $paysPostal;

        return $this;
    }

    /**
     * Get pays_postal
     *
     * @return \Application\Sonata\ClientBundle\Entity\ListCountries
     */
    public function getPaysPostal()
    {
        return $this->pays_postal;
    }

    /**
     * Add garantie
     *
     * @param \Application\Sonata\ClientBundle\Entity\Garantie $garantie
     * @return Client
     */
    public function addGarantie(\Application\Sonata\ClientBundle\Entity\Garantie $garantie)
    {
        $this->garantie[] = $garantie;

        return $this;
    }

    /**
     * Remove garantie
     *
     * @param \Application\Sonata\ClientBundle\Entity\Garantie $garantie
     */
    public function removeGarantie(\Application\Sonata\ClientBundle\Entity\Garantie $garantie)
    {
        $this->garantie->removeElement($garantie);
    }

    /**
     * Get garantie
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getGarantie()
    {
        return $this->garantie;
    }

    /**
     * Set pays_facturation
     *
     * @param \Application\Sonata\ClientBundle\Entity\ListCountries $paysFacturation
     * @return Client
     */
    public function setPaysFacturation(\Application\Sonata\ClientBundle\Entity\ListCountries $paysFacturation = null)
    {
        $this->pays_facturation = $paysFacturation;

        return $this;
    }

    /**
     * Get pays_facturation
     *
     * @return \Application\Sonata\ClientBundle\Entity\ListCountries
     */
    public function getPaysFacturation()
    {
        return $this->pays_facturation;
    }

    /**
     * @return float
     */
    public function getComptesSoldeMontant()
    {
        $solde = 0;
        foreach($this->getComptes() as $compte){
            /** @var $compte Compte */
            $solde += $compte->getMontant();
        }
        return $solde;
    }
    
    
    public function getCompteReelSum() {
    	$this->_compte_reel_sum = 0;
    	foreach($this->getComptes() as $compte){
    		if(!$compte->getStatut()) {
    			continue;
    		}
    		if($compte->getStatut()->getId() == 1) {
    			$this->_compte_reel_sum += $compte->getMontant();
    		}
    	}
    	
    	$compte_previsionnel_sum = $this->getComptePrevisionnelSum();
    	
    	if($compte_previsionnel_sum != 0) {
    		$this->_compte_reel_sum += $compte_previsionnel_sum;
    	}
    	return round($this->_compte_reel_sum);
    }
    
    public function getCompteReel() {
    	$comptes = array();
    	foreach($this->getComptes() as $compte){
    		if(!$compte->getStatut()) {
    			continue;
    		}
    		if($compte->getStatut()->getId() == 1) {
    			$comptes[] = $compte;
    		}
    	}
    	return $comptes;
    }
    
    
    public function getComptePrevisionnelSum() {
    	$this->_compte_previsionnel_sum = 0;
    	foreach($this->getComptes() as $compte){
    		if(!$compte->getStatut()) {
    			continue;
    		}
    		if($compte->getStatut()->getId() == 2) {
    			$this->_compte_previsionnel_sum += $compte->getMontant();
    		}
    	}
    	return $this->_compte_previsionnel_sum;
    }
    


    /**
     * Add comptes
     *
     * @param \Application\Sonata\ClientBundle\Entity\Compte $comptes
     * @return Client
     */
    public function addCompte(\Application\Sonata\ClientBundle\Entity\Compte $comptes)
    {
        $this->comptes[] = $comptes;

        return $this;
    }

    /**
     * Remove comptes
     *
     * @param \Application\Sonata\ClientBundle\Entity\Compte $comptes
     */
    public function removeCompte(\Application\Sonata\ClientBundle\Entity\Compte $comptes)
    {
        $this->comptes->removeElement($comptes);
    }

    /**
     * Get comptes
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getComptes()
    {
        return $this->comptes;
    }

    /**
     * Add comptes_de_depot
     *
     * @param \Application\Sonata\ClientBundle\Entity\CompteDeDepot $comptesDeDepot
     * @return Client
     */
    public function addComptesDeDepot(\Application\Sonata\ClientBundle\Entity\CompteDeDepot $comptesDeDepot)
    {
        $this->comptes_de_depot[] = $comptesDeDepot;

        return $this;
    }

    /**
     * Remove comptes_de_depot
     *
     * @param \Application\Sonata\ClientBundle\Entity\CompteDeDepot $comptesDeDepot
     */
    public function removeComptesDeDepot(\Application\Sonata\ClientBundle\Entity\CompteDeDepot $comptesDeDepot)
    {
        $this->comptes_de_depot->removeElement($comptesDeDepot);
    }

    /**
     * Get comptes_de_depot
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getComptesDeDepot()
    {
        return $this->comptes_de_depot;
    }

    /**
     * Add alertes
     *
     * @param \Application\Sonata\ClientBundle\Entity\ClientAlert $alertes
     * @return Client
     */
    public function addAlerte(\Application\Sonata\ClientBundle\Entity\ClientAlert $alertes)
    {
        $this->alertes[] = $alertes;

        return $this;
    }

    /**
     * Remove alertes
     *
     * @param \Application\Sonata\ClientBundle\Entity\ClientAlert $alertes
     */
    public function removeAlerte(\Application\Sonata\ClientBundle\Entity\ClientAlert $alertes)
    {
        $this->alertes->removeElement($alertes);
    }

    /**
     * Get alertes
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAlertes()
    {
        return $this->alertes;
    }

    /**
     * Set invoicing
     *
     * @param \Application\Sonata\ClientBundle\Entity\ClientInvoicing $invoicing
     * @return Client
     */
    public function setInvoicing(\Application\Sonata\ClientBundle\Entity\ClientInvoicing $invoicing = null)
    {
        $this->invoicing = $invoicing;

        return $this;
    }

    /**
     * Get invoicing
     *
     * @return \Application\Sonata\ClientBundle\Entity\ClientInvoicing
     */
    public function getInvoicing()
    {
        return $this->invoicing;
    }

    /**
     * Add commentaires
     *
     * @param \Application\Sonata\ClientBundle\Entity\Commentaire $commentaires
     * @return Client
     */
    public function addCommentaire(\Application\Sonata\ClientBundle\Entity\Commentaire $commentaires)
    {
        $this->commentaires[] = $commentaires;

        return $this;
    }

    /**
     * Remove commentaires
     *
     * @param \Application\Sonata\ClientBundle\Entity\Commentaire $commentaires
     */
    public function removeCommentaire(\Application\Sonata\ClientBundle\Entity\Commentaire $commentaires)
    {
        $this->commentaires->removeElement($commentaires);
    }

    /**
     * Get commentaires
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCommentaires()
    {
        return $this->commentaires;
    }

    /**
     * Add contacts
     *
     * @param \Application\Sonata\ClientBundle\Entity\Contact $contacts
     * @return Client
     */
    public function addContact(\Application\Sonata\ClientBundle\Entity\Contact $contacts)
    {
        $this->contacts[] = $contacts;

        return $this;
    }

    /**
     * Remove contacts
     *
     * @param \Application\Sonata\ClientBundle\Entity\Contact $contacts
     */
    public function removeContact(\Application\Sonata\ClientBundle\Entity\Contact $contacts)
    {
        $this->contacts->removeElement($contacts);
    }

    /**
     * Get contacts
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getContacts()
    {
        return $this->contacts;
    }

    /**
     * Add coordonnees
     *
     * @param \Application\Sonata\ClientBundle\Entity\Coordonnees $coordonnees
     * @return Client
     */
    public function addCoordonnee(\Application\Sonata\ClientBundle\Entity\Coordonnees $coordonnees)
    {
        $this->coordonnees[] = $coordonnees;

        return $this;
    }

    /**
     * Remove coordonnees
     *
     * @param \Application\Sonata\ClientBundle\Entity\Coordonnees $coordonnees
     */
    public function removeCoordonnee(\Application\Sonata\ClientBundle\Entity\Coordonnees $coordonnees)
    {
        $this->coordonnees->removeElement($coordonnees);
    }

    /**
     * Get coordonnees
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCoordonnees()
    {
        return $this->coordonnees;
    }

    /**
     * Add documents
     *
     * @param \Application\Sonata\ClientBundle\Entity\Document $documents
     * @return Client
     */
    public function addDocument(\Application\Sonata\ClientBundle\Entity\Document $documents)
    {
        $this->documents[] = $documents;

        return $this;
    }

    /**
     * Remove documents
     *
     * @param \Application\Sonata\ClientBundle\Entity\Document $documents
     */
    public function removeDocument(\Application\Sonata\ClientBundle\Entity\Document $documents)
    {
        $this->documents->removeElement($documents);
    }

    /**
     * Get documents
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDocuments()
    {
        return $this->documents;
    }

    /**
     * Add tarifs
     *
     * @param \Application\Sonata\ClientBundle\Entity\Tarif $tarifs
     * @return Client
     */
    public function addTarif(\Application\Sonata\ClientBundle\Entity\Tarif $tarifs)
    {
        $this->tarifs[] = $tarifs;

        return $this;
    }

    /**
     * Remove tarifs
     *
     * @param \Application\Sonata\ClientBundle\Entity\Tarif $tarifs
     */
    public function removeTarif(\Application\Sonata\ClientBundle\Entity\Tarif $tarifs)
    {
        $this->tarifs->removeElement($tarifs);
    }

    /**
     * Get tarifs
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTarifs()
    {
        return $this->tarifs;
    }

    /**
     * Set N_TVA_FR
     *
     * @param string $nTVAFR
     * @return Client
     */
    public function setNTVAFR($nTVAFR)
    {
        $this->N_TVA_FR = $nTVAFR;

        return $this;
    }

    /**
     * Get N_TVA_FR
     *
     * @return string
     */
    public function getNTVAFR()
    {
        return $this->N_TVA_FR;
    }
    
    
    
    /**
     * Set reference client
     *
     * @param string $value
     * @return Client
     */
    public function setReferenceClient($value)
    {
    	$this->reference_client = $value;
    
    	return $this;
    }
    
    /**
     * Get reference client
     *
     * @return string
     */
    public function getReferenceClient()
    {
    	return $this->reference_client;
    }
    

    /**
     * @return string
     */
    public function getLocaleCodeVillePostal()
    {
        $pays = $this->getPaysPostal();
        if ($pays && in_array($pays->getCode(), array('GB', 'US'))){
            return $this->getVillePostal().' '.$this->getCodePostalPostal();
        }

        return $this->getCodePostalPostal().' '.$this->getVillePostal();
    }
    
    /**
     * Set montant_credit_initial
     *
     * @param float $montantHTEnDevise
     * @return A02TVA
     */
    public function setMontantCreditInitial($amount)
    {
    	$this->montant_credit_initial = $amount;
    
    	return $this;
    }
    
    /**
     * Get montant_credit_initial
     *
     * @return float
     */
    public function getMontantCreditInitial()
    {
    	return $this->montant_credit_initial;
    }
    
    
    public function getDeclaration($year = null, $month = null) {
    	static $instances = array();
    	
    	if(is_null($year) || is_null($month)) {
	    	$now = new \DateTime();
	    	$dateQuery = $now->format('d') > 25 ? $now : new \DateTime('now -1 month');
	    	$year = $dateQuery->format('Y');
	    	$month = $dateQuery->format('m');
    	}
    	$key = sha1($this->getId(). $year . $month);
    	if(!isset($instances[$key])) {
	    	$clientDeclaration = new ClientDeclaration($this);
	    	$clientDeclaration->setShowAllOperations(false)->setYear($year)
	    		->setMonth($month);
	    	
	    	$instances[$key] = $clientDeclaration;
    	}
    	return $instances[$key];
    }
    
    
    public function getDeclarationComputation($year = null, $month = null) {
    	return new ClientDeclarationComputation($this->getDeclaration($year, $month));
    }
    
    
    
}