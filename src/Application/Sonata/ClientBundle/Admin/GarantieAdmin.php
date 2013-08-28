<?php

namespace Application\Sonata\ClientBundle\Admin;

use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Symfony\Component\Form\Extension\Core\Type\CountryType;

use Knp\Menu\ItemInterface as MenuItemInterface;
use Symfony\Component\HttpFoundation\Request;

use Sonata\AdminBundle\Validator\ErrorElement;
use Application\Sonata\ClientBundle\Entity\ClientAlert;
use Application\Form\Type\AmountType;

use Application\Sonata\ClientBundle\Entity\Compte;
use Application\Sonata\ClientBundle\Entity\CompteDeDepot;
use Application\Sonata\ClientBundle\Entity\Garantie;

use Application\Sonata\ClientBundle\Admin\AbstractTabsAdmin as Admin;

class GarantieAdmin extends Admin
{
    protected $_prefix_label = 'garantie';

    /**
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        parent::configureFormFields($formMapper);

        $id = $this->getRequest()->get($this->getIdParameter());

        
        $doctrine = \AppKernel::getStaticContainer()->get('doctrine');
        /* @var $em \Doctrine\ORM\EntityManager */
        $em = $doctrine->getManager();
        
        
        
        if($id && $garantie = $em->getRepository('ApplicationSonataClientBundle:Garantie')->find($id)) {
        	//$typeDocumentId = $document->getTypeDocument()->getId();
        	$formMapper->with(' ')
        		->add('nom_de_la_banques_id_old', 'hidden', array('data' => $garantie->getNomDeLaBanquesId(), 'mapped' => false, ));
        }
        
        
        
        
        
        $formMapper->with($this->getFieldLabel('title'))
            ->add('type_garantie', null,
            array(
                'label' => $this->getFieldLabel('type_garantie'),
                'disabled' => !!$id,
            ))
            ->add('montant', new AmountType(), array(
            'data_class' => 'Application\Sonata\ClientBundle\Entity\Garantie',
            'label' => $this->getFieldLabel('montant'),
        ))
            ->add('nom_de_lemeteur', null, array('label' => $this->getFieldLabel('nom_de_lemeteur')))
            ->add('nom_de_la_banques_id', 'choice', array(
                'label' => $this->getFieldLabel('nom_de_la_banques_id'),
                'data' => $id ? null : 1,
                'choices' => Garantie::getNomDeLaBanques())
        )
            ->add('num_de_ganrantie', null, array(
            'label' => $this->getFieldLabel('num_de_ganrantie'),
            'data' => $id ? null : 'sans référence',
        ))
            ->add('date_demission', null, array(
            'label' => $this->getFieldLabel('date_demission'),
            'attr' => array('class' => 'datepicker'),
            'widget' => 'single_text',
            'input' => 'datetime',
            'format' => $this->date_format_datetime
        ))
            ->add('date_decheance', null, array(
            'label' => $this->getFieldLabel('date_decheance'),
            'attr' => array('class' => 'datepicker'),
            'widget' => 'single_text',
            'input' => 'datetime',
            'format' => $this->date_format_datetime
        ))
            ->add('expire', null, array('label' => $this->getFieldLabel('expire')))
            ->add('note', null, array('label' => $this->getFieldLabel('note')));
    }

    //list
    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        parent::configureListFields($listMapper);

        $listMapper
            ->add('type_garantie.name', null, array('label' => $this->getFieldLabel('type_garantie')))
            ->add('montant', 'money', array(
            'label' => $this->getFieldLabel('montant'),
            'template' => 'ApplicationSonataClientBundle:CRUD:list_garantie_montant.html.twig',
        ))
            ->add('date_decheance', 'date', array(
            'label' => $this->getFieldLabel('date_decheance'),
            'template' => 'ApplicationSonataClientBundle:CRUD:list_date_decheance.html.twig',
        ))
            ->add('nom_de_la_banques_id', null, array(
            'label' => $this->getFieldLabel('nom_de_la_banques_id'),
            'template' => 'ApplicationSonataClientBundle:CRUD:list_banques.html.twig',
        ));
    }

    /**
     * @param mixed $object
     * @return mixed|void
     */
    public function postPersist($object)
    {
    	
    	$this->_addCompteCompteDeDepotLines($object);
        
   
    	if($object->getNomDeLaBanquesId() == 0) {
           	$this->_versementDuDepotDeGarantie($object);
        }
    }

    /**
     * @param \Application\Sonata\ClientBundle\Entity\Garantie $object
     * @return mixed|void
     */
    public function preRemove($object)
    {
        //2 => Dépôt de Garantie
        if ($object->getTypeGarantie() && $object->getTypeGarantie()->getId() == 2) {
            /* @var $doctrine \Doctrine\Bundle\DoctrineBundle\Registry */
            $doctrine = \AppKernel::getStaticContainer()->get('doctrine');

            /* @var $em \Doctrine\ORM\EntityManager */
            $em = $doctrine->getManager();

            /** @var $compte_de_depot CompteDeDepot */
            list($compte_de_depot) = $em->getRepository('ApplicationSonataClientBundle:CompteDeDepot')
                ->createQueryBuilder('c')
                ->select('SUM(c.montant) as total')
                ->setMaxResults(1)
                ->getQuery()
                ->execute();

            if ($compte_de_depot['total'] != 0) {
                echo '<div class="alert alert-error">' . $this->trans("Impossible de supprimer le dépot de garantie car le solde du compte de dépot n'est pas nul") . '</div>';
                exit;
            } else {

                //DELETE
                /** @var $compte Compte */
                $compte = $em->getRepository('ApplicationSonataClientBundle:Compte')->findByGarantie($object->getId());

                foreach ($compte as $c) {
                    $c->setGarantie(NULL);
                    $em->persist($c);
                }

                /** @var $compte_de_depot CompteDeDepot */
                $compte_de_depot = $em->getRepository('ApplicationSonataClientBundle:CompteDeDepot')->findByGarantie($object->getId());

                foreach ($compte_de_depot as $c) {
                    $c->setGarantie(NULL);
                    $em->persist($c);
                }

                $em->flush();

                unset($c, $compte_de_depot, $compte);
            }
        }
    }

    /**
     * (non-PHPdoc)
     * @see \Sonata\AdminBundle\Admin\Admin::postUpdate()
     */
    public function postUpdate($object)
    {
    	$formRequestData = $this->getRequest()->request->all();
    	$formRequestData = $formRequestData[$this->getForm()->getName()];
    	
    	$nom_de_la_banques_id = $object->getNomDeLaBanquesId();
    	$nom_de_la_banques_id_old = $formRequestData['nom_de_la_banques_id_old'];
    	
    	if(!$nom_de_la_banques_id  &&  $nom_de_la_banques_id_old == 1) {
    		$this->_versementDuDepotDeGarantie($object);
    	}
    	
    	$this->_addCompteCompteDeDepotLines($object);
    }
    
    
    
    protected function _addCompteCompteDeDepotLines($object) {
    	/** @var $object  \Application\Sonata\ClientBundle\Entity\Garantie */
    	if ($object->getTypeGarantie() && $object->getNomDeLaBanquesId() == 0) { // not "a etablir"
    	
    		//2 => Dépôt de Garantie
    		if ($object->getTypeGarantie()->getId() == 2) {
    	
    			/* @var $doctrine \Doctrine\Bundle\DoctrineBundle\Registry */
    			$doctrine = \AppKernel::getStaticContainer()->get('doctrine');
    	
    			/* @var $em \Doctrine\ORM\EntityManager */
    			$em = $doctrine->getManager();
    	
    			//example: http://redmine.testenm.com/issues/880
    			$status_object = $em->getRepository('ApplicationSonataClientBundle:ListCompteStatuts')->find(1);
    	
    			//1
    			$compte = new Compte();
    			$compte->setDate($object->getDateDemission());
    			$compte->setMontant($object->getMontant());
    			$compte->setOperation('Versement du dépôt de garantie');
    			$compte->setClient($object->getClient());
    			$compte->setGarantie($object);
    			$compte->setStatut($status_object);
    			$em->persist($compte);
    	
    			//2
    			$compte = new Compte();
    			$compte->setDate($object->getDateDemission());
    			$compte->setMontant(-$object->getMontant());
    			$compte->setOperation('Transfert dans compte de dépôt');
    			$compte->setClient($object->getClient());
    			$compte->setGarantie($object);
    			$compte->setStatut($status_object);
    			$em->persist($compte);
    	
    			//3
    			$compte_de_depot = new CompteDeDepot();
    			$compte_de_depot->setDate($object->getDateDemission());
    			$compte_de_depot->setMontant($object->getMontant());
    			$compte_de_depot->setOperation('Transfert du compte courant');
    			$compte_de_depot->setClient($object->getClient());
    			$compte_de_depot->setGarantie($object);
    			$compte_de_depot->setStatut($status_object);
    			$em->persist($compte_de_depot);
    	
    			$em->flush();
    	
    			unset($compte, $compte_de_depot);
    		}
    	}
    }
    
    
    protected function _versementDuDepotDeGarantie($object) {
    	/* @var $doctrine \Doctrine\Bundle\DoctrineBundle\Registry */
    	$doctrine = \AppKernel::getStaticContainer()->get('doctrine');
    	/* @var $em \Doctrine\ORM\EntityManager */
    	$em = $doctrine->getManager();
    	//example: http://redmine.testenm.com/issues/880
    	$status_object = $em->getRepository('ApplicationSonataClientBundle:ListCompteStatuts')->find(1);
    	//1
    	$compte = new Compte();
    	$compte->setDate($object->getDateDemission());
    	$compte->setMontant($object->getMontant());
    	$compte->setOperation('Versement du dépôt de garantie');
    	$compte->setClient($object->getClient());
    	$compte->setGarantie($object);
    	$compte->setStatut($status_object);
    	$em->persist($compte);
    	$em->flush();
    }
}

