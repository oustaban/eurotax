<?php

namespace Application\Sonata\ClientBundle\DataFixtures\ORM;

use Application\Sonata\ClientBundle\DataFixtures\AbstractLoadListData;
use Application\Sonata\ClientBundle\Entity\ListNatureDuClients;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping as ORM;

class LoadListNatureDuClientsData extends AbstractLoadListData
{
    /**
     * @var string
     */
    protected $_className = 'ListNatureDuClients';

    /**
     * @var array
     */
    protected $_lists = array(
        '6e',
        'DEB',
        'DES',
    );
}