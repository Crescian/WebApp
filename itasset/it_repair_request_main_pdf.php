<?php
include_once '../configuration/connection.php';
include '../vendor/tcpdf/tcpdf.php';
$ITR = $conn->db_conn_it_repair_request(); //* IT REPAIR AND REQUEST Database connection
date_default_timezone_set('Asia/Manila');
$repairId = $_GET['id'];

$actionArray = array();
$sqlstringAction = "SELECT * FROM tblit_action_taken WHERE repair_id = '{$repairId}'";
$result_stmt_action = $ITR->prepare($sqlstringAction);
$result_stmt_action->execute();
while ($row = $result_stmt_action->fetch(PDO::FETCH_ASSOC)) {
    $actionArray[] = $row;
}
class Repair
{
    private $db1;
    private $db2;
    public $referenceNumber;
    public $item;
    public $location;
    public $dateRequested;
    public $dateAcknowledged;
    public $dateFinish;
    public $remarks;
    public $requestedBy;
    public $repairedBy;
    public $requestedByJob;
    public $approvedBy;
    public $approvedByJob;
    public $acknowledgedBy;
    public $acknowledgedByJob;
    public $preparedBySign;
    public $acknowledgeBySign;
    public $approvedBySign;
    public $action_taken;
    public $action_taken_date;

    public function __construct($db1, $db2)
    {
        $this->db1 = $db1;
        $this->db2 = $db2;
    }


    public function details($id, $ITR)
    {
        $sqlstring = "SELECT queue_number, item,  location,  date_requested::date, datetime_acknowledge::date, remarks, prepared_by, prepared_by, repaired_by, datetime_accomplish,
        encode(prepared_by_sign, 'escape') AS prepared_sign,
        encode(repaired_by_sign, 'escape') AS repaired_sign,
        encode(approved_by_sign, 'escape') AS approved_sign
        FROM tblit_repair WHERE repair_id = '{$id}'";
        $result_stmt = $ITR->prepare($sqlstring);
        $result_stmt->execute();
        $result_res = $result_stmt->fetchAll();
        foreach ($result_res as $row) {
            // $data_base64 = base64_encode($sqlstring);
            // $curl = curl_init();
            // curl_setopt($curl, CURLOPT_URL, $this->db1);
            // curl_setopt($curl, CURLOPT_HEADER, false);
            // curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            // curl_setopt($curl, CURLOPT_POST, true);
            // curl_setopt($curl, CURLOPT_POSTFIELDS, array("data" => $data_base64));
            // $json_response = curl_exec($curl);
            // //* ====== Close Connection ======
            // curl_close($curl);
            // //* ======== Prepare Array ========
            // $data_result =  json_decode($json_response, true);
            // foreach ($data_result['data'] as $row) {
            $this->referenceNumber = $row['queue_number'];
            $this->item = $row['item'];
            $this->location = $row['location'];
            $this->dateRequested = date("m/d/y", strtotime($row['date_requested']));
            $this->dateAcknowledged = date("m/d/y", strtotime($row['datetime_acknowledge']));
            $this->dateFinish = date("m/d/y", strtotime($row['datetime_accomplish']));
            $this->remarks = $row['remarks'];
            $this->requestedBy = $row['prepared_by'];
            $this->repairedBy = $row['repaired_by'];
            $this->requestedByJob = self::getJobPosition($row['prepared_by']);
            $this->acknowledgedBy = $row['prepared_by'];
            $this->acknowledgedByJob = self::getJobPosition($row['repaired_by']);
            $this->preparedBySign = $row['prepared_sign'];
            $this->acknowledgeBySign = $row['repaired_sign'];
            $this->approvedBySign = $row['approved_sign'];
            self::getApprovedBy();
        }
    }

    function getApprovedBy()
    {
        $sqlstring = "SELECT CONCAT(emp_fn, ' ', emp_sn) AS approved_by FROM prl_employee WHERE pos_code = 'VPI'";
        $data_base64 = base64_encode($sqlstring);
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $this->db2);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, array("data" => $data_base64));
        $json_response = curl_exec($curl);
        //* ====== Close Connection ======
        curl_close($curl);
        //* ======== Prepare Array ========
        $data_result = json_decode($json_response, true);
        foreach ($data_result['data'] as $row) {
            $this->approvedBy = $row['approved_by'];
            $this->approvedByJob = self::getJobPosition($row['approved_by']);
        }
    }

    function getJobPosition($name)
    {
        $sqlstring = "SELECT pos_name FROM prl_employee
            INNER JOIN prl_position ON prl_employee.pos_code = prl_position.pos_code
            WHERE CONCAT(emp_fn, ' ', emp_sn) = '{$name}'";
        $data_base64 = base64_encode($sqlstring);
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $this->db2);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, array("data" => $data_base64));
        $json_response = curl_exec($curl);
        //* ====== Close Connection ======
        curl_close($curl);
        //* ======== Prepare Array ========
        $data_result =  json_decode($json_response, true);
        foreach ($data_result['data'] as $row) {
            return $row['pos_name'];
        }
    }
}

$repair = new Repair($php_fetch_it_repair_api, $php_fetch_bannerweb_api);
$repair->details($repairId, $ITR);


//* extend TCPF with custom functions
class MYPDF extends TCPDF
{
    public function TableHeader()
    {
        $html_header = '
                <table cellspacing="0" cellpadding="0.2" border=".1" style="width:100%">
                    <tr>
                        <td style="text-align: center; width: 99.5%; font-size: 12px;">Action Taken</td>
                    </tr>
                </table>';
        $this->writeHTML($html_header, false, false, true, false, '');
    }
    public function OutputTable($actionArray)
    {
        $this->TableHeader();
        $w = array(95, 95);

        foreach ($actionArray as $details) {
            $num_pages = $this->getNumPages(); //* Get current number of pages.
            $this->startTransaction();

            $this->Cell($w[0], 5, $details['action_taken'], 1, 0, 'L', 0);
            $this->Cell($w[1], 5, $details['action_taken_date'], 1, 0, 'C', 0);
            $this->Ln();

            if ($num_pages < $this->getNumPages()) {
                $this->rollbackTransaction(true); //* Undo adding the row.
                //* Add page
                $this->SetMargins(12.5, 13, 12.5);
                $this->AddPage();
                //* Draw the header.
                $this->TableHeader();
            } else {
                $this->commitTransaction(); //* Otherwise we are fine with this row, discard undo history.
            }
        }
        $this->Cell(array_sum($w), 0, '', 'T');
        $this->Ln();
    }
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
            $this->Cell(0, 0, 'Information Technology Department', 0, 1, 'C');
            $this->SetFont('helvetica', 'B', 14);
            $this->Cell(0, 0, 'IT REPAIR REQUEST FORM', 0, 0, 'C');
            // $this->Rotate(20, 70, 110);
        }
    }

    //* Page footer
    public function Footer()
    {
        // Stop Transformation
        // $this->SetY(-160);
        // $this->Rotate(20, 70, 110);
        // $this->Image('../vendor/images/served.jpg', 50, 12, 38.8, 9.8, 'JPG', '', 'T', true, 300, '', false, false, 0, false, false, false);
        // $this->SetY(-150);
        // $this->SetFont('helvetica', '', 10);
        // $this->Cell(77, '', 'Crescian Lloyd Lanoy', 0, false, 'R', 0, '', 0, false, 'T', 'M');
        // $this->Cell(191, '', 'ITD/XX-012-00', 0, false, 'R', 0, '', 0, false, 'T', 'M');
    }
}

//* create new PDF document
$pdf = new MYPDF("L", PDF_UNIT, "JUNIORLONG", true, 'UTF-8', false);
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED); //* set default monospaced font
$pdf->SetMargins(12.5, 12, 12.5); //* set margins
$pdf->SetAutoPageBreak(TRUE, 5); //* set page break
$pdf->setPrintHeader(true); //* include header
$pdf->setPrintFooter(true); //* include footer
$pdf->AddPage(); //* Add page
$pdf->Ln(20); //* Next Line

$pdf->Image('../vendor/images/banner-serve.jpg', 15, 5, 55, 30, 'JPG', '', 'T', true, 300, '', false, false, 0, false, false, false);
$pdf->Image('@' . base64_decode($repair->preparedBySign), 27, 20.5, 20, 10, '', '', '', false, 300, '', false, false, 0, false, false, false);
$pdf->Image('@' . base64_decode($repair->acknowledgeBySign), 40, 25, 20, 10, '', '', '', false, 300, '', false, false, 0, false, false, false);

$pdf->SetY(-142);
$pdf->SetX(-193);
$pdf->SetFont('helvetica', 'B', 10);
// $pdf->Cell(0, 0, 'Crescian Lloyd Lanoy', 0, 0, 'L');
$pdf->SetX(-164);
$pdf->Cell(0, 0, $repair->dateFinish, 0, 0, 'L');
$pdf->SetY(-137);
$pdf->SetX(-177);
// $pdf->Cell(0, 0, 'Crescian Lloyd Lanoy', 0, 0, 'L');

//* -------------------------------- Header --------------------------------
//* Image($file, $x='', $y='', $w=0, $h=0, $type='', $link='', $align='', $resize=false, $dpi=300, $palign='', $ismask=false, $imgmask=false, $border=0, $fitbox=false, $hidden=false, $fitonpage=false)
//* Cell(w, h = 0, txt = '', border = 0, ln = 0, align = '', fill = 0, link = nil, stretch = 0, ignore_min_height = false, calign = 'T', valign = 'M')
$pdf->SetFont('freeserif', '', 14);
$pdf->Cell(138.5, 0, '№', 0, 0, 'R', 0, '', 0, FALSE, '', 'T');
$pdf->SetFont('helvetica', 'B', 12);
$pdf->Cell(27, 0, $repair->referenceNumber, 0, 0, 'R', 0, '', 0, FALSE, 'T', 'B');
$pdf->Ln(10.5);

//* -------------------------------- Info --------------------------------
$pdf->SetFont('helvetica', '', 12);
$pdf->Cell(23, 0, 'Item : ', 0, 0, 'L');
$pdf->Cell(92, 0, $repair->item, 'B', 0);

$pdf->Cell(48, 0, 'Date Requested : ', 0, 0, 'R');
$pdf->Cell(27, 0, $repair->dateRequested, 'B', 0, 'L');
$pdf->Ln();

$pdf->Cell(23, 0, 'Location : ', 0, 0, 'L');
$pdf->Cell(92, 0, $repair->location, 'B', 0);

$pdf->Cell(48, 0, 'Date Acknowledged : ', 0, 0, 'R');
$pdf->Cell(27, 0, $repair->dateAcknowledged, 'B', 0, 'L');
$pdf->Ln();
$repairedUpperCase = strtoupper($repair->repairedBy);
$pdf->Cell(163, 0, 'Date Finished : ', 0, 0, 'R');
$pdf->Cell(27, 0, $repair->dateFinish, 'B', 0, 'L');
$pdf->Ln(8);
$pdf->Image('@' . base64_decode($repair->acknowledgeBySign), 61, 75, 40, 20, '', '', '', false, 300, '', false, false, 0, false, false, false);
//* -------------------------------- Request Item and Description --------------------------------
// $pdf->MultiCell(190, 0, "Action Taken : &nbsp;", 'TB', 'L', 0, 1, '', '', true, 0, true); //Outer Cell
$pdf->OutputTable($actionArray);
$pdf->MultiCell(190, 0, "<br><br>Remarks : <u>{$repair->remarks}</u><br><br>Repaired By : &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; {$repairedUpperCase} <br>
<span style=\"font-size: 10px;\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Print Name & Signature</span>
", 'TB', 'L', 0, 1, '', '', true, 0, true); //Outer Cell

//* -------------------------------- Designation --------------------------------
$pdf->SetFont('helvetica', '', 12);
$pdf->Cell(63.6, 9, 'Requested By:', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
$pdf->Cell(63.6, 9, 'Acknowledged By:', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
$pdf->Cell(63.6, 9, 'Approved By:', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
$pdf->Ln(23);

    //* -------------------------------- Signature --------------------------------
    $pdf->Image('@' . base64_decode($repair->preparedBySign), 10, 110, 40, 20, '', '', '', false, 300, '', false, false, 0, false, false, false);
    $pdf->Image('@' . base64_decode($repair->acknowledgeBySign), 74, 110, 40, 20, '', '', '', false, 300, '', false, false, 0, false, false, false);
    $pdf->Image('@' . base64_decode($repair->approvedBySign), 140, 110, 40, 20, '', '', '', false, 300, '', false, false, 0, false, false, false);

//* -------------------------------- Responsible --------------------------------
$pdf->SetFont('helvetica', '', 10);
$pdf->Cell(63.6, 5, $repair->requestedBy, 0, 0, 'L', 0, '', 0, false, 'T', 'M');
$pdf->Cell(63.6, 5, $repair->repairedBy, 0, 0, 'L', 0, '', 0, false, 'T', 'M');
$pdf->Cell(63.6, 5, $repair->approvedBy, 0, 0, 'L', 0, '', 0, false, 'T', 'M');
$pdf->Ln();

// //* -------------------------------- Job Position --------------------------------
$pdf->MultiCell(63.6, 5, $repair->requestedByJob, 0, 'L', 0, 0);
$pdf->MultiCell(63.6, 5, $repair->acknowledgedByJob, 0, 'L', 0, 0);
$pdf->MultiCell(63.6, 5, $repair->approvedByJob, 0, 'L', 0, 0);

$filename = "ITR-" . "0001" . "-" . "23" . ".pdf";
$pdf->Output($filename, 'I'); //* Close and output PDF document
