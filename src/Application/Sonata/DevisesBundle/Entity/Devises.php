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
     * @ORM\Column(name="date", type="date")
     */
    private $date;

    /**
     * @var string $moneyUSD
     *
     * @ORM\Column(name="moneyUSD", type="float" , nullable=true)
     */
    private $moneyUSD;

    /**
     * @var string $moneyGBP
     *
     * @ORM\Column(name="moneyGBP", type="float", nullable=true)
     */
    private $moneyGBP;

    /**
     * @var string $moneyINR
     *
     * @ORM\Column(name="moneyINR", type="float", nullable=true)
     */
    private $moneyINR;


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
     * @var string $moneyAED
     *
     * @ORM\Column(name="moneyAED", type="float", nullable=true)
     */
    private $moneyAED;


    /**
     * @var string $moneyCHF
     *
     * @ORM\Column(name="moneyCHF", type="float", nullable=true)
     */
    private $moneyCHF;

    /**
     * @var string $moneyJPY
     *
     * @ORM\Column(name="moneyJPY", type="float", nullable=true)
     */
    private $moneyJPY;

    /**
     *
     */
    public function __construct()
    {
        $this->date = new \DateTime(date('Y-m-01'));
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
     * Set moneyINR
     *
     * @param float $moneyINR
     * @return Devises
     */
    public function setMoneyINR($moneyINR)
    {
        $this->moneyINR = $moneyINR;

        return $this;
    }

    /**
     * Get moneyINR
     *
     * @return float
     */
    public function getMoneyINR()
    {
        return $this->moneyINR;
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
     * Set moneyAED
     *
     * @param float $moneyAED
     * @return Devises
     */
    public function setMoneyAED($moneyAED)
    {
        $this->moneyAED = $moneyAED;

        return $this;
    }

    /**
     * Get moneyAED
     *
     * @return float
     */
    public function getMoneyAED()
    {
        return $this->moneyAED;
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
}