<?php

namespace Application\Sonata\ClientBundle\DataFixtures\ORM;

use Application\Sonata\ClientBundle\DataFixtures\AbstractLoadListData;
use Application\Sonata\ClientBundle\Entity\ListTypeGaranties;


class LoadListTypeGarantiesData extends AbstractLoadListData
{
    /**
     * @var string
     */
    protected $_className = 'ListTypeGaranties';

    /**
     * @var array
     */
    protected $_lists = array(
        'Garantie Bancaire',
        'Dépôt de Garantie',
        'Garantie Parentale',
    );
}