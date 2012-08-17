<?php

namespace Application\Sonata\ClientBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Application\Sonata\ClientBundle\Entity\ListTypeDocuments
 *
 * @ORM\Table("et_list_type_documents")
 * @ORM\Entity
 */
class ListTypeDocuments
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
     * @var string $name
     *
     * @ORM\Column(name="name", type="string", length=100)
     */
    private $name;


    /**
     * ORM\OneToMany(targetEntity="Document", mappedBy="type_document")
     **/
    protected $document;

    /***
     *
     */
    public function __construct()
    {

        $this->document = new ArrayCollection();
    }

    /**
     * @return string
     */

    public function __toString()
    {

        return $this->getName();
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
     * Set name
     *
     * @param string $name
     * @return ListTypeDocuments
     */
    public function setName($name)
    {
        $this->name = $name;
    
        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }


}