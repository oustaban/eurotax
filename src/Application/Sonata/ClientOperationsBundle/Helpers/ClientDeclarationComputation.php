<?php
namespace Application\Sonata\ClientOperationsBundle\Helpers;


use Application\Sonata\ClientOperationsBundle\Helpers\ClientDeclaration;


class ClientDeclarationComputation {
	
	protected $currentClientDeclatraion,
		$previousMonthClientDeclaration;
	
	
	public function __construct(ClientDeclaration $clientDeclaration) {
		$this->currentClientDeclatraion = $clientDeclaration;
		$this->previousMonthClientDeclaration = $clientDeclaration->getPreviousMonth();
	}
	
	public function getSoldeTVATotal() {
		return round($this->currentClientDeclatraion->getRealSoldeTVATotal()  + $this->getCreditOfVATFromPreviousPeriod());
	}
	
	public function getCreditOfVATCarriedForward() {
		return round($this->getSoldeTVATotal()	+ $this->currentClientDeclatraion->getRapprochementState()->getDemandeDeRemboursement());
	}
	
	
	public function getAbsCreditOfVATFromPreviousPeriod() {
		return abs($this->getCreditOfVATFromPreviousPeriod());
	}	
	
	public function getCreditOfVATFromPreviousPeriod() {
		return round(($this->previousMonthClientDeclaration->getRealSoldeTVATotal() + $this->previousMonthClientDeclaration->getPreviousMonth()->getCreditOfVATCarriedForward())
			+ $this->previousMonthClientDeclaration->getRapprochementState()->getDemandeDeRemboursement());
	}
	
	public function getTotalInputTVA() {
		return round($this->currentClientDeclatraion->getTotalInputTVA() + $this->getAbsCreditOfVATFromPreviousPeriod());
	}

	
	
}