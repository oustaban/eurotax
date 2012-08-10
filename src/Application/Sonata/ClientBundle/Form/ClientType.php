<?php

namespace Application\Sonata\ClientBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ClientType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('user')
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
            ->add('nature_du_client')
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Application\Sonata\ClientBundle\Entity\Client'
        ));
    }

    public function getName()
    {
        return 'application_sonata_clientbundle_clienttype';
    }
}
