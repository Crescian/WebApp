<?php
include '../vendor/tcpdf/tcpdf.php';
include_once '../configuration/connection.php';
$PHD = $conn->db_conn_physical_security(); //* Physical Security Database connection

$firealarmid = $_GET['d'];

$sqlstringheader = "SELECT prepared_by1,prepared_by2,prepared_by3,checked_by,date_prepared,noted_by,prepared_by1_date,prepared_by2_date,prepared_by3_date,
encode(prepared_by1_sign, 'escape') AS prepared_by_signature1,
encode(prepared_by2_sign, 'escape') AS prepared_by_signature2,
encode(prepared_by3_sign, 'escape') AS prepared_by_signature3,
encode(checked_by_sign, 'escape') AS check_signature,
CASE WHEN noted_by_acknowledge = true THEN encode(noted_by_sign, 'escape') ELSE '' END AS noted_signature 
FROM phd_monthly_fire_alarm_header WHERE firealarmid = :firealarmid";

$result_stmt_header = $PHD->prepare($sqlstringheader);
$result_stmt_header->bindParam(':firealarmid', $firealarmid);
$result_stmt_header->execute();
$result_res_header = $result_stmt_header->fetchAll();
foreach ($result_res_header as $row) {
    $vsQuarterDate = date_format(date_create($row['date_prepared']), 'F d,Y');
    $performed1 = $row['prepared_by1'];
    $performed2 = $row['prepared_by2'];
    $performed3 = $row['prepared_by3'];
    $checked_by = $row['checked_by'];
    $noted_by = $row['noted_by'];
    $perform_signature1 = $row['prepared_by_signature1'];
    $perform_signature2 = $row['prepared_by_signature2'];
    $perform_signature3 = $row['prepared_by_signature3'];
    $check_signature = $row['check_signature'];
    $noted_signature = $row['noted_signature'];
    $performed1_date = $row['prepared_by1_date'];
    $performed2_date = $row['prepared_by2_date'];
    $performed3_date = $row['prepared_by3_date'];
}
$categoryArray = array();
$sqlstringcategory = "SELECT category_name,location,units,date_performed,date_prepared_units,status,remarks,prepared_by, 
encode(perpared_by_sign, 'escape') AS perpared_by_sign  FROM phd_monthly_fire_alarm_details 
WHERE firealarm_id = '" . $firealarmid . "' ORDER BY location ASC";
$result_stmt_categ = $PHD->prepare($sqlstringcategory);
$result_stmt_categ->execute();
while ($row = $result_stmt_categ->fetch(PDO::FETCH_ASSOC)) {
    $categoryArray[$row['category_name']][$row['location']][$row['units']][] = $row;
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
            $actioncode = 'X';
            break;
        case 3:
            $actioncode = 'N/A';
            break;
    }
    return $actioncode;
}
$overallcount = 1;
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
            $this->Cell(0, 0, 'MONTHLY FIRE ALARM SYSTEM CHECKLIST (MCP/BELL/ANNUNCIATOR)', 0, 0, 'C');
            $this->Ln();
            $this->Cell(0, 0, 'As of ' . date("F Y"), 0, 0, 'C');
        }
        // if ($this->PageNo() >= 2) {
        //     $this->SetFont('helvetica', '', 10);
        //     $this->Cell(0, 7, '', 0, 1, 'L');
        //     $this->Cell(0, 5, 'Monthly Fire Alarm System Checklist (Mcp/Bell/Annunciator)', 0, 0, 'L');
        // }
        if ($this->PageNo() == 2) {
            $this->SetFont('helvetica', '', 10);
            $this->Cell(0, 7, '', 0, 1, 'L');
            $this->Cell(0, 5, 'Monthly Fire Alarm System Checklist (Mcp/Bell/Annunciator)', 0, 0, 'L');
            $this->Ln();
            $html2 = '
                <table border="1" style="width:100%">
                    <tr>
                        <th style="text-align: center; width: 39.5%; font-size: 12px; vertical-align: middle;"><div style="font-size:5pt">&nbsp;</div>Location</th>
                        <th style="text-align: center; width: 15%; font-size: 12px;"><div style="font-size:5pt">&nbsp;</div>Units</th>
                        <th style="text-align: center; width: 12.5%; font-size: 12px;">Date Performed</th>
                        <th style="text-align: center; width: 10%; font-size: 12px;"><div style="font-size:5pt">&nbsp;</div>Working</th>
                        <th style="text-align: center; width: 13%; font-size: 12px;"><div style="font-size:5pt">&nbsp;</div>Remarks</th>
                        <th style="text-align: center; width: 10%; font-size: 12px;"><div style="font-size:5pt">&nbsp;</div>Sig</th>
                    </tr>
                </table>';
            $this->writeHTML($html2, true, false, false, false, '');
        }
        if ($this->PageNo() > 2) {
            $this->SetFont('helvetica', '', 10);
            $this->Cell(0, 7, '', 0, 1, 'L');
            $this->Cell(0, 5, 'Monthly Fire Alarm System Checklist (Mcp/Bell/Annunciator)', 0, 0, 'L');
        }
    }

    //* Page footer
    public function Footer()
    {
        //* Position at 26 mm from bottom
        $this->SetY(-21);
        $this->SetFont('helvetica', '', 10);
        //* Page Number
        //* Cell(w, h = 0, txt = '', border = 0, ln = 0, align = '', fill = 0, link = nil, stretch = 0, ignore_min_height = false, calign = 'T', valign = 'M')
        $this->Cell(191, '', 'PHD/IS-014-00', 0, false, 'R', 0, '', 0, false, 'T', 'M');
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
        if ($this->PageNo() <= 1) {
            //* Position at 30 mm from bottom
            $this->SetY(-30);
            $this->Cell(191, 5, '', 'T', 0, 'L', 0, '', 0, false, 'T', 'M');
        }
    }

    public function TableHeader()
    {
        $html_header = '
                <table border="1" style="width:100%" border="1">
                    <tr>
                        <th style="text-align: center; width: 39.5%; font-size: 12px; vertical-align: middle;"><div style="font-size:5pt">&nbsp;</div>Location</th>
                        <th style="text-align: center; width: 15%; font-size: 12px;"><div style="font-size:5pt">&nbsp;</div>Units</th>
                        <th style="text-align: center; width: 12.5%; font-size: 12px;">Date Performed</th>
                        <th style="text-align: center; width: 10%; font-size: 12px;"><div style="font-size:5pt">&nbsp;</div>Working</th>
                        <th style="text-align: center; width: 13%; font-size: 12px;"><div style="font-size:5pt">&nbsp;</div>Remarks</th>
                        <th style="text-align: center; width: 10%; font-size: 12px;"><div style="font-size:5pt">&nbsp;</div>Sig</th>
                    </tr>
                </table>';
        $this->writeHTML($html_header, false, false, true, false, '');
    }

    public function OutputTable($categoryArray)
    {
        $this->TableHeader();
        $annunciator = 0;
        $mcp = 0;
        $bell = 0;
        $html = '<table border="1" style="width:100%">';
        
        foreach ($categoryArray as $dailyCat => $itemcategory) { //? LOOP CATEGORY
            $removeExtraName = $dailyCat == 'Main Plant (Fire Alarm)' ? substr($dailyCat, 0, 10) : $dailyCat;
            $html .= '<tr><td colspan="5">' . $removeExtraName . '</td></tr>';

            foreach ($itemcategory as $locationname => $tablerow) { //? LOOP LOCATION

                $isFirstRow = true;
                foreach ($tablerow as $units => $prepareDetail) { //? LOOP UNITS

                    $counted = count($tablerow);
                    $html .= '<tr>';
                    if ($isFirstRow) {
                        $html .= '<td rowspan="' . $counted . '" style="width: 39.5%;"> ' . $locationname  . '</td>';
                        $isFirstRow = false;
                    }
                    $html .= '<td style="width: 15%;"> ' . $units . ' ';
                    if ($units == 'MCP') {
                        $mcp++;
                        $html .= $mcp;
                    } else if ($units == 'Bell') {
                        $bell++;
                        $html .= $bell;
                    } else if ($units == 'Annunciator') {
                        $annunciator++;
                        $html .= $annunciator;
                    }
                    $html .= '</td>';
                    foreach ($prepareDetail as $output) {
                        $status = getActionCode($output['status']);
                        $date = $output['date_prepared_units'] == NULL ? '-' : $output['date_prepared_units'];
                        $remarks = $output['remarks'] == '' ? '-' : $output['remarks'];
                        $signature = $output['perpared_by_sign'] == null ? '<img src="../vendor/images/blank.jpg" width="30" height="5">' : '<img src="data:image/jpeg;base64,' . $output['perpared_by_sign'] . '" width="30">';
                        $html .= '<td style="width: 12.5%;"> ' . $date . '</td>';
                        $html .= '<td style="width: 10%; text-align: center;"> ' . $status . '</td>';
                        $html .= '<td style="width: 13%; text-align: center;"> ' . $remarks . '</td>';
                        $html .= '<td style="width: 10%; text-align: center;">' . $signature . '</td>';
                    }
                    $html .= '</tr>';
                }
            }
        }

        $html .= '</table>';
        $this->writeHTML($html, true, false, true, false, '');
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
$pdf->Ln(39);
$pdf->SetMargins(12.5, 22.5, 12.5); //* set margins
//* -------------------------------- Info --------------------------------
$pdf->SetFont('helvetica', '', 12);
$pdf->Cell(161, 0, 'Date: ', 0, 0, 'R');
$pdf->Cell(30, 0, $vsQuarterDate, 0, 0, 'C');
$pdf->Ln(7);
$pdf->SetFont('helvetica', '', 10);
$pdf->OutputTable($categoryArray);
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
    // $pdf->Image('@' . base64_decode($perform_signature1), 16, 32, 25, 15, '', '', '', false, 300, '', false, false, 0, false, false, false);
    // $pdf->Image('@' . base64_decode($check_signature), 80, 32, 25, 15, '', '', '', false, 300, '', false, false, 0, false, false, false);
    // $pdf->Image('@' . base64_decode($noted_by), 145, 32, 25, 15, '', '', '', false, 300, '', false, false, 0, false, false, false);
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
        // $pdf->Image('@' . base64_decode($perform_signature1), 16, 32, 25, 15, '', '', '', false, 300, '', false, false, 0, false, false, false);
        // $pdf->Image('@' . base64_decode($check_signature), 80, 32, 25, 15, '', '', '', false, 300, '', false, false, 0, false, false, false);
        // $pdf->Image('@' . base64_decode($noted_by), 145, 32, 25, 15, '', '', '', false, 300, '', false, false, 0, false, false, false);
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
        // $pdf->Image('@' . base64_decode($perform_signature2), 16, 32, 25, 15, '', '', '', false, 300, '', false, false, 0, false, false, false);
        // $pdf->Image('@' . base64_decode($perform_signature3), 80, 32, 25, 15, '', '', '', false, 300, '', false, false, 0, false, false, false);
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
        // $pdf->Image('@' . base64_decode($check_signature), 16, 70, 25, 15, '', '', '', false, 300, '', false, false, 0, false, false, false);
        // $pdf->Image('@' . base64_decode($noted_signature), 80, 70, 25, 15, '', '', '', false, 300, '', false, false, 0, false, false, false);
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
            // $pdf->Image('@' . base64_decode($perform_signature1), 16, 32, 25, 15, '', '', '', false, 300, '', false, false, 0, false, false, false);
            // $pdf->Image('@' . base64_decode($perform_signature3), 80, 32, 25, 15, '', '', '', false, 300, '', false, false, 0, false, false, false);
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
            // $pdf->Image('@' . base64_decode($check_signature), 16, 70, 25, 15, '', '', '', false, 300, '', false, false, 0, false, false, false);
            // $pdf->Image('@' . base64_decode($noted_signature), 80, 70, 25, 15, '', '', '', false, 300, '', false, false, 0, false, false, false);
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
            // $pdf->Image('@' . base64_decode($perform_signature1), 16, 32, 25, 15, '', '', '', false, 300, '', false, false, 0, false, false, false);
            // $pdf->Image('@' . base64_decode($perform_signature2), 80, 32, 25, 15, '', '', '', false, 300, '', false, false, 0, false, false, false);
            // $pdf->Image('@' . base64_decode($perform_signature3), 145, 32, 25, 15, '', '', '', false, 300, '', false, false, 0, false, false, false);
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
            // $pdf->Image('@' . base64_decode($check_signature), 16, 70, 25, 15, '', '', '', false, 300, '', false, false, 0, false, false, false);
            // $pdf->Image('@' . base64_decode($noted_signature), 80, 70, 25, 15, '', '', '', false, 300, '', false, false, 0, false, false, false);
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
        // $pdf->Image('@' . base64_decode($perform_signature2), 16, 32, 25, 15, '', '', '', false, 300, '', false, false, 0, false, false, false);
        // $pdf->Image('@' . base64_decode($check_signature), 80, 32, 25, 15, '', '', '', false, 300, '', false, false, 0, false, false, false);
        // $pdf->Image('@' . base64_decode($noted_by), 140, 32, 20, 25, 15, '', '', false, 300, '', false, false, 0, false, false, false);
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
        // $pdf->Image('@' . base64_decode($perform_signature1), 16, 32, 25, 15, '', '', '', false, 300, '', false, false, 0, false, false, false);
        // $pdf->Image('@' . base64_decode($perform_signature2), 80, 32, 25, 15, '', '', '', false, 300, '', false, false, 0, false, false, false);
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
        // $pdf->Image('@' . base64_decode($check_signature), 16, 70, 25, 15, '', '', '', false, 300, '', false, false, 0, false, false, false);
        // $pdf->Image('@' . base64_decode($noted_signature), 80, 70, 25, 15, '', '', '', false, 300, '', false, false, 0, false, false, false);
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