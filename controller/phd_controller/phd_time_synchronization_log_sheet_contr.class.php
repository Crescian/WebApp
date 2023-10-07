<?php
session_start();
if (isset($_POST['action'])) {
    include_once '../../configuration/connection.php';
    include_once '../../model/phd_model/phd_time_synchronization_log_sheet_model.class.php';
    $PHD = $conn->db_conn_physical_security(); //* Physical Security Database connection
    $BannerWebLive = $conn->db_conn_bannerweb(); //* Banner Web Database connection
    $timeSynchronization = new PhdTimeSynchronization();
    $action = trim($_POST['action']);
    date_default_timezone_set('Asia/Manila');
    $currentDate = date('Y-m-d');
    function fetchSignature($emp_name, $BannerWebLive)
    {
        $empSignature = "SELECT encode(employee_signature, 'escape') as employee_signature FROM bpi_employee_signature WHERE emp_name = '{$emp_name}'";
        $empSignature_stmt = $BannerWebLive->prepare($empSignature);
        $empSignature_stmt->execute();
        $result_REs = $empSignature_stmt->fetchAll();
        foreach($result_REs as $row) {
            return $row['employee_signature'];
        }
        $BannerWebLive = null; //* ======== Close Connection ========
    }

    switch ($action) {
        case 'load_time_sync_list_table':
            echo $timeSynchronization->fetchData($PHD, $php_fetch_phd_api);
            break;
        case 'load_time_synchronization_table_body':
            echo $timeSynchronization->loadDataTimeSynchronizationTableBody($PHD, $php_fetch_phd_api);
            break;
        case 'generate_surveillance_refno':
            $inField = trim($_POST['inField']);
            $inTable = trim($_POST['inTable']);
            echo $timeSynchronization->generateDataSurveillanceRefNo($PHD, $php_fetch_phd_api, $inField, $inTable);
            break;
        case 'save_timeSync_header':
            $timesync_header = 'Time Synchronization Monitoring as of ' . $currentDate;
            $date_created = $currentDate;
            $timesync_ref_no = trim($_POST['timesync_ref_no']);
            $perform_by = trim($_POST['perform_by']);
            $checked_by = trim($_POST['checked_by']);
            $noted_by = trim($_POST['noted_by']);
            //* ======== Fetch Employee Prepared Signature ========
            $performBySignature_row = fetchSignature($perform_by, $BannerWebLive);
            //* ======== Fetch Employee Checked Signature ========
            $checkedBySignature_row = fetchSignature($checked_by, $BannerWebLive);
            //* ======== Fetch Employee Noted Signature ========
            $NotedBySignature_row = fetchSignature($noted_by, $BannerWebLive);
            echo $timeSynchronization->saveDataHeader($PHD, $php_update_phd_api, $php_insert_phd_api, $BannerWebLive, $timesync_header, $date_created, $timesync_ref_no, $perform_by, $checked_by, $noted_by, $performBySignature_row, $checkedBySignature_row, $NotedBySignature_row, $currentDate);
            break;
        case 'save_timeSync_details':
            $timesyncheader_id = trim($_POST['timesyncheader_id']);
            $surveillance_name = trim($_POST['surveillance_name']);
            $real_time = date("Y-m-d " . trim($_POST['real_time']));
            $actual_time = date("Y-m-d " . trim($_POST['actual_time']));
            $time_gap = trim($_POST['time_gap']);
            $remarks = trim($_POST['remarks']);
            $date_created = $currentDate;
            $timesync_ref_no = trim($_POST['timesync_ref_no']);
            $logged_user = trim($_POST['logged_user']);
            echo $timeSynchronization->saveDataDetails($PHD, $logged_user, $timesyncheader_id, $surveillance_name, $real_time, $actual_time, $time_gap, $remarks, $date_created, $timesync_ref_no);
            break;
        case 'delete_timesync':
            $timesyncheaderid = trim($_POST['timesyncheaderid']);
            echo $timeSynchronization->deleteData($PHD, $php_update_phd_api, $timesyncheaderid);
            break;
        case 'preview':
            $timesyncheaderid = trim($_POST['timesyncheaderid']);
            echo $timeSynchronization->previewData($PHD, $php_fetch_phd_api, $timesyncheaderid);
            break;
        case 'update_timeSync_details':
            $time_id = trim($_POST['time_id']);
            $time_gap = trim($_POST['time_gap']);
            $remarks = trim($_POST['remarks']);
            $timesync_ref_no = trim($_POST['timesync_ref_no']);
            $date_created = $currentDate;
            $prepare = trim($_POST['prepare']);
            $logged_user = trim($_POST['logged_user']);
            if ($remarks == '') {
                $real_time = trim($_POST['real_time']);
                $actual_time = trim($_POST['actual_time']);
            } else {
                $real_time = date("Y-m-d " . trim($_POST['real_time']));
                $actual_time = date("Y-m-d " . trim($_POST['actual_time']));
            }
            echo $timeSynchronization->updateData($PHD, $php_update_phd_api, $logged_user, $prepare, $time_id, $real_time, $actual_time, $time_gap, $remarks, $date_created, $timesync_ref_no);
            break;
        case 'load-noted-by':
            $timesyncheaderid = trim($_POST['timesyncheaderid']);
            echo $timeSynchronization->loadDataBy($PHD, $php_fetch_phd_api, $timesyncheaderid);
            break;
        case 'preview_checked_by':
            $timesyncheaderid = trim($_POST['timesyncheaderid']);
            echo $timeSynchronization->previewDataCheckedBy($PHD, $php_fetch_phd_api, $timesyncheaderid);
            break;
    }
}
