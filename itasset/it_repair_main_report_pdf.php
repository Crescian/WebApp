<?php
include_once '../configuration/connection.php';
include '../vendor/tcpdf/tcpdf.php';
session_start();
date_default_timezone_set('Asia/Manila');

$month = $_GET['month'];

$itemData_List = array();
$sqlstring = "SELECT date_requested,prepared_by,item,remarks,location,datetime_accomplish,datetime_repair,priority,repaired_by,TO_CHAR(datetime_accomplish, 'YYYY-MM') 
                AS month_year,duration FROM tblit_repair
                WHERE TO_CHAR(datetime_accomplish, 'YYYY-MM') = '{$month}' ORDER BY repair_id ASC";
$data_base64 = base64_encode($sqlstring);
$curl = curl_init();
curl_setopt($curl, CURLOPT_URL, $php_fetch_it_repair_api);
curl_setopt($curl, CURLOPT_HEADER, false);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_POST, true);
curl_setopt($curl, CURLOPT_POSTFIELDS, array("data" => $data_base64));
$json_response = curl_exec($curl);
//* ====== Close Connection ======
curl_close($curl);
//* ======== Prepare Array ========
$data_result =  json_decode($json_response, true);
foreach ($data_result['data'] as $row) {
    $itemData_List[] = $row;
}

function getJobPosition($db, $name)
{
    $sqlstring = "SELECT pos_name FROM prl_employee
        INNER JOIN prl_position ON prl_employee.pos_code = prl_position.pos_code
        WHERE CONCAT(emp_fn, ' ', emp_sn) = '{$name}'";
    $data_base64 = base64_encode($sqlstring);
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $db);
    curl_setopt($curl, CURLOPT_HEADER, false);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, array("data" => $data_base64));
    $json_response = curl_exec($curl);
    //* ====== Close Connection ======
    curl_close($curl);
    //* ======== Prepare Array ========
    $data_result =  json_decode($json_response, true);
    foreach ($data_result['data'] as $row) {
        return $row['pos_name'];
    }
}
function getDeptHead($php_fetch_bannerweb_api)
{
    $sqlstring = "SELECT CONCAT(emp_fn,' ',emp_mi,'. ',emp_sn ) AS fullname, pos_code FROM prl_employee WHERE pos_code IN('VPO', 'VPI', 'PRS')";
    $data_base64 = base64_encode($sqlstring);
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $php_fetch_bannerweb_api);
    curl_setopt($curl, CURLOPT_HEADER, false);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, array("data" => $data_base64));
    $json_response = curl_exec($curl);
    //* ====== Close Connection ======
    curl_close($curl);
    //* ======== Prepare Array ========
    $data_result =  json_decode($json_response, true);
    foreach ($data_result['data'] as $row) {
        $data[$row['pos_code']] = $row['fullname'];
    }
    return $data;
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
            $this->Image('../vendor/images/Banner Logo-black.jpg', 145, 12.7, 39, 10.5, 'JPG', '', 'T', true, 300, '', false, false, 0, false, false, false);
            $this->Image('../vendor/images/ISO Logo-black.jpg', 276, 12.7, 40.5, 12, 'JPG', '', 'T', true, 300, '', false, false, 0, false, false, false);
            $this->Ln(14.5);
            //* Cell(w, h = 0, txt = '', border = 0, ln = 0, align = '', fill = 0, link = nil, stretch = 0, ignore_min_height = false, calign = 'T', valign = 'M')
            $this->SetFont('helvetica', '', 12);
            $this->Cell(0, 0, 'Information Technology Department', 0, 1, 'C');
            $this->SetFont('helvetica', 'B', 14);
            $this->Cell(0, 0, 'SUPPORT HARDWARE/SOFTWARE ISSUES', 0, 1, 'C');
            $this->Cell(0, 0, strtoupper(date('F Y', strtotime($month))), 0, 0, 'C');
        }
        if ($this->PageNo() >= 2) {
            $this->SetFont('helvetica', '', 10);
            $this->Cell(0, 7, '', 0, 1, 'L');
            if ($this->PageNo() == 5) {
                $this->Cell(304.5, 5, 'Support Hardware/Software Issues - ' . date('F Y', strtotime($month)), 0, 0, 'L');
            } else {
                $this->Cell(304.5, 5, 'Support Hardware/Software Issues - ' . date('F Y', strtotime($month)), 0, 0, 'L');
            }
            $this->Ln(2);
        }
        if ($this->PageNo() >= 1.5) {
            $this->Ln();
            if ($this->PageNo() >= 11) {
            } else {
                $this->SetFont('helvetica', '', 12);
                $html_header =
                    '<table cellspacing="0" cellpadding="0.2" border="1" style="width:100%">
                    <tr>
                        <th align="center" width="8.1%">Date/Time Requested</th>
                        <th align="center" width="11.1%"><div style="font-size:5pt">&nbsp;</div>Requested By</th>
                        <th align="center" width="11.1%"><div style="font-size:5pt">&nbsp;</div>Affected Node</th>
                        <th align="center" width="37.15%"><div style="font-size:5pt">&nbsp;</div>Remarks</th>
                        <th align="center" width="8.1%"><div style="font-size:5pt">&nbsp;</div>Finish</th>
                        <th align="center" width="6.4%"><div style="font-size:5pt">&nbsp;</div>Duration</th>
                        <th align="center" width="5.7%"><div style="font-size:5pt">&nbsp;</div>Priority</th>
                        <th align="center" width="12.5%">Assigned Technician</th>
                    </tr>
                </table>';
                $this->writeHTML($html_header, false, false, true, false, '');
            }
        }
    }

    //* Page footer
    public function Footer()
    {
        //* Position at 26 mm from bottom
        $this->SetY(-21.5);
        $this->SetFont('helvetica', '', 10);
        //* Cell(w, h = 0, txt = '', border = 0, ln = 0, align = '', fill = 0, link = nil, stretch = 0, ignore_min_height = false, calign = 'T', valign = 'M')
        // $this->Cell(305, '', 'ISD/XX-036-00', 0, 0, 'R', 0, '', 0, false, '', '');
        $this->Ln(4);
        //* Page Number
        $numPages = $this->getNumPages();
        if ($numPages < $this->getNumPages()) {
            $this->Cell(320, '', '', 0, 0, 'R', 0, '', 0, false, '', '');
        } else {
            if ($this->PageNo() >= 1 && $this->PageNo() <= 9) {
                $this->Cell(320, '', 'Page ' . $this->getAliasNumPage() . ' of ' . $this->getAliasNbPages(), 0, false, 'R', 0, '', 0, false, '', ''); //* Last number is Double digit
            }
            if ($this->PageNo() >= 10) {
                $this->Cell(316, '', 'Page ' . $this->getAliasNumPage() . ' of ' . $this->getAliasNbPages(), 0, false, 'R', 0, '', 0, false, '', ''); //* Both number is Double digit
            }
            
            if ($this->PageNo() >= 1) {
                //* Position at 30 mm from bottom
                $this->SetY(-19);
                if ($this->PageNo() >= 20) {
                    $this->Cell(305.5, 5, '', 0, 0, 'L', 0, '', 0, false, '', 'M');
                } else {
                    $this->Cell(305.5, 5, '', 'T', 0, 'L', 0, '', 0, false, 'T', 'M');
                }
                // $this->Cell(205, '', 'Page ' . $this->getAliasNumPage() . ' of ' . $this->getAliasNbPages(), 0, false, 'R', 0, '', 0, false, '', ''); //* Last number is Double digit
            }
        }
    }

    public function TableHeader()
    {
        $this->Ln(2);
        $this->SetFont('helvetica', '', 12);
        $html_header = '
                <table cellspacing="0" cellpadding="0.2" border="1" style="width:100%">
                    <tr>
                        <th align="center" width="8.1%">Date/Time Requested</th>
                        <th align="center" width="11.1%"><div style="font-size:5pt">&nbsp;</div>Requested By</th>
                        <th align="center" width="11.1%"><div style="font-size:5pt">&nbsp;</div>Affected Node</th>
                        <th align="center" width="37.15%"><div style="font-size:5pt">&nbsp;</div>Remarks</th>
                        <th align="center" width="8.1%"><div style="font-size:5pt">&nbsp;</div>Finish</th>
                        <th align="center" width="6.4%"><div style="font-size:5pt">&nbsp;</div>Duration</th>
                        <th align="center" width="5.7%"><div style="font-size:5pt">&nbsp;</div>Priority</th>
                        <th align="center" width="12.5%">Assigned Technician</th>
                    </tr>
                </table>';
        $this->writeHTML($html_header, false, false, true, false, '');
    }

    public function OutputTable($itemData_List)
    {
        $this->TableHeader();
        $this->SetFont('helvetica', '', 10);
        // $w = array(45, 45, 55, 120, 19.1, 10, 10, 10, 10); //* width
        // $w = array(40, 45, 55, 120, 19.1, 10, 10, 10, 10); //* width
        $html = '<table style="width:100%;" border="1" cellspacing="0" cellpadding="2">';
        foreach ($itemData_List as $details) {
            $html .= '<tr>';
            // $this->MultiCell($w[0], 5, date('m-d-y h:i A', strtotime($details['date_requested'])), 1, 'L', 0, 0, '', '', true, 1, false, true, 0, 'T');
            // $this->MultiCell($w[1], 5, $details['prepared_by'], 1, 'L', 0, 1, '', '', true, 0, false, true, 0, 'T');

            // $this->Cell($w[0], 5, date('m-d-y h:i A', strtotime($details['date_requested'])), 1, 0, 'L', 0);
            // $this->Cell($w[1], 5, $details['prepared_by'], 1, 0, 'L', 0);
            // $this->Cell($w[2], 5, $details['item'], 1, 0, 'L', 0);
            // $this->Cell($w[3], 5, $details['remarks'], 1, 0, 'L', 0);
            // $this->Cell($w[4], 5, $details['location'], 1, 0, 'L', 0);
            // $this->Cell($w[5], 5, date('m-d-y h:i A', strtotime($details['datetime_accomplish'])), 1, 0, 'L', 0);
            // $this->Cell($w[6], 5, getDuration($details['datetime_repair'], $details['datetime_accomplish']), 1, 0, 'L', 0);
            // $this->Cell($w[7], 5, $details['priority'], 1, 0, 'L', 0);
            // $this->Cell($w[8], 5, $details['repaired_by'], 1, 0, 'L', 0);
            // $this->Ln();

            $html .= '<td style="width: 8.1%; text-align: center;">' . date('m-d-y h:i A', strtotime($details['date_requested'])) . '</td>';
            $html .= '<td style="width: 11.1%;">' . $details['prepared_by'] . '</td>';
            $html .= '<td style="width: 11.1%;">' . $details['item'] . '</td>';
            $html .= '<td style="width: 37.15%;">' . $details['remarks'] . '</td>';
            $html .= '<td style="width: 8.1%; text-align: center;">' . date('m-d-y h:i A', strtotime($details['datetime_accomplish'])) . '</td>';
            $html .= '<td style="width: 6.4%; text-align: center;">' . $details['duration'] . '</td>';
            $html .= '<td style="width: 5.7%; text-align: center;">' . $details['priority'] . '</td>';
            $html .= '<td style="width: 12.5%;">' . $details['repaired_by'] . '</td>';
            $html .= '</tr>';
        }
        $html .= '</table>';
        $this->writeHTML($html, true, false, true, false, '');
    }
}

//* create new PDF document
$pdf = new MYPDF("L", PDF_UNIT, "LONG", true, 'UTF-8', false); //* create new PDF document
//* remove default header/footer
//* $pdf->setPrintHeader(false);
//*  $pdf->setPrintFooter(false);
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED); //* set default monospaced font
//*  $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT); // set margins
$pdf->SetMargins(12.5, 12, 12.5); //* set margins
$pdf->SetAutoPageBreak(TRUE, 19); //* set page break
$pdf->AddPage();
$pdf->SetMargins(12.5, 24.6, 12.5); //* set margins
$pdf->SetFont('helvetica', 'B', 14);
$pdf->Ln(39.5);
$pdf->SetFont('helvetica', '', 11);
$pdf->OutputTable($itemData_List);
$pdf->Ln(0);
$pdf->Cell(306, 0, '*****Nothing Follows*****', 0, 0, 'C');
$pdf->Ln(30);
//* ---------- Respondents ----------
$pdf->SetFont('helvetica', '', 12);
$pdf->Cell(76.5, 9, 'Prepared By:', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
$pdf->Cell(76.5, 9, 'Checked By:', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
$pdf->Cell(76.5, 9, 'Approved By:', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
$pdf->Cell(76.5, 9, 'Noted By:', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
$pdf->Ln(10);
//* -------------------------------- Signature --------------------------------
// $pdf->Image('@' . base64_decode($prepared_by_sign), 10, '', 30, 20, '', '', '', false, 300, '', false, false, 0, false, false, false);
// $pdf->Image('@' . base64_decode($checked_by_sign), 110, '', 40, 20, '', '', '', false, 300, '', false, false, 0, false, false, false);
// $pdf->Image('@' . base64_decode($noted_by_sign), 215, '', 40, 20, '', '', '', false, 300, '', false, false, 0, false, false, false);
$pdf->Ln(10);
//* -------------------------------- Responsible --------------------------------
$pdf->SetFont('helvetica', '', 10);
$pdf->Cell(76.5, 5, $_SESSION['fullname'], 0, 0, 'L', 0, '', 0, false, 'T', 'M');
$pdf->Cell(76.5, 5, getDeptHead($php_fetch_bannerweb_api)['VPI'], 0, 0, 'L', 0, '', 0, false, 'T', 'M');
$pdf->Cell(76.5, 5, getDeptHead($php_fetch_bannerweb_api)['VPO'], 0, 0, 'L', 0, '', 0, false, 'T', 'M');
$pdf->Cell(76.5, 5, getDeptHead($php_fetch_bannerweb_api)['PRS'], 0, 0, 'L', 0, '', 0, false, 'T', 'M');
$pdf->Ln();
//* -------------------------------- Job Position --------------------------------
$pdf->Cell(76.5, 5, getJobPosition($php_fetch_bannerweb_api, $_SESSION['fullname']), 0, 0, 'L', 0, '', 0, false, 'T', 'M');
$pdf->Cell(76.5, 5, 'VP for IT', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
$pdf->Cell(76.5, 5, 'EVP Operations', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
$pdf->Cell(76.5, 5, 'President', 0, 0, 'L', 0, '', 0, false, 'T', 'M');


$pdf->Output('IT_Repair_Request_Report.pdf', 'I'); //* Close and output PDF document