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
use Application\Sonata\ClientBundle\Entity\ListTypeDocuments;
use Application\Sonata\ClientBundle\Entity\ListNatureDuClients;
use Doctrine\ORM\EntityRepository;

use Application\Sonata\ClientBundle\Admin\AbstractTabsAdmin as Admin;

class DocumentAdmin extends Admin
{
    /**
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        parent::configureFormFields($formMapper);
        
        $id = $this->getRequest()->get($this->getIdParameter());
        $client = $this->getClient();

        $typeDocumentId = 0;
        $typeDocDisabled = false;
        
        $dateNotaire = null;
        $dateApostille = null;
        
        if($id && $document = $this->getObject($id)) {
        	if($document->getTypeDocument()) {
        		$typeDocumentId = $document->getTypeDocument()->getId();
        	}
        	$dateNotaire = $document->getDateNotaire();
        	$dateApostille = $document->getDateApostille();
        }
        
        // 2 = Pouvoir || 3 = Accord
        if($typeDocumentId == 2 || $typeDocumentId == 3) {
        	$typeDocDisabled = true;
        }
        
        $formMapper->with($this->getFieldLabel('title'))
        
        	->add('local_file_path', 'hidden')
            ->add('file', 'file', array('label' => $this->getFieldLabel('document'), 'required' => false))
            ->add('type_document', null, array(
            'label' => $this->getFieldLabel('type_document'),
            'disabled' => $typeDocDisabled,
            'query_builder' => function (EntityRepository $er) use ($client) {
                $builder = $er->createQueryBuilder('t');
                $filter = array();
				if($client) {
                	/** @var $client \Application\Sonata\ClientBundle\Entity\Client */
	                switch ($client->getNatureDuClient()->getId()) {
	                    case ListNatureDuClients::sixE:
	                        if ($client->getPaysPostal()->getEU()){
	                            $filter = array(
	                                ListTypeDocuments::Mandat,
	                                ListTypeDocuments::Attestation_de_TVA,
	                                ListTypeDocuments::Mandat_Specifique,
	                            );
	
	                        }
	                        else {
	                            $filter = array(
	                                ListTypeDocuments::Pouvoir,
	                                ListTypeDocuments::Accord,
	                                ListTypeDocuments::Lettre_de_designation,
	                            );
	                        }
	                        break;
	                    case ListNatureDuClients::DEB:
	                    	$filter = array(
	                    		ListTypeDocuments::Mandat,
	                    		ListTypeDocuments::Attestation_de_TVA
	                    	);
	                    	
	                    	break;
	                    case ListNatureDuClients::DES:
	                        $filter = array(
	                            ListTypeDocuments::Mandat,
	                        );
	                        break;
	                }
				}
                if (!empty($filter)){
                    $builder->andWhere('t.id IN ('.implode(',', $filter).')');
                }

                return $builder;
            },
        ))
            ->add('date_document', null, array(
            'label' => $this->getFieldLabel('date_document'),
            'attr' => array('class' => 'datepicker'),
            'widget' => 'single_text',
            'input' => 'datetime',
            'format' => $this->date_format_datetime
        ))
            ->add('preavis', null, array('label' => $this->getFieldLabel('preavis')))
            ->add('particularite', null, array('label' => $this->getFieldLabel('particularite')))
            ->add('date_notaire', null, array(
            'label' => $this->getFieldLabel('date_notaire'),
            'attr' => array('class' => 'datepicker'),
            'widget' => 'single_text',
            'input' => 'datetime',
            'format' => $this->date_format_datetime,
            'disabled' => $dateNotaire ? true : false
        ))
            ->add('statut_document_notaire', null, array(
            'label' => $this->getFieldLabel('statut_document_notaire'),
            'empty_value' => '',
            'required' => false,
            'disabled' => $dateNotaire ? true : false
        ))

            ->add('date_apostille', null, array(
            'label' => $this->getFieldLabel('date_apostille'),
            'attr' => array('class' => 'datepicker'),
            'widget' => 'single_text',
            'input' => 'datetime',
            'format' => $this->date_format_datetime,
            'disabled' => $dateApostille ? true : false
        ))

            ->add('statut_document_apostille', null, array(
            'label' => $this->getFieldLabel('statut_document_apostille'),
            'empty_value' => '',
            'required' => false,
            'disabled' => $dateApostille ? true : false
        ));
    }

    //list
    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        parent::configureListFields($listMapper);

        $listMapper->add('document', null, array(
        	'attr' => array('class' => 'no-edit'),
            'label' => $this->getFieldLabel('document'),
            'template' => 'ApplicationSonataClientBundle:CRUD:document_local_file_path.html.twig'
        ))
        ->add('type_document.name', null, array('label' => $this->getFieldLabel('type_document')))
            ->add('date_document', null, array(
            'label' => $this->getFieldLabel('date_document'),
            'template' => 'ApplicationSonataClientBundle:CRUD:list_date_document.html.twig'
        ))
        ->add('statut_document_notaire', null, array(
        	'label' => 'Statut Notaire',
        	'template' => 'ApplicationSonataClientBundle:CRUD:list_statut_document_notaire.html.twig'
        ))
        ->add('statut_document_apostille', null, array(
        	'label' => 'Statut Apostille',
        	'template' => 'ApplicationSonataClientBundle:CRUD:list_statut_document_apostille.html.twig'
        ))
        ->add('client.N_TVA_CEE', null, array('label' => 'N TVA CEE',
        	'template' => 'ApplicationSonataClientBundle:CRUD:list_client_n_tva_cee_document.html.twig'
        ))
        ;
    }

    /**
     * @param mixed $document
     * @return mixed|void
     */
    public function prePersist($document)
    {
        $document->upload();
    }

    /**
     * @param mixed $document
     * @return mixed|void
     */
    public function preUpdate($document)
    {
        $document->upload();
    }

    /**
     * @param mixed $document
     * @return mixed|void
     */
    public function postRemove($document)
    {
        $document->removeUpload();
        $this->removePasDeMandat($document);
    }

    
    public function postPersist($object)
    {
    	
    	$this->removePasDeMandat($object);
    }
    
    /**
     * {@inheritdoc}
     */
    public function postUpdate($object)
    {
    	$this->removePasDeMandat($object);
    }
    
    
    /**
     * @param ErrorElement $errorElement
     * @param mixed $object
     */
    public function validate(ErrorElement $errorElement, $object)
    {
        /* @var $object \Application\Sonata\ClientBundle\Entity\Client */
        parent::validate($errorElement, $object);

        $this->_setupAlerts($errorElement, $object);
    }

    /**
     * @param ErrorElement $errorElement
     * @param \Application\Sonata\ClientBundle\Entity\Document $object
     */
    protected function _setupAlerts(ErrorElement $errorElement, $object)
    {
        /* @var $doctrine \Doctrine\Bundle\DoctrineBundle\Registry */
        $doctrine = \AppKernel::getStaticContainer()->get('doctrine');
        /* @var $em \Doctrine\ORM\EntityManager */
        $em = $doctrine->getManager();

        /* @var $tab \Application\Sonata\ClientBundle\Entity\ListClientTabs */
        $tab = $em->getRepository('ApplicationSonataClientBundle:ListClientTabs')->findOneByAlias('documents');

        $em->getRepository('ApplicationSonataClientBundle:ClientAlert')
            ->createQueryBuilder('c')
            ->delete()
            ->where('c.client = :client')
            ->andWhere('c.tabs = :tab')
            ->setParameters(array(
            ':client' => $object->getClient(),
            ':tab' => $tab,
        ))->getQuery()->execute();

        $value = $object->getTypeDocument();
        if (!$value) {
            $alert = new ClientAlert();
            $alert->setClient($object->getClient());
            $alert->setTabs($tab);
            $alert->setIsBlocked(true);
            $alert->setText('Aucun document légal pour ce client');

            $em->persist($alert);
        } else {

            // ListTypeDocuments::Pouvoir => Pouvoir
            if ($value->getId() != ListTypeDocuments::Pouvoir) {
                $this->ifSaveManquePouvoirAlertMessage($em, $object, $tab);
            } elseif ($this->getCountListTypeDocumentsIfNotType($em, $object, ListTypeDocuments::Pouvoir)) {
                $this->ifSaveManquePouvoirAlertMessage($em, $object, $tab);
            }

            //ListTypeDocuments::Mandat => Mandat
            if ($value->getId() != ListTypeDocuments::Mandat) {
                $this->ifSaveManqueMandatAlertMessage($em, $object, $tab);
            } elseif ($this->getCountListTypeDocumentsIfNotType($em, $object, ListTypeDocuments::Mandat)) {
                $this->ifSaveManqueMandatAlertMessage($em, $object, $tab);
            }
        }
    }

    /**
     * @param $em \Doctrine\ORM\EntityManager
     * @param $object \Application\Sonata\ClientBundle\Entity\Document
     * @param $type_document
     * @return mixed
     */
    protected function getCountListTypeDocumentsIfNotType($em, $object, $type_document)
    {
        $dql = $em->createQueryBuilder()
            ->select('count(d.id)')
            ->from('ApplicationSonataClientBundle:Document', 'd')
            ->where('d.client = :client')
            ->andWhere('d.type_document != :type_document')
            ->setParameters(array(
            ':client' => $object->getClient(),
            ':type_document' => $em->getRepository('ApplicationSonataClientBundle:ListTypeDocuments')->findOneById($type_document),
        ));

        if ($object->getId()) {
            $dql->andWhere('d.id != :id')->setParameter(':id', $object->getId());
        }

        return $dql->getQuery()->getSingleScalarResult();
    }

    /**
     * @param $em \Doctrine\ORM\EntityManager
     * @param $object \Application\Sonata\ClientBundle\Entity\Document
     * @param $tab
     */
    protected function ifSaveManquePouvoirAlertMessage($em, $object, $tab)
    {
        /** @var $client \Application\Sonata\ClientBundle\Entity\Client */
        $client = $this->getClient();

        //ListNatureDuClients::sixE => 6e
        if ($client && $client->getNatureDuClient() && $client->getNatureDuClient()->getId() == ListNatureDuClients::sixE && !in_array($client->getPaysPostal()->getCode(), $this->getListCountryEU())) {

            $alert = new ClientAlert();
            $alert->setClient($object->getClient());
            $alert->setTabs($tab);
            $alert->setIsBlocked(true);
            $alert->setText('Manque Pouvoir');

            $em->persist($alert);
        }
    }

    /**
     * @param $em \Doctrine\ORM\EntityManager
     * @param $object \Application\Sonata\ClientBundle\Entity\Document
     * @param $tab
     */
    protected function ifSaveManqueMandatAlertMessage($em, $object, $tab)
    {
        /** @var $client \Application\Sonata\ClientBundle\Entity\Client */
        $client = $this->getClient();

        //ListNatureDuClients::sixE => 6e
        if (($client && $client->getNatureDuClient() &&
            ($client->getNatureDuClient()->getId() == ListNatureDuClients::sixE && in_array($client->getPaysPostal()->getCode(), $this->getListCountryEU())))
            ||
            ($client && ($client->getNatureDuClient()->getId() == ListNatureDuClients::DEB || $client->getNatureDuClient()->getId() == ListNatureDuClients::DES))
        ) {
            $alert = new ClientAlert();
            $alert->setClient($object->getClient());
            $alert->setTabs($tab);
            $alert->setIsBlocked(true);
            $alert->setText('Manque Mandat');
            $em->persist($alert);
        }
    }
    
    
    
    
    protected function removePasDeMandat($object) {
    	if(!$this->getClient()) return;
    	
    	
    	$doctrine = \AppKernel::getStaticContainer()->get('doctrine');
    	/* @var $em \Doctrine\ORM\EntityManager */
    	$em = $doctrine->getManager();
    	$docs = $em->getRepository('ApplicationSonataClientBundle:Document')->findBy(
    			array(
    					'type_document' => \Application\Sonata\ClientBundle\Entity\ListTypeDocuments::Mandat,
    					'client' => $this->getClient()->getId()
    	
    			)
    	);
  	
    	
    	if(count($docs) > 0) {
    		$tab = $em->getRepository('ApplicationSonataClientBundle:ListClientTabs')->findOneByAlias('general');
    		
    		$em->getRepository('ApplicationSonataClientBundle:ClientAlert')
    		->createQueryBuilder('c')
    		->delete()
    		->andWhere('c.client = :client')
    		->andWhere('c.tabs = :tab')
    		->andWhere('c.text = :text')
    		->setParameter(':client', $this->getClient())
    		->setParameter(':tab', $tab)
    		->setParameter(':text', 'Pas de Mandat')
    		->getQuery()->execute();
    		
    		
    		$tab = $em->getRepository('ApplicationSonataClientBundle:ListClientTabs')->findOneByAlias('documents');
    		
    		$em->getRepository('ApplicationSonataClientBundle:ClientAlert')
    		->createQueryBuilder('c')
    		->delete()
    		->andWhere('c.client = :client')
    		->andWhere('c.tabs = :tab')
    		->andWhere('c.text = :text')
    		->setParameter(':client', $this->getClient())
    		->setParameter(':tab', $tab)
    		->setParameter(':text', 'Manque Mandat')
    		->getQuery()->execute();
    		
    		
    	}
    }
}

