<?php

namespace Application\Sonata\ClientOperationsBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;


/**
 * Application\Sonata\ClientOperationsBundle\Entity\AbstractBuyEntity
 *
 * @ORM\MappedSuperclass
 */
abstract class AbstractBuyEntity extends AbstractAVEntity
{

    public function __construct(){
        parent::__construct();
    }
}