<?php
namespace Application\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class LocationType extends AbstractType
{
    protected $_extension = '';
    static $_all_field_required = true;

    public static function setRequired($required = true)
    {
        static::$_all_field_required = $required;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $label = 'form.' . $this->getName() . '.';
        $builder
            ->add('adresse_1' . $this->_extension, 'text', array('attr' => array('class' => 'span5'), 'label' => $label . 'adresse' . $this->_extension . '_1', 'required' => static::$_all_field_required,))
            ->add('adresse_2' . $this->_extension, 'text', array('attr' => array('class' => 'span5'), 'label' => $label . 'adresse' . $this->_extension . '_2', 'required' => false,))
            ->add('code_postal' . $this->_extension, 'text', array('attr' => array('class' => 'span5'), 'label' => $label . 'CP' . $this->_extension, 'required' => static::$_all_field_required,))
            ->add('ville' . $this->_extension, 'text', array('attr' => array('class' => 'span5'), 'label' => $label . 'ville' . $this->_extension, 'required' => static::$_all_field_required,))
            ->add('pays' . $this->_extension, null, array('empty_value' => '', 'attr' => array('class' => 'span5'), 'label' => $label . 'pays' . $this->_extension, 'required' => static::$_all_field_required,));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'virtual' => true
        ));
    }

    public function getName()
    {
        return 'location';
    }
}