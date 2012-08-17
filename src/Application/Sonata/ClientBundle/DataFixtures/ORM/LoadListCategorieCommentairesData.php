<?php

namespace Application\Sonata\ClientBundle\DataFixtures\ORM;

use Application\Sonata\ClientBundle\DataFixtures\AbstractLoadListData;
use Application\Sonata\ClientBundle\Entity\ListCategorieCommentaires;


class LoadListCategorieCommentairesData extends AbstractLoadListData
{
    /**
     * @var string
     */
    protected $_className = 'ListCategorieCommentaires';

    /**
     * @var array
     */
    protected $_lists = array(
        'Fees',
        'TVA',
        'Docs Légaux',
        'Facturation',
        'Autres',
    );
}