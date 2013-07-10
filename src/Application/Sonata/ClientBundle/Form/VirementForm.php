<?php
namespace Application\Sonata\ClientBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Application\Sonata\ClientBundle\Entity\Coordonnees;
use Doctrine\ORM\EntityRepository;

use Symfony\Component\Validator\Constraints\Min;


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
		$client_id = $this->client_id;
			
		$builder
			->add('amount', 'money', array('required' => true, 'label' => '', 'constraints' => array(new Min(array('limit' => 0)))))
			->add('coordonnees', 'entity', array('label' => '',
			 		'class' => 'Application\Sonata\ClientBundle\Entity\Coordonnees',
			 		//'property' => 'option_value',
			 		'query_builder' => function (EntityRepository $er) use($client_id) {
				return $er->createQueryBuilder('c')
					->where("c.client=$client_id")
					->orderBy('c.orders', 'ASC');
			},'empty_value' => '', 'required' => true,))

			->add('facture', 'text', array('attr' => array('placeholder'=>'Quel est le libellé du transfert ?'),   'required' => true, 'label' => 'Quel est le libellé du transfert ?'))
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