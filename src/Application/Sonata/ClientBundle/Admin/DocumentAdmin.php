<?php

namespace Application\Sonata\ClientBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Symfony\Component\Form\Extension\Core\Type\CountryType;

use Knp\Menu\ItemInterface as MenuItemInterface;
use Symfony\Component\HttpFoundation\Request;

class DocumentAdmin extends AbstractTabsAdmin
{
    protected $_fields_list = array(
        'document',
        'date_document',
        'date_notaire',
    );


    //create & edit form
    /**
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $filter = Request::createFromGlobals()->query->get('filter');

        $formMapper->with('form.document.title')
            ->add('client_id', 'hidden', array('data' => $filter['client_id']['value']))
            ->add('file', 'file', array('label' => 'form.document.document', 'required' => false))
            ->add('type_document', null, array('label' => 'form.document.type_document'))
            ->add('date_document', null, array('label' => 'form.document.date_document'))
            ->add('preavis', null, array('label' => 'form.document.preavis'))
            ->add('particularite', null, array('label' => 'form.document.particularite'))
            ->add('date_notaire', null, array('label' => 'form.document.date_notaire'))
            ->add('date_apostille', null, array('label' => 'form.document.date_apostille'));
    }

    //list
    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper->addIdentifier('id', null);

        foreach ($this->_fields_list as $field) {
            $listMapper->add($field, null, array('label' => 'list.document.' . $field));
        }
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

    #TODO
    /**
     * @param mixed $document
     * @return mixed|void
     */
    public function postRemove($document)
    {
        $document->removeUpload();
    }
}

