<?php
session_start();
if (isset($_POST['action'])) {
    include_once '../../configuration/connection.php';
    include_once '../../model/phd_model/phd_surveillance_log_sheet_model.class.php';
    $PHD = $conn->db_conn_physical_security(); //* Physical Security Database connection
    $BannerWebLive = $conn->db_conn_bannerweb(); //* Banner Web Database connection
    $surveillanceLog = new PhdSurveillanceLogShhet();
    $action = trim($_POST['action']);
    date_default_timezone_set('Asia/Manila');
    $date_created = date("Y-m-d");

    function fetchSignature($emp_name, $BannerWebLive)
    {
        $empSignature = "SELECT encode(employee_signature, 'escape') as employee_signature FROM bpi_employee_signature WHERE emp_name = :emp_name";
        $empSignature_stmt = $BannerWebLive->prepare($empSignature);
        $empSignature_stmt->bindParam(':emp_name', $emp_name);
        $empSignature_stmt->execute();
        $empSignature_row = $empSignature_stmt->fetch(PDO::FETCH_ASSOC);
        return $empSignature_row['employee_signature'];
        $BannerWebLive = null; //* ======== Close Connection ========
    }

    switch ($action) {
        case 'load_time_sync_list_table':
            echo $surveillanceLog->fetchDataTimeSync($PHD);
            break;
        case 'load_event_monitoring_list_table':
            echo $surveillanceLog->fetchDataEventMonitoring($PHD);
            break;
        case 'generate_surveillance_refno':
            $inTable = trim($_post['inTable']);
            $inField = trim($_post['inField']);
            echo $surveillanceLog->generateRefno($PHD, $inTable, $inField);
            break;
        case 'save_timeSync_header':
            $timesync_header = 'Time Synchronization Monitoring as of ' . $date_created;
            $perform_by = trim($_POST['perform_by']);
            $checked_by = trim($_POST['checked_by']);
            $noted_by = trim($_POST['noted_by']);
            $timesync_ref_no = trim($_POST['timesync_ref_no']);
            $performBySignature_row = fetchSignature($perform_by, $BannerWebLive);
            $checkedBySignature_row = fetchSignature($checked_by, $BannerWebLive);
            $NotedBySignature_row = fetchSignature($noted_by, $BannerWebLive);
            echo $surveillanceLog->saveDataTimeSyncHeader($PHD, $timesync_header, $perform_by, $checked_by, $noted_by, $timesync_ref_no, $performBySignature_row, $checkedBySignature_row, $NotedBySignature_row, $date_created);
            break;
        case 'save_timeSync_details':
            $timesyncheader_id = trim($_POST['timesyncheader_id']);
            $surveillance_name = trim($_POST['surveillance_name']);
            $real_time = date("Y-m-d " . trim($_POST['real_time']));
            $actual_time = date("Y-m-d " . trim($_POST['actual_time']));
            $time_gap = trim($_POST['time_gap']);
            $remarks = trim($_POST['remarks']);
            $timesync_ref_no = trim($_POST['timesync_ref_no']);
            echo $surveillanceLog->saveDataTimeSyncDetails($PHD, $timesyncheader_id, $surveillance_name, $real_time, $actual_time, $time_gap, $remarks, $timesync_ref_no, $date_created);
            break;
        case 'delete_timesync':
            $timesyncheaderid = trim($_POST['timesyncheaderid']);
            echo $surveillanceLog->deleteDataTimeSync($PHD, $timesyncheaderid);
            break;
        case 'load_time_synchronization_table_body':
            echo $surveillanceLog->loadTimeSynchronizationTable($PHD);
            break;
        case 'load_event_monitoring_table_body':
            echo $surveillanceLog->loadEventMonitoringTableBody($PHD);
            break;
        case 'save_event_header':
            $event_header = 'Surveillance Event Monitoring as of ' . $date_created;
            $event_ref_no = trim($_POST['event_ref_no']);
            $prepared_by = trim($_POST['prepared_by']);
            $noted_by = trim($_POST['noted_by']);
            //* ======== Fetch Employee Prepared Signature ========
            $preparedBySignature = fetchSignature($prepared_by, $BannerWebLive);
            //* ======== Fetch Employee Noted Signature ========
            $notedBySignature = fetchSignature($noted_by, $BannerWebLive);
            echo $surveillanceLog->saveDataEventHeader($PHD, $event_header, $event_ref_no, $prepared_by, $noted_by, $preparedBySignature, $notedBySignature, $date_created);
            break;
        case 'save_event_details':
            $eventheader_id = trim($_POST['eventheader_id']);
            $surveillance_name = trim($_POST['surveillance_name']);
            $event_time_start = trim($_POST['event_time_start']) == '' ? NULL : date("Y-m-d " . trim($_POST['event_time_start']));
            $event_time_end = trim($_POST['event_time_end']) == '' ? NULL : date("Y-m-d " . trim($_POST['event_time_end']));
            $event_date_from = trim($_POST['event_date_from']) == '' ? NULL : trim($_POST['event_date_from']);
            $event_date_to = trim($_POST['event_date_to']) == '' ? NULL : trim($_POST['event_date_to']);
            $event_total_days = trim($_POST['event_total_days']) == '' ? NULL : trim($_POST['event_total_days']);
            $event_min_days = trim($_POST['event_min_days']);
            $event_ref_no = trim($_POST['event_ref_no']);
            $event_comments = trim($_POST['event_comments']);
            if ($event_total_days == NULL) {
                $date_created = NULL;
                $prepared_by = NULL;
            } else {
                $date_created = $currentDate;
                $prepared_by = trim($_POST['prepared_by']);
            }
            echo $surveillanceLog->saveDataEventDetails($PHD, $eventheader_id, $surveillance_name, $event_time_start, $event_time_end, $event_date_from, $event_date_to, $event_total_days, $event_min_days, $event_ref_no, $event_comments, $prepared_by, $date_created);
            break;
        case 'delete_event_monitoring':
            $eventheaderid = trim($_POST['eventheaderid']);
            echo $surveillanceLog->deleteDataEventMonitoring($PHD, $eventheaderid);
            break;
    }
}
