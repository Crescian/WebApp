<?php
include '../vendor/tcpdf/tcpdf.php';
include_once '../configuration/connection.php';
$PHD = $conn->db_conn_physical_security(); //* Physical Security Database connection

$pirid = $_GET['d'];

$sqlstringheader = "SELECT performed1,performed2,performed3,checked_by,pir_date,noted_by,performed1_date,performed2_date,performed3_date,
encode(performed1_sign, 'escape') AS perform_signature1,
encode(performed2_sign, 'escape') AS perform_signature2,
encode(performed3_sign, 'escape') AS perform_signature3,
encode(checked_by_sign, 'escape') AS check_signature,
CASE WHEN noted_by_acknowledge = true THEN encode(noted_by_sign, 'escape') ELSE '' END AS noted_signature 
FROM phd_monthly_pir_alarm_header WHERE pirid = :pirid";

$result_stmt_header = $PHD->prepare($sqlstringheader);
$result_stmt_header->bindParam(':pirid', $pirid);
$result_stmt_header->execute();
$result_res_header = $result_stmt_header->fetchAll();
foreach ($result_res_header as $row) {
    $vsQuarterDate = date_format(date_create($row['pir_date']), 'F d,Y');
    $performed1 = $row['performed1'];
    $performed2 = $row['performed2'];
    $performed3 = $row['performed3'];
    $checked_by = $row['checked_by'];
    $noted_by = $row['noted_by'];
    $perform_signature1 = $row['perform_signature1'];
    $perform_signature2 = $row['perform_signature2'];
    $perform_signature3 = $row['perform_signature3'];
    $check_signature = $row['check_signature'];
    $noted_signature = $row['noted_signature'];
    $performed1_date = $row['performed1_date'];
    $performed2_date = $row['performed2_date'];
    $performed3_date = $row['performed3_date'];
}
$itemData_List = array();
$sqlstring = "SELECT * FROM phd_monthly_pir_alarm_details WHERE pir_id = '" . $pirid . "'";
$result_stmt = $PHD->prepare($sqlstring);
$result_stmt->execute();
foreach ($result_stmt->fetchAll() as $row) {
    $itemData_List[] = $row;
}

function getActionCode($category)
{
    switch ($category) {
        case 0:
            $actioncode = '-';
            break;
        case 1:
            $actioncode = '/';
            break;
        case 2:
            $actioncode = 'N/A';
            break;
        case 3:
    }
    return $actioncode;
}
//* extend TCPF with custom functions
class MYPDF extends TCPDF
{
    //* Page header
    public function Header()
    {
        if ($this->PageNo() == 1) {
            //* Image($file, $x='', $y='', $w=0, $h=0, $type='', $link='', $align='', $resize=false, $dpi=300, $palign='', $ismask=false, $imgmask=false, $border=0, $fitbox=false, $hidden=false, $fitonpage=false)
            $this->Image('../vendor/images/Banner Logo_V1.jpg', 86, 12, 43, 11, 'JPG', '', 'T', true, 300, '', false, false, 0, false, false, false);
            $this->Image('../vendor/images/ISO Logo_V1.jpg', 159, 12, 44, 12, 'JPG', '', 'T', true, 300, '', false, false, 0, false, false, false);
            $this->Ln(14.5);
            //* Cell(w, h = 0, txt = '', border = 0, ln = 0, align = '', fill = 0, link = nil, stretch = 0, ignore_min_height = false, calign = 'T', valign = 'M')
            $this->SetFont('helvetica', '', 12);
            $this->Cell(0, 0, 'Physical Security Department', 0, 1, 'C');
            $this->SetFont('helvetica', 'B', 14);
            $this->Cell(0, 0, 'MONTHLY PIR ALARM CHECKLIST', 0, 0, 'C');
        }
        if ($this->PageNo() >= 2) {
            $this->SetFont('helvetica', '', 10);
            $this->Cell(0, 7, '', 0, 1, 'L');
            $this->Cell(0, 5, 'Monthly Pir Alarm Checklist', 0, 0, 'L');
        }
    }

    //* Page footer
    public function Footer()
    {
        //* Position at 26 mm from bottom
        $this->SetY(-22);
        $this->SetFont('helvetica', '', 10);
        //* Page Number
        //* Cell(w, h = 0, txt = '', border = 0, ln = 0, align = '', fill = 0, link = nil, stretch = 0, ignore_min_height = false, calign = 'T', valign = 'M')
        $this->Cell(191, '', 'PHD/IS-024-00', 0, false, 'R', 0, '', 0, false, 'T', 'M');
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
                        <th scope="col" style="text-align: center; font-size: 12px; width: 30%; vertical-align: middle;"><div style="font-size:5pt">&nbsp;</div>Rooms</th>
                        <th scope="col" style="text-align: center; font-size: 12px; width: 10%;">Time Activated</th>
                        <th scope="col" style="text-align: center; font-size: 12px; width: 20%;"><div style="font-size:5pt">&nbsp;</div>Motion Detected</th>
                        <th scope="col" style="text-align: center; font-size: 12px; width: 20%;"><div style="font-size:5pt">&nbsp;</div>No Motion Detected</th>
                        <th scope="col" style="text-align: center; font-size: 12px; width: 20%;"><div style="font-size:5pt">&nbsp;</div>Dual Presence</th>
                    </tr>
                </table>';
        $this->writeHTML($html_header, false, false, true, false, '');
    }

    public function OutputTable($itemData_List)
    {
        $this->TableHeader();
        $w = array(57.3, 19.1, 38.2, 38.1, 38.2); //* width
        foreach ($itemData_List as $details) {
            $num_pages = $this->getNumPages(); //* Get current number of pages.
            $this->startTransaction();
            $motion = getActionCode($details['motion_detected']);
            $no_motion = getActionCode($details['no_motion_detected']);
            $dual = getActionCode($details['dual_presence']);
            $time_activate = $details['time_activated'] == NULL ? '-' : date_format(date_create($details['time_activated']), 'Hm') . 'H';

            $this->Cell($w[0], 5, $details['location'], 1, 0, 'L', 0);
            $this->Cell($w[1], 5, $time_activate, 1, 0, 'C', 0);
            $this->Cell($w[2], 5, $no_motion, 1, 0, 'C', 0);
            $this->Cell($w[3], 5, $dual, 1, 0, 'C', 0);
            $this->Cell($w[4], 5, $motion, 1, 0, 'C', 0);
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
//* -------------------------------- Info --------------------------------
$pdf->SetFont('helvetica', '', 12);
$pdf->Cell(161, 0, 'Date: ', 0, 0, 'R');
$pdf->Cell(30, 0, $vsQuarterDate, 0, 0, 'C');
$pdf->Ln(7);
$pdf->SetFont('helvetica', '', 10);
$pdf->OutputTable($itemData_List);
// ! My code here -->
$pdf->Ln(25);
//* -------------------------------- Responsible --------------------------------
if ($performed2 == '' and $performed3 == '') {
    $pdf->SetFont('helvetica', '', 12);
    $pdf->Cell(63.6, 9, 'Prepared By:', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
    $pdf->Cell(3, 5, '', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
    $pdf->Cell(63.6, 9, 'Checked by:', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
    $pdf->Cell(3, 5, '', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
    $pdf->Cell(63.6, 9, 'Noted by:', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
    $pdf->Ln(5);
    $pdf->SetFont('helvetica', '', 10);
    // $pdf->Image('@' . base64_decode($perform_signature1), 16, 23, 25, 15, '', '', '', false, 300, '', false, false, 0, false, false, false);
    // $pdf->Image('@' . base64_decode($check_signature), 80, 23, 25, 15, '', '', '', false, 300, '', false, false, 0, false, false, false);
    // $pdf->Image('@' . base64_decode($noted_by), 145, 23, 25, 15, '', '', '', false, 300, '', false, false, 0, false, false, false);
    $pdf->Ln(15);
    $pdf->Cell(63.5, 5, $performed1, 0, 0, 'L', 0, '', 0, false, 'T', 'M');
    $pdf->Cell(3, 5, '', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
    $pdf->Cell(63.6, 5, $checked_by, 0, 0, 'L', 0, '', 0, false, 'T', 'M');
    $pdf->Cell(3, 5, '', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
    $pdf->Cell(63.6, 5, $noted_by, 0, 0, 'L', 0, '', 0, false, 'T', 'M');
    $pdf->Ln();
    $pdf->Cell(63.8, 5, 'Internal Security Staff', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
    $pdf->Cell(3, 5, '', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
    $pdf->Cell(63.6, 5, 'Internal Security Staff', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
    $pdf->Cell(3, 5, '', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
    $pdf->Cell(63.6, 5, 'VP for Physical Security', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
    $pdf->Ln();
} else if ($performed3 != '' and $performed2 != '') {
    if ($performed1 == $performed3) {
        $pdf->SetFont('helvetica', '', 12);
        $pdf->Cell(63.6, 9, 'Prepared By:', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
        $pdf->Cell(3, 5, '', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
        $pdf->Cell(63.6, 9, 'Checked by:', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
        $pdf->Cell(3, 5, '', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
        $pdf->Cell(63.6, 9, 'Noted by:', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
        $pdf->Ln(5);
        $pdf->SetFont('helvetica', '', 10);
        // $pdf->Image('@' . base64_decode($perform_signature1), 16, 23, 25, 15, '', '', '', false, 300, '', false, false, 0, false, false, false);
        // $pdf->Image('@' . base64_decode($check_signature), 80, 23, 25, 15, '', '', '', false, 300, '', false, false, 0, false, false, false);
        // $pdf->Image('@' . base64_decode($noted_by), 145, 23, 25, 15, '', '', '', false, 300, '', false, false, 0, false, false, false);
        $pdf->Ln(15);
        $pdf->Cell(63.6, 5, $performed3, 0, 0, 'L', 0, '', 0, false, 'T', 'M');
        $pdf->Cell(3, 5, '', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
        $pdf->Cell(63.6, 5, $checked_by, 0, 0, 'L', 0, '', 0, false, 'T', 'M');
        $pdf->Cell(3, 5, '', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
        $pdf->Cell(63.6, 5, $noted_by, 0, 0, 'L', 0, '', 0, false, 'T', 'M');
        $pdf->Ln();
        $pdf->Cell(63.6, 5, 'Internal Security Staff', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
        $pdf->Cell(3, 5, '', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
        $pdf->Cell(63.6, 5, 'Internal Security Staff', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
        $pdf->Cell(3, 5, '', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
        $pdf->Cell(63.6, 5, 'VP for Physical Security', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
        $pdf->Ln();
        $pdf->Cell(66.5, 4, 'Date: ' . date_format(date_create($performed3_date), 'm-d-y'), 0, 0, 'L', 0, '', 0, false, 'T', 'M');
    } else if ($performed1 == $performed2) {
        $pdf->SetFont('helvetica', '', 12);
        $pdf->Cell(63.6, 9, 'Prepared By:', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
        $pdf->Ln(5);
        $pdf->SetFont('helvetica', '', 10);
        // $pdf->Image('@' . base64_decode($perform_signature2), 16, 23, 25, 15, '', '', '', false, 300, '', false, false, 0, false, false, false);
        // $pdf->Image('@' . base64_decode($perform_signature3), 80, 23, 25, 15, '', '', '', false, 300, '', false, false, 0, false, false, false);
        $pdf->Ln(15);
        $pdf->Cell(63.5, 5, $performed2, 0, 0, 'L', 0, '', 0, false, 'T', 'M');
        $pdf->Cell(3, 5, '', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
        $pdf->Cell(63.5, 5, $performed3, 0, 0, 'L', 0, '', 0, false, 'T', 'M');
        $pdf->Cell(3, 5, '', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
        $pdf->Ln();
        $pdf->Cell(63.5, 5, 'Internal Security Staff', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
        $pdf->Cell(3, 5, '', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
        $pdf->Cell(63.5, 5, 'Internal Security Staff', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
        $pdf->Cell(3, 5, '', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
        $pdf->Ln();
        $pdf->Cell(66.5, 4, 'Date: ' . date_format(date_create($performed2_date), 'm-d-y'), 0, 0, 'L', 0, '', 0, false, 'T', 'M');
        $pdf->Cell(66.5, 4, 'Date: ' . date_format(date_create($performed3_date), 'm-d-y'), 0, 0, 'L', 0, '', 0, false, 'T', 'M');


        $pdf->Ln(10);
        $pdf->SetFont('helvetica', '', 12);
        $pdf->Cell(63.6, 9, 'Checked by:', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
        $pdf->Cell(3, 5, '', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
        $pdf->Cell(63.6, 9, 'Noted by:', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
        $pdf->Ln(5);
        // $pdf->Image('@' . base64_decode($check_signature), 16, 60, 25, 15, '', '', '', false, 300, '', false, false, 0, false, false, false);
        // $pdf->Image('@' . base64_decode($noted_signature), 80, 60, 25, 15, '', '', '', false, 300, '', false, false, 0, false, false, false);
        $pdf->Ln(15);
        $pdf->SetFont('helvetica', '', 10);
        $pdf->Cell(63.5, 5, $checked_by, 0, 0, 'L', 0, '', 0, false, 'T', 'M');
        $pdf->Cell(3, 5, '', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
        $pdf->Cell(63.5, 5, $noted_by, 0, 0, 'L', 0, '', 0, false, 'T', 'M');
        $pdf->Ln();
        $pdf->Cell(63.5, 5, 'Internal Security Staff', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
        $pdf->Cell(3, 5, '', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
        $pdf->Cell(63.5, 5, 'VP for Physical Security', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
    } else {
        if ($performed2 == $performed3) {
            $pdf->SetFont('helvetica', '', 12);
            $pdf->Cell(63.6, 9, 'Prepared By:', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
            $pdf->Ln(5);
            $pdf->SetFont('helvetica', '', 10);
            // $pdf->Image('@' . base64_decode($perform_signature1), 16, 23, 25, 15, '', '', '', false, 300, '', false, false, 0, false, false, false);
            // $pdf->Image('@' . base64_decode($perform_signature3), 80, 23, 25, 15, '', '', '', false, 300, '', false, false, 0, false, false, false);
            $pdf->Ln(15);
            $pdf->Cell(63.5, 5, $performed1, 0, 0, 'L', 0, '', 0, false, 'T', 'M');
            $pdf->Cell(3, 5, '', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
            $pdf->Cell(63.5, 5, $performed3, 0, 0, 'L', 0, '', 0, false, 'T', 'M');
            $pdf->Ln();
            $pdf->Cell(63.5, 5, 'Internal Security Staff', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
            $pdf->Cell(3, 5, '', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
            $pdf->Cell(63.5, 5, 'Internal Security Staff', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
            $pdf->Ln();
            $pdf->Cell(66.5, 4, 'Date: ' . date_format(date_create($performed1_date), 'm-d-y'), 0, 0, 'L', 0, '', 0, false, 'T', 'M');
            $pdf->Cell(66.5, 4, 'Date: ' . date_format(date_create($performed3_date), 'm-d-y'), 0, 0, 'L', 0, '', 0, false, 'T', 'M');

            $pdf->Ln(10);
            $pdf->SetFont('helvetica', '', 12);
            $pdf->Cell(63.6, 9, 'Checked by:', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
            $pdf->Cell(3, 5, '', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
            $pdf->Cell(63.6, 9, 'Noted by:', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
            $pdf->Ln(5);
            // $pdf->Image('@' . base64_decode($check_signature), 16, 60, 25, 15, '', '', '', false, 300, '', false, false, 0, false, false, false);
            // $pdf->Image('@' . base64_decode($noted_signature), 80, 60, 25, 15, '', '', '', false, 300, '', false, false, 0, false, false, false);
            $pdf->Ln(15);
            $pdf->SetFont('helvetica', '', 10);
            $pdf->Cell(63.5, 5, $checked_by, 0, 0, 'L', 0, '', 0, false, 'T', 'M');
            $pdf->Cell(3, 5, '', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
            $pdf->Cell(63.5, 5, $noted_by, 0, 0, 'L', 0, '', 0, false, 'T', 'M');
            $pdf->Ln();
            $pdf->Cell(63.5, 5, 'Internal Security Staff', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
            $pdf->Cell(3, 5, '', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
            $pdf->Cell(63.5, 5, 'VP for Physical Security', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
        } else {
            $pdf->SetFont('helvetica', '', 12);
            $pdf->Cell(63.6, 9, 'Prepared By:', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
            $pdf->Ln(5);
            $pdf->SetFont('helvetica', '', 10);
            // $pdf->Image('@' . base64_decode($perform_signature1), 16, 23, 25, 15, '', '', '', false, 300, '', false, false, 0, false, false, false);
            // $pdf->Image('@' . base64_decode($perform_signature2), 80, 23, 25, 15, '', '', '', false, 300, '', false, false, 0, false, false, false);
            // $pdf->Image('@' . base64_decode($perform_signature3), 145, 23, 25, 15, '', '', '', false, 300, '', false, false, 0, false, false, false);
            $pdf->Ln(15);
            $pdf->Cell(63.5, 5, $performed1, 0, 0, 'L', 0, '', 0, false, 'T', 'M');
            $pdf->Cell(3, 5, '', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
            $pdf->Cell(63.5, 5, $performed2, 0, 0, 'L', 0, '', 0, false, 'T', 'M');
            $pdf->Cell(3, 5, '', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
            $pdf->Cell(63.5, 5, $performed3, 0, 0, 'L', 0, '', 0, false, 'T', 'M');
            $pdf->Ln();
            $pdf->Cell(63.5, 5, 'Internal Security Staff', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
            $pdf->Cell(3, 5, '', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
            $pdf->Cell(63.5, 5, 'Internal Security Staff', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
            $pdf->Cell(3, 5, '', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
            $pdf->Cell(63.5, 5, 'Internal Security Staff', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
            $pdf->Ln();
            $pdf->Cell(66.5, 4, '', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
            $pdf->Cell(66.5, 4, 'Date: ' . date_format(date_create($performed2_date), 'm-d-y'), 0, 0, 'L', 0, '', 0, false, 'T', 'M');
            $pdf->Cell(66.5, 4, 'Date: ' . date_format(date_create($performed3_date), 'm-d-y'), 0, 0, 'L', 0, '', 0, false, 'T', 'M');

            $pdf->Ln(10);
            $pdf->SetFont('helvetica', '', 12);
            $pdf->Cell(63.6, 9, 'Checked by:', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
            $pdf->Cell(3, 5, '', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
            $pdf->Cell(63.6, 9, 'Noted by:', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
            $pdf->Ln(5);
            // $pdf->Image('@' . base64_decode($check_signature), 16, 60, 25, 15, '', '', '', false, 300, '', false, false, 0, false, false, false);
            // $pdf->Image('@' . base64_decode($noted_signature), 80, 60, 25, 15, '', '', '', false, 300, '', false, false, 0, false, false, false);
            $pdf->Ln(15);
            $pdf->SetFont('helvetica', '', 10);
            $pdf->Cell(63.5, 5, $checked_by, 0, 0, 'L', 0, '', 0, false, 'T', 'M');
            $pdf->Cell(3, 5, '', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
            $pdf->Cell(63.5, 5, $noted_by, 0, 0, 'L', 0, '', 0, false, 'T', 'M');
            $pdf->Ln();
            $pdf->Cell(63.5, 5, 'Internal Security Staff', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
            $pdf->Cell(3, 5, '', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
            $pdf->Cell(63.5, 5, 'VP for Physical Security', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
        }
    }
} else if ($performed2 != '') {
    if ($performed1 == $performed2) {
        $pdf->SetFont('helvetica', '', 12);
        $pdf->Cell(63.6, 9, 'Prepared By:', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
        $pdf->Cell(3, 5, '', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
        $pdf->Cell(63.6, 9, 'Checked by:', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
        $pdf->Cell(3, 5, '', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
        $pdf->Cell(63.6, 9, 'Noted by:', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
        $pdf->Ln(5);
        $pdf->SetFont('helvetica', '', 10);
        // $pdf->Image('@' . base64_decode($perform_signature2), 16, 23, 25, 15, '', '', '', false, 300, '', false, false, 0, false, false, false);
        // $pdf->Image('@' . base64_decode($check_signature), 80, 23, 25, 15, '', '', '', false, 300, '', false, false, 0, false, false, false);
        // $pdf->Image('@' . base64_decode($noted_by), 140, 23, 20, 25, 15, '', '', false, 300, '', false, false, 0, false, false, false);
        $pdf->Ln(15);
        $pdf->Cell(63.5, 5, $performed2, 0, 0, 'L', 0, '', 0, false, 'T', 'M');
        $pdf->Cell(3, 5, '', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
        $pdf->Cell(63.6, 5, $checked_by, 0, 0, 'L', 0, '', 0, false, 'T', 'M');
        $pdf->Cell(3, 5, '', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
        $pdf->Cell(63.6, 5, $noted_by, 0, 0, 'L', 0, '', 0, false, 'T', 'M');
        $pdf->Ln();
        $pdf->Cell(38.8, 5, 'Internal Security Staff', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
        $pdf->Cell(3, 5, '', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
        $pdf->Cell(63.6, 5, 'Internal Security Staff', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
        $pdf->Cell(3, 5, '', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
        $pdf->Cell(63.6, 5, 'VP for Physical Security', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
        $pdf->Ln();
        $pdf->Cell(66.5, 4, 'Date: ' . date_format(date_create($performed2_date), 'm-d-y'), 0, 0, 'L', 0, '', 0, false, 'T', 'M');
    } else {
        $pdf->SetFont('helvetica', '', 12);
        $pdf->Cell(63.6, 9, 'Prepared By:', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
        $pdf->Ln(5);
        $pdf->SetFont('helvetica', '', 10);
        // $pdf->Image('@' . base64_decode($perform_signature1), 16, 23, 25, 15, '', '', '', false, 300, '', false, false, 0, false, false, false);
        // $pdf->Image('@' . base64_decode($perform_signature2), 80, 23, 25, 15, '', '', '', false, 300, '', false, false, 0, false, false, false);
        $pdf->Ln(15);
        $pdf->Cell(63.5, 5, $performed1, 0, 0, 'L', 0, '', 0, false, 'T', 'M');
        $pdf->Cell(3, 5, '', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
        $pdf->Cell(63.5, 5, $performed2, 0, 0, 'L', 0, '', 0, false, 'T', 'M');
        $pdf->Ln();
        $pdf->Cell(63.5, 5, 'Internal Security Staff', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
        $pdf->Cell(3, 5, '', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
        $pdf->Cell(63.5, 5, 'Internal Security Staff', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
        $pdf->Ln();
        $pdf->Cell(66.5, 4, '', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
        $pdf->Cell(66.5, 4, 'Date: ' . date_format(date_create($performed2_date), 'm-d-y'), 0, 0, 'L', 0, '', 0, false, 'T', 'M');

        $pdf->Ln(10);
        $pdf->SetFont('helvetica', '', 12);
        $pdf->Cell(63.6, 9, 'Checked by:', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
        $pdf->Cell(3, 5, '', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
        $pdf->Cell(63.6, 9, 'Noted by:', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
        $pdf->Ln(5);
        // $pdf->Image('@' . base64_decode($check_signature), 16, 60, 25, 15, '', '', '', false, 300, '', false, false, 0, false, false, false);
        // $pdf->Image('@' . base64_decode($noted_signature), 80, 60, 25, 15, '', '', '', false, 300, '', false, false, 0, false, false, false);
        $pdf->Ln(15);
        $pdf->SetFont('helvetica', '', 10);
        $pdf->Cell(63.5, 5, $checked_by, 0, 0, 'L', 0, '', 0, false, 'T', 'M');
        $pdf->Cell(3, 5, '', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
        $pdf->Cell(63.5, 5, $noted_by, 0, 0, 'L', 0, '', 0, false, 'T', 'M');
        $pdf->Ln();
        $pdf->Cell(63.5, 5, 'Internal Security Staff', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
        $pdf->Cell(3, 5, '', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
        $pdf->Cell(63.5, 5, 'VP for Physical Security', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
    }
}
// ! end
$filename = "Daily Room Inspection Checklist.pdf";
$pdf->Output($filename, 'I'); //* Close and output PDF document