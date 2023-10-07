<?php
session_start();
if (isset($_POST['action'])) {
    include_once '../../configuration/connection.php';
    include_once '../../model/phd_model/phd_monthly_fire_alarm_mba_model.class.php';
    $PHD = $conn->db_conn_physical_security(); //* Physical Security Database connection
    $BannerWebLive = $conn->db_conn_bannerweb(); //* Banner Web Database connection
    $mbaModule = new PhdFireAlarmMba();
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
    function scanExisting($inField, $firealarmid, $PHD)
    {
        $sqlstringScanMainPlant = "SELECT $inField FROM phd_monthly_fire_alarm_header WHERE firealarmid = :firealarmid";
        $result_stmt = $PHD->prepare($sqlstringScanMainPlant);
        $result_stmt->bindParam(':firealarmid', $firealarmid);
        $result_stmt->execute();
        $result_res = $result_stmt->fetch(PDO::FETCH_ASSOC);
        return $result_res;
    }

    switch ($action) {
        case 'load_fire_table':
            echo $mbaModule->fetchData($PHD);
            break;
        case 'load_cms':
            echo $mbaModule->fetchDataCms($BannerWebLive);
            break;
        case 'generate_checklist':
            echo $mbaModule->generateData($PHD);
            break;
        case 'preview_fire':
            $fireid = trim($_POST['fireid']);
            echo $mbaModule->previewData($PHD, $fireid);
            break;
        case 'save_header':
            $title = 'Monthly Fire Alarm System Checklist For The Month Of ' . date('F');
            $prepared_by = trim($_POST['prepared_by']);
            $checkedBy = trim($_POST['checkedBy']);
            $notedBy = trim($_POST['notedBy']);
            //* ======== Fetch Employee Prepared Signature ========
            $preparedBySignature = fetchSignature($prepared_by, $BannerWebLive);
            //* ======== Fetch Employee Checked Signature ========
            $checkedBySignature = fetchSignature($checkedBy, $BannerWebLive);
            //* ======== Fetch Employee Noted Signature ========
            $notedBySignature = fetchSignature($notedBy, $BannerWebLive);
            echo $mbaModule->saveDataHeader($PHD, $title, $prepared_by, $checkedBy, $notedBy, $preparedBySignature, $checkedBySignature, $notedBySignature, $date_created);
            break;
        case 'save_detail':
            $performedBy = trim($_POST['performedBy']);
            $strLocation = trim($_POST['strLocation']);
            $strCategory = trim($_POST['strCategory']);
            $strUnit = trim($_POST['strUnit']);
            $strbtnActivateUnits = trim($_POST['strbtnActivateUnits']) == '' ? NULL : trim($_POST['strbtnActivateUnits']);
            $strWorking = trim($_POST['strWorking']);
            $strRemark = trim($_POST['strRemark']);
            $fireid = trim($_POST['fireid']);
            $preparedBySignature = fetchSignature($performedBy, $BannerWebLive);
            echo $mbaModule->saveData($PHD, $performedBy, $strLocation, $strCategory, $strUnit, $strbtnActivateUnits, $strWorking, $strRemark, $fireid, $preparedBySignature);
            break;
        case 'update_header':
            $prepared_by = trim($_POST['prepared_by']);
            $fireid = trim($_POST['fireid']);
            $result_res_sign = fetchSignature($prepared_by, $BannerWebLive);
            $result_res2 = scanExisting('prepared_by2', $fireid, $PHD);
            $result_res3 = scanExisting('prepared_by3', $fireid, $PHD);
            echo $mbaModule->updateDataHeader($PHD, $prepared_by, $fireid, $result_res_sign, $result_res2, $result_res3, $date_created);
            break;
        case 'update_details':
            $detailsId = trim($_POST['detailsId']);
            $preparedBy = trim($_POST['preparedBy']);
            $strUnit = trim($_POST['strUnit']);
            $strWorking = trim($_POST['strWorking']);
            $strRemark = trim($_POST['strRemark']);
            $performedBy = trim($_POST['performedBy']);
            $preparedBySignature = fetchSignature($performedBy, $BannerWebLive);
            echo $mbaModule->updateData($PHD, $detailsId, $preparedBy, $strUnit, $strWorking, $strRemark, $performedBy, $preparedBySignature, $date_created);
            break;
        case 'delete_details':
            $fireid = trim($_POST['fireid']);
            echo $mbaModule->deleteData($PHD, $fireid);
            break;
        case 'preview_checked_by':
            $fireid = trim($_POST['fireid']);
            echo $mbaModule->previewDataCheckedBy($PHD, $fireid);
            break;
    }
}
