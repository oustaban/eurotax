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
