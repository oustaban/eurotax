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
use Doctrine\ORM\EntityRepository;

class TarifAdmin extends Admin
{
    protected $_prefix_label = 'tarif';

    /**
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        parent::configureFormFields($formMapper);

        $formMapper->with($this->getFieldLabel('title'))
            ->add('mode_de_facturation', null, array(
            'label' => $this->getFieldLabel('mode_de_facturation'),
            'empty_value' => '',
            'required' => false,
            'query_builder' => function(EntityRepository $er)
            {
                return $er->createQueryBuilder('u')
                    ->orderBy('u.name', 'ASC');
            },))
            ->add('value', 'money', array('label' => $this->getFieldLabel('value')))
            ->add('value_percentage', 'percent', array('label' => $this->getFieldLabel('value_percentage')));
    }

    //list
    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        parent::configureListFields($listMapper);

        $listMapper
            ->add('mode_de_facturation.name', null, array('label' => $this->getFieldLabel('mode_de_facturation')))
            ->add('value', 'money', array('label' => $this->getFieldLabel('value')))
            ->add('value_percentage', 'percent', array('label' => $this->getFieldLabel('value_percentage')))
            ->add('mode_de_facturation.invoice_type.name', null, array('label' => $this->getFieldLabel('invoice_type')));
    }

    /**
     * @param ErrorElement $errorElement
     * @param mixed $object
     */
    public function validate(ErrorElement $errorElement, $object)
    {
        /* @var $object \Application\Sonata\ClientBundle\Entity\Tarif */
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
        $tab = $em->getRepository('ApplicationSonataClientBundle:ListClientTabs')->findOneByAlias('tarif');

        $em->getRepository('ApplicationSonataClientBundle:ClientAlert')
            ->createQueryBuilder('c')
            ->delete()
            ->where('c.client = :client')
            ->andWhere('c.tabs = :tab')
            ->setParameters(array(
            ':client' => $object->getClient(),
            ':tab' => $tab,
        ))->getQuery()->execute();


        /* @var $object \Application\Sonata\ClientBundle\Entity\Tarif */
        $value = $object->getValuePercentage();
        $value2 = $object->getValue();

        if (empty($value) && empty($value2)) {
            $alert = new ClientAlert();
            $alert->setClient($object->getClient());
            $alert->setTabs($tab);
            $alert->setIsBlocked(true);
            $alert->setText('Aucun tarif sélectionné');

            $em->persist($alert);
        }
    }
}

