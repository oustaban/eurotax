<?php

namespace Application\Sonata\ClientBundle\DataFixtures\ORM;

use Application\Sonata\ClientBundle\DataFixtures\AbstractLoadListData;
use Application\Sonata\ClientBundle\Entity\ListCivilites;


class LoadListModeDeFacturationsData extends AbstractLoadListData
{
    /**
     * @var string
     */
    protected $_className = 'ListModeDeFacturations';

    /**
     * @var array
     */
    protected $_lists = array(
        'Enregistrement express',
        'Enregistrement normal',
        'Transfert Dossier',
        'Désenregistrement',
        'Forfait Global',
        'Forfait par CA3',
        'Forfait par CA3 (prix selon "nulle")',
        'Forfait par CA3 (prix selon "mouvementée")',
        'Forfait par DES',
        'Forfait par DEB Introduction',
        'Forfait par DEB COMPLEMENTAIRE Introduction',
        'Forfait par Ligne / DEB Introduction',
        'Forfait par DEB Expédition',
        'Forfait par DEB COMPLEMENTAIRE Expédition',
        'Forfait par Ligne / DEB Expédition',
        'Forfait par Demande Remboursement de TVA',
        'Variable TVA remboursée',
        'Variable "% CA"',
        'Variable "% TVA collectée"',
        'Variable "% TVA ded (Achat FR)"',
        'Variable "% TVA ded (A/L FR)"',
    );
}