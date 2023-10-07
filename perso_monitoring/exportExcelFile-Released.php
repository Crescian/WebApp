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
$html .= '<th style="border:1px solid black;">Delivery Date</th>';
$html .= '<th style="border:1px solid black;">DR #</th>';
$html .= '<th style="border:1px solid black;">Remarks</th>';
$html .= '<th style="border:1px solid black;">Delivery Mode</th>';
$html .= '<th style="border:1px solid black;">Dispatch Start</th>';
$html .= '<th style="border:1px solid black;">Dispatch End</th>';
$html .= '</tr>';

$result_sql = "SELECT date_entry,date_receive,customer_name,jonumber,job_description,job_filename,job_quantity,release_date,dr_number,job_remarks,mode_delivery,date_time_start,date_time_end
            FROM bpi_perso_job_entry 
            INNER JOIN bpi_perso_job_process
            ON bpi_perso_job_process.jobentry_id = bpi_perso_job_entry.jobentryid
            WHERE job_status = 'Done' AND process_id = '27' ORDER BY customer_name,date_entry ASC";
$result_stmt = $perso->prepare($result_sql);
$result_stmt->execute();



while ($row = $result_stmt->fetch(PDO::FETCH_ASSOC)) {
    if ($row['dr_number'] == "") {
        $dr_number = '-';
    } else {
        $dr_number = $row['dr_number'];
    }

    $html .= '<tr>';
    $html .= '<td style="border:1px solid black; text-align:center;">' . $row['date_entry'] . '</td>';
    $html .= '<td style="border:1px solid black; text-align:center;">' . $row['date_receive'] . '</td>';
    $html .= '<td style="border:1px solid black;">' . $row['customer_name'] . '</td>';
    $html .= '<td style="border:1px solid black;">' . $row['jonumber'] . '</td>';
    $html .= '<td style="border:1px solid black;">' . $row['job_description'] . '</td>';
    $html .= '<td style="border:1px solid black;">' . $row['job_filename'] . '</td>';
    $html .= '<td style="border:1px solid black;">' . number_format($row['job_quantity']) . '</td>';
    $html .= '<td style="border:1px solid black; text-align:center;">' . $row['release_date'] . '</td>';
    $html .= '<td style="border:1px solid black;">' . $dr_number . '</td>';
    $html .= '<td style="border:1px solid black;">' . $row['job_remarks'] . '</td>';
    $html .= '<td style="border:1px solid black;">' . $row['mode_delivery'] . '</td>';
    $html .= '<td style="border:1px solid black; text-align:center;">' . $row['date_time_start'] . '</td>';
    $html .= '<td style="border:1px solid black; text-align:center;">' . $row['date_time_end'] . '</td>';
    $html .= '</tr>';
}
$html .= '</table>';
header('Content-Type:application/vnd-ms-excel');
header('Content-Disposition:attachment;filename=JobReleased-' . date('Y-m-d h:i:s') . '.xls');
echo $html;

$perso = null; ## ======== Close Connection ========
