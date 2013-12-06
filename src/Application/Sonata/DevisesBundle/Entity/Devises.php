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
    const Device = 'EUR';

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
     * @ORM\Column(name="date", type="date", unique=true)
     */
    private $date;

    /**
     * @var string $moneyUSD
     *
     * @ORM\Column(name="moneyUSD", type="float" , nullable=true)
     */
    private $moneyUSD;

    /**
     * @var string $moneyAUD
     *
     * @ORM\Column(name="moneyAUD", type="float", nullable=true)
     */
    private $moneyAUD;

    /**
     * @var string $moneyCAD
     *
     * @ORM\Column(name="moneyCAD", type="float", nullable=true)
     */
    private $moneyCAD;

    /**
     * @var string $moneyCHF
     *
     * @ORM\Column(name="moneyCHF", type="float", nullable=true)
     */
    private $moneyCHF;

    /**
     * @var string $moneyDKK
     *
     * @ORM\Column(name="moneyDKK", type="float", nullable=true)
     */
    private $moneyDKK;

    /**
     * @var string $moneyGBP
     *
     * @ORM\Column(name="moneyGBP", type="float", nullable=true)
     */
    private $moneyGBP;

    /**
     * @var string $moneyJPY
     *
     * @ORM\Column(name="moneyJPY", type="float", nullable=true)
     */
    private $moneyJPY;

    /**
     * @var string $moneyNOK
     *
     * @ORM\Column(name="moneyNOK", type="float", nullable=true)
     */
    private $moneyNOK;

    /**
     * @var string $moneySEK
     *
     * @ORM\Column(name="moneySEK", type="float", nullable=true)
     */
    private $moneySEK;

    /**
     * CZK - Czech Koruna
     * 
     * @var string $moneyCZK
     *
     * @ORM\Column(name="moneyCZK", type="float", nullable=true)
     */
    private $moneyCZK;
    
    /**
     * HRK - Croatian Kuna
     * 
     * @var string $moneyHRK
     *
     * @ORM\Column(name="moneyHRK", type="float", nullable=true)
     */
    private $moneyHRK;
    
    /**
     * HUF - Hungarian Forint
     * 
     * @var string $moneyHUF
     *
     * @ORM\Column(name="moneyHUF", type="float", nullable=true)
     */
    private $moneyHUF;
    
    /**
     * PLN - Polish Zloty
     * 
     * @var string $moneyPLN
     *
     * @ORM\Column(name="moneyPLN", type="float", nullable=true)
     */
    private $moneyPLN;
    
    /**
     * RON - Romanian Leu
     * 
     * @var string $moneyRON
     *
     * @ORM\Column(name="moneyRON", type="float", nullable=true)
     */
    private $moneyRON;
    
    /**
     * RUB - Russian Rouble
     * 
     * @var string $moneyRUB
     *
     * @ORM\Column(name="moneyRUB", type="float", nullable=true)
     */
    private $moneyRUB;
    
    /**
     * TRY - Turkish Lira
     * 
     * @var string $moneyTRY
     *
     * @ORM\Column(name="moneyTRY", type="float", nullable=true)
     */
    private $moneyTRY;
    
    /**
     * ZAR - South African Rand
     * 
     * @var string $moneyZAR
     *
     * @ORM\Column(name="moneyZAR", type="float", nullable=true)
     */
    private $moneyZAR;
    

    /**
     *
     */
    public function __construct()
    {
        $this->setDate(new \DateTime(date('Y-m-01', strtotime('now' . (date('d') > 24 ? ' +8 days' : '')))));
    }

    /**
     * Returns a string representation
     *
     * @return string
     */
    public function __toString()
    {
        return (string)$this->getId() ? : '-';
    }

    /**
     * @static
     * @param $id
     * @param \DateTime $date
     * @return int
     */
    public static function getDevisesValue($id, \DateTime $date)
    {
        /* @var $doctrine \Doctrine\Bundle\DoctrineBundle\Registry */
        $doctrine = \AppKernel::getStaticContainer()->get('doctrine');

        /* @var $em \Doctrine\ORM\EntityManager */
        $em = $doctrine->getManager();

        /** @var $list_devises \Application\Sonata\ClientBundle\Entity\ListDevises */
        $list_devises = $em->getRepository('ApplicationSonataClientBundle:ListDevises')->findOneById($id);

        $devise = $em->getRepository('ApplicationSonataDevisesBundle:Devises')->findOneByDate(new \DateTime($date->format('Y-m-01')));

        /** http://redmine.testenm.com/issues/1364 */
        if ($list_devises->getAlias() == self::Device) {
            return 1;
        } elseif ($devise) {
            $method = 'getMoney' . $list_devises->getAlias();
            if (method_exists($devise, $method)) {

                return $devise->$method();
            }
        }
    }

    /**
     * @return null
     */
    public function getDateChange()
    {
        return null;
    }

    /**
     * @param $value
     */
    public function setDateChange($value)
    {
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
     * Set moneyUSD
     *
     * @param float $moneyUSD
     * @return Devises
     */
    public function setMoneyUSD($moneyUSD)
    {
        $this->moneyUSD = $moneyUSD;

        return $this;
    }

    /**
     * Get moneyUSD
     *
     * @return float
     */
    public function getMoneyUSD()
    {
        return $this->moneyUSD;
    }

    /**
     * Set moneyAUD
     *
     * @param float $moneyAUD
     * @return Devises
     */
    public function setMoneyAUD($moneyAUD)
    {
        $this->moneyAUD = $moneyAUD;

        return $this;
    }

    /**
     * Get moneyAUD
     *
     * @return float
     */
    public function getMoneyAUD()
    {
        return $this->moneyAUD;
    }

    /**
     * Set moneyCAD
     *
     * @param float $moneyCAD
     * @return Devises
     */
    public function setMoneyCAD($moneyCAD)
    {
        $this->moneyCAD = $moneyCAD;

        return $this;
    }

    /**
     * Get moneyCAD
     *
     * @return float
     */
    public function getMoneyCAD()
    {
        return $this->moneyCAD;
    }

    /**
     * Set moneyCHF
     *
     * @param float $moneyCHF
     * @return Devises
     */
    public function setMoneyCHF($moneyCHF)
    {
        $this->moneyCHF = $moneyCHF;

        return $this;
    }

    /**
     * Get moneyCHF
     *
     * @return float
     */
    public function getMoneyCHF()
    {
        return $this->moneyCHF;
    }

    /**
     * Set moneyDKK
     *
     * @param float $moneyDKK
     * @return Devises
     */
    public function setMoneyDKK($moneyDKK)
    {
        $this->moneyDKK = $moneyDKK;

        return $this;
    }

    /**
     * Get moneyDKK
     *
     * @return float
     */
    public function getMoneyDKK()
    {
        return $this->moneyDKK;
    }

    /**
     * Set moneyGBP
     *
     * @param float $moneyGBP
     * @return Devises
     */
    public function setMoneyGBP($moneyGBP)
    {
        $this->moneyGBP = $moneyGBP;

        return $this;
    }

    /**
     * Get moneyGBP
     *
     * @return float
     */
    public function getMoneyGBP()
    {
        return $this->moneyGBP;
    }

    /**
     * Set moneyJPY
     *
     * @param float $moneyJPY
     * @return Devises
     */
    public function setMoneyJPY($moneyJPY)
    {
        $this->moneyJPY = $moneyJPY;

        return $this;
    }

    /**
     * Get moneyJPY
     *
     * @return float
     */
    public function getMoneyJPY()
    {
        return $this->moneyJPY;
    }

    /**
     * Set moneyNOK
     *
     * @param float $moneyNOK
     * @return Devises
     */
    public function setMoneyNOK($moneyNOK)
    {
        $this->moneyNOK = $moneyNOK;

        return $this;
    }

    /**
     * Get moneyNOK
     *
     * @return float
     */
    public function getMoneyNOK()
    {
        return $this->moneyNOK;
    }

    /**
     * Set moneySEK
     *
     * @param float $moneySEK
     * @return Devises
     */
    public function setMoneySEK($moneySEK)
    {
        $this->moneySEK = $moneySEK;

        return $this;
    }

    /**
     * Get moneySEK
     *
     * @return float
     */
    public function getMoneySEK()
    {
        return $this->moneySEK;
    }
    
     /**
     * Set moneyCZK
     *
     * @param float $moneyCZK
     * @return Devises
     */
    public function setMoneyCZK($moneyCZK)
    {
        $this->moneyCZK = $moneyCZK;

        return $this;
    }

    /**
     * Get moneyCZK
     *
     * @return float
     */
    public function getMoneyCZK()
    {
        return $this->moneyCZK;
    }
    
    /**
     * Set moneyHRK
     *
     * @param float $moneyHRK
     * @return Devises
     */
    public function setMoneyHRK($moneyHRK)
    {
    	$this->moneyHRK = $moneyHRK;
    
    	return $this;
    }
    
    /**
     * Get moneyHRK
     *
     * @return float
     */
    public function getMoneyHRK()
    {
    	return $this->moneyHRK;
    }
    
    /**
     * Set moneyHUF
     *
     * @param float $moneyHUF
     * @return Devises
     */
    public function setMoneyHUF($moneyHUF)
    {
    	$this->moneyHUF = $moneyHUF;
    
    	return $this;
    }
    
    /**
     * Get moneyHUF
     *
     * @return float
     */
    public function getMoneyHUF()
    {
    	return $this->moneyHUF;
    }
    
    /**
     * Set moneyPLN
     *
     * @param float $moneyPLN
     * @return Devises
     */
    public function setMoneyPLN($moneyPLN)
    {
    	$this->moneyPLN = $moneyPLN;
    
    	return $this;
    }
    
    /**
     * Get moneyPLN
     *
     * @return float
     */
    public function getMoneyPLN()
    {
    	return $this->moneyPLN;
    }
     
    /**
     * Set moneyRON
     *
     * @param float $moneyRON
     * @return Devises
     */
    public function setMoneyRON($moneyRON)
    {
    	$this->moneyRON = $moneyRON;
    
    	return $this;
    }
    
    /**
     * Get moneyRON
     *
     * @return float
     */
    public function getMoneyRON()
    {
    	return $this->moneyRON;
    }
     
    /**
     * Set moneyRUB
     *
     * @param float $moneyRUB
     * @return Devises
     */
    public function setMoneyRUB($moneyRUB)
    {
    	$this->moneyRUB = $moneyRUB;
    
    	return $this;
    }
    
    /**
     * Get moneyRUB
     *
     * @return float
     */
    public function getMoneyRUB()
    {
    	return $this->moneyRUB;
    }
    
    /**
     * Set moneyTRY
     *
     * @param float $moneyTRY
     * @return Devises
     */
    public function setMoneyTRY($moneyTRY)
    {
    	$this->moneyTRY = $moneyTRY;
    
    	return $this;
    }
    
    /**
     * Get moneyTRY
     *
     * @return float
     */
    public function getMoneyTRY()
    {
    	return $this->moneyTRY;
    }
    
    /**
     * Set moneyZAR
     *
     * @param float $moneyZAR
     * @return Devises
     */
    public function setMoneyZAR($moneyZAR)
    {
    	$this->moneyZAR = $moneyZAR;
    
    	return $this;
    }
    
    /**
     * Get moneyZAR
     *
     * @return float
     */
    public function getMoneyZAR()
    {
    	return $this->moneyZAR;
    }
}