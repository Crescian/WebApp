<?php
session_start();
if (isset($_POST['action'])) {
    include_once '../../configuration/connection.php';
    include_once '../../model/phd_model/phd_surveillance_event_log_sheet_model.class.php';
    $PHD = $conn->db_conn_physical_security(); //* Physical Security Database connection
    $BannerWebLive = $conn->db_conn_bannerweb(); //* Banner Web Database connection
    $surveillanceEvent = new PhdSurveillanceEvent();
    $action = trim($_POST['action']);
    date_default_timezone_set('Asia/Manila');
    $currentDate = date("Y-m-d");

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
        case 'load_event_monitoring_list_table':
            echo $surveillanceEvent->fetchData($PHD);
            break;
        case 'load_event_monitoring_table_body':
            echo $surveillanceEvent->loadDataMonitoringBody($PHD);
            break;
        case 'generate_surveillance_refno':
            $inField = trim($_POST['inField']);
            $inTable = trim($_POST['inTable']);
            echo $surveillanceEvent->generateDataSurveillanceRefno($PHD, $inField, $inTable);
            break;
        case 'save_event_header':
            $event_header = 'Surveillance Event Monitoring as of ' . $currentDate;
            $event_ref_no = trim($_POST['event_ref_no']);
            $prepared_by = trim($_POST['prepared_by']);
            $noted_by = trim($_POST['noted_by']);
            //* ======== Fetch Employee Prepared Signature ========
            $preparedBySignature = fetchSignature($prepared_by, $BannerWebLive);
            //* ======== Fetch Employee Noted Signature ========
            $notedBySignature = fetchSignature($noted_by, $BannerWebLive);
            echo $surveillanceEvent->saveDataHeader($PHD, $BannerWebLive, $currentDate, $event_header, $event_ref_no, $prepared_by, $noted_by, $preparedBySignature, $notedBySignature);
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
            echo $surveillanceEvent->saveDataDetails($PHD, $eventheader_id, $surveillance_name, $event_time_start, $event_time_end, $event_date_from, $event_date_to, $event_total_days, $event_min_days, $event_ref_no, $event_comments, $date_created, $prepared_by);
            break;
        case 'preview_employee_header':
            $eventheaderid = trim($_POST['eventheaderid']);
            echo $surveillanceEvent->previewDataEmployeeHeader($PHD, $eventheaderid);
            break;
        case 'preview_event_monitoring_table_body':
            $eventheaderid = trim($_POST['eventheaderid']);
            echo $surveillanceEvent->previewDataEventMonitoringTableBody($PHD, $eventheaderid);
            break;
        case 'update_event_header':
            $eventheaderid = trim($_POST['eventheaderid']);
            $prepared_by = trim($_POST['prepared_by']);
            $noted_by = trim($_POST['noted_by']);

            //* ======== Fetch Employee Perform Signature ========
            $preparedBySignature = fetchSignature($prepared_by, $BannerWebLive);
            //* ======== Fetch Employee Noted Signature ========
            $notedBySignature = fetchSignature($noted_by, $BannerWebLive);
            echo $surveillanceEvent->updateDataEventHeader($PHD, $currentDate, $eventheaderid, $prepared_by, $noted_by, $preparedBySignature, $notedBySignature);
            break;
        case 'update_event_details':
            $eventheader_id = trim($_POST['eventheader_id']);
            $surveillance_name = trim($_POST['surveillance_name']);
            $event_time_start = trim($_POST['event_time_start']) == '' ? NULL : date("Y-m-d " . trim($_POST['event_time_start']));
            $event_time_end = trim($_POST['event_time_end']) == '' ? NULL : date("Y-m-d " . trim($_POST['event_time_end']));
            $event_date_from = trim($_POST['event_date_from']) == '' ? NULL : trim($_POST['event_date_from']);
            $event_date_to = trim($_POST['event_date_to']) == '' ? NULL : trim($_POST['event_date_to']);
            $event_total_days = trim($_POST['event_total_days']) == '' ? NULL : trim($_POST['event_total_days']);
            $event_comments = trim($_POST['event_comments']);
            if ($event_total_days == NULL) {
                $date_created = NULL;
                $prepared_by = NULL;
            } else {
                $date_created = $currentDate;
                $prepared_by = trim($_POST['prepared_by']);
            }
            echo $surveillanceEvent->updateDataEventDetails($PHD, $eventheader_id, $surveillance_name, $event_time_start, $event_time_end, $event_date_from, $event_date_to, $event_total_days, $event_comments, $date_created, $prepared_by);
            break;
        case 'delete_event_monitoring':
            $eventheaderid = trim($_POST['eventheaderid']);
            echo $surveillanceEvent->deleteDataEventMonitoring($PHD, $eventheaderid);
            break;
    }
}
