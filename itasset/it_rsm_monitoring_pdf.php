<?php
session_start();
include '../vendor/tcpdf/tcpdf.php';
include_once '../configuration/connection.php';

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
function getJobPosition($employee, $php_fetch_bannerweb_api)
{
    $sql_pos_name = "SELECT pos_name FROM prl_employee 
        INNER JOIN prl_position ON prl_position.pos_code = prl_employee.pos_code
        WHERE (emp_fn || ' ' || emp_sn) = '{$employee}'";
    $data_result = sqlQuery($sql_pos_name, $php_fetch_bannerweb_api);
    foreach ($data_result['data'] as $row) {
        return $row['pos_name'];
    }
}
$month = $_GET['month'];
//* -------------------------------- Header --------------------------------
$actionArray = array();
$sqlstring = "SELECT rsmheader.rsmnumber,rsmdetail.code,rsmdetail.description,rsmquantity,rsmdetail.purchasemeasure,rsmdetail.remarks 
                    FROM rsmdetail LEFT JOIN prdetail ON prdetail.rsmno = rsmdetail.rsmnumber AND rsmdetail.code = prdetail.code
                    LEFT JOIN podetail ON podetail.prnumber = prdetail.prnumber AND podetail.code = prdetail.code 
                    LEFT JOIN rsmheader ON rsmheader.rsmnumber = rsmdetail.rsmnumber 
                    WHERE department = 'Information Technology' AND TO_CHAR(dateprepared, 'YYYY-MM') > '{$month}'
                    ORDER BY dateprepared DESC";
// $result_stmt = $WHPO->prepare($sqlstring);
// $result_stmt->execute();
// while ($row = $result_stmt->fetch(PDO::FETCH_ASSOC)) {
$data_result = sqlQuery($sqlstring, $php_fetch_whpo_api);

foreach ($data_result['data'] as $row) {
    $actionArray[] = $row;
}

//* extend TCPF with custom functions
class MYPDF extends TCPDF
{
    //* Page header
    public function Header()
    {
        $month = $_GET['month'];
        if ($this->PageNo() == 1) {
            //* Image($file, $x='', $y='', $w=0, $h=0, $type='', $link='', $align='', $resize=false, $dpi=300, $palign='', $ismask=false, $imgmask=false, $border=0, $fitbox=false, $hidden=false, $fitonpage=false)
            $this->Image('../vendor/images/Banner Logo-black.jpg', 86, 12, 42.5, 11, 'JPG', '', 'T', true, 300, '', false, false, 0, false, false, false);
            $this->Image('../vendor/images/ISO Logo-black.jpg', 159, 12, 43, 12.7, 'JPG', '', 'T', true, 300, '', false, false, 0, false, false, false);
            $this->Ln(15);
            //* Cell(w, h = 0, txt = '', border = 0, ln = 0, align = '', fill = 0, link = nil, stretch = 0, ignore_min_height = false, calign = 'T', valign = 'M')
            $this->SetFont('helvetica', '', 12);
            $this->Cell(0, 0, 'Information Technology Department', 0, 1, 'C');
            $this->SetFont('helvetica', 'B', 14);
            $this->Cell(0, 0, 'RSM MONITORING REPORT', 0, 1, 'C');
            $this->Cell(0, 0, strtoupper(date('F Y', strtotime($month))), 0, 0, 'C');
        }
        if ($this->PageNo() >= 2) {
            $this->SetFont('helvetica', '', 10);
            $this->Cell(0, 7, '', 0, 1, 'L');
            $this->Cell(0, 5, 'Rsm Monitoring Report', 0, 0, 'L');
        }
    }

    //* Page footer
    public function Footer()
    {
        //* Position at 26 mm from bottom
        $this->SetY(-17);
        $this->SetFont('helvetica', '', 10);
        //* Page Number
        //* Cell(w, h = 0, txt = '', border = 0, ln = 0, align = '', fill = 0, link = nil, stretch = 0, ignore_min_height = false, calign = 'T', valign = 'M')
        // $this->Cell(191, '', 'PHD/IS-023-00', 0, false, 'R', 0, '', 0, false, 'T', 'M');
        $this->Ln(4);
        //* Page Number
        //TODO Fix Alignment if its only single page
        if ($this->PageNo() <= 1) {
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
                        <td style="text-align: center; width: 15%; font-size: 12px;"><div style="font-size:6pt">&nbsp;</div>RSM NO.</td>
                        <td style="text-align: center; width: 20%; font-size: 12px;"><div style="font-size:6pt">&nbsp;</div>Code</td>
                        <td style="text-align: center; width: 20%; font-size: 12px;"><div style="font-size:6pt">&nbsp;</div>Description</td>
                        <td style="text-align: center; width: 10%; font-size: 12px;">RSM QTY</td>
                        <td style="text-align: center; width: 15%; font-size: 12px;">Purchase Measure</td>
                        <td style="text-align: center; width: 20%; font-size: 12px;"><div style="font-size:6pt">&nbsp;</div>REMARKS</td>
                    </tr>
                </table>';
        $this->writeHTML($html_header, false, false, true, false, '');
    }
    public function OutputTable($actionArray)
    {
        $this->SetFont('helvetica', '', 10);
        $this->TableHeader();
        $w = array(28.65, 38.15, 38.15, 19.1, 28.7, 38.2);
        foreach ($actionArray as $details) {
            $num_pages = $this->getNumPages(); //* Get current number of pages.
            $this->startTransaction();
            $rsmnumberHeight = $this->GetStringHeight($w[0], $details['rsmnumber']);
            $codeHeight = $this->GetStringHeight($w[1], $details['code']);
            $descriptionHeight = $this->GetStringHeight($w[2], $details['description']);
            $rsmquantityHeight = $this->GetStringHeight($w[3], $details['rsmquantity']);
            $purchasemeasureHeight = $this->GetStringHeight($w[4], $details['purchasemeasure']);
            $remarksHeight = $this->GetStringHeight($w[5], $details['remarks']);
            $cellHeight = max($rsmnumberHeight, $codeHeight, $descriptionHeight, $rsmquantityHeight, $purchasemeasureHeight, $remarksHeight);
            $this->MultiCell($w[0], $cellHeight, $details['rsmnumber'], 1, 'L', 0, 0);
            $this->MultiCell($w[1], $cellHeight, $details['code'] == '' ? '-' : $details['code'], 1, 'L', 0, 0);
            $this->MultiCell($w[2], $cellHeight, $details['description'], 1, 'L', 0, 0);
            $this->MultiCell($w[3], $cellHeight, $details['rsmquantity'], 1, 'C', 0, 0);
            $this->MultiCell($w[4], $cellHeight, $details['purchasemeasure'], 1, 'C', 0, 0);
            $this->MultiCell($w[5], $cellHeight, $details['remarks'], 1, 'L', 0, 0);
            $this->Ln();
            if ($num_pages < $this->getNumPages()) {
                $this->rollbackTransaction(true); //* Undo adding the row.
                //* Add page
                $this->SetMargins(12.5, 12, 12.5);
                $this->AddPage();
                //* Draw the header.
                $this->TableHeader();
                // * Re-do the row.
            } else {
                $this->commitTransaction(); //* Otherwise we are fine with this row, discard undo history.
            }
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
$pdf->SetAutoPageBreak(TRUE, 20); //* set page break
$pdf->AddPage();
$pdf->Ln(33);
//* -------------------------------- Info --------------------------------
$pdf->Ln(5);
$pdf->OutputTable($actionArray);
//* -------------------------------- Designation --------------------------------
$pdf->Ln(50);
$pdf->SetFont('helvetica', '', 12);
$pdf->Cell(63.6, 9, 'Prepared By:', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
$pdf->Cell(3, 5, '', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
$pdf->Cell(63.6, 9, 'Approved by:', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
$pdf->Cell(3, 5, '', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
$pdf->Cell(63.6, 9, 'Noted by:', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
$pdf->Ln(5);

$pdf->SetFont('helvetica', '', 10);
$pdf->Ln(15);
$pdf->Cell(63.5, 5, $_SESSION['fullname'], 0, 0, 'L', 0, '', 0, false, 'T', 'M');
$pdf->Cell(3, 5, '', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
$pdf->Cell(63.6, 5, 'Oliver S. Razalan', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
$pdf->Cell(3, 5, '', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
$pdf->Cell(63.6, 5, 'Oliver S. Razalan', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
$pdf->Ln();
$pdf->Cell(63.6, 5, getJobPosition($_SESSION['fullname'], $php_fetch_bannerweb_api), 0, 0, 'L', 0, '', 0, false, 'T', 'M');
$pdf->Cell(3, 5, '', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
$pdf->MultiCell(63.6, 10, 'VP for IT', 0, 'L', 0, 0);
$pdf->Cell(3, 5, '', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
$pdf->Cell(63.6, 5, 'VP for IT', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
$filename = "RSM Monitoring.pdf";
$pdf->Output($filename, 'I'); //* Close and output PDF document