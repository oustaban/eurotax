<?php
namespace Application\Sonata\ClientOperationsBundle\Form;


use Symfony\Component\Form\AbstractType;
//use Symfony\Component\Form\FormBuilder;
use Application\Sonata\ClientOperationsBundle\Entity\Rapprochement;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\FormBuilderInterface;

class RapprochementForm extends AbstractType
{

	/**
	 
	 * @param  \Symfony\Component\Form\FormBuilder $builder
	 * @param  array $options
	 * @return void
	 */
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		
		$builder
			->add('id', 'hidden')
			->add('intro_info_id', 'choice', array(
				'label' => 'Intro',
				'choices' => Rapprochement::$intro_info_id_options,
				'expanded' => true,
				'multiple' => false
			))
			
			->add('intro_info_number', null, array('attr' => array('class' => 'input-small money')))
			->add('intro_info_number2', null, array('attr' => array('class' => 'input-small money')))
			->add('intro_info_text', null, array('attr' => array('class' => '')))
			
			
			->add('exped_info_id', 'choice', array(
					'label' => 'DEB',
					'choices' => Rapprochement::$exped_info_id_options,
					'empty_value' => '',
					'expanded' => true
			))
			->add('exped_info_number', null, array('attr' => array('class' => 'input-small money')))
			->add('exped_info_number2', null, array('attr' => array('class' => 'input-small money')))
			->add('exped_info_text', null, array('attr' => array('class' => '')))
	
		;
			
			
	}
	
	/**
	 * @param array $options
	 * @return array The default options
	 */
	public function getDefaultOptions(array $options)
	{
		return array(
			'data_class' => 'Application\Sonata\ClientOperationsBundle\Entity\Rapprochement'
		);
	}
	
	/**
	 * @return string
	 */
	public function getName()
	{
		return 'rapprochement';
	}
	
	
	
	
}