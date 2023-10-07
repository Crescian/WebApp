<?php
include '../vendor/tcpdf/tcpdf.php';
include_once '../configuration/connection.php';
$PHD = $conn->db_conn_physical_security(); //* Physical Security Database connection

$qvsid = $_GET['d'];

$sqlstringheader = "SELECT qvs_checked,qvs_perform,qvs_noted,qvs_date,
encode(qvs_perform_sign, 'escape') 
AS perform_sign,
encode(qvs_checked_sign, 'escape') 
AS checked_sign,
CASE WHEN qvs_noted_assign = true THEN encode(qvs_noted_sign, 'escape') ELSE '' END AS qvs_noted_sign 
FROM phd_quarterly_vs_header WHERE qvsid = :qvsid";
$result_stmt_header = $PHD->prepare($sqlstringheader);
$result_stmt_header->bindParam(':qvsid', $qvsid);
$result_stmt_header->execute();
$result_res_header = $result_stmt_header->fetchAll();
foreach ($result_res_header as $row) {
    $vsQuarterDate = date_format(date_create($row['qvs_date']), "F d, Y");

    $fullname = $row['qvs_checked'];
    $performedBy = $row['qvs_perform'];
    $NotedBy = $row['qvs_noted'];

    $perform_sign = $row['perform_sign'];
    $checked_sign = $row['checked_sign'];
    $qvs_noted_sign = $row['qvs_noted_sign'];
}

$sqlChklist = "SELECT * 
FROM phd_quarterly_vs_details 
WHERE qvs_location_name IN ('HSA MAN Vault A', 'HSA MAN Vault B') AND qvs_id = :qvs_id ORDER BY qvsdetailid ASC";
$result_chkList_stmt = $PHD->prepare($sqlChklist);
$result_chkList_stmt->bindParam(':qvs_id', $qvsid);
$result_chkList_stmt->execute();
$result_chkList_res = $result_chkList_stmt->fetchAll();

$sqlChklistPer = "SELECT * FROM phd_quarterly_vs_details WHERE qvs_id = :qvs_id
AND qvs_location_name = 'HSA PER Vault' ORDER BY qvsdetailid ASC";
$result_chkList_per_stmt = $PHD->prepare($sqlChklistPer);
$result_chkList_per_stmt->bindParam(':qvs_id', $qvsid);
$result_chkList_per_stmt->execute();
$result_chkList_per_res = $result_chkList_per_stmt->fetchAll();

function getActionCode($category)
{
    switch ($category) {
        case '':
            $actioncode = '';
            break;
        case 'Check':
            $actioncode = '/';
            break;
        case 'Clean':
            $actioncode = 'C';
            break;
        case 'Adjust':
            $actioncode = 'A';
            break;
        case 'Repair/Replacement':
            $actioncode = 'R';
            break;
        case 'lubricate':
            $actioncode = 'L';
            break;
        case 'Polish':
            $actioncode = 'P';
            break;
    }
    return $actioncode;
}

//* extend TCPF with custom functions
class MYPDF extends TCPDF
{
    //* Page header
    public function Header()
    {
        if ($this->PageNo() == 1) {
            //* Image($file, $x='', $y='', $w=0, $h=0, $type='', $link='', $align='', $resize=false, $dpi=300, $palign='', $ismask=false, $imgmask=false, $border=0, $fitbox=false, $hidden=false, $fitonpage=false)
            $this->Image('../vendor/images/Banner Logo-colored.jpg', 86, 12, 42.5, 11, 'JPG', '', 'T', true, 300, '', false, false, 0, false, false, false);
            $this->Image('../vendor/images/ISO Logo-colored.jpg', 159, 12, 43, 12.7, 'JPG', '', 'T', true, 300, '', false, false, 0, false, false, false);
            $this->Ln(15);
            //* Cell(w, h = 0, txt = '', border = 0, ln = 0, align = '', fill = 0, link = nil, stretch = 0, ignore_min_height = false, calign = 'T', valign = 'M')
            $this->SetFont('helvetica', '', 12);
            $this->Cell(0, 0, 'Physical Security Department', 0, 1, 'C');
            $this->SetFont('helvetica', 'B', 14);
            $this->Cell(0, 0, 'QUARTERLY VIBRATION CHECKLIST', 0, 0, 'C');
        }
        if ($this->PageNo() >= 2) {
            $this->SetFont('helvetica', '', 10);
            $this->Cell(0, 7, '', 0, 1, 'L');
            $this->Cell(0, 5, 'Quarterly Vibration Checklist', 0, 0, 'L');
        }
    }

    //* Page footer
    public function Footer()
    {
        //* Position at 26 mm from bottom
        $this->SetY(-17.7);
        $this->SetFont('helvetica', '', 10);
        //* Page Number
        //* Cell(w, h = 0, txt = '', border = 0, ln = 0, align = '', fill = 0, link = nil, stretch = 0, ignore_min_height = false, calign = 'T', valign = 'M')
        $this->Cell(191, '', 'PHD/IS-022-02', 0, false, 'R', 0, '', 0, false, 'T', 'M');
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
$pdf->SetAutoPageBreak(TRUE, 17); //* set page break
$pdf->AddPage();
$pdf->Ln(33);
//* -------------------------------- Info --------------------------------
$pdf->SetFont('helvetica', '', 12);
$pdf->Cell(159, 0, 'Date: ', 0, 0, 'R');
$pdf->Cell(35, 0, $vsQuarterDate, 0, 0, 'C');
$pdf->Ln(7);
// ! TABLE OUTPUT
$pdf->SetFont('helvetica', '', 12);
$html_table =
    '<table>
                <tr>
                    <td>
                        <table style="width:100%;" border="1" cellspacing="0" cellpadding="1.5">
                            <tr>
                                <td style="text-align:center; width:48%;"><div style="font-size:5pt">&nbsp;</div>Particular</td>
                                <td style="text-align:center; width:16%;">Action Taken</td>
                                <td style="text-align:center; width:19%;">Time Activated</td>
                                <td style="text-align:center; width:16.5%;">Time Verified</td>
                            </tr>';
$pdf->SetFont('helvetica', '', 11);
foreach ($result_chkList_res as $row) {
    $actioncode = $row['qvs_action_code'] == '' ? '-' : getActionCode($row['qvs_action_code']);
    $time = $row['qvs_action_time'] == NULL ? '-' : date_format(date_create($row['qvs_action_time']), 'Hm') . 'H';
    $activated = $row['qvs_activated_time'] == NULL ? '-' : date_format(date_create($row['qvs_activated_time']), 'Hm') . 'H';
    $html_table .=  '       <tr>
                                <td style="width:48%;">' . $row['qvs_particular'] . '</td>
                                <td style="text-align:center; width:16%; height:5px;">' . $actioncode . '</td>
                                <td style="text-align:center; width:19%;">' . $time . '</td>
                                <td style="text-align:center; width:16.5%;">' . $activated . '</td>
                            </tr>';
}

$pdf->SetFont('helvetica', '', 12);
$html_table .= '</table>
                    </td>
                    <td>
                        <table style="width:100%;" border="1" cellspacing="0" cellpadding="1.5">
                            <tr>
                                <td style="text-align:center; width:48%;"><div style="font-size:5pt">&nbsp;</div>Particular</td>
                                <td style="text-align:center; width:16%;">Action Taken</td>
                                <td style="text-align:center; width:19%;">Time Activated</td>
                                <td style="text-align:center; width:16.5%;">Time Verified</td>
                            </tr>';

$pdf->SetFont('helvetica', '', 11);
foreach ($result_chkList_per_res as $rows) {
    $actioncode = $rows['qvs_action_code'] == '' ? '-' : getActionCode($rows['qvs_action_code']);
    $timePerso = $rows['qvs_action_time'] == NULL ? '-' : date_format(date_create($rows['qvs_action_time']), 'Hm') . 'H';
    $activatedPerso = $rows['qvs_activated_time'] == NULL ? '-' : date_format(date_create($rows['qvs_activated_time']), 'Hm') . 'H';
    $html_table .=  '       <tr>
                                <td style="width:48%;">' . $rows['qvs_particular'] . '</td>
                                <td style="text-align:center; width:16%;">' . $actioncode . '</td>
                                <td style="text-align:center; width:19%;">' . $timePerso . '</td>
                                <td style="text-align:center; width:16.5%;">' . $activatedPerso . '</td>
                            </tr>';
}
$html_table .= '        </table>
                    </td>
                </tr>
            </table>';
$pdf->writeHTML($html_table, false, false, true, false, '');
$pdf->Ln(2);
// ! END
//* -------------------------------- Legend --------------------------------
$pdf->SetFont('helvetica', '', 10);
$pdf->Cell(2, 5, '', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
$pdf->Cell(25, 7, 'Action Code', 0, 1, 'L', 0, '', 0, false, 'T', 'M');
$pdf->Cell(2, 5, '', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
$pdf->Cell(20, 5, '/ = Check', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
$pdf->Cell(20, 5, 'A = Adjust', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
$pdf->Cell(25, 5, 'L = Lubricate', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
$pdf->Ln();
$pdf->Cell(2, 5, '', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
$pdf->Cell(20, 5, 'C = Clean', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
$pdf->Cell(45, 5, 'R = Repair/Replacement', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
$pdf->Cell(20, 5, 'P = Polish', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
$pdf->Ln(46);
$pdf->SetFont('helvetica', '', 12);
$pdf->Cell(63.6, 9, 'Prepared by:', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
$pdf->Cell(63.6, 9, 'Checked by:', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
$pdf->Cell(63.6, 9, 'Noted by:', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
$pdf->Ln(16);
//* -------------------------------- Responsible --------------------------------
$pdf->SetFont('helvetica', '', 10);
// $pdf->Image('@' . base64_decode($perform_sign), 15, 220, 25, 10, '', '', '', false, 300, '', false, false, 0, false, false, false);
$pdf->Ln(1);
// $pdf->Image('@' . base64_decode($checked_sign), 80, 220, 25, 10, '', '', '', false, 300, '', false, false, 0, false, false, false);
$pdf->Ln(1);
// $pdf->Image('@' . base64_decode($qvs_noted_sign), 140, 220, 25, 10, '', '', '', false, 300, '', false, false, 0, false, false, false);
$pdf->Ln(1);
$pdf->Cell(46.8, 7, $performedBy, 0, 0, 'L', 0, '', 0, false, 'T', 'M');
$pdf->Cell(16.8, 7, '', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
$pdf->Cell(46.8, 7, $fullname, 0, 0, 'L', 0, '', 0, false, 'T', 'M');
$pdf->Cell(16.8, 7, '', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
$pdf->Cell(46.8, 7, $NotedBy, 0, 0, 'L', 0, '', 0, false, 'T', 'M');
$pdf->Ln(6);
//* -------------------------------- Designation --------------------------------
$pdf->SetFont('helvetica', '', 10);
$pdf->Cell(46.8, 5, 'Internal Security Staff', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
$pdf->Cell(16.8, 5, '', 0, 0, 'C', 0, '', 0, false, 'T', 'M');
$pdf->Cell(46.8, 5, 'Internal Security Staff', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
$pdf->Cell(16.8, 5, '', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
$pdf->Cell(46.8, 5, 'VP for Physical Security', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
$filename = "Quarterly Vibration Checklist.pdf";
$pdf->Output($filename, 'I'); //* Close and output PDF document