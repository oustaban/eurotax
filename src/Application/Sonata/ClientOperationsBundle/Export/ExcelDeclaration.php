<?php
namespace Application\Sonata\ClientOperationsBundle\Export;

use Application\Sonata\ClientOperationsBundle\Helpers\ClientDeclaration;

class ExcelDeclaration {
	
	protected $translator, $clientDeclaration;
	
	private $_excel, $_locking = false,
		$_admin,
		$_client,
		$_show_all_operations,
		$_year, $_month, $_file_name, $_current_row;
	
	
	
	protected $_styleBorders = array(
		'borders' => array(
			'top' => array(
				'style' => \PHPExcel_Style_Border::BORDER_THIN,
			),
			'bottom' => array(
				'style' => \PHPExcel_Style_Border::BORDER_THIN,
			),
			'left' => array(
				'style' => \PHPExcel_Style_Border::BORDER_THIN,
			),
			'right' => array(
				'style' => \PHPExcel_Style_Border::BORDER_THIN,
			),
		),
	);
	
	protected $_styleTopBorders = array(
		'borders' => array(
			'top' => array(
				'style' => \PHPExcel_Style_Border::BORDER_THIN,
			),
			'bottom' => array(
				'style' => \PHPExcel_Style_Border::BORDER_NONE,
			),
			'left' => array(
				'style' => \PHPExcel_Style_Border::BORDER_NONE,
			),
			'right' => array(
				'style' => \PHPExcel_Style_Border::BORDER_NONE,
			),
		),
	);	
	
	protected $_styleLeftBorders = array(
		'borders' => array(
			'top' => array(
				'style' => \PHPExcel_Style_Border::BORDER_NONE,
			),
			'bottom' => array(
				'style' => \PHPExcel_Style_Border::BORDER_NONE,
			),
			'left' => array(
				'style' => \PHPExcel_Style_Border::BORDER_THIN,
			),
			'right' => array(
				'style' => \PHPExcel_Style_Border::BORDER_NONE,
			),
		),
	);
	
	protected $_styleRightBorders = array(
		'borders' => array(
			'top' => array(
				'style' => \PHPExcel_Style_Border::BORDER_NONE,
			),
			'bottom' => array(
				'style' => \PHPExcel_Style_Border::BORDER_NONE,
			),
			'left' => array(
				'style' => \PHPExcel_Style_Border::BORDER_NONE,
			),
			'right' => array(
				'style' => \PHPExcel_Style_Border::BORDER_THIN,
			),
		),
	);
	
	
	
	protected $_styleTopLeftBorders = array(
		'borders' => array(
			'top' => array(
				'style' => \PHPExcel_Style_Border::BORDER_THIN,
			),
			'bottom' => array(
				'style' => \PHPExcel_Style_Border::BORDER_NONE,
			),
			'left' => array(
				'style' => \PHPExcel_Style_Border::BORDER_THIN,
			),
			'right' => array(
				'style' => \PHPExcel_Style_Border::BORDER_NONE,
			),
		),
	);
	
	protected $_styleTopRightBorders = array(
		'borders' => array(
			'top' => array(
				'style' => \PHPExcel_Style_Border::BORDER_THIN,
			),
			'bottom' => array(
				'style' => \PHPExcel_Style_Border::BORDER_NONE,
			),
			'left' => array(
				'style' => \PHPExcel_Style_Border::BORDER_NONE,
			),
			'right' => array(
				'style' => \PHPExcel_Style_Border::BORDER_THIN,
			),
		),
	);
	
	
	
	protected $_styleBottomBorders = array(
		'borders' => array(
			'top' => array(
				'style' => \PHPExcel_Style_Border::BORDER_NONE,
			),
			'bottom' => array(
				'style' => \PHPExcel_Style_Border::BORDER_THIN,
			),
			'left' => array(
				'style' => \PHPExcel_Style_Border::BORDER_NONE,
			),
			'right' => array(
				'style' => \PHPExcel_Style_Border::BORDER_NONE,
			),
		),
	);
	
	
	protected $_styleBottomLeftBorders = array(
		'borders' => array(
			'top' => array(
				'style' => \PHPExcel_Style_Border::BORDER_NONE,
			),
			'bottom' => array(
				'style' => \PHPExcel_Style_Border::BORDER_THIN,
			),
			'left' => array(
				'style' => \PHPExcel_Style_Border::BORDER_THIN,
			),
			'right' => array(
				'style' => \PHPExcel_Style_Border::BORDER_NONE,
			),
		),
	);
	
	protected $_styleBottomRightBorders = array(
		'borders' => array(
			'top' => array(
				'style' => \PHPExcel_Style_Border::BORDER_NONE,
			),
			'bottom' => array(
				'style' => \PHPExcel_Style_Border::BORDER_THIN,
			),
			'left' => array(
				'style' => \PHPExcel_Style_Border::BORDER_NONE,
			),
			'right' => array(
				'style' => \PHPExcel_Style_Border::BORDER_THIN,
			),
		),
	);
	
	
	
	protected $_styleMediumBorders = array(
		'borders' => array(
			'top' => array(
				'style' => \PHPExcel_Style_Border::BORDER_MEDIUM,
			),
			'bottom' => array(
				'style' => \PHPExcel_Style_Border::BORDER_MEDIUM,
			),
			'left' => array(
				'style' => \PHPExcel_Style_Border::BORDER_MEDIUM,
			),
			'right' => array(
				'style' => \PHPExcel_Style_Border::BORDER_MEDIUM,
			),
		),
	);
	
	
	public function __construct($client, $show_all_operations, $year, $month) {
		$this->_excel = new \PHPExcel();
		$this->translator = \AppKernel::getStaticContainer()->get('translator');
		
		$this->_client = $client;
		$this->_show_all_operations = $show_all_operations;
		$this->_year = $year;
		$this->_month = $month;
		
		$this->clientDeclaration = new ClientDeclaration($this->_client);
		$this->clientDeclaration->setShowAllOperations($this->_show_all_operations)
			->setYear($this->_year)
			->setMonth($this->_month);
	}
	
	/**
	 * @param $field
	 * @param $params
	 */
	public function set($field, $params) {
		$this->$field = $params;
	}
	
	/**
	 * @param $field
	 * @return mixed
	 */
	protected function get($field) {
		return $this->$field;
	}
	
	/**
	 *
	 */
	protected function setFileName()
	{
		$this->_file_name = $this->_client . '-Declaration-' . date('Y-m') . '-1';
	}
	
	/**
	 * @return mixed
	 */
	public function getFileNameExt()
	{
		return $this->_file_name . '.xlsx';
	}
	
	protected function getProperties() {
		$this->_excel->getProperties()->setCreator("Eurotax");
		$this->_excel->getDefaultStyle()->getFont()->setName('Arial');
		$this->_excel->getDefaultStyle()->getFont()->setSize(10);
		$this->_excel->getDefaultStyle()->applyFromArray(array(
			'borders' => array(
				'allborders' => array(
					'style' => \PHPExcel_Style_Border::BORDER_NONE,
					//'color' => array('rgb' => \PHPExcel_Style_Color::COLOR_WHITE)
				)
			)
		));
			
		
	}
	
	public function getData() {
		$this->getProperties();
		
		$this->_cellProperties();
		
		$this->_head();
		$this->_info();
		$this->_financial();
		$this->_vat();
		$this->_operations();
		
		$this->_excel->setActiveSheetIndex(0);
		/* $this->_excel->getActiveSheet()->getStyle(
			'A1:' .
			$this->_excel->getActiveSheet()->getHighestColumn() .
			$this->_excel->getActiveSheet()->getHighestRow()
		)->applyFromArray(array(
			'borders' => array(
				'allborders' => array(
					'style' => \PHPExcel_Style_Border::BORDER_NONE
				)
			)
		)); */
		$this->_excel->getActiveSheet()->setTitle('Declaration');
		
		
		
		
	}
	
	protected function _cellProperties() {
		$this->_excel->getActiveSheet()->getColumnDimension('A')->setWidth(2);
		$this->_excel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
		
		$this->_excel->getActiveSheet()->getColumnDimension('C')->setWidth(5);
		$this->_excel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
		$this->_excel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
		$this->_excel->getActiveSheet()->getColumnDimension('F')->setWidth(20);
		$this->_excel->getActiveSheet()->getColumnDimension('G')->setWidth(20);
		
		$this->_excel->getActiveSheet()->getColumnDimension('H')->setWidth(5);
		
		$this->_excel->getActiveSheet()->getColumnDimension('I')->setWidth(20);
		$this->_excel->getActiveSheet()->getColumnDimension('J')->setWidth(20);
	}
	
	
	protected function _head() {
		
		/* $objRichText = new \PHPExcel_RichText();
		$key = $objRichText->createTextRun('EUROTAX') */;
		$this->_excel->getActiveSheet()->getCell('B2')->setValue('EUROTAX');
		$this->_excel->getActiveSheet()->getCell('B3')->setValue('77-79 Av. A. Briand');
		$this->_excel->getActiveSheet()->getCell('B4')->setValue('94110 ARCUEIL');
		
		
		if(!$this->_locking) {
			$this->_excel->getActiveSheet()->getCell('F2')->setValue('Mois TVA non clôturé');
			$this->_excel->getActiveSheet()->getStyle('F2')->getFont()->setBold(true);
		}
		
		$this->_excel->getActiveSheet()->getCell('J2')->setValue($this->_client->getUser()->getFullname());
		$this->_excel->getActiveSheet()->getStyle('J2')->getFont()->setBold(true);
		$this->_excel->getActiveSheet()->getCell('J3')->setValue($this->_client->getUser()->getPhone());
		$this->_excel->getActiveSheet()->getCell('J4')->setValue($this->_client->getUser()->getEmail());
		
		
		
	}
	
	protected function _info() {
	
		$this->_excel->getActiveSheet()->getCell('B7')->setValue( $this->translator->trans('ApplicationSonataClientOperationsBundle.declaration.vat_entity') );
		$this->_excel->getActiveSheet()->mergeCells('B7:E7');
		$this->_excel->getActiveSheet()->getStyle('B7:E7')->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$this->_excel->getActiveSheet()->getStyle('B7:E7')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$this->_excel->getActiveSheet()->getStyle('B7:E7')->applyFromArray($this->_styleMediumBorders);
		
		$this->_excel->getActiveSheet()->getCell('B8')->setValue( $this->_client->getRaisonSociale() );
		$this->_excel->getActiveSheet()->mergeCells('B8:C8');
		$this->_excel->getActiveSheet()->getStyle('B8:C8')->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$this->_excel->getActiveSheet()->getStyle('B8:C8')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$this->_excel->getActiveSheet()->getStyle('B8:C8')->applyFromArray($this->_styleMediumBorders);
				
		$this->_excel->getActiveSheet()->getCell('D8')->setValue( $this->_client->getNTVAFR() );
		$this->_excel->getActiveSheet()->mergeCells('D8:E8');
		$this->_excel->getActiveSheet()->getStyle('D8:E8')->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$this->_excel->getActiveSheet()->getStyle('D8:E8')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$this->_excel->getActiveSheet()->getStyle('D8:E8')->applyFromArray($this->_styleMediumBorders);
		
		
		
		$this->_excel->getActiveSheet()->getCell('H7')->setValue( $this->translator->trans('ApplicationSonataClientOperationsBundle.declaration.summ') );
		$this->_excel->getActiveSheet()->mergeCells('H7:J7');
		$this->_excel->getActiveSheet()->getStyle('H7:J7')->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$this->_excel->getActiveSheet()->getStyle('H7:J7')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$this->_excel->getActiveSheet()->getStyle('H7:J7')->applyFromArray($this->_styleMediumBorders);
		
		$this->_excel->getActiveSheet()->getCell('H8')->setValue( $this->_client->getPeriodiciteCA3Info($this->_year, $this->_month) );
		$this->_excel->getActiveSheet()->mergeCells('H8:J8');
		$this->_excel->getActiveSheet()->getStyle('H8:J8')->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$this->_excel->getActiveSheet()->getStyle('H8:J8')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$this->_excel->getActiveSheet()->getStyle('H8:J8')->applyFromArray($this->_styleMediumBorders);
		
	}
	
	protected function _financial() {
		//header
		$this->_excel->getActiveSheet()->getCell('B10')->setValue( $this->translator->trans('ApplicationSonataClientOperationsBundle.declaration.fin_situation') );
		$this->_excel->getActiveSheet()->getStyle('B10')->getFont()->setBold(true);
		$this->_excel->getActiveSheet()->mergeCells('B10:J10');
		$this->_excel->getActiveSheet()->getStyle('B10:J10')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$this->_excel->getActiveSheet()->getStyle('B10:J10')->applyFromArray($this->_styleBorders);
		
		//balance
		
		$this->_excel->getActiveSheet()->getCell('B12')->setValue( $this->translator->trans('ApplicationSonataClientOperationsBundle.declaration.balance.tva') );
		$this->_excel->getActiveSheet()->getCell('B13')->setValue( $this->translator->trans('ApplicationSonataClientOperationsBundle.declaration.balance.additional_taxes') );
		$this->_excel->getActiveSheet()->getCell('B14')->setValue( $this->translator->trans('ApplicationSonataClientOperationsBundle.declaration.balance.account_balance') );
		$this->_excel->getActiveSheet()->getCell('B15')->setValue( $this->translator->trans('ApplicationSonataClientOperationsBundle.declaration.balance.total') );
		
		
		$this->_excel->getActiveSheet()->getCell('E12')->setValue($this->clientDeclaration->getSoldeTVATotal());
		$this->_excel->getActiveSheet()->getStyle('E12')->getNumberFormat()->setFormatCode('#,##0.00;[RED]\(#,##0.00\)');
		
		if($this->_client->getTaxeAdditionnelle()) {
			$this->_excel->getActiveSheet()->getCell('E13')->setValue($this->clientDeclaration->getRapprochementState()->getCreditTvaAReporter());
			$this->_excel->getActiveSheet()->getStyle('E13')->getNumberFormat()->setFormatCode('#,##0.00;[RED]\(#,##0.00\)');
		}
		$this->_excel->getActiveSheet()->getCell('E14')->setValue($this->_client->getCompteReelSum());
		$this->_excel->getActiveSheet()->getStyle('E14')->getNumberFormat()->setFormatCode('#,##0.00;[RED]\(#,##0.00\)');
		$this->_excel->getActiveSheet()->getCell('E15')->setValue($this->clientDeclaration->getTotalBalance());
		$this->_excel->getActiveSheet()->getStyle('E15')->getNumberFormat()->setFormatCode('#,##0.00;[RED]\(#,##0.00\)');
		
		$this->_excel->getActiveSheet()->getCell('F15')->setValue('on ' . date('d/m/Y'));
		
		//bank
		$this->_excel->getActiveSheet()->getCell('I13')->setValue('eurotax bank details:');
		$this->_excel->getActiveSheet()->getCell('I14')->setValue('BARCLAYS BANK Paris');
		$this->_excel->getActiveSheet()->getCell('I15')->setValue('BIC : BARCFRPP');
		$this->_excel->getActiveSheet()->getCell('I16')->setValue('IBAN : FR7630588610817346941010126');
		
	}
	
	protected function _vat() {
		
		//header
		$this->_excel->getActiveSheet()->getCell('B19')->setValue( $this->translator->trans('ApplicationSonataClientOperationsBundle.declaration.vat_situation') );
		$this->_excel->getActiveSheet()->getStyle('B19')->getFont()->setBold(true);
		$this->_excel->getActiveSheet()->mergeCells('B19:J19');
		$this->_excel->getActiveSheet()->getStyle('B19:J19')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$this->_excel->getActiveSheet()->getStyle('B19:J19')->applyFromArray($this->_styleBorders);
		//output
		$this->_excel->getActiveSheet()->getCell('B21')->setValue( $this->translator->trans('ApplicationSonataClientOperationsBundle.declaration.total_header_output') );
		$this->_excel->getActiveSheet()->mergeCells('B21:E21');
		$this->_excel->getActiveSheet()->getStyle('B21:E21')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$this->_excel->getActiveSheet()->getStyle('B21:E21')->applyFromArray($this->_styleBorders);
		//input
		$this->_excel->getActiveSheet()->getCell('G21')->setValue( $this->translator->trans('ApplicationSonataClientOperationsBundle.declaration.total_header_input') );
		$this->_excel->getActiveSheet()->mergeCells('G21:J21');
		$this->_excel->getActiveSheet()->getStyle('G21:J21')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$this->_excel->getActiveSheet()->getStyle('G21:J21')->applyFromArray($this->_styleBorders);
		
		$this->_excel->getActiveSheet()->getCell('D24')->setValue( $this->translator->trans('ApplicationSonataClientOperationsBundle.declaration.th.nett') );
		$this->_excel->getActiveSheet()->getCell('E24')->setValue( $this->translator->trans('ApplicationSonataClientOperationsBundle.declaration.th.tva') );
		

		$this->_excel->getActiveSheet()->getCell('I24')->setValue( $this->translator->trans('ApplicationSonataClientOperationsBundle.declaration.th.nett') );
		$this->_excel->getActiveSheet()->getCell('J24')->setValue( $this->translator->trans('ApplicationSonataClientOperationsBundle.declaration.th.tva') );
		
		
		$this->_excel->getActiveSheet()->getCell('B25')->setValue( $this->translator->trans('ApplicationSonataClientOperationsBundle.declaration.sales') );
		$this->_excel->getActiveSheet()->mergeCells('B25:D25');
		
		//top border
		$this->_excel->getActiveSheet()->getStyle("B25")->applyFromArray($this->_styleTopLeftBorders);
		$this->_excel->getActiveSheet()->getStyle("C25:D25")->applyFromArray($this->_styleTopBorders);
		$this->_excel->getActiveSheet()->getStyle("E25")->applyFromArray($this->_styleTopRightBorders);
		
		//==totals==//
		$row = 26;
		if($this->clientDeclaration->getV01TVAList()) {
			foreach($this->clientDeclaration->getV01TVAList() as $entity) {
				$this->_excel->getActiveSheet()->getStyle('D'.$row)->getNumberFormat()->setFormatCode('#,##0.00;[RED]\(#,##0.00\)');
				$this->_excel->getActiveSheet()->getStyle('E'.$row)->getNumberFormat()->setFormatCode('#,##0.00;[RED]\(#,##0.00\)');
				
				$this->_excel->getActiveSheet()->getCell('D'.$row)->setValue($entity->getHT());
				$this->_excel->getActiveSheet()->getCell('D'.$row)->setValue($entity->getTVA());
				
				$this->_excel->getActiveSheet()->getStyle("B$row")->applyFromArray($this->_styleLeftBorders);
				$this->_excel->getActiveSheet()->getStyle("E$row")->applyFromArray($this->_styleRightBorders);
				
				$row++;
			}
		}
		
		//bottom border
		$this->_excel->getActiveSheet()->getStyle("B$row")->applyFromArray($this->_styleBottomLeftBorders);
		$this->_excel->getActiveSheet()->getStyle("C$row:D$row")->applyFromArray($this->_styleBottomBorders);
		$this->_excel->getActiveSheet()->getStyle("E$row")->applyFromArray($this->_styleBottomRightBorders);
		
		
		$this->_excel->getActiveSheet()->getCell('G25')->setValue( $this->translator->trans('ApplicationSonataClientOperationsBundle.declaration.vat_purchases')
				. ' ' . $this->translator->trans('ApplicationSonataClientOperationsBundle.declaration.period_this_month') );
		$this->_excel->getActiveSheet()->mergeCells('G25:I25');
		
		
		//top border
		$this->_excel->getActiveSheet()->getStyle("G25")->applyFromArray($this->_styleTopLeftBorders);
		$this->_excel->getActiveSheet()->getStyle("H25:I25")->applyFromArray($this->_styleTopBorders);
		$this->_excel->getActiveSheet()->getStyle("J25")->applyFromArray($this->_styleTopRightBorders);
		
		
		$row = 26;
		if($this->clientDeclaration->getA02TVAlist()) {
			$this->_excel->getActiveSheet()->getCell('G'.$row)->setValue('Purchases');
			$row++;
			
			foreach($this->clientDeclaration->getA02TVAlist() as $entity) {
				$this->_excel->getActiveSheet()->getStyle('I'.$row)->getNumberFormat()->setFormatCode('#,##0.00;[RED]\(#,##0.00\)');
				$this->_excel->getActiveSheet()->getStyle('J'.$row)->getNumberFormat()->setFormatCode('#,##0.00;[RED]\(#,##0.00\)');
		
				$this->_excel->getActiveSheet()->getCell('I'.$row)->setValue($entity->getTauxDeTVA() * 100);
				$this->_excel->getActiveSheet()->getCell('J'.$row)->setValue($entity->getTVA());
				
				
				$this->_excel->getActiveSheet()->getStyle("G$row")->applyFromArray($this->_styleLeftBorders);
				$this->_excel->getActiveSheet()->getStyle("J$row")->applyFromArray($this->_styleRightBorders);
				
				$row++;
			}
		}
		//bottom border
		$this->_excel->getActiveSheet()->getStyle("G$row")->applyFromArray($this->_styleBottomLeftBorders);
		$this->_excel->getActiveSheet()->getStyle("H$row:I$row")->applyFromArray($this->_styleBottomBorders);
		$this->_excel->getActiveSheet()->getStyle("J$row")->applyFromArray($this->_styleBottomRightBorders);
		
		
		
		//top border
		$this->_excel->getActiveSheet()->getStyle("G$row")->applyFromArray($this->_styleTopLeftBorders);
		$this->_excel->getActiveSheet()->getStyle("H$row:I$row")->applyFromArray($this->_styleTopBorders);
		$this->_excel->getActiveSheet()->getStyle("J$row")->applyFromArray($this->_styleTopRightBorders);
		
		if($this->clientDeclaration->getA08IMlist()) {
				
			$this->_excel->getActiveSheet()->getCell('G'.$row)->setValue('Importation');
			$row++;
				
			foreach($this->clientDeclaration->getA08IMlist() as $entity) {
				$this->_excel->getActiveSheet()->getStyle('I'.$row)->getNumberFormat()->setFormatCode('#,##0.00;[RED]\(#,##0.00\)');
				$this->_excel->getActiveSheet()->getStyle('J'.$row)->getNumberFormat()->setFormatCode('#,##0.00;[RED]\(#,##0.00\)');
		
				$this->_excel->getActiveSheet()->getCell('I'.$row)->setValue($entity->getTauxDeTVA() * 100);
				$this->_excel->getActiveSheet()->getCell('J'.$row)->setValue($entity->getTVA());
				
				$this->_excel->getActiveSheet()->getStyle("G$row")->applyFromArray($this->_styleLeftBorders);
				$this->_excel->getActiveSheet()->getStyle("J$row")->applyFromArray($this->_styleRightBorders);
				
				
				$row++;
			}
		}
		
		//bottom border
		$this->_excel->getActiveSheet()->getStyle("G$row")->applyFromArray($this->_styleBottomLeftBorders);
		$this->_excel->getActiveSheet()->getStyle("H$row:I$row")->applyFromArray($this->_styleBottomBorders);
		$this->_excel->getActiveSheet()->getStyle("J$row")->applyFromArray($this->_styleBottomRightBorders);
		
		
		$row = $row+2;
		
		
		if($this->clientDeclaration->getA02TVAPrevlist()) {
			
			$this->_excel->getActiveSheet()->getCell('G'.$row)->setValue($this->translator->trans('ApplicationSonataClientOperationsBundle.declaration.vat_purchases') 
				. ' ' . $this->translator->trans('ApplicationSonataClientOperationsBundle.declaration.period_prev_month'));
			$row++;
			
			
			
			$this->_excel->getActiveSheet()->getCell('G'.$row)->setValue('Purchases');
			//$row++;
				
			
			foreach($this->clientDeclaration->getA02TVAPrevlist() as $entity) {
				$this->_excel->getActiveSheet()->getStyle('I'.$row)->getNumberFormat()->setFormatCode('#,##0.00;[RED]\(#,##0.00\)');
				$this->_excel->getActiveSheet()->getStyle('J'.$row)->getNumberFormat()->setFormatCode('#,##0.00;[RED]\(#,##0.00\)');
		
				$this->_excel->getActiveSheet()->getCell('I'.$row)->setValue($entity->getTauxDeTVA() * 100);
				$this->_excel->getActiveSheet()->getCell('J'.$row)->setValue($entity->getTVA());
				
				
				
				
				$row++;
			}
		}
		
		if($this->clientDeclaration->getA08IMPrevlist()) {
			$this->_excel->getActiveSheet()->getCell('G'.$row)->setValue('Importation');
			$row++;
		
			foreach($this->clientDeclaration->getA08IMPrevlist() as $entity) {
				$this->_excel->getActiveSheet()->getStyle('I'.$row)->getNumberFormat()->setFormatCode('#,##0.00;[RED]\(#,##0.00\)');
				$this->_excel->getActiveSheet()->getStyle('J'.$row)->getNumberFormat()->setFormatCode('#,##0.00;[RED]\(#,##0.00\)');
		
				$this->_excel->getActiveSheet()->getCell('I'.$row)->setValue($entity->getTauxDeTVA() * 100);
				$this->_excel->getActiveSheet()->getCell('J'.$row)->setValue($entity->getTVA());
				$row++;
			}
		}
		
		//==end totals==//
		
		
		//==charge==//
		$row = $row+3;
		
		//top border
		$this->_excel->getActiveSheet()->getStyle("B$row")->applyFromArray($this->_styleTopLeftBorders);
		$this->_excel->getActiveSheet()->getStyle("C$row:I$row")->applyFromArray($this->_styleTopBorders);
		$this->_excel->getActiveSheet()->getStyle("J$row")->applyFromArray($this->_styleTopRightBorders);
		
		$this->_excel->getActiveSheet()->getCell('D'.$row)->setValue( $this->translator->trans('ApplicationSonataClientOperationsBundle.declaration.charge_header') );
		$this->_excel->getActiveSheet()->mergeCells("D$row:J$row");
		$this->_excel->getActiveSheet()->getStyle("D$row:J$row")->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		
		
		$row++;
		if($this->clientDeclaration->getA04283Ilist()) {
			foreach($this->clientDeclaration->getA04283Ilist() as $entity) {
				
				$this->_excel->getActiveSheet()->getStyle('C'.$row)->getNumberFormat()->setFormatCode('0.00%');
				$this->_excel->getActiveSheet()->getStyle('D'.$row)->getNumberFormat()->setFormatCode('#,##0.00;[RED]\(#,##0.00\)');
				$this->_excel->getActiveSheet()->getStyle('E'.$row)->getNumberFormat()->setFormatCode('#,##0.00;[RED]\(#,##0.00\)');
			
				$this->_excel->getActiveSheet()->getCell('C'.$row)->setValue($entity->getTauxDeTVA() * 100);
				$this->_excel->getActiveSheet()->getCell('D'.$row)->setValue($entity->getHT());
				$this->_excel->getActiveSheet()->getCell('E'.$row)->setValue($entity->getTVA());
				
				
				$this->_excel->getActiveSheet()->getStyle('H'.$row)->getNumberFormat()->setFormatCode('0.00%');
				$this->_excel->getActiveSheet()->getStyle('I'.$row)->getNumberFormat()->setFormatCode('#,##0.00;[RED]\(#,##0.00\)');
				$this->_excel->getActiveSheet()->getStyle('J'.$row)->getNumberFormat()->setFormatCode('#,##0.00;[RED]\(#,##0.00\)');
					
				$this->_excel->getActiveSheet()->getCell('H'.$row)->setValue($entity->getTauxDeTVA() * 100);
				$this->_excel->getActiveSheet()->getCell('I'.$row)->setValue($entity->getHT());
				$this->_excel->getActiveSheet()->getCell('J'.$row)->setValue($entity->getTVA());
				
				
				$this->_excel->getActiveSheet()->getStyle("B$row")->applyFromArray($this->_styleLeftBorders);
				$this->_excel->getActiveSheet()->getStyle("J$row")->applyFromArray($this->_styleRightBorders);
				
				
				$row++;
			}
		}
		//bottom border
		$this->_excel->getActiveSheet()->getStyle("B$row")->applyFromArray($this->_styleBottomLeftBorders);
		$this->_excel->getActiveSheet()->getStyle("C$row:I$row")->applyFromArray($this->_styleBottomBorders);
		$this->_excel->getActiveSheet()->getStyle("J$row")->applyFromArray($this->_styleBottomRightBorders);
		
		
		
		//==end charge==//
		
		//==acquisitions==//
		$row++;
		$row++;
		
		//top border
		$this->_excel->getActiveSheet()->getStyle("B$row")->applyFromArray($this->_styleTopLeftBorders);
		$this->_excel->getActiveSheet()->getStyle("C$row:I$row")->applyFromArray($this->_styleTopBorders);
		$this->_excel->getActiveSheet()->getStyle("J$row")->applyFromArray($this->_styleTopRightBorders);
		
		
		$this->_excel->getActiveSheet()->getCell('D'.$row)->setValue( $this->translator->trans('ApplicationSonataClientOperationsBundle.declaration.acquisitions_header') );
		$this->_excel->getActiveSheet()->mergeCells("D$row:J$row");
		$this->_excel->getActiveSheet()->getStyle("D$row:J$row")->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		
		
		$row++;
		if($this->clientDeclaration->getA06AIBlist()) {
			foreach($this->clientDeclaration->getA06AIBlist() as $entity) {
		
				$this->_excel->getActiveSheet()->getStyle('C'.$row)->getNumberFormat()->setFormatCode('0.00%');
				$this->_excel->getActiveSheet()->getStyle('D'.$row)->getNumberFormat()->setFormatCode('#,##0.00;[RED]\(#,##0.00\)');
				$this->_excel->getActiveSheet()->getStyle('E'.$row)->getNumberFormat()->setFormatCode('#,##0.00;[RED]\(#,##0.00\)');
					
				$this->_excel->getActiveSheet()->getCell('C'.$row)->setValue($entity->getTauxDeTVA() * 100);
				$this->_excel->getActiveSheet()->getCell('D'.$row)->setValue($entity->getHT());
				$this->_excel->getActiveSheet()->getCell('E'.$row)->setValue($entity->getTVA());
		
		
				$this->_excel->getActiveSheet()->getStyle('H'.$row)->getNumberFormat()->setFormatCode('0.00%');
				$this->_excel->getActiveSheet()->getStyle('I'.$row)->getNumberFormat()->setFormatCode('#,##0.00;[RED]\(#,##0.00\)');
				$this->_excel->getActiveSheet()->getStyle('J'.$row)->getNumberFormat()->setFormatCode('#,##0.00;[RED]\(#,##0.00\)');
					
				$this->_excel->getActiveSheet()->getCell('H'.$row)->setValue($entity->getTauxDeTVA() * 100);
				$this->_excel->getActiveSheet()->getCell('I'.$row)->setValue($entity->getHT());
				$this->_excel->getActiveSheet()->getCell('J'.$row)->setValue($entity->getTVA());
				
				$this->_excel->getActiveSheet()->getStyle("B$row")->applyFromArray($this->_styleLeftBorders);
				$this->_excel->getActiveSheet()->getStyle("J$row")->applyFromArray($this->_styleRightBorders);
		
				$row++;
			}
		}
		//bottom border
		$this->_excel->getActiveSheet()->getStyle("B$row")->applyFromArray($this->_styleBottomLeftBorders);
		$this->_excel->getActiveSheet()->getStyle("C$row:I$row")->applyFromArray($this->_styleBottomBorders);
		$this->_excel->getActiveSheet()->getStyle("J$row")->applyFromArray($this->_styleBottomRightBorders);
		//==end acquisitions==//
		$row++;
		
		$this->_current_row = $row;
	}
	
	protected function _operations() {
	
		$row = $this->_current_row;
		$row++;		
		
		//header
		$this->_excel->getActiveSheet()->getCell("B$row")->setValue( $this->translator->trans('ApplicationSonataClientOperationsBundle.declaration.operations_header') );
		$this->_excel->getActiveSheet()->getStyle("B$row")->getFont()->setBold(true);
		$this->_excel->getActiveSheet()->mergeCells("B$row:J$row");
		$this->_excel->getActiveSheet()->getStyle("B$row:J$row")->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$this->_excel->getActiveSheet()->getStyle("B$row:J$row")->applyFromArray($this->_styleBorders);
		
		//exports
		$row++;
		
		$repeatRow = $row;
		
		$this->_excel->getActiveSheet()->getCell("B$row")->setValue( $this->translator->trans('ApplicationSonataClientOperationsBundle.declaration.exports_header') );
		$this->_excel->getActiveSheet()->mergeCells("B$row:D$row");
		if($this->clientDeclaration->getV07EXlist()) {
			foreach($this->clientDeclaration->getV07EXlist() as $entity) {
		
				//$this->_excel->getActiveSheet()->getStyle('C'.$row)->getNumberFormat()->setFormatCode('0.00%');
				$this->_excel->getActiveSheet()->getStyle('D'.$row)->getNumberFormat()->setFormatCode('#,##0.00;[RED]\(#,##0.00\)');
				//$this->_excel->getActiveSheet()->getCell('C'.$row)->setValue($entity->getTauxDeTVA() * 100);
				$this->_excel->getActiveSheet()->getCell('D'.$row)->setValue($entity->getHT());
		
				$row++;
			}
		}
		
		//input
		//quota
		$row = $repeatRow;
		$this->_excel->getActiveSheet()->getCell("G$row")->setValue( $this->translator->trans('ApplicationSonataClientOperationsBundle.declaration.quota_header') );
		$this->_excel->getActiveSheet()->mergeCells("G$row:H$row");
		if($this->clientDeclaration->getA10CAFlist()) {
			foreach($this->clientDeclaration->getA10CAFlist() as $entity) {
		
				$this->_excel->getActiveSheet()->getStyle('I'.$row)->getNumberFormat()->setFormatCode('#,##0.00;[RED]\(#,##0.00\)');
				$this->_excel->getActiveSheet()->getCell('I'.$row)->setValue($entity->getHT());
		
				$row++;
			}
		}
		
		
		
		
		//deliveries
		$this->_excel->getActiveSheet()->getCell("B$row")->setValue( $this->translator->trans('ApplicationSonataClientOperationsBundle.exports.V05LIC.title') );
		$this->_excel->getActiveSheet()->mergeCells("B$row:D$row");
		if($this->clientDeclaration->getV05LIClist()) {
			foreach($this->clientDeclaration->getV05LIClist() as $entity) {
		
				//$this->_excel->getActiveSheet()->getStyle('C'.$row)->getNumberFormat()->setFormatCode('0.00%');
				$this->_excel->getActiveSheet()->getStyle('D'.$row)->getNumberFormat()->setFormatCode('#,##0.00;[RED]\(#,##0.00\)');
				//$this->_excel->getActiveSheet()->getCell('C'.$row)->setValue($entity->getTauxDeTVA() * 100);
				$this->_excel->getActiveSheet()->getCell('D'.$row)->setValue($entity->getHT());
		
				$row++;
			}
		}
		
		//no tva
		$this->_excel->getActiveSheet()->getCell("B$row")->setValue( $this->translator->trans('ApplicationSonataClientOperationsBundle.exports.V03283I.title') );
		$this->_excel->getActiveSheet()->mergeCells("B$row:D$row");
		if($this->clientDeclaration->getV03283Ilist()) {
			foreach($this->clientDeclaration->getV03283Ilist() as $entity) {
		
				//$this->_excel->getActiveSheet()->getStyle('C'.$row)->getNumberFormat()->setFormatCode('0.00%');
				$this->_excel->getActiveSheet()->getStyle('D'.$row)->getNumberFormat()->setFormatCode('#,##0.00;[RED]\(#,##0.00\)');
				//$this->_excel->getActiveSheet()->getCell('C'.$row)->setValue($entity->getTauxDeTVA() * 100);
				$this->_excel->getActiveSheet()->getCell('D'.$row)->setValue($entity->getHT());
		
				$row++;
			}
		}
		
		//other
		$this->_excel->getActiveSheet()->getCell("B$row")->setValue( $this->translator->trans('ApplicationSonataClientOperationsBundle.declaration.other_header') );
		$this->_excel->getActiveSheet()->mergeCells("B$row:D$row");
		if($this->clientDeclaration->getV11INTlist()) {
			foreach($this->clientDeclaration->getV11INTlist() as $entity) {
		
				//$this->_excel->getActiveSheet()->getStyle('C'.$row)->getNumberFormat()->setFormatCode('0.00%');
				$this->_excel->getActiveSheet()->getStyle('D'.$row)->getNumberFormat()->setFormatCode('#,##0.00;[RED]\(#,##0.00\)');
				//$this->_excel->getActiveSheet()->getCell('C'.$row)->setValue($entity->getTauxDeTVA() * 100);
				$this->_excel->getActiveSheet()->getCell('D'.$row)->setValue($entity->getHT());
		
				$row++;
			}
		}
		
		
		
	}
	
	
	protected function _ruling() {
	
	}
	
	public function render()
	{
		$this->setFileName();
		$this->getData();
	
		$writer = new \PHPExcel_Writer_Excel2007($this->_excel);
		$writer->save($this->getFileAbsolute());
		
		/* header('Content-Type: application/excel');
		header('Content-Disposition: attachment; filename="' . $this->getFileNameExt() . '"');
		header('Cache-Control: max-age=0');
		$writer->save('php://output'); */
		unset($this->_excel);
	}
	
	/**
	 * @return string
	 */
	public function getFileAbsolute()
	{
		return TMP_UPLOAD_PATH . '/' . $this->getFileNameExt();
	}
}