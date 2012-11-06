<?php

namespace Application\Sonata\ClientOperationsBundle\Export;


class Excel
{
    private $_excel;
    private $_client;
    private $_config_excel;
    protected $_skip = 3;
    private $_file_name;
    private $translator;
    private $_sheet;
    private $_sum = array();
    private $_ABC = array();
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
    protected function getFileName()
    {
        $this->_file_name = $this->_client . '-Exportation-TVA-' . date('Y-m') . '-1';
    }

    public function __toString()
    {
        return (string)$this->getFileName();
    }

    protected function getProperties()
    {
        $this->_excel->getProperties()->setCreator("Eurotax");
        $this->_excel->getDefaultStyle()->getFont()->setName('Arial');
        $this->_excel->getDefaultStyle()->getFont()->setSize(10);
    }

    /**
     *
     */
    public function getData()
    {
        $this->getProperties();

        $i = 0;
        foreach ($this->get('_config_excel') as $table => $params) {
            if ($i > 0) {
                $this->_excel->createSheet(null, $i);
            }
            $this->_excel->setActiveSheetIndex($i);

            $this->_sheet = $this->_excel->getActiveSheet();


            $this->_sheet->setTitle($table);

            $this->_sum = array();
            $this->_sheet->fromArray($this->fromArray($params));

            $i++;
        }

        $this->_excel->setActiveSheetIndex(0);
    }


    /**
     * @param $inc
     * @param $row
     * @param $params
     * @return array
     */
    protected function getCell($inc, $row, $params)
    {
        $ceil = array();
        $k = 'A';
        $this->_ABC = array();

        foreach ($params['fields'] as $key => $field) {

            $value = call_user_func(array($row, $this->getMethod($field)));

            if ($value instanceof \Application\Sonata\ClientBundle\Entity\ListDevises) {
                $ceil[$field] = $value->getAlias();
            } elseif ($value instanceof \DateTime) {
                //excel time
                $date = \PHPExcel_Shared_Date::PHPToExcel($value);
                if ($field == 'mois') {
                    $this->_sheet->getStyle($k . $inc)->getNumberFormat()->setFormatCode('MM-YY');
                } else {
                    $this->_sheet->getStyle($k . $inc)->getNumberFormat()->setFormatCode('dd.mm.YYYY');
                }
                $ceil[$field] = $date > 0 ? $date : '';

            } else {
                if (is_float($value)) {
                    $ceil[$field] = (double)$value;
                } else {
                    $ceil[$field] = (string)$value;
                }
            }

            if ($field == 'HT' || $field == 'TVA') {
                $this->_sum[$field] = $key;
            }

            if (in_array($field, array('HT', 'TVA', 'mois', 'montant_TTC', 'montant_TVA_francaise', 'paiement_montant', 'paiement_devise'))) {

                $this->_sheet->getStyle($k . $inc)->applyFromArray($this->_styleArrayGray);
            } else {
                $this->_sheet->getStyle($k . $inc)->applyFromArray($this->_styleBorders);
            }

            $this->_ABC[$key] = $k;
            $k++;
        }

        return $ceil;
    }


    protected function getTotal($count, $key, $text = '', $position = 'left')
    {
        $styleArray = array(
            'font' => array(
                'bold' => true,
            ),
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


        if (isset($this->_ABC[$key]) && $sum = $this->_ABC[$key]) {

            if ($position == 'left') {
                $this->_sheet->setCellValue($this->_ABC[$key - 1] . $count, $text);
                $this->_sheet->getStyle($this->_ABC[$key - 1] . $count)->applyFromArray(array(
                    'font' => array(
                        'bold' => true,
                    ),
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
            } else {
                $this->_sheet->setCellValue($this->_ABC[$key + 1] . ($count), $text);
                $this->_sheet->getStyle($this->_ABC[$key + 1] . $count)->applyFromArray(array(
                    'font' => array(
                        'bold' => true,
                    ),
                ));
            }


            $this->_sheet->setCellValue($sum . $count, '=SUM(' . $sum . '2:' . $sum . ($count - $this->_skip) . ')')
                ->getStyle($sum . $count)->getNumberFormat()->setFormatCode('# ##0\ "€";[Red]-# ##0\ "€"');


            $this->_sheet->getStyle($sum . $count)->applyFromArray($styleArray);

            #$this->_sheet->getStyle($sum . '2:' . $sum . ($count - 3))->applyFromArray($styleArray2);
//                ->setFillType(\PHPExcel_Style_Fill::FILL_SOLID);

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

        $method = 'result' . $params['entity'];
        if (method_exists($this, $method)) {
            $rows = $this->$method($result, $params);
        } else {
            $rows = $this->resultDefault($result, $params);
        }

        unset($result);
        return $rows;
    }

    /**
     * @param $result
     * @param $params
     * @return array
     */
    protected function resultDEBExpedTEST($result, $params)
    {
        $rows = array();
        return $rows;
    }


    /**
     * @param $result
     * @param $params
     * @return array
     */
    protected function resultDEBIntroTEST($result, $params)
    {
        $rows = array();
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
        //skip line
//        if ($params['skip_line'] > 1) {
//            for ($i = 1; $i < $params['skip_line']; $i++) {
//                $rows[] = $this->getSkipLine($params);
//            }
//        }

        //header
        $rows[] = $this->headers($params);

        $count = count($rows) + 1;

        //default result
        foreach ($result as $key => $row) {
            $rows[] = $this->getCell($key + $count, $row, $params);
        }

        $count = count($rows) + 1;
        $this->footer($count);

        return $rows;
    }

    /**
     * @param $value
     */
    protected function setSkip($value)
    {
        $this->_skip = $value;
    }

    protected function footer($count)
    {
        if (isset($this->_sum['HT'])) {
            $this->getTotal($count + $this->_skip, $this->_sum['HT'], 'TOTAL du mois', 'left');
        }

        if (isset($this->_sum['TVA'])) {
            $this->getTotal($count + $this->_skip, $this->_sum['TVA'], 'selon filtre', 'right');
        }
    }

    /**
     * @param $params
     * @return array
     */
    protected function headers($params)
    {
        $col = array();
        $k = 'A';
        $header = 1;
        foreach ($params['fields'] as $field) {
            $col[] = $this->translator->trans('ApplicationSonataClientOperationsBundle.list.' . $params['entity'] . '.' . $field);
            $this->_sheet->getStyle($k . $header)->applyFromArray($this->_styleBorders)
                ->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);

            $this->_sheet->getColumnDimension($k)->setAutoSize(true);

            $k++;
        }
        $this->_sheet->getRowDimension($header)->setRowHeight(38);

        return $col;
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
        $this->getFileName();
        $this->getData();

        header('Content-Type: application/excel');
        header('Content-Disposition: attachment; filename="' . $this->_file_name . '.xlsx"');
        header('Cache-Control: max-age=0');

        $writer = new \PHPExcel_Writer_Excel2007($this->_excel);
        $writer->save('php://output');

        unset($this->_excel);
    }
}