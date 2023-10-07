<?php
include '../configuration/connection.php';
date_default_timezone_set('Asia/Manila');
$perso = $conn->db_conn_personalization(); //* Personalization Database connection

$jobSection = $_GET['d'];

$html = '<table>';
$html .= '<tr>';
$html .= '<th style="border:1px solid black;">Priority #</th>';
$html .= '<th style="border:1px solid black;">Date Entry</th>';
$html .= '<th style="border:1px solid black;">Customer Name</th>';
$html .= '<th style="border:1px solid black;">J.O Number</th>';
$html .= '<th style="border:1px solid black;">Description</th>';
$html .= '<th style="border:1px solid black;">Filename</th>';
$html .= '<th style="border:1px solid black;">Process Name</th>';
$html .= '<th style="border:1px solid black;">Status</th>';
$html .= '<th style="border:1px solid black;">Date Time Start</th>';
$html .= '<th style="border:1px solid black;">Date Time End</th>';
$html .= '<th style="border:1px solid black;">Quantity</th>';
$html .= '<th style="border:1px solid black;">Instruction</th>';
$html .= '<th style="border:1px solid black;">Delivery Date</th>';
$html .= '<th style="border:1px solid black;">Machine</th>';
$html .= '</tr>';

$result_sql = "SELECT JobEntry.jobentryid,JobProcess.process_priority,JobEntry.date_entry,JobEntry.release_date,JobEntry.jonumber,JobEntry.job_filename,JobEntry.job_quantity,JobEntry.job_remarks,JobProcess.process_id,
            ProcessList.process_name,JobProcess.process_sequence,JobProcess.date_time_start,JobProcess.date_time_end,JobProcess.process_status,ProcessList.process_division,JobEntry.customer_name,JobEntry.job_description,JobProcess.process_machine
            FROM bpi_perso_job_entry JobEntry
            INNER JOIN bpi_perso_job_process JobProcess
            ON JobProcess.jobentry_id = JobEntry.jobentryid
            INNER JOIN bpi_perso_process_list ProcessList
            ON ProcessList.processid = JobProcess.process_id
            WHERE ProcessList.process_section = :jobSection AND JobProcess.process_status <> 'Done' AND JobProcess.process_status <> 'Process Done' AND (ProcessList.process_category ISNULL OR ProcessList.process_category = '' OR ProcessList.process_category = 'For Kitting' OR ProcessList.process_category = 'For Print')
            GROUP BY JobEntry.jobentryid,JobProcess.process_priority,JobEntry.date_entry,JobEntry.jonumber,JobEntry.job_filename,JobEntry.job_quantity,JobEntry.job_remarks,JobProcess.process_id,ProcessList.process_name,JobProcess.process_sequence,JobProcess.date_time_start,
            JobProcess.date_time_end,JobProcess.process_status,ProcessList.process_division,JobProcess.process_machine
            ORDER BY JobProcess.process_priority, JobEntry.job_filename, JobProcess.process_sequence ASC";
$result_stmt = $perso->prepare($result_sql);
$result_stmt->bindParam(':jobSection', $jobSection);
$result_stmt->execute();

while ($row = $result_stmt->fetch(PDO::FETCH_ASSOC)) {
    $html .= '<tr>';
    $html .= '<td style="border:1px solid black; text-align:center;">' . $row['process_priority'] . '</td>';
    $html .= '<td style="border:1px solid black; text-align:center;">' . $row['date_entry'] . '</td>';
    $html .= '<td style="border:1px solid black;">' . $row['customer_name'] . '</td>';
    $html .= '<td style="border:1px solid black;">' . $row['jonumber'] . '</td>';
    $html .= '<td style="border:1px solid black;">' . $row['job_description'] . '</td>';
    $html .= '<td style="border:1px solid black;">' . $row['job_filename'] . '</td>';
    $html .= '<td style="border:1px solid black;">' . $row['process_name'] . '</td>';
    $html .= '<td style="border:1px solid black;">' . $row['process_status'] . '</td>';
    $html .= '<td style="border:1px solid black; text-align:center;">' . date_format(date_create($row['date_time_start']), 'Y/m/d h:i:s A') . '</td>';
    $html .= '<td style="border:1px solid black; text-align:center;">' . date_format(date_create($row['date_time_end']), 'Y/m/d h:i:s A') . '</td>';
    $html .= '<td style="border:1px solid black;">' . number_format($row['job_quantity']) . '</td>';
    $html .= '<td style="border:1px solid black;">' . $row['job_remarks'] . '</td>';
    $html .= '<td style="border:1px solid black; text-align:center;">' . $row['release_date'] . '</td>';
    $html .= '<td style="border:1px solid black; text-align:center;">' . $row['process_machine'] . '</td>';
    $html .= '</tr>';
}
$html .= '</table>';
header('Content-Type:application/vnd-ms-excel');
header('Content-Disposition:attachment;filename=Persomonitoring-' . $jobSection . '-' . date('Y-m-d h:i:s') . '.xls');
echo $html;
$perso = null; ## ======== Close Connection ========
