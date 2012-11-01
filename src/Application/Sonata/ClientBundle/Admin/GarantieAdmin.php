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

class GarantieAdmin extends Admin
{
    protected $_prefix_label = 'garantie';

    //create & edit form
    /**
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {

        parent::configureFormFields($formMapper);

        $id = $this->getRequest()->get($this->getIdParameter());

        $formMapper->with($this->getFieldLabel('title'))
            ->add('type_garantie', null,
            array(
                'label' => $this->getFieldLabel('type_garantie'),
                'disabled' => !!$id,
            ))
            ->add('montant', null, array('label' => $this->getFieldLabel('montant')))
            ->add('devise', null, array('label' => $this->getFieldLabel('devise')))
            ->add('nom_de_lemeteur', null, array('label' => $this->getFieldLabel('nom_de_lemeteur')))
            ->add('nom_de_la_banques_id', 'choice', array(
            'label' => ' ',
            'choices' => array(
                1 => 'a établir',
                'Nom demandé'
            )
        ))
            ->add('num_de_ganrantie', null, array('label' => $this->getFieldLabel('num_de_ganrantie')))
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
            ->add('expire', null, array(
                'label' => $this->getFieldLabel('expire'),
                'template' => 'ApplicationSonataClientBundle:CRUD:list_boolean_expire.html.twig',
            ))
        ;
    }

    /**
     * @param ErrorElement $errorElement
     * @param mixed $object
     */
    public function validate(ErrorElement $errorElement, $object)
    {
        /* @var $object \Application\Sonata\ClientBundle\Entity\Garantie */
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
        $tab = $em->getRepository('ApplicationSonataClientBundle:ListClientTabs')->findOneByAlias('garanties');

        $em->getRepository('ApplicationSonataClientBundle:ClientAlert')
            ->createQueryBuilder('c')
            ->delete()
            ->where('c.client_id = :client_id')
            ->andWhere('c.tabs = :tab')
            ->setParameters(array(
            ':client_id' => $object->getClientId(),
            ':tab' => $tab,
        ))->getQuery()->execute();

        /* @var $object \Application\Sonata\ClientBundle\Entity\Garantie */

        if ($object->getTypeGarantie()) {

            $value = $object->getTypeGarantie()->getId();
            switch ($value) {

                case 1:
                    $value = $object->getNomDeLaBanquesId();

                    if (0) {
                        $alert = new ClientAlert();
                        $alert->setClientId($object->getClientId());
                        $alert->setTabs($tab);
                        $alert->setIsBlocked(false);
                        $alert->setText('Manque Garantie Bancaire');

                        $em->persist($alert);
                    }

                    $value = $object->getDateDecheance();
                    if (0) {

                        $alert = new ClientAlert();
                        $alert->setClientId($object->getClientId());
                        $alert->setTabs($tab);
                        $alert->setIsBlocked(false);
                        $alert->setText("Date d'échéance Garantie Bancaire proche");

                        $em->persist($alert);
                    }
                    break;

                case 3:
                    $value = $object->getNomDeLaBanquesId();

                    if (0) {
                        $alert = new ClientAlert();
                        $alert->setClientId($object->getClientId());
                        $alert->setTabs($tab);
                        $alert->setIsBlocked(false);
                        $alert->setText('Manque Garantie Parentale');

                        $em->persist($alert);
                    }

                    $value = $object->getDateDecheance();
                    if (0) {

                        $alert = new ClientAlert();
                        $alert->setClientId($object->getClientId());
                        $alert->setTabs($tab);
                        $alert->setIsBlocked(false);
                        $alert->setText("Date d'échéance Garantie Parentale proche");

                        $em->persist($alert);
                    }
                    break;
            }
        }
    }
}

