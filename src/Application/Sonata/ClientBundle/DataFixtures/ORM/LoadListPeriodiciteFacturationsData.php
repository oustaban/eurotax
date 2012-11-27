<?php

namespace Application\Sonata\ClientBundle\DataFixtures\ORM;

use Application\Sonata\ClientBundle\DataFixtures\AbstractLoadListData;
use Application\Sonata\ClientBundle\Entity\ListPeriodiciteFacturations;


class LoadListPeriodiciteFacturationsData extends AbstractLoadListData
{
    /**
     * @var string
     */
    protected $_className = 'ListPeriodiciteFacturations';

    /**
     * @var array
     */
    protected $_lists = array(
        'Mensuelle',
        'Trimestrielle',
        'Semestrielle',
        'Annuelle',
    );
}