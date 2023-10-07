<?php
include_once '../configuration/connection.php';
include '../vendor/tcpdf/tcpdf.php';
$ITR = $conn->db_conn_it_repair_request(); //* IT REPAIR AND REQUEST Database connection

class Request
{
    public $referenceNumber;
    public $type;
    public $dateRequested;
    public $dateNeeded;
    public $item;
    public $description;
    public $purpose;
    public $requestedBy;
    public $requestedByJob;
    public $approvedBy;
    public $approvedByJob;
    public $notedBy;
    public $notedByJob;
    public $preparedBySign;
    public $approvedBySign;
    public $notedBySign;
    public $dateFinish;
    public $accomplishBy;
    public $accomplishBySign;
    public $preparedByAcknowledgeDate;
    
    public function sqlQuery($sqlstring, $connection)
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

    public function details($ITR, $db1, $db2, $id)
    {
        $sqlstring = "SELECT queue_number, request_type, prepared_by_date, date_needed, item, description, purpose, prepared_by, approved_by, noted_by,repaired_by, repaired_by_date,prepared_by_acknowledge_date,
        encode(prepared_by_sign, 'escape') AS prepared_sign,
        encode(approved_by_sign, 'escape') AS approved_sign,
        encode(noted_by_sign, 'escape') AS noted_sign,
        encode(repaired_by_sign, 'escape') AS accomplish_sign
            FROM tblit_request WHERE request_id = '{$id}'";
        $result_stmt = $ITR->prepare($sqlstring);
        $result_stmt->execute();
        $result_res = $result_stmt->fetchAll();
        foreach ($result_res as $row) {
            // $data_result = self::sqlQuery($sqlstring, $db1);
            // foreach ($data_result['data'] as $row) {
            $this->referenceNumber = $row['queue_number'];
            $this->type = $row['request_type'];
            $this->dateRequested = date("F d, Y", strtotime($row['prepared_by_date']));
            $this->dateNeeded = date("F d, Y", strtotime($row['date_needed']));
            $this->item = $row['item'];
            $this->description = $row['description'];
            $this->purpose = $row['purpose'];
            $this->requestedBy = $row['prepared_by'];
            $this->requestedByJob = self::getJobPosition($db2, $row['prepared_by']);
            $this->approvedBy = $row['approved_by'];
            $this->approvedByJob = self::getJobPosition($db2, $row['approved_by']);
            $this->notedBy = $row['noted_by'];
            $this->notedByJob = self::getJobPosition($db2, $row['noted_by']);
            $this->preparedBySign = $row['prepared_sign'];
            $this->approvedBySign = $row['approved_sign'];
            $this->notedBySign = $row['noted_sign'];
            $this->accomplishBy = $row['repaired_by'];
            $this->accomplishBySign = $row['accomplish_sign'];
            $this->dateFinish = date("m/d/y", strtotime($row['repaired_by_date']));
            $this->preparedByAcknowledgeDate = $row['prepared_by_acknowledge_date'];
        }
    }

    function getJobPosition($db, $name)
    {
        $sqlstring = "SELECT pos_name FROM prl_employee
            INNER JOIN prl_position ON prl_employee.pos_code = prl_position.pos_code
            WHERE CONCAT(emp_fn, ' ', emp_sn) = '{$name}'";
        $data_result = self::sqlQuery($sqlstring, $db);
        foreach ($data_result['data'] as $row) {
            return $row['pos_name'];
        }
    }
}

$requestId = $_GET['id'];
$request = new Request();
$request->details($ITR, $php_fetch_it_repair_api, $php_fetch_bannerweb_api, $requestId);

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
            $this->Cell(0, 0, 'Information Technology Department', 0, 1, 'C');
            $this->SetFont('helvetica', 'B', 14);
            $this->Cell(0, 0, 'IT SOFTWARE/ HARDWARE REQUEST FORM', 0, 0, 'C');
        }
    }

    //* Page footer
    public function Footer()
    {
        $this->SetY(-14);
        $this->SetFont('helvetica', '', 10);
        $this->Cell(191, '', 'ITD/XX-012-00', 0, false, 'R', 0, '', 0, false, 'T', 'M');
    }
}

//* create new PDF document
$pdf = new MYPDF("L", PDF_UNIT, "JUNIORLONG", true, 'UTF-8', false);
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED); //* set default monospaced font
$pdf->SetMargins(12.5, 12, 12.5); //* set margins
$pdf->SetAutoPageBreak(TRUE, 10); //* set page break
$pdf->setPrintHeader(true); //* include header
$pdf->setPrintFooter(true); //* include footer
$pdf->AddPage(); //* Add page
$pdf->Ln(20); //* Next Line

$pdf->Image('../vendor/images/banner-serve.jpg', 15, 1, 55, 30, 'JPG', '', 'T', true, 300, '', false, false, 0, false, false, false);
$pdf->Image('@' . base64_decode($request->accomplishBySign), 27, 15, 20, 10, '', '', '', false, 300, '', false, false, 0, false, false, false);
$pdf->Image('@' . base64_decode($request->preparedBySign), 40, 20, 20, 10, '', '', '', false, 300, '', false, false, 0, false, false, false);

$pdf->SetY(-147);
$pdf->SetX(-193);
$pdf->SetFont('helvetica', 'B', 10);
// $pdf->Cell(0, 0, 'Crescian Lloyd Lanoy', 0, 0, 'L');
$pdf->SetX(-164);
$pdf->Cell(0, 0, $request->preparedByAcknowledgeDate, 0, 0, 'L');
$pdf->SetY(-137);
$pdf->SetX(-177);
// $pdf->Cell(0, 0, 'Crescian Lloyd Lanoy', 0, 0, 'L');

//* -------------------------------- Header --------------------------------
//* Image($file, $x='', $y='', $w=0, $h=0, $type='', $link='', $align='', $resize=false, $dpi=300, $palign='', $ismask=false, $imgmask=false, $border=0, $fitbox=false, $hidden=false, $fitonpage=false)
//* Cell(w, h = 0, txt = '', border = 0, ln = 0, align = '', fill = 0, link = nil, stretch = 0, ignore_min_height = false, calign = 'T', valign = 'M')
$pdf->SetFont('freeserif', '', 14);
$pdf->Cell(138.5, 0, '№', 0, 0, 'R', 0, '', 0, FALSE, '', 'T');
$pdf->SetFont('helvetica', 'B', 12);
$pdf->Cell(27, 0, $request->referenceNumber, 0, 0, 'R', 0, '', 0, FALSE, 'T', 'B');
$pdf->Ln(16.5);
//* -------------------------------- Info --------------------------------
$pdf->SetFont('helvetica', '', 12);
$pdf->Cell(30, 0, 'Request Type :', 0, 0, 'L');
$pdf->Cell(35, 0, $request->type, 0, 0);
$pdf->Cell(89, 0, 'Date Requested:', 0, 0, 'R');
$pdf->Cell(36, 0, $request->dateRequested, 0, 0, 'R');
$pdf->Ln();
$pdf->Cell(154, 0, 'Date Needed:', 0, 0, 'R');
$pdf->Cell(36, 0, $request->dateNeeded, 0, 0, 'R');
$pdf->Ln(10);
//* -------------------------------- Request Item and Description --------------------------------
$pdf->MultiCell(190, 17, '', 1); //Outer Cell

$pdf->MultiCell(36, 9, ' Requested Item : ', 0, '', 0, 0, '', 62);
$pdf->MultiCell(152, 0, $request->item, 'B', '', 0, 1, '', '', true, 0, false, true, 0, 'T');
$pdf->Ln(2);

$pdf->MultiCell(36, 9, ' Purpose : ', 0, '', 0, 0);
$pdf->MultiCell(152, 0, $request->purpose, 'B', '', 0, 1, '', '', true, 0, false, true, 0, 'T');
$pdf->Ln(3.5);

//* -------------------------------- Purpose --------------------------------
$pdf->MultiCell(190, 30, '', 1); //Outer Cell
$pdf->MultiCell(0, 0, " Description : ", 0, '', 0, 0, '', 80.5);
$pdf->Ln(5);
$pdf->MultiCell(135, 0, $request->description, 0, 'L', 0, 1, 20, '', true, 0, false, true, 0, 'T');
$pdf->MultiCell(42.5, 25, '', 1, '', 0, 1, 157.5, 80.5);

// $pdf->CheckBox('urgent', 5, false, array(), array(), 'Yes', 155, 88);
$pdf->MultiCell(45, 0, '<input type="checkbox" name="urgent" value="1" checked=""/><label for="urgent">Urgent</label>', 0, '', 0, 1, 157, 85.5, true, 0, true);
$pdf->Ln(3);
$pdf->MultiCell(45, 0, '<input type="checkbox" name="for_scheduling" value="1" checked=""/><label for="for_scheduling">For Scheduling</label>', 0, '', 0, 1, 157, '', true, 0, true);
$pdf->Ln(9.5);

//* -------------------------------- Designation --------------------------------
$pdf->SetFont('helvetica', '', 12);
$pdf->Cell(63.6, 9, 'Requested by:', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
$pdf->Cell(63.6, 9, 'Approved by:', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
$pdf->Cell(63.6, 9, 'Noted by:', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
$pdf->Ln(10);

//* -------------------------------- Signature --------------------------------
$pdf->Image('@' . base64_decode($request->preparedBySign), 15, 115, 30, 20, '', '', '', false, 300, '', false, false, 0, false, false, false);
$pdf->Image('@' . base64_decode($request->approvedBySign), 74, 115, 40, 20, '', '', '', false, 300, '', false, false, 0, false, false, false);
$pdf->Image('@' . base64_decode($request->notedBySign), 130, 115, 40, 20, '', '', '', false, 300, '', false, false, 0, false, false, false);
$pdf->Ln(10);

//* -------------------------------- Responsible --------------------------------
$pdf->SetFont('helvetica', '', 10);
$pdf->Cell(63.6, 5, $request->requestedBy, 0, 0, 'L', 0, '', 0, false, 'T', 'M');
$pdf->Cell(63.6, 5, $request->approvedBy, 0, 0, 'L', 0, '', 0, false, 'T', 'M');
$pdf->Cell(63.6, 5, $request->notedBy, 0, 0, 'L', 0, '', 0, false, 'T', 'M');
$pdf->Ln();

//* -------------------------------- Job Position --------------------------------
$pdf->MultiCell(63.6, 5, $request->requestedByJob, 0, 'L', 0, 0);
$pdf->MultiCell(63.6, 5, $request->approvedByJob, 0, 'L', 0, 0);
$pdf->MultiCell(63.6, 5, $request->notedByJob, 0, 'L', 0, 0);

// $filename = "company" . "-" . "deletion_date" . "-CFD-" . "refno" . ".pdf";
$filename = $request->referenceNumber . ".pdf";
$pdf->Output($filename, 'I'); //* Close and output PDF document
$emailLogs = null; //* ======== Close Connection ========}
