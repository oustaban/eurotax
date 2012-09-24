<?php

namespace Application\Sonata\ClientOperationsBundle\Admin;

use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Validator\ErrorElement;

use Application\Sonata\ClientOperationsBundle\Admin\AbstractTabsAdmin as Admin;

class V01TVAAdmin extends Admin
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
            ->add('taux_de_TVA', 'percent', array('label' => $this->getFieldLabel('taux_de_TVA')))
            ->add('montant_TVA_francaise', 'money', array('label' => $this->getFieldLabel('montant_TVA_francaise')))
            ->add('montant_TTC', 'money', array('label' => $this->getFieldLabel('montant_TTC')))
            ->add('paiement_montant', 'money', array('label' => $this->getFieldLabel('paiement_montant')))
            ->add('paiement_devise', null, array('label' => $this->getFieldLabel('paiement_devise_id')))
            ->add('paiement_date', null, array(
                'label' => $this->getFieldLabel('paiement_date'),
                'attr' => array('class' => 'datepicker'),
                'widget' => 'single_text',
                'input' => 'datetime',
                'format' => $this->date_format_datetime)
        )
            ->add('mois', 'date', array(
            'label' => $this->getFieldLabel('mois'),
            'days' => range(1, 1),
            'format' => 'dd MMMM yyyy',
        ))
            ->add('taux_de_change', 'percent', array('label' => $this->getFieldLabel('taux_de_change')))
            ->add('HT', 'money', array('label' => $this->getFieldLabel('HT')))
            ->add('TVA', 'money', array('label' => $this->getFieldLabel('TVA')))
            ->add('commentaires', null, array('label' => $this->getFieldLabel('commentaires')));
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
            ->add('taux_de_TVA', 'percent', array('label' => $this->getFieldLabel('taux_de_TVA')))
            ->add('montant_TVA_francaise', 'money', array('label' => $this->getFieldLabel('montant_TVA_francaise')))
            ->add('montant_TTC', 'money', array('label' => $this->getFieldLabel('montant_TTC')))
            ->add('paiement_montant', 'money', array('label' => $this->getFieldLabel('paiement_montant')))
            ->add('paiement_devise_id', null, array('label' => $this->getFieldLabel('paiement_devise_id')))
            ->add('paiement_date', null, array(
            'label' => $this->getFieldLabel('paiement_date'),
            'template' => $this->_bundle_name . ':CRUD:list_paiement_date.html.twig'
        ))
            ->add('mois', null, array(
            'label' => $this->getFieldLabel('mois'),
            'template' => $this->_bundle_name . ':CRUD:list_mois.html.twig',
        ))
            ->add('taux_de_change', 'percent', array('label' => $this->getFieldLabel('taux_de_change')))
            ->add('HT', 'money', array('label' => $this->getFieldLabel('HT'), 'template' => 'ApplicationSonataClientOperationsBundle:CRUD:HT.html.twig'))
            ->add('TVA', 'money', array('label' => $this->getFieldLabel('TVA')));
    }

    /**
     * @param ErrorElement $errorElement
     * @param mixed $object
     */
    public function validate(ErrorElement $errorElement, $object)
    {
        /* @var $object \Application\Sonata\ClientOperationsBundle\Entity\V01TVA */
        parent::validate($errorElement, $object);

        $value = $object->getMois();
        if (!$value) {
            $errorElement->addViolation('"Mois" should not be null');
        }

        $value = $object->getNoTVATiers();
        if ($value) {
            if (!preg_match('/^FR.*/', $value)) {
                $errorElement->addViolation('"N° TVA Tiers" should begin with "FR"');
            }
        }

        $value = $object->getMontantTVAFrancaise();
        if ($value) {
            if (!($value == $object->getMontantHTEnDevise() * $object->getTauxDeTVA() / 100)) {
                $errorElement->addViolation('Wrong "Montant TVA Francaise"');
            }
        }

        $value = $object->getMontantTTC();
        if ($value) {
            if (!($value == $object->getMontantHTEnDevise() + $object->getMontantTVAFrancaise())) {
                $errorElement->addViolation('Wrong "Montant TTC"');
            }
        }

        $value = $object->getPaiementMontant();
        if ($value) {
            if (!$object->getPaiementDevise()) {
                $errorElement->addViolation('"Paiement Devise" can\'t be empty');
            }

            if (!$object->getPaiementDate()) {
                $errorElement->addViolation('"Paiement Date" can\'t be empty');
            }

            $mois = $object->getMois();
            if (!$mois || $mois['year'] . '-' . $mois['month'] != date('Y-n', strtotime('-1 month'))) {
                $errorElement->addViolation('Wrong "Mois"');
            }
        }

        $value = $object->getHT();
        if ($value) {
            if (!($value == $object->getMontantHTEnDevise()/$object->getTauxDeChange())) {
                $errorElement->addViolation('Wrong "HT"');
            }
        }

        $value = $object->getDevise()->getAlias();
        if ($value != 'euro') {
            /* @var $doctrine \Doctrine\Bundle\DoctrineBundle\Registry */
            $doctrine = \AppKernel::getStaticContainer()->get('doctrine');
            $em = $doctrine->getManager();
            /* @var $devise \Application\Sonata\DevisesBundle\Entity\Devises */
            $devise = $em->getRepository('ApplicationSonataDevisesBundle:Devises')->findOneByDate($object->getDatePiece());

            $error = true;
            if ($devise){
                $method = 'getMoney' . ucfirst($value);
                if (method_exists($devise, $method)) {
                    $error = !$devise->$method();
                }
            }
            if ($error){
                $errorElement->addViolation('No Devise for this month');
            }
        }
    }

}