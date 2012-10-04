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

use Application\Sonata\ClientBundle\Admin\AbstractTabsAdmin as Admin;

class ContactAdmin extends Admin
{
    protected $_fields_list = array(
        'nom',
        'prenom',
    );

    //create & edit form
    /**
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        parent::configureFormFields($formMapper);

        $formMapper->with($this->getFieldLabel('title'))
            ->add('civilite', null, array('label' => $this->getFieldLabel('civilite')))
            ->add('nom', null, array('label' => $this->getFieldLabel('nom')))
            ->add('prenom', null, array('label' => $this->getFieldLabel('prenom')))
            ->add('telephone_1', null, array('label' => $this->getFieldLabel('telephone_1')))
            ->add('telephone_2', null, array('label' => $this->getFieldLabel('telephone_2')))
            ->add('fax', null, array('label' => $this->getFieldLabel('fax')))
            ->add('email', 'email', array('label' => $this->getFieldLabel('email')))
            ->add('raison_sociale_societe', null, array('label' => $this->getFieldLabel('raison_sociale_societe')))
            ->add('affichage_facture_id', 'choice', array(
            'label' => $this->getFieldLabel('affichage_facture_id'),
            'empty_value' => '',
            'required' => false,
            'choices' => array(
                1 => 1,
                2 => 2
            ),
        ));
    }

    //list
    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        parent::configureListFields($listMapper);

        $listMapper->addIdentifier('id', null);

        foreach ($this->_fields_list as $field) {
            $listMapper->add($field, null, array('label' => $this->getFieldLabel($field)));
        }
    }


    /**
     * @param ErrorElement $errorElement
     * @param mixed $object
     */
    public function validate(ErrorElement $errorElement, $object)
    {
        /* @var $object \Application\Sonata\ClientBundle\Entity\Contact */
        parent::validate($errorElement, $object);

        $this->_setupAlerts($errorElement, $object);
    }

    /**
     * @param $errorElement
     * @param $object
     */
    protected function _setupAlerts($errorElement, $object)
    {
        /** @var $doctrine  \Doctrine\Bundle\DoctrineBundle\Registry */
        $doctrine = $this->getConfigurationPool()->getContainer()->get('doctrine');

        /* @var $em \Doctrine\ORM\EntityManager */
        $em = $doctrine->getManager();

        /* @var $tab \Application\Sonata\ClientBundle\Entity\ListClientTabs */
        $tab = $em->getRepository('ApplicationSonataClientBundle:ListClientTabs')->findOneByAlias('contacts');

        $em->getRepository('ApplicationSonataClientBundle:ClientAlert')
            ->createQueryBuilder('c')
            ->delete()
            ->where('c.client_id = :client_id')
            ->andWhere('c.tabs = :tab')
            ->setParameters(array(
            ':client_id' => $object->getClientId(),
            ':tab' => $tab,
        ))->getQuery()->execute();


        $value = $object->getAffichageFactureId();
        if ($value == 1 || $value == 2) {
            $alert = new ClientAlert();
            $alert->setClientId($object->getClientId());
            $alert->setTabs($tab);
            $alert->setIsBlocked(true);
            $alert->setText('Aucun contact pour Facturation');

            $em->persist($alert);
        }
    }
}