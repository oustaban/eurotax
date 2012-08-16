<?php

namespace Application\Sonata\ClientBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Application\Sonata\ClientBundle\Entity\ListCategorieCommentaires
 *
 * @ORM\Table("et_list_categorie_commentaires")
 * @ORM\Entity
 */
class ListCategorieCommentaires
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
     * @ORM\Column(name="name", type="string", length=200)
     */
    private $name;


    /**
     * ORM\OneToMany(targetEntity="Commentaire", mappedBy="category")
     **/
    protected $commentaire;

    /***
     *
     */
    public function __construct()
    {

        $this->commentaire = ArrayCollection();
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
     * @return ListCategorieCommentaires
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