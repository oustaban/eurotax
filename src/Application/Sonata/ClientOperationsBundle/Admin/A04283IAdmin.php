<?php

namespace Application\Sonata\ClientOperationsBundle\Admin;

use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Validator\ErrorElement;

use Application\Sonata\ClientOperationsBundle\Admin\AbstractTabsAdmin as Admin;

class A04283IAdmin extends Admin
{

    /**
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        parent::configureFormFields($formMapper);

        $formMapper
            ->add('tiers', null, array('label' => $this->getFieldLabel('tiers')))
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
            ->add('date_piece', null, array(
            'label' => $this->getFieldLabel('date_piece'),
            'template' => $this->_bundle_name . ':CRUD:list_date_piece.html.twig'
        ))
            ->add('numero_piece', null, array('label' => $this->getFieldLabel('numero_piece')))
            ->add('devise', null, array('label' => $this->getFieldLabel('devise_id')))
            ->add('montant_HT_en_devise', 'money', array('label' => $this->getFieldLabel('montant_HT_en_devise'), 'template' => 'ApplicationSonataClientOperationsBundle:CRUD:montant_HT_en_devise.html.twig'))
            ->add('taux_de_TVA', 'percent', array('label' => $this->getFieldLabel('taux_de_TVA')))
            ->add('mois', null, array(
            'label' => $this->getFieldLabel('mois'),
            'template' => $this->_bundle_name . ':CRUD:list_mois.html.twig',
        ))
            ->add('taux_de_change', 'percent', array('label' => $this->getFieldLabel('taux_de_change')))
            ->add('HT', 'money', array('label' => $this->getFieldLabel('HT'), 'template' => 'ApplicationSonataClientOperationsBundle:CRUD:HT.html.twig'))
            ->add('TVA', 'money', array('label' => $this->getFieldLabel('TVA')))
            ->add('commentaires', null, array('label' => $this->getFieldLabel('commentaires')));
    }

    protected function getDate_pieceFormValue($value)
    {
        return $this->dateFormValue($value);
    }

    /**
     * @param ErrorElement $errorElement
     * @param mixed $object
     */
    public function validate(ErrorElement $errorElement, $object)
    {
        /* @var $object \Application\Sonata\ClientOperationsBundle\Entity\A04283I */
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
                $errorElement->addViolation('Wrong "Mois"');
            }
        }

        $value = $object->getHT();
        if ($value) {
            if (!($value == $this->getNumberRound($object->getMontantHTEnDevise()/$object->getTauxDeChange()))) {
                $errorElement->addViolation('Wrong "HT"');
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
                $errorElement->addViolation('No Devise for this month');
            }
        }
    }
}