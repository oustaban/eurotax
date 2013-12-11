<?php
namespace Application\Sonata\ClientOperationsBundle\Helpers;

use Application\Sonata\ClientBundle\Entity\Client;
use Application\Sonata\ClientOperationsBundle\Entity\RapprochementState;
use Application\Sonata\ClientBundle\Entity\ListNatureDuClients;

class ClientDeclaration {
	

	private $client, $_show_all_operations = false, $_year, $_month;
	
	
	public function __construct(Client $client) {
		$this->client = $client;
	}
	
	/**
	 * 
	 * @param boolean $bool
	 */
	public function setShowAllOperations($bool = false) {
		$this->_show_all_operations = $bool;	
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
	
	
	public function getV01TVAList() {
		$V01TVAlist = $this->getEntityList('V01TVA', false);
		
		//Grouped by percentage
		if(count($V01TVAlist) > 3) {
			$V01TVAlist = $this->getEntityList('V01TVA', false, true);
		}
		
		return $V01TVAlist;
	}
	
	
	public function getV03283IList() {
		$V03283Ilist = $this->getEntityList('V03283I', false);
		if(count($V03283Ilist) > 3) {
			$V03283Ilist = $this->getEntityList('V03283I', false, true);
		}
		return $V03283Ilist;
	}

	public function getV05LICList() {
		$V05LIClist = $this->getEntityList('V05LIC', false);
		if(count($V05LIClist) > 3) {
			$V05LIClist = $this->getEntityList('V05LIC', false, true);
		}
		return $V05LIClist;
	}
	
	public function getV07EXList() {
		$V07EXlist = $this->getEntityList('V07EX', false);
		
		if(count($V07EXlist) > 3) {
			$V07EXlist = $this->getEntityList('V07EX', false, true);
		}
		
		return $V07EXlist;
	}
	
	public function getV11INTList() {
		$V11INTlist = $this->getEntityList('V11INT', false);
		if(count($V11INTlist) > 3) {
			$V11INTlist = $this->getEntityList('V11INT', false, true);
		}
		return $V11INTlist;
	}
	
	public function getA02TVAList() {	
		$A02TVAlist = $this->getEntityList('A02TVA', false, false, 'paiement_date');
		
		if(count($A02TVAlist) > 3) {
			$A02TVAlist = $this->getEntityList('A02TVA', false, true, 'paiement_date');
		}
		return $A02TVAlist;
	}
	
	public function getA04283IList() {
		$A04283Ilist = $this->getEntityList('A04283I', false);
		if(count($A04283Ilist) > 3) {
			$A04283Ilist = $this->getEntityList('A04283I', false, true);
		}
		return $A04283Ilist;
	}
	
	public function getA06AIBList() {
		$A06AIBlist = $this->getEntityList('A06AIB', false);
		if(count($A06AIBlist) > 3) {
			$A06AIBlist = $this->getEntityList('A06AIB', false, true);
		}
		return $A06AIBlist;
	}
	
	public function getA08IMList() {
		$A08IMlist = $this->getEntityList('A08IM', false, false, 'date_piece');
		if(count($A08IMlist) > 3) {
			$A08IMlist = $this->getEntityList('A08IM', false, true, 'date_piece');
		}
		return $A08IMlist;
	}
	
	public function getA10CAFList() {
		$A10CAFlist = $this->getEntityList('A10CAF', false);
		if(count($A10CAFlist) > 3) {
			$A10CAFlist = $this->getEntityList('A10CAF', false, true);
		}
		
		return $A10CAFlist;
		
	}
		
	
	public function getA02TVAPrevList() {
		$A02TVAPrevlist = $this->getEntityList('A02TVA', true, true, 'mois', 'paiement_date'); // Previous month
		
		return $A02TVAPrevlist;
	}
	
	public function getA08IMPrevList() {
		$A08IMPrevlist = $this->getEntityList('A08IM', true, true, 'mois', 'date_piece'); // Previous month
		
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
		
		return $rulingNettTotal;
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
		
		return $rulingVatTotal;
		
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
		$Total1 = $this->_sumData(array_merge($this->getV01TVAList()?:array(), $this->getA04283IList()?:array(), $this->getA02TVAList()?:array()));
		return $Total1;
	}
	
	/**
	 * INPUT TVA
	 * 
	 */
	public function getTotalVat2() {
		$Total2 = $this->_sumData(
        	$Total2MergedData = array_merge($this->getA08IMList()?:array(), $this->getA02TVAPrevList()?:array(), 
        		$this->getA08IMPrevList()?:array(), $this->getA04283IList()?:array(), $this->getA06AIBList()?:array(), $this->getA02TVAList()?:array())
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
		$soldeTVATotal = ($Total1?$Total1->getTVA():0) - ($Total2?$Total2->getTVA():0);
		
		return $soldeTVATotal;
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
	
	public function getCreditToBeReportedTotal() {
		$value = $this->getTVACredit() - $this->getRapprochementState()->getDemandeDeRemboursement();
		return number_format($value, 0);
	}
	
	public function getRapprochementState() {
		
		if($this->client->getNatureDuClient()->getId() != ListNatureDuClients::sixE && $this->isOperationLocked()) {
			return new RapprochementState();
		}
		
		static $instances = array();
		
		$key = $this->client->getId(). $this->_year . $this->_month;
		
		if(!isset($instances[$key])) {
			$em = \AppKernel::getStaticContainer()->get('doctrine')->getManager();
			$rap = $em->getRepository('ApplicationSonataClientOperationsBundle:RapprochementState')
				->findOneBy(array('client_id' => $this->client->getId(), 'month' => $this->_month, 'year' => $this->_year));
			
			
			if(!$rap) {
				$rap = new RapprochementState();
			}
		
			$instances[$key] = $rap;
		}
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
		$key = $entity . $isPrevMonth . $monthField . $prevMonthField;
		 
		if(!isset($results[$key])) {
			/* @var $em \Doctrine\ORM\EntityManager */
			$em = \AppKernel::getStaticContainer()->get('doctrine')->getManager();
			$qb = $em->createQueryBuilder();
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
	
			/* if($entity == 'A02TVA' && $isPrevMonth) {
			 var_dump($this->_query_month, ($this->_query_month == -1), $q->getQuery()->getSql());
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
					$ht += $entity->getHT();
				}
			}
			if(method_exists($entity, 'getTVA')) {
				$tva += $entity->getTVA();
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
	
			 
			//     		if ($this->_query_month == -1) {
			//     			$qb->orWhere($qb->getRootAlias() . '.'.$monthField.' IS NULL');
			//     			$qb->orWhere($qb->getRootAlias() . '.'.$monthField.' BETWEEN :form_month AND :to_month');
			//     		} else {
			$qb->andWhere($qb->getRootAlias() . '.'.$monthField.' BETWEEN :form_month AND :to_month');
			//     		
		
	
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
		$this->_locking = \AppKernel::getStaticContainer()->get('doctrine')->getRepository('ApplicationSonataClientOperationsBundle:Locking')
			->findOneBy(array('client_id' => $this->client->getId(), 'month' => $this->_month, 'year' => $this->_year));
		
		return $this->_locking ? true : false;
	}
	
	
	
}

