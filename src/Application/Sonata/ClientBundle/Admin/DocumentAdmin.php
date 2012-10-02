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

class DocumentAdmin extends Admin
{
    //create & edit form
    /**
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $filter = $this->getRequest()->query->get('filter');

        $formMapper->with('form.document.title')
            ->add('client_id', 'hidden', array('data' => $filter['client_id']['value']))
            ->add('file', 'file', array('label' => 'form.document.document', 'required' => false))
            ->add('type_document', null, array('label' => 'form.document.type_document'))
            ->add('date_document', null, array(
            'label' => 'form.document.date_document',
            'attr' => array('class' => 'datepicker'),
            'widget' => 'single_text',
            'input' => 'datetime',
            'format' => $this->date_format_datetime
        ))
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

        $listMapper->add('document', null, array(
            'label' => 'list.document.document',
            'template' => 'ApplicationSonataClientBundle:CRUD:document_link.html.twig'
        ));
        $listMapper->add('date_document', null, array(
            'label' => 'list.document.date_document',
            'template' => 'ApplicationSonataClientBundle:CRUD:list_date_document.html.twig'
        ));
        $listMapper->add('date_notaire', null, array('label' => 'list.document.date_notaire'));
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

    /**
     * @param mixed $document
     * @return mixed|void
     */
    public function postRemove($document)
    {
        $document->removeUpload();
    }

    /**
     * @param ErrorElement $errorElement
     * @param mixed $object
     */
    public function validate(ErrorElement $errorElement, $object)
    {
        /* @var $object \Application\Sonata\ClientBundle\Entity\Client */
        parent::validate($errorElement, $object);

        $this->_setupAlerts($errorElement, $object);
    }

    /**
     * @param ErrorElement $errorElement
     * @param mixed $object
     */
    protected function _setupAlerts(ErrorElement $errorElement, $object)
    {
        /* @var $object \Application\Sonata\ClientBundle\Entity\Document */

        /* @var $doctrine \Doctrine\Bundle\DoctrineBundle\Registry */
        $doctrine = \AppKernel::getStaticContainer()->get('doctrine');
        /* @var $em \Doctrine\ORM\EntityManager */
        $em = $doctrine->getManager();

        /* @var $tab \Application\Sonata\ClientBundle\Entity\ListClientTabs */
        $tab = $em->getRepository('ApplicationSonataClientBundle:ListClientTabs')->findOneByAlias('documents');

        $em->getRepository('ApplicationSonataClientBundle:ClientAlert')
            ->createQueryBuilder('c')
            ->delete()
            ->where('c.client_id = :client_id')
            ->andWhere('c.tabs = :tab')
            ->setParameters(array(
            ':client_id'=>$object->getId(),
            ':tab'=>$tab,
        ))->getQuery()->execute();

        $value = $object->getTypeDocument();
        if (!$value) {
            $alert = new ClientAlert();
            $alert->setClientId($object->getId());
            $alert->setTabs($tab);
            $alert->setIsBlocked(true);
            $alert->setText('Aucun document légal pour ce client');

            $em->persist($alert);
        }

        $value = $object->getPreavis();
        if (!$value) {
            $alert = new ClientAlert();
            $alert->setClientId($object->getId());
            $alert->setTabs($tab);
            $alert->setIsBlocked(true);
            $alert->setText('Manque Mandat');

            $em->persist($alert);
        }
    }
}

