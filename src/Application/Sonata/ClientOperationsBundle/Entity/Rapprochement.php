<?php

namespace Application\Sonata\ClientOperationsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Application\Sonata\ClientOperationsBundle\Entity\Rapprochement
 *
 * @ORM\Table("et_rapprochement")
 * @ORM\Entity
 */
class Rapprochement {

	
	public static $intro_info_id_options = array(
		1 => 'Pas de DEB Intro : seuil de 460 K€ non franchi',
		2 => 'Pas de DEB Intro : Data Intrastat insuffisant mais A/L pratiquée sur Ca3',
		3 => 'Pas de DEB Intro : Une DEB complémentaire est à déposer',
		4 => 'Arrondi',
		5 => 'Autre'
	);
	

	public static $exped_info_id_options = array(
		1 => 'Pas de DEB Exped : Data Intrastat insuffisant mais LIC déclarée sur Ca3',
		2 => 'Pas de DEB Exped : Une DEB complémentaire est à déposer',
		3 => 'Arrondi',
		4 => 'Autre'
	);
	
	
	/**
	 * @var integer $id
	 *
	 * @ORM\Column(name="id", type="integer")
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	private $id;
	
	/**
	 * @var \DateTime $date
	 *
	 * @ORM\Column(name="date", type="datetime")
	 */
	private $date;
	
	/**
	 * @var integer $client_id
	 *
	 * @ORM\Column(name="client_id", type="integer")
	 */
	private $client_id;
	
	
	/**
	 * @var \DateTime $mois
	 *
	 * @ORM\Column(name="mois", type="date", nullable=true)
	 */
	private $mois;	
	
	
	/**
	 * @var integer $intro_info_id
	 *
	 * @ORM\Column(name="intro_info_id", type="integer")
	 */
	private $intro_info_id;
	
	
	/**
	 * @var integer $intro_info_number
	 *
	 * @ORM\Column(name="intro_info_number", type="float")
	 */
	private $intro_info_number;
	
	
	/**
	 * @var integer $intro_info_number2
	 *
	 * @ORM\Column(name="intro_info_number2", type="float", nullable=true)
	 */
	private $intro_info_number2;
	
	/**
	 * @var integer $intro_info_text
	 *
	 * 
	 * @ORM\Column(name="intro_info_text", type="string", length=255, nullable=true)
	 */
	private $intro_info_text;
	
	
	
	/**
	 * @var integer $exped_info_id
	 * 
	 * @ORM\Column(name="exped_info_id", type="integer")
	 */
	private $exped_info_id;
	
	
	/**
	 * @var integer $exped_info_number
	 *
	 * @ORM\Column(name="exped_info_number", type="float")
	 */
	private $exped_info_number;
	
	/**
	 * @var integer $exped_info_number2
	 *
	 * @ORM\Column(name="exped_info_number2", type="float", nullable=true)
	 */
	private $exped_info_number2;
	
	/**
	 * @var integer $exped_info_text
	 *
	 * 
	 * @ORM\Column(name="exped_info_text", type="string", length=255, nullable=true)
	 */
	private $exped_info_text;
	
	
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
	 * Set date
	 *
	 * @param \DateTime $date
	 * @return Imports
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
	 * Set mois
	 *
	 * @param \DateTime $mois
	 * @return AbstractSellEntity
	 */
	public function setMois($mois)
	{
		$this->mois = $mois;
	
		return $this;
	}
	
	/**
	 * Get mois
	 *
	 * @return \DateTime
	 */
	public function getMois()
	{
		return $this->mois;
	}
	
	
	/**
	 * 
	 * @param int $introInfoId
	 */
	public function setIntroInfoId($introInfoId) {
		$this->intro_info_id = $introInfoId;	
	}
	
	/**
	 * 
	 * @return int
	 */
	public function getIntroInfoId() {
		return $this->intro_info_id;
	}
	
	/**
	 * 
	 * @param float $introInfoNumber
	 */
	public function setIntroInfoNumber($introInfoNumber) {
		$this->intro_info_number = $introInfoNumber;
	}
	
	/**
	 * 
	 * @return float
	 */
	public function getIntroInfoNumber() {
		return $this->intro_info_number;
	}
	
	/**
	 *
	 * @param float $introInfoNumber2
	 */
	public function setIntroInfoNumber2($introInfoNumber2) {
		$this->intro_info_number2 = $introInfoNumber2;
	}
	
	/**
	 *
	 * @return float
	 */
	public function getIntroInfoNumber2() {
		return $this->intro_info_number2;
	}
		
	
	/**
	 * 
	 * @param string $introInfoText
	 */
	public function setIntroInfoText($introInfoText) {
		$this->intro_info_text = $introInfoText;
	}
	
	/**
	 * 
	 * @return string
	 */
	public function getIntroInfoText() {
		return $this->intro_info_text;
	}
	
	/**
	 *
	 * @param int $debInfoId
	 */
	public function setExpedInfoId($debInfoId) {
		$this->exped_info_id = $debInfoId;
	}
	
	/**
	 *
	 * @return int
	 */
	public function getExpedInfoId() {
		return $this->exped_info_id;
	}
	
	/**
	 *
	 * @param float $debInfoNumber
	 */
	public function setExpedInfoNumber($debInfoNumber) {
		$this->exped_info_number = $debInfoNumber;
	}
	
	/**
	 *
	 * @return float
	 */
	public function getExpedInfoNumber() {
		return $this->exped_info_number;
	}
	
	
	/**
	 *
	 * @param float $debInfoNumber2
	 */
	public function setExpedInfoNumber2($debInfoNumber2) {
		$this->exped_info_number2 = $debInfoNumber2;
	}
	
	/**
	 *
	 * @return float
	 */
	public function getExpedInfoNumber2() {
		return $this->exped_info_number2;
	}	
	
	/**
	 *
	 * @param string $debInfoText
	 */
	public function setExpedInfoText($debInfoText) {
		$this->exped_info_text = $debInfoText;
	}
	
	/**
	 *
	 * @return string
	 */
	public function getExpedInfoText() {
		return $this->exped_info_text;
	}
	
	
	
}