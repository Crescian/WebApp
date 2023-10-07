<?php
include '../vendor/tcpdf/tcpdf.php';
include_once '../configuration/connection.php';
$PHD = $conn->db_conn_physical_security(); //* Physical Security Database connection

$dailyroomid = $_GET['d'];

$sqlstringheader = "SELECT dr_prepared_main,dr_prepared_main2,dr_prepared_main3,dr_prepared_admin_lobby,dr_prepared_admin_lobby2,dr_prepared_admin_lobby3,dr_prepared_warehouse_2_3,dr_prepared_warehouse_2_32,dr_prepared_warehouse_2_33,dr_prepared_admin_lobby,dr_prepared_warehouse_2_3,dr_noted,dr_date,

dr_prepared_main_date,dr_prepared_main2_date,dr_prepared_main3_date,
dr_prepared_admin_lobby_date,dr_prepared_admin_lobby2_date,dr_prepared_admin_lobby3_date,
dr_prepared_warehouse_2_3_date,dr_prepared_warehouse_2_3_date2,dr_prepared_warehouse_2_3_date3,

encode(dr_prepared_main_sign, 'escape') AS main_sign,
encode(dr_prepared_admin_lobby_sign, 'escape') AS admin_sign,
encode(dr_prepared_warehouse_2_3_sign, 'escape') AS warehouse_sign,

encode(dr_prepared_main2_sign, 'escape') AS main_sign2,
encode(dr_prepared_admin_lobby2_sign, 'escape') AS admin_sign2,
encode(dr_prepared_warehouse_2_3_sign2, 'escape') AS warehouse_sign2,

encode(dr_prepared_main3_sign, 'escape') AS main_sign3,
encode(dr_prepared_admin_lobby3_sign, 'escape') AS admin_sign3,
encode(dr_prepared_warehouse_2_3_sign3, 'escape') AS warehouse_sign3,

CASE WHEN dr_noted_acknowledge = true THEN encode(dr_noted_sign, 'escape') ELSE '' END AS dr_noted_sign 
FROM phd_dr_inspection_header WHERE dailyroomid = :dailyroomid";

$result_stmt_header = $PHD->prepare($sqlstringheader);
$result_stmt_header->bindParam(':dailyroomid', $dailyroomid);
$result_stmt_header->execute();
$result_res_header = $result_stmt_header->fetchAll();
foreach ($result_res_header as $row) {
    $main_plant = $row['dr_prepared_main'];
    $admin_lobby = $row['dr_prepared_admin_lobby'];
    $warehouse = $row['dr_prepared_warehouse_2_3'];

    $main_plant2 = $row['dr_prepared_main2'];
    $admin_lobby2 = $row['dr_prepared_admin_lobby2'];
    $warehouse2 = $row['dr_prepared_warehouse_2_32'];

    $main_plant3 = $row['dr_prepared_main3'];
    $admin_lobby3 = $row['dr_prepared_admin_lobby3'];
    $warehouse3 = $row['dr_prepared_warehouse_2_33'];

    $main_date = $row['dr_prepared_main_date'];
    $admin_lobby_date = $row['dr_prepared_admin_lobby_date'];
    $warehouse_date = $row['dr_prepared_warehouse_2_3_date'];

    $main_date2 = $row['dr_prepared_main2_date'];
    $admin_lobby_date2 = $row['dr_prepared_admin_lobby2_date'];
    $warehouse_date2 = $row['dr_prepared_warehouse_2_3_date2'];

    $main_date3 = $row['dr_prepared_main3_date'];
    $admin_lobby_date3 = $row['dr_prepared_admin_lobby3_date'];
    $warehouse_date3 = $row['dr_prepared_warehouse_2_3_date3'];

    $NotedBy = $row['dr_noted'];
    $vsQuarterDate = date_format(date_create($row['dr_date']), 'F d,Y');

    $main_sign = $row['main_sign'];
    $admin_sign = $row['admin_sign'];
    $warehouse_sign = $row['warehouse_sign'];

    $main_sign2 = $row['main_sign2'];
    $admin_sign2 = $row['admin_sign2'];
    $warehouse_sign2 = $row['warehouse_sign2'];

    $main_sign3 = $row['main_sign3'];
    $admin_sign3 = $row['admin_sign3'];
    $warehouse_sign3 = $row['warehouse_sign3'];

    $dr_noted_sign = $row['dr_noted_sign'];
}
$categoryArray = array();
$sqlstringcategory = "SELECT dr_rooms,dr_category,dr_aircon_off,dr_lights_off,dr_door_locked,dr_conv_outlet_unplugged,dr_time_activated,dr_rooms,dr_remarks FROM phd_dr_inspection_details
WHERE dailyroom_id = '" . $dailyroomid . "' ORDER BY drdetailid ASC";
$result_stmt_categ = $PHD->prepare($sqlstringcategory);
$result_stmt_categ->execute();
while ($row = $result_stmt_categ->fetch(PDO::FETCH_ASSOC)) {
    $categoryArray[$row['dr_category']][] = $row; //* ======== 2D Array
}

function getActionCode($category)
{
    switch ($category) {
        case 0:
            $actioncode = '-';
            break;
        case 1:
            $actioncode = '/';
            break;
        case 2:
            $actioncode = 'X';
            break;
        case 3:
            $actioncode = 'N/A';
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
            $this->Image('../vendor/images/Banner Logo_V1.jpg', 86, 12, 43, 11, 'JPG', '', 'T', true, 300, '', false, false, 0, false, false, false);
            $this->Image('../vendor/images/ISO Logo_V1.jpg', 159, 12, 44, 12, 'JPG', '', 'T', true, 300, '', false, false, 0, false, false, false);
            $this->Ln(14.5);
            //* Cell(w, h = 0, txt = '', border = 0, ln = 0, align = '', fill = 0, link = nil, stretch = 0, ignore_min_height = false, calign = 'T', valign = 'M')
            $this->SetFont('helvetica', '', 12);
            $this->Cell(0, 0, 'Physical Security Department', 0, 1, 'C');
            $this->SetFont('helvetica', 'B', 14);
            $this->Cell(0, 0, 'DAILY ROOM INSPECTION CHECKLIST', 0, 0, 'C');
        }
        if ($this->PageNo() >= 2) {
            $this->SetFont('helvetica', '', 10);
            $this->Cell(0, 7, '', 0, 1, 'L');
            $this->Cell(0, 5, 'Daily Room Inspection Checklist', 0, 0, 'L');
        }
    }

    //* Page footer
    public function Footer()
    {
        //* Position at 26 mm from bottom
        $this->SetY(-20);
        $this->SetFont('helvetica', '', 10);
        // ! My code here -->
        // Get the current page number
        $current_page = $this->getPage();
        if (
            $current_page == 1
        ) {
            $this->SetY(-26);
            $this->SetFont('helvetica', '', 10);
            $this->Cell(94.5, '', 'Note: continuation on back page', 0, false, 'L', 0, '', 0, false, 'T', 'M');
            $this->Cell(95.5, 5, 'Instruction: If yes put / , if no put X', 0, 0, 'R', 0, '', 0, false, 'T', 'M');
            $this->Ln();
        }
        //* Page Number
        //* Cell(w, h = 0, txt = '', border = 0, ln = 0, align = '', fill = 0, link = nil, stretch = 0, ignore_min_height = false, calign = 'T', valign = 'M')
        $this->Cell(191, '', 'PHD/IS-002-00', 0, false, 'R', 0, '', 0, false, 'T', 'M');
        $this->Ln(4);
        // ! end
        //* Page Number
        //TODO Fix Alignment if its only single page
        if ($this->PageNo() > 1) {
            $this->SetY(-16);
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
                <table cellspacing="0" cellpadding="0.2" border=".1" style="width:100%">
                    <tr>
                        <td style="text-align: center; width: 34%; font-size: 12px;"><div style="font-size:5pt">&nbsp;</div>Rooms</td>
                        <td style="text-align: center; width: 10%; font-size: 12px;">Time Checked</td>
                        <td style="text-align: center; width: 9%; font-size: 12px;">Aircon Off</td>
                        <td style="text-align: center; width: 9%; font-size: 12px;">Lights Off</td>
                        <td style="text-align: center; width: 10%; font-size: 12px;">Door Locked</td>
                        <td style="text-align: center; width: 15%; font-size: 12px;">Conv. Outlet Unplugged</td>
                        <td style="text-align: center; width: 13%; font-size: 12px;"><div style="font-size:5pt">&nbsp;</div>Remarks</td>
                    </tr>
                </table>';
        $this->writeHTML($html_header, false, false, true, false, '');
    }

    public function OutputTable($categoryArray)
    {
        $this->TableHeader();
        $w = array(65, 19, 17, 17.5, 19, 28.5, 25); //* width
        foreach ($categoryArray as $dailyCat => $detailContainer) {
            $removeExtraName = $dailyCat == 'Main Plant (Daily Room)' ? substr($dailyCat, 0, 10) : $dailyCat;
            $this->Cell(0, 5, $removeExtraName, 1, 0, 'L', 0);
            $this->Ln();
            foreach ($detailContainer as $details) {
                $num_pages = $this->getNumPages(); //* Get current number of pages.
                $this->startTransaction();
                $aircon = getActionCode($details['dr_aircon_off']);
                $lights = getActionCode($details['dr_lights_off']);
                $door = getActionCode($details['dr_door_locked']);
                $outlet = getActionCode($details['dr_conv_outlet_unplugged']);
                $date = $details['dr_time_activated'] == NULL ? '-' : date_format(date_create($details['dr_time_activated']), 'Hm') . 'H';

                $this->Cell($w[0], 5, $details['dr_rooms'], 1, 0, 'L', 0);
                $this->Cell($w[1], 5, $date, 1, 0, 'C', 0);
                $this->Cell($w[2], 5, $aircon, 1, 0, 'C', 0);
                $this->Cell($w[3], 5, $lights, 1, 0, 'C', 0);
                $this->Cell($w[4], 5, $door, 1, 0, 'C', 0);
                $this->Cell($w[5], 5, $outlet, 1, 0, 'C', 0);
                $this->Cell($w[6], 5, $details['dr_remarks'], 1, 0, 'C', 0);
                $this->Ln();

                if ($num_pages < $this->getNumPages()) {
                    $this->rollbackTransaction(true); //* Undo adding the row.
                    //* Add page
                    $this->SetMargins(12.5, 13, 12.5);
                    $this->AddPage();
                    //* Draw the header.
                    $this->TableHeader();
                    // * Re-do the row.
                    $this->Cell('', 5, $removeExtraName, 'LRB', 0, 'L', 0);
                    $this->Ln();
                } else {
                    $this->commitTransaction(); //* Otherwise we are fine with this row, discard undo history.
                }
            }
        }
        $this->Cell(array_sum($w), 0, '', 'T');
        $this->Ln();
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
$pdf->SetAutoPageBreak(TRUE, 30); //* set page break
$pdf->AddPage();
$pdf->Ln(33);
//* -------------------------------- Info --------------------------------
$pdf->SetFont('helvetica', '', 12);
$pdf->Cell(161, 0, 'Date: ', 0, 0, 'R');
$pdf->Cell(30, 0, $vsQuarterDate, 0, 0, 'C');
$pdf->Ln(7);
$pdf->SetFont('helvetica', '', 10);
$pdf->OutputTable($categoryArray);
// ! My code here -->
$pdf->SetFont('helvetica', '', 10);
$pdf->Cell(0, 5, 'Instruction: If yes put / , if no put X', 0, 0, 'R', 0, '', 0, false, 'T', 'M');
$pdf->Ln();
//* -------------------------------- Responsible --------------------------------
$pdf->SetFont('helvetica', '', 12);
$pdf->Cell(63.6, 9, 'Prepared By:', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
$pdf->Ln(5);
$pdf->SetFont('helvetica', '', 10);

if ($main_plant2 == '' and $main_plant3 == '') {
    // $pdf->Image('@' . base64_decode($main_sign), 11, 215.5, 40, 20, '', '', '', false, 300, '', false, false, 0, false, false, false);
    $pdf->Ln(15);
    $pdf->Cell(52.5, 5, $main_plant, 0, 0, 'L', 0, '', 0, false, 'T', 'M');
    $pdf->Cell(3, 5, '', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
    $pdf->Cell(20, 5, 'Main Plant', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
    $pdf->Ln();
    $pdf->Cell(38.8, 5, 'Internal Security Staff', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
    $pdf->Ln();
} else if ($main_plant3 != '' and $main_plant2 != '') {
    if ($main_plant == $main_plant3) {
        // $pdf->Image('@' . base64_decode($main_sign), 11, 215.5, 40, 20, '', '', '', false, 300, '', false, false, 0, false, false, false);
        $pdf->Ln(15);
        $pdf->Cell(52.5, 5, $main_plant, 0, 0, 'L', 0, '', 0, false, 'T', 'M');
        $pdf->Cell(3, 5, '', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
        $pdf->Cell(20, 5, 'Main Plant', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
        $pdf->Ln();
        $pdf->Cell(38.8, 5, 'Internal Security Staff', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
        $pdf->Ln();
        $pdf->Cell(55.5, 4, 'Date: ' . date_format(date_create($main_date3), 'm-d-y'), 0, 0, 'L', 0, '', 0, false, 'T', 'M');
    } else if ($main_plant == $main_plant2) {
        // $pdf->Image('@' . base64_decode($main_sign2), 11, 215.5, 40, 20, '', '', '', false, 300, '', false, false, 0, false, false, false);
        $pdf->Ln(15);
        $pdf->Cell(52.5, 5, $main_plant2, 0, 0, 'L', 0, '', 0, false, 'T', 'M');
        $pdf->Cell(3, 5, '', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
        $pdf->Cell(20, 5, 'Main Plant', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
        $pdf->Ln();
        $pdf->Cell(38.8, 5, 'Internal Security Staff', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
        $pdf->Ln();
        $pdf->Cell(55.5, 4, 'Date: ' . date_format(date_create($main_date2), 'm-d-y'), 0, 0, 'L', 0, '', 0, false, 'T', 'M');
    } else {
        if ($main_plant2 == $main_plant3) {
            // $pdf->Image('@' . base64_decode($main_sign), 11, 215.5, 40, 20, '', '', '', false, 300, '', false, false, 0, false, false, false);
            // $pdf->Image('@' . base64_decode($main_sign3), 65, '', 40, 20, '', '', '', false, 300, '', false, false, 0, false, false, false);
            $pdf->Ln(15);
            $pdf->Cell(52.5, 5, $main_plant, 0, 0, 'L', 0, '', 0, false, 'T', 'M');
            $pdf->Cell(3, 5, '', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
            $pdf->Cell(52.5, 5, $main_plant3, 0, 0, 'L', 0, '', 0, false, 'T', 'M');
            $pdf->Cell(20, 5, 'Main Plant', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
            $pdf->Ln();
            $pdf->Cell(52.5, 5, 'Internal Security Staff', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
            $pdf->Cell(3, 5, '', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
            $pdf->Cell(52.5, 5, 'Internal Security Staff', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
            $pdf->Ln();
            $pdf->Cell(55.5, 4, 'Date: ' . date_format(date_create($main_date), 'm-d-y'), 0, 0, 'L', 0, '', 0, false, 'T', 'M');
            $pdf->Cell(55.5, 4, 'Date: ' . date_format(date_create($main_date3), 'm-d-y'), 0, 0, 'L', 0, '', 0, false, 'T', 'M');
        } else {
            // $pdf->Image('@' . base64_decode($main_sign), 11, 215.5, 40, 20, '', '', '', false, 300, '', false, false, 0, false, false, false);
            // $pdf->Image('@' . base64_decode($main_sign2), 65, '', 40, 20, '', '', '', false, 300, '', false, false, 0, false, false, false);
            // $pdf->Image('@' . base64_decode($main_sign3), 116, '', 40, 20, '', '', '', false, 300, '', false, false, 0, false, false, false);
            $pdf->Ln(15);
            $pdf->Cell(52.5, 5, $main_plant, 0, 0, 'L', 0, '', 0, false, 'T', 'M');
            $pdf->Cell(3, 5, '', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
            $pdf->Cell(52.5, 5, $main_plant2, 0, 0, 'L', 0, '', 0, false, 'T', 'M');
            $pdf->Cell(3, 5, '', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
            $pdf->Cell(52.5, 5, $main_plant3, 0, 0, 'L', 0, '', 0, false, 'T', 'M');
            $pdf->Cell(20, 5, 'Main Plant', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
            $pdf->Ln();
            $pdf->Cell(52.5, 5, 'Internal Security Staff', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
            $pdf->Cell(3, 5, '', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
            $pdf->Cell(52.5, 5, 'Internal Security Staff', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
            $pdf->Cell(3, 5, '', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
            $pdf->Cell(52.5, 5, 'Internal Security Staff', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
            $pdf->Ln();
            $pdf->Cell(55.5, 4, 'Date: ' . date_format(date_create($main_date), 'm-d-y'), 0, 0, 'L', 0, '', 0, false, 'T', 'M');
            $pdf->Cell(55.5, 4, 'Date: ' . date_format(date_create($main_date2), 'm-d-y'), 0, 0, 'L', 0, '', 0, false, 'T', 'M');
            $pdf->Cell(55.5, 4, 'Date: ' . date_format(date_create($main_date3), 'm-d-y'), 0, 0, 'L', 0, '', 0, false, 'T', 'M');
        }
    }
} else if ($main_plant2 != '') {
    if ($main_plant == $main_plant2) {
        // $pdf->Image('@' . base64_decode($main_sign2), 11, 215.5, 40, 20, '', '', '', false, 300, '', false, false, 0, false, false, false);
        $pdf->Ln(15);
        $pdf->Cell(52.5, 5, $main_plant2, 0, 0, 'L', 0, '', 0, false, 'T', 'M');
        $pdf->Cell(3, 5, '', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
        $pdf->Cell(20, 5, 'Main Plant', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
        $pdf->Ln();
        $pdf->Cell(38.8, 5, 'Internal Security Staff', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
        $pdf->Ln();
        $pdf->Cell(55.5, 4, 'Date: ' . date_format(date_create($warehouse_date2), 'm-d-y'), 0, 0, 'L', 0, '', 0, false, 'T', 'M');
    } else {
        // $pdf->Image('@' . base64_decode($main_sign), 11, 215.5, 40, 20, '', '', '', false, 300, '', false, false, 0, false, false, false);
        // $pdf->Image('@' . base64_decode($main_sign2), 65, '', 40, 20, '', '', '', false, 300, '', false, false, 0, false, false, false);
        $pdf->Ln(15);
        $pdf->Cell(52.5, 5, $main_plant, 0, 0, 'L', 0, '', 0, false, 'T', 'M');
        $pdf->Cell(3, 5, '', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
        $pdf->Cell(52.5, 5, $main_plant2, 0, 0, 'L', 0, '', 0, false, 'T', 'M');
        $pdf->Cell(20, 5, 'Main Plant', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
        $pdf->Ln();
        $pdf->Cell(52.5, 5, 'Internal Security Staff', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
        $pdf->Cell(3, 5, '', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
        $pdf->Cell(52.5, 5, 'Internal Security Staff', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
        $pdf->Ln();
        $pdf->Cell(55.5, 4, '', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
        $pdf->Cell(55.5, 4, 'Date: ' . date_format(date_create($main_date2), 'm-d-y'), 0, 0, 'L', 0, '', 0, false, 'T', 'M');
    }
}
$pdf->Ln();
if ($admin_lobby2 == '' and $admin_lobby3 == '') {
    // $pdf->Image('@' . base64_decode($admin_sign), 11, '', 40, 20, '', '', '', false, 300, '', false, false, 0, false, false, false);
    $pdf->Ln(10);
    $pdf->Cell(52.5, 5, $admin_lobby, 0, 0, 'L', 0, '', 0, false, 'T', 'M');
    $pdf->Cell(3, 5, '', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
    $pdf->Cell(30, 5, 'Admin & Lobby', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
    $pdf->Ln();
    $pdf->Cell(52.5, 5, 'Internal Security Staff', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
    $pdf->Ln();
} else if ($admin_lobby3 != '' and $admin_lobby2 != '') {
    if ($admin_lobby == $admin_lobby3) {
        // $pdf->Image('@' . base64_decode($admin_sign3), 11, '', 40, 20, '', '', '', false, 300, '', false, false, 0, false, false, false);
        $pdf->Ln(10);
        $pdf->Cell(52.5, 5, $admin_lobby3, 0, 0, 'L', 0, '', 0, false, 'T', 'M');
        $pdf->Cell(3, 5, '', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
        $pdf->Cell(30, 5, 'Admin & Lobby', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
        $pdf->Ln();
        $pdf->Cell(52.5, 5, 'Internal Security Staff', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
        $pdf->Ln();
        $pdf->Cell(52.5, 4, 'Date: ' . date_format(date_create($admin_lobby_date3), 'm-d-y'), 0, 0, 'L', 0, '', 0, false, 'T', 'M');
    } else if ($admin_lobby == $admin_lobby2) {
        // $pdf->Image('@' . base64_decode($admin_sign2), 11, '', 40, 20, '', '', '', false, 300, '', false, false, 0, false, false, false);
        $pdf->Ln(10);
        $pdf->Cell(52.5, 5, $admin_lobby2, 0, 0, 'L', 0, '', 0, false, 'T', 'M');
        $pdf->Cell(3, 5, '', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
        $pdf->Cell(30, 5, 'Admin & Lobby', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
        $pdf->Ln();
        $pdf->Cell(52.5, 5, 'Internal Security Staff', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
        $pdf->Ln();
        $pdf->Cell(52.5, 4, 'Date: ' . date_format(date_create($admin_lobby_date2), 'm-d-y'), 0, 0, 'L', 0, '', 0, false, 'T', 'M');
    } else {
        if ($admin_lobby2 == $admin_lobby3) {
            // $pdf->Image('@' . base64_decode($admin_sign), 11, '', 40, 20, '', '', '', false, 300, '', false, false, 0, false, false, false);
            // $pdf->Image('@' . base64_decode($admin_sign3), 65, '', 40, 20, '', '', '', false, 300, '', false, false, 0, false, false, false);
            $pdf->Ln(10);
            $pdf->Cell(52.5, 5, $admin_lobby3, 0, 0, 'L', 0, '', 0, false, 'T', 'M');
            $pdf->Cell(3, 5, '', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
            $pdf->Cell(52.5, 5, $admin_lobby3, 0, 0, 'L', 0, '', 0, false, 'T', 'M');
            $pdf->Ln();
            $pdf->Cell(52.5, 5, 'Internal Security Staff', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
            $pdf->Cell(3, 5, '', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
            $pdf->Cell(52.5, 5, 'Internal Security Staff', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
            $pdf->Ln();
            $pdf->Cell(55.5, 4, 'Date: ' . date_format(date_create($admin_lobby_date), 'm-d-y'), 0, 0, 'L', 0, '', 0, false, 'T', 'M');
            $pdf->Cell(55.5, 4, 'Date: ' . date_format(date_create($admin_lobby_date3), 'm-d-y'), 0, 0, 'L', 0, '', 0, false, 'T', 'M');
        } else {
            // $pdf->Image('@' . base64_decode($admin_sign), 11, '', 40, 20, '', '', '', false, 300, '', false, false, 0, false, false, false);
            // $pdf->Image('@' . base64_decode($admin_sign2), 65, '', 40, 20, '', '', '', false, 300, '', false, false, 0, false, false, false);
            // $pdf->Image('@' . base64_decode($admin_sign3), 116, '', 40, 20, '', '', '', false, 300, '', false, false, 0, false, false, false);
            $pdf->Ln(10);
            $pdf->Cell(52.5, 5, $admin_lobby, 0, 0, 'L', 0, '', 0, false, 'T', 'M');
            $pdf->Cell(3, 5, '', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
            $pdf->Cell(52.5, 5, $admin_lobby2, 0, 0, 'L', 0, '', 0, false, 'T', 'M');
            $pdf->Cell(3, 5, '', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
            $pdf->Cell(52.5, 5, $admin_lobby3, 0, 0, 'L', 0, '', 0, false, 'T', 'M');
            $pdf->Cell(30, 5, 'Admin & Lobby', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
            $pdf->Ln();
            $pdf->Cell(52.5, 5, 'Internal Security Staff', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
            $pdf->Cell(3, 5, '', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
            $pdf->Cell(52.5, 5, 'Internal Security Staff', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
            $pdf->Cell(3, 5, '', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
            $pdf->Cell(52.5, 5, 'Internal Security Staff', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
            $pdf->Ln();
            $pdf->Cell(55.5, 4, '', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
            $pdf->Cell(55.5, 4, 'Date: ' . date_format(date_create($admin_lobby_date2), 'm-d-y'), 0, 0, 'L', 0, '', 0, false, 'T', 'M');
            $pdf->Cell(55.5, 4, 'Date: ' . date_format(date_create($admin_lobby_date3), 'm-d-y'), 0, 0, 'L', 0, '', 0, false, 'T', 'M');
        }
    }
} else if ($admin_lobby2 != '') {
    if ($admin_lobby == $admin_lobby2) {
        // $pdf->Image('@' . base64_decode($admin_sign2), 11, '', 40, 20, '', '', '', false, 300, '', false, false, 0, false, false, false);
        $pdf->Ln(15);
        $pdf->Cell(52.5, 5, $admin_lobby2, 0, 0, 'L', 0, '', 0, false, 'T', 'M');
        $pdf->Cell(3, 5, '', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
        $pdf->Cell(20, 5, 'Main Plant', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
        $pdf->Ln();
        $pdf->Cell(38.8, 5, 'Internal Security Staff', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
        $pdf->Ln();
        $pdf->Cell(55.5, 4, 'Date: ' . date_format(date_create($warehouse_date2), 'm-d-y'), 0, 0, 'L', 0, '', 0, false, 'T', 'M');
    } else {
        // $pdf->Image('@' . base64_decode($admin_sign), 11, '', 40, 20, '', '', '', false, 300, '', false, false, 0, false, false, false);
        // $pdf->Image('@' . base64_decode($admin_sign2), 65, '', 40, 20, '', '', '', false, 300, '', false, false, 0, false, false, false);
        $pdf->Ln(10);
        $pdf->Cell(52.5, 5, $admin_lobby, 0, 0, 'L', 0, '', 0, false, 'T', 'M');
        $pdf->Cell(3, 5, '', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
        $pdf->Cell(52.5, 5, $admin_lobby2, 0, 0, 'L', 0, '', 0, false, 'T', 'M');
        $pdf->Cell(30, 5, 'Admin & Lobby', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
        $pdf->Ln();
        $pdf->Cell(52.5, 5, 'Internal Security Staff', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
        $pdf->Cell(3, 5, '', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
        $pdf->Cell(52.5, 5, 'Internal Security Staff', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
        $pdf->Ln();
        $pdf->Cell(55.5, 4, '', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
        $pdf->Cell(55.5, 4, 'Date: ' . date_format(date_create($admin_lobby_date2), 'm-d-y'), 0, 0, 'L', 0, '', 0, false, 'T', 'M');
    }
}
$pdf->Ln();
if ($warehouse2 == '' and $warehouse3 == '') {
    // $pdf->Image('@' . base64_decode($warehouse_sign), 11, '', 40, 20, '', '', '', false, 300, '', false, false, 0, false, false, false);
    $pdf->Ln(8);
    $pdf->Cell(52.5, 5, $warehouse, 0, 0, 'L', 0, '', 0, false, 'T', 'M');
    $pdf->Cell(3, 5, '', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
    $pdf->Cell(30, 5, 'Warehouse 2 & 3', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
    $pdf->Ln();
    $pdf->Cell(52.5, 5, 'Internal Security Staff', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
    $pdf->Ln();
} else if ($warehouse3 != '' and $warehouse2 != '') {
    if ($warehouse == $warehouse3) {
        // $pdf->Image('@' . base64_decode($warehouse_sign3), 11, '', 40, 20, '', '', '', false, 300, '', false, false, 0, false, false, false);
        $pdf->Ln(8);
        $pdf->Cell(52.5, 5, $warehouse3, 0, 0, 'L', 0, '', 0, false, 'T', 'M');
        $pdf->Cell(3, 5, '', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
        $pdf->Cell(30, 5, 'Warehouse 2 & 3', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
        $pdf->Ln();
        $pdf->Cell(52.5, 5, 'Internal Security Staff', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
        $pdf->Ln();
        $pdf->Cell(55.5, 4, 'Date: ' . date_format(date_create($warehouse_date3), 'm-d-y'), 0, 0, 'L', 0, '', 0, false, 'T', 'M');
    } else if ($warehouse == $warehouse2) {
        // $pdf->Image('@' . base64_decode($warehouse_sign2), 11, '', 40, 20, '', '', '', false, 300, '', false, false, 0, false, false, false);
        $pdf->Ln(8);
        $pdf->Cell(52.5, 5, $warehouse2, 0, 0, 'L', 0, '', 0, false, 'T', 'M');
        $pdf->Cell(3, 5, '', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
        $pdf->Cell(30, 5, 'Warehouse 2 & 3', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
        $pdf->Ln();
        $pdf->Cell(52.5, 5, 'Internal Security Staff', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
        $pdf->Ln();
        $pdf->Cell(55.5, 4, 'Date: ' . date_format(date_create($warehouse_date2), 'm-d-y'), 0, 0, 'L', 0, '', 0, false, 'T', 'M');
    } else {
        if ($warehouse2 == $warehouse3) {
            // $pdf->Image('@' . base64_decode($warehouse_sign), 11, '', 40, 20, '', '', '', false, 300, '', false, false, 0, false, false, false);
            // $pdf->Image('@' . base64_decode($warehouse_sign3), 65, '', 40, 20, '', '', '', false, 300, '', false, false, 0, false, false, false);
            $pdf->Ln(10);
            $pdf->Cell(52.5, 5, $warehouse3, 0, 0, 'L', 0, '', 0, false, 'T', 'M');
            $pdf->Cell(3, 5, '', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
            $pdf->Cell(52.5, 5, $warehouse3, 0, 0, 'L', 0, '', 0, false, 'T', 'M');
            $pdf->Ln();
            $pdf->Cell(52.5, 5, 'Internal Security Staff', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
            $pdf->Cell(3, 5, '', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
            $pdf->Cell(52.5, 5, 'Internal Security Staff', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
            $pdf->Ln();
            $pdf->Cell(55.5, 4, 'Date: ' . date_format(date_create($warehouse_date), 'm-d-y'), 0, 0, 'L', 0, '', 0, false, 'T', 'M');
            $pdf->Cell(55.5, 4, 'Date: ' . date_format(date_create($warehouse_date3), 'm-d-y'), 0, 0, 'L', 0, '', 0, false, 'T', 'M');
        } else {
            // $pdf->Image('@' . base64_decode($warehouse_sign), 11, '', 40, 20, '', '', '', false, 300, '', false, false, 0, false, false, false);
            // $pdf->Image('@' . base64_decode($warehouse_sign2), 65, '', 40, 20, '', '', '', false, 300, '', false, false, 0, false, false, false);
            // $pdf->Image('@' . base64_decode($warehouse_sign3), 116, '', 40, 20, '', '', '', false, 300, '', false, false, 0, false, false, false);
            $pdf->Ln(8);
            $pdf->Cell(52.5, 5, $warehouse, 0, 0, 'L', 0, '', 0, false, 'T', 'M');
            $pdf->Cell(3, 5, '', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
            $pdf->Cell(52.5, 5, $warehouse2, 0, 0, 'L', 0, '', 0, false, 'T', 'M');
            $pdf->Cell(3, 5, '', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
            $pdf->Cell(52.5, 5, $warehouse3, 0, 0, 'L', 0, '', 0, false, 'T', 'M');
            $pdf->Cell(30, 5, 'Warehouse 2 & 3', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
            $pdf->Ln();
            $pdf->Cell(52.5, 5, 'Internal Security Staff', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
            $pdf->Cell(3, 5, '', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
            $pdf->Cell(52.5, 5, 'Internal Security Staff', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
            $pdf->Cell(3, 5, '', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
            $pdf->Cell(52.5, 5, 'Internal Security Staff', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
            $pdf->Ln();
            $pdf->Cell(55.5, 4, 'Date: ' . date_format(date_create($warehouse_date), 'm-d-y'), 0, 0, 'L', 0, '', 0, false, 'T', 'M');
            $pdf->Cell(55.5, 4, 'Date: ' . date_format(date_create($warehouse_date2), 'm-d-y'), 0, 0, 'L', 0, '', 0, false, 'T', 'M');
            $pdf->Cell(55.5, 4, 'Date: ' . date_format(date_create($warehouse_date3), 'm-d-y'), 0, 0, 'L', 0, '', 0, false, 'T', 'M');
        }
    }
} else if ($warehouse2 != '') {
    if ($warehouse == $warehouse2) {
        // $pdf->Image('@' . base64_decode($warehouse_sign), 11, '', 40, 20, '', '', '', false, 300, '', false, false, 0, false, false, false);
        $pdf->Ln(15);
        $pdf->Cell(52.5, 5, $warehouse2, 0, 0, 'L', 0, '', 0, false, 'T', 'M');
        $pdf->Cell(3, 5, '', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
        $pdf->Cell(20, 5, 'Main Plant', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
        $pdf->Ln();
        $pdf->Cell(38.8, 5, 'Internal Security Staff', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
        $pdf->Ln();
        $pdf->Cell(55.5, 4, 'Date: ' . date_format(date_create($warehouse_date2), 'm-d-y'), 0, 0, 'L', 0, '', 0, false, 'T', 'M');
    } else {
        // $pdf->Image('@' . base64_decode($warehouse_sign), 11, '', 40, 20, '', '', '', false, 300, '', false, false, 0, false, false, false);
        // $pdf->Image('@' . base64_decode($warehouse_sign2), 65, '', 40, 20, '', '', '', false, 300, '', false, false, 0, false, false, false);
        $pdf->Ln(8);
        $pdf->Cell(52.5, 5, $warehouse, 0, 0, 'L', 0, '', 0, false, 'T', 'M');
        $pdf->Cell(3, 5, '', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
        $pdf->Cell(52.5, 5, $warehouse2, 0, 0, 'L', 0, '', 0, false, 'T', 'M');
        $pdf->Cell(30, 5, 'Warehouse 2 & 3', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
        $pdf->Ln();
        $pdf->Cell(52.5, 5, 'Internal Security Staff', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
        $pdf->Cell(3, 5, '', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
        $pdf->Cell(52.5, 5, 'Internal Security Staff', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
        $pdf->Ln();
        $pdf->Cell(55.5, 4, '', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
        $pdf->Cell(55.5, 4, 'Date: ' . date_format(date_create($warehouse_date2), 'm-d-y'), 0, 0, 'L', 0, '', 0, false, 'T', 'M');
    }
}
$pdf->Ln(10);
$pdf->SetFont('helvetica', '', 12);
$pdf->Cell(38.6, 9, 'Noted by:', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
$pdf->Ln(8);
// $pdf->Image('@' . base64_decode($dr_noted_sign), 11, '', 40, 20, '', '', '', false, 300, '', false, false, 0, false, false, false);
$pdf->Ln(12);
$pdf->SetFont('helvetica', '', 10);
$pdf->Cell(38.8, 5, $NotedBy, 0, 1, 'L', 0, '', 0, false, 'T', 'M');
$pdf->Cell(46.8, 5, 'VP for Physical Security', 0, 0, 'L', 0, '', 0, false, 'T', 'M');
// ! end
$filename = "Daily Room Inspection Checklist.pdf";
$pdf->Output($filename, 'I'); //* Close and output PDF document