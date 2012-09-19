<?php

namespace Application\Sonata\ClientBundle\DataFixtures\ORM;

use Application\Sonata\ClientBundle\DataFixtures\AbstractLoadListData;
use Application\Sonata\ClientBundle\Entity\ListFacturationDavances;


class LoadListFacturationDavancesData extends AbstractLoadListData
{
    /**
     * @var string
     */
    protected $_className = 'ListFacturationDavances';

    /**
     * @var array
     */
    protected $_lists = array(
        'Mois',
        'Trimestre',
        'Semestre',
        'Annuel :)',
    );
}