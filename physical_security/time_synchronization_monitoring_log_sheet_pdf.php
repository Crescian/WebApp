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
    while ($sql_pos_name_row = $sql_pos_name_stmt->fetch(PDO::FETCH_ASSOC)) {
        return $sql_pos_name_row['pos_name'];
    }
    $BannerWebLive = null; //* ======== Close Connection ========
}

$timesyncheaderid = $_GET['d'];

//* -------------------------------- Header --------------------------------
$sqlstring_header = "SELECT date_created,
    prepared_by,
    CASE WHEN prepared_by_acknowledge = true THEN encode(prepared_by_sign, 'escape') ELSE '' END AS prepared_by_sign,
    checked_by,
    CASE WHEN checked_by_acknowledge = true THEN encode(checked_by_sign, 'escape') ELSE '' END AS checked_by_sign,
    noted_by,
    CASE WHEN noted_by_acknowledge = true THEN encode(noted_by_sign, 'escape') ELSE '' END AS noted_by_sign
    FROM phd_time_sync_log_header WHERE timesyncheaderid = '{$timesyncheaderid}'";
$result_header_stmt = $PHD->prepare($sqlstring_header);
$result_header_stmt->execute();
$result_Res = $result_header_stmt->fetchAll();
// while ($result_header_row = $result_header_stmt->fetch(PDO::FETCH_ASSOC)) {
foreach ($result_Res as $result_header_row) {
    $date_created = $result_header_row['date_created'];
    $prepared_by = $result_header_row['prepared_by'];
    $prepared_by_position = getJobPosition($prepared_by, $BannerWebLive);
    $prepared_by_sign = $result_header_row['prepared_by_sign'];
    $checked_by = $result_header_row['checked_by'];
    $checked_by_position = getJobPosition($checked_by, $BannerWebLive);
    $checked_by_sign = $result_header_row['checked_by_sign'];
    $noted_by = $result_header_row['noted_by'];
    $noted_by_position = getJobPosition($noted_by, $BannerWebLive);
    $noted_by_sign = $result_header_row['noted_by_sign'];
}
//* -------------------------------- Details --------------------------------
$sqlstring_details = "SELECT * FROM phd_time_sync_log_details 
    INNER JOIN phd_surveillance_name ON surveillance_name = phd_time_sync_log_details.surveillance_no 
    WHERE timesyncheader_id = :timesyncheader_id ORDER BY surveillanceid ASC";
$result_details_stmt = $PHD->prepare($sqlstring_details);
$result_details_stmt->bindParam(':timesyncheader_id', $timesyncheaderid);
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
            $this->Image('../vendor/images/Banner Logo-colored.jpg', 89, 12, 40, 9.8, 'JPG', '', 'T', true, 300, '', false, false, 0, false, false, false);
            $this->Image('../vendor/images/ISO Logo-colored.jpg', 163, 12, 41, 11.5, 'JPG', '', 'T', true, 300, '', false, false, 0, false, false, false);
            $this->Ln(14);
            //* Cell(w, h = 0, txt = '', border = 0, ln = 0, align = '', fill = 0, link = nil, stretch = 0, ignore_min_height = false, calign = 'T', valign = 'M')
            $this->SetFont('helvetica', '', 12);
            $this->Cell(0, 0, 'Physical Security Department', 0, 1, 'C');
            $this->SetFont('helvetica', 'B', 14);
            $this->Cell(0, 0, 'TIME SYNCHRONIZATION MONITORING LOG SHEET', 0, 0, 'C');
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
        $this->SetY(-16);
        $this->SetFont('helvetica', '', 10);
        //* Page Number
        //* Cell(w, h = 0, txt = '', border = 0, ln = 0, align = '', fill = 0, link = nil, stretch = 0, ignore_min_height = false, calign = 'T', valign = 'M')
        $this->Cell(191, '', 'PHD/IS-026-00', 0, false, 'R', 0, '', 0, false, 'T', 'M');
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
    public function TableHeader($header, $w)
    {
        //* Colors, line width and bold font
        //* Header
        $this->SetFont('helvetica', '', 11);
        $this->SetFillColor(255, 255, 255);
        $this->SetTextColor(0);
        $this->SetLineWidth(0);
        $this->SetFont('', '');
        $num_headers = count($header);
        for ($i = 0; $i < $num_headers; ++$i) {
            $this->Cell($w[$i], 2, $header[$i], 1, 0, 'C', 1);
        }
        $this->Ln();
        //* Color and font restoration
        // $this->SetFillColor(224, 235, 255);
        $this->SetTextColor(0);
        $this->SetFont('');
    }
    //* Colored table
    public function ColoredTable($header, $data)
    {
        $w = array(32, 50, 50, 29, 30);
        $this->TableHeader($header, $w);
        //* Data
        $fill = 0;
        $this->SetFont('helvetica', '', 9.8);
        foreach ($data as $row) {
            //* Get current number of pages.
            $num_pages = $this->getNumPages();
            $this->startTransaction();
            $this->Cell($w[0], 4, $row['surveillance_no'], 1, 0, 'L', $fill);
            $this->Cell($w[1], 4, date_format(date_create($row['real_time']), 'H:i:s'), 1, 0, 'C', $fill);
            $this->Cell($w[2], 4, date_format(date_create($row['actual_time']), 'H:i:s'), 1, 0, 'C', $fill);
            $this->Cell($w[3], 4, $row['time_gap'], 1, 0, 'C', $fill);
            $this->Cell($w[4], 4, $row['remarks'], 1, 0, 'C', $fill);
            $this->Ln();
            //* If old number of pages is less than the new number of pages,
            //* we hit an automatic page break, and need to rollback.
            if (
                $num_pages < $this->getNumPages()
            ) {
                //* Undo adding the row.
                $this->rollbackTransaction(true);
                //* Adds a bottom line onto the current page. 
                //* Note: May cause page break itself.
                $this->Cell(array_sum($w), 0, '', 'T');
                //* add page
                $this->SetMargins(12.5, 12, 12.5);
                $this->AddPage();
                //* Draw the header.
                $this->TableHeader($header, $w);
                //* Re-do the row.
                $this->Cell($w[0], 5, $row['surveillance_no'], 'LR', 0, 'C', $fill);
                $this->Cell($w[1], 5, date_format(date_create($row['real_time']), 'H:i:s'), 'LR', 0, 'C', $fill);
                $this->Cell($w[2], 5, date_format(date_create($row['actual_time']), 'H:i:s'), 'LR', 0, 'C', $fill);
                $this->Cell($w[3], 5, $row['time_gap'], 'LR', 0, 'C', $fill);
                $this->Cell($w[4], 5, $row['remarks'], 'LR', 0, 'C', $fill);
                $this->Ln();
            } else {
                //* Otherwise we are fine with this row, discard undo history.
                $this->commitTransaction();
            }
            $fill = !$fill;
        }
        $this->Cell(array_sum($w), 0, '', 'T');
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
$pdf->SetAutoPageBreak(TRUE, 0); //* set page break
$pdf->AddPage();
$pdf->Ln(33);
//* -------------------------------- Info --------------------------------
$pdf->SetFont('helvetica', '', 12);
$pdf->Cell(161, 0, 'Date: ', 0, 0, 'R');
$pdf->Cell(30, 0, date_format(date_create($date_created), 'F d, Y'), 0, 0, 'C');
$pdf->Ln(7);
$header = array('Surveillance No.', 'ACS Time', 'Surveillance Time', 'Time Gap', 'Remarks'); //* column titles
$pdf->ColoredTable($header, $result_details_row); //* print colored table
$pdf->Ln(-1);
//* -------------------------------- Designation --------------------------------
$pdf->SetFont('helvetica', '', 12);
$pdf->Cell(63.6, 9, 'Performed by:', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
$pdf->Cell(63.6, 9, 'Checked by:', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
$pdf->Cell(63.6, 9, 'Noted by:', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
$pdf->Ln(16);
//* -------------------------------- Signature --------------------------------
//* Image($file, $x='', $y='', $w=0, $h=0, $type='', $link='', $align='', $resize=false, $dpi=300, $palign='', $ismask=false, $imgmask=false, $border=0, $fitbox=false, $hidden=false, $fitonpage=false)
$pdf->Image('@' . base64_decode($prepared_by_sign), 10, 128, 40, 20, '', '', '', false, 300, '', false, false, 0, false, false, false);
$pdf->Image('@' . base64_decode($checked_by_sign), 70, 128, 40, 20, '', '', '', false, 300, '', false, false, 0, false, false, false);
$pdf->Image('@' . base64_decode($noted_by_sign), 135, 128, 40, 20, '', '', '', false, 300, '', false, false, 0, false, false, false);
// $pdf->Ln(6);
//* -------------------------------- Responsible --------------------------------
$pdf->SetFont('helvetica', '', 10);
$pdf->Cell(46.8, 0, $prepared_by, 0, 0, 'L', 0, '', 0, false, 'T', 'M');
$pdf->Cell(16.8, 0, '', 0, 0, 'L', '', '', 0, false, 'T', 'M');
$pdf->Cell(46.8, 0, $checked_by, 0, 0, 'L', 0, '', 0, false, 'T', 'M');
$pdf->Cell(16.8, 0, '', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
$pdf->Cell(46.8, 0, $noted_by, 0, 0, 'L', 0, '', 0, false, 'T', 'M');
$pdf->Ln();
//* -------------------------------- Job Title --------------------------------
$pdf->Cell(46.8, 0, $prepared_by_position, 0, 0, 'L', 0, '', 0, false, 'T', 'M');
$pdf->Cell(16.8, 0, '', 0, 0, 'C', 0, '', 0, false, 'T', 'M');
$pdf->Cell(46.8, 0, $checked_by_position, 0, 0, 'L', 0, '', 0, false, 'T', 'M');
$pdf->Cell(16.8, 0, '', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
$pdf->Cell(46.8, 0, $noted_by_position, 0, 0, 'L', 0, '', 0, false, 'T', 'M');

$filename = "Time Synchronization Monitoring Log Sheet.pdf";
$pdf->Output($filename, 'I'); //* Close and output PDF document