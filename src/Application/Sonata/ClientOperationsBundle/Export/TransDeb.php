<?php
namespace Application\Sonata\ClientOperationsBundle\Export;

use Symfony\Component\HttpFoundation\Response;

class TransDeb {
	
	protected $_client;
	protected $_admin;
	protected $_data;
	
	private $_exportCount = 1;
	
	/**
	 * caractere 1 to 4 : 0000
	 * Caractere 5 : 1
	 * 
	 * @param number $id
	 * @return string
	 */
	protected function col1($id = 1) {
		return str_pad($id, 5, 0, STR_PAD_LEFT);
	} 
	
	/**
	 * 7 to 12 : this is a sequence completed with 0000 on the left. Create a table for counter.. and add an index for this file that you increment each time
	 * 13 to 18 :  Begin on 000001 and incremet on each line
	 *
	 * @param unknown $index
	 * @param unknown $increment
	 * @return string
	 */
	protected function col2($index, $increment) {
		return $index. str_pad($increment, 5, 0, STR_PAD_LEFT);
	}
	
	/**
	 * 25 to 33 : SIREN number of the client ( Take the first caractere of SIRET on http://eurotax.testenm.com/sonata/client/client/5/edit )
	 */
	protected function col3() {
		return str_replace(' ', '', $this->_client->getSiret());
	}
	
	/**
	 * 39 to 40 : code of the  Département  
	 * 
	 * @param \Application\Sonata\ClientOperationsBundle\Entity\AbstractDEBEntity $row
	 * @return string
	 */
	protected function col4(\Application\Sonata\ClientOperationsBundle\Entity\AbstractDEBEntity $row) {
		return $row->getDepartement();
	}

	
	/**
	 * 41 : Mode Transport
	 * 
	 * @param \Application\Sonata\ClientOperationsBundle\Entity\AbstractDEBEntity $row
	 * @return string
	 */
	protected function col5(\Application\Sonata\ClientOperationsBundle\Entity\AbstractDEBEntity $row) {
		return $row->getModeTransport();
	}
	
	/**
	 * 43 to 44 : code of the Pays Dest. Prov.
	 * 45 to 46 : Nature transaction
	 * 47 to 57 : Valeur Fiscale  ( with 000 before to comple... no digit after ,)
	 * 58 to 61 : Conditions Livraison
	 * 62 to 63 : Régime
	 * 64 : I have to check the other one..
	 * 65 to 72 : I have to check the other one..
	 * 73 I have to check the other one..
	 * 74 à 83 : Masse Nette  ( complete with 000 before.. no digit after ,)
	 * 84 à 94 : Valeur Statistique  ( complete with 000 before.. no digit after ,)
	 * 95 à 104 : Unités Supplémentaires ( complete with 000 before.. no digit after ,)
	 *
	 * @param \Application\Sonata\ClientOperationsBundle\Entity\AbstractDEBEntity $row
	 * @return string
	 */
	protected function col6(\Application\Sonata\ClientOperationsBundle\Entity\AbstractDEBEntity $row) {
		return $row->getPaysIdDestination() . $row->getNatureTransaction() .  000 . (int) $row->getValeurFiscale() .
			$row->getConditionsLivraison() . $row->getRegime() .
			 000 . (int) $row->getMasseMette() .
			 000 . (int) $row->getValeurStatistique() .
			 000 . (int) $row->getUnitesSupplementaires();
	}
	
	
	/**
	 * 106 à 107 : code of Pays d'Origine
	 * 108 à 122 : I have to check
	 *
	 * @param \Application\Sonata\ClientOperationsBundle\Entity\AbstractDEBEntity $row
	 * @return string
	 */
	protected function col7(\Application\Sonata\ClientOperationsBundle\Entity\AbstractDEBEntity $row) {
		if($row->getPaysOrigine()) {
			return $row->getPaysIdOrigine();
		}
		return '';
	}
	
	/**
	 * 124 à 129 : MMYYYYY of the Date pièce
	 *
	 * @param \Application\Sonata\ClientOperationsBundle\Entity\AbstractDEBEntity $row
	 * @return string
	 */
	protected function col8(\Application\Sonata\ClientOperationsBundle\Entity\AbstractDEBEntity $row) {
		return $row->getDatePiece()->format('mY');
	}
		
	/**
	 * 
	 * @param number $len
	 * @return string
	 */
	private function spacer($len = 1) {
		return str_pad(' ', $len);
	}
	

	
	private function _createOrSetFileIndex() {
		$transdebPath = DOCUMENT_ROOT . '/transdeb';
		$transdebIndexPath = $transdebPath . '/index';
		
		if(!is_dir($transdebPath)) {
			mkdir($transdebPath);
		}
		
		if(!is_file($transdebIndexPath)) {
			$fp = fopen($transdebIndexPath, 'w');
			fwrite($fp, 1);
			fclose($fp);
			$this->_exportCount = 1;
		} else {
			$fp = fopen($transdebIndexPath, 'r');
			$this->_exportCount = (int) fread($fp, filesize($transdebIndexPath));
			fclose($fp);
		}
	}
	
	
	private function _incrementFileIndex() {
		$transdebPath = DOCUMENT_ROOT . '/transdeb';
		$transdebIndexPath = $transdebPath . '/index';
		$fp = fopen($transdebIndexPath, 'w');
		fwrite($fp, ++$this->_exportCount);
		fclose($fp);
	}
	
	
	
	/**
	 caractere 1 to 4 : 0000
	 Caractere 5 : 1
	 
	 Caractere 6 : white caractere
	 7 to 12 : this is a sequence completed with 0000 on the left. Create a table for counter.. and add an index for this file that you increment each time
	 13 to 18 :  Begin on 000001 and incremet on each line
	 
	 19 to 22 : white caractere
	 23 to 24 : white caractere
	  25 to 33 : SIREN number of the client ( Take the first caractere of SIRET on http://eurotax.testenm.com/sonata/client/client/5/edit )
	 
	 34 to 38 : white caractere
	 39 to 40 : code of the  Département
	 41 : Mode Transport
	
	 42 : white caractere
	 43 to 44 : code of the Pays Dest. Prov.
	 45 to 46 : Nature transaction
	 47 to 57 : Valeur Fiscale  ( with 000 before to comple... no digit after ,)
	 58 to 61 : Conditions Livraison
	 62 to 63 : Régime
	 64 : I have to check the other one..
	 65 to 72 : I have to check the other one..
	 73 I have to check the other one..
	 74 à 83 : Masse Nette  ( complete with 000 before.. no digit after ,)
	 84 à 94 : Valeur Statistique  ( complete with 000 before.. no digit after ,)
	 95 à 104 : Unités Supplémentaires ( complete with 000 before.. no digit after ,)
	
	 105 : white caractere
	 106 à 107 : code of Pays d'Origine
	 108 à 122 : I have to check
	
	 123 : white caractere
	 124 à 129 : MMYYYYY of the Date pièce
	
	 130 à 136 : white caractere
	
	 *
	 */
	private function _render() {
		$result = $this->queryResult(array('entity'=>'DEBExped'));
		$lines = array();
		$ctr = 1;
		foreach($result as $row) {
			$lines[] = $this->col1() . $this->spacer() . 
				$this->col2($this->_exportCount, $ctr) . $this->spacer(5) .
				$this->col3() . $this->spacer(4) .
				$this->col4($row) . $this->spacer() .
				$this->col5($row) . $this->spacer() .
				$this->col6($row) . $this->spacer() .
				$this->col7($row) . $this->spacer() .
				$this->col8($row) . $this->spacer(6) 
			;
			$ctr++;
		}
		
		
		$this->_data = implode("\n", $lines);
		return $this->_data;
		
	}
	
	
	/**
	 * 
	 * @return string
	 */
	public function render() {
		$this->_createOrSetFileIndex();
		return $this->_render();
	}

	/**
	 * 
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function download() {
		$filename = 'transdeb-'.time() . '.txt'; // temp only
		$response = new Response(
			$this->_data,
			200,
			array(
				'Content-Type' => 'text/plain',
				'Content-Disposition' => 'attachment; filename="'.$filename.'"'
			)
		);
		$response->sendHeaders();
		$response->sendContent();
		
		
		$this->_incrementFileIndex();
		return $response;
	}
	
	
	
	
	/**
	 * @param $params
	 * @return mixed
	 */
	protected function queryResult($params)
	{
		$admin = \AppKernel::getStaticContainer()->get('application.sonata.admin.' . strtolower($params['entity']));
		$result = $admin->createQuery()
		->getQuery()
		->execute();
	
		unset($admin);
	
		return $result;
	}
	
	
	
	
	/**
	 * @param $field
	 * @param $params
	 */
	public function set($field, $params)
	{
		$this->$field = $params;
	}
	
	/**
	 * @param $field
	 * @return mixed
	 */
	protected function get($field)
	{
		return $this->$field;
	}
	
	
}