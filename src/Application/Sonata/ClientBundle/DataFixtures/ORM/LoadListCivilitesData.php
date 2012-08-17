<?php

namespace Application\Sonata\ClientBundle\DataFixtures\ORM;

use Application\Sonata\ClientBundle\DataFixtures\AbstractLoadListData;
use Application\Sonata\ClientBundle\Entity\ListCivilites;


class LoadListCivilitesData extends AbstractLoadListData
{
    /**
     * @var string
     */
    protected $_className = 'ListCivilites';

    /**
     * @var array
     */
    protected $_lists = array(
        'Mr.',
        'Mme.',
        'M.',
        'Ms.',
    );
}