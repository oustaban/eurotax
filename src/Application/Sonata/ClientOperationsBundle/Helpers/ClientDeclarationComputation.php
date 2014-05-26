<?php
namespace Application\Sonata\ClientOperationsBundle\Helpers;


use Application\Sonata\ClientOperationsBundle\Helpers\ClientDeclaration;


class ClientDeclarationComputation {
	
	protected $currentClientDeclaration,
		$previousMonthClientDeclaration;
	
	
	public function __construct(ClientDeclaration $clientDeclaration) {
		$this->currentClientDeclaration = $clientDeclaration;
		$this->previousMonthClientDeclaration = $clientDeclaration->getPreviousMonth();
	}
	
	public function getSoldeTVATotal() {
		return round($this->currentClientDeclaration->getRealSoldeTVATotal()  + $this->getCreditOfVATFromPreviousPeriod());
	}
	
	public function getCreditOfVATCarriedForward() {
		return round($this->getSoldeTVATotal()	+ $this->currentClientDeclaration->getRapprochementState()->getDemandeDeRemboursement());
	}
	
	
	public function getAbsCreditOfVATFromPreviousPeriod() {
		return abs($this->getCreditOfVATFromPreviousPeriod());
	}	
	
	public function getCreditOfVATFromPreviousPeriod() {
		return $this->previousMonthClientDeclaration->getRapprochementState()->getRealCreditTvaAReporter();
	}
	
	public function getTotalInputTVA() {
		return round($this->currentClientDeclaration->getTotalInputTVA() + $this->getAbsCreditOfVATFromPreviousPeriod());
	}

	
	
}