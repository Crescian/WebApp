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

$date_from = $_GET['dfrom'];
$date_to = $_GET['dto'];
$dateFilter = $_GET['dcat'];
$file_reference_no = $_GET['ref'];
$file_company = $_GET['comp'];

$inField = ($dateFilter == 'tf1') ? 'file_received_date' : (($dateFilter == 'tf2') ? 'file_deleted_date' : 'file_delivery_date');
$sqlstring = "SELECT file_received_date,file_filename,file_filesize,file_deleted_date, file_reference_no,file_deleted_date,prepared_by, encode(prepared_by_sign, 'escape') as prepared_by_sign, checked_by,checked_by_acknowledge, encode(checked_by_sign, 'escape') as checked_by_sign, 
noted_by,noted_by_acknowledge, encode(noted_by_sign, 'escape') as noted_by_sign FROM bpi_perso_file_deletion 
WHERE file_company ILIKE '%{$file_company}%' AND {$inField} BETWEEN '{$date_from}' AND '{$date_to}' AND file_reference_no = '{$file_reference_no}' AND file_certified = true ORDER BY {$inField} DESC;";
$data_result = sqlQuery($sqlstring, $php_fetch_perso_api);
foreach ($data_result['data'] as $row) {
    $refno = $row['file_reference_no'];
    $deletion_date = $row['file_deleted_date'];
    //* ======== Performed By ========
    $prepared_by = $row['prepared_by'];
    $prepared_by_position = fetchJobTitle($php_fetch_bannerweb_api, $prepared_by);
    $prepared_by_sign = $row['prepared_by_sign'];
    //* ======== Checked By ========
    $checked_by = $row['checked_by'];
    $checked_by_position = fetchJobTitle($php_fetch_bannerweb_api, $checked_by);
    $checked_by_sign = $row['checked_by_acknowledge'] == 'true' ? $row['checked_by_sign'] : '';
    //* ======== Noted By ========
    $noted_by = $row['noted_by'];
    $noted_by_position = fetchJobTitle($php_fetch_bannerweb_api, $noted_by);
    $noted_by_sign = $row['noted_by_acknowledge'] == 'true' ? $row['noted_by_sign'] : '';
}

//* extend TCPF with custom functions
class MYPDF extends TCPDF
{
    //* Page header
    public function Header()
    {
        if ($this->PageNo() == 1) {
            //* Image($file, $x='', $y='', $w=0, $h=0, $type='', $link='', $align='', $resize=false, $dpi=300, $palign='', $ismask=false, $imgmask=false, $border=0, $fitbox=false, $hidden=false, $fitonpage=false)
            $this->Image('../vendor/images/Banner Logo-colored.jpg', 86, 12, 43, 11, 'JPG', '', 'T', true, 300, '', false, false, 0, false, false, false);
            $this->Image('../vendor/images/ISO Logo-colored.jpg', 159, 12, 44, 12, 'JPG', '', 'T', true, 300, '', false, false, 0, false, false, false);
            $this->Ln(14.5);
            //* Cell(w, h = 0, txt = '', border = 0, ln = 0, align = '', fill = 0, link = nil, stretch = 0, ignore_min_height = false, calign = 'T', valign = 'M')
            $this->SetFont('helvetica', '', 12);
            $this->Cell(0, 0, 'Information Security/Data Protection Department', 0, 1, 'C');
            $this->SetFont('helvetica', 'B', 14);
            $this->Cell(136, 0, 'CERTIFICATE OF FILE DELETION', 0, 0, 'R');
        }
        if ($this->PageNo() >= 2) {
            $this->SetFont('helvetica', '', 10);
            $this->Cell(0, 7, '', 0, 1, 'L');
            $this->Cell(0, 5, 'Certificate of File Deletion', 0, 0, 'L');
        }
    }

    //* Page footer
    public function Footer()
    {
        //* Position at 26 mm from bottom
        $this->SetY(-22);
        $this->SetFont('helvetica', '', 10);
        //* Page Number
        //* Cell(w, h = 0, txt = '', border = 0, ln = 0, align = '', fill = 0, link = nil, stretch = 0, ignore_min_height = false, calign = 'T', valign = 'M')
        $this->Cell(191, '', 'ISD/XX-025-00', 0, false, 'R', 0, '', 0, false, 'T', 'M');
        $this->Ln(4);
        //* Page Number
        $numPages = $this->getNumPages();
        if ($numPages < $this->getNumPages()) {
            $this->Cell(207, '', '', 0, 0, 'R', 0, '', 0, false, 'T', 'M');
        } else {
            if ($this->PageNo() >= 1 && $this->PageNo() <= 9) {
                $this->Cell(207, '', 'Page ' . $this->getAliasNumPage() . ' of ' . $this->getAliasNbPages(), 0, 0, 'R', 0, '', 0, false, '', ''); //* Last number is Double digit
            }
            if ($this->PageNo() >= 10) {
                $this->Cell(202, '', 'Page ' . $this->getAliasNumPage() . ' of ' . $this->getAliasNbPages(), 0, 0, 'R', 0, '', 0, false, '', ''); //* Both number is Double digit
            }
        }
    }

    public function TableHeader($header, $w)
    {
        $num_headers = count($header);
        $this->SetFont('helvetica', '', 12);
        for ($i = 0; $i < $num_headers; ++$i) {
            $this->Cell($w[$i], 8, $header[$i], 1, 0, 'C', 0);
        }
        $this->Ln();
    }

    public function OutputTable($header, $data_result)
    {
        $w = array(29, 104, 30, 28); //* set column width
        $this->TableHeader($header, $w);
        $this->SetFont('helvetica', '', 10);
        foreach ($data_result['data'] as $row) { //* Data
            $num_pages = $this->getNumPages(); //* Get current number of pages.
            $this->startTransaction();
            $this->Cell($w[0], 10, $row['file_received_date'], 1, 0, 'C', 0);
            $this->MultiCell($w[1], 10, $row['file_filename'], 1, 'L', 0, 0, '', '', true, 0, false, true, 10, 'T', false);
            $this->Cell($w[2], 10, $row['file_filesize'], 1, 0, 'R', 0);
            $this->Cell($w[3], 10, $row['file_deleted_date'], 1, 0, 'C', 0);
            $this->Ln();
            //* If old number of pages is less than the new number of pages,we hit an automatic page break, and need to rollback.
            if ($num_pages < $this->getNumPages()) {
                $this->rollbackTransaction(true); //* Undo adding the row.
                //* Add page
                $this->SetMargins(12.5, 13, 12.5);
                $this->AddPage();
                $this->TableHeader($header, $w); //* Draw the header.
                $this->SetFont('helvetica', '', 10);
                //* Re-do the row.
                $this->Cell($w[0], 10, $row['file_received_date'], 1, 0, 'C', 0);
                $this->MultiCell($w[1], 10, $row['file_filename'], 1, 'L', 0, 0, '', '', true, 0, false, true, 10, 'T', false);
                $this->Cell($w[2], 10, $row['file_filesize'], 1, 0, 'R', 0);
                $this->Cell($w[3], 10, $row['file_deleted_date'], 1, 0, 'C', 0);
                $this->Ln();
            } else {
                $this->commitTransaction(); //* Otherwise we are fine with this row, discard undo history.
            }
        }
    }
}

//* create new PDF document
//* ====== 191 total width with margin 12.5
$pdf = new MYPDF("P", PDF_UNIT, "LETTER", true, 'UTF-8', false);
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED); //* set default monospaced font
$pdf->SetMargins(12.5, 12, 12.5); //* set margins
$pdf->SetAutoPageBreak(TRUE, 35); //* set page break
$pdf->setPrintHeader(true); //* include header
$pdf->setPrintFooter(true); //* include footer
$pdf->AddPage(); //* Add page
$pdf->Ln(20.5); //* Next Line
//* -------------------------------- Header --------------------------------
//* Image($file, $x='', $y='', $w=0, $h=0, $type='', $link='', $align='', $resize=false, $dpi=300, $palign='', $ismask=false, $imgmask=false, $border=0, $fitbox=false, $hidden=false, $fitonpage=false)
//* Cell(w, h = 0, txt = '', border = 0, ln = 0, align = '', fill = 0, link = nil, stretch = 0, ignore_min_height = false, calign = 'T', valign = 'M')
$pdf->SetFont('helvetica', 'B', 12);
$pdf->Cell(191, 0, 'No. CFD-' . $refno, 0, 1, 'R');
$pdf->Ln(10);
//* -------------------------------- Info --------------------------------
$pdf->SetFont('helvetica', '', 12);
$pdf->Cell(32, 0, 'Name of Issuer: ', 0, 0, 'L');
$pdf->Cell(40, 0, $file_company, 0, 0, 'C');
$pdf->Cell(82, 0, 'Date: ', 0, 0, 'R');
$pdf->Cell(37, 0, date("F d, Y"), 0, 0, 'C');
$pdf->Ln(7);
//* -------------------------------- Table --------------------------------
$pdf->SetFont('helvetica', 'B', 12);
$pdf->Cell(0, 9, 'Deletion Details', 'LTR', 0, 'C', 0, '', 0, false, 'T', 'M');
$pdf->Ln();
$pdf->SetFont('helvetica', '', 12);
$pdf->Cell(39, 9, ' Deletion Program', 'LTR', 0, 'L', 0, '', 0, false, 'T', 'M');
$pdf->Cell(152, 9, '  Eraser', 'TR', 0, 'L', 0, '', 0, false, '', 'M');
$pdf->Ln();
$pdf->Cell(39, 9, ' Deleted Items', 'LTR', 0, 'L', 0, '', 0, false, 'T', 'M');
$pdf->SetFont('zapfdingbats', '', 17);
$pdf->Cell(1, 9, '   3', 'T', 0, 'L', 0, '', 0, false, 'T', 'T');
$pdf->SetFont('zapfdingbats', '', 15);
$pdf->Cell(11, 9, '  o', 'T', 0, 'L', 0, '', 0, false, 'T', 'M');
$pdf->SetFont('helvetica', '', 12);
$pdf->Cell(64, 9, 'Embossing File', 'T', 0, 'L', 0, '', 0, false, 'T', 'M');
$pdf->SetFont('zapfdingbats', '', 17);
$pdf->Cell(1, 9, '   3', 'T', 0, 'L', 0, '', 0, false, 'T', 'T');
$pdf->SetFont('zapfdingbats', '', 15);
$pdf->Cell(11, 9, '  o', 'T', 0, 'L', 0, '', 0, false, 'T', 'M');
$pdf->SetFont('helvetica', '', 12);
$pdf->Cell(64, 9, 'Reports', 'TR', 0, 'L', 0, '', 0, false, 'T', 'M');
$pdf->Ln();
$pdf->Cell(39, 9, '', 'LR', 0, 'L', 0, '', 0, false, '', 'M');
$pdf->SetFont('zapfdingbats', '', 17);
$pdf->Cell(1, 9, '   3', 0, 0, 'L', 0, '', 0, false, 'T', 'T');
$pdf->SetFont('zapfdingbats', '', 15);
$pdf->Cell(11, 9, '  o', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
$pdf->SetFont('helvetica', '', 12);
$pdf->Cell(65, 9, 'Logs', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
$pdf->SetFont('zapfdingbats', '', 15);
$pdf->Cell(11, 9, '  o', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
$pdf->SetFont('helvetica', '', 12);
$pdf->Cell(64, 9, 'OTHERS _________________', 'R', 0, 'L', 0, '', 0, false, 'T', 'M');
$pdf->Ln();
$pdf->SetFont('helvetica', '', 10);
$header = array('Date Received', 'File Name', 'File Size', 'Date Deleted'); //* column titles
$pdf->OutputTable($header, $data_result); //* table
//* -------------------------------- Designation --------------------------------
$pdf->Ln(10);
$pdf->SetFont('helvetica', '', 12);
$pdf->Cell(63.6, 0, 'Prepared by:', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
$pdf->Cell(63.6, 0, 'Checked by:', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
$pdf->Cell(63.6, 0, 'Noted by:', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
$pdf->Ln(8);
//* -------------------------------- Signature --------------------------------
$pdf->Image('@' . base64_decode($prepared_by_sign), 10, '', 40, 20, '', '', '', false, 300, '', false, false, 0, false, false, false);
$pdf->Image('@' . base64_decode($checked_by_sign), 66, '', 40, 20, '', '', '', false, 300, '', false, false, 0, false, false, false);
$pdf->Image('@' . base64_decode($noted_by_sign), 140, '', 40, 20, '', '', '', false, 300, '', false, false, 0, false, false, false);
$pdf->Ln(16);
//* -------------------------------- Responsible --------------------------------
$pdf->SetFont('helvetica', '', 10);
$pdf->Cell(63.6, 0, $prepared_by, 0, 0, 'L', 0, '', 0, false, 'T', 'M');
$pdf->Cell(63.6, 0, $checked_by, 0, 0, 'L', 0, '', 0, false, 'T', 'M');
$pdf->Cell(63.6, 0, $noted_by, 0, 0, 'L', 0, '', 0, false, 'T', 'M');
$pdf->Ln();
//* -------------------------------- Job Position --------------------------------
$pdf->MultiCell(63.66, 10, $prepared_by_position, 0, 'L', 0, 0, '', '', true, 0, false, true, 10, 'T', false);
$pdf->MultiCell(63.66, 10, $checked_by_position, 0, 'L', 0, 0, '', '', true, 0, false, true, 10, 'T', false);

if ($noted_by == 'Esperidion Castro') {
    $pdf->MultiCell(63.66, 10, 'Vice President for Information Security/Data Protection', 0, 'L', 0, 0, '', '', true, 0, false, true, 10, 'T', false);
} else {
    $pdf->MultiCell(63.66, 10, $noted_by_position, 0, 'L', 0, 0, '', '', true, 0, false, true, 10, 'T', false);
}

$filename = $file_company . "-" . $deletion_date . "-CFD-" . $refno . ".pdf";
$pdf->Output($filename, 'I'); //* Close and output PDF document
