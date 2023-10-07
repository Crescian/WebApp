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

$dateFilter = $_GET['df'];

$sqlstring = "SELECT * FROM info_sec_sftp_file_retention WHERE TO_CHAR(sftp_file_received_date_time, 'YYYY-MM-DD') = '{$dateFilter}' AND sftp_deleted = false;";
$data_result = sqlQuery($sqlstring, $php_fetch_info_sec_api);

//* extend TCPF with custom functions
class MYPDF extends TCPDF
{
    //* Page footer
    public function Footer()
    {
        //* Position at 26 mm from bottom
        $this->SetY(-22);
        $this->SetFont('helvetica', '', 10);
        //* Page Number
        //* Cell(w, h = 0, txt = '', border = 0, ln = 0, align = '', fill = 0, link = nil, stretch = 0, ignore_min_height = false, calign = 'T', valign = 'M')
        $this->Cell(191, '', '', 0, false, 'R', 0, '', 0, false, 'T', 'M');
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
        $w = array(32, 37, 95, 28); //* set column width
        $this->TableHeader($header, $w);
        $this->SetFont('helvetica', '', 10);
        foreach ($data_result['data'] as $row) { //* Data
            $num_pages = $this->getNumPages(); //* Get current number of pages.
            $this->startTransaction();
            $this->MultiCell($w[0], 10, date_format(date_create($row['sftp_file_received_date_time']), 'Y-m-d H:i:s A'), 1, 'C', 0, 0, '', '', true, 0, false, true, 10, 'T', false);
            $this->MultiCell($w[1], 10, $row['sftp_company_name'], 1, 'L', 0, 0, '', '', true, 0, false, true, 10, 'M', false);
            $this->MultiCell($w[2], 10, $row['sftp_filename'], 1, 'L', 0, 0, '', '', true, 0, false, true, 10, 'M', false);
            $this->Cell($w[3], 10, $row['sftp_filesize'], 1, 0, 'R', 0);
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
                $this->MultiCell($w[0], 10, date_format(date_create($row['sftp_file_received_date_time']), 'Y-m-d H:i:s A'), 1, 'C', 0, 0, '', '', true, 0, false, true, 10, 'T', false);
                $this->MultiCell($w[1], 10, $row['sftp_company_name'], 1, 'L', 0, 0, '', '', true, 0, false, true, 10, 'M', false);
                $this->MultiCell($w[2], 10, $row['sftp_filename'], 1, 'L', 0, 0, '', '', true, 0, false, true, 10, 'M', false);
                $this->Cell($w[3], 10, $row['sftp_filesize'], 1, 0, 'R', 0);
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
$pdf->SetAutoPageBreak(TRUE, 20); //* set page break
$pdf->setPrintHeader(false); //* include header
$pdf->setPrintFooter(true); //* include footer
$pdf->AddPage(); //* Add page
$pdf->Ln(5); //* Next Line
$header = array('Date Received', 'Company',  'File Name', 'File Size'); //* column titles
$pdf->OutputTable($header, $data_result); //* table

$filename = "File Received Checklist.pdf";
$pdf->Output($filename, 'I'); //* Close and output PDF document