<?php
namespace Application\Sonata\ClientBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Application\Sonata\ClientBundle\Entity\Coordonnees;
use Doctrine\ORM\EntityRepository;


class VirementForm extends AbstractType {
	
	
	private $client_id;
	
	
	public function __construct($client_id) {
		$this->client_id = $client_id;
	}
	
	
	
	/**
	* @param  \Symfony\Component\Form\FormBuilder $builder
	* @param  array $options
	* @return void
	*/
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		
		//$this->getRequest();
		
		
		$client_id = $this->client_id;
		
	
		$builder
			->add('amount', 'money', array('required' => true, 'label' => ''))
			 ->add('coordonnees', 'entity', array('label' => '',
			 		'class' => 'Application\Sonata\ClientBundle\Entity\Coordonnees',
			 		//'property' => 'option_value',
			 		'query_builder' => function (EntityRepository $er) use($client_id) {
				return $er->createQueryBuilder('c')
					->where("c.client=$client_id")
					->orderBy('c.orders', 'ASC');
			},'empty_value' => '', 'required' => true,)) 
			
			;
	
	}
	
	
	/**
	 * @return string
	 */
	public function getName()
	{
		return 'virement';
	}
	
	
	
}