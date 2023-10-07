<?php
include '../vendor/tcpdf/tcpdf.php';
include_once '../configuration/connection.php';
$actionArray = array();

$control_no = $_GET['control_no'];
$sqlstringAction = "SELECT * FROM tblit_user_access_request WHERE control_no = '{$control_no}'";
$data_result = sqlQuery($sqlstringAction, $php_fetch_itasset_api);
foreach ($data_result['data'] as $row) {
    $mail_account = $row['mail_account'];
    $file_storage_access = $row['file_storage_access'];
    $in_house_access = $row['in_house_access'];
    $domain_account = $row['domain_account'];
    $purpose = $row['purpose'];
    $access = $row['access'];
    $priority = $row['priority'];
    $prepared_by = $row['prepared_by'];
    $approved_by = $row['approved_by'];
    $noted_by = $row['noted_by'];
    $prepared_by_job_pos = getJobPosition($row['prepared_by'], $php_fetch_bannerweb_api);
    $approved_by_job_pos = getJobPosition($row['approved_by'], $php_fetch_bannerweb_api);
    $noted_by_job_pos = getJobPosition($row['noted_by'], $php_fetch_bannerweb_api);
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
function getJobPosition($name, $php_fetch_bannerweb_api)
{
    $sqlstring = "SELECT pos_name FROM prl_employee 
            INNER JOIN prl_position ON prl_employee.pos_code = prl_position.pos_code
            WHERE CONCAT(emp_fn || ' ' || emp_mi || '. ' || emp_sn || emp_ext) = '{$name}'
		    ORDER BY (emp_fn || ' ' || emp_mi || '. ' || emp_sn || emp_ext) ASC
            ";
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
        $control_no = $_GET['control_no'];
        if ($this->PageNo() == 1) {
            //* Image($file, $x='', $y='', $w=0, $h=0, $type='', $link='', $align='', $resize=false, $dpi=300, $palign='', $ismask=false, $imgmask=false, $border=0, $fitbox=false, $hidden=false, $fitonpage=false)
            $this->Image('../vendor/images/Banner Logo-colored.jpg', 89, 12, 40, 9.8, 'JPG', '', 'T', true, 300, '', false, false, 0, false, false, false);
            $this->Image('../vendor/images/ISO Logo-colored.jpg', 163, 12, 41, 11, 'JPG', '', 'T', true, 300, '', false, false, 0, false, false, false);
            $this->Ln(14.5);
            //* Cell(w, h = 0, txt = '', border = 0, ln = 0, align = '', fill = 0, link = nil, stretch = 0, ignore_min_height = false, calign = 'T', valign = 'M')
            $this->SetFont('helvetica', '', 12);
            $this->Cell(0, 0, 'Information Technology Department', 0, 1, 'C');
            $this->SetFont('helvetica', 'B', 14);
            $this->Cell(138, 0, 'USER ACCESS REQUISITION FORM', 0, 0, 'R');
            $this->SetFont('helvetica', 'B', 12);
            $this->Cell(53.5, 0, 'UAF-' . $control_no, 0, 0, 'R', 0, '', 0, FALSE, '', 'B');
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
        $this->Cell(191, '', 'ITD/XX-021-00', 0, false, 'R', 0, '', 0, false, 'T', 'M');
        // $this->Ln(4);
        // ! end
        //* Page Number
        //TODO Fix Alignment if its only single page
        // if ($this->PageNo() > 1) {
        //     $this->SetY(-16);
        //     $this->Cell(205, '', 'Page ' . $this->getAliasNumPage() . ' of ' . $this->getAliasNbPages(), 0, false, 'R', 0, '', 0, false, '', ''); //* Last number is Double digit
        // }
        // if ($this->PageNo() <= 9) {
        //     $this->Cell(205, '', 'Page ' . $this->getAliasNumPage() . ' of ' . $this->getAliasNbPages(), 0, false, 'R', 0, '', 0, false, '', ''); //* Last number is Double digit
        // }
        // if ($this->PageNo() >= 10) {
        //     $this->Cell(202, '', 'Page ' . $this->getAliasNumPage() . ' of ' . $this->getAliasNbPages(), 0, false, 'R', 0, '', 0, false, '', ''); //* Both number is double digit
        // }
    }
}
//* create new PDF document
$pdf = new MYPDF("L", PDF_UNIT, "JUNIORLONG", true, 'UTF-8', false);
//* remove default header/footer
//* $pdf->setPrintHeader(false);
//*  $pdf->setPrintFooter(false);
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED); //* set default monospaced font
//*  $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT); // set margins
$pdf->SetMargins(12.5, 12, 12.5); //* set margins
$pdf->SetAutoPageBreak(TRUE, 10); //* set page break
$pdf->AddPage();
$pdf->Ln(33);

//?DATA
$pdf->SetFont('helvetica', '', 12);
$pdf->Cell(140, 40, '', 1, 0, 'L');
$pdf->Cell(2, 40, '', 0, 0, 'L');
$pdf->Cell(49, 40, '', 1, 1, 'L');
$pdf->Ln(2);
$pdf->Cell(140, 25, '', 1, 0, 'L');
$pdf->Cell(2, 25, '', 0, 0, 'L');
$pdf->Cell(49, 25, '', 1, 1, 'L');
$pdf->SetY(50);
$pdf->Cell(2, 9, '', 0, 0, 'L');
$pdf->Cell(30, 5, 'Mail Account :', 0, 0, 'L');
$pdf->MultiCell(100, 6.5, $mail_account, 'B', '', 0, 0, 45, 46, true, 0, true, '', 0);
$pdf->Ln();
// $pdf->Cell(100, 6.5, $mail_account, 1, 1, 'L');

$pdf->Cell(2, 9, '', 0, 0, 'L');
$pdf->Cell(30, 9, 'File Storage :', 0, 0, 'L');
// $pdf->Cell(100, 6.5, $file_storage_access, 'B', 1, 'L');
$pdf->MultiCell(100, 6.5, $file_storage_access, 'B', '', 0, 0, 45, 57, true, 0, true, '', 0);
$pdf->Ln();

$pdf->Cell(2, 9, '', 0, 0, 'L');
$pdf->Cell(30, 13, 'In-House :', 0, 0, 'L');
$pdf->MultiCell(100, 6.5, $in_house_access, 'B', '', 0, 0, 45, 68, true, 0, true, '', 0);
// $pdf->Cell(100, 6.5, $in_house_access, 'B', 1, 'L');

$statusCheckedDomain = $domain_account == 1 ? 'true' : 'false';

$pdf->MultiCell(9, 7, '<input type="checkbox" name="domain" id="domain" value="1" checked="' . $statusCheckedDomain . '" readonly="true" style="font-size:15px;"></input>', 0, '', 0, 0, 12.5, 78, true, 0, true, '', 0);
$pdf->Cell(40, 6, 'Domain Account:', 0, 0, 'L');

$pdf->SetY(88);
$pdf->Cell(2, 9, '', 0, 0, 'L');
$pdf->Cell(30, 9, 'Purpose :', 0, 1, 'L');
$pdf->Cell(7, 9, '', 0, 0, 'L');
$pdf->MultiCell(125, 6.5, $purpose, 0, '', 0, 0, 19.5, 96, true, 0, true, '', 0);
// $pdf->Cell(133, 9, $purpose, 0, 0, 'L');

$statusCheckedNew = $access == 'New' ? 'true' : 'false';
$statusCheckedAdditional = $access == 'Additional' ? 'true' : 'false';
$statusCheckedChange = $access == 'Change' ? 'true' : 'false';
$pdf->MultiCell(9, 7, '<input type="checkbox" name="new" id="new" value="1" checked="' . $statusCheckedNew . '" readonly="true" style="font-size:15px;"></input>', 0, '', 0, 0, 160, 53, true, 0, true, '', 0);
$pdf->Cell(40, 6, 'New', 0, 0, 'L');
$pdf->MultiCell(9, 7, '<input type="checkbox" name="additional" id="additional" value="1" checked="' . $statusCheckedAdditional . '" readonly="true" style="font-size:15px;"></input>', 0, '', 0, 0, 160, 60, true, 0, true, '', 0);
$pdf->Cell(40, 6, 'Additional', 0, 0, 'L');
$pdf->MultiCell(9, 7, '<input type="checkbox" name="change" id="change" value="1" checked="' . $statusCheckedChange . '" readonly="true" style="font-size:15px;"></input>', 0, '', 0, 0, 160, 67, true, 0, true, '', 0);
$pdf->Cell(40, 6, 'Change', 0, 0, 'L');
$statusCheckedUrgent = $priority == 'Urgent' ? 'true' : 'false';
$statusCheckedScheduling = $priority == 'For Scheduling' ? 'true' : 'false';
$pdf->MultiCell(9, 7, '<input type="checkbox" name="urgent" id="urgent" value="1" checked="' . $statusCheckedUrgent . '" readonly="true" style="font-size:15px;"></input>', 0, '', 0, 0, 160, 92, true, 0, true, '', 0);
$pdf->Cell(40, 6, 'Urgent', 0, 0, 'L');
$pdf->MultiCell(9, 7, '<input type="checkbox" name="scheduling" id="scheduling" value="1" checked="' . $statusCheckedScheduling . '" readonly="true" style="font-size:15px;"></input>', 0, '', 0, 0, 160, 99, true, 0, true, '', 0);
$pdf->Cell(40, 6, 'For Scheduling', 0, 0, 'L');


$pdf->SetY(115);
$pdf->SetFont('helvetica', '', 12);
$pdf->Cell(63.6, 9, 'Requested By:', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
$pdf->Cell(3, 5, '', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
$pdf->Cell(63.6, 9, 'Approved by:', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
$pdf->Cell(3, 5, '', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
$pdf->Cell(63.6, 9, 'Noted by:', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
$pdf->Ln(5);

$pdf->SetFont('helvetica', '', 10);
$pdf->Ln(15);
$pdf->Cell(63.5, 5, $prepared_by, 0, 0, 'L', 0, '', 0, false, 'T', 'M');
$pdf->Cell(3, 5, '', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
$pdf->Cell(63.6, 5, $approved_by, 0, 0, 'L', 0, '', 0, false, 'T', 'M');
$pdf->Cell(3, 5, '', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
$pdf->Cell(63.6, 5, $noted_by, 0, 0, 'L', 0, '', 0, false, 'T', 'M');
$pdf->Ln();
$pdf->Cell(63.6, 5, $prepared_by_job_pos, 0, 0, 'L', 0, '', 0, false, 'T', 'M');
$pdf->Cell(3, 5, '', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
$pdf->MultiCell(63.6, 10, $approved_by_job_pos, 0, 'L', 0, 0);
$pdf->Cell(3, 5, '', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
$pdf->MultiCell(63.6, 10, $noted_by_job_pos, 0, 'L', 0, 0);
$filename = "It Hardware Issued.pdf";

$pdf->Output($filename, 'I'); //* Close and output PDF document