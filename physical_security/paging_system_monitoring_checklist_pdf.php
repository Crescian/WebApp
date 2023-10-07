<?php
include '../vendor/tcpdf/tcpdf.php';
include_once '../configuration/connection.php';
$BannerWebLive = $conn->db_conn_bannerweb(); //* Banner Web Database connection
$PHD = $conn->db_conn_physical_security(); //* Physical Security Database connection

function getJobPosition($employee, $BannerWebLive)
{
    if (!empty($employee)) {
        $sql_pos_name = "SELECT pos_name FROM prl_employee 
            INNER JOIN prl_position ON prl_position.pos_code = prl_employee.pos_code
            WHERE (emp_fn || ' ' || emp_sn) = :employee";
        $sql_pos_name_stmt = $BannerWebLive->prepare($sql_pos_name);
        $sql_pos_name_stmt->bindParam(':employee', $employee);
        $sql_pos_name_stmt->execute();
        $sql_pos_name_row = $sql_pos_name_stmt->fetch(PDO::FETCH_ASSOC);
        return $sql_pos_name_row['pos_name'];
    } else {
        return '';
    }
    $BannerWebLive = null; //* ======== Close Connection ========
}

$pagingheaderid = $_GET['d'];
//* -------------------------------- Header --------------------------------
$sqlstring_header = "SELECT paging_date_created,
    paging_performed_by1,encode(paging_performed_by1_sign, 'escape') AS paging_performed_by1_sign,paging_performed_by1_date,
    paging_performed_by2,encode(paging_performed_by2_sign, 'escape') AS paging_performed_by2_sign,paging_performed_by2_date,
    paging_performed_by3,encode(paging_performed_by3_sign, 'escape') AS paging_performed_by3_sign,paging_performed_by3_date,
    paging_checked_by,CASE WHEN paging_checked_by_acknowledge = true THEN encode(paging_checked_by_sign, 'escape') ELSE '' END AS paging_checked_by_sign,
    paging_noted_by,CASE WHEN paging_noted_by_acknowledge = true THEN encode(paging_noted_by_sign, 'escape') ELSE '' END AS paging_noted_by_sign
    FROM phd_paging_monitoring_header WHERE pagingheaderid = :pagingheaderid";
$result_header_stmt = $PHD->prepare($sqlstring_header);
$result_header_stmt->bindParam(':pagingheaderid', $pagingheaderid);
$result_header_stmt->execute();
$result_header_row = $result_header_stmt->fetch(PDO::FETCH_ASSOC);
$date_created = $result_header_row['paging_date_created'];
//* ======== Performed By ========
$paging_performed_by1 = $result_header_row['paging_performed_by1'];
$performed_by1_position = getJobPosition($paging_performed_by1, $BannerWebLive);
$paging_performed_by1_sign = $result_header_row['paging_performed_by1_sign'];
$paging_performed_by1_date = $result_header_row['paging_performed_by1_date'];

$paging_performed_by2 = $result_header_row['paging_performed_by2'];
$performed_by2_position = getJobPosition($paging_performed_by2, $BannerWebLive);
$paging_performed_by2_sign = $result_header_row['paging_performed_by2_sign'];
$paging_performed_by2_date = $result_header_row['paging_performed_by2_date'];

$paging_performed_by3 = $result_header_row['paging_performed_by3'];
$performed_by3_position = getJobPosition($paging_performed_by3, $BannerWebLive);
$paging_performed_by3_sign = $result_header_row['paging_performed_by3_sign'];
$paging_performed_by3_date = $result_header_row['paging_performed_by3_date'];
//* ======== Checked By ========
$paging_checked_by = $result_header_row['paging_checked_by'];
$checked_by_position = getJobPosition($paging_checked_by, $BannerWebLive);
$paging_checked_by_sign = $result_header_row['paging_checked_by_sign'];
//* ======== Noted By ========
$paging_noted_by = $result_header_row['paging_noted_by'];
$noted_by_position = getJobPosition($paging_noted_by, $BannerWebLive);
$paging_noted_by_sign = $result_header_row['paging_noted_by_sign'];
//* -------------------------------- Details --------------------------------
$sqlstring_details = "SELECT * FROM phd_paging_monitoring_details WHERE pagingheader_id = :pagingheader_id
    ORDER BY paging_category_name,paging_location_name ASC";
$result_details_stmt = $PHD->prepare($sqlstring_details);
$result_details_stmt->bindParam(':pagingheader_id', $pagingheaderid);
$result_details_stmt->execute();
$resultData_List = array();
while ($details_row = $result_details_stmt->fetch(PDO::FETCH_ASSOC)) {
    $resultData_List[$details_row['paging_category_name']][] = $details_row; //* ======== 2D Array
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
            $this->Cell(0, 0, 'SEMI-ANNUAL PAGING SYSTEM MONITORING CHECKLIST', 0, 0, 'C');
        }
        if ($this->PageNo() >= 2) {
            $this->SetFont('helvetica', '', 10);
            $this->Cell(0, 7, '', 0, 1, 'L');
            $this->Cell(0, 5, 'Semi-Annual Paging System Monitoring Checklist', 0, 0, 'L');
        }
    }

    //* Page footer
    public function Footer()
    {
        //* Position at 22 mm from bottom
        $this->SetY(-22);
        $this->SetFont('helvetica', '', 10);
        //* Page Number
        //* Cell(w, h = 0, txt = '', border = 0, ln = 0, align = '', fill = 0, link = nil, stretch = 0, ignore_min_height = false, calign = 'T', valign = 'M')
        $this->Cell(191, '', 'PHD/IS-004-00', 0, false, 'R', 0, '', 0, false, 'T', 'M');
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
        <table style="width:100%;" border="1" cellspacing="0" cellpadding="1">
            <tr>
                <th rowspan="2" style="width:38%;text-align: center;vertical-align:middle;"><div style="font-size:6px">&nbsp;</div>Paging Components/Location</th>
                <th colspan="2" style="width:30%;text-align: center;vertical-align:middle;">Status</th>
                <th rowspan="2" style="width:32%;text-align: center;vertical-align:middle;"><div style="font-size:6px">&nbsp;</div>Remarks</th>
            </tr>
            <tr>
                <th style="width:15%;text-align: center;vertical-align:middle;">Ok</th>
                <th style="width:15%;text-align: center;vertical-align:middle;">Defective</th>
            </tr>
        </table>';
        $this->writeHTML($html_header, false, false, true, false, '');
    }

    //* Colored table
    public function OutputTable($resultData_List)
    {
        $w = array(73, 28, 28, 62); //* width
        $this->TableHeader();
        foreach ($resultData_List as $details_category => $details_row) {
            $this->Cell(0, 5, $details_category, 1, 0, 'L', 0);
            $this->Ln();
            foreach ($details_row as $row) {
                $num_pages = $this->getNumPages(); //* Get current number of pages.
                $this->startTransaction();
                $this->Cell($w[0], 5, $row['paging_location_name'], 1, 0, 'L', 0);
                if ($row['paging_status_ok'] == '1') {
                    $this->SetFont('zapfdingbats', '', 12);
                    $this->Cell($w[1], 5, '4', 1, 0, 'C', 0);
                } else {
                    $this->Cell($w[1], 5, '', 1, 0, 'C', 0);
                }
                if ($row['paging_status_defective'] == '1') {
                    $this->SetFont('zapfdingbats', '', 12);
                    $this->Cell($w[2], 5, '4', 1, 0, 'C', 0);
                } else {
                    $this->Cell($w[2], 5, '', 1, 0, 'C', 0);
                }
                $this->SetFont('helvetica', '', 12);
                $this->Cell($w[3], 5, $row['paging_remarks'], 1, 0, 'C', 0);
                $this->Ln();
                if ($num_pages < $this->getNumPages()) { //* If old number of pages is less than the new number of pages,we hit an automatic page break, and need to rollback.
                    $this->rollbackTransaction(true); //* Undo adding the row.
                    //* Add page
                    $this->SetMargins(12.5, 13, 12.5);
                    $this->AddPage();
                    //* Draw the header.
                    $this->TableHeader();
                    // * Re-do the row.
                    $this->Cell('', 5, $details_category, 1, 0, 'L', 0);
                    $this->Ln();
                } else {
                    $this->commitTransaction(); //* Otherwise we are fine with this row, discard undo history.
                }
            }
        }
        $this->Cell(array_sum($w), 0, '', 'T');
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
$pdf->SetAutoPageBreak(TRUE, 25); //* set page break
$pdf->AddPage();
$pdf->Ln(32.8);
//* -------------------------------- Info --------------------------------
$pdf->SetFont('helvetica', '', 12);
$pdf->Cell(154, 0, 'Date: ', 0, 0, 'R');
$pdf->Cell(37, 0, date_format(date_create($date_created), 'F d,Y'), 0, 0, 'C');
$pdf->Ln(7);
$pdf->OutputTable($resultData_List); //* print colored table
if ($paging_performed_by2 == '' && $paging_performed_by3 == '') { //* single prepared by
    //* -------------------------------- Designation --------------------------------
    $pdf->Ln(7);
    $pdf->SetFont('helvetica', '', 12);
    $pdf->Cell(63.6, 9, 'Prepared by:', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
    $pdf->Cell(63.6, 9, 'Checked by:', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
    $pdf->Cell(63.6, 9, 'Noted by:', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
    $pdf->Ln(10);
    //* -------------------------------- Signature --------------------------------
    // $pdf->Image('@' . base64_decode($paging_performed_by1_sign), 5, '', 40, 20, '', '', '', false, 300, '', false, false, 0, false, false, false);
    // $pdf->Image('@' . base64_decode($paging_checked_by_sign), 70, '', 40, 20, '', '', '', false, 300, '', false, false, 0, false, false, false);
    // $pdf->Image('@' . base64_decode($paging_noted_by_sign), 130, '', 40, 20, '', '', '', false, 300, '', false, false, 0, false, false, false);
    $pdf->Ln(13);
    //* -------------------------------- Responsible --------------------------------
    $pdf->SetFont('helvetica', '', 10);
    $pdf->Cell(63.6, 5, $paging_performed_by1, 0, 0, 'L', 0, '', 0, false, 'T', 'M');
    $pdf->Cell(63.6, 5, $paging_checked_by, 0, 0, 'L', 0, '', 0, false, 'T', 'M');
    $pdf->Cell(63.6, 5, $paging_noted_by, 0, 0, 'L', 0, '', 0, false, 'T', 'M');
    $pdf->Ln();
    //* -------------------------------- Job Position --------------------------------
    $pdf->Cell(63.6, 4, $performed_by1_position, 0, 0, 'L', 0, '', 0, false, 'T', 'M');
    $pdf->Cell(63.6, 4, $checked_by_position, 0, 0, 'L', 0, '', 0, false, 'T', 'M');
    $pdf->Cell(63.6, 4, $noted_by_position, 0, 0, 'L', 0, '', 0, false, 'T', 'M');
} else { //* multiple prepared by
    //* -------------------------------- Designation --------------------------------
    $pdf->Ln(4);
    $pdf->SetFont('helvetica', '', 12);
    $pdf->Cell(63.6, 9, 'Prepared by:', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
    $pdf->Ln(7);
    if ($paging_performed_by1 == $paging_performed_by3) {
        //* -------------------------------- Signature --------------------------------
        // $pdf->Image('@' . base64_decode($paging_performed_by1_sign), 5, '', 40, 20, '', '', '', false, 300, '', false, false, 0, false, false, false);
        // $pdf->Image('@' . base64_decode($paging_performed_by2_sign), 70, '', 40, 20, '', '', '', false, 300, '', false, false, 0, false, false, false);
        $pdf->Ln(13);
        //* -------------------------------- Responsible --------------------------------
        $pdf->SetFont('helvetica', '', 10);
        $pdf->Cell(63.6, 5, $paging_performed_by1, 0, 0, 'L', 0, '', 0, false, 'T', 'M');
        $pdf->Cell(63.6, 5, $paging_performed_by2, 0, 0, 'L', 0, '', 0, false, 'T', 'M');
        $pdf->Ln();
        //* -------------------------------- Job Position --------------------------------
        $pdf->Cell(63.6, 4, $performed_by1_position, 0, 0, 'L', 0, '', 0, false, 'T', 'M');
        $pdf->Cell(63.6, 4, $performed_by2_position, 0, 0, 'L', 0, '', 0, false, 'T', 'M');
        $pdf->Ln(6);
        //* -------------------------------- Prepared Date --------------------------------
        $pdf->Cell(63.6, 4, 'Date: ' . date_format(date_create($paging_performed_by3_date), 'm-d-y'), 0, 0, 'L', 0, '', 0, false, 'T', 'M');
        $pdf->Cell(63.6, 4, 'Date: ' . date_format(date_create($paging_performed_by2_date), 'm-d-y'), 0, 0, 'L', 0, '', 0, false, 'T', 'M');
        $pdf->Ln(10);
    } else {
        //* -------------------------------- Signature --------------------------------
        // $pdf->Image('@' . base64_decode($paging_performed_by1_sign), 5, '', 40, 20, '', '', '', false, 300, '', false, false, 0, false, false, false);
        // $pdf->Image('@' . base64_decode($paging_performed_by2_sign), 70, '', 40, 20, '', '', '', false, 300, '', false, false, 0, false, false, false);
        // $pdf->Image('@' . base64_decode($paging_performed_by3_sign), 130, '', 40, 20, '', '', '', false, 300, '', false, false, 0, false, false, false);
        $pdf->Ln(13);
        //* -------------------------------- Responsible --------------------------------
        $pdf->SetFont('helvetica', '', 10);
        $pdf->Cell(63.6, 5, $paging_performed_by1, 0, 0, 'L', 0, '', 0, false, 'T', 'M');
        $pdf->Cell(63.6, 5, $paging_performed_by2, 0, 0, 'L', 0, '', 0, false, 'T', 'M');
        $pdf->Cell(63.6, 5, $paging_performed_by3, 0, 0, 'L', 0, '', 0, false, 'T', 'M');
        $pdf->Ln();
        //* -------------------------------- Job Position --------------------------------
        $pdf->Cell(63.6, 4, $performed_by1_position, 0, 0, 'L', 0, '', 0, false, 'T', 'M');
        $pdf->Cell(63.6, 4, $performed_by2_position, 0, 0, 'L', 0, '', 0, false, 'T', 'M');
        $pdf->Cell(63.6, 4, $performed_by3_position, 0, 0, 'L', 0, '', 0, false, 'T', 'M');
        $pdf->Ln(6);
        //* -------------------------------- Prepared Date --------------------------------
        $pdf->Cell(63.6, 4, 'Date: ' . date_format(date_create($paging_performed_by1_date), 'm-d-y'), 0, 0, 'L', 0, '', 0, false, 'T', 'M');
        $pdf->Cell(63.6, 4, 'Date: ' . date_format(date_create($paging_performed_by2_date), 'm-d-y'), 0, 0, 'L', 0, '', 0, false, 'T', 'M');
        if ($paging_performed_by3_date == '') {
            $pdf->Cell(63.6, 4, $paging_performed_by3_date, 0, 0, 'L', 0, '', 0, false, 'T', 'M');
        } else {
            $pdf->Cell(63.6, 4, 'Date: ' . date_format(date_create($paging_performed_by3_date), 'm-d-y'), 0, 0, 'L', 0, '', 0, false, 'T', 'M');
        }
        $pdf->Ln(10);
    }
    //* -------------------------------- Designation --------------------------------
    $pdf->SetFont('helvetica', '', 12);
    $pdf->Cell(63.6, 9, 'Checked by:', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
    $pdf->Cell(63.6, 9, 'Noted by:', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
    $pdf->Ln(5.6);
    //* -------------------------------- Signature --------------------------------
    // $pdf->Image('@' . base64_decode($paging_checked_by_sign), 5, 20, 40, 20, '', '', '', false, 300, '', false, false, 0, false, false, false);
    // $pdf->Image('@' . base64_decode($paging_noted_by_sign), 70, 20, 40, 20, '', '', '', false, 300, '', false, false, 0, false, false, false);
    $pdf->Ln(15);
    //* -------------------------------- Responsible --------------------------------
    $pdf->SetFont('helvetica', '', 10);
    $pdf->Cell(63.6, 5, $paging_checked_by, 0, 0, 'L', 0, '', 0, false, 'T', 'M');
    $pdf->Cell(63.6, 5, $paging_noted_by, 0, 0, 'L', 0, '', 0, false, 'T', 'M');
    $pdf->Ln();
    //* -------------------------------- Job Position --------------------------------
    $pdf->Cell(63.6, 4, $checked_by_position, 0, 0, 'L', 0, '', 0, false, 'T', 'M');
    $pdf->Cell(63.6, 4, $noted_by_position, 0, 0, 'L', 0, '', 0, false, 'T', 'M');
}


$filename = "Paging System Monitoring Checklist.pdf";
$pdf->Output($filename, 'I'); //* Close and output PDF document