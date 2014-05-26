<?php
namespace Application\Sonata\ClientOperationsBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Application\Sonata\ClientOperationsBundle\Helpers\ClientDeclaration;
use Application\Sonata\ClientOperationsBundle\Helpers\ClientDeclarationComputation;
use Application\Sonata\ClientOperationsBundle\Entity\RapprochementState;


class PopulateCreditTVAAReporterCommand extends ContainerAwareCommand {

	protected $em;
	
	protected function configure() {
		$this
		->setName('clientoperationsbundle:populate:credittva')
		->setDescription('Populate Credit TVA a Reporter');
	}
	
	protected function execute(InputInterface $input, OutputInterface $output) {
		$this->em = $this->getContainer()->get('doctrine')->getManager();
		$locks = $this->em->getRepository('ApplicationSonataClientOperationsBundle:Locking')
			->findBy(array(), array('year' => 'ASC', 'month' => 'ASC', 'client_id' => 'ASC'));
		
		$clients = $clientDeclarations = $clientDeclarationComputations = array();
		
		foreach($locks as $lock) {
			$rapState = $this->em->getRepository('ApplicationSonataClientOperationsBundle:RapprochementState')
				->findOneBy(array('client_id' => $lock->getClientId(), 'month' => $lock->getMonth(), 'year' => $lock->getYear()));
				
			
			if(!$rapState) {
				$rapState = new RapprochementState();
				$rapState->setClientId($lock->getClientId())
					->setMonth($lock->getMonth())
					->setYear($lock->getYear());
			}
			$realCreditTvaAReporter = 0;

			if(!isset($clients[$lock->getClientId()])) {
				$client = $this->em->getRepository('ApplicationSonataClientBundle:Client')
					->findOneBy(array('id' => $lock->getClientId()));
				
				if(!$client) {
					continue;
				}
				$clients[$lock->getClientId()] = $client;
			}


			if(!isset($clientDeclarations[$lock->getClientId()])) {
				$clientDeclaration = new ClientDeclaration($clients[$lock->getClientId()]);
				$clientDeclaration->setYear($lock->getYear())
				->setMonth($lock->getMonth());
					
				$clientDeclarations[$lock->getClientId()] = $clientDeclaration;
				$clientDeclarationComputations[$lock->getClientId()] = new ClientDeclarationComputation($clientDeclaration);
			}

			$realCreditTvaAReporter = $clientDeclarationComputations[$lock->getClientId()]->getCreditOfVATCarriedForward();
			$rapState->setRealCreditTvaAReporter($realCreditTvaAReporter);

			$this->em->persist($rapState);
			$this->em->flush();
		}
		
	}
	
	
}