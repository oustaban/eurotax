<?php

namespace Application\Sonata\ClientBundle\DataFixtures\ORM;

use Application\Sonata\ClientBundle\DataFixtures\AbstractLoadListData;
use Application\Sonata\ClientBundle\Entity\ListFacturationDuClients;


class LoadListFacturationDuClientsData extends AbstractLoadListData
{
    /**
     * @var string
     */
    protected $_className = 'ListFacturationDuClients';

    /**
     * @var array
     */
    protected $_lists = array(
        '5 (à échoir)',
        '25 (échue)',
    );
}