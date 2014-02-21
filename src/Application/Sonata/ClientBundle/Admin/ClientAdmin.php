<?php

namespace Application\Sonata\ClientBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\SecurityContext;

use Knp\Menu\ItemInterface as MenuItemInterface;
use Application\Form\Type\LocationPostalType;
use Application\Form\Type\LocationFacturationType;
use Sonata\AdminBundle\Validator\ErrorElement;
use Application\Sonata\ClientBundle\Entity\Client;
use Application\Sonata\ClientBundle\Entity\ClientAlert;
use Doctrine\ORM\EntityRepository;
use Application\Sonata\ClientBundle\Entity\ListCountries;
use Application\Sonata\ClientBundle\Entity\ListNatureDuClients;

class ClientAdmin extends Admin
{
    protected $maxPerPage = 100000;

    public $dashboards = array('Admin');
    public $date_format_datetime = 'dd/MM/yyyy';
    public $date_format_php = 'd/m/Y';

    protected $_bundle_name = 'ApplicationSonataClientBundle';
    protected $_niveau_dobligation_list = array(
        0 => 'pas de DEB In ni DEB Out',
        1 => 'DEB In et/ou DEB Out complète',
    	2 => 'DEB Out simplifiée (uniquement si Client-DEB)',
    	3 => 'DEB Out simplifiée (uniquement si Client-DEB)',
        4 => 'DEB Out simplifiée (uniquement si Client-DEB)',
    );

    protected $_fields_list = array(
        'raison_sociale' => array(),
        'nature_du_client' => array(),
        'date_de_depot_id' => array(),
        'date_debut_mission' => array('template' => 'ApplicationSonataClientBundle:CRUD:list_date_debut_mission.html.twig'),
        'date_fin_mission' => array('template' => 'ApplicationSonataClientBundle:CRUD:list_date_fin_mission.html.twig'),
        'user' => array(),
    );

    protected $datagridValues = array(
        '_sort_order' => 'ASC',
        '_sort_by' => 'raison_sociale'
    );

    protected $_is_validate_import = false;
    
    
    /**
     * @return array
     */
    public function getBatchActions()
    {
        return array();
    }

    
    /**
     * //create & edit form
     * 
     * On modification...  the value
     * Nom
     * Nature du client
     * Date Début Mission
     * Date fin mission
     * Mode enregistrement
     * SIRET
     * N° TVA FR
     * Num dossier fiscal (qui pourrait être renommé : N° Dossier fiscal)
     * Périodicité Ca3
     *
     * Should not be modified anymore by all admin ( put the field in grey )
     * 
     * 
     * 
     * If user is "Gestionnaire"... put in grey on modification of client ( so gestionnaire will not modify this values...)
     * le Gestionnaire*
     * "Date de fin de mission"
     * "Période de facturation"
     * "Taxe Additionnelle"
     * Centre des Impôts"
     * "Date de Dépôt"
     * "Télédéclaration"
     * "INTRO & EXPED"
     * 
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        //LocationPostalType::setRequired();
        //LocationFacturationType::setRequired(false);

        $id = $this->getRequest()->get($this->getIdParameter());
        $class = $id ? '' : ' hidden';

        $user = \AppKernel::getStaticContainer()->get('security.context')->getToken()->getUser();
        $isGestionnaire = $user->hasGroup('Gestionnaire');
        $client = $this->getNewInstance();
        
        if($id) {
        	$client = $this->getObject($id);
        }
        
        //var_dump($client->getFilesWebDir($client));
        $formMapper
        	->add('is_new', 'hidden', array('data' => $id ? 0 : 1, 'mapped' => false, 'attr' => array('class' => 'is_new')))
        	
        	
        	->add('reference_client', 'hidden') //temp only
        	
            ->with('form.client.row1')
            ->add('code_client', null, array('label' => 'form.code_client', 'disabled' => $this->getValidateImport() ? false : true))
            ->add('autre_destinataire_de_facturation', null, array('label' => 'form.autre_destinataire_de_facturation'))
            ->with('form.client.row2')
            ->add('user', null, array('label' => 'form.user', 'query_builder' => function (EntityRepository $er)
        {
            return $er->createQueryBuilder('u')
                ->orderBy('u.firstname, u.lastname', 'ASC');
        }, 'empty_value' => '', 'required' => true, 'disabled' => $isGestionnaire ? true : false))
            ->with('form.client.row3')
            ->add('nom', null, array('label' => 'form.nom', 'disabled' => $id ? true : false))
            ->with('form.client.row4')
            ->add('nature_du_client', null, array('label' => 'form.nature_du_client', 'empty_value' => '', 'required' => true, 'disabled' => $id ? true : false))
            ->add('contact', null, array('label' => 'form.contacts'))
            ->with('form.client.row5')
            ->add('raison_sociale', null, array('label' => 'form.raison_sociale'))
            ->add('raison_sociale_2', null, array('label' => 'form.raison_sociale_2'))
            ->with('form.client.row6')
            ->add('location_postal', $locPostal = new LocationPostalType(), array(
                'data_class' => 'Application\Sonata\ClientBundle\Entity\Client',
                'label' => 'Location',
                //'required' => true,
            	'pays' => array('required' => true, 'disabled' => $id ? true: false),
            ),
            array('type' => 'location'))
            ->add('location_facturation', new LocationFacturationType(), array(
            'data_class' => 'Application\Sonata\ClientBundle\Entity\Client',
            'label' => 'Location',
            
            'pays' => array('required' => false, 'disabled' => false),
            'adresse_1' => array('required' => false),
            'code_postal' => array('required' => false),
            'ville' => array('required' => false),
            		
            		
        ), array('type' => 'location'))
            ->with('form.client.row7')
            ->add('N_TVA_CEE', null, array('label' => 'form.N_TVA_CEE'))
            ->add('N_TVA_CEE_facture', null, array('label' => 'form.N_TVA_CEE_facture'))
            ->with('form.client.row8')
            ->add('activite', null, array('label' => 'form.activite', 'required' => false, 'attr' => array('style' => 'width: 670px;')))
            ->with('form.client.row9')
            ->add('date_debut_mission', 'date', array(
            'label' => 'form.date_debut_mission',
            'attr' => array('class' => 'datepicker'),
            'widget' => 'single_text',
            'input' => 'datetime',
            'format' => $this->date_format_datetime,
            'disabled' => $id ? true : false
        ))
            ->add('date_fin_mission', 'date', array(
            'label' => 'form.date_fin_mission',
            'attr' => array('class' => 'datepicker' . $class),
            'widget' => 'single_text',
            'input' => 'datetime',
            'format' => $this->date_format_datetime,
            'empty_value' => '',
            'required' => false,
            'disabled' => $client->getDateFinMission() || $isGestionnaire ? true : false
        ))
            ->with('form.client.row10')
            ->add('mode_denregistrement', null, array('label' => 'form.mode_denregistrement', 'empty_value' => '', 
            		'required' => true, 'disabled' => $id ? true : false))
            
            ->with('form.client.row11')
            ->add('siret', null, array('label' => 'form.siret', 'required' => false, 'disabled' => $client->getSiret() ? true : false))
            ->add('N_TVA_FR', null, array('label' => 'form.N_TVA_FR', 'disabled' => $client->getNTVAFR() ? true : false))
            ->with('form.client.row12')
            ->add('periodicite_facturation', null, array('label' => 'form.periodicite_facturation',  'disabled' => $isGestionnaire ? true : false))
            ->with('form.client.row13')
            ->add('num_dossier_fiscal', null, array('label' => 'form.num_dossier_fiscal', 'required' => false, 'disabled' => $client->getNumDossierFiscal() ? true : false))
            ->with('form.client.row14')
            ->add('taxe_additionnelle', 'choice',
            array('expanded' => false,
                'label' => 'form.taxe_additionnelle',
                'choices' => array(1 => 'Oui', 0 => 'Non'),
            	'empty_value' => '',
                'required' => false,
            	'disabled' => $isGestionnaire ? true : false
            ))
           
        
            ->with('form.client.row15')
            ->add('center_des_impots', null, array('label' => 'form.center_des_impots', 'query_builder' => function (EntityRepository $er)
        {
            return $er->createQueryBuilder('u')
                ->orderBy('u.nom', 'ASC');
        }, 'empty_value' => '', 'required' => true, 'disabled' => $isGestionnaire ? true : false))
        
        ->add('date_de_depot_id', 'choice', array(
        		'label' => 'form.date_de_depot_id',
        		'choices' => array(15 => 15, 19 => 19, 24 => 24, 31 => 31),
        		'attr' => array('class' => 'date_de_depot_id'), 'disabled' => $isGestionnaire ? true : false
        ))
        
        
        ->with('form.client.row16')
        ->add('periodicite_CA3', null, array('label' => 'form.periodicite_CA3', 'query_builder' => function (EntityRepository $er)
        {
        	return $er->createQueryBuilder('l')
        	->where("l.name <> 'Annuelle'")
        	->andWhere("l.name <> 'Semestrielle'")
        	->orderBy('l.id', 'ASC');
        },'empty_value' => '', 'required' => false, 'disabled' => $id ? true : false))
        
        ->add('teledeclaration', null, array('label' => 'form.teledeclaration', 'disabled' => $isGestionnaire ? true : false))
        
        
        
            ->with('form.client.row17')
            ->add('niveau_dobligation_id', 'choice', array(
            		'label' => 'Niveau Obligation INTRO',
            		'choices' => $this->getNiveauDobligationIdChoise(),
            		'empty_value' => '',
            		'required' => false,
            		'help' => ' ',
            		'disabled' => $isGestionnaire ? true : false
            ))
            ->add('niveau_dobligation_exped_id', 'choice', array(
            		'label' => 'Niveau Obligation EXPED',
            		'choices' => $this->getNiveauDobligationIdChoise(),
            		'empty_value' => '',
            		'required' => false,
            		'help' => ' ',
            		'disabled' => $isGestionnaire ? true : false
            ))
        
        ->with('form.client.row18')
        ->add('language', null, array('label' => 'form.language', 'empty_value' => '', 'required' => true))
        ;
            
            
       /*  if($id) {
           	$locPostal->pays_is_disabled = true;
        } */
    }


    /**
     * @return mixed
     */
    public function getNiveauDobligationIdListHelp()
    {
        return $this->_niveau_dobligation_list;
    }

    /**
     * @return array
     */
    private function getNiveauDobligationIdChoise()
    {
        $rows = array();
        foreach ($this->_niveau_dobligation_list as $key => $value) {
            $rows[$key] = $key;
        }
        return $rows;
    }

    /**
     * {@inheritdoc}
     */
    public function getRequest()
    {
        if (!$this->request) {
            $this->setRequest(Request::createFromGlobals());
        }

        return $this->request;
    }

    //filter form
    /**
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        foreach ($this->_fields_list as $field => $options) {
            $options['label'] = 'filter.' . $field;
            $datagridMapper->add($field, null, $options);
        }
    }

    //list
    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $fieldUserOptions = array('label' => 'list.user');
        /* @var $securityContext SecurityContext */
        $securityContext = \AppKernel::getStaticContainer()->get('security.context');
        if (!$securityContext->isGranted('ROLE_EDIT_USERS')) {
            $fieldUserOptions['template'] = 'ApplicationSonataClientBundle:CRUD:list_user_text.html.twig';
        }
        
        
        
        $now = new \DateTime();
        //$moisExtraColTitle = $now->format('m.Y');
        /*
         *
        We should have "Mois TVA (M-1).YYYY" if days <= 25, "Mois TVA (M).YYYY" if days > 25
        */
        $moisExtraColTitle = $now->format('d') > 25 ? date('m.Y', strtotime('now')) : date('m.Y', strtotime('now -1 month'));

        $listMapper
            ->add('_action', 'actions', array(
            'actions' => array(
                'operations' => array('template' => 'ApplicationSonataClientBundle:CRUD:operations_action.html.twig'),
                'edit' => array('template' => 'ApplicationSonataClientBundle:CRUD:edit_action.html.twig'),
            )
        ))
            ->add('code_client', null, array('label' => 'list.code_client', 'attar' => array('class' => 'money')))
            ->add('raison_sociale', null, array('label' => 'list.raison_sociale'))
            ->add('nature_du_client.name', null, array('label' => 'list.nature_du_client'))
            ->add('user', null, $fieldUserOptions)
            ->add('center_des_impots.nom', null, array('label' => 'list.center_des_impots'))
            ->add('date_de_depot_id', null, array('label' => 'list.date_de_depot_id'))
            ->add('teledeclaration', null, array('label' => 'list.teledeclaration'))
            ->add('mois_tva', null, array('label' => 'Mois-TVA ' . $moisExtraColTitle, 'template' => 'ApplicationSonataClientBundle:CRUD:declaration_solde_TVA_total_text.html.twig'))
            ->add('BAPSA', null, array('label' => 'list.taxes_assimilees', 'template' => 'ApplicationSonataClientBundle:CRUD:declaration_credit_TVA_reporter.html.twig'))
            ->add('remboursement_de_TVA', null, array('label' => 'list.remboursement_de_TVA', 'template' => 'ApplicationSonataClientBundle:CRUD:declaration_demande_de_remboursement.html.twig'))
            ->add('comptes', "money", array('label' => 'list.solde_du_compte', 'template' => 'ApplicationSonataClientBundle:CRUD:comptes.html.twig'))
            ->add('date_debut_mission', null, array(
            'template' => 'ApplicationSonataClientBundle:CRUD:list_date_debut_mission.html.twig',
            'label' => 'list.date_debut_mission'
        ))
            ->add('date_fin_mission', null, array(
            'template' => 'ApplicationSonataClientBundle:CRUD:list_date_fin_mission.html.twig',
            'label' => 'list.date_fin_mission'
        ));
    }

    /**
     * @return array
     */
    public function getFormTheme()
    {
        return array($this->_bundle_name . ':Form:form_admin_fields.html.twig');
    }

    /**
     * @param string $name
     *
     * @return null|string
     */
    public function getTemplate($name)
    {
        switch ($name) {
            case 'create':
            case 'edit':
                return $this->_bundle_name . ':CRUD:edit.html.twig';
            case 'list':
                return $this->_bundle_name . ':CRUD:base_list.html.twig';
        }

        return parent::getTemplate($name);
    }

    /**
     * @param string $action
     * @return array
     */
    public function getBreadcrumbs($action)
    {
        $res = parent::getBreadcrumbs($action);
        array_shift($res);
        return $res;
    }

    /**
     * @param ErrorElement $errorElement
     * @param mixed|\Application\Sonata\ClientBundle\Entity\Client $object
     */
    public function validate(ErrorElement $errorElement, $object)
    {
        /* @var $object \Application\Sonata\ClientBundle\Entity\Client */
        parent::validate($errorElement, $object);

        //EU validate NotBlank
        if (!$object->getNTVACEE() && $object->getPaysPostal() && $object->getPaysPostal()->getCode() && in_array($object->getPaysPostal()->getCode(), ListCountries::getCountryEUCode())) {
            $errorElement->with('N_TVA_CEE')->addViolation('This value should not be blank.')->end();
        }

        if (!$object->getNTVACEEFacture() && $object->getPaysFacturation() && $object->getPaysFacturation()->getCode() && in_array($object->getPaysFacturation()->getCode(), ListCountries::getCountryEUCode())) {
            $errorElement->with('N_TVA_CEE_facture')->addViolation('This value should not be blank.')->end();
        }
        
       /*  if ($object->getNTVACEEFacture() && !preg_match('/^FR \d{2} \d{3} \d{3} \d{3}$/', $object->getNTVACEEFacture())) {
        	$errorElement->with('N_TVA_CEE_facture')->addViolation('Le format du N° TVA CEE facturé est incorrect.   Respecter : FR XX XXX XXX XXX')->end();
        } */

        
        $this->validateNTVACEEFacture($errorElement, $object);
        
        if ($object->getAutreDestinataireDeFacturation()) {

            //validate NotBlank
            foreach (array(
                         'raison_sociale_2',
                     ) as $field) {

                $method = 'get' . str_replace(' ', '', ucwords(str_replace('_', ' ', $field)));
                if (method_exists($object, $method) && !$object->$method()) {
                    $errorElement->with($field)->addViolation('This value should not be blank.')->end();
                }
            }
        }

        $value = $object->getSiret();
        if (!preg_match('/^\d{3} \d{3} \d{3} \d{3} \d{2}$/', $value)) {
            $errorElement->with('siret')->addViolation('Le format du N° SIRET est incorrect.   Respecter : xxx xxx xxx xxx xx')->end();
        }
        else {
            $value = $object->getNTVAFR();
            $siret = substr($object->getSiret(), 0, 11);
            if ($value) {
                if (!preg_match('/^FR (\d{2}|ZW) '.$siret.'$/', $value)) {
                    $errorElement->with('N_TVA_FR')->addViolation('Non concordance entre le SIRET et le N° TVA FR.   Respecter FR xx (+SIREN xxx xxx xxx)')->end();
                }
            }
        }

        $value = $object->getNumDossierFiscal();
        if ($value) {
            if (!preg_match('/^\d{6}\/\d{2}$/', $value)) {
                $errorElement->with('num_dossier_fiscal')->addViolation('"Num dossier fiscal" non valide, devrait avoir le format xxxxxx/xx')->end();
            }
        }

        $value = $object->getDateDeDepot();
        if ($value && $object->getNatureDuClient()) {
            if (in_array($object->getNatureDuClient()->getId(), array(ListNatureDuClients::DEB, ListNatureDuClients::DES)) && $value!=31){
                $errorElement->with('date_de_depot_id')->addViolation('"Date de dépôt" non cohérente')->end();
            }
        }
        
        
        
        
        /**
         * If CENTRE DES IMPOTS = DRESG/6 and date <> 19 : No validation possible - Message on DATE : "CDI = DRESG, date = 19"

If CENTRE DES IMPOTS = CDI-94/7 and date <> 15 or 24 : No validation possible - Message on DATE : "CDI = CDI-94, date = 15 or 24"

If CENTRE DES IMPOTS = CISD/9 and date <> 31 : No validation possible - Message on DATE : "CDI = CISD, date = 31"

         */
        
        $centerDesImpotsID = $object->getCenterDesImpots() ? $object->getCenterDesImpots()->getId() : 0;
        $dateDepot = $object->getDateDeDepot();
        
        if($centerDesImpotsID && $dateDepot) {
        	
        	if($centerDesImpotsID == 6 && $dateDepot != 19) {
        		$errorElement->with('date_de_depot_id')->addViolation('CDI = DRESG, date = 19')->end();
        	}
        	
        	if($centerDesImpotsID == 7 && ($dateDepot != 15 && $dateDepot != 24)) {
        		$errorElement->with('date_de_depot_id')->addViolation('CDI = CDI-94, date = 15 or 24')->end();
        	}
        	
        	if($centerDesImpotsID == 9 && $dateDepot != 31) {
        		$errorElement->with('date_de_depot_id')->addViolation('CDI = CISD, date = 31')->end();
        	}
        }
        
        
        
       
    }
    
    /**
     * N_TVA_CEE_facture validation
     * 
     * @param unknown $errorElement
     * @param unknown $object
     */
    protected function validateNTVACEEFacture(ErrorElement $errorElement, $object) {
    	 
    	$validationDef = array(
			'DE' => array(9),// DE + 9 caractères
			'AT' => array(9),// AT + 9 caractères
			'BE' => array(9),// BE + 9 caractères
			'DK' => array(8),// DK + 8 caractères
			'ES' => array(9),// ES + 9 caractères ou +A/B et 8 caractères
			'A/B' => array(8),
			'FI' => array(8),// FI + 8 caractères
			'FR' => array(11),// FR + 11 caractères
			'EL' => array(9),// EL + 9 caractères
			'IE' => array(8),// IE + 8 caractères
			'IT' => array(11),// IT + 11 caractères
			'LU' => array(8),// LU + 8 caractères
			'NL' => array(12),// NL + 12 caractères
			'PT' => array(9),// PT + 9 caractères
			'GB' => array(5,9,12),// GB + 5, 9 ou 12 caractères
			'SE' => array(12),// SE + 12 caractères
			'CY' => array(9),// CY + 9 caractères
			'EE' => array(9),// EE + 9 caractères
			'HU' => array(8),// HU + 8 caractères
			'LV' => array(11),// LV + 11 caractères
			'LT' => array(9,12),// LT + 9 ou 12 caractères
			'MT' => array(8),// MT + 8 caractères
			'PL' => array(10),// PL + 10 caractères
			'CZ' => array(8,9,10),// CZ + 8, 9 ou 10 caractères
			'SK' => array(10),// SK + 10 caractères
			'SI' => array(8),// SI + 8 caractères
			'BG' => array(8),// BG + 10 caractères
			'RO' => array(8),// RO + 10 caractères
			'HR' => array(11),// HR + 11 caractères
    	);
    	 
    	$value = str_replace(' ', '', $object->getNTVACEEFacture());
    	
    	if(!$value) {
    		return;
    	}
    	
    	
    	$key = substr($value, 0, 2);
    	$trail = substr($value, 2); //trailing characters
    	 
    	// for 'A/B' key
    	if(strstr($key,'/')) {
    		$key = substr($value, 0, 3);
    		$trail = substr($value, 3); //trailing characters
    	}
    	 
    	 
    	$validated = function($key, $trail) use ($validationDef) {
    		if(!array_key_exists($key,$validationDef)) {
    			return false;
    		}
    
    		if(isset($validationDef[$key])) {
    			$lengths = $validationDef[$key];
    			if(!in_array(strlen($trail), $lengths)) {
    				return false;
    			}
    		}
    		return true;
    	};
    	 
    	 
    	if($validated($key, $trail) === false) {
    		$errorElement->with('N_TVA_CEE_facture')->addViolation('Mauvais format de TVA.')->end();
    	}
    	 
    }
    
    
    

    /**
     * {@inheritdoc}
     */
    public function postPersist($object)
    {
        /* @var $object \Application\Sonata\ClientBundle\Entity\Client */
        $this->_setupAlerts($object);
    }

    /**
     * {@inheritdoc}
     */
    public function postUpdate($object)
    {
        /* @var $object \Application\Sonata\ClientBundle\Entity\Client */
        $this->_setupAlerts($object);
    }

    /**
     * {@inheritdoc}
     */
    public function preRemove($object)
    {
        /* @var $object \Application\Sonata\ClientBundle\Entity\Client */
        $this->_setupAlerts($object);
    }

    /**
     * @param \Application\Sonata\ClientBundle\Entity\Client $object
     */
    protected function _setupAlerts($object)
    {
    	
        /* @var $doctrine \Doctrine\Bundle\DoctrineBundle\Registry */
        $doctrine = \AppKernel::getStaticContainer()->get('doctrine');
        /* @var $em \Doctrine\ORM\EntityManager */
        $em = $doctrine->getManager();

        /* @var $tab \Application\Sonata\ClientBundle\Entity\ListClientTabs */
        $tab = $em->getRepository('ApplicationSonataClientBundle:ListClientTabs')->findOneByAlias('general');

        $em->getRepository('ApplicationSonataClientBundle:ClientAlert')
            ->createQueryBuilder('c')
            ->delete()
            ->andWhere('c.client = :client')
            ->andWhere('c.tabs = :tab')
            ->setParameter(':client', $object)
            ->setParameter(':tab', $tab)
            ->getQuery()->execute();

        if ($object) {

            $value = $object->getSiret();
            if (!$value) {
                $alert = new ClientAlert();
                $alert->setClient($object);
                $alert->setTabs($tab);
                $alert->setIsBlocked(true);
                $alert->setText('Manque SIRET');

                $em->persist($alert);
            }

            $value = $object->getNTVAFR();
            if (!$value) {
                $alert = new ClientAlert();
                $alert->setClient($object);
                $alert->setTabs($tab);
                $alert->setIsBlocked(true);
                $alert->setText('Manque N° TVA FR');

                $em->persist($alert);
            }

            $value = $object->getNumDossierFiscal();
            if (!$value && $object->getNatureDuClient() && $object->getNatureDuClient()->getId() == 1) {
                $alert = new ClientAlert();
                $alert->setClient($object);
                $alert->setTabs($tab);
                $alert->setIsBlocked(false);
                $alert->setText('Manque Dossier fiscal');

                $em->persist($alert);
            }

            $value = $object->getNiveauDobligationId();
            if (is_null($value)) {
                $alert = new ClientAlert();
                $alert->setClient($object);
                $alert->setTabs($tab);
                $alert->setIsBlocked(true);
                $alert->setText('Clôture Manque Niveau Obligation DEB');

                $em->persist($alert);
            } else {
            	$alert = $em->getRepository('ApplicationSonataClientBundle:ClientAlert')->findOneBy(array('client'=>$object, 'text'=>'Clôture Manque Niveau Obligation DEB'));
            	if($alert) {
            		$this->delete($alert);
            	}
            }

            $value = $object->getPeriodiciteCA3();
            if (!$value) {
                $alert = new ClientAlert();
                $alert->setClient($object);
                $alert->setTabs($tab);
                $alert->setIsBlocked(true);
                $alert->setText('Clôture Manque périodicité CA3');

                $em->persist($alert);
            }

            $value = $object->getActivite();
            if (!$value) {
                $alert = new ClientAlert();
                $alert->setClient($object);
                $alert->setTabs($tab);
                $alert->setIsBlocked(false);
                $alert->setText('Manque Activité');

                $em->persist($alert);
            }

            $value = $object->getNumDossierFiscal();
            if ($value) {
                $explode = explode('/', $value);
                if (!isset($explode[1]) || $explode[1] == '00'){
                    $alert = new ClientAlert();
                    $alert->setClient($object);
                    $alert->setTabs($tab);
                    $alert->setIsBlocked(false);
                    $alert->setText('Numéro de dossier fiscal incomplet');

                    $em->persist($alert);
                }
            }

            /**
             * If Nature du client =  6e and Pays IN European Union
             * If no document type  = "Mandat"  create the alert   "Pas de Mandat"
             */
            if ($object->getNatureDuClient() && $object->getPaysPostal() &&
            	$object->getNatureDuClient()->getId() == ListNatureDuClients::sixE && in_array($object->getPaysPostal()->getCode(), ListCountries::getCountryEUCode())) {
            
	            	$doctrine = \AppKernel::getStaticContainer()->get('doctrine');
	            	/* @var $em \Doctrine\ORM\EntityManager */
	            	$em = $doctrine->getManager();
	            	$docs = $em->getRepository('ApplicationSonataClientBundle:Document')->findBy(
	           			array(
	         				'type_document' => \Application\Sonata\ClientBundle\Entity\ListTypeDocuments::Mandat,
	           				'client' => $object->getId()
	            			
		            	)
	            	);
	            	if(count($docs) == 0) {
	            		$alert = new ClientAlert();
	            		$alert->setClient($object);
	            		$alert->setTabs($tab);
	            		$alert->setIsBlocked(false);
	            		$alert->setText('Pas de Mandat');
	            		
	            		$em->persist($alert);
	            	}
            }
            
            
            /**
             * If Nature du client =  Client DEB
             * If no document type  = "Mandat"  create the alert   "Pas de Mandat"
             */
            if ($object->getNatureDuClient() &&  $object->getNatureDuClient()->getId() == ListNatureDuClients::DEB) {
            
            	$doctrine = \AppKernel::getStaticContainer()->get('doctrine');
            	/* @var $em \Doctrine\ORM\EntityManager */
            	$em = $doctrine->getManager();
            	$docs = $em->getRepository('ApplicationSonataClientBundle:Document')->findBy(
	           			array(
	         				'type_document' => \Application\Sonata\ClientBundle\Entity\ListTypeDocuments::Mandat,
	           				'client' => $object->getId()
	            			
		            	)
	            	);
            
            	if(count($docs) == 0) {
            		$alert = new ClientAlert();
            		$alert->setClient($object);
            		$alert->setTabs($tab);
            		$alert->setIsBlocked(false);
            		$alert->setText('Pas de Mandat');
            		
            		$em->persist($alert);
            		
            	}
            }
            
            $em->flush();
        }
    }

    public function createQuery($context = 'list')
    {
        /** @var $query \Sonata\DoctrineORMAdminBundle\Datagrid\ProxyQuery */
        $query = parent::createQuery($context);

        if ($context == 'list') {
            /** @var $builder \Doctrine\ORM\QueryBuilder */
            $builder = $query->getQueryBuilder();
            $builder
                ->andWhere('(NOT ' . $builder->getRootAlias() . '.date_fin_mission BETWEEN :date_lowest AND :date_highest) OR (' . $builder->getRootAlias() . '.date_fin_mission IS NULL)')
                ->setParameter(':date_lowest', new \DateTime('1000-01-01'))
                ->setParameter(':date_highest', new \DateTime());
            //exit($builder->getQuery()->getSQL());
        }

        return $query;
    }
    
    
    private function languages() {
    	$langArray = array();
    	$langs = $this->getModelManager()->createQuery('ApplicationSonataClientBundle:ListLanguages')->execute();
    	foreach($langs as $lang) {
    		$lang->setName('form.' . $lang->getName());
    		$langArray[$lang->getId()] = $lang;
    	}
    	return $langArray;
    }
    
    
    public function isGranted($name, $object = null)
    {
    	 
    	if($name == 'DELETE') {
    		$user = \AppKernel::getStaticContainer()->get('security.context')->getToken()->getUser();
    
    		if($user->hasGroup('Gestionnaire')) {
    			return false;
    		}
    	} 
    	return parent::isGranted($name, $object);
    }
    
    
    /**
     * @param bool $value
     */
    public function setValidateImport($value = true)
    {
    	$this->_is_validate_import = $value;
    }
    
    /**
     * @return bool
     */
    protected function getValidateImport()
    {
    	return $this->_is_validate_import;
    }
    
    /**
     * Gives access if client is closed and user is supervisuer
     *
     * @return boolean
     */
    public function hasClosedClientAccess() {
    	$id = $this->getRequest()->get($this->getIdParameter());    	
    	$user = \AppKernel::getStaticContainer()->get('security.context')->getToken()->getUser();
    	$client = $this->getObject($id);
    	
    	//$user = \AppKernel::getStaticContainer()->get('security.context')->getToken()->getUser();
    	//return $client->getDateFinMission() && $user->hasGroup('Superviseur');
    	
    	if($client->getDateFinMission()) {
    		return $user->hasGroup('Superviseur');
    	}
    	
    	
    	return true;
    }
    
    
}