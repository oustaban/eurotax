<?php

namespace Application\Sonata\ClientOperationsBundle\Export;


class Excel
{
    private $_excel;
    private $_client;
    private $_config_excel;
    private $_file_name;
    private $translator;
    private $_sheet;

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

            $this->_sheet->fromArray($this->fromArray($params));

            $toCol = $this->_sheet->getColumnDimension($this->_sheet->getHighestColumn())->getColumnIndex();
            $toCol++;
            for ($k = 'A'; $k !== $toCol; $k++) {
                $this->_sheet->getColumnDimension($k)->setAutoSize(true);
            }
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
        foreach ($params['fields'] as $field) {

            $value = call_user_func(array($row, $this->getMethod($field)));

            if ($value instanceof \Application\Sonata\ClientBundle\Entity\ListDevises) {
                $ceil[] = $value->getAlias();
            } elseif ($value instanceof \DateTime) {
                //excel time
                $date = \PHPExcel_Shared_Date::PHPToExcel($value);
                if ($field == 'mois') {
                    $this->_sheet->getStyle($k . $inc)->getNumberFormat()->setFormatCode('MM-YY');
                } else {
                    $this->_sheet->getStyle($k . $inc)->getNumberFormat()->setFormatCode('dd.mm.YYYY');
                }
                $ceil[] = $date > 0 ? $date : '';

            } else {
                $ceil[] = (double)$value;
            }

            $k++;
        }
        return $ceil;
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
        if ($params['skip_line'] > 1) {
            for ($i = 1; $i < $params['skip_line']; $i++) {
                $rows[] = $this->getSkipLine($params);
            }
        }

        //header
        $rows[] = $this->headers($params);

        $count = count($rows) + 1;

        //default result
        foreach ($result as $key => $row) {
            $rows[] = $this->getCell($key + $count, $row, $params);
        }

        $rows[] = $this->getTotal($result, $params);

        return $rows;
    }

    /**
     * @param $result
     * @param $params
     * @return array
     */
    protected function getTotal($result, $params)
    {

        $ceil = array();
        return $ceil;
    }


    /**
     * @param $params
     * @return array
     */
    protected function headers($params)
    {

        $col = array();
        foreach ($params['fields'] as $field) {
            $col[] = $this->translator->trans('ApplicationSonataClientOperationsBundle.list.' . $params['entity'] . '.' . $field);
        }
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