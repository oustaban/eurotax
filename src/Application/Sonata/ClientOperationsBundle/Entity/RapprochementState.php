<?php

namespace Application\Sonata\ClientOperationsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Application\Sonata\ClientOperationsBundle\Entity\RapprochementState
 *
 * @ORM\Table("et_rapprochement_state")
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 */
class RapprochementState {

	

	
	
	/**
	 * @var integer $id
	 *
	 * @ORM\Column(name="id", type="integer")
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	private $id;
	
	
	/**
	 * @var \DateTime
	 * 
	 * @ORM\Column(name="created_at", type="datetime")
	 */
	private $created_at;
	
	/**
	 * @var \DateTime
	 * 
	 * @ORM\Column(name="updated_at", type="datetime")
	 */
	private $updated_at;
	
	
	/**
	 * @var integer $client_id
	 *
	 * @ORM\Column(name="client_id", type="integer")
	 */
	private $client_id;
	
	
	/**
	 * @var integer $month
	 *
	 * @ORM\Column(name="month", type="integer")
	 */
	private $month;
	
	/**
	 * @var integer $year
	 *
	 * @ORM\Column(name="year", type="integer")
	 */
	private $year;
	
	
	/**
	 * @var integer $demande_de_remboursement
	 *
	 * @ORM\Column(name="demande_de_remboursement", type="float", nullable=true)
	 */
	private $demande_de_remboursement;
	
	
	/**
	 * ADDITIONAL TAXES
	 * 
	 * @var integer $credit_tva_a_reporter
	 *
	 * @ORM\Column(name="credit_tva_a_reporter", type="float", nullable=true)
	 */
	private $credit_tva_a_reporter;
	
	
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
	 *
	 * @param float $demande_de_remboursement
	 */
	public function setDemandeDeRemboursement($demande_de_remboursement) {
		$this->demande_de_remboursement = $demande_de_remboursement;
	}
	
	/**
	 *
	 * @return float
	 */
	public function getDemandeDeRemboursement() {
		return $this->demande_de_remboursement;
	}
	
	
	/**
	 *
	 * @param float $credit_tva_a_reporter
	 */
	public function setCreditTvaAReporter($credit_tva_a_reporter) {
		$this->credit_tva_a_reporter = $credit_tva_a_reporter;
	}
	
	/**
	 *
	 * @return float
	 */
	public function getCreditTvaAReporter() {
		return $this->credit_tva_a_reporter;
	}
	
	
	/**
	 * Sets the creation date
	 *
	 * @param \DateTime|null $createdAt
	 */
	public function setCreatedAt(\DateTime $createdAt = null)
	{
		$this->created_at = $createdAt;
	}
	
	/**
	 * Returns the creation date
	 *
	 * @return \DateTime|null
	 */
	public function getCreatedAt()
	{
		return $this->created_at;
	}
	
	/**
	 * Sets the last update date
	 *
	 * @param \DateTime|null $updatedAt
	 */
	public function setUpdatedAt(\DateTime $updatedAt = null)
	{
		$this->updated_at = $updatedAt;
	}
	
	/**
	 * Returns the last update date
	 *
	 * @return \DateTime|null
	 */
	public function getUpdatedAt()
	{
		return $this->updated_at;
	}
	
	
	
	/**
	 * Set client_id
	 *
	 * @param integer $clientId
	 * @return Imports
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
     * Set month
     *
     * @param integer $month
     * @return Locking
     */
    public function setMonth($month)
    {
        $this->month = $month;

        return $this;
    }

    /**
     * Get month
     *
     * @return integer
     */
    public function getMonth()
    {
        return $this->month;
    }

    /**
     * Set year
     *
     * @param integer $year
     * @return Locking
     */
    public function setYear($year)
    {
        $this->year = $year;
    
        return $this;
    }

    /**
     * Get year
     *
     * @return integer 
     */
    public function getYear()
    {
        return $this->year;
    }

    /**
     * Hook on pre-persist operations
     * 
     * @ORM\PrePersist
     */
    public function prePersist()
    {
    	$this->created_at = new \DateTime;
    	$this->updated_at = new \DateTime;
    }
    
    /**
     * Hook on pre-update operations
     * 
     * @ORM\PreUpdate
     */
    public function preUpdate()
    {
    	$this->updated_at = new \DateTime;
    }

}