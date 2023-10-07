<?php
session_start();
if (isset($_POST['action'])) {
    include_once '../../configuration/connection.php';
    include_once '../../model/phd_model/phd_monthly_monitoring_model.class.php';
    $PHD = $conn->db_conn_physical_security(); //* Physical Security Database connection
    $BannerWebLive = $conn->db_conn_bannerweb(); //* Banner Web Database connection
    $monthlyMonitoring = new PhdMonthlyMonitoring();
    $action = trim($_POST['action']);
    date_default_timezone_set('Asia/Manila');
    $currentDate = date('Y-m-d');

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
        case 'load_monthly_monitoring_list_table':
            echo $monthlyMonitoring->fetchData($PHD);
            break;
        case 'load_interlocking_rud_table':
            echo $monthlyMonitoring->loadDataInterlockingRudTable($PHD);
            break;
        case 'load_electric_fence_table':
            echo $monthlyMonitoring->loadDataElectricFenceTable($PHD);
            break;
        case 'load_emergency_eval_switch_table':
            echo $monthlyMonitoring->loadDataEmergenceEvalSwitchTable($PHD);
            break;
        case 'load_room_temp_table':
            echo $monthlyMonitoring->loadDataRoomTempTable($PHD);
            break;
        case 'save_monthly_monitoring_header':
            $performed_by = trim($_POST['performed_by']);
            $checked_by = trim($_POST['checked_by']);
            $noted_by = trim($_POST['noted_by']);
            $category = trim($_POST['category']);
            $monitoring_header = 'Monthly Monitoring as of ' . date('F Y');
            $date_created = date_format(date_create($currentDate), 'm-Y');
            //* ======== Fetch Employee Prepared Signature ========
            $performBySignature_row = fetchSignature($performed_by, $BannerWebLive);
            //* ======== Fetch Employee Prepared Signature ========
            $checkedBySignature_row = fetchSignature($checked_by, $BannerWebLive);
            //* ======== Fetch Employee Prepared Signature ========
            $NotedBySignature_row = fetchSignature($noted_by, $BannerWebLive);
            echo $monthlyMonitoring->saveDataMonthlyHeader($PHD, $performed_by, $checked_by, $noted_by, $category, $monitoring_header, $date_created, $performBySignature_row, $checkedBySignature_row, $NotedBySignature_row, $currentDate);
            break;
        case 'save_interlocking_rud':
            $interlock_category_name = trim($_POST['interlock_category_name']);
            $interlock_location_name = trim($_POST['interlock_location_name']);
            $interlock_status = trim($_POST['interlock_status']);
            $interlock_remarks = trim($_POST['interlock_remarks']);
            $monthlymonitoringid = trim($_POST['monthlymonitoringid']);
            $monitoring_ref_no = trim($_POST['monitoring_ref_no']);
            echo $monthlyMonitoring->saveDataInterlockingRudl($PHD, $interlock_category_name, $interlock_location_name, $interlock_status, $interlock_remarks, $monthlymonitoringid, $monitoring_ref_no, $currentDate);
            break;
        case 'delete_monthly_monitoring':
            $monthlymonitoringid = trim($_POST['monthlymonitoringid']);
            echo $monthlyMonitoring->deleteData($PHD, $monthlymonitoringid);
            break;
        case 'save_electric_fence_details':
            $electric_location_name = trim($_POST['electric_location_name']);
            $electric_status = trim($_POST['electric_status']);
            $electric_remarks = trim($_POST['electric_remarks']);
            $monthlymonitoringid = trim($_POST['monthlymonitoringid']);
            $monitoring_ref_no = trim($_POST['monitoring_ref_no']);
            echo $monthlyMonitoring->saveDataFenceDetails($PHD, $electric_location_name, $electric_status, $electric_remarks, $monthlymonitoringid, $monitoring_ref_no, $currentDate);
            break;
        case 'save_emergency_eval_details':
            $emergency_switch = trim($_POST['emergency_switch']);
            $emergency_status = trim($_POST['emergency_status']);
            $emergency_remarks = trim($_POST['emergency_remarks']);
            $monthlymonitoringid = trim($_POST['monthlymonitoringid']);
            $monitoring_ref_no = trim($_POST['monitoring_ref_no']);
            echo $monthlyMonitoring->saveDataEmergenceDetails($PHD, $emergency_switch, $emergency_status, $emergency_remarks, $monthlymonitoringid, $monitoring_ref_no, $currentDate);
            break;
        case 'save_roomtemp_details':
            $roomtemp_location_name = trim($_POST['roomtemp_location_name']);
            $roomtemp_reading1 = trim($_POST['roomtemp_reading1']);
            $roomtemp_reading2 = trim($_POST['roomtemp_reading2']);
            $roomtemp_temperature_alarm = trim($_POST['roomtemp_temperature_alarm']);
            $roomtemp_remarks = trim($_POST['roomtemp_remarks']);
            $monthlymonitoringid = trim($_POST['monthlymonitoringid']);
            $monitoring_ref_no = trim($_POST['monitoring_ref_no']);
            echo $monthlyMonitoring->saveDataRoomtempDetails($PHD, $roomtemp_location_name, $roomtemp_reading1, $roomtemp_reading2, $roomtemp_temperature_alarm, $roomtemp_remarks, $monthlymonitoringid, $monitoring_ref_no, $currentDate);
            break;
    }
}
