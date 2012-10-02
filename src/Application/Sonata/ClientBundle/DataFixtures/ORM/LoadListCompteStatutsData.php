<?php

namespace Application\Sonata\ClientBundle\DataFixtures\ORM;

use Application\Sonata\ClientBundle\DataFixtures\AbstractLoadListData;
use Application\Sonata\ClientBundle\Entity\ListCivilites;


class LoadListCompteStatutsData extends AbstractLoadListData
{
    /**
     * @var string
     */
    protected $_className = 'ListCompteStatuts';

    /**
     * @var array
     */
    protected $_lists = array(
        'Réél',
        'Prévisionnel',
    );
}