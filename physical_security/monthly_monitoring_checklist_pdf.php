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
    while ($row = $sql_pos_name_stmt->fetch(PDO::FETCH_ASSOC)) {
        return $row['pos_name'];
    }
    $BannerWebLive = null; //* ======== Close Connection ========
}

$monthlymonitoringid = $_GET['d'];
//* -------------------------------- Header --------------------------------
$sqlstring_header = "SELECT date_created,interlocking_date_created,electric_date_created,emergency_date_created,roomtemp_date_created,
    interlocking_performed_by,encode(interlocking_performed_by_sign, 'escape') as interlocking_performed_by_sign,
    interlocking_checked_by,CASE WHEN interlocking_checked_by_acknowledge = true THEN encode(interlocking_checked_by_sign, 'escape') ELSE '' END AS interlocking_checked_by_sign,
    electric_performed_by,encode(electric_performed_by_sign, 'escape') as electric_performed_by_sign,
    electric_checked_by,CASE WHEN electric_checked_by_acknowledge = true THEN encode(electric_checked_by_sign, 'escape') ELSE '' END AS electric_checked_by_sign,
    emergency_performed_by,encode(emergency_performed_by_sign, 'escape') as emergency_performed_by_sign,
    emergency_checked_by,CASE WHEN emergency_checked_by_acknowledge = true THEN encode(emergency_checked_by_sign, 'escape') ELSE '' END AS emergency_checked_by_sign,
    roomtemp_performed_by,encode(roomtemp_performed_by_sign, 'escape') as roomtemp_performed_by_sign,
    roomtemp_checked_by,CASE WHEN roomtemp_checked_by_acknowledge = true THEN encode(roomtemp_checked_by_sign, 'escape') ELSE '' END AS roomtemp_checked_by_sign,
    monitoring_noted_by,CASE WHEN monitoring_noted_by_acknowledge = true THEN encode(monitoring_noted_by_sign, 'escape') ELSE '' END AS monitoring_noted_by_sign
    FROM phd_monthly_monitoring_header WHERE monthlymonitoringid = :monthlymonitoringid";
$result_header_stmt = $PHD->prepare($sqlstring_header);
$result_header_stmt->bindParam(':monthlymonitoringid', $monthlymonitoringid);
$result_header_stmt->execute();
while ($result_header_row = $result_header_stmt->fetch(PDO::FETCH_ASSOC)) {
    $date_created = $result_header_row['date_created'];
    //* ======== Interlocking ========
    $interlocking_date_created = $result_header_row['interlocking_date_created'];
    $interlocking_performed_by = $result_header_row['interlocking_performed_by'];
    $interlocking_performed_by_position = getJobPosition($interlocking_performed_by, $BannerWebLive);
    $interlocking_performed_by_sign = $result_header_row['interlocking_performed_by_sign'];
    $interlocking_checked_by = $result_header_row['interlocking_checked_by'];
    $interlocking_checked_by_position = getJobPosition($interlocking_checked_by, $BannerWebLive);
    $interlocking_checked_by_sign = $result_header_row['interlocking_checked_by_sign'];
    //* ======== Electric ========
    $electric_date_created = $result_header_row['electric_date_created'];
    $electric_performed_by = $result_header_row['electric_performed_by'];
    $electric_performed_by_position = getJobPosition($electric_performed_by, $BannerWebLive);
    $electric_performed_by_sign = $result_header_row['electric_performed_by_sign'];
    $electric_checked_by = $result_header_row['electric_checked_by'];
    $electric_checked_by_position = getJobPosition($electric_checked_by, $BannerWebLive);
    $electric_checked_by_sign = $result_header_row['electric_checked_by_sign'];
    //* ======== Emergency ========
    $emergency_date_created = $result_header_row['emergency_date_created'];
    $emergency_performed_by = $result_header_row['emergency_performed_by'];
    $emergency_performed_by_position = getJobPosition($emergency_performed_by, $BannerWebLive);
    $emergency_performed_by_sign = $result_header_row['emergency_performed_by_sign'];
    $emergency_checked_by = $result_header_row['emergency_checked_by'];
    $emergency_checked_by_position = getJobPosition($emergency_checked_by, $BannerWebLive);
    $emergency_checked_by_sign = $result_header_row['emergency_checked_by_sign'];
    //* ======== Room Temp ========
    $roomtemp_date_created = $result_header_row['roomtemp_date_created'];
    $roomtemp_performed_by = $result_header_row['roomtemp_performed_by'];
    $roomtemp_performed_by_position = getJobPosition($roomtemp_performed_by, $BannerWebLive);
    $roomtemp_performed_by_sign = $result_header_row['roomtemp_performed_by_sign'];
    $roomtemp_checked_by = $result_header_row['roomtemp_checked_by'];
    $roomtemp_checked_by_position = getJobPosition($roomtemp_checked_by, $BannerWebLive);
    $roomtemp_checked_by_sign = $result_header_row['roomtemp_checked_by_sign'];
    //* ======== Noted By ========
    $monitoring_noted_by = $result_header_row['monitoring_noted_by'];
    $monitoring_noted_by_position = getJobPosition($monitoring_noted_by, $BannerWebLive);
    $monitoring_noted_by_sign = $result_header_row['monitoring_noted_by_sign'];
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
            $this->Cell(0, 0, 'PHD MONTHLY MONITORING CHECKLIST', 0, 0, 'C');
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
        $this->Cell(191, '', 'PHD/IS-023-00', 0, false, 'R', 0, '', 0, false, 'T', 'M');
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
$pdf = new MYPDF("P", PDF_UNIT, "LETTER", true, 'UTF-8', false);
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
$pdf->Cell(161, 0, 'Month/Year: ', 0, 0, 'R');
$pdf->Cell(30, 0, date_format(date_create($date_created), 'F Y'), 0, 0, 'C');
$pdf->Ln(7);
//* -------------------------------- Details --------------------------------
//* writeHTML($html, $ln=true, $fill=false, $reseth=false, $cell=false, $align='')
$pdf->SetFont('helvetica', '', 11);
$html_table =
    '<table>
        <tr>
            <td>
                <table style="width:100%;" border="1" cellspacing="0" cellpadding="1">
                    <tr>
                        <th colspan="3" style="text-align:center;">Interlocking RUD</th>
                    </tr>
                    <tr>
                        <td style="text-align:center;">Area</td>
                        <td style="text-align:center;">Status</td>
                        <td style="text-align:center;">Remarks</td>
                    </tr>';
$sqlinterlocking = "SELECT * FROM phd_monthly_monitoring_interlocking WHERE monthlymonitoring_id = :monthlymonitoringid
    ORDER BY interlock_category_name DESC";
$interlocking_stmt = $PHD->prepare($sqlinterlocking);
$interlocking_stmt->bindParam(':monthlymonitoringid', $monthlymonitoringid);
$interlocking_stmt->execute();
$resultData_List_interlocking = array();
while ($interlocking_row = $interlocking_stmt->fetch(PDO::FETCH_ASSOC)) {
    $resultData_List_interlocking[$interlocking_row['interlock_category_name']][] = $interlocking_row; //* ======== 2D Array
}
foreach ($resultData_List_interlocking as $tablecategory => $interlock_category_name) {
    $html_table .= '<tr>';
    $html_table .= '<td colspan="3">' . $tablecategory . '</td>';
    $html_table .= '</tr>';
    foreach ($interlock_category_name as $interlock_row) {
        $html_table .= '<tr>';
        $html_table .= '<td style="text-align:center;">' . $interlock_row['interlock_location_name'] . '</td>';
        $html_table .= '<td style="text-align:center;">' . $interlock_row['interlock_status'] . '</td>';
        $html_table .= '<td style="text-align:center;">' . $interlock_row['interlock_remarks'] . '</td>';
        $html_table .= '</tr>';
    }
}
$html_table .=
    '</table>
            </td>
            <td>
                <table style="width:100%;" border="1" cellspacing="0" cellpadding="1">
                    <tr>
                        <th colspan="3" style="text-align:center;">Electric Fence</th>
                    </tr>
                    <tr>
                        <td style="width:40%;text-align:center;">Area</td>
                        <td style="width:30%;text-align:center;">Status</td>
                        <td style="width:30%;text-align:center;">Remarks</td>
                    </tr>';
$sqlelectric = "SELECT * FROM phd_monthly_monitoring_electric WHERE monthlymonitoring_id = :monthlymonitoringid";
$electric_stmt = $PHD->prepare($sqlelectric);
$electric_stmt->bindParam(':monthlymonitoringid', $monthlymonitoringid);
$electric_stmt->execute();
while ($electric_row = $electric_stmt->fetch(PDO::FETCH_ASSOC)) {
    $html_table .= '<tr>';
    $html_table .= '<td style="width:40%;">' . $electric_row['electric_location_name'] . '</td>';
    $html_table .= '<td style="width:30%;text-align:center;">' . $electric_row['electric_status'] . '</td>';
    $html_table .= '<td style="width:30%;text-align:center;">' . $electric_row['electric_remarks'] . '</td>';
    $html_table .= '</tr>';
}
$html_table .= '</table>
            </td>
        </tr>
        <tr>
            <td></td>
        </tr>
        <tr>
            <td>
                <table style="width:100%;" border="1" cellspacing="0" cellpadding="1">
                    <tr>
                        <th colspan="3" style="text-align:center;">Emergency Evaluation Switch</th>
                    </tr>
                    <tr>
                        <td style="width:35%;text-align:center;">Switch</td>
                        <td style="width:33%;text-align:center;">Status</td>
                        <td style="width:32%;text-align:center;">Remarks</td>
                    </tr>';
$sqlemergency = "SELECT * FROM phd_monthly_monitoring_emergency WHERE monthlymonitoring_id = :monthlymonitoringid";
$emergency_stmt = $PHD->prepare($sqlemergency);
$emergency_stmt->bindParam(':monthlymonitoringid', $monthlymonitoringid);
$emergency_stmt->execute();
while ($emergency_row = $emergency_stmt->fetch(PDO::FETCH_ASSOC)) {
    $html_table .= '<tr>';
    $html_table .= '<td style="width:35%;">' . $emergency_row['emergency_switch'] . '</td>';
    $html_table .= '<td style="width:33%;text-align:center;vertical-align:middle;"><div style="font-size:10pt">&nbsp;</div>' . $emergency_row['emergency_status'] . '</td>';
    $html_table .= '<td style="width:32%;text-align:center;vertical-align:middle;"><div style="font-size:10pt">&nbsp;</div>' . $emergency_row['emergency_remarks'] . '</td>';
    $html_table .= '</tr>';
}
$html_table .= '</table>
            </td>
            <td>
                <table style="width:100%;" border="1" cellspacing="0" cellpadding="1">
                    <tr>
                        <th colspan="5" style="text-align:center;">Room Temperature</th>
                    </tr>
                    <tr>
                        <td style="width:20%;text-align:center;">Room</td>
                        <td style="width:20%;text-align:center;">Reading1</td>
                        <td style="width:20%;text-align:center;">Reading2</td>
                        <td style="width:20%;text-align:center;font-size: 8px;">Temperature Alarm</td>
                        <td style="width:20%;text-align:center;">Remarks</td>
                    </tr>
                    <tr>
                        <td colspan="5" style="text-align:center;font-size: 8px;">Ideal Room Temperature</td>
                    </tr>';
$sqlemergency = "SELECT * FROM phd_monthly_monitoring_roomtemp WHERE monthlymonitoring_id = :monthlymonitoringid";
$emergency_stmt = $PHD->prepare($sqlemergency);
$emergency_stmt->bindParam(':monthlymonitoringid', $monthlymonitoringid);
$emergency_stmt->execute();
while ($emergency_row = $emergency_stmt->fetch(PDO::FETCH_ASSOC)) {
    $html_table .= '<tr>';
    $html_table .= '<td style="width:20%;">' . $emergency_row['roomtemp_location_name'] . '</td>';
    $html_table .= '<td style="width:20%;text-align:center;">' . $emergency_row['roomtemp_reading1'] . '&deg;C' . '</td>';
    $html_table .= '<td style="width:20%;text-align:center;">' . $emergency_row['roomtemp_reading2'] . '&deg;C' . '</td>';
    $html_table .= '<td style="width:20%;text-align:center;">' . $emergency_row['roomtemp_temperature_alarm'] . '&deg;C' . '</td>';
    $html_table .= '<td style="width:20%;text-align:center;">' . $emergency_row['roomtemp_remarks'] . '</td>';
    $html_table .= '</tr>';
}
$html_table .= '</table>
            </td>
        </tr>
    </table>';
$pdf->writeHTML($html_table, false, false, true, false, '');
//* -------------------------------- Designation --------------------------------
$pdf->Ln(2);
$pdf->SetFont('helvetica', '', 12);
$pdf->Cell(63.6, 9, 'Performed by:', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
$pdf->Cell(63.6, 9, 'Checked by:', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
$pdf->Ln(16);
$pdf->SetFont('helvetica', '', 9);
$pdf->Cell(50, 4, $interlocking_date_created, 0, 0, 'R', 0, '', 0, false, 'T', 'M');
//* -------------------------------- Responsible --------------------------------
//* MultiCell($w, $h, $txt, $border=0, $align='J', $fill=0, $ln=1, $x='', $y='', $reseth=true, $stretch=0, $ishtml=false, $autopadding=true, $maxh=0)
$pdf->Ln();
$pdf->SetFont('helvetica', '', 10);
$pdf->Image('@' . base64_decode($interlocking_performed_by_sign), 17, 180, 20, 11, '', '', '', false, 300, '', false, false, 0, false, false, false);
$pdf->Cell(43, 4, $interlocking_performed_by, 0, 0, 'L', 0, '', 0, false, 'T', 'M');
$pdf->MultiCell(20.6, 4, 'Interlocking RUD', 0, 'C', 0, '', '', '', false, 0, false, false, 0);
$pdf->Image('@' . base64_decode($interlocking_checked_by_sign), 73, 185, 28, 15, '', '', '', false, 300, '', false, false, 0, false, false, false);
$pdf->Cell(46.8, 4, $interlocking_checked_by, 0, 0, 'L', 0, '', 0, false, 'T', 'M');
$pdf->Cell(16.8, 4, '', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
$pdf->Ln();
$pdf->Cell(63.6, 4, $interlocking_performed_by_position, 0, 0, 'L', 0, '', 0, false, 'T', 'M');
$pdf->Cell(63.6, 4, $interlocking_checked_by_position, 0, 0, 'L', 0, '', 0, false, 'T', 'M');
$pdf->Ln(7);
$pdf->SetFont('helvetica', '', 9);
$pdf->Cell(50, 4, $electric_date_created, 0, 0, 'R', 0, '', 0, false, 'T', 'M');
$pdf->Ln();
$pdf->SetFont('helvetica', '', 10);
$pdf->Image('@' . base64_decode($electric_performed_by_sign), 17, 200, 20, 11, '', '', '', false, 300, '', false, false, 0, false, false, false);
$pdf->Cell(43, 4, $electric_performed_by, 0, 0, 'L', 0, '', 0, false, 'T', 'M');
$pdf->MultiCell(20.6, 4, 'Electric Fence', 0, 'C', 0, '', '', '', false, 0, false, false, 0);
$pdf->Image('@' . base64_decode($electric_checked_by_sign), 73, 200, 28, 15, '', '', '', false, 300, '', false, false, 0, false, false, false);
$pdf->Cell(46.8, 4, $electric_checked_by, 0, 0, 'L', 0, '', 0, false, 'T', 'M');
$pdf->Cell(16.8, 4, '', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
$pdf->Ln();
$pdf->Cell(63.6, 4, $electric_performed_by_position, 0, 0, 'L', 0, '', 0, false, 'T', 'M');
$pdf->Cell(63.6, 4, $electric_checked_by_position, 0, 0, 'L', 0, '', 0, false, 'T', 'M');
$pdf->Ln(7);
$pdf->SetFont('helvetica', '', 9);
$pdf->Cell(50, 4, $emergency_date_created, 0, 0, 'R', 0, '', 0, false, 'T', 'M');
$pdf->Ln();
$pdf->SetFont('helvetica', '', 10);
$pdf->Image('@' . base64_decode($emergency_performed_by_sign), 17, 215, 20, 11, '', '', '', false, 300, '', false, false, 0, false, false, false);
$pdf->Cell(43, 4, $emergency_performed_by, 0, 0, 'L', 0, '', 0, false, 'T', 'M');
$pdf->MultiCell(20.6, 4, 'Emergency Evaluation Switch', 0, 'C', 0, '', '', '', false, 0, false, false, 0);
$pdf->Image('@' . base64_decode($emergency_checked_by_sign), 73, 215, 28, 15, '', '', '', false, 300, '', false, false, 0, false, false, false);
$pdf->Cell(46.8, 4, $emergency_checked_by, 0, 0, 'L', 0, '', 0, false, 'T', 'M');
$pdf->Cell(16.8, 4, '', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
$pdf->SetFont('helvetica', '', 12);
$pdf->Cell(63.6, 4, 'Noted by:', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
$pdf->SetFont('helvetica', '', 10);
$pdf->Ln();
$pdf->Cell(63.6, 4, $emergency_performed_by_position, 0, 0, 'L', 0, '', 0, false, 'T', 'M');
$pdf->Cell(63.6, 4, $emergency_checked_by_position, 0, 0, 'L', 0, '', 0, false, 'T', 'M');
$pdf->Ln(9);
$pdf->SetFont('helvetica', '', 9);
$pdf->Cell(50, 4, $roomtemp_date_created, 0, 0, 'R', 0, '', 0, false, 'T', 'M');
$pdf->Ln();
$pdf->SetFont('helvetica', '', 10);
$pdf->Image('@' . base64_decode($roomtemp_performed_by_sign), 17, 233, 20, 11, '', '', '', false, 300, '', false, false, 0, false, false, false);
$pdf->Cell(41, 4, $roomtemp_performed_by, 0, 0, 'L', 0, '', 0, false, 'T', 'M');
$pdf->MultiCell(22.6, 4, 'Room Temperature', 0, 'C', 0, '', '', '', false, 0, false, false, 0);
$pdf->Image('@' . base64_decode($roomtemp_checked_by_sign), 73, 232, 28, 15, '', '', '', false, 300, '', false, false, 0, false, false, false);
$pdf->Cell(46.8, 4, $roomtemp_checked_by, 0, 0, 'L', 0, '', 0, false, 'T', 'M');
$pdf->Cell(16.8, 4, '', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
$pdf->Image('@' . base64_decode($monitoring_noted_by_sign), 138, 232, 28, 15, '', '', '', false, 300, '', false, false, 0, false, false, false);
$pdf->Cell(63.6, 4, $monitoring_noted_by, 0, 0, 'L', 0, '', 0, false, 'T', 'M');
$pdf->Ln();
$pdf->Cell(63.6, 4, $roomtemp_performed_by_position, 0, 0, 'L', 0, '', 0, false, 'T', 'M');
$pdf->Cell(63.6, 4, $roomtemp_checked_by_position, 0, 0, 'L', 0, '', 0, false, 'T', 'M');
$pdf->Cell(63.6, 4, $monitoring_noted_by_position, 0, 0, 'L', 0, '', 0, false, 'T', 'M');
$PHD = null; //* ======== Close Connection ========
$filename = "PHD Monthly Monitoring Checklist.pdf";
$pdf->Output($filename, 'I'); //* Close and output PDF document