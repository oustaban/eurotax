<?php

namespace Application\Sonata\ClientBundle\DataFixtures\ORM;

use Application\Sonata\ClientBundle\DataFixtures\AbstractLoadListData;
use Application\Sonata\ClientBundle\Entity\ListModeDenregistrements;


class LoadListModeDenregistrementsData extends AbstractLoadListData
{
    /**
     * @var string
     */
    protected $_className = 'ListModeDenregistrements';

    /**
     * @var array
     */
    protected $_lists = array(
        'Express',
        'Normal',
    );
}