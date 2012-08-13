<?php
namespace Application\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class LocationType extends AbstractType
{
    protected $_extension = array(
        'name' => '',
        'label' => ''
    );

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('adresse_1' . $this->_extension['name'], 'text', array('label' => 'Adresse' . $this->_extension['label'] . ' 1'))
            ->add('adresse_2' . $this->_extension['name'], 'text', array('label' => 'Adresse' . $this->_extension['label'] . ' 2'))
            ->add('code_postal' . $this->_extension['name'], 'text', array('label' => 'CP' . $this->_extension['label']))
            ->add('ville' . $this->_extension['name'], 'text', array('label' => 'Ville' . $this->_extension['label']))
            ->add('pays_id' . $this->_extension['name'], 'country', array('label' => 'Pays' . $this->_extension['label']))
            ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'virtual' => true
        ));
    }

    public function getName()
    {
        return 'location' . $this->_extension['name'];
    }
}