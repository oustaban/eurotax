<?php

namespace Application\Sonata\ClientOperationsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Application\Sonata\ClientOperationsBundle\Entity\Imports
 *
 * @ORM\Table("et_operations_imports")
 * @ORM\Entity
 */
class Imports
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
     * @ORM\Column(name="date", type="datetime")
     */
    private $date;

    /**
     * @var integer $user_id
     *
     * @ORM\ManyToOne(targetEntity="\Application\Sonata\UserBundle\Entity\User", inversedBy="imports")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;


    /**
     * @return Application\Sonata\UserBundle\Entity\User
     */
    public function __toString()
    {
        return $this->getUser();
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
     * Set user
     *
     * @param Application\Sonata\UserBundle\Entity\User $user
     * @return Imports
     */
    public function setUser(\Application\Sonata\UserBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return Application\Sonata\UserBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }
}