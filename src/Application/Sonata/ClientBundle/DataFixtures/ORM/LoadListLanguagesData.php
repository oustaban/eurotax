<?php

namespace Application\Sonata\ClientBundle\DataFixtures\ORM;

use Application\Sonata\ClientBundle\DataFixtures\AbstractLoadListData;
use Application\Sonata\ClientBundle\Entity\ListCivilites;


class LoadListLanguagesData extends AbstractLoadListData
{
    /**
     * @var string
     */
    protected $_className = 'ListLanguages';

    /**
     * @var array
     */
    protected $_lists = array(
        'FR',
        'EN',
        'IT',
    );
}