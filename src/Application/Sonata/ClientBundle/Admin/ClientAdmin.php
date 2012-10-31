<?php

namespace Application\Sonata\ClientBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\HttpFoundation\Request;

use Knp\Menu\ItemInterface as MenuItemInterface;
use Application\Form\Type\LocationPostalType;
use Application\Form\Type\LocationFacturationType;
use Sonata\AdminBundle\Validator\ErrorElement;
use Application\Sonata\ClientBundle\Entity\ClientAlert;
use Doctrine\ORM\EntityRepository;

class ClientAdmin extends Admin
{
    public $dashboards = array('Admin');
    public $date_format_datetime = 'dd/MM/yyyy';
    public $date_format_php = 'd/m/Y';

    protected $_bundle_name = 'ApplicationSonataClientBundle';
    protected $_niveau_dobligation_list = array(
        0 => 'pas de DEB In ni DEB Out',
        1 => 'DEB In et/ou DEB Out complète',
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

    /**
     * @return array
     */
    public function getBatchActions()
    {
        return array();
    }

    //create & edit form
    /**
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        LocationPostalType::setRequired();
        LocationFacturationType::setRequired();

        $id = $this->getRequest()->get($this->getIdParameter());
        $class = $id ? '' : ' hidden';

        $formMapper
            ->with('form.client.row1')
                ->add('code_client', null, array('label' => 'form.code_client', 'disabled' => true))
                ->add('autre_destinataire_de_facturation', null, array('label' => 'form.autre_destinataire_de_facturation'))
            ->with('form.client.row2')
                ->add('user', null, array('label' => 'form.user', 'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')
                    ->orderBy('u.username', 'ASC');
                },))
            ->with('form.client.row3')
                ->add('nom', null, array('label' => 'form.nom'))
            ->with('form.client.row4')
                ->add('nature_du_client', null, array('label' => 'form.nature_du_client'))
                ->add('contact', null, array('label' => 'form.contacts'))
            ->with('form.client.row5')
                ->add('raison_sociale', null, array('label' => 'form.raison_sociale'))
                ->add('raison_sociale_2', null, array('label' => 'form.raison_sociale_2'))
            ->with('form.client.row6')
                ->add('location_postal', new LocationPostalType(), array(
                    'data_class' => 'Application\Sonata\ClientBundle\Entity\Client',
                    'label' => 'Location',
                    'required' => true,
                ),
                array('type' => 'location'))
                ->add('location_facturation', new LocationFacturationType(), array(
                    'data_class' => 'Application\Sonata\ClientBundle\Entity\Client',
                    'label' => 'Location',
                    'required' => true,
                ), array('type' => 'location'))
            ->with('form.client.row7')
                ->add('N_TVA_CEE', null, array('label' => 'form.N_TVA_CEE'))
                ->add('N_TVA_CEE_facture', null, array('label' => 'form.N_TVA_CEE_facture'))
            ->with('form.client.row8')
                ->add('activite', null, array('label' => 'form.activite', 'required' => false,))
            ->with('form.client.row9')
                ->add('date_debut_mission', 'date', array(
                    'label' => 'form.date_debut_mission',
                    'attr' => array('class' => 'datepicker'),
                    'widget' => 'single_text',
                    'input' => 'datetime',
                    'format' => $this->date_format_datetime
                ))
                ->add('date_fin_mission', 'date', array(
                    'label' => 'form.date_fin_mission',
                    'attr' => array('class' => 'datepicker' . $class),
                    'widget' => 'single_text',
                    'input' => 'datetime',
                    'format' => $this->date_format_datetime,
                    'empty_value' => '',
                    'required' => false,
                ))
            ->with('form.client.row10')
                ->add('mode_denregistrement', null, array('label' => 'form.mode_denregistrement'))
            ->with('form.client.row11')
                ->add('siret', null, array('label' => 'form.siret', 'required' => false,))
            ->with('form.client.row12')
                ->add('periodicite_facturation', null, array('label' => 'form.periodicite_facturation'))
            ->with('form.client.row13')
                ->add('num_dossier_fiscal', null, array('label' => 'form.num_dossier_fiscal', 'required' => false,))
            ->with('form.client.row14')
                ->add('taxe_additionnelle', 'choice',
                    array('expanded' => true,
                        'label' => 'form.taxe_additionnelle',
                        'choices' => array(1 => 'Oui', 0 => 'Non'),
                        'required' => false,
                ))
            ->with('form.client.row15')
                ->add('periodicite_CA3', null, array('label' => 'form.periodicite_CA3', 'empty_value' => '', 'required' => false,))
                ->add('date_de_depot_id', 'choice', array(
                    'label' => 'form.date_de_depot_id',
                    'choices' => array(15=>15, 19=>19, 24=>24, 31=>31),
                    'attr' => array('class' => 'date_de_depot_id'),
                ))
            ->with('form.client.row16')
                ->add('center_des_impots', null, array('label' => 'form.center_des_impots'))
                ->add('teledeclaration', null, array('label' => 'form.teledeclaration'))
            ->with('form.client.row17')
                ->add('language', null, array('label' => 'form.language'))
                ->add('niveau_dobligation_id', 'choice', array(
                    'label' => 'form.niveau_dobligation_id',
                    'choices' => $this->getNiveauDobligationIdChoise(),
                    'empty_value' => '',
                    'required' => false,
                    'help' => ' ',
                ))
            ;
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
        $listMapper->addIdentifier('code_client')
            ->add('raison_sociale', null, array('label' => 'list.raison_sociale'))
            ->add('nature_du_client.name', null, array('label' => 'list.nature_du_client'))
            ->add('user', null, array('label' => 'list.user'))
            ->add('center_des_impots.nom', null, array('label' => 'list.center_des_impots'))
            ->add('date_de_depot_id', null, array('label' => 'list.date_de_depot_id'))
            ->add('teledeclaration', null, array('label' => 'list.teledeclaration'))
            ->add('mois_tva', null, array('label' => 'list.mois_tva'))
            ->add('BAPSA', null, array('label' => 'list.BAPSA'))
            ->add('solde_du_compte', null, array('label' => 'list.solde_du_compte'))
            ->add('remboursement_de_TVA', null, array('label' => 'list.remboursement_de_TVA'))
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
     * @param mixed $object
     */
    public function validate(ErrorElement $errorElement, $object)
    {
        /* @var $object \Application\Sonata\ClientBundle\Entity\Client */
        parent::validate($errorElement, $object);
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
    public function postRemove($object)
    {
        /* @var $object \Application\Sonata\ClientBundle\Entity\Client */
        $this->_setupAlerts($object);
    }

    /**
     * @param mixed $object
     */
    protected function _setupAlerts($object)
    {
        /* @var $object \Application\Sonata\ClientBundle\Entity\Client */

        /* @var $doctrine \Doctrine\Bundle\DoctrineBundle\Registry */
        $doctrine = \AppKernel::getStaticContainer()->get('doctrine');
        /* @var $em \Doctrine\ORM\EntityManager */
        $em = $doctrine->getManager();

        /* @var $tab \Application\Sonata\ClientBundle\Entity\ListClientTabs */
        $tab = $em->getRepository('ApplicationSonataClientBundle:ListClientTabs')->findOneByAlias('general');

        $em->getRepository('ApplicationSonataClientBundle:ClientAlert')
            ->createQueryBuilder('c')
            ->delete()
            ->andWhere('c.client_id = :client_id')
            ->andWhere('c.tabs = :tab')
            ->setParameter(':client_id', $object->getId())
            ->setParameter(':tab', $tab)
            ->getQuery()->execute();

        if ($object) {

            $value = $object->getSiret();
            if (!$value) {
                $alert = new ClientAlert();
                $alert->setClientId($object->getId());
                $alert->setTabs($tab);
                $alert->setIsBlocked(true);
                $alert->setText('Manque SIRET');

                $em->persist($alert);
            }

            $value = $object->getNTVACEE();
            if (!$value) {
                $alert = new ClientAlert();
                $alert->setClientId($object->getId());
                $alert->setTabs($tab);
                $alert->setIsBlocked(true);
                $alert->setText('Manque N° TVA FR');

                $em->persist($alert);
            }

            $value = $object->getNumDossierFiscal();
            if (!$value) {
                $alert = new ClientAlert();
                $alert->setClientId($object->getId());
                $alert->setTabs($tab);
                $alert->setIsBlocked(false);
                $alert->setText('Manque Dossier fiscal');

                $em->persist($alert);
            }

            $value = $object->getNiveauDobligationId();
            if (!$value) {
                $alert = new ClientAlert();
                $alert->setClientId($object->getId());
                $alert->setTabs($tab);
                $alert->setIsBlocked(true);
                $alert->setText('Clôture Manque Niveau Obligation DEB');

                $em->persist($alert);
            }

            $value = $object->getPeriodiciteCA3();
            if (!$value) {
                $alert = new ClientAlert();
                $alert->setClientId($object->getId());
                $alert->setTabs($tab);
                $alert->setIsBlocked(true);
                $alert->setText('Clôture Manque périodicité CA3');

                $em->persist($alert);
            }

            $value = $object->getActivite();
            if (!$value) {
                $alert = new ClientAlert();
                $alert->setClientId($object->getId());
                $alert->setTabs($tab);
                $alert->setIsBlocked(false);
                $alert->setText('Manque Activité');

                $em->persist($alert);
            }

            $em->flush();
        }
    }
}