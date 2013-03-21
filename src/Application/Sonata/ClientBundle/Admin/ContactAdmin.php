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

    //create & edit form
    /**
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        parent::configureFormFields($formMapper);

        $formMapper->with($this->getFieldLabel('title'))
            ->add('civilite', null, array('label' => $this->getFieldLabel('civilite')))
            ->add('nom', null, array('label' => $this->getFieldLabel('nom'), 'data' => $this->getClient()->getRaisonSociale()))
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

        $listMapper
            ->add('affichage_facture_id', null, array('label' => $this->getFieldLabel('affichage_facture_id')))
            ->add('raison_sociale_societe', null, array('label' => $this->getFieldLabel('raison_sociale_societe')))
            ->add('civilite.name', null, array('label' => $this->getFieldLabel('civilite')))
            ->add('nom', null, array('label' => $this->getFieldLabel('nom')))
            ->add('prenom', null, array('label' => $this->getFieldLabel('prenom')))
            ->add('telephone_1', null, array('label' => $this->getFieldLabel('telephone_1')))
            ->add('email', null, array('label' => $this->getFieldLabel('email')))
        ;
    }


    /**
     * @param ErrorElement $errorElement
     * @param \Application\Sonata\ClientBundle\Entity\Contact $object
     */
    public function validate(ErrorElement $errorElement, $object)
    {
        /* @var $object \Application\Sonata\ClientBundle\Entity\Contact */
        parent::validate($errorElement, $object);

        $this->_setupValidate($errorElement, $object);

        $this->_setupAlerts($errorElement, $object);
    }

    /**
     * @param ErrorElement $errorElement
     * @param \Application\Sonata\ClientBundle\Entity\Contact $object
     */
    protected function _setupValidate(ErrorElement $errorElement, $object)
    {
        /** http://redmine.testenm.com/issues/1378  */

        $value = $object->getAffichageFactureId();
        if ($value) {

            /** @var $doctrine  \Doctrine\Bundle\DoctrineBundle\Registry */
            $doctrine = $this->getConfigurationPool()->getContainer()->get('doctrine');

            /* @var $em \Doctrine\ORM\EntityManager */
            $em = $doctrine->getManager();

            /** @var $dql \Doctrine\ORM\QueryBuilder */
            $dql = $em->createQueryBuilder()
                ->select('count(c.id)')
                ->from('ApplicationSonataClientBundle:Contact', 'c')
                ->where('c.client = :client')
                ->andWhere('c.affichage_facture_id = :affichage_facture_id')
                ->setParameter(':client', $object->getClient())
                ->setParameter(':affichage_facture_id', $value);

            // Edit
            if ($object->getId()) {
                $dql->andWhere('c.id != :id')->setParameter(':id', $object->getId());
            }

            $count = $dql->getQuery()->getSingleScalarResult();

            if ($count) {
                $errorElement->with('affichage_facture_id')->addViolation('Un contact a déjà ce N° ordre facturation')->end();
            }
        }
    }

    /**
     * @param ErrorElement $errorElement
     * @param \Application\Sonata\ClientBundle\Entity\Contact $object
     */
    protected function _setupAlerts(ErrorElement $errorElement, $object)
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
            ->where('c.client = :client')
            ->andWhere('c.tabs = :tab')
            ->setParameters(array(
            ':client' => $object->getClient(),
            ':tab' => $tab,
        ))->getQuery()->execute();


        $value = $object->getAffichageFactureId();
        if (!$value) {
            $this->saveAffichageFactureAlertMessage($em, $object, $tab);
        } else {

            $dql = $em->createQueryBuilder()
                ->select('count(c.id)')
                ->from('ApplicationSonataClientBundle:Contact', 'c')
                ->where('c.client = :client')
                ->andWhere('c.affichage_facture_id IS NULL')
                ->setParameter(':client', $object->getClient());

            if ($object->getId()) {
                $dql->andWhere('c.id != :id')->setParameter(':id', $object->getId());
            }

            $count = $dql->getQuery()->getSingleScalarResult();

            if ($count) {
                $this->saveAffichageFactureAlertMessage($em, $object, $tab);
            }
        }
    }

    /**
     * @param \Doctrine\ORM\EntityManager $em
     * @param $object \Application\Sonata\ClientBundle\Entity\Contact
     * @param $tab
     */
    protected function saveAffichageFactureAlertMessage(\Doctrine\ORM\EntityManager $em, $object, $tab)
    {
        $alert = new ClientAlert();
        $alert->setClient($object->getClient());
        $alert->setTabs($tab);
        $alert->setIsBlocked(true);
        $alert->setText('Aucun contact pour Facturation');

        $em->persist($alert);
    }
}