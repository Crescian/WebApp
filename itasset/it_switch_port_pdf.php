<?php
session_start();
include '../vendor/tcpdf/tcpdf.php';
include_once '../configuration/connection.php';
$actionArray = array();
$sqlstringAction = "SELECT lan_cable,location,switch,port FROM tblit_switch_module_assign_switch";
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
function getJobPosition($php_fetch_bannerweb_api, $name)
{
    $sqlstring = "SELECT pos_name FROM prl_employee
            INNER JOIN prl_position ON prl_employee.pos_code = prl_position.pos_code
            WHERE CONCAT(emp_fn, ' ', emp_sn) = '{$name}'";
    $data_result = sqlQuery($sqlstring, $php_fetch_bannerweb_api);
    foreach ($data_result['data'] as $row) {
        return $row['pos_name'];
    }
}
//* extend TCPF with custom functions
class MYPDF extends TCPDF
{
    public function Header() //* Page header
    {
        if ($this->PageNo() == 1) {
            //* Image($file, $x='', $y='', $w=0, $h=0, $type='', $link='', $align='', $resize=false, $dpi=300, $palign='', $ismask=false, $imgmask=false, $border=0, $fitbox=false, $hidden=false, $fitonpage=false)
            $this->Image('../vendor/images/Banner Logo-black.jpg', 86, 12, 43, 11, 'JPG', '', 'T', true, 300, '', false, false, 0, false, false, false);
            $this->Image('../vendor/images/ISO Logo-black.jpg', 159, 12, 44, 12, 'JPG', '', 'T', true, 300, '', false, false, 0, false, false, false);
            $this->Ln(14.5);
            //* Cell(w, h = 0, txt = '', border = 0, ln = 0, align = '', fill = 0, link = nil, stretch = 0, ignore_min_height = false, calign = 'T', valign = 'M')
            $this->SetFont('helvetica', '', 12);
            $this->Cell(0, 0, 'Information Technology Department', 0, 1, 'C');
            $this->SetFont('helvetica', 'B', 14);
            $this->Cell(124, 0, 'SWITCH PORT REPORT', 0, 0, 'R');
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
                        <td style="text-align: center; width: 24.9%; font-size: 12px;">Lan Cable</td>
                        <td style="text-align: center; width: 32.2%; font-size: 12px;">Location</td>
                        <td style="text-align: center; width: 21.2%; font-size: 12px;">Switch</td>
                        <td style="text-align: center; width: 21.2%; font-size: 12px;">Port</td>
                    </tr>
                </table>';
        $this->writeHTML($html_header, false, false, true, false, '');
    }
    public function OutputTable($actionArray)
    {
        $this->SetFont('helvetica', '', 10);
        $this->TableHeader();
        $w = array(47.5, 61.5, 40.5, 40.5);
        foreach ($actionArray as $details) {
            $lan_cableHeight = $this->GetStringHeight($w[0], $details['lan_cable']);
            $locationHeight = $this->GetStringHeight($w[1], $details['location']);
            $switchHeight = $this->GetStringHeight($w[2], $details['switch']);
            $portHeight = $this->GetStringHeight($w[3], $details['port']);
            $cellHeight = max($lan_cableHeight, $locationHeight, $switchHeight, $portHeight);
            $this->MultiCell($w[0], $cellHeight, $details['lan_cable'], 1, 'L', 0, 0);
            $this->MultiCell($w[1], $cellHeight, $details['location'], 1, 'L', 0, 0);
            $this->MultiCell($w[2], $cellHeight, $details['switch'], 1, 'C', 0, 0);
            $this->MultiCell($w[3], $cellHeight, $details['port'], 1, 'C', 0, 0);
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
$pdf->Cell(63.5, 5, $_SESSION['fullname'], 0, 0, 'L', 0, '', 0, false, 'T', 'M');
$pdf->Cell(3, 5, '', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
$pdf->Cell(63.6, 5, 'Oliver Razalan', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
$pdf->Cell(3, 5, '', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
$pdf->Cell(63.6, 5, 'Oliver Razalan', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
$pdf->Ln();
$pdf->Cell(63.6, 5, getJobPosition($php_fetch_bannerweb_api, $_SESSION['fullname']), 0, 0, 'L', 0, '', 0, false, 'T', 'M');
$pdf->Cell(3, 5, '', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
$pdf->Cell(63.6, 5, 'VP for Information Technology', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
$pdf->Cell(3, 5, '', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
$pdf->Cell(63.6, 5, 'VP for Information Technology', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
$filename = "It Hardware Issued.pdf";

$pdf->Output($filename, 'I'); //* Close and output PDF document