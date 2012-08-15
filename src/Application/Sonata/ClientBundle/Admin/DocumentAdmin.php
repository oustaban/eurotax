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

class DocumentAdmin extends Admin
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

    //filter form
    /**
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper->add('client_id');
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
     * {@inheritdoc}
     */
    public function generateUrl($name, array $parameters = array(), $absolute = false)
    {
        switch ($name) {
            case 'list':
                $name = 'create';
            case 'create':
            case 'edit':
            case 'delete':
            case 'batch':
                $filter = Request::createFromGlobals()->query->get('filter');
                $parameters['filter']['client_id']['value'] = $filter['client_id']['value'];
                break;
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
            case 'list':
                return 'ApplicationSonataClientBundle:CRUD:list.html.twig';
        }
        return parent::getTemplate($name);
    }

    /**
     * @param $document
     */
    public function saveFile($document)
    {
        $basepath = $this->getRequest()->getBasePath();
        $document->setBasePath($basepath);
        $document->upload();
    }

    /**
     * @param mixed $document
     * @return mixed|void
     */
    public function prePersist($document)
    {
        $this->saveFile($document);
    }

    /**
     * @param mixed $document
     * @return mixed|void
     */
    public function preUpdate($document)
    {
        $this->saveFile($document);
    }

    #TODO
    /**
     * @param mixed $document
     * @return mixed|void
     */
    public function postRemove($document)
    {
        $basepath = $this->getRequest()->getBasePath();
        $document->setBasePath($basepath);
        $document->removeUpload();
    }
}

