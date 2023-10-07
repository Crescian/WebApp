<?php
include_once '../configuration/connection.php';
include '../vendor/tcpdf/tcpdf.php';
session_start();
date_default_timezone_set('Asia/Manila');
$ITR = $conn->db_conn_it_repair_request(); //* IT REPAIR AND REQUEST Database connection

$month = $_GET['month'];
$requestType = $_GET['requestType'];

$itemData_List = array();
$sqlstring = "SELECT TO_CHAR(prepared_by_date, 'MM/DD/YYYY') AS prepared_by_date,queue_number,prepared_by,item,purpose,repaired_by,repaired_by_date,date_needed,TO_CHAR(date_needed, 'MM/DD/YYYY') AS target_date,status, remarks
FROM tblit_request WHERE TO_CHAR(prepared_by_date, 'YYYY-MM') = '{$month}' AND request_type = '{$requestType}' ORDER BY queue_number ASC";
// $data_base64 = base64_encode($sqlstring);
// $curl = curl_init();
// curl_setopt($curl, CURLOPT_URL, $php_fetch_it_repair_api);
// curl_setopt($curl, CURLOPT_HEADER, false);
// curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
// curl_setopt($curl, CURLOPT_POST, true);
// curl_setopt($curl, CURLOPT_POSTFIELDS, array("data" => $data_base64));
// $json_response = curl_exec($curl);
// //* ====== Close Connection ======
// curl_close($curl);
// //* ======== Prepare Array ========
// $data_result =  json_decode($json_response, true);
// foreach ($data_result['data'] as $row) {
// $result_stmt = $ITR->prepare($sqlstring);
// $result_stmt->execute();
// $result_res = $result_stmt->fetchAll();
// foreach ($result_res as $row) {
$data_result = sqlQuery($sqlstring, $php_fetch_it_repair_api);
foreach ($data_result['data'] as $row) {
    $itemData_List[] = $row;
}
// }
$itemData_ListNotDone = array();
$sqlstringNotDone = "SELECT TO_CHAR(prepared_by_date, 'MM/DD/YYYY') AS prepared_by_date,queue_number,prepared_by,item,purpose,repaired_by,repaired_by_date,date_needed,TO_CHAR(date_needed, 'MM/DD/YYYY') AS target_date,status, remarks
FROM tblit_request WHERE TO_CHAR(prepared_by_date, 'YYYY-MM') <> '{$month}' AND request_type = '{$requestType}' AND status IN ('Pending','Ongoing') ORDER BY queue_number ASC";
// $data_base64 = base64_encode($sqlstringNotDone);
// $curl = curl_init();
// curl_setopt($curl, CURLOPT_URL, $php_fetch_it_repair_api);
// curl_setopt($curl, CURLOPT_HEADER, false);
// curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
// curl_setopt($curl, CURLOPT_POST, true);
// curl_setopt($curl, CURLOPT_POSTFIELDS, array("data" => $data_base64));
// $json_response = curl_exec($curl);
// //* ====== Close Connection ======
// curl_close($curl);
// //* ======== Prepare Array ========
// $data_result =  json_decode($json_response, true);
// foreach ($data_result['data'] as $row) {
// $result_stmt_not = $ITR->prepare($sqlstringNotDone);
// $result_stmt_not->execute();
// $result_res_not = $result_stmt_not->fetchAll();
// foreach ($result_res_not as $row) {
$data_result = sqlQuery($sqlstringNotDone, $php_fetch_it_repair_api);
foreach ($data_result['data'] as $row) {
    $itemData_ListNotDone[] = $row;
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
        $requestType = $_GET['requestType'];
        if ($this->PageNo() == 1) {
            //* Image($file, $x='', $y='', $w=0, $h=0, $type='', $link='', $align='', $resize=false, $dpi=300, $palign='', $ismask=false, $imgmask=false, $border=0, $fitbox=false, $hidden=false, $fitonpage=false)
            $this->Image('../vendor/images/Banner Logo-black.jpg', 145, 12.7, 39, 10.5, 'JPG', '', 'T', true, 300, '', false, false, 0, false, false, false);
            $this->Image('../vendor/images/ISO Logo-black.jpg', 276, 12.7, 40.5, 12, 'JPG', '', 'T', true, 300, '', false, false, 0, false, false, false);
            $this->Ln(14.5);
            //* Cell(w, h = 0, txt = '', border = 0, ln = 0, align = '', fill = 0, link = nil, stretch = 0, ignore_min_height = false, calign = 'T', valign = 'M')
            $this->SetFont('helvetica', '', 12);
            $this->Cell(0, 0, 'Information Technology Department', 0, 1, 'C');
            $this->SetFont('helvetica', 'B', 14);
            if ($requestType == 'Hardware') {
                $this->Cell(0, 0, 'HARDWARE REQUEST', 0, 1, 'C');
                $this->Cell(0, 0, strtoupper(date('F Y', strtotime($month))), 0, 0, 'C');
            } else {
                $this->Cell(0, 0, 'SOFTWARE DEVELOPMENT AND SUPPORT REQUEST', 0, 1, 'C');
                $this->Cell(0, 0, strtoupper(date('F Y', strtotime($month))), 0, 0, 'C');
            }
        }
        if ($this->PageNo() >= 2) {
            $this->SetFont('helvetica', '', 10);
            $this->Cell(0, 7, '', 0, 1, 'L');
            if ($this->PageNo() == 5) {
                if ($requestType == 'Hardware') {
                    $this->Cell(304.5, 5, 'Hardware Request - ' . date('F Y', strtotime($month)), 0, 0, 'L');
                } else {
                    $this->Cell(304.5, 5, 'Software Development and Support Request - ' . date('F Y', strtotime($month)), 0, 0, 'L');
                }
            } else {
                if ($requestType == 'Hardware') {
                    $this->Cell(304.5, 5, 'Hardware Request - ' . date('F Y', strtotime($month)), 0, 0, 'L');
                } else {
                    $this->Cell(304.5, 5, 'Software Development and Support Request - ' . date('F Y', strtotime($month)), 0, 0, 'L');
                }
            }
            $this->Ln(2);
            if ($requestType == 'Hardware') {
                if ($this->PageNo() >= 1) {
                    $this->Ln();
                    if ($this->PageNo() >= 1) {
                    } else {
                        $this->SetFont('helvetica', '', 12);
                        $html_header = '
                            <table cellspacing="0" cellpadding="0.2" border="1" style="width:100%">
                                <tr>
                                    <th align="center" width="10%">Date Requested</th>
                                    <th align="center" width="10%"><div style="font-size:5pt">&nbsp;</div>Control No.</th>
                                    <th align="center" width="12%"><div style="font-size:5pt">&nbsp;</div>Requested By</th>
                                    <th align="center" width="12%"><div style="font-size:5pt">&nbsp;</div>Item Requested</th>
                                    <th align="center" width="20%"><div style="font-size:5pt">&nbsp;</div>Purpose</th>
                                    <th align="center" width="10%">Target Date of Completion</th>
                                    <th align="center" width="10%">Actual Date of Completion</th>
                                    <th align="center" width="16%"><div style="font-size:5pt">&nbsp;</div>Remarks</th>
                                </tr>
                            </table>';
                        $this->writeHTML($html_header, false, false, true, false, '');
                    }
                }
            } else {
                if ($this->PageNo() >= 1) {
                    $this->Ln();
                    if ($this->PageNo() >= 3) {
                    } else {
                        $this->SetFont('helvetica', '', 12);
                        $html_header = '
                <table cellspacing="0" cellpadding="0.2" border="1" style="width:100%">
                    <tr>
                        <th align="center" width="9%">Date Requested</th>
                        <th align="center" width="9%"><div style="font-size:5pt">&nbsp;</div>Control No.</th>
                        <th align="center" width="14%"><div style="font-size:5pt">&nbsp;</div>Requested By</th>
                        <th align="center" width="12%"><div style="font-size:5pt">&nbsp;</div>Item Requested</th>
                        <th align="center" width="18%"><div style="font-size:5pt">&nbsp;</div>Purpose</th>
                        <th align="center" width="9%">Assigned Technician</th>
                        <th align="center" width="10%">Target Date of Completion</th>
                        <th align="center" width="10%">Actual Date of Completion</th>
                        <th align="center" width="9%"><div style="font-size:5pt">&nbsp;</div>Remarks</th>
                    </tr>
                </table>';
                        $this->writeHTML($html_header, false, false, true, false, '');
                    }
                }
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
            $this->SetFont('helvetica', '', 10);
            if ($this->PageNo() >= 1 && $this->PageNo() <= 9) {
                $this->Cell(320, '', 'Page ' . $this->getAliasNumPage() . ' of ' . $this->getAliasNbPages(), 0, false, 'R', 0, '', 0, false, '', ''); //* Last number is Double digit
            }
            if ($this->PageNo() >= 10) {
                $this->Cell(316, '', 'Page ' . $this->getAliasNumPage() . ' of ' . $this->getAliasNbPages(), 0, false, 'R', 0, '', 0, false, '', ''); //* Both number is Double digit
            }
        }
    }

    public function TableHeader()
    {
        $requestType = $_GET['requestType'];
        $this->SetFont('helvetica', '', 12);
        $this->Ln(2);
        if ($requestType == 'Hardware') {
            $html_header = '
                <table cellspacing="0" cellpadding="0.2" border="1" style="width:100%">
                    <tr>
                        <th align="center" width="10%">Date Requested</th>
                        <th align="center" width="10%"><div style="font-size:5pt">&nbsp;</div>Control No.</th>
                        <th align="center" width="12%"><div style="font-size:5pt">&nbsp;</div>Requested By</th>
                        <th align="center" width="12%"><div style="font-size:5pt">&nbsp;</div>Item Requested</th>
                        <th align="center" width="20%"><div style="font-size:5pt">&nbsp;</div>Purpose</th>
                        <th align="center" width="10%">Target Date of Completion</th>
                        <th align="center" width="10%">Actual Date of Completion</th>
                        <th align="center" width="16%"><div style="font-size:5pt">&nbsp;</div>Remarks</th>
                    </tr>
                </table>';
        } else {
            $html_header = '
                <table cellspacing="0" cellpadding="0.2" border="1" style="width:100%">
                    <tr>
                        <th align="center" width="9%">Date Requested</th>
                        <th align="center" width="9%"><div style="font-size:5pt">&nbsp;</div>Control No.</th>
                        <th align="center" width="14%"><div style="font-size:5pt">&nbsp;</div>Requested By</th>
                        <th align="center" width="12%"><div style="font-size:5pt">&nbsp;</div>Item Requested</th>
                        <th align="center" width="18%"><div style="font-size:5pt">&nbsp;</div>Purpose</th>
                        <th align="center" width="9%">Assigned Technician</th>
                        <th align="center" width="10%">Target Date of Completion</th>
                        <th align="center" width="10%">Actual Date of Completion</th>
                        <th align="center" width="9%"><div style="font-size:5pt">&nbsp;</div>Remarks</th>
                    </tr>
                </table>';
        }
        $this->writeHTML($html_header, false, false, true, false, '');
    }

    public function OutputTable($itemData_List)
    {
        $requestType = $_GET['requestType'];

        $this->TableHeader();
        $this->SetFont('helvetica', '', 10);
        $html = '<table style="width:100%;" border="1" cellspacing="0" cellpadding="2">';
        if ($requestType == 'Hardware') {
            foreach ($itemData_List as $details) {
                $change = $details['repaired_by_date'] == null ? '' : date('m-d-y', strtotime($details['repaired_by_date']));
                $html .= '<tr>';
                $html .= '<td style="width: 10%; text-align: center;">' . date('m-d-y', strtotime($details['prepared_by_date'])) . '</td>';
                $html .= '<td style="width: 10%;">' . $details['queue_number'] . '</td>';
                $html .= '<td style="width: 12%;">' . $details['prepared_by'] . '</td>';
                $html .= '<td style="width: 12%;">' . $details['item'] . '</td>';
                $html .= '<td style="width: 20%;">' . $details['purpose'] . '</td>';
                $html .= '<td style="width: 10%; text-align: center;">' . date('m-d-y', strtotime($details['date_needed'])) . '</td>';
                $html .= '<td style="width: 10%; text-align: center;">' . $change  . '</td>';
                $html .= '<td style="width: 16%; text-align: center;">(' . $details['status'] . ') ' . $details['remarks'] . '</td>';

                $html .= '</tr>';
            }
        } else {
            foreach ($itemData_List as $details) {
                $change = $details['repaired_by_date'] == null ? '' : date('m-d-y', strtotime($details['repaired_by_date']));
                $html .= '<tr>';
                $html .= '<td style="width: 9%; text-align: center;">' . date('m-d-y', strtotime($details['prepared_by_date'])) . '</td>';
                $html .= '<td style="width: 9%;">' . $details['queue_number'] . '</td>';
                $html .= '<td style="width: 14%;">' . $details['prepared_by'] . '</td>';
                $html .= '<td style="width: 12%;">' . $details['item'] . '</td>';
                $html .= '<td style="width: 18%;">' . $details['purpose'] . '</td>';
                $html .= '<td style="width: 9%;">' . $details['repaired_by'] . '</td>';
                $html .= '<td style="width: 10%; text-align: center;">' . date('m-d-y', strtotime($details['date_needed'])) . '</td>';
                $html .= '<td style="width: 10%; text-align: center;">' . $change  . '</td>';
                $html .= '<td style="width: 9%; text-align: center;">(' . $details['status'] . ') ' . $details['remarks'] . '</td>';
                $html .= '</tr>';
            }
        }
        $html .= '</table>';
        $this->writeHTML($html, true, false, true, false, '');
    }
    public function OutputTable2($itemData_ListNotDone)
    {
        $requestType = $_GET['requestType'];
        $this->TableHeader();
        $this->SetFont('helvetica', '', 10);
        $html = '<table style="width:100%;" border="1" cellspacing="0" cellpadding="2">';
        if ($requestType == 'Hardware') {
            foreach ($itemData_ListNotDone as $details) {
                $change = $details['repaired_by_date'] == null ? '' : date('m-d-y', strtotime($details['repaired_by_date']));
                $html .= '<tr>';
                $html .= '<td style="width: 10%; text-align: center;">' . date('m-d-y', strtotime($details['prepared_by_date'])) . '</td>';
                $html .= '<td style="width: 10%;">' . $details['queue_number'] . '</td>';
                $html .= '<td style="width: 12%;">' . $details['prepared_by'] . '</td>';
                $html .= '<td style="width: 12%;">' . $details['item'] . '</td>';
                $html .= '<td style="width: 20%;">' . $details['purpose'] . '</td>';
                $html .= '<td style="width: 10%; text-align: center;">' . date('m-d-y', strtotime($details['date_needed'])) . '</td>';
                $html .= '<td style="width: 10%; text-align: center;">' . $change  . '</td>';
                $html .= '<td style="width: 16%; text-align: center;">(' . $details['status'] . ') ' . $details['remarks'] . '</td>';
                $html .= '</tr>';
            }
        } else {
            foreach ($itemData_ListNotDone as $details) {
                $change = $details['repaired_by_date'] == null ? '' : date('m-d-y', strtotime($details['repaired_by_date']));
                $html .= '<tr>';
                $html .= '<td style="width: 9%; text-align: center;">' . date('m-d-y', strtotime($details['prepared_by_date'])) . '</td>';
                $html .= '<td style="width: 9%;">' . $details['queue_number'] . '</td>';
                $html .= '<td style="width: 14%;">' . $details['prepared_by'] . '</td>';
                $html .= '<td style="width: 12%;">' . $details['item'] . '</td>';
                $html .= '<td style="width: 18%;">' . $details['purpose'] . '</td>';
                $html .= '<td style="width: 9%;">' . $details['repaired_by'] . '</td>';
                $html .= '<td style="width: 10%; text-align: center;">' . date('m-d-y', strtotime($details['date_needed'])) . '</td>';
                $html .= '<td style="width: 10%; text-align: center;">' . $change  . '</td>';
                $html .= '<td style="width: 9%; text-align: center;">(' . $details['status'] . ') ' . $details['remarks'] . '</td>';
                $html .= '</tr>';
            }
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
$pdf->Ln(39.5);
$pdf->SetFont('helvetica', '', 10);
$pdf->OutputTable($itemData_List);
$pdf->Ln(-2);
$pdf->Cell(0, 0, '  Update for previous month:', 0, 0, 'L');
$pdf->Ln(7);
$pdf->OutputTable2($itemData_ListNotDone);
$pdf->Ln(0);
$pdf->Cell(306, 0, '*****Nothing Follows*****', 0, 0, 'C');
$pdf->Ln(40);
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


$pdf->Output('IT_Request_Main.pdf', 'I'); //* Close and output PDF documen