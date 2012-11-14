<?php
namespace Application\Tools;

include VENDOR_PATH . '/mpdf/mpdf/mpdf.php';

class mPDF extends \mPDF
{
    var $hasOC;
}
