<?php

namespace Application\Sonata\ClientOperationsBundle\Admin;

use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Validator\ErrorElement;

use Application\Sonata\ClientOperationsBundle\Admin\AbstractTabsAdmin as Admin;

class V05LICAdmin extends Admin
{

    /**
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        parent::configureFormFields($formMapper);

        $formMapper
            ->add('tiers', null, array('label' => $this->getFieldLabel('tiers')))
            ->add('no_TVA_tiers', null, array('label' => $this->getFieldLabel('no_TVA_tiers')))
            ->add('date_piece', null, array(
                'label' => $this->getFieldLabel('date_piece'),
                'attr' => array('class' => 'datepicker'),
                'widget' => 'single_text',
                'input' => 'datetime',
                'format' => $this->date_format_datetime)
        )
            ->add('numero_piece', null, array('label' => $this->getFieldLabel('numero_piece')))
            ->add('devise', null, array('label' => $this->getFieldLabel('devise_id')))
            ->add('montant_HT_en_devise', 'money', array('label' => $this->getFieldLabel('montant_HT_en_devise')))
            ->add('mois', 'date', array(
            'label' => $this->getFieldLabel('mois'),
            'days' => range(1, 1),
            'format' => 'dd MMMM yyyy',
        ))
            ->add('taux_de_change', 'percent', array('label' => $this->getFieldLabel('taux_de_change')))
            ->add('HT', 'money', array('label' => $this->getFieldLabel('HT')))
            ->add('regime', null, array('label' => $this->getFieldLabel('regime')))
            ->add('DEB', null, array('label' => $this->getFieldLabel('DEB')))
            ->add('commentaires', null, array('label' => $this->getFieldLabel('commentaires')))
            ->add('n_ligne', null, array('label' => $this->getFieldLabel('n_ligne')))
            ->add('nomenclature', null, array('label' => $this->getFieldLabel('nomenclature')))
            ->add('pays_id_destination', 'country', array('label' => $this->getFieldLabel('pays_id_destination')))
            ->add('valeur_fiscale', 'money', array('label' => $this->getFieldLabel('valeur_fiscale')))
            ->add('valeur_statistique', null, array('label' => $this->getFieldLabel('valeur_statistique')))
            ->add('masse_mette', null, array('label' => $this->getFieldLabel('masse_mette')))
            ->add('unites_supplementaires', null, array('label' => $this->getFieldLabel('unites_supplementaires')))
            ->add('nature_transaction', null, array('label' => $this->getFieldLabel('nature_transaction')))
            ->add('conditions_livraison', null, array('label' => $this->getFieldLabel('conditions_livraison')))
            ->add('mode_transport', null, array('label' => $this->getFieldLabel('mode_transport')))
            ->add('departement', null, array('label' => $this->getFieldLabel('departement')))
            ->add('pays_id_origine', 'country', array('label' => $this->getFieldLabel('pays_id_origine')))
            ->add('CEE', null, array('label' => $this->getFieldLabel('CEE')));
    }

    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        parent::configureListFields($listMapper);

        $listMapper->add('tiers', null, array('label' => $this->getFieldLabel('tiers')))
            ->add('no_TVA_tiers', null, array('label' => $this->getFieldLabel('no_TVA_tiers')))
            ->add('date_piece', null, array(
            'label' => $this->getFieldLabel('date_piece'),
            'template' => $this->_bundle_name . ':CRUD:list_date_piece.html.twig'
        ))
            ->add('numero_piece', null, array('label' => $this->getFieldLabel('numero_piece')))
            ->add('devise.name', null, array('label' => $this->getFieldLabel('devise_id')))
            ->add('montant_HT_en_devise', 'money', array('label' => $this->getFieldLabel('montant_HT_en_devise'), 'template' => 'ApplicationSonataClientOperationsBundle:CRUD:montant_HT_en_devise.html.twig'))
            ->add('mois', null, array(
            'label' => $this->getFieldLabel('mois'),
            'template' => $this->_bundle_name . ':CRUD:list_mois.html.twig',
        ))
            ->add('regime', null, array('label' => $this->getFieldLabel('regime')))
            ->add('HT', 'money', array('label' => $this->getFieldLabel('HT'), 'template' => 'ApplicationSonataClientOperationsBundle:CRUD:HT.html.twig'))
            ->add('DEB', null, array('label' => $this->getFieldLabel('DEB')));
    }

    /**
     * @param ErrorElement $errorElement
     * @param mixed $object
     */
    public function validate(ErrorElement $errorElement, $object)
    {
        /* @var $object \Application\Sonata\ClientOperationsBundle\Entity\V05LIC */
        parent::validate($errorElement, $object);

        $value = $object->getMois();
        if (!$value) {
            if ($value instanceof \DateTime) {
                $month = $value->format('n');
                $year = $value->format('Y');
            } else {
                $month = $value['month'];
                $year = $value['year'];
            }

            if ($year . '-' . $month != date('Y-n', strtotime('-1 month'))) {
                $errorElement->with('mois')->addViolation('Wrong "Mois"')->end();
            }
        }

        $value = $object->getHT();
        if ($value) {
            if (!($value == $this->getNumberRound($object->getMontantHTEnDevise()/$object->getTauxDeChange()))) {
               $errorElement->with('HT')->addViolation('Wrong "HT"')->end();
            }
        }

        $value = $object->getDevise()->getAlias();
        if ($value != 'euro') {
            /* @var $doctrine \Doctrine\Bundle\DoctrineBundle\Registry */
            $doctrine = \AppKernel::getStaticContainer()->get('doctrine');
            $em = $doctrine->getManager();
            /* @var $devise \Application\Sonata\DevisesBundle\Entity\Devises */
            $devise = $em->getRepository('ApplicationSonataDevisesBundle:Devises')->findOneByDate($object->getDatePieceFormat());

            $error = true;
            if ($devise){
                $method = 'getMoney' . ucfirst($value);
                if (method_exists($devise, $method)) {
                    $error = !$devise->$method();
                }
            }
            if ($error){
                $errorElement->with('devise')->addViolation('No Devise for this month')->end();
            }
        }
    }
}