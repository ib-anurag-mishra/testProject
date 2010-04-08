<?php
App::import('Vendor','tcpdf/tcpdf');

class XTCPDF  extends TCPDF
{

    var $xheadertext  = 'Libraries Download Report';
    var $xheadercolor = array(255);
    var $xfootertext  = 'Copyright © %d FreegalMusic.com. All rights reserved.';
    var $xfooterfont  = PDF_FONT_NAME_MAIN ;
    var $xfooterfontsize = 8 ;


    /**
    * Overwrites the default header
    * set the text in the view using
    *    $fpdf->xheadertext = 'YOUR ORGANIZATION';
    * set the fill color in the view using
    *    $fpdf->xheadercolor = array(0,0,100); (r, g, b)
    * set the font in the view using
    *    $fpdf->setHeaderFont(array('YourFont','',fontsize));
    */
    function Header()
    {

        list($r, $b, $g) = $this->xheadercolor;
        $this->setY(0); // shouldn't be needed due to page margin, but helas, otherwise it's at the page top
        $this->SetFillColor($r, $b, $g);
        $this->SetTextColor(0 , 0, 0);
        $this->Cell(0,20, '', 0,1,'C', 1);
        $this->Text(0,5,$this->xheadertext, false, false, true, 0, 0, 'C', 0, '', 0, false, 'T', 'M');
    }

    /**
    * Overwrites the default footer
    * set the text in the view using
    * $fpdf->xfootertext = 'Copyright © %d YOUR ORGANIZATION. All rights reserved.';
    */
    function Footer()
    {
        $year = date('Y');
        $footertext = sprintf($this->xfootertext, $year);
        $this->SetY(-20);
        $this->SetTextColor(0, 0, 0);
        $this->SetFont($this->xfooterfont,'',$this->xfooterfontsize);
        $this->Cell(0,8, $footertext,'T',1,'C');
    }
}
?>