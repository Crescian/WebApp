<?php
include '../vendor/tcpdf/tcpdf.php';
include_once '../configuration/connection.php';
$PHD = $conn->db_conn_physical_security(); //* Physical Security Database connection
$BannerWebLive = $conn->db_conn_bannerweb(); //* Banner Web Database connection

function getJobPosition($employee, $BannerWebLive)
{
    $sql_pos_name = "SELECT pos_name FROM prl_employee 
        INNER JOIN prl_position ON prl_position.pos_code = prl_employee.pos_code
        WHERE (emp_fn || ' ' || emp_sn) = :employee";
    $sql_pos_name_stmt = $BannerWebLive->prepare($sql_pos_name);
    $sql_pos_name_stmt->bindParam(':employee', $employee);
    $sql_pos_name_stmt->execute();
    $sql_pos_name_row = $sql_pos_name_stmt->fetch(PDO::FETCH_ASSOC);
    return $sql_pos_name_row['pos_name'];
    $BannerWebLive = null; //* ======== Close Connection ========
}

$eventheaderid = $_GET['d'];
//* -------------------------------- Header --------------------------------
$sqlstring_header = "SELECT date_created,prepared_by1,
    CASE WHEN prepared_by_acknowledge = true THEN encode(prepared_by1_sign, 'escape') ELSE '' END AS reviewed_by_sign,
    noted_by,
    CASE WHEN noted_by_acknowledge = true THEN encode(noted_by_sign, 'escape') ELSE '' END AS noted_by_sign
    FROM phd_event_monitoring_header WHERE eventheaderid = :eventheaderid";
$result_header_stmt = $PHD->prepare($sqlstring_header);
$result_header_stmt->bindParam(':eventheaderid', $eventheaderid);
$result_header_stmt->execute();
$result_header_row = $result_header_stmt->fetch(PDO::FETCH_ASSOC);
$date_created = $result_header_row['date_created'];
$reviewed_by = $result_header_row['prepared_by1'];
$reviewed_by_position = getJobPosition($reviewed_by, $BannerWebLive);
$reviewed_by_sign = $result_header_row['reviewed_by_sign'];
$noted_by = $result_header_row['noted_by'];
$noted_by_position = getJobPosition($noted_by, $BannerWebLive);
$noted_by_sign = $result_header_row['noted_by_sign'];
//* -------------------------------- Details --------------------------------
$sqlstring_details = "SELECT phd_event_monitoring_details.surveillance_name,event_time_start,event_time_end,event_date_from,event_date_to,event_total_days,event_min_days,event_comments
    FROM phd_event_monitoring_details 
    INNER JOIN phd_surveillance_name ON phd_surveillance_name.surveillance_name = phd_event_monitoring_details.surveillance_name 
    WHERE eventheader_id = :eventheader_id ORDER BY surveillanceid ASC";
$result_details_stmt = $PHD->prepare($sqlstring_details);
$result_details_stmt->bindParam(':eventheader_id', $eventheaderid);
$result_details_stmt->execute();
$result_details_row = $result_details_stmt->fetchAll();

//* extend TCPF with custom functions
class MYPDF extends TCPDF
{
    //* Page header
    public function Header()
    {
        if ($this->PageNo() == 1) {
            //* Image($file, $x='', $y='', $w=0, $h=0, $type='', $link='', $align='', $resize=false, $dpi=300, $palign='', $ismask=false, $imgmask=false, $border=0, $fitbox=false, $hidden=false, $fitonpage=false)
            $this->Image('../vendor/images/Banner Logo-colored.jpg', 120, 12, 42.5, 11, 'JPG', '', 'T', true, 300, '', false, false, 0, false, false, false);
            $this->Image('../vendor/images/ISO Logo-colored.jpg', 224, 12, 43, 12.7, 'JPG', '', 'T', true, 300, '', false, false, 0, false, false, false);
            $this->Ln(14.5);
            //* Cell(w, h = 0, txt = '', border = 0, ln = 0, align = '', fill = 0, link = nil, stretch = 0, ignore_min_height = false, calign = 'T', valign = 'M')
            $this->SetFont('helvetica', '', 12);
            $this->Cell(0, 0, 'Physical Security Department', 0, 1, 'C');
            $this->SetFont('helvetica', 'B', 14);
            $this->Cell(0, 0, 'SURVEILLANCE EVENT MONITORING LOG SHEET', 0, 0, 'C');
        }
        // if ($this->PageNo() >= 2) {
        //     $this->SetFont('helvetica', '', 10);
        //     $this->Cell(42, 29, 'Time Synchronization Monitoring Log Sheet', 0, 0, 'R');
        // }
    }

    //* Page footer
    public function Footer()
    {
        //* Position at 26 mm from bottom
        $this->SetY(-17);
        $this->SetFont('helvetica', '', 10);
        //* Page Number
        //* Cell(w, h = 0, txt = '', border = 0, ln = 0, align = '', fill = 0, link = nil, stretch = 0, ignore_min_height = false, calign = 'T', valign = 'M')
        $this->Cell(255, '', 'PHD/IS-006-00', 0, false, 'R', 0, '', 0, false, 'T', 'M');
        $this->Ln(4);
        //* Page Number
        //TODO Fix Alignment if its only single page
        // if ($this->PageNo() <= 1) {
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
$pdf = new MYPDF("L", PDF_UNIT, "LETTER", true, 'UTF-8', false);
//* remove default header/footer
//* $pdf->setPrintHeader(false);
//*  $pdf->setPrintFooter(false);
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED); //* set default monospaced font
//*  $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT); // set margins
$pdf->SetMargins(12.5, 12, 12.5); //* set margins
$pdf->SetAutoPageBreak(TRUE, 0); //* set page break
$pdf->AddPage();
$pdf->Ln(33);
//* -------------------------------- Info --------------------------------
$pdf->SetFont('helvetica', '', 12);
$pdf->Cell(225, 0, 'Date: ', 0, 0, 'R');
$pdf->Cell(30, 0, date_format(date_create($date_created), 'F d, Y'), 0, 0, 'C');
$pdf->Ln(7);
$pdf->SetFont('helvetica', '', 12);
//* writeHTML($html, $ln=true, $fill=false, $reseth=false, $cell=false, $align='')
$html = '<table style="width:100%;" border="1" cellspacing="0" cellpadding="0">
            <tr>
                <th rowspan="2" style="width:20%;text-align:center;"><div style="font-size:9pt">&nbsp;</div>Surveillance Description</th>
                <th colspan="2" style="width:20%;text-align:center;">Video Event Reviewed</th>
                <th colspan="4" style="width:40%;text-align:center;">Video Event Recorded</th>
                <th rowspan="2" style="width:20%;text-align:center;"><div style="font-size:9pt">&nbsp;</div>Comments</th>
            </tr>
            <tr>
                <td style="text-align:center;"><div style="font-size:5pt">&nbsp;</div>Time Start</td>
                <td style="text-align:center;"><div style="font-size:5pt">&nbsp;</div>Time End</td>
                <td style="width:10%;text-align:center;"><div style="font-size:5pt">&nbsp;</div>From</td>
                <td style="width:10%;text-align:center;"><div style="font-size:5pt">&nbsp;</div>To</td>
                <td style="width:10%;text-align:center;">Total No. of Days</td>
                <td style="width:10%;text-align:center;">Min. Required No.of Days</td>
            </tr>';
$eventCount = 0;
$pdf->SetFont('helvetica', '', 11);
foreach ($result_details_row as $row) {
    $eventCount++;
    $html .= '<tr>
        <td>' . $row['surveillance_name'] . '</td>
        <td style="text-align:center;">' . date_format(date_create($row['event_time_start']), 'Hi') . 'H</td>
        <td style="text-align:center;">2359H</td>
        <td style="text-align:center;">' . date_format(date_create($row['event_date_from']), 'm-d-y') . '</td>
        <td style="text-align:center;">' . date_format(date_create($row['event_date_to']), 'm-d-y') . '</td>
        <td style="text-align:center;">' . $row['event_total_days'] . '</td>
        <td style="text-align:center;">' . $row['event_min_days'] . '</td>
        <td style="text-align:center;">' . $row['event_comments'] . '</td>
    </tr>';
}
$html .= '</table>';
$pdf->writeHTML($html, false, false, true, false, '');
$pdf->Ln(12);
//* -------------------------------- Designation --------------------------------
$pdf->SetFont('helvetica', '', 12);
$pdf->Cell(127.5, 9, 'Reviewed by:', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
$pdf->Cell(127.5, 9, 'Noted by:', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
$pdf->Ln(10);
//* -------------------------------- Signature --------------------------------
//* Image($file, $x='', $y='', $w=0, $h=0, $type='', $link='', $align='', $resize=false, $dpi=300, $palign='', $ismask=false, $imgmask=false, $border=0, $fitbox=false, $hidden=false, $fitonpage=false)
$pdf->Image('@' . base64_decode($reviewed_by_sign), 10, 163, 40, 20, '', '', '', false, 300, '', false, false, 0, false, false, false);
$pdf->Image('@' . base64_decode($noted_by_sign), 135, 163, 40, 20, '', '', '', false, 300, '', false, false, 0, false, false, false);
$pdf->Ln(10);
//* -------------------------------- Responsible --------------------------------
$pdf->SetFont('helvetica', '', 10);
$pdf->Cell(127.5, 0, $reviewed_by, 0, 0, 'L', 0, '', 0, false, 'T', 'M');
$pdf->Cell(127.5, 0, $noted_by, 0, 0, 'L', 0, '', 0, false, 'T', 'M');
$pdf->Ln();
//* -------------------------------- Job Title --------------------------------
$pdf->Cell(127.5, 0, $reviewed_by_position, 0, 0, 'L', 0, '', 0, false, 'T', 'M');
$pdf->Cell(127.5, 0, $noted_by_position, 0, 0, 'L', 0, '', 0, false, 'T', 'M');

$filename = "Surveillance Event Monitoring Log Sheet.pdf";
$pdf->Output($filename, 'I'); //* Close and output PDF document
