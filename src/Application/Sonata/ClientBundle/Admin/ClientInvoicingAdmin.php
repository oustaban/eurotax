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

class ClientInvoicingAdmin extends Admin
{
    protected $_prefix_label = 'clientinvoicing';

    /**
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $filter = $_GET['filter'];

        $label = 'form.' . $this->_prefix_label . '.';
        $formMapper->with($label . 'title')
            //->add('client', null, array('data' => $filter['client_id']['value']))
            ->add('facturation_du_client', null, array('label' => $label . 'facturation_du_client'))
            ->add('min', null, array('label' => $label . 'min'))
            ->add('max', null, array('label' => $label . 'max'))
            ->add('facturation_davance_value', null, array('label' => $label . 'facturation_davance_value'))
            ->add('facturation_davance', null, array('label' => 'facturation_davance'))
            ->add('paiement', null, array('label' => $label . 'paiement'))
            ->add('libelle', null, array('label' => $label . 'libelle'));
    }

    /**
     * @param string $name
     * @param array $parameters
     * @param bool $absolute
     * @return string
     */
    public function generateUrl($name, array $parameters = array(), $absolute = false)
    {
        $this->_generate_url = false;
        $parameters['filter']['client_id']['value'] = $this->client_id;

        if ($name == 'list') {

            return $this->getLinkTarif();
        }

        return parent::generateUrl($name, $parameters, $absolute);
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
                return $this->_bundle_name . ':CRUD:edit_client_invoicing.html.twig';
        }

        return parent::getTemplate($name);
    }


    public function getLinkTarif()
    {

        return '/sonata/client/tarif/list?filter[client_id][value]=' . $this->client_id;
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
     * @param $object \Application\Sonata\ClientBundle\Entity\ClientInvoicing
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

        $value = false;
        if ($value) {
            $alert = new ClientAlert();
            $alert->setClient($object->getClient());
            $alert->setTabs($tab);
            $alert->setIsBlocked(true);
            $alert->setText('Manque Libéllé avance');

            $em->persist($alert);
        }
    }
}

