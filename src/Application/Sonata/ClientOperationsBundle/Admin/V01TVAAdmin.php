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
            ->add('montant_TVA_francaise', 'money', array('label' => $this->getFieldLabel('montant_TVA_francaise'), 'template' => 'ApplicationSonataClientOperationsBundle:CRUD:montant_TVA_francaise.html.twig'))
            ->add('montant_TTC', 'money', array('label' => $this->getFieldLabel('montant_TTC'), 'template' => 'ApplicationSonataClientOperationsBundle:CRUD:montant_TTC.html.twig'))
            ->add('paiement_montant', 'money', array('label' => $this->getFieldLabel('paiement_montant'), 'template' => 'ApplicationSonataClientOperationsBundle:CRUD:paiement_montant.html.twig'))
            ->add('paiement_devise.name', null, array('label' => $this->getFieldLabel('paiement_devise_id')))
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
            ->add('TVA', 'money', array('label' => $this->getFieldLabel('TVA'), 'template' => 'ApplicationSonataClientOperationsBundle:CRUD:TVA.html.twig'));
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
            $errorElement->with('mois')->addViolation('"Mois" should not be null')->end();
        }

        $value = $object->getNoTVATiers();
        if ($value) {
            if (!preg_match('/^FR.*/', $value)) {
                $errorElement->with('no_TVA_tiers')->addViolation('"N° TVA Tiers" should begin with "FR"')->end();
            }
        }

        $value = $object->getMontantTVAFrancaise();
        if ($value) {

            if (!($value == $this->getNumberRound($object->getMontantHTEnDevise() * $object->getTauxDeTVA()))) {
                $errorElement->with('montant_TVA_francaise')->addViolation('Wrong "Montant TVA Francaise"')->end();
            }
        }

        $value = $object->getMontantTTC();
        if ($value) {
            if (!($value == $this->getNumberRound($object->getMontantHTEnDevise() + $object->getMontantTVAFrancaise()))) {

                $errorElement->with('montant_TTC')->addViolation('Wrong "Montant TTC"')->end();
            }
        }

        $value = $object->getPaiementMontant();
        if ($value) {
            if (!$object->getPaiementDevise()) {
                $errorElement->with('paiement_montant')->addViolation('"Paiement Devise" can\'t be empty')->end();
            }

            if (!$object->getPaiementDate()) {
                $errorElement->with('paiement_montant')->addViolation('"Paiement Date" can\'t be empty')->end();
            }

            $mois = $object->getMois();

            if (!$mois) {
                if ($mois instanceof \DateTime) {
                    $month = $mois->format('n');
                    $year = $mois->format('Y');
                } else {
                    $month = $mois['month'];
                    $year = $mois['year'];
                }

                if ($year . '-' . $month != date('Y-n', strtotime('-1 month'))) {
                    $errorElement->with('mois')->addViolation('Wrong "Mois"')->end();
                }
            }
        }

        $value = $object->getHT();
        if ($value) {
            if (!($value == $this->getNumberRound($object->getMontantHTEnDevise() / $object->getTauxDeChange()))) {
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
            if ($devise) {
                $method = 'getMoney' . ucfirst($value);
                if (method_exists($devise, $method)) {
                    $error = !$devise->$method();
                }
            }
            if ($error) {
                $errorElement->with('devise')->addViolation('No Devise for this month')->end();
            }
        }
    }

}