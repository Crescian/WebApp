<?php
include '../../configuration/connection.php';
include '../../vendor/tcpdf/tcpdf.php';

date_default_timezone_set('Asia/Manila');
$BannerWebLive = $conn->db_conn_bannerweb(); //* Banner Web Database connection
$ITR = $conn->db_conn_it_repair_request(); //* IT Repair Request Database connection

$currentDate = date('Y-m-d');
class Repair
{
    private $db;
    function __construct($db)
    {
        $this->db = $db;
    }

    function details()
    {
        $query = "SELECT * FROM tblit_repair";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $rowData = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($rowData as $row) {
            $data[] = array(
                "date_requested" => date('m/d/Y h:i:s A', strtotime($row['date_requested'])),
                "requested_by" => $row['requested_by'],
                "item" => $row['item'],
                "remarks" => $row['remarks'],
                "location" => $row['location'],
                "datetime_accomplish" =>  date('m/d/Y h:i:s A', strtotime($row['datetime_accomplish'])),
                "duration" => self::getDuration($row['datetime_repair'], $row['datetime_accomplish']),
                "priority" => "Medium",
                "repaired_by" => $row['repaired_by']
            );
        }

        return $data;
    }

    function getDuration($dateStart, $dateEnd)
    {
        $start = new DateTime($dateStart);
        $end = new DateTime($dateEnd);
        $diff = $start->diff($end);
        $minutes = ($diff->days * 24 * 60) + ($diff->h * 60) + $diff->i;
        return $minutes . " Min.";
    }
}
$repair = new Repair($ITR);

class MYPDF extends TCPDF
{ //* extend TCPF with custom functions
    //* Page header

    public function Header()
    {
        if ($this->PageNo() == 1) {
            //* Image($file, $x='', $y='', $w=0, $h=0, $type='', $link='', $align='', $resize=false, $dpi=300, $palign='', $ismask=false, $imgmask=false, $border=0, $fitbox=false, $hidden=false, $fitonpage=false)
            $this->Image('../../vendor/images/Banner Logo.jpg', 144, 11, 43, 11, 'JPG', '', 'T', true, 300, '', false, false, 0, false, false, false);
            $this->Image('../../vendor/images/ISO Logo.jpg', 276, 11, 42, 12, 'JPG', '', 'T', true, 300, '', false, false, 0, false, false, false);
            $this->Ln(14.5);
            //* Cell(w, h = 0, txt = '', border = 0, ln = 0, align = '', fill = 0, link = nil, stretch = 0, ignore_min_height = false, calign = 'T', valign = 'M')
            $this->SetFont('helvetica', '', 12);
            $this->Cell(0, 0, 'Information Technology Department', 0, 1, 'C');
            $this->SetFont('helvetica', 'B', 14);
            $this->Cell(180, 0, 'I.T. REPAIR REQUEST', 0, 0, 'R');
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
        // $this->Cell(305, '', 'ISD/XX-036-00', 0, 0, 'R', 0, '', 0, false, '', '');
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
}

$pdf = new MYPDF("L", PDF_UNIT, "LONG", true, 'UTF-8', false); //* create new PDF document
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED); //* set default monospaced font
$pdf->SetAutoPageBreak(TRUE, 25); //* set page break
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
// $pdf->Cell(153, 0, 'Name of Issuer: ' . '$company', 0, 0, 'L');
$pdf->Cell(0, 0, 'Date: ' . date_format(date_create($currentDate), 'F d, Y'), 0, 0, 'R');
$pdf->Ln(8);
//* ---------- Table ----------
$pdf->SetFont('helvetica', '', 9);
// $header = array('Date and Time', 'Requested By', 'Defective Item', 'Remarks', 'Location', 'Finish', 'Duration', 'Priority', 'Technician'); //* column titles
// $pdf->OutputTable($header, $repair); //* table
$tblHeader = '<table cellspacing="0" cellpadding="3" border="1" style="font-size: medium;">
                <thead>
                    <tr>
                        <th align="center" width="70"><b>Date and Time</b></th>
                        <th align="center"><b>Requested By</b></th>
                        <th align="center"><b>Defective Item</b></th>
                        <th align="center" width="200"><b>Remarks</b></th>
                        <th align="center" width="140"><b>Location</b></th>
                        <th align="center" width="70"><b>Finish</b></th>
                        <th align="center" width="45"><b>Duration</b></th>
                        <th align="center" width="50"><b>Priority</b></th>
                        <th align="center"><b>Technician</b></th>
                    </tr>
                </thead>
                <tbody>';
$tblBody = "";
foreach ($repair->details() as $row) {
    $tblBody .=
        '<tr>
            <td align="center" width="70">' . $row['date_requested'] . '</td>
            <td align="center"><div style="font-size:5px">&nbsp;</div>' . $row['requested_by'] . '</td>
            <td align="center">' . $row['item'] . '</td>
            <td align="center" width="200">' . $row['remarks'] . '</td>
            <td align="center" width="140">' . $row['location'] . '</td>
            <td align="center" width="70">' . $row['datetime_accomplish'] . '</td>
            <td align="center" width="45"><div style="font-size:5px">&nbsp;</div>' . $row['duration'] . '</td>
            <td align="center" width="50"><div style="font-size:5px">&nbsp;</div>' . $row['priority'] . '</td>
            <td align="center"><div style="font-size:5px">&nbsp;</div>' . $row['repaired_by'] . '</td>
        </tr>';
}
$tblFooter = '</tbody></table>';

$tbl = $tblHeader . $tblBody . $tblFooter;

$pdf->writeHTML($tbl, true, false, false, false, '');
$pdf->Ln(15);
//* ---------- Respondents ----------
$pdf->SetFont('helvetica', '', 12);
// $pdf->Cell(102, 9, 'Prepared by:', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
// $pdf->Cell(102, 9, 'Checked by:', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
// $pdf->Cell(102, 9, 'Noted by:', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
$pdf->Ln(13);
//* -------------------------------- Signature --------------------------------
// $pdf->Image('@' . base64_decode($prepared_by_sign), 10, '', 30, 20, '', '', '', false, 300, '', false, false, 0, false, false, false);
// $pdf->Image('@' . base64_decode($checked_by_sign), 110, '', 40, 20, '', '', '', false, 300, '', false, false, 0, false, false, false);
// $pdf->Image('@' . base64_decode($noted_by_sign), 215, '', 40, 20, '', '', '', false, 300, '', false, false, 0, false, false, false);
// $pdf->Ln(10);
//* -------------------------------- Responsible --------------------------------
$pdf->SetFont('helvetica', '', 10);
// $pdf->Cell(102, 5, '$prepared_by', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
// $pdf->Cell(102, 5, '$checked_by', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
// $pdf->Cell(102, 5, '$noted_by', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
$pdf->Ln();
//* -------------------------------- Job Position --------------------------------
// $pdf->Cell(102, 5, '$prepared_by_position', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
// $pdf->Cell(102, 5, '$checked_by_position', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
// $pdf->Cell(102, 5, '$noted_by_position', 0, 0, 'L', 0, '', 0, false, 'T', 'M');


$pdf->Output('Certificate of File Deletion Summary', 'I'); //* Close and output PDF document
