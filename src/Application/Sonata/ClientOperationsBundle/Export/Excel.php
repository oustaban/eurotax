<?php

namespace Application\Sonata\ClientOperationsBundle\Export;


class Excel
{
    private $_excel;
    private $_client;
    private $_config_excel;
    private $_locking = false;
    protected $_skip = 3;
    private $_file_name;
    private $translator;
    private $_sheet;
    private $_sum = array();
    private $_header_cell = array();
    private $_params = array();
    
    
    protected $_admin;
    
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

    protected $_styleArrayGray = array(
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
        'fill' => array(
            'type' => \PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array(
                'argb' => 'bfbfbf',
            ),
        ),
    );


    protected $_keyTabData = array(
    			'Overview' => array(
    					array('title' => 'French VAT summary', 'desc' => 'Summary of operations related to the return period'),
    					array('title' => 'Account', 'desc' => 'Financial statement of your account with us'),
    					array('title' => 'Funds request', 'desc' => 'Funds request for payment of the TVA'),
    			),
    			'Sales'	=> array(
    					array('title' => 'Sales with Output TVA', 'desc' => 'Sales of Goods & Services in France. TVA charged'),
    					array('title' => 'Sales without TVA (Art 283-1)', 'desc' => 'Sales of Goods & Services in France. TVA not charged (Art 283-1 FTC)'),
    					array('title' => 'Intra-EU deliveries of goods', 'desc' => 'Exempt sales of Goods from France to the E.U. (Intra-EU deliveries)'),
    					array('title' => 'Intrastat (Dispatches)', 'desc' => 'Intrastat return (for Customs purposes) : Dispatches from France to the EU'),
    					array('title' => 'Other Intl Service Sales 0%', 'desc' => 'Exempt Sales of International Services from France & Other Exempt Operations'),
    					array('title' => 'Exports of Goods 0%', 'desc' => 'Exempt Sales of Goods from France to a non EU country (exportation)')
    			),
    			'Purchases' => array(
    					array('title' => 'Purchases with TVA', 'desc' => 'Purchases of Goods & Services taxable in France. Charged and recoverable TVA'),
    					array('title' => 'Imports of Goods', 'desc' => 'Imports of Goods from a non-EU country to France'),
    					array('title' => 'Purchases without TVA', 'desc' => 'Purchases of Goods & Services taxable in France. Art 283-1 : Reverse Charge'),
    					array('title' => 'Intra-EU Acquisitions of Goods', 'desc' => 'Intra-EU Acquisitions of Goods from the EU to France : Reverse Charge'),
    					array('title' => 'Intrastat (Arrivals)', 'desc' => 'Intrastat return (for Customs purposes) : Arrivals from another EU country to France'),
    					array('title' => 'Purch. of EU & non-EU Services', 'desc' => 'EU & non-EU Purchases of Services : Reverse Charge'),
    					array('title' => 'QFP Evolution', 'desc' => 'Purchases of Goods & Services taxable in France. TVA not charged : Quota of Free Purchase (QFP)'),
    			),
    	
    	);
    
    
    public function __construct()
    {
        $this->_excel = new \PHPExcel();
        $this->translator = \AppKernel::getStaticContainer()->get('translator');
        
        
        
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

    /**
     *
     */
    protected function setFileName()
    {
        $this->_file_name = $this->_client . '-Exportation-TVA-' . date('Y-m') . '-1';
    }

    /**
     * @return mixed
     */
    public function getFileNameExt()
    {
        return $this->_file_name . '.xlsx';
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return (string)$this->getFileNameExt();
    }

    protected function getProperties()
    {
        $this->_excel->getProperties()->setCreator("Eurotax");
        $this->_excel->getDefaultStyle()->getFont()->setName('Arial');
        $this->_excel->getDefaultStyle()->getFont()->setSize(10);
    }


    /**
     * @param $value
     */
    protected function setParams($value)
    {
        $this->_params = $value;
    }

    /**
     * @return array
     */
    protected function getParams()
    {
        return $this->_params;
    }

    /**
     *
     */
    public function getData()
    {
        $this->getProperties();
        
        
        
        $this->keyTabData();
        $this->accountTabData();
       	
        $i = 2;
        foreach ($this->get('_config_excel') as $table => $params) {

            $this->setParams($params);
            //if ($i > 0) {
                $this->_excel->createSheet($i);
            //}

            $this->_excel->setActiveSheetIndex($i);

            $this->_sheet = $this->_excel->getActiveSheet();
            $this->_sheet->getDefaultColumnDimension()->setWidth(10);

            $this->_sheet->setTitle($table);
            $this->setTabsColor($params);

            $this->_sum = array();

            $this->_sheet->fromArray($this->fromArray($params));
            $i++;
        }

        $this->_excel->setActiveSheetIndex(0);
    }
    
    
    protected function accountTabData() {
    	$this->_excel->createSheet(1);
    	$this->_excel->setActiveSheetIndex(1);
    	$this->_sheet = $this->_excel->getActiveSheet();
    	$this->_sheet->getDefaultColumnDimension()->setWidth(10);
    	$this->_sheet->setTitle('Account ' . date('Y'));
    	
    	
    	
    	$this->_excel->getActiveSheet()->getColumnDimension('A')->setWidth(15);
    	$this->_excel->getActiveSheet()->getColumnDimension('B')->setWidth(50);
    	$this->_excel->getActiveSheet()->getColumnDimension('C')->setWidth(15);
    	$this->_excel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
    	
    	
    	$this->_excel->getActiveSheet()->getColumnDimension('F')->setWidth(15);
    	$this->_excel->getActiveSheet()->getColumnDimension('G')->setWidth(50);
    	$this->_excel->getActiveSheet()->getColumnDimension('H')->setWidth(15);
    	$this->_excel->getActiveSheet()->getColumnDimension('I')->setWidth(20);
    	
    	
    	
    	$this->_excel->getActiveSheet()->getCell('A1')->setValue('eurotax');
    	$this->_excel->getActiveSheet()->getStyle('A1')->getFont()->setName('Arial');
    	$this->_excel->getActiveSheet()->getStyle('A1')->getFont()->setSize(14);
    	
    	
    	$this->_excel->getActiveSheet()->setCellValue('B1', 'SHRIEVE PRODUCTS INTL. ACCOUNT as at 06/12/2012');
    	$this->_excel->getActiveSheet()->mergeCells('B1:I1');
    	$this->yellowHeader('B1');
    	
    	
    	$this->_excel->getActiveSheet()->setCellValue('A3', 'French VAT return Curreny:');
    	$this->_excel->getActiveSheet()->setCellValue('D3', 'EURO');
    	$this->_excel->getActiveSheet()->getStyle('D3')->getFont()->setBold(true);
    	
    	$this->_excel->getActiveSheet()->setCellValue('F3', 'Account Number:');
    	$this->_excel->getActiveSheet()->setCellValue('I3', $this->get('_admin')->client_id);
    	$this->_excel->getActiveSheet()->getStyle('I3')->getFont()->setBold(true);
    	 
    	$excel = $this->_excel;
    	
		$headerFunc = function($row, $cols) use($excel) {    	
    	
	    	$headers = array(
	    		'Date', 'Description', 'Euro', 'Balance'		
	    	);
	    	$i = 0;
	    	$col = '';
	    	foreach($headers as $header) {
	    		$col = $cols[$i];
	    		$cell = $col.$row;
	    		$excel->getActiveSheet()->setCellValue($cell, $header);
	    		$excel->getActiveSheet()->getStyle($cell)->getFont()->setBold(true);
	    		$i++;
	    	}
	    	
	    	$excel->getActiveSheet()->setCellValue($col.($row+1), 'EURO');
	    	$excel->getActiveSheet()->getStyle($cell)->getFont()->setBold(true);
	    	
	    	
	    	foreach($cols as $col) {
	    		$excel->getActiveSheet()->getStyle($col.($row+2))->applyFromArray(
	    				array('fill' 	=> array(
	    						'type'		=> \PHPExcel_Style_Fill::FILL_SOLID,
	    						'color'		=> array('argb' => 'FF000000')
	    					)
	    				)
	    		);
	    	}
	    	
    	
		};
		
		
		$headerFunc(5, range('A', 'D'));
		$headerFunc(5, range('F', 'I'));
		
		$result = $this->queryResult(array('entity'=>'compte'));
		$i = 8;
		foreach ($result as $key => $row) {
			$date = \PHPExcel_Shared_Date::PHPToExcel($row->getDate());
			$this->_excel->getActiveSheet()->setCellValue("A$i", $date);
			$this->_sheet->getStyle("A$i")->getNumberFormat()->setFormatCode('dd.mm.YYYY');
			$this->_excel->getActiveSheet()->setCellValue("B$i", $row->getOperation());
			$this->_excel->getActiveSheet()->setCellValue("C$i", $row->getMontant());
			
			//Balance column			
			if($i > 8) {
				$this->_excel->getActiveSheet()->setCellValue("D$i", '=SUM(C'.($i-1).':C'.$i.')');
			}
			
			
			
			
			$i++;
		}
    }

    
    
    protected function keyTabData() {
    	// Key tab
    	$this->_excel->setActiveSheetIndex(0);
    	$this->_sheet = $this->_excel->getActiveSheet();
    	$this->_sheet->getDefaultColumnDimension()->setWidth(10);
    	$this->_sheet->setTitle('Key');
    	$this->_excel->getActiveSheet()->getRowDimension(1)->setRowHeight(25);
    	$this->_excel->getActiveSheet()->getRowDimension(3)->setRowHeight(25);
    	$objRichText = new \PHPExcel_RichText();
    	$key = $objRichText->createTextRun('KEY');
    	
    	$this->_excel->getActiveSheet()->getColumnDimension('A')->setWidth(10);
    	$this->_excel->getActiveSheet()->getColumnDimension('B')->setWidth(40);
    	$this->_excel->getActiveSheet()->getColumnDimension('C')->setWidth(90);
    	 
    	
    	$this->_excel->getActiveSheet()->getCell('B1')->setValue($objRichText);
    	$this->_excel->getActiveSheet()->mergeCells('B1:C1');
    	$this->_excel->getActiveSheet()->getStyle('B1:C1')->getFont()->setName('Arial');
    	$this->_excel->getActiveSheet()->getStyle('B1:C1')->getFont()->setSize(12);
    	$this->_excel->getActiveSheet()->getStyle('B1:C1')->getFont()->setBold(true);
    	$this->_excel->getActiveSheet()->getStyle('B1:C1')->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);
    	$this->_excel->getActiveSheet()->getStyle('B1:C1')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    	$this->_excel->getActiveSheet()->getStyle('B1:C1')->applyFromArray(
    			array('fill' 	=> array(
    					'type'		=> \PHPExcel_Style_Fill::FILL_SOLID,
    					'color'		=> array('argb' => 'FFCCFFCC')
    			),
    					'borders' => array(
    							'allborders'	=> array('style' => \PHPExcel_Style_Border::BORDER_THIN),
    					)
    			)
    	);
    	
    	$this->yellowHeader('A3');
    	$this->_excel->getActiveSheet()->setCellValue('B3', 'Title');
    	$this->yellowHeader('B3');
    	$this->_excel->getActiveSheet()->setCellValue('C3', 'Description of operations');
    	$this->yellowHeader('C3');
    	
    	
    	
    	$startRow = 4;
    	foreach($this->_keyTabData as $head => $rows) {
    		$rowCount = count($rows)-1;
    		$lastRow = $startRow + $rowCount;
    		$objRichText = new \PHPExcel_RichText();
    		$headTitle = $objRichText->createTextRun($head);
    		$cells = "A$startRow:A$lastRow";
    		$this->_excel->getActiveSheet()->getCell("A$startRow")->setValue($objRichText);
    		$this->_excel->getActiveSheet()->mergeCells($cells);
    		$this->_excel->getActiveSheet()->getStyle($cells)->getFont()->setSize(11);
    		$this->_excel->getActiveSheet()->getStyle($cells)->getFont()->setBold(true);
    		$this->_excel->getActiveSheet()->getStyle($cells)->getAlignment()->setTextRotation(90);
    		$this->_excel->getActiveSheet()->getStyle($cells)->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);
    		$this->_excel->getActiveSheet()->getStyle($cells)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    		 
    		unset($objRichText);
    		 
    		$i = $startRow;
    		foreach($rows as $row) {
    			$this->_excel->getActiveSheet()->getRowDimension($i)->setRowHeight(20);
    			$this->_excel->getActiveSheet()->getCell("B$i")->setValue($row['title']);
    			$this->_excel->getActiveSheet()->getCell("C$i")->setValue($row['desc']);
    			$i++;
    		}
    		 
    		 
    		$this->_excel->getActiveSheet()->getRowDimension($i)->setRowHeight(20);
    		$this->_excel->getActiveSheet()->mergeCells("A$i:C$i");
    		$this->_excel->getActiveSheet()->getStyle("A$i:C$i")->applyFromArray(
    				array('fill' 	=> array(
    						'type'		=> \PHPExcel_Style_Fill::FILL_SOLID,
    						'color'		=> array('argb' => 'FFFFFF00')
    					),
    	
    						'borders' => array(
    								'allborders'	=> array('style' => \PHPExcel_Style_Border::BORDER_THIN),
    						)
    				)
    		);
    		$startRow+=($rowCount+2);
    		 
    	}
    }
    
    
    protected function yellowHeader($cellCoordinate) {
    	$this->_excel->getActiveSheet()->getStyle($cellCoordinate)->getFont()->setName('MS Sans Serif');
    	$this->_excel->getActiveSheet()->getStyle($cellCoordinate)->getFont()->setSize(10);
    	$this->_excel->getActiveSheet()->getStyle($cellCoordinate)->getFont()->setBold(true);
    	$this->_excel->getActiveSheet()->getStyle($cellCoordinate)->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);
    	$this->_excel->getActiveSheet()->getStyle($cellCoordinate)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    	$this->_excel->getActiveSheet()->getStyle($cellCoordinate)->applyFromArray(
    			array('fill' 	=> array(
    					'type'		=> \PHPExcel_Style_Fill::FILL_SOLID,
    					'color'		=> array('argb' => 'FFFFFF00')
    			),
    	
    					'borders' => array(
    							'allborders'	=> array('style' => \PHPExcel_Style_Border::BORDER_THIN),
    					)
    			)
    	);
    }
    
    
    
    /**
     * @param $params
     */
    protected function setTabsColor($params)
    {
        switch ($params['entity']) {
            case 'V01TVA':
            case 'V03283I':
            case 'V05LIC':
            case 'V07EX':
            case 'V09DES':
            case 'V11INT':
                $this->_sheet->getTabColor()->setARGB('FF9869ba');
                break;

            case 'A02TVA':
            case 'A04283I':
            case 'A06AIB':
            case 'A08IM':
            case 'A10CAF':
                $this->_sheet->getTabColor()->setARGB('FFffff0c');
                break;
        }
    }

    /**
     * @param $wRow
     * @param $row
     * @param $params
     * @return array
     */
    protected function getCell($wRow, $row, $params)
    {
        $ceil = array();
        $wColumn = 'A';
        $this->_header_cell = array();

        foreach ($params['fields'] as $key => $field) {

            $this->_sheet->getStyle($wColumn . $wRow)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

            $value = call_user_func(array($row, $this->getMethod($field)));

            if ($value instanceof \Application\Sonata\ClientBundle\Entity\ListDevises) {
                $ceil[$field] = $value->getAlias();
            } elseif ($value instanceof \DateTime) {
                //excel time
                $date = \PHPExcel_Shared_Date::PHPToExcel($value);
                if ($field == 'mois') {
                    $this->_sheet->getStyle($wColumn . $wRow)->getNumberFormat()->setFormatCode('MM-YY');
                } else {
                    $this->_sheet->getStyle($wColumn . $wRow)->getNumberFormat()->setFormatCode('dd.mm.YYYY');
                }
                $ceil[$field] = $date > 0 ? $date : '';

            } else {
                if (is_float($value)) {
                    $ceil[$field] = (double)$value;
                    $this->_sheet->getStyle($wColumn . $wRow)->getNumberFormat()->setFormatCode('#\ ##0\ ;[Red]-#\ ##0\ ');
                } else {
                    $ceil[$field] = (string)$value;
                }
            }

            if ((in_array($field, array('mois', 'montant_TTC', 'montant_TVA_francaise', 'paiement_montant', 'paiement_devise')) || isset($this->_sum[$field]) && !($params['entity'] == 'DEBIntro' || $params['entity'] == 'DEBExped'))) {
                $this->_sheet->getStyle($wColumn . $wRow)->applyFromArray($this->_styleArrayGray);
            } else {
                $this->_sheet->getStyle($wColumn . $wRow)->applyFromArray($this->_styleBorders);
            }

            $this->_header_cell[$key] = $wColumn;
            $wColumn++;
        }

        return $ceil;
    }


    protected function getTotal($wRow, $key, $value = 'SUM', $text = '', $position = 'left', $bold = true)
    {
        $styleArray = array(
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
            'fill' => array(
                'type' => \PHPExcel_Style_Fill::FILL_SOLID,
                'color' => array(
                    'argb' => 'bfbfbf',
                ),
            ),
        );

        if ($bold) {
            $styleArray += array(
                'font' => array(
                    'bold' => true,
                ));
        }


        if (isset($this->_header_cell[$key]) && $cell = $this->_header_cell[$key]) {

            if ($position == 'left') {

                if (isset($this->_header_cell[$key - 1])) {
                    $this->_sheet->setCellValue($this->_header_cell[$key - 1] . $wRow, $text);
                    $this->_sheet->getStyle($this->_header_cell[$key - 1] . $wRow)->applyFromArray(array(
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
                    ));
                }

                if ($bold) {
                    $styleArray += array(
                        'font' => array(
                            'bold' => true,
                        ));
                }

            } else {
                if (isset($this->_header_cell[$key + 1])) {
                    $this->_sheet->setCellValue($this->_header_cell[$key + 1] . ($wRow), $text);
                    $this->_sheet->getStyle($this->_header_cell[$key + 1] . $wRow)->applyFromArray(array(
                        'font' => array(
                            'bold' => true,
                        ),
                    ));
                }
            }

            $this->_sheet->setCellValue($cell . $wRow, $this->getFormula($value, $cell, $wRow))
                ->getStyle($cell . $wRow)->getNumberFormat()->setFormatCode('# ##0\ "€";[Red]-# ##0\ "€"');

            $this->_sheet->getStyle($cell . $wRow)->applyFromArray($styleArray);
        }
    }

    /**
     * @param $value
     * @param $cell
     * @param $wRow
     * @return string
     */
    protected function getFormula($value, $cell, $wRow)
    {
        $params = $this->getParams();
        $index = $params['skip_line'] + 1;

        switch ($value) {

            case 'SUM':
                return '=SUM(' . $cell . $index . ':' . $cell . ($wRow - $this->_skip) . ')';
                break;

            case 'SUMIFGreaterThanZero':
                return '=SUMIF(' . $cell . $index . ':' . $cell . ($wRow - $this->_skip) . ', ">0")';
                break;


            case 'SUMIFLessThanZero':
                return '=SUMIF(' . $cell . $index . ':' . $cell . ($wRow - $this->_skip) . ', "<0")';
                break;

            default:
                return $value;
                break;
        }
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
     * @param $params
     * @return array
     */
    protected function fromArray($params)
    {
        $result = $this->queryResult($params);

        $rows = $this->resultDefault($result, $params);

        unset($result);
        return $rows;
    }

    /**
     * @param $result
     * @param $params
     * @return array
     */
    protected function resultDefault($result, $params)
    {
        $rows = array();

        if ($params['skip_line'] > 1) {
            for ($i = 1; $i < $params['skip_line']; $i++) {
                $rows[] = $this->getSkipLine($params);
            }
        }
        //header
        $rows[] = $this->headers($params);

        $count = count($rows) + 1;

        foreach ($params['fields'] as $key => $field) {
            if ($field == 'HT' || $field == 'TVA' || $field == 'valeur_fiscale' || $field == 'valeur_statistique') {
                $this->_sum[$field] = $key;
            }
        }

        //default result
        foreach ($result as $key => $row) {
            $rows[] = $this->getCell($key + $count, $row, $params);
        }

        $count = count($rows) + 1;
        $this->footer($params, $count);

        return $rows;
    }

    /**
     * @param $value
     */
    protected function setSkip($value)
    {
        $this->_skip = $value;
    }

    protected function footer($params, $count)
    {
        if ($params['entity'] == 'DEBExped' || $params['entity'] == 'DEBIntro') {
            $styleArray = array(
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
                'fill' => array(
                    'type' => \PHPExcel_Style_Fill::FILL_SOLID,
                    'color' => array(
                        'argb' => 'ffff00',
                    ),
                ),
                'font' => array(
                    'bold' => true,
                    'color' => array(
                        'argb' => 'ff0000',
                    ),
                )
            );

            if (isset($this->_sum['valeur_fiscale'])) {

                $key = $this->_sum['valeur_fiscale'];
                if (isset($this->_header_cell[$key])) {
                    $cell = $this->_header_cell[$key];
                    $wRow = $count + $this->_skip;

                    $this->_sheet->setCellValue($this->_header_cell[$key - 2] . $wRow, 'Totaux');
                    $this->_sheet->setCellValue($cell . $wRow, $this->getFormula('SUM', $cell, $wRow));
                    $this->_sheet->getStyle($this->_header_cell[$key - 2] . $wRow . ':' . $this->_header_cell[$key] . $wRow)->applyFromArray($styleArray);
                }
            }
            if (isset($this->_sum['valeur_statistique'])) {

                $key = $this->_sum['valeur_statistique'];
                if (isset($this->_header_cell[$key])) {
                    $cell = $this->_header_cell[$key];
                    $wRow = $count + $this->_skip;

                    $this->_sheet->setCellValue($cell . $wRow, $this->getFormula('SUM', $cell, $wRow));
                    $this->_sheet->getStyle($this->_header_cell[$key - 1] . $wRow . ':' . $this->_header_cell[$key] . $wRow)->applyFromArray($styleArray);
                }
            }
        } else {
            //sum
            if (isset($this->_sum['HT'])) {
                $this->getTotal($count + $this->_skip, $this->_sum['HT'], 'SUM', 'TOTAL du mois', 'left');

            }
            if (isset($this->_sum['valeur_fiscale'])) {
                $this->getTotal($count + $this->_skip, $this->_sum['valeur_fiscale'], 'SUM', 'TOTAL du mois', 'left');
            }

            if (isset($this->_sum['TVA'])) {
                if ($params['entity'] == 'A08IM') {
                    $this->getTotal($count + $this->_skip, $this->_sum['TVA'], 'SUM', 'TOTAL du mois', 'left');
                }
                $this->getTotal($count + $this->_skip, $this->_sum['TVA'], 'SUM', 'selon filtre', 'right');
            } elseif (isset($this->_sum['HT'])) {
                $this->getTotal($count + $this->_skip, $this->_sum['HT'], 'SUM', 'selon filtre', 'right');
            }

            if (isset($this->_sum['valeur_statistique'])) {
                $this->getTotal($count + $this->_skip, $this->_sum['valeur_statistique'], 'SUM', 'selon filtre', 'right');
            } elseif (isset($this->_sum['valeur_fiscale'])) {
                $this->getTotal($count + $this->_skip, $this->_sum['valeur_fiscale'], 'SUM', 'selon filtre', 'right');
            }

            //sum_positive
            $count += 2;
            if (isset($this->_sum['HT'])) {
                $this->getTotal($count + $this->_skip, $this->_sum['HT'], 'SUMIFGreaterThanZero', 'Dont factures', 'left', false);

            }

            if (isset($this->_sum['valeur_fiscale'])) {
                $this->getTotal($count + $this->_skip, $this->_sum['valeur_fiscale'], 'SUMIFGreaterThanZero', 'Dont factures', 'left', false);
            }

            if (isset($this->_sum['TVA'])) {

                if ($params['entity'] == 'A08IM') {
                    $this->getTotal($count + $this->_skip, $this->_sum['TVA'], 'SUMIFGreaterThanZero', 'Dont factures', 'left');
                }
                $this->getTotal($count + $this->_skip, $this->_sum['TVA'], 'SUMIFGreaterThanZero', 'tout data', 'right', false);

            } elseif (isset($this->_sum['HT'])) {
                $this->getTotal($count + $this->_skip, $this->_sum['HT'], 'SUMIFGreaterThanZero', 'tout data', 'right', false);
            }

            if (isset($this->_sum['valeur_statistique'])) {
                $this->getTotal($count + $this->_skip, $this->_sum['valeur_statistique'], 'SUMIFGreaterThanZero', 'tout data', 'right', false);
            }


            //sum_negative
            $count += 2;
            if (isset($this->_sum['HT'])) {
                $this->getTotal($count + $this->_skip, $this->_sum['HT'], 'SUMIFLessThanZero', 'Dont avoirs', 'left', false);
            }

            if (isset($this->_sum['valeur_fiscale'])) {
                $this->getTotal($count + $this->_skip, $this->_sum['valeur_fiscale'], 'SUMIFLessThanZero', 'Dont avoirs', 'left', false);
            }


            if (isset($this->_sum['TVA'])) {

                if ($params['entity'] == 'A08IM') {
                    $this->getTotal($count + $this->_skip, $this->_sum['TVA'], 'SUMIFLessThanZero', 'avoirs', 'left');
                }
                $this->getTotal($count + $this->_skip, $this->_sum['TVA'], 'SUMIFLessThanZero', 'tout data', 'right', false);


            } elseif (isset($this->_sum['HT'])) {
                $this->getTotal($count + $this->_skip, $this->_sum['HT'], 'SUMIFLessThanZero', 'tout data', 'right', false);
            }

            if (isset($this->_sum['valeur_statistique'])) {
                $this->getTotal($count + $this->_skip, $this->_sum['valeur_statistique'], 'SUMIFLessThanZero', 'tout data', 'right', false);
            }
        }
    }

    /**
     * @param $params
     * @return array
     */
    protected function headers($params)
    {
        $col = array();
        $wColumn = 'A';
        $last = '';
        $wRows = $params['skip_line'];
        $styleHeader = array();
        foreach ($params['fields'] as $field) {
            $col[] = $this->translator->trans('ApplicationSonataClientOperationsBundle.list.' . $params['entity'] . '.' . $field);

            if ($params['entity'] == 'V05LIC' || $params['entity'] == 'A06AIB') {
                if (!isset($styleHeader[$field]) && ($field == 'regime' || $field == 'DEB')) {

                    $styleHeader[$field] = array(
                        'fill' => array(
                            'type' => \PHPExcel_Style_Fill::FILL_SOLID,
                            'color' => array(
                                'argb' => 'b6dde8',
                            ),
                        ),
                    );
                }
            }

            if ($params['entity'] == 'DEBIntro' || $params['entity'] == 'DEBExped') {

                if (in_array($field, array('n_ligne',
                    'nomenclature',
                    'pays_destination',
                    'regime',
                    'valeur_statistique',
                    'masse_mette',
                    'unites_supplementaires',
                    'nature_transaction',
                    'conditions_livraison',
                    'mode_transport',
                    'departement',
                    'pays_origine',
                    'CEE',))
                ) {
                    $styleHeader[$field] = array(
                        'fill' => array(
                            'type' => \PHPExcel_Style_Fill::FILL_SOLID,
                            'color' => array(
                                'argb' => '000000',
                            ),
                        ),
                        'font' => array(
                            'bold' => true,
                            'color' => array(
                                'argb' => 'ffffff',
                            ),
                        ),
                    );
                } elseif ($field == 'valeur_fiscale') {
                    $styleHeader[$field] = array(
                        'fill' => array(
                            'type' => \PHPExcel_Style_Fill::FILL_SOLID,
                            'color' => array(
                                'argb' => 'ffff00',
                            ),
                        ),
                        'font' => array(
                            'bold' => true,
                            'color' => array(
                                'argb' => 'ff3900',
                            ),
                        ),
                    );
                }
            }

            $this->_sheet->getStyle($wColumn . $wRows)
                ->applyFromArray($this->_styleBorders + (isset($styleHeader[$field]) ? $styleHeader[$field] : array()))
                ->getAlignment()
                ->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER)
                ->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
                ->setWrapText(true);

            $this->setWidthSize($wColumn, $field, $params);
//            $this->_sheet->getColumnDimension($wColumn)->setAutoSize(true);
            $last = $wColumn;
            $wColumn++;
        }

        if ($params['entity'] == 'DEBIntro' || $params['entity'] == 'DEBExped') {

            $index = 1;
            $objRichText = new \PHPExcel_RichText();
            $objPayable = $objRichText->createTextRun("DECLARATION D'ECHANGES DE BIENS ENTRE ETATS MEMBRES DE LA C.E.E. (DEB)");
            $objPayable->getFont()
                ->setName('Arial')
                ->setBold(true)
                ->setSize(10);
            $this->_sheet->getCell('A' . $index)->setValue($objRichText);
            $this->_sheet->mergeCells('A' . $index . ':' . $last . $index);
            $this->_sheet->getStyle('A' . $index . ':' . $last . $index)
                ->getAlignment()
                ->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER)
                ->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $this->_sheet->getRowDimension(1)->setRowHeight(25);


            $index = 3;
            $objRichText = new \PHPExcel_RichText();
            $objPayable = $objRichText->createTextRun("FLUX");
            $objPayable->getFont()
                ->setName('Arial')
                ->setBold(true)
                ->setSize(10);
            $this->_sheet->getCell('A' . $index)->setValue($objRichText);
            $this->_sheet->mergeCells('A' . $index . ':' . $last . $index);
            $this->_sheet->getStyle('A' . $index . ':' . $last . $index)
                ->getAlignment()
                ->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER)
                ->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $this->_sheet->getStyle('A' . $index . ':' . $last . $index)->applyFromArray($this->_styleBorders);
            $this->_sheet->getRowDimension($index)->setRowHeight(25);


            $index = 4;
            $objRichText = new \PHPExcel_RichText();
            $objPayable = $objRichText->createTextRun($params['entity'] == 'DEBExped' ? "EXPEDITION" : 'INTRODUCTION');
            $objPayable->getFont()
                ->setName('Arial')
                ->setSize(10);
            $this->_sheet->getCell('A' . $index)->setValue($objRichText);
            $this->_sheet->mergeCells('A' . $index . ':' . $last . $index);
            $this->_sheet->getStyle('A' . $index . ':' . $last . $index)
                ->getAlignment()
                ->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER)
                ->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $this->_sheet->getStyle('A' . $index . ':' . $last . $index)->applyFromArray($this->_styleBorders);
            $this->_sheet->getRowDimension($index)->setRowHeight(25);

            $wColumn = 'A';
            $index = 6;
            foreach ($params['fields'] as $key => $field) {

                $this->_sheet->getCell($wColumn . $index)->setValue($key + 1);
                $this->_sheet->getStyle($wColumn . $index)->applyFromArray($this->_styleBorders + array('fill' => array(
                    'type' => \PHPExcel_Style_Fill::FILL_SOLID,
                    'color' => array(
                        'argb' => '000000',
                    ),
                ),
                    'font' => array(
                        'bold' => true,
                        'color' => array(
                            'argb' => 'ffffff',
                        ),
                    ),));

                $this->_sheet->getStyle($wColumn . $index)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

                $wColumn++;
            }
        }

        $this->setRowHeight($wRows, $params);

        //autofilter
        $this->_sheet->setAutoFilter('A' . $wRows . ':' . $last . $wRows);

        return $col;
    }

    /**
     * @param $wRows
     * @param $params
     */
    protected function setRowHeight($wRows, $params)
    {
        switch ($params['entity']) {

            case 'DEBIntro':
            case 'DEBExped':
                $this->_sheet->getRowDimension($wRows)->setRowHeight(52);
                break;

            default:
                $this->_sheet->getRowDimension($wRows)->setRowHeight(38);
                break;
        }
    }

    /**
     * @param $wColumn
     * @param $field
     * @param $params
     */
    protected function setWidthSize($wColumn, $field, $params)
    {
        switch ($field) {

            case 'commentaires':
                $this->_sheet->getColumnDimension($wColumn)->setWidth(16);
                break;
        }

        if ($params['entity'] == 'DEBIntro' || $params['entity'] == 'DEBExped') {
            switch ($field) {

                case 'CEE':
                case 'valeur_statistique':
                    $this->_sheet->getColumnDimension($wColumn)->setWidth(16);
                    break;

                case 'conditions_livraison':
                case 'nature_transaction':
                    $this->_sheet->getColumnDimension($wColumn)->setWidth(15);
                    break;

            }
        }
    }


    /**
     * @param $params
     * @return array
     */
    protected function getSkipLine($params)
    {
        $ceil = array();
        foreach ($params['fields'] as $field) {
            $ceil[] = '';
        }
        return $ceil;
    }

    /**
     * @param $field
     * @return string
     */
    protected function getMethod($field)
    {
        return 'get' . str_replace(' ', '', ucwords(str_replace('_', ' ', ucwords($field))));
    }

    /**
     *
     */
    public function render()
    {
        $this->setFileName();
        $this->getData();

        if (!$this->get('_locking')) {
            header('Content-Type: application/excel');
            header('Content-Disposition: attachment; filename="' . $this->getFileNameExt() . '"');
            header('Cache-Control: max-age=0');
        }

        $writer = new \PHPExcel_Writer_Excel2007($this->_excel);
        $writer->save($this->get('_locking') ? $this->getFileAbsolute() : 'php://output');

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