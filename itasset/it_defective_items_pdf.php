<?php
include '../vendor/tcpdf/tcpdf.php';
include_once '../configuration/connection.php';
$actionArray = array();
$sqlstringAction = "SELECT status, description, date_issued, issued_by, item ,barcode
        FROM tblit_hardware_issuance_employee
        WHERE status = 'Defective'
        UNION
        SELECT status, description, date_issued, issued_by, item ,barcode
        FROM tblit_hardware_issuance_machine
        WHERE status = 'Defective'";
$data_result = sqlQuery($sqlstringAction, $php_fetch_itasset_api);
foreach ($data_result['data'] as $row) {
    $actionArray[] = $row;
}
function sqlQuery($sqlstring, $connection)
{
    $data_base64 = base64_encode($sqlstring);
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $connection);
    curl_setopt($curl, CURLOPT_HEADER, false);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, array("data" => $data_base64));
    $json_response = curl_exec($curl);
    //* ====== Close Connection ======
    curl_close($curl);
    return json_decode($json_response, true);
}
//* extend TCPF with custom functions
class MYPDF extends TCPDF
{
    public function Header() //* Page header
    {
        $control_no = $_GET['control_no'];
        if ($this->PageNo() == 1) {
            //* Image($file, $x='', $y='', $w=0, $h=0, $type='', $link='', $align='', $resize=false, $dpi=300, $palign='', $ismask=false, $imgmask=false, $border=0, $fitbox=false, $hidden=false, $fitonpage=false)
            $this->Image('../vendor/images/Banner Logo-colored.jpg', 86, 12, 43, 11, 'JPG', '', 'T', true, 300, '', false, false, 0, false, false, false);
            $this->Image('../vendor/images/ISO Logo-colored.jpg', 159, 12, 44, 12, 'JPG', '', 'T', true, 300, '', false, false, 0, false, false, false);
            $this->Ln(14.5);
            //* Cell(w, h = 0, txt = '', border = 0, ln = 0, align = '', fill = 0, link = nil, stretch = 0, ignore_min_height = false, calign = 'T', valign = 'M')
            $this->SetFont('helvetica', '', 12);
            $this->Cell(0, 0, 'Information Technology Department', 0, 1, 'C');
            $this->SetFont('helvetica', 'B', 14);
            $this->Cell(142.5, 0, 'IT DEFECTIVE ITEMS TRANSFER FORM', 0, 0, 'R');
            $this->SetFont('helvetica', '', 12);
            $this->Cell(20.5, 0, 'No.', 0, 0, 'R', 0, '', 0, FALSE, '', 'B');
            $this->SetFont('helvetica', 'B', 12);
            $this->Cell(28.5, 0, $control_no, 0, 0, 'R', 0, '', 0, FALSE, '', 'B');
        }
        if ($this->PageNo() >= 2) {
            $this->SetFont('helvetica', '', 10);
            $this->Cell(0, 7, '', 0, 1, 'L');
            $this->Cell(0, 5, 'Daily Room Inspection Checklist', 0, 0, 'L');
        }
    }
    public function Footer() //* Page footer
    {
        //* Position at 26 mm from bottom
        $this->SetY(-20);
        $this->SetFont('helvetica', '', 10);
        // ! My code here -->
        // Get the current page number
        $current_page = $this->getPage();
        //* Cell(w, h = 0, txt = '', border = 0, ln = 0, align = '', fill = 0, link = nil, stretch = 0, ignore_min_height = false, calign = 'T', valign = 'M')
        $this->Cell(191, '', 'ITD/XX-007-00', 0, false, 'R', 0, '', 0, false, 'T', 'M');
        $this->Ln(4);
        // ! end
        //* Page Number
        //TODO Fix Alignment if its only single page
        if ($this->PageNo() > 1) {
            $this->SetY(-16);
            $this->Cell(205, '', 'Page ' . $this->getAliasNumPage() . ' of ' . $this->getAliasNbPages(), 0, false, 'R', 0, '', 0, false, '', ''); //* Last number is Double digit
        }
        if ($this->PageNo() <= 9) {
            $this->Cell(205, '', 'Page ' . $this->getAliasNumPage() . ' of ' . $this->getAliasNbPages(), 0, false, 'R', 0, '', 0, false, '', ''); //* Last number is Double digit
        }
        if ($this->PageNo() >= 10) {
            $this->Cell(202, '', 'Page ' . $this->getAliasNumPage() . ' of ' . $this->getAliasNbPages(), 0, false, 'R', 0, '', 0, false, '', ''); //* Both number is double digit
        }
    }
    public function TableHeader()
    {
        $html_header = '
                <table cellspacing="0" cellpadding="0.2" border=".1" style="width:100%">
                    <tr>
                        <td style="text-align: center; width: 49.7%; font-size: 12px;">Barcode</td>
                        <td style="text-align: center; width: 49.8%; font-size: 12px;">Description</td>
                    </tr>
                </table>';
        $this->writeHTML($html_header, false, false, true, false, '');
    }
    public function OutputTable($actionArray)
    {
        $this->SetFont('helvetica', '', 10);
        $this->TableHeader();
        $w = array(95, 95);
        foreach ($actionArray as $details) {
            $barcodeHeight = $this->GetStringHeight($w[0], $details['barcode']);
            $descriptionHeight = $this->GetStringHeight($w[1], $details['description']);
            $cellHeight = max($descriptionHeight, $barcodeHeight);
            $this->MultiCell($w[0], $cellHeight, $details['barcode'], 1, 'L', 0, 0);
            $this->MultiCell($w[1], $cellHeight, $details['description'], 1, 'L', 0, 0);
            $this->Ln();
        }
        $this->Cell(array_sum($w), 0, '', 'T');
        $this->Ln();
    }
}
//* create new PDF document
$pdf = new MYPDF("P", PDF_UNIT, "LETTER", true, 'UTF-8', false);
//* remove default header/footer
//* $pdf->setPrintHeader(false);
//*  $pdf->setPrintFooter(false);
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED); //* set default monospaced font
//*  $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT); // set margins
$pdf->SetMargins(12.5, 12, 12.5); //* set margins
$pdf->SetAutoPageBreak(TRUE, 30); //* set page break
$pdf->AddPage();
$pdf->Ln(33);

// !== info here
$pdf->Cell(20, 0, 'From:', 0, 0, 'L:');
$pdf->Cell(120, 0, 'IT Department', 0, 0, 'L:');
$pdf->Cell(0, 0, 'Date: September 07, 2023', 0, 1, 'L:');
$pdf->Cell(20, 0, 'To:', 0, 0, 'L:');
$pdf->Cell(0, 0, 'Materials Support & Maintenance Department', 0, 1, 'L:');
$pdf->Cell(20, 0, 'Subject:', 0, 0, 'L:');
$pdf->Cell(0, 0, 'Unrepairable IT items - For Disposal', 0, 0, 'L:');
$pdf->Ln(3);
$pdf->Cell(0, 1, '', 'B', 0, 'L');
$pdf->Ln(7);
// $pdf->Cell(95.5, 1, 'Item', 0, 0, 'C');
// $pdf->Cell(0, 1, 'Control No.', 0, 0, 'C');
$pdf->OutputTable($actionArray);
$pdf->SetY(217);
$pdf->SetFont('helvetica', '', 12);
$pdf->Cell(63.6, 9, 'Prepared By:', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
$pdf->Cell(3, 5, '', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
$pdf->Cell(63.6, 9, 'Noted by:', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
$pdf->Cell(3, 5, '', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
$pdf->Cell(63.6, 9, 'Received by:', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
$pdf->Ln(5);
$pdf->SetFont('helvetica', '', 10);
$pdf->Ln(15);
$pdf->Cell(63.5, 5, 'Crescian Lloyd Lanoy', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
$pdf->Cell(3, 5, '', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
$pdf->Cell(63.6, 5, 'Oliver Razalan', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
$pdf->Cell(3, 5, '', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
$pdf->Cell(63.6, 5, 'Jhon Paul Abinales', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
$pdf->Ln();
$pdf->Cell(63.6, 5, 'Internal Security Staff', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
$pdf->Cell(3, 5, '', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
// $pdf->Cell(63, 5, 'Vice President for Information Technology', 1, 'L', 0, 0);
$pdf->Cell(63.6, 5, 'Vice President for Information Technology', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
$pdf->Cell(3, 5, '', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
$pdf->Cell(63.6, 5, 'Warehouse Supervisor', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
$filename = "It Hardware Issued.pdf";

$pdf->Output($filename, 'I'); //* Close and output PDF document