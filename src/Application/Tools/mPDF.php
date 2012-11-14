<?php
namespace Application\Tools;

include VENDOR_PATH . '/mpdf/mpdf/mpdf.php';

class mPDF extends \mPDF
{
    var $hasOC;

    function SetFont($family,$style='',$size=0, $write=true, $forcewrite=false) {
        $return = parent::SetFont($family,$style,$size, $write, $forcewrite);
        $this->fixFonts();
        return $return;
    }

    function AddCIDFont($family,$style,$name,&$cw,$CMap,$registry,$desc) {
        $return = parent::AddCIDFont($family,$style,$name,$cw,$CMap,$registry,$desc);
        $this->fixFonts();
        return $return;
    }

    function OpenTag($tag,$attr)
    {
        $return = parent::OpenTag($tag,$attr);
        $this->fixBlk();
        return $return;
    }

    function CloseTag($tag)
    {
        $return = parent::CloseTag($tag);
        $this->fixBlk();
        return $return;
    }

    function _out($s,$ln=true) {
        $d = debug_backtrace(false, 2);
        if ($d[1]['function'] == '_tableWrite' && strpos($s, '___TABLE___BACKGROUNDS') === 0){
            for( $j = 0 ; $j < 5 ; $j++ ) { //Columns
                $this->colsums[$j] = 0;
            }
        }

        return parent::_out($s, $ln);
    }

    function fixFonts(){
        foreach ($this->fonts as &$font){
            if (!isset($font['used'])){
                $font['used'] = false;
            }
        }
    }

    function fixBlk(){
        foreach ($this->blk as &$blk){
            if (!isset($blk['direction'])){
                $blk['direction'] = 'ltr';
            }
        }
    }
}
