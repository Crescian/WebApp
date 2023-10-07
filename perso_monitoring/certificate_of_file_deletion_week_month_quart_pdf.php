<?php
include '../configuration/connection.php';
include '../vendor/tcpdf/tcpdf.php';
date_default_timezone_set('Asia/Manila');

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

function fetchJobTitle($php_fetch_bannerweb_api, $emp_name)
{
    $sqlstring = "SELECT pos_name FROM prl_employee INNER JOIN prl_position ON prl_position.pos_code = prl_employee.pos_code WHERE (emp_fn || ' ' || emp_sn) = '{$emp_name}';";
    $data_result = sqlQuery($sqlstring, $php_fetch_bannerweb_api);
    foreach ($data_result['data'] as $row) {
        return $row['pos_name'];
    }
}

function fetchSignature($php_fetch_bannerweb_api, $emp_name)
{
    $sqlstring = "SELECT encode(employee_signature, 'escape') as employee_signature FROM bpi_employee_signature WHERE emp_name = '{$emp_name}';";
    $data_result = sqlQuery($sqlstring, $php_fetch_bannerweb_api);
    foreach ($data_result['data'] as $row) {
        return $row['employee_signature'];
    }
}

$currentDate = date('Y-m-d');
$categ = $_GET['d'];
$company = $_GET['com'];

$sqlstring = "SELECT file_received_date,file_filename,file_filesize,prepared_by,checked_by,noted_by,file_deleted_date FROM bpi_perso_file_deletion WHERE file_company ILIKE '%{$company}%'";
switch ($categ) {
    case 'wee':
        $category = 'Weekly';
        $date_from = $_GET['df'];
        $date_to = $_GET['dt'];
        $dateFilter = $_GET['f'];
        $inField = ($dateFilter == 'tf1') ? 'file_received_date' : (($dateFilter == 'tf2') ? 'file_deleted_date' : 'file_delivery_date');
        $sqlstring .= " AND {$inField} BETWEEN '{$date_from}' AND '{$date_to}' AND file_certified = true ORDER BY {$inField};";
        break;
    case 'mon':
        $category = 'Monthly';
        $month_date = $_GET['m'];
        $sqlstring .= " AND TO_CHAR(file_received_date, 'MM YYYY') BETWEEN '{$month_date}' AND '{$month_date}' AND file_certified = true ORDER BY TO_CHAR(file_received_date, 'MM-dd-YY') ASC;";
        break;
    case 'qua':
        $category = 'Quarterly';
        $month_from = $_GET['df'];
        $month_to = $_GET['dt'];
        $sqlstring .= " AND TO_CHAR(file_received_date, 'YYYY MM') BETWEEN '{$month_from}' AND '{$month_to}' AND file_certified = true ORDER BY TO_CHAR(file_received_date, 'YYYY MM DD') ASC;";
        break;
}
$data_result = sqlQuery($sqlstring, $php_fetch_perso_api);
//* ======== Performed By ========
$prepared_by = 'Jenny Rose Corpuz';
$prepared_by_position = fetchJobTitle($php_fetch_bannerweb_api, $prepared_by);
$prepared_by_sign = fetchSignature($php_fetch_bannerweb_api, $prepared_by);
//* ======== Checked By ========
$checked_by = 'Justine Manalo';
$checked_by_position = fetchJobTitle($php_fetch_bannerweb_api, $checked_by);
$checked_by_sign = fetchSignature($php_fetch_bannerweb_api, $checked_by);
//* ======== Noted By ========
$noted_by = 'Mary Jane Ang';
$noted_by_position = fetchJobTitle($php_fetch_bannerweb_api, $noted_by);
$noted_by_sign = fetchSignature($php_fetch_bannerweb_api, $noted_by);

class MYPDF extends TCPDF
{ //* extend TCPF with custom functions
    //* Page header
    public function Header()
    {
        if ($this->PageNo() == 1) {
            //* Image($file, $x='', $y='', $w=0, $h=0, $type='', $link='', $align='', $resize=false, $dpi=300, $palign='', $ismask=false, $imgmask=false, $border=0, $fitbox=false, $hidden=false, $fitonpage=false)
            $this->Image('../vendor/images/Banner Logo-colored.jpg', 144, 11, 43, 11, 'JPG', '', 'T', true, 300, '', false, false, 0, false, false, false);
            $this->Image('../vendor/images/ISO Logo-colored.jpg', 276, 11, 42, 12, 'JPG', '', 'T', true, 300, '', false, false, 0, false, false, false);
            $this->Ln(14.5);
            //* Cell(w, h = 0, txt = '', border = 0, ln = 0, align = '', fill = 0, link = nil, stretch = 0, ignore_min_height = false, calign = 'T', valign = 'M')
            $this->SetFont('helvetica', '', 12);
            $this->Cell(0, 0, 'Information Security/Data Protection Department', 0, 1, 'C');
            $this->SetFont('helvetica', 'B', 14);
            $this->Cell(205, 0, 'CERTIFICATE OF FILE DELETION SUMMARY', 0, 0, 'R');
        }
        if ($this->PageNo() >= 2) {
            $this->SetFont('helvetica', '', 10);
            $this->Cell(0, 7, '', 0, 1, 'L');
            $this->Cell(0, 5, 'Certificate of File Deletion Summary', 0, 0, 'L');
        }
    }
    //* Page footer
    public function Footer()
    {
        //* Position at 26 mm from bottom
        $this->SetY(-26);
        $this->SetFont('helvetica', '', 10);
        //* Cell(w, h = 0, txt = '', border = 0, ln = 0, align = '', fill = 0, link = nil, stretch = 0, ignore_min_height = false, calign = 'T', valign = 'M')
        $this->Cell(305, '', 'ISD/XX-036-00', 0, 0, 'R', 0, '', 0, false, '', '');
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
        }
    }

    public function TableHeader($header, $w)
    {
        $this->SetFont('helvetica', 'B');
        $num_headers = count($header);
        for ($i = 0; $i < $num_headers; ++$i) {
            $this->Cell($w[$i], 7, $header[$i], 1, 0, 'C', 0);
        }
        $this->Ln();
    }
    //* Colored table
    public function OutputTable($header, $data_result)
    {
        $w = array(25, 127, 27, 34, 34, 34, 23); //* set column width
        $this->TableHeader($header, $w);
        $this->SetFont('helvetica', '', 10);
        foreach ($data_result['data'] as $row) { //* Data
            $num_pages = $this->getNumPages(); //* Get current number of pages.
            $this->startTransaction();
            $this->Cell($w[0], 10, date_format(date_create($row['file_received_date']), 'm-d-y'), 1, 0, 'C', 0);
            $this->MultiCell($w[1], 10, $row['file_filename'], 1, 'L', 0, 0, '', '', true, 0, false, true, 10, 'M', false);
            $this->Cell($w[2], 10, $row['file_filesize'], 1, 0, 'R', 0);
            $this->Cell($w[3], 10, $row['prepared_by'], 1, 0, 'C', 0);
            $this->Cell($w[4], 10, $row['checked_by'], 1, 0, 'C', 0);
            $this->Cell($w[5], 10, $row['noted_by'], 1, 0, 'C', 0);
            $this->Cell($w[6], 10, date_format(date_create($row['file_deleted_date']), 'm-d-y'), 1, 0, 'C', 0);
            $this->Ln();
            //* If old number of pages is less than the new number of pages,we hit an automatic page break, and need to rollback.
            if ($num_pages < $this->getNumPages()) {
                $this->rollbackTransaction(true); //* Undo adding the row.
                //* Add page
                $this->SetMargins(12.5, 13, 12.5);
                $this->AddPage();
                $this->TableHeader($header, $w); //* Draw the header.
                //* Re-do the row.
                $this->SetFont('helvetica', '', 10);
                $this->Cell($w[0], 10, date_format(date_create($row['file_received_date']), 'm-d-y'), 1, 0, 'C', 0);
                $this->MultiCell($w[1], 10, $row['file_filename'], 1, 'L', 0, 0, '', '', true, 0, false, true, 10, 'T', false);
                $this->Cell($w[2], 10, $row['file_filesize'], 1, 0, 'R', 0);
                $this->Cell($w[3], 10, $row['prepared_by'], 1, 0, 'C', 0);
                $this->Cell($w[4], 10, $row['checked_by'], 1, 0, 'C', 0);
                $this->Cell($w[5], 10, $row['noted_by'], 1, 0, 'C', 0);
                $this->Cell($w[6], 10, date_format(date_create($row['file_deleted_date']), 'm-d-y'), 1, 0, 'C', 0);
                $this->Ln();
            } else {
                $this->commitTransaction(); //* Otherwise we are fine with this row, discard undo history.
            }
        }
    }
}

$pdf = new MYPDF("L", PDF_UNIT, "LONG", true, 'UTF-8', false); //* create new PDF document
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED); //* set default monospaced font
$pdf->SetAutoPageBreak(TRUE, 30); //* set page break
//* set margins
//* $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetMargins(12.5, 12, 12.5);
$pdf->setPrintHeader(true); //* include header
$pdf->setPrintFooter(true); //* include footer
$pdf->AddPage(); //* Add page
$pdf->Ln(30); //* Next Line
//* ---------- Info ----------
//* Cell(w, h = 0, txt = '', border = 0, ln = 0, align = '', fill = 0, link = nil, stretch = 0, ignore_min_height = false, calign = 'T', valign = 'M')
$pdf->SetFont('helvetica', '', 12);
$pdf->Cell(153, 0, 'Name of Issuer: ' . $company, 0, 0, 'L');
$pdf->Cell(152, 0, 'Date: ' . date_format(date_create($currentDate), 'F d, Y'), 0, 0, 'R');
$pdf->Ln(6);
switch ($category) {
    case 'Weekly':
        $pdf->Cell(153, 0, 'Deletion Period: ' . $category . ' (' . date_format(date_create($date_from), 'M d,Y') . ' to ' . date_format(date_create($date_to), 'M d,Y') . ')', 0, 0, 'L');
        break;
    case 'Monthly':
        $pdf->Cell(153, 0, 'Deletion Period: ' . $category . ' (' . date('F Y', strtotime(str_replace(' ', '/01/', $month_date))) . ')', 0, 0, 'L');
        break;
    case 'Quarterly':
        $pdf->Cell(153, 0, 'Deletion Period: ' . $category . ' (' . date('F Y', strtotime(str_replace(' ', '/', $month_from) . '/01')) . ' To ' . date('F Y', strtotime(str_replace(' ', '/', $month_to) . '/01')) . ')', 0, 0, 'L');
        break;
}
$pdf->Ln(8);
//* ---------- Table ----------
$pdf->SetFont('helvetica', '', 9);
$header = array('Date Received', 'Filename', 'Filesize', 'Deleted By', 'Witnessed By', 'Certified By', 'Deletion Date'); //* column titles
$pdf->OutputTable($header, $data_result); //* table
$pdf->Ln(10);
//* ---------- Respondents ----------
$pdf->SetFont('helvetica', '', 12);
$pdf->Cell(102, 0, 'Prepared by:', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
$pdf->Cell(102, 0, 'Checked by:', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
$pdf->Cell(102, 0, 'Noted by:', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
$pdf->Ln(8);
//* -------------------------------- Signature --------------------------------
$pdf->Image('@' . base64_decode($prepared_by_sign), 10, '', 30, 20, '', '', '', false, 300, '', false, false, 0, false, false, false);
$pdf->Image('@' . base64_decode($checked_by_sign), 110, '', 40, 20, '', '', '', false, 300, '', false, false, 0, false, false, false);
$pdf->Image('@' . base64_decode($noted_by_sign), 215, '', 40, 20, '', '', '', false, 300, '', false, false, 0, false, false, false);
$pdf->Ln(16);
//* -------------------------------- Responsible --------------------------------
$pdf->SetFont('helvetica', '', 10);
$pdf->Cell(102, 0, $prepared_by, 0, 0, 'L', 0, '', 0, false, 'T', 'M');
$pdf->Cell(102, 0, $checked_by, 0, 0, 'L', 0, '', 0, false, 'T', 'M');
$pdf->Cell(102, 0, $noted_by, 0, 0, 'L', 0, '', 0, false, 'T', 'M');
$pdf->Ln();
//* -------------------------------- Job Position --------------------------------
$pdf->Cell(102, 0, $prepared_by_position, 0, 0, 'L', 0, '', 0, false, 'T', 'M');
$pdf->Cell(102, 0, $checked_by_position, 0, 0, 'L', 0, '', 0, false, 'T', 'M');
$pdf->Cell(102, 0, $noted_by_position, 0, 0, 'L', 0, '', 0, false, 'T', 'M');


$pdf->Output('Certificate of File Deletion Summary', 'I'); //* Close and output PDF document
$emailLogs = null; //* ======== Close Connection ========}
