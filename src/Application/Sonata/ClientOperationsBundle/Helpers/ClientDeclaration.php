<?php
namespace Application\Sonata\ClientOperationsBundle\Helpers;

use Application\Sonata\ClientBundle\Entity\Client;
use Application\Sonata\ClientOperationsBundle\Entity\RapprochementState;
use Application\Sonata\ClientBundle\Entity\ListNatureDuClients;
use Application\Sonata\ClientOperationsBundle\Entity\A02TVA;

class ClientDeclaration {
	
	private $client, $_show_all_operations = false, 
		$_year, $_month, $em, 
		$_query_month = 0;
	
	
	public function __construct(Client $client) {
		$this->client = $client;
		
		/* @var $em \Doctrine\ORM\EntityManager */
		$this->em = \AppKernel::getStaticContainer()->get('doctrine')->getManager();
	}
	
	/**
	 * 
	 * @param boolean $bool
	 */
	public function setShowAllOperations($bool = false) {
		$this->_show_all_operations = $bool;	
		return $this;
	}
	
	public function setQueryMonth($month) {
		$this->_query_month = $month;
		return $this;
	}
	
	
	public function setYear($year) {
		$this->_year = $year;
		return $this;
	}

	
	public function setMonth($month) {
		$this->_month = $month;
		return $this;
	}
	
	protected function isGrouped() {
		/*
		 * V01 = 9 lines
V03 = 6 lines
V05 = 6 lines
V07 = 3 lines
A02 = 3 lines
A04 = 11 lines
A06 = 2 lines

		 */
		
		
		$entities = array(
			'V01TVA' => $this->getEntityList('V01TVA', false),
			'V03283I' => $this->getEntityList('V03283I', false),
			'V05LIC' => $this->getEntityList('V05LIC', false),
			'V07EX' => $this->getEntityList('V07EX', false),
			'A02TVA' => $this->getEntityList('A02TVA', false),
			'A04283I' => $this->getEntityList('A04283I', false),
			'A06AIB' => $this->getEntityList('A06AIB', false),
			'A08IM' => $this->getEntityList('A08IM', false),
			'A10CAF' => $this->getEntityList('A10CAF', false),
			'V11INT' => $this->getEntityList('V11INT', false)
		);
		
		$count = 0;
		foreach($entities as $k => $entity) {
			//var_dump($k, count($entity));
			//echo '<br />';
			if(count($entity) > 3) {
				$count++;
			}
		}
		//var_dump($count);
		//exit;
		
		//It should be merged if there is at least one entity with more than 3 lines.
		if($count > 0) {
			return true;
		}
		
		
		return false;
		
		
	}
	public function getV01TVAList() {
		$V01TVAlist = $this->getEntityList('V01TVA', false);
		
		//Grouped by percentage
		if($this->isGrouped()) {
			$V01TVAlist = $this->getEntityList('V01TVA', false, true);
		}
		
		return $V01TVAlist;
	}
	
	
	public function getV03283IList() {
		$V03283Ilist = $this->getEntityList('V03283I', false);
		if($this->isGrouped()) {
			$V03283Ilist = $this->getEntityList('V03283I', false, true);
		}
		return $V03283Ilist;
	}

	public function getV05LICList() {
		$V05LIClist = $this->getEntityList('V05LIC', false);
		if($this->isGrouped()) {
			$V05LIClist = $this->getEntityList('V05LIC', false, true);
		}
		return $V05LIClist;
	}
	
	public function getV07EXList() {
		$V07EXlist = $this->getEntityList('V07EX', false);
		
		if($this->isGrouped()) {
			$V07EXlist = $this->getEntityList('V07EX', false, true);
		}
		
		return $V07EXlist;
	}
	
	public function getV11INTList() {
		$V11INTlist = $this->getEntityList('V11INT', false);
		if($this->isGrouped()) {
			$V11INTlist = $this->getEntityList('V11INT', false, true);
		}
		return $V11INTlist;
	}
	
	public function getA02TVAList() {	
		if($this->isOperationLocked()) {
			$A02TVAlist = $this->getEntityList('A02TVA', false, false);
		} else {
			$A02TVAlist = $this->getEntityList('A02TVA', false, false, 'paiement_date');
		}
		
		if($this->isGrouped()) {
			//$A02TVAlist = $this->getEntityList('A02TVA', false, true, 'paiement_date');
			//$A02TVAlist = $this->getEntityList('A02TVA', false, true);
			
			if($this->isOperationLocked()) {
				$A02TVAlist = $this->getEntityList('A02TVA', false, true);
			} else {
				$A02TVAlist = $this->getEntityList('A02TVA', false, true, 'paiement_date');
			}
				
		}
		return $A02TVAlist;
	}
	
	public function getA04283IList() {
		$A04283Ilist = $this->getEntityList('A04283I', false);
		if($this->isGrouped()) {
			$A04283Ilist = $this->getEntityList('A04283I', false, true);
		}
		return $A04283Ilist;
	}
	
	public function getA06AIBList() {
		$A06AIBlist = $this->getEntityList('A06AIB', false);
		if($this->isGrouped()) {
			$A06AIBlist = $this->getEntityList('A06AIB', false, true);
		}
		return $A06AIBlist;
	}
	
	public function getA08IMList() {
		if($this->isOperationLocked()) {
			$A08IMlist = $this->getEntityList('A08IM', false, false);
		} else {
			$A08IMlist = $this->getEntityList('A08IM', false, false, 'date_piece');
		}
		
		if($this->isGrouped()) {
			if($this->isOperationLocked()) {
				$A08IMlist = $this->getEntityList('A08IM', false, true);
			} else {
				$A08IMlist = $this->getEntityList('A08IM', false, true, 'date_piece');
			}
		}
		return $A08IMlist;
	}
	
	public function getA10CAFList() {
		$A10CAFlist = $this->getEntityList('A10CAF', false);
		if($this->isGrouped()) {
			$A10CAFlist = $this->getEntityList('A10CAF', false, true);
		}
		
		return $A10CAFlist;
		
	}
		
	
	public function getA02TVAPrevList() {
		if($this->isOperationLocked()) {
			$A02TVAPrevlist = $this->getEntityList('A02TVA', true, false, 'mois', 'mois'); // Previous month
		} else {
			$A02TVAPrevlist = $this->getEntityList('A02TVA', true, false, 'mois', 'paiement_date'); // Previous month
		}
		
		if($this->isGrouped()) {
			if($this->isOperationLocked()) {
				$A02TVAPrevlist = $this->getEntityList('A02TVA', true, true, 'mois', 'mois'); // Previous month
			} else {
				$A02TVAPrevlist = $this->getEntityList('A02TVA', true, true, 'mois', 'paiement_date'); // Previous month
			}
		}
		return $A02TVAPrevlist;
	}
	
	public function getA08IMPrevList() {
		if($this->isOperationLocked()) {
			$A08IMPrevlist = $this->getEntityList('A08IM', true, false, 'mois', 'mois'); // Previous month
		} else {
			$A08IMPrevlist = $this->getEntityList('A08IM', true, false, 'mois', 'date_piece'); // Previous month
		}
		
		if($this->isGrouped()) {
			if($this->isOperationLocked()) {
				$A08IMPrevlist = $this->getEntityList('A08IM', true, true, 'mois', 'mois'); // Previous month
			} else {
				$A08IMPrevlist = $this->getEntityList('A08IM', true, true, 'mois', 'date_piece'); // Previous month
			}
		}
		return $A08IMPrevlist;
	}
		 
	public function getA04283ISumPrev() {	
		$A04283ISumPrev = $this->_sumData($this->getEntityList('A04283I', true, false));
		
		return $A04283ISumPrev;
	}
	
	public function getA06AIBSumPrev() {
		$A06AIBSumPrev = $this->_sumData($this->getEntityList('A06AIB', true, false));
		
		return $A06AIBSumPrev;
	}
		
	public function getRulingNettTotal() {
		$rulingNettTotal = 0;
		$A04283ISumPrev = $this->getA04283ISumPrev();
		$A06AIBSumPrev = $this->getA06AIBSumPrev();
		
		if($A04283ISumPrev) {
			$rulingNettTotal += $A04283ISumPrev->getHT();
		}
		

		if($A06AIBSumPrev) {
			$rulingNettTotal += $A06AIBSumPrev->getHT();
		}
		
		return round($rulingNettTotal);
	}
	
	public function getRulingVatTotal() {
		$rulingVatTotal = 0;
		$A04283ISumPrev = $this->getA04283ISumPrev();
		$A06AIBSumPrev = $this->getA06AIBSumPrev();
	
		if($A04283ISumPrev) {
			$rulingVatTotal += $A04283ISumPrev->getTVA();
		}
		
		if($A06AIBSumPrev) {
			$rulingVatTotal += $A06AIBSumPrev->getTVA();
		}
		
		return round($rulingVatTotal);
		
	}
	
	
	/**
	 * 
	 * OUTPUT TVA
	 
	Total Nett = sum of Nett of V01, A04, A06
	Total TVA = sum of TVA of V01, A04, A06
	Total Nett ( 2nd) = sum of Nett A02, AO4 ( 2Nd), A06 (2nd)
	Total TVA (2nd ) = sum of TVA A02, AO4 ( 2Nd), A06 (2nd)
	 
	*/
	public function getTotalVat1() {
		$Total1 = $this->_sumData(array_merge($this->getV01TVAList()?:array(), $this->getA04283IList()?:array(), 
			$this->getA06AIBList()?:array()));
		return $Total1;
	}
	
	/**
	 * INPUT TVA
	 * 
	 */
	public function getTotalVat2() {
		$Total2 = $this->_sumData(
        	$Total2MergedData = array_merge($this->getA08IMList()?:array(), $this->getA02TVAPrevList()?:array(), 
        		$this->getA08IMPrevList()?:array(), $this->getA04283IList()?:array(), 
        		$this->getA06AIBList()?:array(), $this->getA02TVAList()?:array())
        );
		return $Total2;
	}
	
	/**
	 * TVA Credit
	 * 
	 * @return number
	 */
	public function getSoldeTVATotal() {
		$Total1 = $this->getTotalVat1();
		$Total2 = $this->getTotalVat2();
		
		$outputTVA = $Total1 ? $Total1->getTVA() : 0;
		$inputTVA = $Total2 ? $Total2->getTVA() : 0;
		
		$soldeTVATotal = $initialSoldeTVATotal = ($outputTVA) - ($inputTVA);
		//$soldeTVATotal = ($outputTVA) - ($inputTVA + ($initialSoldeTVATotal + $this->getRapprochementState()->getDemandeDeRemboursement()));
		
		//$value = round($this->getTVACredit()) + round($this->getRapprochementState()->getDemandeDeRemboursement());

		
		return round($soldeTVATotal);
	}
	
	public function getTotalInputHT() {
		$Total2 = $this->getTotalVat2();
		
		$inputHT = 0;
		
		if($Total2 && method_exists($Total2, 'getHT')) {
			$inputHT = $Total2->getHT();
		}
		return $inputHT;
	}
	
	public function getTotalInputTVA() {
		$Total2 = $this->getTotalVat2();
		$inputTVA = $Total2 ? $Total2->getTVA() : 0;
		return $inputTVA +  + abs($this->getPreviousMonth()->getRapprochementState()->getRealCreditTvaAReporter());
		
		//return $inputTVA;
	}
	
	
	public function getTotalOutputHT() {
		$Total1 = $this->getTotalVat1();
		$outputHT = $Total1 ? $Total1->getHT() : 0;
		return $outputHT;
	}
	
	public function getTotalOutputTVA() {
		$Total1 = $this->getTotalVat1();
		$outputTVA = $Total1 ? $Total1->getTVA() : 0;
		return $outputTVA;
	}
	
	
	
	
	
	/**
	 * Final solde total
	 */
	public function getRealSoldeTVATotal() {
		
		//var_dump($this->getTotalOutputTVA(), $this->getTotalInputTVA());
		
		$soldeTVATotal = $this->getTotalOutputTVA() - $this->getTotalInputTVA();
		
		return round($soldeTVATotal);
	}
	
	
	
	public function getSoldeTVATotalText() {
		$soldeTVATotal = $this->getSoldeTVATotal();
		
		if($this->client->getNatureDuClient()->getId() == ListNatureDuClients::sixE && $this->isOperationLocked()) {
			return $soldeTVATotal;
		}
	}
	
	public function getTVACredit() {
		return $this->getSoldeTVATotal();
	}
	
	
	public function getTotalBalance() {
		$total = $this->getTVACredit();
		if($this->client->getTaxeAdditionnelle()) {
			$total += $this->getRapprochementState()->getCreditTvaAReporter();
		}
		
		$total -= round($this->client->getCompteReelSum());
		return round($total);
	}
	
	
	
	public function getNaturalCreditToBeReportedTotal() {
		$value = round($this->getTVACredit()) + round($this->getRapprochementState()->getDemandeDeRemboursement());
		return $value;
	}
	
	/**
	 * Credit of VAT carried forward
	 * 
	 * @deprecated Use ClientDeclatrationComputation#getCreditOfVATCarriedForward
	 */
	public function getCreditOfVATCarriedForward() {
		
		
		/**
		  	VAT BALANCE = - 80 000
			French VAT claim = 50 000
			Credit of VAT = - 30 000
		 */
		return round($this->getRealSoldeTVATotal() + $this->getRapprochementState()->getDemandeDeRemboursement());
	}
	
	public function getAbsCreditOfVATCarriedForward() {
		return abs($this->getCreditOfVATCarriedForward());
	}
	

	
	public function getSoldeTVATotalPlusPreviousCreditDeTVA() {
		$total = $this->getPreviousMonth()->getSoldeTVATotal();
		$total += $this->getSoldeTVATotal();
				
		return $total;
	}
	
	
	
	public function getPreviousCreditDeTVA() {
		$value = round($this->getTVACredit()) + round($this->getRapprochementState()->getDemandeDeRemboursement());
		return $value;
	}
	
	
	public function getPreviousMonth() {
		static $instances = array();
		
		$lastMonth = new \DateTime("{$this->_year}-{$this->_month}-01 -1 month");
		$_year = $lastMonth->format('Y');
		$_month = $lastMonth->format('m');
		
		$key = sha1($this->client->getId() . $_year . $_month);
		if(!isset($instances[$key])) {
			//$instances[$key] = $this->findRappState($_year, $_month);
			$prevMonthClientDeclaration = new ClientDeclaration($this->client);
			$prevMonthClientDeclaration->setShowAllOperations($this->_show_all_operations)
				->setYear($_year)
				->setMonth($_month);
			$instances[$key] = $prevMonthClientDeclaration;
		}
		return $instances[$key];
	} 
	
	
	protected function findRappState($year, $month) {
		//$em = \AppKernel::getStaticContainer()->get('doctrine')->getManager();
		$rap = $this->em->getRepository('ApplicationSonataClientOperationsBundle:RapprochementState')
			->findOneBy(array('client_id' => $this->client->getId(), 'month' => $this->_month, 'year' => $this->_year));
		if(!$rap) {
			$rap = new RapprochementState();
		}
		return $rap;
	}
	
	
	public function getRapprochementState() {
		if($this->client->getNatureDuClient()->getId() != ListNatureDuClients::sixE && $this->isOperationLocked()) {
			return new RapprochementState();
		}
		
		//var_dump($this->client->getId());
		static $instances = array();
		$key = sha1($this->client->getId(). $this->_year . $this->_month);
		if(!isset($instances[$key])) {
			$instances[$key] = $this->findRappState($this->_year, $this->_month);
		}
		
		//var_dump($key);
		
		return $instances[$key];
	}
	
	/**
	 * 
	 * @param string $entity
	 * @param string $isPrevMonth
	 * @param boolean $mergeData
	 * @param string $monthField
	 * @param string $prevMonthField
	 * @return array|null
	 */
	public function getEntityList($entity, $isPrevMonth = false, $mergeData = false, $monthField = 'mois', $prevMonthField = 'date_piece') {
		static $results = array();
		$key = sha1($this->client->getId() . $entity . $isPrevMonth . $mergeData . $monthField . $prevMonthField . $this->_year . $this->_month);
		
		if(!isset($results[$key])) {
			/* @var $em \Doctrine\ORM\EntityManager */
			//$em = \AppKernel::getStaticContainer()->get('doctrine')->getManager();
			$qb = $this->em->createQueryBuilder();
			$q = $qb->select('v')->from("Application\Sonata\ClientOperationsBundle\Entity\\". $entity, 'v');
			$qb = $this->_listQueryFilter($qb, $isPrevMonth, $monthField, $prevMonthField);
	
			if($entity == 'V05LIC') {
				$qb->andWhere('(' . $qb->getRootAlias() . '.regime IN (21, 25, 26) OR ' . $qb->getRootAlias() . '.regime IS NULL)');
			} elseif($entity == 'A06AIB') {
				$qb->andWhere('(' . $qb->getRootAlias() . '.regime IN (11) OR ' . $qb->getRootAlias() . '.regime IS NULL)');
			}
			$results[$key] = $q->getQuery()
			->getResult()
			//->getSql()
			;
	
			/* if($entity == 'A02TVA' && !$isPrevMonth) {
				$form_month = $this->_year . '-' . $this->_month . '-01';
				$to_month = $this->_year . '-' . $this->_month . '-31';
				
			 	var_dump($form_month, $to_month, $q->getQuery()->getSql());
			 	exit;
			} */ 
	
		}

		
		if (!empty($results[$key])) {
			if($mergeData) {
				return $this->_mergeData($results[$key]);
			} else{
				return $results[$key];
			}
		}
		return null;
	}
	
	
	/**
	 * Merge entities based on percentage
	 *
	 * @param unknown $entities
	 * @return array
	 */
	private function _mergeData($entities) {
		$dataSet = array();
		$hts = array();
		$tvas = array();
		$rawEntities = array();
		 
		if(empty($entities) || !is_array($entities)) {
			return null;
		}
		 
		foreach($entities as $entity) {
	
			$key = method_exists($entity, 'getTauxDeTVA') ? $entity->getTauxDeTVA() : 0;
			if( ( ( method_exists($entity, 'getHT') && $entity->getHT() < 0 ) || ( method_exists($entity, 'getTVA') && $entity->getTVA() < 0) )
				&& (get_class($entity) != 'Application\Sonata\ClientOperationsBundle\Entity\A02TVA' && get_class($entity) != 'Application\Sonata\ClientOperationsBundle\Entity\A08IM')) {
	
				$key++;
			}
			 
			if(method_exists($entity, 'getHT')) {
				$hts[base64_encode($key)][] = $entity->getHT();
			}
			 
			if(method_exists($entity, 'getTVA')) {
				$tvas[base64_encode($key)][] = $entity->getTVA();
			}
			$rawEntities[base64_encode($key)] = $entity;
		}
		 
		foreach($rawEntities as $k => $entity) {
			$ht = 0;
			$tva = 0;
			if(isset($hts[$k])) {
				foreach ($hts[$k] as $v) {
					$ht+=$v;
				}
			}
	
			if(isset($tvas[$k])) {
				foreach ($tvas[$k] as $v) {
					$tva+=$v;
				}
			}
	
			$cEntity = clone $entity;
	
			if(method_exists($cEntity, 'setTVA')) {
				$cEntity->setTVA($tva);
			}
			if(method_exists($cEntity, 'setHT')) {
				$cEntity->setHT($ht);
			}
	
	
			$dataSet[] = $cEntity;
		}
		 
		return $dataSet;
		 
	}
	
	/**
	 * Sets total values for Nett = HT (€) and VAT = TVA (€)
	 *
	 * @param unknown $entities
	 * @return NULL|unknown
	 */
	private function _sumData($entities) {
		$ht = 0;
		$tva = 0;
	
		if(empty($entities)) {
			return null;
		}
		 
		foreach($entities as $entity) {
			if(method_exists($entity, 'getHT')) {
				if(get_class($entity) != 'Application\Sonata\ClientOperationsBundle\Entity\A02TVA') {
					$ht += ($entity->getHT());
				}
			}
			if(method_exists($entity, 'getTVA')) {
				$tva += ($entity->getTVA());
			}
		}
		 
		$cEntity = clone $entity;
	
		if(method_exists($cEntity, 'setTVA')) {
			$cEntity->setTVA($tva);
		}
		if(method_exists($cEntity, 'setHT')) {
			$cEntity->setHT($ht);
		}
	
		return $cEntity;
	}
	
	private function _listQueryFilter(\Doctrine\ORM\QueryBuilder $qb, $isPrevMonth = false, $monthField = 'mois', $prevMonthField = 'date_piece') {
		
		if (!$this->_show_all_operations){
			$form_month = $this->_year . '-' . $this->_month . '-01';
			$to_month = $this->_year . '-' . $this->_month . '-31';
	
			//var_dump($this->_query_month);
			
			/* if ($this->_query_month == -1) {
				$qb->orWhere($qb->getRootAlias() . '.'.$monthField.' IS NULL');
			    $qb->orWhere($qb->getRootAlias() . '.'.$monthField.' BETWEEN :form_month AND :to_month');
			} else { */
				$qb->andWhere($qb->getRootAlias() . '.'.$monthField.' BETWEEN :form_month AND :to_month');
			//}  		
	
			if($isPrevMonth) {
				$lastMonth = new \DateTime($form_month);
				$lastMonth->sub(\DateInterval::createFromDateString('1 month'));
	
				$dp_to_month = $lastMonth->format('Y-m') . '-31';
				//$dp_form_month = $lastMonth->format('Y-m') . '-01';
				$dp_form_month = '2000-12-01';
				 
				$qb->andWhere($qb->getRootAlias() . '.'. $prevMonthField .' BETWEEN :dp_form_month AND :dp_to_month');
				 
				$qb->setParameter(':dp_form_month', $dp_form_month);
				$qb->setParameter(':dp_to_month', $dp_to_month);
				//var_dump($form_month, $to_month, $dp_form_month, $dp_to_month);
			}
	
			$qb->setParameter(':form_month', $form_month);
			$qb->setParameter(':to_month', $to_month);			 
		}
	
		$qb->andWhere($qb->getRootAlias() . '.client_id=' . $this->client->getId())
			//->orderBy($qb->getRootAlias() .'.TVA')
			;
			 
		return $qb;
	}
	
	
	private function isOperationLocked() {
		$this->_locking = $this->em->getRepository('ApplicationSonataClientOperationsBundle:Locking')
			->findOneBy(array('client_id' => $this->client->getId(), 'month' => $this->_month, 'year' => $this->_year));
		
		return $this->_locking ? true : false;
	}
	
	
	
}

