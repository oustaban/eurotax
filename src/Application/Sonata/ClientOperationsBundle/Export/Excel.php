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

        $i = 0;
        foreach ($this->get('_config_excel') as $table => $params) {

            $this->setParams($params);
            if ($i > 0) {
                $this->_excel->createSheet(null, $i);
            }

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
                } elseif (in_array($field, array('n_ligne',
                    'nomenclature',
                    'pays_id_destination',
                    'valeur_fiscale',
                    'regime',
                    'valeur_statistique',
                    'masse_mette',
                    'unites_supplementaires',
                    'nature_transaction',
                    'conditions_livraison',
                    'mode_transport',
                    'departement',
                    'pays_id_origine',
                    'CEE',))
                ) {
                    $styleHeader[$field] = array(
                        'fill' => array(
                            'type' => \PHPExcel_Style_Fill::FILL_SOLID,
                            'color' => array(
                                'argb' => 'ffff99',
                            ),
                        ),
                    );
                }
            }

            if ($params['entity'] == 'DEBIntro' || $params['entity'] == 'DEBExped') {

                if (in_array($field, array('n_ligne',
                    'nomenclature',
                    'pays_id_destination',
                    'regime',
                    'valeur_statistique',
                    'masse_mette',
                    'unites_supplementaires',
                    'nature_transaction',
                    'conditions_livraison',
                    'mode_transport',
                    'departement',
                    'pays_id_origine',
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