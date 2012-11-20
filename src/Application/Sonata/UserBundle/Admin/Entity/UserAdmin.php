<?php
namespace Application\Sonata\UserBundle\Admin\Entity;

use Sonata\UserBundle\Admin\Entity\UserAdmin as BaseUserAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\AdminBundle\Validator\ErrorElement;
use Application\Sonata\UserBundle\Admin\Validate\ErrorElements;

use FOS\UserBundle\Model\UserManagerInterface;

class UserAdmin extends BaseUserAdmin
{
    public $dashboards = array('Admin');

    protected $datagridValues = array(
        '_sort_order' => 'ASC',
        '_sort_by' => 'username'
    );


    /**
     * {@inheritdoc}
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('username')
            ->add('firstname', null, array('label' => 'form.label_firstname'))
            ->add('lastname', null, array('label' => 'form.label_lastname'))
            ->add('email')
            ->add('groups')
            ->add('createdAt', 'date', array(
            'template' => 'ApplicationSonataUserBundle:CRUD:list_created_at.html.twig',
        ))
            ->add('enabled');

//        if ($this->isGranted('ROLE_ALLOWED_TO_SWITCH')) {
//            $listMapper
//                ->add('impersonating', 'string', array('template' => 'SonataUserBundle:Admin:Field/impersonating.html.twig'))
//            ;
//        }
    }

    /**
     * {@inheritdoc}
     */
    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->with('General')
            ->add('username')
            ->add('email')
            ->add('firstname')
            ->add('lastname')
            ->add('groups')
            ->end();
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->with('Profile')
            ->add('username')
            ->add('email')
            ->add('plainPassword', 'text', array('required' => false))
            ->add('firstname', null, array('required' => false))
            ->add('lastname', null, array('required' => false))
            ->add('phone', null, array('required' => false))
            ->add('groups', 'sonata_type_model', array('required' => false, 'expanded' => true, 'multiple' => true))
            ->add('enabled', null, array('required' => false))
            ->end();
    }

    /**
     * {@inheritdoc}
     */
    public function preUpdate($user)
    {
        $this->getUserManager()->updateCanonicalFields($user);
        $this->getUserManager()->updatePassword($user);
    }

    /**
     * @param UserManagerInterface $userManager
     */
    public function setUserManager(UserManagerInterface $userManager)
    {
        $this->userManager = $userManager;
    }

    /**
     * @return UserManagerInterface
     */
    public function getUserManager()
    {
        return $this->userManager;
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
     * @param string $name
     *
     * @return null|string
     */
    public function getTemplate($name)
    {
        switch ($name) {
            case 'list':
                return 'ApplicationSonataUserBundle:CRUD:list.html.twig';

            case 'create':
            case 'edit':
                return 'ApplicationSonataUserBundle:CRUD:edit.html.twig';
        }
        return parent::getTemplate($name);
    }


    /**
     * @return array
     */
    public function getFormTheme()
    {
        return array('ApplicationSonataUserBundle:Form:fields.html.twig');
    }

    /**
     * @param ErrorElement $errorElement
     * @param mixed $object
     */
    public function validate(ErrorElement $errorElement, $object)
    {
        exit('123');
        /* @var $object \Application\Sonata\UserBundle\Entity\User */
        parent::validate($errorElement, $object);

        $error = new ErrorElements($errorElement, $object);
        $error->validatePhone();
     }
}
