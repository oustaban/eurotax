<?php

namespace Application\Sonata\ClientOperationsBundle\Admin;

use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Validator\ErrorElement;

use Application\Sonata\ClientOperationsBundle\Admin\AbstractTabsAdmin as Admin;

class V07EXAdmin extends Admin
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
            ->add('mois', 'date', array(
            'label' => $this->getFieldLabel('mois'),
            'days' => range(1, 1),
            'format' => 'dd MMMM yyyy',
        ))
            ->add('taux_de_change', 'percent', array('label' => $this->getFieldLabel('taux_de_change')))
            ->add('HT', 'money', array('label' => $this->getFieldLabel('HT')))
            ->add('commentaires', null, array('label' => $this->getFieldLabel('commentaires')));
    }

    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        parent::configureListFields($listMapper);

        $listMapper->add('tiers', null, array('label' => $this->getFieldLabel('tiers')))
            ->add('numero_piece', null, array('label' => $this->getFieldLabel('numero_piece')))
            ->add('date_piece', null, array(
            'label' => $this->getFieldLabel('date_piece'),
            'template' => $this->_bundle_name . ':CRUD:list_date_piece.html.twig'
        ))
            ->add('devise', null, array('label' => $this->getFieldLabel('devise_id')))
            ->add('montant_HT_en_devise', 'money', array('label' => $this->getFieldLabel('montant_HT_en_devise'), 'template' => 'ApplicationSonataClientOperationsBundle:CRUD:montant_HT_en_devise.html.twig'))
            ->add('mois', null, array(
            'label' => $this->getFieldLabel('mois'),
            'template' => $this->_bundle_name . ':CRUD:list_mois.html.twig',
        ))
            ->add('taux_de_change', 'percent', array('label' => $this->getFieldLabel('taux_de_change')))
            ->add('HT', 'money', array('label' => $this->getFieldLabel('HT'), 'template' => 'ApplicationSonataClientOperationsBundle:CRUD:HT.html.twig'));
    }

    /**
     * @param ErrorElement $errorElement
     * @param mixed $object
     */
    public function validate(ErrorElement $errorElement, $object)
    {
        /* @var $object \Application\Sonata\ClientOperationsBundle\Entity\V07EX */
        parent::validate($errorElement, $object);

        $value = $object->getMois();
        if (!$value || $value['year'] . '-' . $value['month'] != date('Y-n', strtotime('-1 month'))) {
            $errorElement->addViolation('Wrong "Mois"');
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