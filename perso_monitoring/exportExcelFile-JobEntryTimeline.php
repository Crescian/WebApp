<?php
include '../configuration/connection.php';
date_default_timezone_set('Asia/Manila');
$perso = $conn->db_conn_personalization(); //* Personalization Database connection


$html = '<table>';
$html .= '<tr>';
$html .= '<th style="border:1px solid black;">Date Entry</th>';
$html .= '<th style="border:1px solid black;">Date Received</th>';
$html .= '<th style="border:1px solid black;">Customer Name</th>';
$html .= '<th style="border:1px solid black;">J.O Number</th>';
$html .= '<th style="border:1px solid black;">Description</th>';
$html .= '<th style="border:1px solid black;">Filename</th>';
$html .= '<th style="border:1px solid black;">Quantity</th>';
$html .= '<th style="border:1px solid black;">Instruction</th>';
$html .= '<th style="border:1px solid black;">Delivery Date</th>';
$html .= '<th style="border:1px solid black;">DR #</th>';
$html .= '<th style="border:1px solid black;">Delivery Mode</th>';
$html .= '<th style="border:1px solid black;">Courier</th>';
$html .= '<th style="border:1px solid black;">Status</th>';
$html .= '</tr>';

$result_sql = "SELECT dr_number,date_entry,date_receive,customer_name,jonumber,job_description,job_filename,job_quantity,job_remarks,release_date,mode_delivery,pickup_courier,job_status 
            FROM bpi_perso_job_entry WHERE job_status <> 'Done' ORDER BY customer_name,date_entry ASC";
$result_stmt = $perso->prepare($result_sql);
$result_stmt->execute();

while ($row = $result_stmt->fetch(PDO::FETCH_ASSOC)) {
    $dr_number = $row['dr_number'] == "" ? '-' : $row['dr_number'];
    $courier = $row['pickup_courier'] == "" ? '-' : $row['pickup_courier'];

    $html .= '<tr>';
    $html .= '<td style="border:1px solid black; text-align:center;">' . date_format(date_create($row['date_entry']), 'Y-m-d') . '</td>';
    $html .= '<td style="border:1px solid black; text-align:center;">' . date_format(date_create($row['date_receive']), 'Y-m-d') . '</td>';
    $html .= '<td style="border:1px solid black;">' . $row['customer_name'] . '</td>';
    $html .= '<td style="border:1px solid black;">' . $row['jonumber'] . '</td>';
    $html .= '<td style="border:1px solid black;">' . $row['job_description'] . '</td>';
    $html .= '<td style="border:1px solid black;">' . $row['job_filename'] . '</td>';
    $html .= '<td style="border:1px solid black;">' . number_format($row['job_quantity']) . '</td>';
    $html .= '<td style="border:1px solid black;">' . $row['job_remarks'] . '</td>';
    $html .= '<td style="border:1px solid black; text-align:center;">' . $row['release_date'] . '</td>';
    $html .= '<td style="border:1px solid black;">' . $dr_number . '</td>';
    $html .= '<td style="border:1px solid black;">' . $row['mode_delivery'] . '</td>';
    $html .= '<td style="border:1px solid black;">' . $courier . '</td>';
    $html .= '<td style="border:1px solid black;">' . $row['job_status'] . '</td>';
    $html .= '</tr>';
}
$html .= '</table>';
header('Content-Type:application/vnd-ms-excel');
header('Content-Disposition:attachment;filename=JobEntry-' . date('Y-m-d h:i:s') . '.xls');
echo $html;

$perso = null; //* ======== Close Connection ========
