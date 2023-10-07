<?php
include_once '../configuration/connection.php';
include '../vendor/tcpdf/tcpdf.php';
date_default_timezone_set('Asia/Manila');
$infoSec = $conn->db_conn_info_security(); //* Info Security Database connection
$bannerWeb = $conn->db_conn_bannerweb(); //* BannerWeb Database connection

function getJobPosition($employee, $bannerWeb)
{
    $sql_pos_name = "SELECT pos_name FROM prl_employee 
        INNER JOIN prl_position ON prl_position.pos_code = prl_employee.pos_code
        WHERE (emp_fn || ' ' || emp_sn) = :employee";
    $sql_pos_name_stmt = $bannerWeb->prepare($sql_pos_name);
    $sql_pos_name_stmt->bindParam(':employee', $employee);
    $sql_pos_name_stmt->execute();
    while ($sql_pos_name_row = $sql_pos_name_stmt->fetch(PDO::FETCH_ASSOC)) {
        return $sql_pos_name_row['pos_name'];
    }
    $bannerWeb = null; //* ======== Close Connection ========
}

function fetchEmpSignature($emloyee_name, $bannerWeb)
{
    $employeeSign = '';
    $employeeSignature = "SELECT encode(employee_signature, 'escape') as employee_signature FROM bpi_employee_signature WHERE emp_name = :emloyee_name";
    $employeeSignature_stmt = $bannerWeb->prepare($employeeSignature);
    $employeeSignature_stmt->bindParam(':emloyee_name', $emloyee_name);
    $employeeSignature_stmt->execute();
    while ($employeeSignature_row = $employeeSignature_stmt->fetch(PDO::FETCH_ASSOC)) {
        $employeeSign =  $employeeSignature_row['employee_signature'];
    }
    return $employeeSign;
    $bannerWeb = null; //* ======== Close Connection ========
}

$sqlstring = "SELECT control_no,date_needed,web_priority,service_type,req_description,application_name,prepared_by,approved_by,received_by,noted_by,
encode(prepared_by_sign, 'escape') as prepared_by_sign,
encode(approved_by_sign, 'escape') as approved_by_sign,
encode(received_by_sign, 'escape') as received_by_sign,
encode(noted_by_sign, 'escape') as noted_by_sign
FROM info_sec_web_app_request WHERE webappid = ?";
$result_stmt = $infoSec->prepare($sqlstring);
$result_stmt->execute([$_GET['id']]);
//* ======== Prepare Array ========
while ($row = $result_stmt->fetch(PDO::FETCH_ASSOC)) {
    $control_no = $row['control_no'];
    $date_needed = date_format(date_create($row['date_needed']), 'M d, Y');
    $checked_urgent = $row['web_priority'] == 'Urgent' ? 'checked' : '';
    $checked_sched = $row['web_priority'] == 'For Scheduling' ? 'checked' : '';
    //* ======== Service Checked ========
    $app_new_check = $row['service_type'] == 'New Application' ? 'checked' : '';
    $app_enhance_check = $row['service_type'] == 'Enhancement to existing application' ? 'checked' : '';
    $app_replace_check = $row['service_type'] == 'Replace an existing application' ? 'checked' : '';
    $app_new_module_check = $row['service_type'] == 'New Module' ? 'checked' : '';
    $app_new_report_check = $row['service_type'] == 'New Report' ? 'checked' : '';
    $other_check = $row['service_type'] == 'Other' ? 'checked' : '';
    //* ======== Application Name ========
    $app_new = $row['service_type'] == 'New Application' ? $row['application_name'] : '';
    $app_enhance = $row['service_type'] == 'Enhancement to existing application' ? $row['application_name'] : '';
    $app_replace = $row['service_type'] == 'Replace an existing application' ? $row['application_name'] : '';
    $app_new_module = $row['service_type'] == 'New Module' ? $row['application_name'] : '';
    $app_new_report = $row['service_type'] == 'New Report' ? $row['application_name'] : '';
    $other = $row['service_type'] == 'Other' ? $row['application_name'] : '';
    $description = $row['req_description'];

    //* ======== Prepared By ========
    $prepared_by = $row['prepared_by'];
    $prepared_by_position = getJobPosition($prepared_by, $bannerWeb);
    $prepared_by_sign = '';
    // $prepared_by_sign = $row['prepared_by_sign'];
    //* ======== Approved By ========
    $approved_by = $row['approved_by'];
    $approved_by_position = getJobPosition($approved_by, $bannerWeb);
    $approved_by_sign = '';
    // $approved_by_sign = $row['approved_by_sign'];
    //* ======== Received By ========
    $received_by = $row['received_by'];
    $received_by_position = getJobPosition($received_by, $bannerWeb);
    $received_by_sign = '';
    // $received_by_sign = $row['received_by_sign'];
    //* ======== Noted By ========
    $noted_by = $row['noted_by'];
    $noted_by_position = getJobPosition($noted_by, $bannerWeb);
    $noted_by_sign = '';
    // $noted_by_sign = $row['noted_by_sign'];
}


//* extend TCPF with custom functions
class MYPDF extends TCPDF
{
    //* Page header
    public function Header()
    {
        if ($this->PageNo() == 1) {
            //* Image($file, $x='', $y='', $w=0, $h=0, $type='', $link='', $align='', $resize=false, $dpi=300, $palign='', $ismask=false, $imgmask=false, $border=0, $fitbox=false, $hidden=false, $fitonpage=false)
            $this->Image('../vendor/images/Banner Logo.jpg', 85, 12, 41, 9.6, 'JPG', '', 'T', true, 300, '', false, false, 0, false, false, false);
            $this->Image('../vendor/images/ISO Logo.jpg', 162, 12, 42, 10.5, 'JPG', '', 'T', true, 300, '', false, false, 0, false, false, false);
            $this->Ln(13);
            //* Cell(w, h = 0, txt = '', border = 0, ln = 0, align = '', fill = 0, link = nil, stretch = 0, ignore_min_height = false, calign = 'T', valign = 'M')
            $this->SetFont('helvetica', '', 12);
            $this->Cell(0, 0, 'Information Security/Data Protection Department', 0, 1, 'C');
            $this->SetFont('helvetica', 'B', 14);
            $this->Cell(0, 0, 'WEB APPLICATION REQUEST FORM', 0, 0, 'C');
        }
        if ($this->PageNo() >= 2) {
            $this->SetFont('helvetica', '', 10);
            $this->Cell(0, 7, '', 0, 1, 'L');
            $this->Cell(0, 5, 'Web Application Request Form', 0, 0, 'L');
        }
    }
    //* Page footer
    public function Footer()
    {
        //* Cell(w, h = 0, txt = '', border = 0, ln = 0, align = '', fill = 0, link = nil, stretch = 0, ignore_min_height = false, calign = 'T', valign = 'M')
        //* Position at 26 mm from bottom
        $this->SetFont('helvetica', '', 10);
        //* Page Number
        // $numPages = $this->getNumPages();
        // if ($this->getAliasNbPages() == $numPages) {
        $this->SetY(-16);
        $this->Cell(191, '', 'ISD/XX-034-00', 0, false, 'R', 0, '', 0, false, 'T', 'M');
        // } else {
        // $this->SetY(-22);
        // $this->Cell(191, '', 'ISD/XX-034-00', 0, false, 'R', 0, '', 0, false, 'T', 'M');
        // $this->Ln(4);
        // if ($this->PageNo() >= 1 && $this->PageNo() <= 9) {
        //     $this->Cell(207, '', 'Page ' . $this->getAliasNumPage() . ' of ' . $this->getAliasNbPages(), 0, 0, 'R', 0, '', 0, false, '', ''); //* Last number is Double digit
        // }
        // if ($this->PageNo() >= 10) {
        //     $this->Cell(202, '', 'Page ' . $this->getAliasNumPage() . ' of ' . $this->getAliasNbPages(), 0, 0, 'R', 0, '', 0, false, '', ''); //* Both number is Double digit
        // }
        // }
    }
}
//* create new PDF document
$pdf = new MYPDF("P", PDF_UNIT, "LETTER", true, 'UTF-8', false);
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED); //* set default monospaced font
$pdf->SetMargins(12.5, 12, 12.5); //* set margins
$pdf->SetAutoPageBreak(TRUE, 20); //* set page break
$pdf->setPrintHeader(true); //* include header
$pdf->setPrintFooter(true); //* include footer
$pdf->AddPage(); //* Add page
$pdf->Ln(19); //* Next Line
//* Cell(w, h = 0, txt = '', border = 0, ln = 0, align = '', fill = 0, link = nil, stretch = 0, ignore_min_height = false, calign = 'T', valign = 'M')
//* -------------------------------- Header --------------------------------
$pdf->SetFont('freeserif', '', 14);
$pdf->Cell(163, 0, '№', 0, 0, 'R', 0, '', 0, FALSE, '', 'T');
$pdf->SetFont('helvetica', 'B', 12);
$pdf->Cell(28, 0, $control_no, 0, 0, 'R', 0, '', 0, FALSE, 'T', 'B');
$pdf->Ln(12.5);
//* -------------------------------- Info --------------------------------
$pdf->SetFont('helvetica', '', 12);
$pdf->Cell(152, 0, 'Date Requested: ', 0, 0, 'R');
$pdf->Cell(39, 0, 'December 25, 2023', 0, 0, 'L');
$pdf->Ln(7);
$pdf->SetFont('helvetica', '', 12);
$pdf->Cell(95.5, 12, 'Date Needed:  ' . $date_needed, 1, 0, 'L');
//* MultiCell($w, $h, $txt, $border=0, $align='J', $fill=0, $ln=1, $x='', $y='', $reseth=true, $stretch=0, $ishtml=false, $autopadding=true, $maxh=0)
$pdf->MultiCell(46.75, 12, '<div style="font-size:8px">&nbsp;</div><input type="checkbox" name="urgent" value="1" checked="' . $checked_urgent . '" readonly="true"></input><label for="urgent">Urgent</label>', 'TLB', 'C', 0, 0, 108, 50.5, true, 0, true, '', 0);
$pdf->MultiCell(47.75, 12, '<div style="font-size:8px">&nbsp;</div><input type="checkbox" name="for_sched" value="1" checked="' . $checked_sched . '" readonly="true"></input><label for="for_sched">For Scheduling</label>', 'TRB', 'C', 0, 0, 155, 50.5, true, 0, true, '', 0);
$pdf->Ln(17);
$pdf->Cell(0, 7, 'SERVICE REQUEST', 1, 0, 'L');
$pdf->Ln(7);
$pdf->SetFont('helvetica', '', 11);
//* -------------------------------- New Application --------------------------------
$pdf->Line(133, 83, 200, 83);
$pdf->MultiCell(0, 7, '<div style="font-size:5px">&nbsp;</div><input type="checkbox" name="new_app" value="1" checked="' . $app_new_check . '" readonly="true" style="font-size:15px;"></input><label for="new_app">New Application</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<label for="new_app">Application Name:&nbsp;&nbsp;&nbsp;' . $app_new . '</label>', 'LR', '', 0, 0, 12.5, 74.5, true, 0, true, '', 0);
//* -------------------------------- Enhancement(s) to existing application --------------------------------
$pdf->Line(133, 90, 200, 90);
$pdf->MultiCell(0, 7, '<div style="font-size:5px">&nbsp;</div><input type="checkbox" name="enhance_app" value="1" checked="' . $app_enhance_check . '" readonly="true" style="font-size:15px;"></input><label for="enhance_app">Enhancement(s) to existing application</label>&nbsp;&nbsp;&nbsp;&nbsp;
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label for="enhance_app">Application Name:&nbsp;&nbsp;&nbsp;' . $app_enhance . '</label>', 'LR', '', 0, 0, 12.5, 81.5, true, 0, true, '', 0);
//* -------------------------------- Replace existing application --------------------------------
$pdf->Line(133, 97, 200, 97);
$pdf->MultiCell(0, 7, '<div style="font-size:5px">&nbsp;</div><input type="checkbox" name="replace_app" value="1" checked="' . $app_replace_check . '" readonly="true" style="font-size:15px;"></input><label for="replace_app">Replace an existing application</label>&nbsp;&nbsp;&nbsp;&nbsp;
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label for="replace_app">Application Name:&nbsp;&nbsp;&nbsp;' . $app_replace . '</label>', 'LR', '', 0, 0, 12.5, 88.5, true, 0, true, '', 0);
//* -------------------------------- New Module --------------------------------
$pdf->Line(133, 104, 200, 104);
$pdf->MultiCell(0, 7, '<div style="font-size:5px">&nbsp;</div><input type="checkbox" name="new_module" value="1" checked="' . $app_new_module_check . '" readonly="true" style="font-size:15px;"></input><label for="new_module">New Module</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<label for="new_module">Application Name:&nbsp;&nbsp;&nbsp;' . $app_new_module . '</label>', 'LR', '', 0, 0, 12.5, 95.5, true, 0, true, '', 0);
//* -------------------------------- New Report --------------------------------
$pdf->Line(133, 111, 200, 111);
$pdf->MultiCell(0, 7, '<div style="font-size:5px">&nbsp;</div><input type="checkbox" name="new_report" value="1" checked="' . $app_new_report_check . '" readonly="true" style="font-size:15px;"></input><label for="new_report">New Report</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<label for="new_report">Application Name:&nbsp;&nbsp;&nbsp;' . $app_new_report . '</label>', 'LR', '', 0, 0, 12.5, 102.5, true, 0, true, '', 0);
//* -------------------------------- Other --------------------------------
$pdf->Line(31, 118, 200, 118);
$pdf->MultiCell(0, 12, '<div style="font-size:5px">&nbsp;</div><input type="checkbox" name="other" value="1" checked="' . $other_check . '" readonly="true" style="font-size:15px;"></input><label for="other">Other:</label>&nbsp;
<label for="other">' . $other . '</label>', 'LRB', '', 0, 0, 12.5, 109.5, true, 0, true, '', 0);
$pdf->Ln(17);
//* -------------------------------- Description --------------------------------
$pdf->SetFont('helvetica', '', 12);
$pdf->Cell(0, 7, 'DETAILED DESCRIPTION OF THE REQUESTED SERVICE', 1, 0, 'L');
$pdf->Ln(7);
$pdf->MultiCell(0, 70, '<div style="font-size:5px">&nbsp;</div>&nbsp;&nbsp;' . $description, 'LRB', '', 0, 0, 12.5, 133.5, true, 0, true, '', 0);
$pdf->Ln(88);
//* -------------------------------- Designation --------------------------------
$pdf->SetFont('helvetica', '', 12);
$pdf->Cell(47.75, 7, 'Prepared by:', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
$pdf->Cell(47.75, 7, 'Approved by:', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
$pdf->Cell(47.75, 7, 'Received by:', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
$pdf->Cell(47.75, 7, 'Noted by:', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
$pdf->Ln(7);
//* -------------------------------- Signature --------------------------------
$pdf->Image('@' . base64_decode($prepared_by_sign), 10, '', 30, 20, '', '', '', false, 300, '', false, false, 0, false, false, false);
$pdf->Image('@' . base64_decode($approved_by_sign), 50, '', 40, 20, '', '', '', false, 300, '', false, false, 0, false, false, false);
$pdf->Image('@' . base64_decode($received_by_sign), 100, '', 40, 20, '', '', '', false, 300, '', false, false, 0, false, false, false);
$pdf->Image('@' . base64_decode($noted_by_sign), 153, '', 40, 20, '', '', '', false, 300, '', false, false, 0, false, false, false);
$pdf->Ln(13);
//* -------------------------------- Responsible --------------------------------
$pdf->SetFont('helvetica', '', 10);
$pdf->Cell(47.75, 5, $prepared_by, 0, 0, 'L', 0, '', 0, false, 'T', 'M');
$pdf->Cell(47.75, 5, $approved_by, 0, 0, 'L', 0, '', 0, false, 'T', 'M');
$pdf->Cell(47.75, 5, $received_by, 0, 0, 'L', 0, '', 0, false, 'T', 'M');
$pdf->Cell(47.75, 5, $noted_by, 0, 0, 'L', 0, '', 0, false, 'T', 'M');
$pdf->Ln();
//* -------------------------------- Job Position --------------------------------
$pdf->MultiCell(47.75, 7, $prepared_by_position, 0, '', 0, 0, 12.5, 245.5, true, 0, false, true, 0);
$pdf->MultiCell(47.75, 7, $approved_by_position, 0, '', 0, 0, 60, 245.5, true, 0, false, true, 0);
$pdf->MultiCell(47.75, 7, $received_by_position, 0, '', 0, 0, 108, 245.5, true, 0, false, true, 0);
$pdf->MultiCell(47.75, 7, $noted_by_position, 0, '', 0, 0, 155.7, 245.5, true, 0, false, true, 0);
//* MultiCell($w, $h, $txt, $border=0, $align='J', $fill=0, $ln=1, $x='', $y='', $reseth=true, $stretch=0, $ishtml=false, $autopadding=true, $maxh=0)

$filename =  $control_no . ".pdf";
$pdf->Output($filename, 'I'); //* Close and output PDF document