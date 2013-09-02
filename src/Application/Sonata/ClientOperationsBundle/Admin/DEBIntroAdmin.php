<?php

namespace Application\Sonata\ClientOperationsBundle\Admin;

use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Validator\ErrorElement;
use Application\Sonata\ClientOperationsBundle\Admin\Validate\DEBErrorElements;
use Doctrine\ORM\EntityRepository;

use Application\Sonata\ClientOperationsBundle\Admin\AbstractTabsAdmin as Admin;

class DEBIntroAdmin extends Admin
{

    /**
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        parent::configureFormFields($formMapper);

        $id = $this->getRequest()->get($this->getIdParameter());

        if ($id){
            $formMapper
                ->add('mois', null, array('label' => $this->getFieldLabel('mois'), 'disabled' => true));
        }

        $formMapper
            ->add('n_ligne', null, array('label' => $this->getFieldLabel('n_ligne')))
            /* ->add('date_piece', null, array(
                'label' => 'Mois TVA',
                'attr' => array('class' => 'datepicker'),
                'widget' => 'single_text',
                'input' => 'datetime',
                'format' => $this->date_format_datetime)
        ) */
            ->add('nomenclature', null, array('label' => $this->getFieldLabel('nomenclature')))
            ->add('pays_destination', null, array('label' => $this->getFieldLabel('pays_id_destination'), 'query_builder' => function (EntityRepository $er)
        {
            return $er->createQueryBuilder('p')
                ->andWhere('p.destination=1')
                ->orderBy('p.code')
                ;
        },'property' => 'code',))
            ->add('valeur_fiscale', 'money', array('label' => $this->getFieldLabel('valeur_fiscale'), 'required' => false))
            ->add('regime', 'choice', array('label' => $this->getFieldLabel('regime'),
            		'choices' => array(
            				11 => 11,
            				19 => 19,
            		),
            		'empty_value' => '',
        	))
            ->add('valeur_statistique', 'money', array('label' => $this->getFieldLabel('valeur_statistique'), 'required' => false))
            ->add('masse_mette', null, array('label' => $this->getFieldLabel('masse_mette')))
            ->add('unites_supplementaires', null, array('label' => $this->getFieldLabel('unites_supplementaires')))
            ->add('nature_transaction', 'choice', array('label' => $this->getFieldLabel('nature_transaction'), 'choices' => $this->_nature_transaction_source, 'empty_value' => null, 'data' => !$id ? 0 : null))
            ->add('conditions_livraison', null, array('label' => $this->getFieldLabel('conditions_livraison')))
            ->add('mode_transport', null, array('label' => $this->getFieldLabel('mode_transport')))
            ->add('departement', null, array('label' => $this->getFieldLabel('departement')))
            ->add('pays_origine', null, array('label' => $this->getFieldLabel('pays_id_origine'), 'query_builder' => function (EntityRepository $er)
        {
            return $er->createQueryBuilder('p')
                //->andWhere('p.destination=1')
                ->orderBy('p.code')
                ;
        },'property' => 'code',))
          //  ->add('CEE', null, array('label' => $this->getFieldLabel('CEE')))
          ;
    }

    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        parent::configureListFields($listMapper);

        $listMapper->add('n_ligne', null, array('label' => $this->getFieldLabel('n_ligne')))
            ->add('date_piece', null, array(
            'label' => 'Mois TVA',
            'template' => $this->_bundle_name . ':CRUD:list_date_piece_2.html.twig'
        ))
            ->add('nomenclature', null, array('label' => $this->getFieldLabel('nomenclature')))
            ->add('pays_destination.name', null, array('label' => $this->getFieldLabel('pays_id_destination')))
            ->add('valeur_fiscale', 'money', array('label' => $this->getFieldLabel('valeur_fiscale'), 'template' => 'ApplicationSonataClientOperationsBundle:CRUD:valeur_fiscale.html.twig'))
            ->add('regime', null, array('label' => $this->getFieldLabel('regime')))
            ->add('valeur_statistique', 'money', array('label' => $this->getFieldLabel('valeur_statistique'), 'template' => 'ApplicationSonataClientOperationsBundle:CRUD:valeur_statistique.html.twig'))
            ->add('masse_mette', null, array('label' => $this->getFieldLabel('masse_mette')))
            ->add('unites_supplementaires', null, array('label' => $this->getFieldLabel('unites_supplementaires')))
            ->add('nature_transaction', null, array('label' => $this->getFieldLabel('nature_transaction')))
            ->add('conditions_livraison', null, array('label' => $this->getFieldLabel('conditions_livraison')))
            ->add('mode_transport', null, array('label' => $this->getFieldLabel('mode_transport')))
            ->add('departement', null, array('label' => $this->getFieldLabel('departement')))
            ->add('pays_origine.name', null, array('label' => $this->getFieldLabel('pays_id_origine')))
            //->add('CEE', null, array('label' => $this->getFieldLabel('CEE')))
            ;

        $this->postConfigureListFields($listMapper);
    }

    /**
     * @param \Sonata\AdminBundle\Validator\ErrorElement $errorElement
     * @param mixed $object
     */
    public function validate(ErrorElement $errorElement, $object)
    {
        /* @var $object \Application\Sonata\ClientOperationsBundle\Entity\DEBIntro */
        parent::validate($errorElement, $object);

        $error = new DEBErrorElements($errorElement, $object, $this->import_file_year, $this->import_file_month);
        $error->setValidateImport($this->getValidateImport())
        	->validateRegime(array(11, 19))
        	->validateDEB()
        	->setMois($this)
        	->setDatePieceByFilename();

       
    }
}