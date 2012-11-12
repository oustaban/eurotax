<?php

namespace Application\Sonata\DashboardBundle\Export;

use Application\Sonata\ClientBundle\Entity\Garantie;
use Application\Sonata\ClientBundle\Entity\Client;

class Excel
{
    protected $_sheet;
    protected $_file;
    protected $_lastwColumn;
    protected $_headerHeight = 19.5;
    protected $_tabs_title = 'Suivi GB';

    protected $_headers = array(
        'client' => array(
            'title' => "Client",
            'width' => 13.14,
            'aligment' => \PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
        ),
        'date_decheance' => array(
            'title' => "Echéance",
            'aligment' => \PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
            'format' => 'dd.mm.YYYY',
        ),
        'montant' => array(
            'title' => "Montant",
            'aligment' => \PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
            'format' => '# ##0\ €',
        ),
        'nom_de_la_banques_id' => array(
            'title' => "Banque",
            'width' => 37.86,
            'aligment' => \PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
        ),
        'num_de_ganrantie' => array(
            'title' => "Numéro",
            'width' => 15,
            'aligment' => \PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
        ),
        'note' => array(
            'title' => "Commentaire",
            'width' => 67.43,
            'aligment' => \PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
        ),
        'client_date_fin_mission' => array(
            'title' => "Fin de mission",
            'width' => 13.43,
            'aligment' => \PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
            'format' => 'dd.mm.YY',
        ),
        'calc_day_date_decheance' => array(
            'title' => "Nb de jour",
            'aligment' => \PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
            'format' => '# ##0.00;[Red](###0.00)',
        )
    );

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

    public function __construct()
    {
        $this->file = 'Suivi GB - ' . date('d-m-Y') . '.xlsx';
    }

    /**
     *
     */
    public function render()
    {
        $excel = new \PHPExcel();
        $excel->getDefaultStyle()->getFont()->setName('Arial');
        $excel->getDefaultStyle()->getFont()->setSize(10);

        $this->_sheet = $excel->getActiveSheet();

        $this->_sheet->setTitle($this->_tabs_title);
        $this->_sheet->getTabColor()->setARGB('FFc3e59e');

        $this->_sheet->fromArray($this->makeExportData());

        //resize
        $this->excelWidth();
        //height header

        header('Content-Type: application/excel');
        header('Content-Disposition: attachment; filename="' . $this->getFile() . '"');
        header('Cache-Control: max-age=0');

        $writer = new \PHPExcel_Writer_Excel2007($excel);
        $writer->save('php://output');
    }

    /**
     * @return string
     */
    protected function getFile()
    {
        return $this->file;
    }

    /**
     * @return array
     */
    protected function excelHeader()
    {
        $cell = array();
        $wColumn = 'A';
        $wRow = 1;
        foreach ($this->_headers as $field => $value) {
            $cell[$field] = $value['title'];
            $this->_sheet->getStyle($wColumn . $wRow)->applyFromArray($this->_styleBorders + array(
                'font' => array(
                    'bold' => true,
                )));

            $this->_lastwColumn = $wColumn++;
        }

        $this->_sheet->getRowDimension($wRow)->setRowHeight($this->_headerHeight);
        // valign & center
        $this->_sheet->getStyle('A' . $wRow . ':' . $this->_lastwColumn . $wRow)->getAlignment()
            ->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER)
            ->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        return $cell;

    }

    /**
     * @return array
     */
    protected function makeExportData()
    {
        $rows = array();
        $rows[] = $this->excelHeader();

        /** @var $em \Doctrine\ORM\EntityManager */
        $em = \AppKernel::getStaticContainer()->get('doctrine');

        /** @var $query \Doctrine\ORM\QueryBuilder */
        $object = $em->getRepository('ApplicationSonataClientBundle:Garantie')
            ->createQueryBuilder('g')
            ->orderBy('g.date_decheance', 'ASC')
            ->getQuery()
            ->execute();

        $nom_de_la_banques = Garantie::getNomDeLaBanques();

        foreach ($object as $key => $row) {

            $date_decheance = $row->getDateDecheance() ? \PHPExcel_Shared_Date::PHPToExcel($row->getDateDecheance()) : '';

            /** @var $row Garantie */
            $cell = array(
                'client' => (string)$row->getClient(),
                'date_decheance' => $date_decheance,
                'montant' => $row->getMontant(),
                'nom_de_la_banques_id' => isset($nom_de_la_banques[$row->getNomDeLaBanquesId()]) ? $nom_de_la_banques[$row->getNomDeLaBanquesId()] : '',
                'num_de_ganrantie' => $row->getNumDeGanrantie(),
                'note' => $row->getNote(),
                'client_date_fin_mission' => $row->getClient()->getDateFinMission() ? \PHPExcel_Shared_Date::PHPToExcel($row->getClient()->getDateFinMission()) : '',
                'calc_day_date_decheance' => $date_decheance ? '=B' . ($key + 2) . '-TODAY()' : '',
            );

            //format
            $this->excelCellFormat($key + 2);
            $rows[] = $cell;
        }

        return $rows;
    }

    /**
     * @param $wRow
     */
    protected function excelCellFormat($wRow)
    {
        $this->_sheet->getRowDimension($wRow)->setRowHeight($this->_headerHeight);

        $wColumn = 'A';
        foreach ($this->_headers as $value) {

            $this->_sheet->getStyle($wColumn . $wRow)->applyFromArray($this->_styleBorders);
            $this->_sheet->getStyle($wColumn . $wRow)->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);

            if (isset($value['aligment'])) {
                $this->_sheet->getStyle($wColumn . $wRow)->getAlignment()->setHorizontal($value['aligment']);
            }

            if (isset($value['format'])) {
                $this->_sheet->getStyle($wColumn . $wRow)->getNumberFormat()->setFormatCode($value['format']);
            }

            $wColumn++;
        }
    }

    /**
     *
     */
    protected function excelWidth()
    {
        $wColumn = 'A';
        foreach ($this->_headers as $value) {
            if (isset($value['width'])) {
                $this->_sheet->getColumnDimension($wColumn)->setWidth($value['width']);
            } else {
                $this->_sheet->getColumnDimension($wColumn)->setAutoSize(true);
            }
            $wColumn++;
        }
    }
}
