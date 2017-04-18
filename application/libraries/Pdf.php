<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Creates PDF document using TCPDF
 * @author Mukund Topiwala
 */

require_once dirname(__FILE__) . '/tcpdf/tcpdf.php';

class Pdf extends TCPDF
{
    function __construct()
    {
        parent::__construct();
    }

    
    //Page header
    public function Header() {
        $image_file = K_PATH_IMAGES.'cplogo.png';
      	$this->Image($image_file, 10, 10, 30, '', 'PNG', '', 'T', false, 300, '', false, false, 0, false, false, false);
        $this->SetFont('helvetica', 'B', 12);
        $this->Cell(0, 15, K_HEADER_TXT, 0, false, 'R', 0, '', 0, false, 'M', 'M');
    }

/*
    // Page footer
    public function Footer() {
        // Position at 15 mm from bottom
        $this->SetY(-15);
        // Set font
        $this->SetFont('helvetica', 'I', 8);
        // Page number
        $this->Cell(0, 10, 'Page '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
    }    

  */  
}

/* End of file Pdf.php */
/* Location: ./application/libraries/Pdf.php */
