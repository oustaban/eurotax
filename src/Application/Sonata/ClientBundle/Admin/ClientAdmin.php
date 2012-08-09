<?php

namespace Application\Sonata\ClientBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Show\ShowMapper;

use Knp\Menu\ItemInterface as MenuItemInterface;

class ClientAdmin extends Admin
{
    //create & edit form
    protected function configureFormFields(FormMapper $formMapper)
    {

        $formMapper
            ->add('user_id')
            ->add('nom')
            ->add('raison_sociale')
            ->add('adresse_1_postal')
            ->add('adresse_2_postal')
            ->add('code_postal_postal')
            ->add('ville_postal')
            ->add('pays_id_postal')
            ->add('activite')
            ->add('date_debut_mission')
            ->add('mode_denregistrement_id')
            ->add('avance_contractuelle')
            ->add('siret')
            ->add('periodicite_facturation_id')
            ->add('num_dossier_fiscal')
            ->add('taxe_additionnelle')
            ->add('periodicite_CA3_id')
            ->add('center_des_impots_id')
            ->add('adresse_1_facturation')
            ->add('adresse_2_facturation')
            ->add('code_postal_facturation')
            ->add('ville_facturation')
            ->add('pays_id_facturation')
            ->add('date_fin_mission')
            ->add('libelle_avance')
            ->add('date_de_depot_id')
            ->add('N_TVA_CEE')
            ->add('niveau_dobligation_id')
            ->add('nature_du_client');

            //->add('client', new ClientType());
    }

    //filter form
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {

        $datagridMapper
        ->add('user_id')
        ->add('nom')
        ->add('raison_sociale')
        ->add('adresse_1_postal')
        ->add('adresse_2_postal')
        ->add('code_postal_postal')
        ->add('ville_postal')
        ->add('pays_id_postal')
        ->add('activite')
        ->add('date_debut_mission')
        ->add('mode_denregistrement_id')
        ->add('avance_contractuelle')
        ->add('siret')
        ->add('periodicite_facturation_id')
        ->add('num_dossier_fiscal')
        ->add('taxe_additionnelle')
        ->add('periodicite_CA3_id')
        ->add('center_des_impots_id')
        ->add('adresse_1_facturation')
        ->add('adresse_2_facturation')
        ->add('code_postal_facturation')
        ->add('ville_facturation')
        ->add('pays_id_facturation')
        ->add('date_fin_mission')
        ->add('libelle_avance')
        ->add('date_de_depot_id')
        ->add('N_TVA_CEE')
        ->add('niveau_dobligation_id')
        ->add('nature_du_client');
    }

    //list
    protected function configureListFields(ListMapper $listMapper)
    {

        $listMapper
        ->add('user_id')
        ->add('nom')
        ->add('raison_sociale')
        ->add('adresse_1_postal')
        ->add('adresse_2_postal')
        ->add('code_postal_postal')
        ->add('ville_postal')
        ->add('pays_id_postal')
        ->add('activite')
        ->add('date_debut_mission')
        ->add('mode_denregistrement_id')
        ->add('avance_contractuelle')
        ->add('siret')
        ->add('periodicite_facturation_id')
        ->add('num_dossier_fiscal')
        ->add('taxe_additionnelle')
        ->add('periodicite_CA3_id')
        ->add('center_des_impots_id')
        ->add('adresse_1_facturation')
        ->add('adresse_2_facturation')
        ->add('code_postal_facturation')
        ->add('ville_facturation')
        ->add('pays_id_facturation')
        ->add('date_fin_mission')
        ->add('libelle_avance')
        ->add('date_de_depot_id')
        ->add('N_TVA_CEE')
        ->add('niveau_dobligation_id')
        ->add('nature_du_client');
    }
}