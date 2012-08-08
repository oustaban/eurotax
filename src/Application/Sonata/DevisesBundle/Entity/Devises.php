<?php

namespace Application\Sonata\DevisesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Application\Sonata\DevisesBundle\Entity\Devises
 *
 * @ORM\Table("et_devises")
 * @ORM\Entity
 */
class Devises
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
     * @var \DateTime $date
     *
     * @ORM\Column(name="date", type="date")
     */
    private $date;

    /**
     * @var string $money_dollar
     *
     * @ORM\Column(name="money_dollar", type="float" )
     */
    private $money_dollar;

    /**
     * @var string $money_yen
     *
     * @ORM\Column(name="money_yen", type="float")
     */
    private $money_yen;

    /**
     * @var string $money_british
     *
     * @ORM\Column(name="money_british", type="float")
     */
    private $money_british;


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
     * @return Devises
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
     * Set money_dollar
     *
     * @param string $moneyDollar
     * @return Devises
     */
    public function setMoneyDollar($moneyDollar)
    {
        $this->money_dollar = $moneyDollar;
    
        return $this;
    }

    /**
     * Get money_dollar
     *
     * @return string 
     */
    public function getMoneyDollar()
    {
        return $this->money_dollar;
    }

    /**
     * Set money_yen
     *
     * @param string $moneyYen
     * @return Devises
     */
    public function setMoneyYen($moneyYen)
    {
        $this->money_yen = $moneyYen;
    
        return $this;
    }

    /**
     * Get money_yen
     *
     * @return string 
     */
    public function getMoneyYen()
    {
        return $this->money_yen;
    }

    /**
     * Set money_british
     *
     * @param string $moneyBritish
     * @return Devises
     */
    public function setMoneyBritish($moneyBritish)
    {
        $this->money_british = $moneyBritish;
    
        return $this;
    }

    /**
     * Get money_british
     *
     * @return string 
     */
    public function getMoneyBritish()
    {
        return $this->money_british;
    }
}