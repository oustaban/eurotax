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
            ->add('client', null, array('data' => $this->getClient()))
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
}

